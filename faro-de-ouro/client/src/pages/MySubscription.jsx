import React, { useEffect, useState } from 'react';
import api from '../api';
import Layout from '../components/Layout';

const MySubscription = ({ user }) => {
  const [plans, setPlans] = useState([]);
  const [coupon, setCoupon] = useState('');

  // Calculate remaining days if the plan is not expired yet
  const getRemainingDays = () => {
    if (!user || !user.plan_expires_at) return 0;
    const expirationDate = new Date(user.plan_expires_at);
    const now = new Date();
    const diffTime = expirationDate - now;
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    return diffDays > 0 ? diffDays : 0;
  };

  const remainingDays = getRemainingDays();

  useEffect(() => {
    api.get('public&action=plans').then(res => setPlans(res.data));
  }, []);

  const handleApplyCoupon = async (e) => {
    e.preventDefault();
    try {
      await api.post('auth&action=apply_coupon', { code: coupon });
      alert('Cupom aplicado com sucesso! A página será atualizada.');
      window.location.reload();
    } catch (err) {
      alert('Cupom inválido ou expirado.');
    }
  };

  return (
    <Layout user={user}>
      <div className="max-w-4xl mx-auto py-8">
        <h1 className="text-3xl font-bold text-brand-dark mb-8">Meu Plano</h1>

        {/* Current Plan Status */}
        <div className="bg-white rounded-xl shadow-sm border border-gray-100 p-8 mb-12">
          <h2 className="text-xl font-bold mb-4">Status da Assinatura</h2>
          <div className="flex flex-col md:flex-row items-center justify-between gap-6">
            <div>
              <p className="text-gray-600 mb-2">Seu acesso atual expira em:</p>
              <p className="text-2xl font-bold text-brand-dark">
                {user?.plan_expires_at ? new Date(user.plan_expires_at).toLocaleDateString('pt-BR') : 'Indisponível'}
              </p>
              {remainingDays > 0 && (
                <p className={`mt-2 font-semibold ${remainingDays <= 3 ? 'text-red-500' : 'text-green-600'}`}>
                  Faltam {remainingDays} dias
                </p>
              )}
              {remainingDays === 0 && (
                <p className="mt-2 font-semibold text-red-500">
                  Expirado
                </p>
              )}
            </div>

            <div className="flex-1 w-full max-w-md">
              <form onSubmit={handleApplyCoupon} className="flex gap-2">
                <input
                  type="text"
                  placeholder="Código do Cupom"
                  required
                  value={coupon}
                  onChange={e => setCoupon(e.target.value.toUpperCase())}
                  className="flex-1 px-4 py-2 border rounded-lg focus:ring-brand-orange"
                />
                <button type="submit" className="bg-brand-dark text-white px-6 py-2 rounded-lg font-bold hover:bg-gray-800 transition">
                  Aplicar
                </button>
              </form>
              <p className="text-sm text-gray-500 mt-2">Tem um cupom de desconto ou gratuidade? Insira acima.</p>
            </div>
          </div>
        </div>

        <h2 className="text-2xl font-bold text-brand-dark mb-6">Assine e continue utilizando</h2>
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          {plans.map(plan => (
            <div key={plan.id} className="bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col hover:shadow-md transition">
              <div className="p-4 bg-brand-dark text-white text-center rounded-t-xl">
                <h3 className="text-lg font-bold">{plan.name}</h3>
              </div>
              <div className="p-6 text-center flex-1 flex flex-col justify-center">
                <div className="text-3xl font-extrabold text-brand-orange mb-1">
                  R$ {Number(plan.price).toFixed(2)}
                </div>
                <div className="text-gray-500 mb-6 text-sm">
                  a cada {plan.duration_months} {plan.duration_months > 1 ? 'meses' : 'mês'}
                </div>
                {plan.mp_link ? (
                  <a href={plan.mp_link} target="_blank" rel="noopener noreferrer" className="block w-full py-3 px-4 bg-brand-orange text-white font-bold rounded-lg hover:bg-orange-600 transition shadow-sm">
                    Assinar via Mercado Pago
                  </a>
                ) : (
                  <button disabled className="w-full py-3 px-4 bg-gray-200 text-gray-400 font-bold rounded-lg cursor-not-allowed">
                    Indisponível
                  </button>
                )}
              </div>
            </div>
          ))}
          {plans.length === 0 && <div className="col-span-3 text-center text-gray-500">Nenhum plano configurado no momento. Entre em contato com o suporte.</div>}
        </div>
      </div>
    </Layout>
  );
};

export default MySubscription;
