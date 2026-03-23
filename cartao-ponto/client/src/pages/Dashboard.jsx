import { useState, useEffect } from 'react';
import axios from 'axios';
import { Play, Pause, Square, Clock, DollarSign, Activity } from 'lucide-react';
import { format, differenceInSeconds } from 'date-fns';

export default function Dashboard() {
  const [activeTimer, setActiveTimer] = useState(null);
  const [projects, setProjects] = useState([]);
  const [selectedProject, setSelectedProject] = useState('');
  const [elapsedTime, setElapsedTime] = useState(0);
  const [stats, setStats] = useState({ total_seconds: 0, total_earnings: 0, today_seconds: 0, today_earnings: 0 });
  const [loading, setLoading] = useState(true);

  const loadData = async () => {
    try {
      const [timerRes, projRes, statsRes] = await Promise.all([
        axios.get('/time_entries.php?action=current'),
        axios.get('/projects.php'),
        axios.get('/stats.php')
      ]);

      setActiveTimer(timerRes.data.entry || null);
      if (timerRes.data.entry?.project_id) {
        setSelectedProject(timerRes.data.entry.project_id);
      }
      setProjects(projRes.data.projects || []);
      setStats(statsRes.data || {});
    } catch (err) {
      console.error(err);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    loadData();
  }, []);

  useEffect(() => {
    let interval;
    if (activeTimer) {
      // Usar a mesma timezone (UTC ou local) para start e now é crítico.
      // O banco envia no formato "YYYY-MM-DD HH:mm:ss" na timezone America/Sao_Paulo (agora)
      // Se apenas passarmos pra new Date(), dependendo do browser, ele pode adicionar horas.
      // Substituir ' ' por 'T' faz o parseISO assumir horário local.
      const startStr = activeTimer.start_time.replace(' ', 'T');
      const start = new Date(startStr);

      const pauseSeconds = parseInt(activeTimer.total_pause_seconds || 0, 10);

      // Imediatamente calcular o tempo inicial para não esperar 1 seg pelo primeiro render
      const calcTime = () => {
        if (activeTimer.status === 'running') {
          const now = new Date();
          const diff = differenceInSeconds(now, start) - pauseSeconds;
          setElapsedTime(diff > 0 ? diff : 0);
        } else if (activeTimer.status === 'paused') {
          const pauseStartStr = activeTimer.pause_start.replace(' ', 'T');
          const pauseStart = new Date(pauseStartStr);
          const diff = differenceInSeconds(pauseStart, start) - pauseSeconds;
          setElapsedTime(diff > 0 ? diff : 0);
        }
      };

      calcTime();
      interval = setInterval(calcTime, 1000);
    } else {
      setElapsedTime(0);
    }

    return () => clearInterval(interval);
  }, [activeTimer]);

  const handleStart = async () => {
    try {
      await axios.post('/time_entries.php?action=start', { project_id: selectedProject });
      loadData();
    } catch (err) {
      alert(err.response?.data?.error || 'Erro ao iniciar');
    }
  };

  const handlePauseResume = async () => {
    try {
      if (activeTimer.status === 'running') {
        await axios.post('/time_entries.php?action=pause');
      } else {
        await axios.post('/time_entries.php?action=resume');
      }
      loadData();
    } catch (err) {
      alert(err.response?.data?.error || 'Erro');
    }
  };

  const handleStop = async () => {
    try {
      await axios.post('/time_entries.php?action=stop');
      setActiveTimer(null);
      setSelectedProject('');
      loadData();
    } catch (err) {
      alert(err.response?.data?.error || 'Erro ao parar');
    }
  };

  const formatTime = (totalSeconds) => {
    const hours = Math.floor(totalSeconds / 3600);
    const minutes = Math.floor((totalSeconds % 3600) / 60);
    const seconds = totalSeconds % 60;
    return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
  };

  const formatCurrency = (val) => {
    return Number(val).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
  };

  // Calculate current earnings for the active timer
  const currentEarnings = () => {
    if (!activeTimer || !selectedProject) return 0;
    const proj = projects.find(p => p.id === parseInt(selectedProject));
    if (!proj) return 0;

    const hoursWorked = elapsedTime / 3600;
    let derivedHourly = 0;

    const amount = Number(proj.rate_amount);
    const expected = Number(proj.expected_hours) > 0 ? Number(proj.expected_hours) : 8;

    if (proj.rate_type === 'hourly') derivedHourly = amount;
    else if (proj.rate_type === 'daily') derivedHourly = amount / expected;
    else if (proj.rate_type === 'monthly') derivedHourly = amount / (expected > 8 ? expected : 220); // fallback for monthly

    return hoursWorked * derivedHourly;
  };

  if (loading) return <div className="p-8 text-center text-gray-500">Carregando painel...</div>;

  return (
    <div className="max-w-4xl mx-auto space-y-6">
      <h1 className="text-2xl font-bold text-gray-800">Painel de Controle</h1>

      {/* Timer Card */}
      <div className="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div className="p-6">
          <h2 className="text-lg font-semibold text-gray-700 mb-4 flex items-center">
            <Activity className="h-5 w-5 mr-2 text-blue-600" />
            Controle de Ponto Atual
          </h2>

          <div className="flex flex-col md:flex-row gap-6 items-center justify-between">
            {/* Project Select */}
            <div className="w-full md:w-1/3">
              <label className="block text-sm font-medium text-gray-600 mb-2">Trabalhando em:</label>
              <select
                className="w-full border-gray-300 rounded-md shadow-sm p-3 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 disabled:opacity-50"
                value={selectedProject}
                onChange={(e) => setSelectedProject(e.target.value)}
                disabled={activeTimer !== null}
              >
                <option value="">Sem Projeto Específico (Geral)</option>
                {projects.map(p => (
                  <option key={p.id} value={p.id}>{p.name} - {formatCurrency(p.rate_amount)}/{p.rate_type}</option>
                ))}
              </select>
            </div>

            {/* Timer Display */}
            <div className="text-center w-full md:w-1/3">
              <div className="text-5xl md:text-6xl font-mono font-bold tracking-tight text-gray-800 mb-2">
                {formatTime(elapsedTime)}
              </div>
              {activeTimer && selectedProject && currentEarnings() > 0 && (
                <div className="text-green-600 font-semibold text-lg flex justify-center items-center">
                  + {formatCurrency(currentEarnings())}
                </div>
              )}
            </div>

            {/* Controls */}
            <div className="flex w-full md:w-1/3 justify-center md:justify-end gap-3">
              {!activeTimer ? (
                <button
                  onClick={handleStart}
                  className="flex-1 md:flex-none flex items-center justify-center bg-green-600 hover:bg-green-700 text-white px-6 py-4 rounded-xl font-bold shadow-md transition-colors"
                >
                  <Play className="h-6 w-6 mr-2" fill="currentColor" /> INICIAR
                </button>
              ) : (
                <>
                  <button
                    onClick={handlePauseResume}
                    className={`flex-1 md:flex-none flex items-center justify-center px-6 py-4 rounded-xl font-bold shadow-md transition-colors ${
                      activeTimer.status === 'running'
                        ? 'bg-yellow-500 hover:bg-yellow-600 text-white'
                        : 'bg-green-500 hover:bg-green-600 text-white'
                    }`}
                  >
                    {activeTimer.status === 'running' ? (
                      <><Pause className="h-6 w-6 mr-2" fill="currentColor" /> PAUSAR</>
                    ) : (
                      <><Play className="h-6 w-6 mr-2" fill="currentColor" /> RETOMAR</>
                    )}
                  </button>
                  <button
                    onClick={handleStop}
                    className="flex-1 md:flex-none flex items-center justify-center bg-red-600 hover:bg-red-700 text-white px-6 py-4 rounded-xl font-bold shadow-md transition-colors"
                  >
                    <Square className="h-6 w-6 mr-2" fill="currentColor" /> PARAR
                  </button>
                </>
              )}
            </div>
          </div>
        </div>
        {activeTimer?.status === 'paused' && (
          <div className="bg-yellow-50 text-yellow-800 px-6 py-3 text-sm font-medium text-center border-t border-yellow-100">
            Trabalho pausado no momento. Lembre-se de retomar quando voltar.
          </div>
        )}
      </div>

      {/* Stats Grid */}
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div className="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
          <div className="flex items-center text-gray-500 mb-2">
            <Clock className="h-5 w-5 mr-2" />
            <h3 className="font-medium">Horas Hoje</h3>
          </div>
          <p className="text-2xl font-bold text-gray-800">{formatTime(stats.today_seconds)}</p>
        </div>

        <div className="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
          <div className="flex items-center text-green-600 mb-2">
            <DollarSign className="h-5 w-5 mr-2" />
            <h3 className="font-medium">Ganhos Hoje</h3>
          </div>
          <p className="text-2xl font-bold text-gray-800">{formatCurrency(stats.today_earnings)}</p>
        </div>

        <div className="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
          <div className="flex items-center text-gray-500 mb-2">
            <Clock className="h-5 w-5 mr-2" />
            <h3 className="font-medium">Total de Horas</h3>
          </div>
          <p className="text-2xl font-bold text-gray-800">{formatTime(stats.total_seconds)}</p>
        </div>

        <div className="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
          <div className="flex items-center text-green-600 mb-2">
            <DollarSign className="h-5 w-5 mr-2" />
            <h3 className="font-medium">Ganhos Totais</h3>
          </div>
          <p className="text-2xl font-bold text-gray-800">{formatCurrency(stats.total_earnings)}</p>
        </div>
      </div>
    </div>
  );
}
