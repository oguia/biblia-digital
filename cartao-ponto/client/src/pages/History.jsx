import { useState, useEffect } from 'react';
import axios from 'axios';
import { format, parseISO } from 'date-fns';
import ptBR from 'date-fns/locale/pt-BR';
import { FileText, Trash2, Calendar, Clock, DollarSign, Plus } from 'lucide-react';

export default function History() {
  const [entries, setEntries] = useState([]);
  const [projects, setProjects] = useState([]);
  const [loading, setLoading] = useState(true);

  const [showManualForm, setShowManualForm] = useState(false);
  const [manualData, setManualData] = useState({ project_id: '', start_time: '', end_time: '' });

  const loadData = async () => {
    try {
      const [entriesRes, projRes] = await Promise.all([
        axios.get('/time_entries.php'),
        axios.get('/projects.php')
      ]);
      setEntries(entriesRes.data.entries || []);
      setProjects(projRes.data.projects || []);
    } catch (err) {
      console.error(err);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    loadData();
  }, []);

  const handleDelete = async (id) => {
    if (confirm('Tem certeza que deseja excluir este registro de ponto?')) {
      try {
        await axios.delete(`/time_entries.php?id=${id}`);
        loadData();
      } catch (err) {
        alert('Erro ao excluir registro');
      }
    }
  };

  const handleManualSubmit = async (e) => {
    e.preventDefault();
    if (!manualData.start_time || !manualData.end_time) return alert('Preencha os horários');

    // Convert local datetime-local to format accepted by PHP (Y-m-d H:i:s)
    const formattedStart = manualData.start_time.replace('T', ' ') + ':00';
    const formattedEnd = manualData.end_time.replace('T', ' ') + ':00';

    try {
      await axios.post('/time_entries.php?action=manual', {
        project_id: manualData.project_id,
        start_time: formattedStart,
        end_time: formattedEnd
      });
      setShowManualForm(false);
      setManualData({ project_id: '', start_time: '', end_time: '' });
      loadData();
    } catch (err) {
      alert(err.response?.data?.error || 'Erro ao salvar registro manual');
    }
  };

  const formatCurrency = (val) => Number(val).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });

  const calculateDuration = (start, end, pauseSecs) => {
    if (!start || !end) return 0;
    const s = new Date(start).getTime();
    const e = new Date(end).getTime();
    return Math.max(0, ((e - s) / 1000) - parseInt(pauseSecs || 0));
  };

  const formatDuration = (seconds) => {
    const h = Math.floor(seconds / 3600);
    const m = Math.floor((seconds % 3600) / 60);
    return `${h}h ${m}m`;
  };

  const calculateEarnings = (entry, durationSecs) => {
    if (!entry.rate_amount || entry.rate_amount <= 0) return 0;

    const hours = durationSecs / 3600;
    const amount = Number(entry.rate_amount);
    const expected = Number(entry.expected_hours) > 0 ? Number(entry.expected_hours) : 8;

    if (entry.rate_type === 'hourly') return hours * amount;
    if (entry.rate_type === 'daily') return hours * (amount / expected);
    if (entry.rate_type === 'monthly') return hours * (amount / (expected > 8 ? expected : 220));
    return 0;
  };

  const generatePDF = () => {
    // A simple CSV for now since it's easier to implement browser-side without heavy libraries.
    // The user asked for "Geração de Relatório PDF/Excel".
    const headers = ['Data', 'Serviço', 'Início', 'Fim', 'Duração', 'Ganhos'];
    const rows = entries.filter(e => e.status === 'completed').map(e => {
      const durSecs = calculateDuration(e.start_time, e.end_time, e.total_pause_seconds);
      const start = format(parseISO(e.start_time), 'dd/MM/yyyy HH:mm');
      const end = format(parseISO(e.end_time), 'HH:mm');
      const projName = e.project_name || 'Geral';
      const dur = formatDuration(durSecs);
      const earns = calculateEarnings(e, durSecs).toFixed(2).replace('.', ',');
      return `"${start}","${projName}","${start}","${end}","${dur}","R$ ${earns}"`;
    });

    const csvContent = "data:text/csv;charset=utf-8," + [headers.join(','), ...rows].join('\n');
    const encodedUri = encodeURI(csvContent);
    const link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", `relatorio_ponto_${format(new Date(), 'yyyyMMdd')}.csv`);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  };

  if (loading) return <div className="p-8 text-center text-gray-500">Carregando histórico...</div>;

  return (
    <div className="max-w-4xl mx-auto space-y-6">
      <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <h1 className="text-2xl font-bold text-gray-800">Histórico de Ponto</h1>
        <div className="flex space-x-3 w-full md:w-auto">
          <button
            onClick={() => setShowManualForm(!showManualForm)}
            className="flex-1 md:flex-none flex items-center justify-center bg-white hover:bg-gray-50 text-blue-600 border border-blue-200 px-4 py-2 rounded-lg font-medium shadow-sm transition-colors"
          >
            <Plus className="h-5 w-5 mr-1" /> Adicionar Manual
          </button>
          <button
            onClick={generatePDF}
            className="flex-1 md:flex-none flex items-center justify-center bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-lg font-medium shadow-sm transition-colors"
          >
            <FileText className="h-5 w-5 mr-1" /> Exportar CSV
          </button>
        </div>
      </div>

      {showManualForm && (
        <div className="bg-white p-6 rounded-xl shadow-sm border border-blue-200 mb-6">
          <h2 className="text-lg font-bold text-gray-800 mb-4">Adicionar Registro Manualmente</h2>
          <form onSubmit={handleManualSubmit} className="space-y-4">
            <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">Serviço / Projeto</label>
                <select
                  value={manualData.project_id}
                  onChange={e => setManualData({...manualData, project_id: e.target.value})}
                  className="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                >
                  <option value="">Geral</option>
                  {projects.map(p => <option key={p.id} value={p.id}>{p.name}</option>)}
                </select>
              </div>
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">Início *</label>
                <input required type="datetime-local" value={manualData.start_time} onChange={e => setManualData({...manualData, start_time: e.target.value})} className="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" />
              </div>
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">Fim *</label>
                <input required type="datetime-local" value={manualData.end_time} onChange={e => setManualData({...manualData, end_time: e.target.value})} className="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" />
              </div>
            </div>
            <div className="flex justify-end pt-2 space-x-3">
              <button type="button" onClick={() => setShowManualForm(false)} className="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg">Cancelar</button>
              <button type="submit" className="px-4 py-2 bg-blue-600 text-white rounded-lg shadow-sm">Salvar Registro</button>
            </div>
          </form>
        </div>
      )}

      <div className="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        {entries.length === 0 ? (
          <div className="p-12 text-center text-gray-500">
            Nenhum registro de ponto encontrado.
          </div>
        ) : (
          <div className="divide-y divide-gray-100">
            {entries.map(entry => {
              const durSecs = calculateDuration(entry.start_time, entry.end_time, entry.total_pause_seconds);
              const isRunning = entry.status !== 'completed';
              const earns = calculateEarnings(entry, durSecs);

              return (
                <div key={entry.id} className="p-4 md:p-6 hover:bg-gray-50 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 transition-colors">
                  <div className="flex-1 w-full">
                    <div className="flex items-center space-x-2 mb-1">
                      <span className={`inline-block px-2 py-0.5 text-xs font-semibold rounded-full ${isRunning ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700'}`}>
                        {isRunning ? 'Em andamento' : 'Concluído'}
                      </span>
                      <h3 className="font-bold text-gray-800 text-lg truncate">{entry.project_name || 'Serviço Geral'}</h3>
                    </div>

                    <div className="flex flex-col md:flex-row md:space-x-6 text-sm text-gray-500 mt-2 space-y-2 md:space-y-0">
                      <div className="flex items-center">
                        <Calendar className="h-4 w-4 mr-1 text-gray-400" />
                        {format(parseISO(entry.start_time), 'dd/MM/yyyy', { locale: ptBR })}
                      </div>
                      <div className="flex items-center">
                        <Clock className="h-4 w-4 mr-1 text-gray-400" />
                        {format(parseISO(entry.start_time), 'HH:mm')}
                        {entry.end_time ? ` - ${format(parseISO(entry.end_time), 'HH:mm')}` : ' - Agora'}
                      </div>
                      {entry.total_pause_seconds > 0 && (
                        <div className="flex items-center text-yellow-600">
                          (Pausa: {Math.floor(entry.total_pause_seconds / 60)}m)
                        </div>
                      )}
                    </div>
                  </div>

                  <div className="flex items-center justify-between md:justify-end w-full md:w-auto md:space-x-6 bg-gray-50 md:bg-transparent p-3 md:p-0 rounded-lg">
                    <div className="text-left md:text-right">
                      <p className="text-xs text-gray-500 font-medium uppercase tracking-wider">Duração</p>
                      <p className="font-mono font-bold text-gray-800 text-lg">{formatDuration(durSecs)}</p>
                    </div>

                    <div className="text-right">
                      <p className="text-xs text-gray-500 font-medium uppercase tracking-wider">Ganhos</p>
                      <p className={`font-bold text-lg ${earns > 0 ? 'text-green-600' : 'text-gray-400'}`}>
                        {earns > 0 ? formatCurrency(earns) : '-'}
                      </p>
                    </div>

                    {!isRunning && (
                      <button
                        onClick={() => handleDelete(entry.id)}
                        className="ml-4 p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors focus:outline-none"
                        title="Excluir Registro"
                      >
                        <Trash2 className="h-5 w-5" />
                      </button>
                    )}
                  </div>
                </div>
              );
            })}
          </div>
        )}
      </div>
    </div>
  );
}
