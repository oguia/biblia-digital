import { useState, useEffect } from 'react';
import api from '../api/api';
import { Loader, ExternalLink, Check, X, RefreshCw } from 'lucide-react';
import { useTranslation } from 'react-i18next';

interface Task {
  project_id: number;
  project_url: string;
  keywords: string;
  base_description: string;
  target_id: number;
  target_url: string;
  type: string;
}

const Worker = () => {
  const [task, setTask] = useState<Task | null>(null);
  const [loading, setLoading] = useState(false);
  const [aiContent, setAiContent] = useState('');
  const [generating, setGenerating] = useState(false);
  const [liveUrl, setLiveUrl] = useState('');
  const { t } = useTranslation();

  useEffect(() => {
    fetchTask();
  }, []);

  const fetchTask = async () => {
    setLoading(true);
    setAiContent('');
    setLiveUrl('');
    try {
      const { data } = await api.get('/queue.php');
      setTask(data.task);
    } catch (err) {
      console.error(err);
    } finally {
      setLoading(false);
    }
  };

  const generateContent = async () => {
    if (!task) return;
    setGenerating(true);
    try {
      const prompt = `Write a short, engaging description (max 150 chars) for a link submission.
      URL: ${task.project_url}.
      Keywords: ${task.keywords}.
      Context: ${task.base_description}.
      Make it natural.`;

      const { data } = await api.post('/gemini.php', { prompt });
      setAiContent(data.content);
    } catch (err) {
      alert('AI Generation Failed');
    } finally {
      setGenerating(false);
    }
  };

  const submitTask = async (status: 'completed' | 'failed') => {
    if (!task) return;
    try {
      await api.post('/submissions.php', {
        project_id: task.project_id,
        target_id: task.target_id,
        status,
        live_url: liveUrl,
        ai_content: aiContent
      });
      fetchTask(); // Get next
    } catch (err) {
      alert('Failed to submit');
    }
  };

  if (loading) return <div className="flex justify-center p-10"><Loader className="animate-spin text-blue-600" /></div>;
  if (!task) return <div className="text-center p-10 text-gray-600">{t('worker.no_tasks')}</div>;

  return (
    <div className="flex h-[calc(100vh-100px)] gap-6">
      {/* Left: Task Info & Tools */}
      <div className="w-1/3 flex flex-col gap-6 overflow-y-auto pr-2">
        <div className="bg-white p-4 rounded shadow">
          <h3 className="font-bold text-gray-700 mb-2">{t('worker.target_site')}</h3>
          <div className="flex items-center justify-between bg-gray-50 p-2 rounded">
             <span className="truncate text-sm text-blue-600 font-mono select-all">{task.target_url}</span>
             <a href={task.target_url} target="_blank" rel="noopener noreferrer" className="text-gray-500 hover:text-blue-600">
                <ExternalLink className="w-4 h-4" />
             </a>
          </div>
          <p className="text-xs text-gray-500 mt-1">Type: {task.type}</p>
        </div>

        <div className="bg-white p-4 rounded shadow">
          <h3 className="font-bold text-gray-700 mb-2">{t('worker.project_info')}</h3>
          <p className="text-sm font-semibold text-gray-900">{t('worker.label_url')}</p>
          <div className="bg-gray-50 p-2 rounded text-sm text-green-600 mb-2 select-all break-all">{task.project_url}</div>

          <p className="text-sm font-semibold text-gray-900">{t('worker.label_keywords')}</p>
          <div className="bg-gray-50 p-2 rounded text-sm text-gray-800 mb-2 select-all">{task.keywords}</div>

          <p className="text-sm font-semibold text-gray-900">{t('worker.label_desc')}</p>
          <div className="bg-gray-50 p-2 rounded text-sm text-gray-600">{task.base_description}</div>
        </div>

        <div className="bg-white p-4 rounded shadow flex-1">
          <div className="flex justify-between items-center mb-2">
             <h3 className="font-bold text-gray-700">{t('worker.ai_content')}</h3>
             <button
               onClick={generateContent}
               disabled={generating}
               className="text-xs bg-purple-100 text-purple-700 px-2 py-1 rounded hover:bg-purple-200 flex items-center"
             >
               {generating ? <Loader className="w-3 h-3 animate-spin mr-1" /> : <RefreshCw className="w-3 h-3 mr-1" />}
               {t('worker.generate')}
             </button>
          </div>
          <textarea
            className="w-full h-32 p-2 border rounded text-sm bg-gray-50 focus:ring-purple-500 focus:border-purple-500 text-gray-900"
            value={aiContent}
            onChange={(e) => setAiContent(e.target.value)}
            placeholder={t('worker.placeholder_ai')}
          />
        </div>

        <div className="bg-white p-4 rounded shadow">
          <h3 className="font-bold text-gray-700 mb-2">{t('worker.completion')}</h3>
          <input
            type="text"
            placeholder={t('worker.live_link')}
            className="w-full p-2 border rounded mb-3 text-sm text-gray-900"
            value={liveUrl}
            onChange={(e) => setLiveUrl(e.target.value)}
          />
          <div className="flex gap-2">
             <button onClick={() => submitTask('failed')} className="flex-1 bg-red-100 text-red-700 py-2 rounded hover:bg-red-200 flex justify-center items-center">
                <X className="w-4 h-4 mr-2" /> {t('worker.btn_skip')}
             </button>
             <button onClick={() => submitTask('completed')} className="flex-1 bg-green-600 text-white py-2 rounded hover:bg-green-700 flex justify-center items-center">
                <Check className="w-4 h-4 mr-2" /> {t('worker.btn_complete')}
             </button>
          </div>
        </div>
      </div>

      {/* Right: Browser View (iframe if allowed, otherwise placeholder) */}
      <div className="flex-1 bg-white rounded shadow border overflow-hidden relative">
        <iframe
          src={task.target_url}
          className="w-full h-full"
          title="Target Site"
          sandbox="allow-same-origin allow-scripts allow-forms allow-popups"
        />
        <div className="absolute top-0 left-0 w-full bg-yellow-100 text-yellow-800 text-xs p-1 text-center opacity-75 hover:opacity-100">
           {t('worker.note_iframe')}
        </div>
      </div>
    </div>
  );
};

export default Worker;