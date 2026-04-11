import React, { useState, useEffect } from 'react';
import Layout from '../components/Layout';
import api from '../api';

const AdminSettings = ({ user }) => {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [mpToken, setMpToken] = useState('');
  const [loading, setLoading] = useState(false);

  useEffect(() => {
    api.get('superadmin&action=settings').then(res => {
      setEmail(res.data.email || '');
      setMpToken(res.data.mp_access_token || '');
    });
  }, []);

  const handleSaveCredentials = async (e) => {
    e.preventDefault();
    setLoading(true);
    try {
      await api.post('superadmin&action=update_credentials', { email, password });
      alert('Credenciais atualizadas com sucesso! (Se alterou a senha ou e-mail, pode precisar logar novamente).');
      if(password) setPassword('');
    } catch (err) {
      alert(err.response?.data?.error || 'Erro ao atualizar credenciais.');
    }
    setLoading(false);
  };

  const handleSaveMp = async (e) => {
    e.preventDefault();
    setLoading(true);
    try {
      await api.post('superadmin&action=update_settings', { mp_access_token: mpToken });
      alert('Configurações do Mercado Pago salvas.');
    } catch (err) {
      alert('Erro ao salvar configurações.');
    }
    setLoading(false);
  };

  return (
    <Layout user={user}>
      <h1 className="text-3xl font-bold text-brand-dark mb-8">Configurações Gerais</h1>

      <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div className="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
          <h2 className="text-xl font-bold text-brand-dark mb-4 border-b pb-2">Credenciais de Acesso (Admin)</h2>
          <form onSubmit={handleSaveCredentials} className="space-y-4">
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">E-mail Administrativo</label>
              <input type="email" required value={email} onChange={e => setEmail(e.target.value)} className="w-full px-4 py-2 border rounded-lg focus:ring-brand-orange" />
            </div>
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">Nova Senha (deixe em branco para não alterar)</label>
              <input type="password" value={password} onChange={e => setPassword(e.target.value)} className="w-full px-4 py-2 border rounded-lg focus:ring-brand-orange" placeholder="********" />
            </div>
            <button type="submit" disabled={loading} className="w-full bg-brand-dark text-white py-2 rounded-lg hover:bg-gray-800 transition">Atualizar Acesso</button>
          </form>
        </div>

        <div className="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
          <h2 className="text-xl font-bold text-brand-dark mb-4 border-b pb-2">Integração Mercado Pago</h2>
          <form onSubmit={handleSaveMp} className="space-y-4">
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">Access Token (Produção)</label>
              <input type="text" required value={mpToken} onChange={e => setMpToken(e.target.value)} className="w-full px-4 py-2 border rounded-lg focus:ring-brand-orange" placeholder="APP_USR-..." />
              <p className="text-xs text-gray-500 mt-1">Isso será usado para gerar os links de assinatura automaticamente quando você criar os planos.</p>
            </div>
            <button type="submit" disabled={loading} className="w-full bg-brand-orange text-white py-2 rounded-lg hover:bg-orange-600 transition">Salvar Integração</button>
          </form>
        </div>
      </div>
    </Layout>
  );
};

export default AdminSettings;
