import React, { useState, useEffect } from 'react';
import Layout from '../components/Layout';
import api from '../api';

const AdminCoupons = ({ user }) => {
  const [coupons, setCoupons] = useState([]);
  const [code, setCode] = useState('');
  const [freeMonths, setFreeMonths] = useState(1);

  const fetchCoupons = () => {
    api.get('superadmin&action=coupons').then(res => setCoupons(res.data));
  };

  useEffect(() => {
    fetchCoupons();
  }, []);

  const handleCreateCoupon = async (e) => {
    e.preventDefault();
    try {
      await api.post('superadmin&action=create_coupon', { code: code.toUpperCase(), free_months: freeMonths });
      setCode('');
      setFreeMonths(1);
      fetchCoupons();
    } catch (err) {
      alert('Erro ao criar cupom. O código pode já existir.');
    }
  };

  return (
    <Layout user={user}>
      <h1 className="text-3xl font-bold text-brand-dark mb-8">Gerenciar Cupons e Convites</h1>

      <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div className="bg-white rounded-xl shadow-sm overflow-hidden p-6">
          <h2 className="text-xl font-bold mb-4">Novo Cupom</h2>
          <form onSubmit={handleCreateCoupon} className="space-y-4">
            <div>
              <label className="block text-sm font-medium text-gray-700">Código (ex: VITALICIO, TESTE30)</label>
              <input type="text" required value={code} onChange={e => setCode(e.target.value.toUpperCase())} className="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-brand-orange focus:border-brand-orange sm:text-sm" />
            </div>
            <div>
              <label className="block text-sm font-medium text-gray-700">Meses Gratuitos (999 para vitalício)</label>
              <input type="number" required min="1" value={freeMonths} onChange={e => setFreeMonths(e.target.value)} className="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-brand-orange focus:border-brand-orange sm:text-sm" />
            </div>
            <button type="submit" className="w-full bg-brand-orange text-white py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-orange">
              Criar Cupom
            </button>
          </form>
        </div>

        <div className="bg-white rounded-xl shadow-sm overflow-hidden p-6">
          <h2 className="text-xl font-bold mb-4">Cupons Ativos</h2>
          <div className="overflow-x-auto">
            <table className="min-w-full divide-y divide-gray-200">
              <thead>
                <tr>
                  <th className="px-4 py-2 text-left text-xs text-gray-500 uppercase">Código</th>
                  <th className="px-4 py-2 text-left text-xs text-gray-500 uppercase">Meses Free</th>
                  <th className="px-4 py-2 text-left text-xs text-gray-500 uppercase">Status</th>
                </tr>
              </thead>
              <tbody>
                {coupons.map(c => (
                  <tr key={c.id} className="border-t">
                    <td className="px-4 py-2 text-sm font-bold">{c.code}</td>
                    <td className="px-4 py-2 text-sm">{c.free_months >= 999 ? 'Vitalício' : c.free_months}</td>
                    <td className="px-4 py-2 text-sm text-green-600 font-bold">Ativo</td>
                  </tr>
                ))}
                {coupons.length === 0 && (
                  <tr><td colSpan="3" className="px-4 py-4 text-center text-gray-500">Nenhum cupom cadastrado.</td></tr>
                )}
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </Layout>
  );
};

export default AdminCoupons;
