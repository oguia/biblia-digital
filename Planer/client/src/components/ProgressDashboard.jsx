import React from 'react';
import useSWR from 'swr';
import { fetchWithAuth } from '../lib/api';
import { Bar } from 'react-chartjs-2';
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  BarElement,
  Title,
  Tooltip,
  Legend,
} from 'chart.js';

ChartJS.register(
  CategoryScale,
  LinearScale,
  BarElement,
  Title,
  Tooltip,
  Legend
);

export default function ProgressDashboard() {
  const { data, error } = useSWR('/plans.php?action=list', fetchWithAuth);

  if (error) return <div className="text-red-500 text-sm">Erro ao carregar métricas.</div>;
  if (!data) return <div className="text-slate-500 text-sm">Carregando métricas...</div>;

  // Calculate stats
  const totalPlans = data.plans.length;

  // Collect all skills used
  const allSkills = [];
  data.plans.forEach(plan => {
    if (plan.skills) {
      allSkills.push(...plan.skills);
    }
  });

  const uniqueSkills = [...new Map(allSkills.map(item => [item['id'], item])).values()];
  const totalUniqueSkills = uniqueSkills.length;

  // Count skills by component
  const componentCounts = {};
  allSkills.forEach(skill => {
    const comp = skill.component || 'Diversos';
    componentCounts[comp] = (componentCounts[comp] || 0) + 1;
  });

  const chartData = {
    labels: Object.keys(componentCounts),
    datasets: [
      {
        label: 'Habilidades Trabalhadas',
        data: Object.values(componentCounts),
        backgroundColor: 'rgba(59, 130, 246, 0.8)', // highlight blue
        borderRadius: 4,
      },
    ],
  };

  const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: { display: false },
      title: { display: false }
    },
    scales: {
      y: { beginAtZero: true, ticks: { stepSize: 1 } }
    }
  };

  return (
    <div className="bg-white p-6 rounded-lg border border-slate-200 shadow-sm mt-8">
      <h3 className="text-lg font-bold text-navy-900 mb-6">Meu Progresso Pedagógico</h3>

      <div className="grid grid-cols-2 gap-4 mb-8">
        <div className="bg-slate-50 p-4 rounded-md border border-slate-100 text-center">
          <p className="text-3xl font-bold text-navy-900 mb-1">{totalPlans}</p>
          <p className="text-xs font-medium text-slate-500 uppercase tracking-wide">Aulas Planejadas</p>
        </div>
        <div className="bg-blue-50 p-4 rounded-md border border-blue-100 text-center">
          <p className="text-3xl font-bold text-highlight mb-1">{totalUniqueSkills}</p>
          <p className="text-xs font-medium text-blue-800 uppercase tracking-wide">Habilidades BNCC Trabalhadas</p>
        </div>
      </div>

      {totalUniqueSkills > 0 ? (
        <div>
           <p className="text-sm font-semibold text-slate-700 mb-4">Habilidades por Componente Curricular</p>
           <div className="h-64 w-full">
             <Bar options={chartOptions} data={chartData} />
           </div>
        </div>
      ) : (
        <div className="text-center p-6 bg-slate-50 rounded-md">
           <p className="text-slate-500 text-sm">Adicione habilidades BNCC aos seus planos para visualizar o gráfico de progresso.</p>
        </div>
      )}
    </div>
  );
}