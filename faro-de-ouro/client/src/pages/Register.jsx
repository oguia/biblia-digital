import React, { useState } from 'react';
import { useNavigate, Link } from 'react-router-dom';
import api from '../api';

const Register = () => {
  const [formData, setFormData] = useState({
    name: '', email: '', password: '', type: 'PF', document: '', tenantName: ''
  });
  const [error, setError] = useState('');
  const navigate = useNavigate();

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      await api.post('auth&action=register', formData);
      // Auto login after register
      const loginRes = await api.post('auth&action=login', { email: formData.email, password: formData.password });
      localStorage.setItem('faro_token', loginRes.data.token);
      navigate('/app');
    } catch (err) {
      setError(err.response?.data?.error || 'Erro ao cadastrar');
    }
  };

  return (
    <div className="min-h-screen flex items-center justify-center bg-brand-gray-light py-12 px-4">
      <div className="max-w-md w-full bg-white p-8 rounded-xl shadow-lg">
        <div className="text-center mb-8">
          <img className="mx-auto h-20 w-auto object-contain" src="/logo.png" alt="Faro de Ouro" />
          <h2 className="mt-4 text-2xl font-bold text-brand-dark">Criar Conta (7 dias grátis)</h2>
        </div>

        <form className="space-y-4" onSubmit={handleSubmit}>
          {error && <div className="text-red-500 text-sm">{error}</div>}

          <div className="flex gap-4 mb-4">
            <label className="flex items-center gap-2">
              <input type="radio" name="type" value="PF" checked={formData.type === 'PF'} onChange={(e) => setFormData({...formData, type: e.target.value})} className="text-brand-orange focus:ring-brand-orange" />
              Pessoa Física (Autônomo)
            </label>
            <label className="flex items-center gap-2">
              <input type="radio" name="type" value="PJ" checked={formData.type === 'PJ'} onChange={(e) => setFormData({...formData, type: e.target.value})} className="text-brand-orange focus:ring-brand-orange" />
              Pessoa Jurídica (Empresa)
            </label>
          </div>

          <input
            type="text" required placeholder="Seu Nome Completo"
            className="w-full px-3 py-2 border rounded-md focus:ring-brand-orange focus:border-brand-orange"
            value={formData.name} onChange={(e) => setFormData({...formData, name: e.target.value})}
          />

          <input
            type="text" required placeholder={formData.type === 'PF' ? 'CPF' : 'CNPJ'}
            className="w-full px-3 py-2 border rounded-md focus:ring-brand-orange focus:border-brand-orange"
            value={formData.document} onChange={(e) => setFormData({...formData, document: e.target.value})}
          />

          {formData.type === 'PJ' && (
            <input
              type="text" required placeholder="Nome da Empresa"
              className="w-full px-3 py-2 border rounded-md focus:ring-brand-orange focus:border-brand-orange"
              value={formData.tenantName} onChange={(e) => setFormData({...formData, tenantName: e.target.value})}
            />
          )}

          <input
            type="email" required placeholder="Email"
            className="w-full px-3 py-2 border rounded-md focus:ring-brand-orange focus:border-brand-orange"
            value={formData.email} onChange={(e) => setFormData({...formData, email: e.target.value})}
          />

          <input
            type="password" required placeholder="Senha"
            className="w-full px-3 py-2 border rounded-md focus:ring-brand-orange focus:border-brand-orange"
            value={formData.password} onChange={(e) => setFormData({...formData, password: e.target.value})}
          />

          <button type="submit" className="w-full py-2 px-4 border border-transparent rounded-md shadow-sm text-white bg-brand-orange hover:bg-orange-600 font-medium">
            Cadastrar e Começar Teste
          </button>
        </form>
        <div className="text-center mt-4">
          <Link to="/login" className="text-sm text-brand-orange">Já tenho conta</Link>
        </div>
      </div>
    </div>
  );
};

export default Register;
