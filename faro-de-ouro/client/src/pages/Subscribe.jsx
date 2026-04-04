import React, { useEffect, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import api from '../api';

const Subscribe = () => {
  const [plans, setPlans] = useState([]);
  const [coupon, setCoupon] = useState('');
  const navigate = useNavigate();

  useEffect(() => {
    api.get('public&action=plans').then(res => setPlans(res.data));
  }, []);

  const handleLogout = () => {
    localStorage.removeItem('faro_token');
    navigate('/login');
  };

  return (
    <div className="min-h-screen bg-brand-gray-light py-12 px-4 sm:px-6 lg:px-8">
      <div className="max-w-4xl mx-auto">
        <div className="text-center mb-12">
          <img className="mx-auto h-20 w-auto object-contain mb-4" src="/logo.png" alt="Faro de Ouro" />
          <h2 className="text-3xl font-extrabold text-brand-dark">Seu acesso expirou</h2>
          <p className="mt-4 text-lg text-gray-600">Escolha um plano abaixo para continuar gerenciando seu estoque com o Faro de Ouro.</p>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
          {plans.map(plan => (
            <div key={plan.id} className="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100 flex flex-col">
              <div className="p-6 bg-brand-dark text-white text-center">
                <h3 className="text-xl font-bold">{plan.name}</h3>
              </div>
              <div className="p-8 text-center flex-1 flex flex-col justify-center">
                <div className="text-4xl font-extrabold text-brand-orange mb-2">
                  R$ {Number(plan.price).toFixed(2)}
                </div>
                <div className="text-gray-500 mb-8">
                  a cada {plan.duration_months} {plan.duration_months > 1 ? 'meses' : 'mês'}
                </div>
                {plan.mp_link ? (
                  <a href={plan.mp_link} target="_blank" rel="noopener noreferrer" className="block w-full py-3 px-4 bg-brand-orange text-white font-bold rounded-lg hover:bg-orange-600 transition">
                    Assinar via Mercado Pago
                  </a>
                ) : (
                  <button disabled className="w-full py-3 px-4 bg-gray-300 text-gray-500 font-bold rounded-lg cursor-not-allowed">
                    Indisponível
                  </button>
                )}
              </div>
            </div>
          ))}
          {plans.length === 0 && <div className="col-span-3 text-center text-gray-500">Nenhum plano configurado no momento. Entre em contato com o suporte.</div>}
        </div>

        <div className="max-w-md mx-auto mb-12">
          <form onSubmit={async (e) => {
            e.preventDefault();
            try {
              await api.post('auth&action=apply_coupon', { code: coupon });
              alert('Cupom aplicado! Seu acesso foi liberado.');
              navigate('/app');
            } catch (err) {
              alert('Cupom inválido ou expirado.');
            }
          }} className="flex gap-2">
            <input type="text" placeholder="Possui um cupom?" required value={coupon} onChange={e => setCoupon(e.target.value.toUpperCase())} className="flex-1 px-4 py-2 border rounded-lg focus:ring-brand-orange" />
            <button type="submit" className="bg-brand-dark text-white px-4 py-2 rounded-lg font-bold">Aplicar</button>
          </form>
        </div>

        <div className="text-center">
          <button onClick={handleLogout} className="text-gray-500 hover:text-gray-800 underline">
            Sair e voltar para o Login
          </button>
        </div>
      </div>
    </div>
  );
};

export default Subscribe;
