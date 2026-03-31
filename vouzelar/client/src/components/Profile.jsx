import React, { useState, useEffect } from 'react';
import { useLocation } from 'wouter';
import { ArrowLeft, User, Mail, Lock, CheckCircle } from 'lucide-react';

export default function Profile({ setView }) {
  const [user, setUser] = useState(() => JSON.parse(localStorage.getItem('vouzelar_user')));
  const [form, setForm] = useState({ name: user.name, email: user.email, password: '' });
  const [loading, setLoading] = useState(false);
  const [message, setMessage] = useState('');
  const [error, setError] = useState('');

  const token = localStorage.getItem('vouzelar_token');

  const handleUpdate = async (e) => {
    e.preventDefault();
    setLoading(true);
    setMessage('');
    setError('');

    try {
      const res = await fetch('./api/profile.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Authorization': `Bearer ${token}` },
        body: JSON.stringify(form)
      });
      const data = await res.json();

      if (!res.ok) throw new Error(data.error || 'Erro ao atualizar perfil');

      setMessage(data.message);
      setForm({ ...form, password: '' }); // Clear password field after update

      // Update local storage
      const updatedUser = { ...user, name: form.name, email: form.email };
      localStorage.setItem('vouzelar_user', JSON.stringify(updatedUser));
      setUser(updatedUser);

    } catch (err) {
      setError(err.message);
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="min-h-screen bg-gray-50 pb-20">
      <header className="bg-white text-gray-900 p-4 shadow-sm flex items-center gap-3 sticky top-0 z-10">
        <button onClick={() => setView('dashboard')} className="p-2 hover:bg-gray-100 rounded-full">
          <ArrowLeft size={20} />
        </button>
        <h1 className="text-xl font-bold">Configurações de Perfil</h1>
      </header>

      <main className="p-4 max-w-lg mx-auto mt-4">
        <div className="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
          <h2 className="text-lg font-semibold text-gray-900 mb-6 flex items-center gap-2">
            <User className="text-primary-600" /> Seus Dados
          </h2>

          {message && (
            <div className="mb-4 bg-green-50 text-green-700 p-3 rounded-xl flex items-center gap-2 text-sm border border-green-200">
              <CheckCircle size={18} /> {message}
            </div>
          )}
          {error && <div className="mb-4 bg-red-50 text-red-600 p-3 rounded-xl text-sm border border-red-200">{error}</div>}

          <form onSubmit={handleUpdate} className="space-y-4">
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">Nome Completo</label>
              <div className="relative">
                <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <User size={16} className="text-gray-400" />
                </div>
                <input
                  type="text"
                  required
                  className="pl-10 w-full border border-gray-300 rounded-xl p-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-primary-500 focus:outline-none transition-colors"
                  value={form.name}
                  onChange={e => setForm({...form, name: e.target.value})}
                />
              </div>
            </div>

            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">E-mail de Login</label>
              <div className="relative">
                <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <Mail size={16} className="text-gray-400" />
                </div>
                <input
                  type="email"
                  required
                  className="pl-10 w-full border border-gray-300 rounded-xl p-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-primary-500 focus:outline-none transition-colors"
                  value={form.email}
                  onChange={e => setForm({...form, email: e.target.value})}
                />
              </div>
            </div>

            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">Nova Senha</label>
              <div className="relative">
                <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <Lock size={16} className="text-gray-400" />
                </div>
                <input
                  type="password"
                  placeholder="Deixe em branco para manter a atual"
                  className="pl-10 w-full border border-gray-300 rounded-xl p-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-primary-500 focus:outline-none transition-colors"
                  value={form.password}
                  onChange={e => setForm({...form, password: e.target.value})}
                />
              </div>
            </div>

            <button
              type="submit"
              disabled={loading}
              className="w-full bg-primary-600 text-white p-3 rounded-xl font-bold mt-4 hover:bg-primary-700 transition-colors disabled:opacity-50"
            >
              {loading ? 'Salvando...' : 'Atualizar Perfil'}
            </button>
          </form>
        </div>
      </main>
    </div>
  );
}
