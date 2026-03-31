import { useState } from 'react';
import { Link } from 'react-router-dom';
import axios from 'axios';
import { UserPlus } from 'lucide-react';

export default function Register({ setUser }) {
  const [formData, setFormData] = useState({ name: '', email: '', password: '', type: 'pf' });
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(false);

  const handleChange = (e) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    setError('');

    try {
      const res = await axios.post('/auth.php?action=register', formData);
      localStorage.setItem('token', res.data.token);
      setUser(res.data.user);
    } catch (err) {
      setError(err.response?.data?.error || 'Erro ao registrar');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="min-h-screen flex items-center justify-center bg-gray-100 p-4">
      <div className="max-w-md w-full bg-white rounded-xl shadow-md p-8">
        <div className="text-center mb-8">
          <h2 className="text-3xl font-bold text-gray-800">Criar Conta</h2>
          <p className="text-gray-500 mt-2">Comece a controlar suas horas hoje</p>
        </div>

        {error && <div className="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">{error}</div>}

        <form onSubmit={handleSubmit}>
          <div className="mb-4 flex justify-center space-x-4">
            <label className="inline-flex items-center cursor-pointer">
              <input type="radio" name="type" value="pf" className="form-radio text-blue-600 h-5 w-5" checked={formData.type === 'pf'} onChange={handleChange} />
              <span className="ml-2 text-gray-700">Pessoa Física</span>
            </label>
            <label className="inline-flex items-center cursor-pointer">
              <input type="radio" name="type" value="pj" className="form-radio text-blue-600 h-5 w-5" checked={formData.type === 'pj'} onChange={handleChange} />
              <span className="ml-2 text-gray-700">Empresa (PJ)</span>
            </label>
          </div>

          <div className="mb-4">
            <label className="block text-gray-700 text-sm font-bold mb-2">Nome {formData.type === 'pj' ? 'da Empresa' : 'Completo'}</label>
            <input
              type="text"
              name="name"
              className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              value={formData.name}
              onChange={handleChange}
              required
            />
          </div>

          <div className="mb-4">
            <label className="block text-gray-700 text-sm font-bold mb-2">Email</label>
            <input
              type="email"
              name="email"
              className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              value={formData.email}
              onChange={handleChange}
              required
            />
          </div>

          <div className="mb-6">
            <label className="block text-gray-700 text-sm font-bold mb-2">Senha</label>
            <input
              type="password"
              name="password"
              className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              value={formData.password}
              onChange={handleChange}
              required
            />
          </div>

          <button
            type="submit"
            disabled={loading}
            className="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md focus:outline-none flex justify-center items-center"
          >
            {loading ? 'Cadastrando...' : <><UserPlus className="mr-2 h-5 w-5" /> Cadastrar</>}
          </button>
        </form>

        <div className="mt-6 text-center">
          <p className="text-gray-600">
            Já tem conta? <Link to="/login" className="text-blue-600 hover:text-blue-800 font-semibold">Fazer login</Link>
          </p>
        </div>
      </div>
    </div>
  );
}
