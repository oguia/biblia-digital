import React, { useState } from 'react';
import { CreditCard, CheckCircle, ArrowRight } from 'lucide-react';

export default function SubscriptionBlock({ plan, onUpgrade }) {
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');

  const handleSubscribe = async () => {
    setLoading(true);
    setError('');
    const token = localStorage.getItem('vouzelar_token');

    try {
      const res = await fetch('./api/checkout.php?action=create_preference', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${token}`
        },
        body: JSON.stringify({ plan })
      });

      const data = await res.json();

      if (!res.ok) throw new Error(data.error || 'Erro ao gerar link de pagamento');

      // Redirect to Mercado Pago
      window.location.href = data.init_point;
    } catch (err) {
      setError(err.message);
      setLoading(false);
    }
  };

  const isFamily = plan === 'family';
  const price = isFamily ? '19,90' : '5,00';
  const title = isFamily ? 'Plano Família' : 'Plano Individual';

  return (
    <div className="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100 max-w-sm mx-auto">
      <div className="p-8">
        <h3 className="text-center text-2xl font-semibold text-gray-900 mb-2">{title}</h3>
        <div className="text-center mb-6">
          <span className="text-5xl font-extrabold text-gray-900">R${price}</span>
          <span className="text-xl font-medium text-gray-500">/mês</span>
        </div>

        <ul className="space-y-4 mb-8">
          <li className="flex items-start">
            <CheckCircle className="flex-shrink-0 h-6 w-6 text-green-500" />
            <span className="ml-3 text-gray-500">Controle de estoque inteligente</span>
          </li>
          <li className="flex items-start">
            <CheckCircle className="flex-shrink-0 h-6 w-6 text-green-500" />
            <span className="ml-3 text-gray-500">Alertas de menor preço</span>
          </li>
          {isFamily && (
             <>
               <li className="flex items-start">
                 <CheckCircle className="flex-shrink-0 h-6 w-6 text-green-500" />
                 <span className="ml-3 text-gray-500">Até 5 cuidadores conectados</span>
               </li>
               <li className="flex items-start">
                 <CheckCircle className="flex-shrink-0 h-6 w-6 text-green-500" />
                 <span className="ml-3 text-gray-500">Escala de compras rotativa</span>
               </li>
               <li className="flex items-start">
                 <CheckCircle className="flex-shrink-0 h-6 w-6 text-green-500" />
                 <span className="ml-3 text-gray-500 font-medium">Alertas PWA de emergência</span>
               </li>
             </>
          )}
        </ul>

        {error && <p className="text-red-500 text-sm mb-4 text-center">{error}</p>}

        <button
          onClick={handleSubscribe}
          disabled={loading}
          className="w-full bg-primary-600 text-white rounded-xl py-4 font-bold text-lg hover:bg-primary-700 flex items-center justify-center gap-2 transition-transform active:scale-95 disabled:opacity-50"
        >
          {loading ? 'Processando...' : (
            <>
              Assinar com Mercado Pago <ArrowRight size={20} />
            </>
          )}
        </button>
      </div>
    </div>
  );
}
