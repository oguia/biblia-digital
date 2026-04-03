import React, { useEffect, useState } from 'react';
import api from '../api';
import { Package, AlertTriangle } from 'lucide-react';
import Layout from '../components/Layout';

const Dashboard = ({ user }) => {
  const [stats, setStats] = useState({ total_products: 0, low_stock: 0 });

  useEffect(() => {
    api.get('products&action=dashboard').then(res => setStats(res.data));
  }, []);

  return (
    <Layout user={user}>
      <h1 className="text-3xl font-bold text-brand-dark mb-8">Dashboard</h1>

      <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div className="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center gap-4">
          <div className="p-4 bg-gray-100 rounded-lg text-gray-600">
            <Package size={32} />
          </div>
          <div>
            <p className="text-sm text-gray-500 font-medium">Total de Produtos</p>
            <p className="text-3xl font-bold text-brand-dark">{stats.total_products}</p>
          </div>
        </div>

        <div className="bg-white p-6 rounded-xl shadow-sm border border-orange-100 flex items-center gap-4">
          <div className="p-4 bg-orange-100 rounded-lg text-brand-orange">
            <AlertTriangle size={32} />
          </div>
          <div>
            <p className="text-sm text-gray-500 font-medium">Estoque Baixo</p>
            <p className="text-3xl font-bold text-brand-orange">{stats.low_stock}</p>
          </div>
        </div>
      </div>
    </Layout>
  );
};

export default Dashboard;
