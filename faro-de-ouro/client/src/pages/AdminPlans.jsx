import React, { useEffect, useState } from 'react';
import Layout from '../components/Layout';
import api from '../api';

const AdminPlans = ({ user }) => {
  const [plans, setPlans] = useState([]);
  const [formData, setFormData] = useState({ name: '', duration_months: 1, price: '', mp_link: '' });

  const loadData = () => {
    api.get('superadmin&action=plans').then(res => setPlans(res.data));
  };

  useEffect(() => { loadData(); }, []);

  const handleSubmit = async (e) => {
    e.preventDefault();
    await api.post('superadmin&action=create_plan', formData);
    setFormData({ name: '', duration_months: 1, price: '', mp_link: '' });
    loadData();
  };

  return (
    <Layout user={user}>
      <h1 className="text-2xl font-bold text-brand-dark mb-6">Gerenciar Planos</h1>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div className="lg:col-span-1 bg-white rounded-xl shadow-sm p-6 self-start">
          <h2 className="text-lg font-bold mb-4">Novo Plano</h2>
          <form onSubmit={handleSubmit} className="space-y-4">
            <input type="text" required placeholder="Nome (ex: Mensal)" className="w-full px-3 py-2 border rounded focus:ring-brand-orange" value={formData.name} onChange={e => setFormData({...formData, name: e.target.value})} />
            <input type="number" required placeholder="Duração (Meses)" min="1" className="w-full px-3 py-2 border rounded focus:ring-brand-orange" value={formData.duration_months} onChange={e => setFormData({...formData, duration_months: e.target.value})} />
            <input type="number" step="0.01" required placeholder="Preço (R$)" className="w-full px-3 py-2 border rounded focus:ring-brand-orange" value={formData.price} onChange={e => setFormData({...formData, price: e.target.value})} />
            <input type="url" placeholder="Link Mercado Pago (Preapproval)" className="w-full px-3 py-2 border rounded focus:ring-brand-orange" value={formData.mp_link} onChange={e => setFormData({...formData, mp_link: e.target.value})} />
            <button type="submit" className="w-full bg-brand-orange text-white py-2 rounded hover:bg-orange-600 transition">Salvar Plano</button>
          </form>
        </div>

        <div className="lg:col-span-2 bg-white rounded-xl shadow-sm overflow-hidden p-6">
          <h2 className="text-lg font-bold mb-4">Planos Atuais</h2>
          <div className="overflow-x-auto">
            <table className="min-w-full divide-y divide-gray-200">
              <thead>
                <tr>
                  <th className="px-4 py-2 text-left text-xs text-gray-500 uppercase">Nome</th>
                  <th className="px-4 py-2 text-left text-xs text-gray-500 uppercase">Duração</th>
                  <th className="px-4 py-2 text-left text-xs text-gray-500 uppercase">Preço</th>
                  <th className="px-4 py-2 text-left text-xs text-gray-500 uppercase">Link MP</th>
                </tr>
              </thead>
              <tbody>
                {plans.map(p => (
                  <tr key={p.id} className="border-t">
                    <td className="px-4 py-2 text-sm font-bold">{p.name}</td>
                    <td className="px-4 py-2 text-sm">{p.duration_months} meses</td>
                    <td className="px-4 py-2 text-sm text-brand-orange font-bold">R$ {Number(p.price).toFixed(2)}</td>
                    <td className="px-4 py-2 text-sm text-gray-500 truncate max-w-xs">{p.mp_link || '-'}</td>
                  </tr>
                ))}
                {plans.length === 0 && <tr><td colSpan="4" className="px-4 py-4 text-center text-gray-500">Nenhum plano cadastrado.</td></tr>}
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </Layout>
  );
};

export default AdminPlans;
