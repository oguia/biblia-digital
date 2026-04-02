import React, { useState, useEffect } from 'react';
import { ArrowLeft, Users, UserPlus, Shield, Info } from 'lucide-react';

export default function Caregivers({ setView }) {
  const [members, setMembers] = useState([]);
  const [loading, setLoading] = useState(true);
  const [form, setForm] = useState({ name: '', email: '' });
  const [error, setError] = useState('');
  const [message, setMessage] = useState('');

  const token = localStorage.getItem('vouzelar_token');
  const user = JSON.parse(localStorage.getItem('vouzelar_user') || '{}');

  useEffect(() => {
    fetchMembers();
  }, []);

  const fetchMembers = async () => {
    setLoading(true);
    try {
      const baseUrl = window.location.href.includes('localhost') ? 'http://localhost:8000' : 'api';
      const res = await fetch(`${baseUrl}/caregiver.php?action=family_members`, {
        headers: { 'Authorization': `Bearer ${token}` }
      });
      if (res.ok) {
        const data = await res.json();
        setMembers(data);
      }
    } catch (err) {
      console.error(err);
    } finally {
      setLoading(false);
    }
  };

  const handleAddMember = async (e) => {
    e.preventDefault();
    setError('');
    setMessage('');

    try {
      const baseUrl = window.location.href.includes('localhost') ? 'http://localhost:8000' : 'api';
      const res = await fetch(`${baseUrl}/caregiver.php?action=family_members`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Authorization': `Bearer ${token}` },
        body: JSON.stringify(form)
      });
      const data = await res.json();

      if (!res.ok) throw new Error(data.error || 'Erro ao adicionar cuidador.');

      setMessage(data.message);
      setForm({ name: '', email: '' });
      fetchMembers();
    } catch (err) {
      setError(err.message);
    }
  };

  const isFamilyPlan = user.plan === 'family' || user.role === 'superadmin';

  return (
    <div className="min-h-screen bg-gray-50 pb-20">
      <header className="bg-white text-gray-900 p-4 shadow-sm flex items-center gap-3 sticky top-0 z-10">
        <button onClick={() => setView('dashboard')} className="p-2 hover:bg-gray-100 rounded-full">
          <ArrowLeft size={20} />
        </button>
        <h1 className="text-xl font-bold">Rede de Apoio</h1>
      </header>

      <main className="p-4 max-w-lg mx-auto space-y-6 mt-4">

        <div className="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 text-center">
           <Users className="w-12 h-12 text-primary-600 mx-auto mb-3" />
           <h2 className="text-lg font-bold text-gray-900">Cuidadores da Família</h2>
           <p className="text-sm text-gray-600 mt-2">
             Adicione irmãos, enfermeiros ou parentes para dividirem os cuidados.
             Eles terão acesso ao estoque e receberão alertas.
           </p>
        </div>

        {!isFamilyPlan && (
          <div className="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-xl shadow-sm">
            <div className="flex items-start">
              <Info className="h-5 w-5 text-yellow-600 flex-shrink-0" />
              <div className="ml-3">
                <p className="text-sm text-yellow-800 font-medium">Recurso Premium</p>
                <p className="text-sm text-yellow-700 mt-1">
                  Seu plano atual não permite adicionar cuidadores extras.
                  Faça upgrade para o Plano Família para usar a Rede de Apoio.
                </p>
                <button onClick={() => setView('profile')} className="mt-2 text-sm font-bold text-yellow-900 underline">Fazer Upgrade</button>
              </div>
            </div>
          </div>
        )}

        {isFamilyPlan && (
          <div className="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
            <h3 className="font-bold text-gray-900 mb-4 flex items-center gap-2">
              <UserPlus size={18} className="text-primary-600"/> Adicionar Cuidador
            </h3>

            {message && <div className="mb-4 bg-green-50 text-green-700 p-3 rounded-lg text-sm">{message}</div>}
            {error && <div className="mb-4 bg-red-50 text-red-600 p-3 rounded-lg text-sm">{error}</div>}

            <form onSubmit={handleAddMember} className="space-y-3">
              <input
                type="text"
                placeholder="Nome do Cuidador"
                required
                className="w-full border border-gray-300 p-3 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-500"
                value={form.name}
                onChange={e => setForm({...form, name: e.target.value})}
              />
              <input
                type="email"
                placeholder="E-mail de Login"
                required
                className="w-full border border-gray-300 p-3 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-500"
                value={form.email}
                onChange={e => setForm({...form, email: e.target.value})}
              />
              <button
                type="submit"
                disabled={members.length >= 5}
                className="w-full bg-primary-600 text-white p-3 rounded-xl font-bold mt-2 hover:bg-primary-700 disabled:opacity-50 transition-colors"
              >
                Enviar Convite
              </button>
              {members.length >= 5 && <p className="text-xs text-center text-red-500 mt-2">Limite de 5 cuidadores atingido.</p>}
            </form>
          </div>
        )}

        <div className="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
           <h3 className="font-bold text-gray-900 mb-4">Membros da Rede ({members.length}/5)</h3>

           {loading ? (
             <p className="text-center text-gray-500 py-4">Carregando...</p>
           ) : (
             <div className="space-y-3">
               {members.map(member => (
                 <div key={member.id} className="flex items-center justify-between p-3 bg-gray-50 rounded-xl border border-gray-100">
                   <div>
                     <p className="font-bold text-gray-900 flex items-center gap-1">
                       {member.name}
                       {member.role === 'admin' && <Shield size={14} className="text-yellow-500" />}
                     </p>
                     <p className="text-xs text-gray-500">{member.email}</p>
                   </div>
                   <span className="text-xs px-2 py-1 bg-gray-200 text-gray-700 rounded-md capitalize">
                     {member.role === 'admin' ? 'Administrador' : 'Cuidador'}
                   </span>
                 </div>
               ))}
             </div>
           )}
        </div>

      </main>
    </div>
  );
}
