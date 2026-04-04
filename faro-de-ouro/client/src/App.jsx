import React, { useEffect, useState } from 'react';
import { HashRouter, Routes, Route, Navigate } from 'react-router-dom';
import api from './api';
import Landing from './pages/Landing';
import Login from './pages/Login';
import Register from './pages/Register';
import Dashboard from './pages/Dashboard';
import Products from './pages/Products';
import Kardex from './pages/Kardex';
import Suggestions from './pages/Suggestions';
import Users from './pages/Users';
import SuperAdmin from './pages/SuperAdmin';
import AdminPlans from './pages/AdminPlans';
import AdminSettings from './pages/AdminSettings';
import AdminCoupons from './pages/AdminCoupons';

import Subscribe from './pages/Subscribe';
import Categories from './pages/Categories';

const ProtectedRoute = ({ children, requireRole }) => {
  const [user, setUser] = useState(null);
  const [loading, setLoading] = useState(true);
  const [expired, setExpired] = useState(false);

  useEffect(() => {
    const token = localStorage.getItem('faro_token');
    if (!token) {
      setLoading(false);
      return;
    }

    api.get('auth&action=me')
      .then(res => {
        if (res.data.user.access_status === 'expired' && requireRole !== 'superadmin') {
          setExpired(true);
        } else {
          setUser(res.data.user);
        }
      })
      .catch(() => {
        localStorage.removeItem('faro_token');
      })
      .finally(() => setLoading(false));
  }, []);

  if (loading) return <div className="min-h-screen flex items-center justify-center bg-brand-gray-light">Carregando...</div>;
  if (!user && !expired) return <Navigate to="/login" />;
  if (expired) return (
    <div className="min-h-screen flex items-center justify-center bg-brand-gray-light">
       <div className="bg-white p-8 rounded-xl shadow-lg max-w-md text-center">
         <h2 className="text-2xl font-bold text-red-600 mb-4">Acesso Expirado</h2>
         <p className="mb-6 text-gray-600">Seu período de teste ou assinatura chegou ao fim. Assine um de nossos planos para continuar gerenciando seu estoque!</p>
         <button onClick={() => window.location.href = '#/subscribe'} className="bg-brand-orange text-white px-6 py-2 rounded-lg hover:bg-orange-600">Ver Planos</button>
         <div className="mt-4"><button onClick={() => { localStorage.removeItem('faro_token'); window.location.href = '#/login'; }} className="text-sm text-gray-500 underline">Sair</button></div>
       </div>
    </div>
  );

  if (requireRole && user.role !== requireRole) return <Navigate to="/app" />;

  return React.cloneElement(children, { user });
};

function App() {
  return (
    <HashRouter>
      <Routes>
        <Route path="/" element={<Landing />} />
        <Route path="/login" element={<Login />} />
        <Route path="/register" element={<Register />} />
        <Route path="/subscribe" element={<Subscribe />} />

        <Route path="/app" element={<ProtectedRoute><Dashboard /></ProtectedRoute>} />
        <Route path="/app/categories" element={<ProtectedRoute><Categories /></ProtectedRoute>} />
        <Route path="/app/products" element={<ProtectedRoute><Products /></ProtectedRoute>} />
        <Route path="/app/kardex" element={<ProtectedRoute><Kardex /></ProtectedRoute>} />
        <Route path="/app/suggestions" element={<ProtectedRoute><Suggestions /></ProtectedRoute>} />
        <Route path="/app/users" element={<ProtectedRoute requireRole="owner"><Users /></ProtectedRoute>} />

        <Route path="/admin" element={<ProtectedRoute requireRole="superadmin"><SuperAdmin /></ProtectedRoute>} />
        <Route path="/admin/plans" element={<ProtectedRoute requireRole="superadmin"><AdminPlans /></ProtectedRoute>} />
          <Route path="/admin/coupons" element={<ProtectedRoute requireRole="superadmin"><AdminCoupons /></ProtectedRoute>} />
          <Route path="/admin/settings" element={<ProtectedRoute requireRole="superadmin"><AdminSettings /></ProtectedRoute>} />
      </Routes>
    </HashRouter>
  );
}

export default App;
