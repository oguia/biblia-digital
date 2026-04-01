import { useState, useEffect } from 'react';
import api from '../api/api';
import type { Target } from '../types';
import { Search, Loader, Trash2, CheckCircle, PlayCircle, PauseCircle } from 'lucide-react';
import { useTranslation } from 'react-i18next';

const Targets = () => {
  const [targets, setTargets] = useState<Target[]>([]);
  const [searchQuery, setSearchQuery] = useState('');
  const [crawling, setCrawling] = useState(false);
  const [crawlResults, setCrawlResults] = useState<any[]>([]);
  const { t } = useTranslation();

  useEffect(() => {
    fetchTargets();
  }, []);

  const fetchTargets = async () => {
    try {
      const { data } = await api.get('/targets.php');
      setTargets(data.targets);
    } catch (err) {
      console.error(err);
    }
  };

  const runCrawler = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!searchQuery) return;
    setCrawling(true);
    setCrawlResults([]);
    try {
      const { data } = await api.post('/crawler.php', { query: searchQuery });
      setCrawlResults(data.results);
    } catch (err) {
      alert('Crawler failed');
    } finally {
      setCrawling(false);
    }
  };

  const addTarget = async (url: string, type: string = 'unknown') => {
    try {
      await api.post('/targets.php', { url, type });
      // Remove from crawl results
      setCrawlResults(crawlResults.filter(r => r.url !== url));
      fetchTargets();
    } catch (err) {
      alert('Failed to add target');
    }
  };

  const deleteTarget = async (id: number) => {
    if (!confirm('Are you sure?')) return;
    try {
      await api.delete(`/targets.php?id=${id}`);
      fetchTargets();
    } catch (err) {
        // ignore
    }
  };

  const toggleStatus = async (target: Target) => {
    const newStatus = target.status === 'active' ? 'pending' : 'active';
    try {
      await api.put(`/targets.php?id=${target.id}`, { status: newStatus });
      fetchTargets();
    } catch (err) {
      console.error(err);
      alert('Failed to update status');
    }
  };

  return (
    <div className="space-y-6">
      <h1 className="text-2xl font-bold text-gray-900">{t('targets.title')}</h1>

      {/* Crawler Interface */}
      <div className="rounded-lg bg-white p-6 shadow">
        <h2 className="text-lg font-medium text-gray-900 mb-4">{t('targets.find_new')}</h2>
        <form onSubmit={runCrawler} className="flex gap-4">
          <input
            type="text"
            placeholder="e.g. 'submit url' keyword"
            value={searchQuery}
            onChange={(e) => setSearchQuery(e.target.value)}
            className="flex-1 rounded border-gray-300 p-2 border focus:ring-blue-500 focus:border-blue-500 text-gray-900 bg-white"
          />
          <button
            type="submit"
            disabled={crawling}
            className="flex items-center rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700 disabled:bg-blue-400"
          >
            {crawling ? <Loader className="mr-2 h-4 w-4 animate-spin" /> : <Search className="mr-2 h-4 w-4" />}
            {t('targets.scan')}
          </button>
        </form>

        {crawlResults.length > 0 && (
          <div className="mt-6">
            <h3 className="text-md font-semibold text-gray-800 mb-2">{t('targets.potential')} ({crawlResults.length})</h3>
            <div className="max-h-60 overflow-y-auto border rounded divide-y">
              {crawlResults.map((res, idx) => (
                <div key={idx} className="flex items-center justify-between p-3 hover:bg-gray-50">
                  <div className="overflow-hidden">
                    <p className="text-sm font-medium text-blue-600 truncate">{res.title}</p>
                    <p className="text-xs text-gray-500 truncate">{res.url}</p>
                  </div>
                  <button
                    onClick={() => addTarget(res.url)}
                    className="ml-2 text-green-600 hover:text-green-800"
                  >
                    <CheckCircle className="h-5 w-5" />
                  </button>
                </div>
              ))}
            </div>
          </div>
        )}
      </div>

      {/* Existing Targets List */}
      <div className="rounded-lg bg-white shadow overflow-hidden">
        <div className="px-6 py-4 border-b">
          <h2 className="text-lg font-medium text-gray-900">{t('targets.managed')}</h2>
        </div>
        <table className="min-w-full divide-y divide-gray-200">
          <thead className="bg-gray-50">
            <tr>
              <th className="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">{t('targets.col_url')}</th>
              <th className="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">{t('targets.col_status')}</th>
              <th className="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">{t('targets.col_actions')}</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-gray-200 bg-white">
            {targets.map((target) => (
              <tr key={target.id}>
                <td className="whitespace-nowrap px-6 py-4 text-sm text-gray-900 max-w-xs truncate" title={target.url}>{target.url}</td>
                <td className="whitespace-nowrap px-6 py-4 text-sm">
                   <span className={`px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${target.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'}`}>
                      {target.status}
                   </span>
                </td>
                <td className="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                  <button
                    onClick={() => toggleStatus(target)}
                    className="text-blue-600 hover:text-blue-900 mr-4"
                    title={target.status === 'active' ? 'Pause' : 'Activate'}
                  >
                    {target.status === 'active' ? <PauseCircle className="h-4 w-4" /> : <PlayCircle className="h-4 w-4" />}
                  </button>
                  <button onClick={() => deleteTarget(target.id)} className="text-red-600 hover:text-red-900">
                    <Trash2 className="h-4 w-4" />
                  </button>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  );
};

export default Targets;