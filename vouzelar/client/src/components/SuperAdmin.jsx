import React, { useState, useEffect } from 'react';
import { useLocation } from 'wouter';
import { ArrowLeft, TrendingUp, Users, Activity, Pill, Gift, Copy } from 'lucide-react';

export default function SuperAdmin({ setView }) {
  const [, setLocation] = useLocation();
  const [stats, setStats] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');

  const token = localStorage.getItem('vouzelar_token');

  useEffect(() => {
    fetchStats();
  }, []);

  const fetchStats = async () => {
    try {
      const baseUrl = window.location.href.includes('localhost') ? 'http://localhost:8000' : 'api';
      const res = await fetch(`${baseUrl}/superadmin.php`, {
        headers: { 'Authorization': `Bearer ${token}` }
      });
      const data = await res.json();

      if (!res.ok) throw new Error(data.error || 'Erro ao carregar painel admin');
      setStats(data);
    } catch (err) {
      setError(err.message);
    } finally {
      setLoading(false);
    }
  };

  const handleGenerateCode = async () => {
    try {
      const baseUrl = window.location.href.includes('localhost') ? 'http://localhost:8000' : 'api';
      const res = await fetch(`${baseUrl}/superadmin.php?action=generate_code`, {
        method: 'POST',
        headers: { 'Authorization': `Bearer ${token}` }
      });
      const data = await res.json();
      if (res.ok) {
        alert(`Código gerado: ${data.code}`);
        fetchStats(); // recarregar a lista de códigos
      } else {
        alert(data.error);
      }
    } catch (err) {
      alert(err.message);
    }
  };

  const copyToClipboard = (text) => {
    navigator.clipboard.writeText(text);
    alert('Código copiado!');
  };

  if (error) {
    return (
      <div className="min-h-screen bg-gray-50 p-8 flex flex-col items-center justify-center">
        <p className="text-red-500 mb-4">{error}</p>
        <button onClick={() => setView('dashboard')} className="text-primary-600 underline">Voltar</button>
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-gray-100 pb-20">
      <header className="bg-gray-900 text-white p-4 shadow-md flex items-center gap-3 sticky top-0 z-10">
        <button onClick={() => setView('dashboard')} className="p-2 hover:bg-gray-800 rounded-full">
          <ArrowLeft size={20} />
        </button>
        <h1 className="text-xl font-bold">Painel Super Admin</h1>
      </header>

      {loading || !stats ? (
        <p className="text-center p-8 text-gray-500">Carregando métricas globais...</p>
      ) : (
        <main className="p-4 max-w-4xl mx-auto space-y-6 mt-4">

          {/* Top KPIs */}
          <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div className="bg-white p-4 rounded-2xl shadow-sm border border-gray-200">
              <div className="text-gray-500 text-sm font-medium mb-1 flex items-center gap-2"><TrendingUp size={16}/> MRR Total</div>
              <div className="text-2xl font-bold text-green-600">R$ {stats.estimated_mrr.toFixed(2)}</div>
              <div className="text-xs text-gray-400 mt-1">Estimativa Mensal</div>
            </div>

            <div className="bg-white p-4 rounded-2xl shadow-sm border border-gray-200">
              <div className="text-gray-500 text-sm font-medium mb-1 flex items-center gap-2"><Users size={16}/> Usuários Totais</div>
              <div className="text-2xl font-bold text-gray-900">{stats.total_users}</div>
              <div className="text-xs text-gray-400 mt-1">Na plataforma</div>
            </div>

            <div className="bg-white p-4 rounded-2xl shadow-sm border border-gray-200">
              <div className="text-gray-500 text-sm font-medium mb-1 flex items-center gap-2"><Activity size={16}/> Pacientes</div>
              <div className="text-2xl font-bold text-gray-900">{stats.users_by_role.patient || 0}</div>
              <div className="text-xs text-gray-400 mt-1">Gerenciados</div>
            </div>

            <div className="bg-white p-4 rounded-2xl shadow-sm border border-gray-200">
              <div className="text-gray-500 text-sm font-medium mb-1 flex items-center gap-2"><Users size={16}/> Planos</div>
              <div className="flex justify-between mt-2">
                 <div className="text-center">
                    <div className="font-bold text-gray-800">{stats.users_by_plan.individual || 0}</div>
                    <div className="text-[10px] uppercase tracking-wide text-gray-500">Indiv.</div>
                 </div>
                 <div className="text-center">
                    <div className="font-bold text-primary-600">{stats.users_by_plan.family || 0}</div>
                    <div className="text-[10px] uppercase tracking-wide text-primary-600">Família</div>
                 </div>
              </div>
            </div>
          </div>

          {/* Top Medications */}
          <div className="bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
             <h2 className="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <Pill className="text-primary-500" /> Remédios Mais Cadastrados
             </h2>
             <p className="text-sm text-gray-500 mb-4">Utilize estes dados para buscar parcerias com farmácias no módulo de Afiliados.</p>

             {stats.top_meds.length === 0 ? (
               <p className="text-gray-400 text-center py-4">Nenhum remédio cadastrado ainda.</p>
             ) : (
               <div className="space-y-3">
                 {stats.top_meds.map((med, index) => (
                   <div key={index} className="flex justify-between items-center p-3 bg-gray-50 rounded-xl">
                      <div className="font-medium text-gray-800">{med.name}</div>
                      <div className="bg-primary-100 text-primary-800 px-3 py-1 rounded-full text-sm font-bold">
                        {med.tracking_count} usos
                      </div>
                   </div>
                 ))}
               </div>
             )}
          </div>

          {/* Invites */}
          <div className="bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
             <div className="flex justify-between items-center mb-4">
               <h2 className="text-lg font-bold text-gray-900 flex items-center gap-2">
                  <Gift className="text-primary-500" /> Gerador de Acessos
               </h2>
               <button onClick={handleGenerateCode} className="bg-primary-600 text-white px-4 py-2 rounded-xl text-sm font-bold hover:bg-primary-700 transition-colors shadow-sm">
                 + Gerar Código Vitalício
               </button>
             </div>
             <p className="text-sm text-gray-500 mb-6">Crie códigos únicos para dar acesso "Família Vitalício" aos usuários para testarem ou comprarem fora do sistema.</p>

             {(!stats.recent_codes || stats.recent_codes.length === 0) ? (
               <p className="text-gray-400 text-center py-4 bg-gray-50 rounded-xl border border-dashed">Nenhum código gerado.</p>
             ) : (
               <div className="grid grid-cols-1 md:grid-cols-2 gap-3">
                 {stats.recent_codes.map((codeObj, index) => (
                   <div key={index} className={`flex justify-between items-center p-4 rounded-xl border ${codeObj.is_used ? 'bg-gray-100 border-gray-200 opacity-60' : 'bg-green-50 border-green-200'}`}>
                      <div>
                        <div className={`text-xl font-black tracking-widest ${codeObj.is_used ? 'text-gray-500 line-through' : 'text-green-700'}`}>{codeObj.code}</div>
                        <div className="text-xs mt-1 text-gray-500 font-medium">
                          {codeObj.is_used ? 'Já Resgatado' : 'Disponível'}
                        </div>
                      </div>
                      {!codeObj.is_used && (
                        <button onClick={() => copyToClipboard(codeObj.code)} className="p-2 bg-white text-green-700 rounded-lg hover:bg-green-100 shadow-sm border border-green-200" title="Copiar">
                          <Copy size={20} />
                        </button>
                      )}
                   </div>
                 ))}
               </div>
             )}
          </div>

        </main>
      )}
    </div>
  );
}
