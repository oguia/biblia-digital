import React, { useState, useEffect } from 'react';
import { Users as UsersIcon, Plus, Trash2, Shield, ShieldCheck } from 'lucide-react';
import api from '../api';
import Layout from '../components/Layout';

const Users = ({ user }) => {
  const [users, setUsers] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  const [showModal, setShowModal] = useState(false);
  const [formData, setFormData] = useState({ name: '', email: '', password: '', role: 'operator' });
  const [submitError, setSubmitError] = useState(null);

  useEffect(() => {
    fetchUsers();
  }, []);

  const fetchUsers = () => {
    api.get('users')
      .then(res => {
        setUsers(res.data.data || []);
      })
      .catch(err => setError('Erro ao carregar usuários.'))
      .finally(() => setLoading(false));
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    setSubmitError(null);
    api.post('users', formData)
      .then(() => {
        setShowModal(false);
        setFormData({ name: '', email: '', password: '', role: 'operator' });
        fetchUsers();
      })
      .catch(err => {
        setSubmitError(err.response?.data?.message || 'Erro ao criar usuário.');
      });
  };

  const handleDelete = (id) => {
    if (window.confirm('Tem certeza que deseja excluir este usuário?')) {
      api.delete(`users&id=${id}`)
        .then(() => fetchUsers())
        .catch(() => alert('Erro ao excluir.'));
    }
  };

  if (loading) return <Layout user={user}><div className="p-8 text-center">Carregando usuários...</div></Layout>;

  return (
    <Layout user={user}>
      <div className="p-4 md:p-8 max-w-6xl mx-auto">
        <div className="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
          <div>
            <h1 className="text-2xl font-bold text-brand-black flex items-center gap-2">
              <UsersIcon className="text-brand-orange" />
              Equipe e Usuários
            </h1>
            <p className="text-gray-500 mt-1">Gerencie os acessos ao seu sistema</p>
          </div>
          <button
            onClick={() => setShowModal(true)}
            className="bg-brand-orange text-white px-4 py-2 rounded-lg flex items-center gap-2 hover:bg-orange-600 transition"
          >
            <Plus size={20} />
            Novo Usuário
          </button>
        </div>

        {error && <div className="bg-red-50 text-red-600 p-4 rounded-lg mb-6">{error}</div>}

        <div className="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
          <div className="overflow-x-auto">
            <table className="w-full text-left border-collapse">
              <thead>
                <tr className="bg-brand-gray-light text-gray-600 text-sm border-b">
                  <th className="p-4 font-semibold">Nome</th>
                  <th className="p-4 font-semibold">Email</th>
                  <th className="p-4 font-semibold">Permissão</th>
                  <th className="p-4 font-semibold text-center w-24">Ações</th>
                </tr>
              </thead>
              <tbody>
                {users.length === 0 ? (
                  <tr>
                    <td colSpan="4" className="p-8 text-center text-gray-500">
                      Nenhum usuário cadastrado.
                    </td>
                  </tr>
                ) : (
                  users.map((user) => (
                    <tr key={user.id} className="border-b last:border-0 hover:bg-gray-50">
                      <td className="p-4 font-medium">{user.name}</td>
                      <td className="p-4 text-gray-600">{user.email}</td>
                      <td className="p-4">
                        {user.role === 'owner' ? (
                          <span className="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                            <ShieldCheck size={14} /> Proprietário
                          </span>
                        ) : (
                          <span className="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            <Shield size={14} /> Operador
                          </span>
                        )}
                      </td>
                      <td className="p-4 text-center">
                        {user.role !== 'owner' && (
                          <button onClick={() => handleDelete(user.id)} className="text-red-500 hover:text-red-700 p-1">
                            <Trash2 size={18} />
                          </button>
                        )}
                      </td>
                    </tr>
                  ))
                )}
              </tbody>
            </table>
          </div>
        </div>
      </div>

      {showModal && (
        <div className="fixed inset-0 bg-black/50 flex items-center justify-center p-4 z-50">
          <div className="bg-white rounded-xl max-w-md w-full p-6">
            <h3 className="text-xl font-bold text-brand-black mb-4">Adicionar Usuário</h3>

            {submitError && <div className="bg-red-50 text-red-600 p-3 rounded-lg text-sm mb-4">{submitError}</div>}

            <form onSubmit={handleSubmit} className="space-y-4">
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">Nome</label>
                <input
                  type="text" required
                  className="w-full p-2 border rounded-lg focus:ring-2 focus:ring-brand-orange outline-none"
                  value={formData.name} onChange={e => setFormData({...formData, name: e.target.value})}
                />
              </div>

              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">Email (Login)</label>
                <input
                  type="email" required
                  className="w-full p-2 border rounded-lg focus:ring-2 focus:ring-brand-orange outline-none"
                  value={formData.email} onChange={e => setFormData({...formData, email: e.target.value})}
                />
              </div>

              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">Senha Temporária</label>
                <input
                  type="text" required minLength="6"
                  className="w-full p-2 border rounded-lg focus:ring-2 focus:ring-brand-orange outline-none"
                  value={formData.password} onChange={e => setFormData({...formData, password: e.target.value})}
                />
              </div>

              <div className="pt-4 flex gap-3">
                <button type="button" onClick={() => setShowModal(false)} className="flex-1 px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-50">Cancelar</button>
                <button type="submit" className="flex-1 px-4 py-2 bg-brand-orange text-white rounded-lg hover:bg-orange-600">Salvar</button>
              </div>
            </form>
          </div>
        </div>
      )}
    </Layout>
  );
};

export default Users;
