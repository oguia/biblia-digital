import React, { useEffect, useState } from 'react';
import Layout from '../components/Layout';
import api from '../api';
import { format } from 'date-fns';
import { useNavigate } from 'react-router-dom';

const SuperAdmin = ({ user }) => {
  const navigate = useNavigate();
  const [tenants, setTenants] = useState([]);
  const [suggestions, setSuggestions] = useState([]);

  useEffect(() => {
    api.get('superadmin&action=tenants').then(res => setTenants(res.data));
    api.get('superadmin&action=suggestions').then(res => setSuggestions(res.data));
  }, []);

  const grantAccess = async (id, days) => {
    if(confirm(`Estender acesso por ${days} dias?`)) {
      await api.post('superadmin&action=grant_access', { tenant_id: id, days });
      api.get('superadmin&action=tenants').then(res => setTenants(res.data));
    }
  };

  return (
    <Layout user={user}>
      <h1 className="text-3xl font-bold text-brand-dark mb-8">Painel do Administrador</h1>

      <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div className="bg-white rounded-xl shadow-sm overflow-hidden p-6">
          <h2 className="text-xl font-bold mb-4">Empresas Cadastradas</h2>
          <div className="overflow-x-auto">
            <table className="min-w-full divide-y divide-gray-200">
              <thead>
                <tr>
                  <th className="px-4 py-2 text-left text-xs text-gray-500 uppercase">Nome / Doc</th>
                  <th className="px-4 py-2 text-left text-xs text-gray-500 uppercase">Status</th>
                  <th className="px-4 py-2 text-right text-xs text-gray-500 uppercase">Ações</th>
                </tr>
              </thead>
              <tbody>
                {tenants.map(t => {
                  const isActive = new Date() < new Date(t.plan_expires_at || t.trial_ends_at);
                  return (
                    <tr key={t.id} className="border-t">
                      <td className="px-4 py-2 text-sm">
                        <div className="font-bold">{t.name}</div>
                        <div className="text-gray-500 text-xs">{t.type} - {t.document}</div>
                      </td>
                      <td className="px-4 py-2 text-sm">
                        <span className={`px-2 py-1 text-xs rounded-full ${isActive ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}`}>
                          {isActive ? 'Ativo' : 'Expirado'}
                        </span>
                      </td>
                      <td className="px-4 py-2 text-right text-sm">
                         <button onClick={() => {
                            api.post('superadmin&action=login_as', { tenant_id: t.id }).then(r => {
                              localStorage.setItem('faro_token', r.data.token);
                              window.location.href = '#/app';
                              window.location.reload();
                            }).catch(e => alert('Nenhum owner encontrado'));
                         }} className="text-xs bg-brand-dark hover:bg-gray-800 px-2 py-1 rounded text-white mr-2">Acessar App</button>
                         <button onClick={() => grantAccess(t.id, 30)} className="text-xs bg-gray-100 hover:bg-gray-200 px-2 py-1 rounded text-gray-700">+30d</button>
                      </td>
                    </tr>
                  )
                })}
              </tbody>
            </table>
          </div>
        </div>

        <div className="bg-white rounded-xl shadow-sm overflow-hidden p-6">
          <h2 className="text-xl font-bold mb-4">Caixa de Sugestões</h2>
          <div className="space-y-4">
            {suggestions.map(s => (
              <div key={s.id} className="p-4 bg-gray-50 rounded-lg border">
                <div className="flex justify-between items-start mb-2">
                  <div>
                    <span className="font-bold text-sm text-gray-900">{s.user_name}</span>
                    <span className="text-xs text-gray-500 block">{s.user_email}</span>
                  </div>
                  <span className="text-xs text-gray-400">{format(new Date(s.created_at), 'dd/MM/yyyy HH:mm')}</span>
                </div>
                <p className="text-sm text-gray-700">{s.message}</p>
              </div>
            ))}
            {suggestions.length === 0 && <p className="text-gray-500 text-sm">Nenhuma sugestão recebida ainda.</p>}
          </div>
        </div>
      </div>
    </Layout>
  );
};

export default SuperAdmin;
