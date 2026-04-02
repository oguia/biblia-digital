import React, { useState } from 'react';
import { useLocation } from 'wouter';
import { ShieldAlert, Users, CreditCard } from 'lucide-react';

export default function Register() {
  const [, setLocation] = useLocation();
  const [formData, setFormData] = useState({
    name: '',
    email: '',
    password: '',
    cep: '',
    city: '',
    state: '',
    plan: 'individual' // default
  });
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(false);

  const handleCepChange = async (e) => {
    let cep = e.target.value.replace(/\D/g, '');
    setFormData({...formData, cep});

    if (cep.length === 8) {
      try {
        const res = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
        const data = await res.json();
        if (!data.erro) {
          setFormData(prev => ({
            ...prev,
            city: data.localidade,
            state: data.uf
          }));
        }
      } catch (err) {
        console.error("Erro ao buscar CEP", err);
      }
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    setError('');

    try {
      const res = await fetch('./api/auth.php?action=register', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(formData)
      });
      const data = await res.json();

      if (!res.ok) throw new Error(data.error || 'Erro ao registrar');

      localStorage.setItem('vouzelar_token', data.token);
      localStorage.setItem('vouzelar_user', JSON.stringify(data.user));

      setLocation('/dashboard');
    } catch (err) {
      setError(err.message);
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
      <div className="sm:mx-auto sm:w-full sm:max-w-md">
        <h2 className="mt-6 text-center text-3xl font-extrabold text-gray-900">
          Crie sua conta VouZelar
        </h2>
        <p className="mt-2 text-center text-sm text-gray-600">
          Você terá 7 dias grátis para testar o plano escolhido.
        </p>
      </div>

      <div className="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div className="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
          {error && <div className="mb-4 bg-red-50 text-red-600 p-3 rounded-md text-sm">{error}</div>}

          <form onSubmit={handleSubmit} className="space-y-6">
            <div>
              <label className="block text-sm font-medium text-gray-700">Nome completo</label>
              <div className="mt-1">
                <input
                  type="text"
                  required
                  className="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                  value={formData.name}
                  onChange={e => setFormData({...formData, name: e.target.value})}
                />
              </div>
            </div>

            <div>
              <label className="block text-sm font-medium text-gray-700">Email</label>
              <div className="mt-1">
                <input
                  type="email"
                  required
                  className="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                  value={formData.email}
                  onChange={e => setFormData({...formData, email: e.target.value})}
                />
              </div>
            </div>

            <div>
              <label className="block text-sm font-medium text-gray-700">Senha</label>
              <div className="mt-1">
                <input
                  type="password"
                  required
                  className="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                  value={formData.password}
                  onChange={e => setFormData({...formData, password: e.target.value})}
                />
              </div>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div className="md:col-span-2">
                <label className="block text-sm font-medium text-gray-700">CEP</label>
                <div className="mt-1">
                  <input
                    type="text"
                    required
                    maxLength="8"
                    placeholder="Somente números"
                    className="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                    value={formData.cep}
                    onChange={handleCepChange}
                  />
                </div>
              </div>
              <div>
                <label className="block text-sm font-medium text-gray-700">Cidade</label>
                <div className="mt-1">
                  <input
                    type="text"
                    required
                    className="appearance-none block w-full px-3 py-2 border border-gray-300 bg-gray-50 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                    value={formData.city}
                    onChange={e => setFormData({...formData, city: e.target.value})}
                  />
                </div>
              </div>
              <div>
                <label className="block text-sm font-medium text-gray-700">Estado</label>
                <div className="mt-1">
                  <input
                    type="text"
                    required
                    className="appearance-none block w-full px-3 py-2 border border-gray-300 bg-gray-50 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                    value={formData.state}
                    onChange={e => setFormData({...formData, state: e.target.value})}
                  />
                </div>
              </div>
            </div>

            <div className="pt-4">
              <span className="block text-sm font-medium text-gray-700 mb-3">Qual plano você deseja testar?</span>
              <div className="space-y-4">
                {/* Individual Plan */}
                <label className={`relative block rounded-lg border p-4 cursor-pointer hover:border-primary-500 focus:outline-none ${formData.plan === 'individual' ? 'border-primary-500 bg-primary-50 ring-1 ring-primary-500' : 'border-gray-300'}`}>
                  <input type="radio" name="plan" value="individual" className="sr-only" onChange={() => setFormData({...formData, plan: 'individual'})} checked={formData.plan === 'individual'} />
                  <div className="flex justify-between items-center">
                    <div className="flex items-center">
                      <ShieldAlert className="h-6 w-6 text-primary-600 mr-3" />
                      <div>
                        <p className="text-base font-medium text-gray-900">Individual</p>
                        <p className="text-sm text-gray-500">Para 1 paciente. Alertas de estoque e preços.</p>
                      </div>
                    </div>
                    <div className="text-right">
                      <p className="text-sm font-medium text-gray-900">R$ 5/mês</p>
                      <p className="text-xs text-gray-500">após 7 dias</p>
                    </div>
                  </div>
                </label>

                {/* Family Plan */}
                <label className={`relative block rounded-lg border p-4 cursor-pointer hover:border-primary-500 focus:outline-none ${formData.plan === 'family' ? 'border-primary-500 bg-primary-50 ring-1 ring-primary-500' : 'border-gray-300'}`}>
                  <input type="radio" name="plan" value="family" className="sr-only" onChange={() => setFormData({...formData, plan: 'family'})} checked={formData.plan === 'family'} />
                  <div className="flex justify-between items-center">
                    <div className="flex items-center">
                      <Users className="h-6 w-6 text-primary-600 mr-3" />
                      <div>
                        <p className="text-base font-medium text-gray-900">Família</p>
                        <p className="text-sm text-gray-500">Até 5 cuidadores, escala de compras e alertas de emergência.</p>
                      </div>
                    </div>
                    <div className="text-right">
                      <p className="text-sm font-medium text-gray-900">R$ 19,90/mês</p>
                      <p className="text-xs text-gray-500">após 7 dias</p>
                    </div>
                  </div>
                </label>
              </div>
            </div>

            <div>
              <button
                type="submit"
                disabled={loading}
                className="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 disabled:opacity-50"
              >
                {loading ? 'Criando conta...' : 'Começar Teste Grátis'}
              </button>
            </div>

            <div className="text-center mt-4">
              <a href="#/login" className="text-sm text-primary-600 hover:text-primary-500">
                Já tem uma conta? Entrar
              </a>
            </div>
          </form>
        </div>
      </div>
    </div>
  );
}
