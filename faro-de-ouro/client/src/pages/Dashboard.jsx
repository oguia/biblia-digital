import React, { useEffect, useState } from 'react';
import api from '../api';
import { Package, AlertTriangle } from 'lucide-react';
import { Link } from 'react-router-dom';
import Layout from '../components/Layout';

const Dashboard = ({ user }) => {
  const [stats, setStats] = useState({ total_products: 0, low_stock: 0 });

  useEffect(() => {
    api.get('products&action=dashboard').then(res => setStats(res.data));
  }, []);

  const getRemainingDays = () => {
    if (!user || !user.plan_expires_at) return 0;
    const expirationDate = new Date(user.plan_expires_at);
    const now = new Date();
    const diffTime = expirationDate - now;
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    return diffDays > 0 ? diffDays : 0;
  };

  const remainingDays = getRemainingDays();

  return (
    <Layout user={user}>
      {user?.role === 'owner' && remainingDays <= 7 && remainingDays > 0 && (
        <div className="bg-orange-100 border-l-4 border-brand-orange text-orange-900 p-4 mb-8 rounded-r-lg flex flex-col sm:flex-row justify-between items-center shadow-sm">
          <div className="flex items-center gap-3 mb-2 sm:mb-0">
            <AlertTriangle className="text-brand-orange w-6 h-6" />
            <p>Seu período de teste acaba em <span className="font-bold">{remainingDays} {remainingDays === 1 ? 'dia' : 'dias'}</span>. Assine para não perder o acesso!</p>
          </div>
          <Link to="/app/subscription" className="bg-brand-orange text-white px-4 py-2 rounded-lg font-bold hover:bg-orange-600 transition text-sm shadow-sm whitespace-nowrap">
            Ver Planos
          </Link>
        </div>
      )}

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
