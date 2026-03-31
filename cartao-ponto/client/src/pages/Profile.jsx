import { useState, useEffect } from 'react';
import axios from 'axios';
import { Save, UserCircle } from 'lucide-react';

export default function Profile({ user, setUser }) {
  const [formData, setFormData] = useState({ name: '', email: '', type: 'pf', password: '' });
  const [loading, setLoading] = useState(false);
  const [message, setMessage] = useState({ type: '', text: '' });

  useEffect(() => {
    if (user) {
      setFormData({
        name: user.name || '',
        email: user.email || '',
        type: user.type || 'pf',
        password: '' // Keep empty for security
      });
    }
  }, [user]);

  const handleChange = (e) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    setMessage({ type: '', text: '' });

    try {
      const res = await axios.put('/profile.php', formData);
      setUser(res.data.user);
      setMessage({ type: 'success', text: 'Perfil atualizado com sucesso!' });

      // Auto-hide success message after 3 seconds
      setTimeout(() => setMessage({ type: '', text: '' }), 3000);
    } catch (err) {
      setMessage({
        type: 'error',
        text: err.response?.data?.error || 'Erro ao atualizar perfil'
      });
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="max-w-2xl mx-auto space-y-6">
      <div className="flex items-center space-x-3 mb-6">
        <UserCircle className="h-8 w-8 text-blue-600" />
        <h1 className="text-2xl font-bold text-gray-800">Meu Perfil</h1>
      </div>

      <div className="bg-white p-6 md:p-8 rounded-xl shadow-sm border border-gray-200">
        {message.text && (
          <div className={`mb-6 p-4 rounded-lg border ${message.type === 'success' ? 'bg-green-50 border-green-200 text-green-700' : 'bg-red-50 border-red-200 text-red-700'}`}>
            {message.text}
          </div>
        )}

        <form onSubmit={handleSubmit} className="space-y-6">
          <div className="flex flex-col sm:flex-row sm:space-x-8 space-y-4 sm:space-y-0 p-4 bg-gray-50 rounded-lg border border-gray-100 mb-6">
            <span className="text-sm font-medium text-gray-700 mb-2 sm:mb-0 w-full sm:w-1/4 pt-1">Tipo de Conta:</span>
            <div className="flex space-x-6">
              <label className="inline-flex items-center cursor-pointer">
                <input
                  type="radio"
                  name="type"
                  value="pf"
                  className="form-radio text-blue-600 h-5 w-5"
                  checked={formData.type === 'pf'}
                  onChange={handleChange}
                />
                <span className="ml-2 text-gray-800 font-medium">Pessoa Física</span>
              </label>
              <label className="inline-flex items-center cursor-pointer">
                <input
                  type="radio"
                  name="type"
                  value="pj"
                  className="form-radio text-blue-600 h-5 w-5"
                  checked={formData.type === 'pj'}
                  onChange={handleChange}
                />
                <span className="ml-2 text-gray-800 font-medium">Empresa (PJ)</span>
              </label>
            </div>
          </div>

          <div>
            <label className="block text-gray-700 text-sm font-bold mb-2">
              {formData.type === 'pj' ? 'Nome da Empresa' : 'Nome Completo'}
            </label>
            <input
              type="text"
              name="name"
              className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white"
              value={formData.name}
              onChange={handleChange}
              required
            />
          </div>

          <div>
            <label className="block text-gray-700 text-sm font-bold mb-2">E-mail</label>
            <input
              type="email"
              name="email"
              className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white"
              value={formData.email}
              onChange={handleChange}
              required
            />
            <p className="text-xs text-gray-500 mt-2">Este e-mail é usado para fazer login no sistema.</p>
          </div>

          <div className="pt-4 border-t border-gray-100">
            <h3 className="text-lg font-bold text-gray-800 mb-4">Segurança</h3>
            <div>
              <label className="block text-gray-700 text-sm font-bold mb-2">Nova Senha</label>
              <input
                type="password"
                name="password"
                placeholder="Deixe em branco para não alterar"
                className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white"
                value={formData.password}
                onChange={handleChange}
              />
              <p className="text-xs text-gray-500 mt-2">Se você não quiser mudar a senha atual, basta deixar este campo vazio.</p>
            </div>
          </div>

          <div className="pt-4 border-t border-gray-100 flex justify-end">
            <button
              type="submit"
              disabled={loading}
              className="flex items-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg focus:outline-none shadow-md transition-colors disabled:opacity-50"
            >
              {loading ? 'Salvando...' : <><Save className="mr-2 h-5 w-5" /> Salvar Alterações</>}
            </button>
          </div>
        </form>
      </div>
    </div>
  );
}
