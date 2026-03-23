import { useState } from 'react';
import axios from 'axios';
import { CreditCard, Gift, ShieldCheck, CheckCircle2, AlertCircle, X, RefreshCw, Smartphone } from 'lucide-react';
import { format, parseISO } from 'date-fns';
import ptBR from 'date-fns/locale/pt-BR';
import { useNavigate } from 'react-router-dom';

export default function Subscription({ user, setUser }) {
  const navigate = useNavigate();
  const [inviteCode, setInviteCode] = useState('');
  const [loading, setLoading] = useState(false);
  const [message, setMessage] = useState({ type: '', text: '' });
  const [selectedPlanModal, setSelectedPlanModal] = useState(null);

  const isExpired = user && (!user.plan_expires_at || new Date(user.plan_expires_at) < new Date());

  const handleRedeem = async (e) => {
    e.preventDefault();
    if (!inviteCode.trim()) return;

    setLoading(true);
    setMessage({ type: '', text: '' });

    try {
      const res = await axios.post('/subscription.php?action=redeem_invite', { code: inviteCode.trim() });

      // Update local user state with new expiration date
      setUser({ ...user, plan_expires_at: res.data.plan_expires_at });

      setMessage({ type: 'success', text: 'Código resgatado com sucesso! Acesso vitalício liberado.' });
      setInviteCode('');

      // Redirect to dashboard after 3 seconds if they were expired
      if (isExpired) {
        setTimeout(() => navigate('/'), 3000);
      }
    } catch (err) {
      setMessage({
        type: 'error',
        text: err.response?.data?.error || 'Erro ao resgatar o código.'
      });
    } finally {
      setLoading(false);
    }
  };

  const [processingPayment, setProcessingPayment] = useState(false);

  const openPaymentModal = (planKey) => {
    setSelectedPlanModal(planKey);
  };

  const closePaymentModal = () => {
    setSelectedPlanModal(null);
  };

  const handleCheckout = async (paymentType) => {
    if (!selectedPlanModal) return;
    setProcessingPayment(true);
    setMessage({ type: '', text: '' });

    try {
      const res = await axios.post('/checkout.php', {
        plan: selectedPlanModal,
        payment_type: paymentType
      });

      if (res.data.init_point) {
        window.location.href = res.data.init_point;
      }
    } catch (err) {
      alert(err.response?.data?.error || 'Erro ao gerar link de pagamento do Mercado Pago.');
    } finally {
      setProcessingPayment(false);
      closePaymentModal();
    }
  };

  if (!user) return null;

  return (
    <div className="max-w-6xl mx-auto space-y-10 py-8 px-4">
      {/* Status Banner */}
      <div className={`p-6 rounded-2xl border-2 flex flex-col md:flex-row items-center justify-between gap-4 ${
        isExpired
          ? 'bg-red-50 border-red-200 text-red-800'
          : user.plan_expires_at?.startsWith('2099')
            ? 'bg-purple-50 border-purple-200 text-purple-800'
            : 'bg-green-50 border-green-200 text-green-800'
      }`}>
        <div className="flex items-center">
          {isExpired ? <AlertCircle className="h-8 w-8 mr-4 text-red-500" /> : <ShieldCheck className="h-8 w-8 mr-4 text-green-500" />}
          <div>
            <h2 className="text-xl font-bold">
              {isExpired ? 'Sua assinatura expirou' : user.plan_expires_at?.startsWith('2099') ? 'Acesso Vitalício Ativo' : 'Assinatura Ativa'}
            </h2>
            <p className="mt-1 opacity-90">
              {isExpired
                ? 'Você não pode registrar novas horas ou criar projetos. Renove seu plano abaixo.'
                : user.plan_expires_at?.startsWith('2099')
                  ? 'Você possui acesso completo e irrestrito ao sistema graças a um convite VIP.'
                  : `Seu período de testes expira em: ${format(parseISO(user.plan_expires_at), "dd 'de' MMMM 'de' yyyy", { locale: ptBR })}`}
            </p>
          </div>
        </div>
      </div>

      {/* Invite Code Redemption */}
      <div className="bg-white p-8 rounded-2xl shadow-sm border border-gray-200">
        <div className="flex flex-col md:flex-row gap-8 items-center">
          <div className="flex-1">
            <h3 className="text-xl font-bold text-gray-800 flex items-center mb-2">
              <Gift className="h-6 w-6 mr-2 text-indigo-600" /> Tem um Código de Convite?
            </h3>
            <p className="text-gray-600">
              Se você recebeu um código de acesso especial do administrador, insira-o aqui para liberar seu acesso gratuitamente.
            </p>
          </div>

          <div className="flex-1 w-full">
            <form onSubmit={handleRedeem} className="flex gap-3">
              <input
                type="text"
                placeholder="Ex: ABC123XYZ"
                value={inviteCode}
                onChange={(e) => setInviteCode(e.target.value.toUpperCase())}
                className="flex-1 px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 uppercase font-mono tracking-widest text-lg bg-gray-50"
                maxLength={10}
                required
              />
              <button
                type="submit"
                disabled={loading || !inviteCode.trim()}
                className="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl font-bold shadow-md transition-colors disabled:opacity-50"
              >
                {loading ? 'Verificando...' : 'Resgatar'}
              </button>
            </form>
            {message.text && (
              <p className={`mt-3 text-sm font-medium ${message.type === 'success' ? 'text-green-600' : 'text-red-600'}`}>
                {message.text}
              </p>
            )}
          </div>
        </div>
      </div>

      {/* Pricing Plans */}
      <div>
        <div className="text-center mb-10">
          <h2 className="text-3xl font-bold text-gray-900">Escolha o Melhor Plano para Você</h2>
          <p className="mt-4 text-xl text-gray-600">Controle suas horas e aumente seus ganhos profissionais.</p>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
          {/* Mensal */}
          <div className="bg-white rounded-3xl p-8 shadow-sm border border-gray-200 flex flex-col hover:border-blue-500 transition-colors">
            <h3 className="text-2xl font-bold text-gray-900 mb-2">Mensal</h3>
            <p className="text-gray-500 mb-6">Flexibilidade para o dia a dia.</p>
            <div className="mb-6">
              <span className="text-4xl font-extrabold text-gray-900">R$ 19,90</span>
              <span className="text-gray-500">/mês</span>
            </div>
            <ul className="space-y-4 mb-8 flex-1">
              {['Acesso completo ao sistema', 'Projetos ilimitados', 'Suporte via email'].map((feature, i) => (
                <li key={i} className="flex items-center text-gray-600">
                  <CheckCircle2 className="h-5 w-5 text-green-500 mr-3 shrink-0" />
                  {feature}
                </li>
              ))}
            </ul>
            <button disabled={processingPayment} onClick={() => openPaymentModal('monthly')} className="w-full bg-blue-50 text-blue-700 hover:bg-blue-100 font-bold py-4 rounded-xl flex items-center justify-center transition-colors disabled:opacity-50">
              <CreditCard className="mr-2 h-5 w-5" /> Pagar com Mercado Pago
            </button>
          </div>

          {/* Trimestral */}
          <div className="bg-blue-600 rounded-3xl p-8 shadow-xl border border-blue-500 flex flex-col transform md:-translate-y-4 relative">
            <div className="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-gradient-to-r from-yellow-400 to-orange-500 text-white px-4 py-1 rounded-full text-sm font-bold shadow-sm">
              MAIS POPULAR
            </div>
            <h3 className="text-2xl font-bold text-white mb-2">Trimestral</h3>
            <p className="text-blue-100 mb-6">Economia de 15% ideal para freelancers.</p>
            <div className="mb-6">
              <span className="text-4xl font-extrabold text-white">R$ 49,90</span>
              <span className="text-blue-200">/trimestre</span>
            </div>
            <ul className="space-y-4 mb-8 flex-1">
              {['Acesso completo ao sistema', 'Projetos ilimitados', 'Suporte prioritário', 'Exportação de relatórios avançados'].map((feature, i) => (
                <li key={i} className="flex items-center text-blue-50">
                  <CheckCircle2 className="h-5 w-5 text-blue-300 mr-3 shrink-0" />
                  {feature}
                </li>
              ))}
            </ul>
            <button disabled={processingPayment} onClick={() => openPaymentModal('quarterly')} className="w-full bg-white text-blue-700 hover:bg-gray-50 font-bold py-4 rounded-xl flex items-center justify-center transition-colors shadow-md disabled:opacity-50">
              <CreditCard className="mr-2 h-5 w-5" /> Pagar com Mercado Pago
            </button>
          </div>

          {/* Anual */}
          <div className="bg-white rounded-3xl p-8 shadow-sm border border-gray-200 flex flex-col hover:border-blue-500 transition-colors">
            <h3 className="text-2xl font-bold text-gray-900 mb-2">Anual</h3>
            <p className="text-gray-500 mb-6">Máxima economia (25% off).</p>
            <div className="mb-6">
              <span className="text-4xl font-extrabold text-gray-900">R$ 179,00</span>
              <span className="text-gray-500">/ano</span>
            </div>
            <ul className="space-y-4 mb-8 flex-1">
               {['Acesso completo ao sistema', 'Projetos ilimitados', 'Suporte VIP via WhatsApp', 'Todos os recursos futuros inclusos'].map((feature, i) => (
                <li key={i} className="flex items-center text-gray-600">
                  <CheckCircle2 className="h-5 w-5 text-green-500 mr-3 shrink-0" />
                  {feature}
                </li>
              ))}
            </ul>
            <button disabled={processingPayment} onClick={() => openPaymentModal('annual')} className="w-full bg-blue-50 text-blue-700 hover:bg-blue-100 font-bold py-4 rounded-xl flex items-center justify-center transition-colors disabled:opacity-50">
              <CreditCard className="mr-2 h-5 w-5" /> Pagar com Mercado Pago
            </button>
          </div>
        </div>
      </div>

      {/* Payment Method Modal */}
      {selectedPlanModal && (
        <div className="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
          <div className="bg-white rounded-2xl max-w-md w-full shadow-2xl overflow-hidden relative">
            <button
              onClick={closePaymentModal}
              className="absolute top-4 right-4 text-gray-400 hover:text-gray-600 p-1"
            >
              <X className="h-6 w-6" />
            </button>

            <div className="p-6 md:p-8">
              <h3 className="text-2xl font-bold text-gray-900 mb-2">Como deseja pagar?</h3>
              <p className="text-gray-600 mb-8">Escolha a forma que faz mais sentido para você. Fique tranquilo, é seguro.</p>

              <div className="space-y-4">
                {/* One-time Payment Option */}
                <button
                  onClick={() => handleCheckout('one_time')}
                  disabled={processingPayment}
                  className="w-full flex items-start p-4 border-2 border-gray-200 rounded-xl hover:border-blue-500 hover:bg-blue-50 transition-all text-left group"
                >
                  <div className="bg-gray-100 group-hover:bg-blue-100 p-3 rounded-full mr-4">
                    <Smartphone className="h-6 w-6 text-gray-600 group-hover:text-blue-600" />
                  </div>
                  <div>
                    <h4 className="font-bold text-gray-900">Pagamento Único (PIX / Boleto)</h4>
                    <p className="text-sm text-gray-500 mt-1">Você paga apenas o plano atual. Quando expirar, você precisará renovar manualmente pelo site.</p>
                  </div>
                </button>

                {/* Subscription Payment Option */}
                <button
                  onClick={() => handleCheckout('subscription')}
                  disabled={processingPayment}
                  className="w-full flex items-start p-4 border-2 border-green-200 rounded-xl hover:border-green-500 hover:bg-green-50 transition-all text-left group"
                >
                  <div className="bg-green-100 group-hover:bg-green-200 p-3 rounded-full mr-4">
                    <RefreshCw className="h-6 w-6 text-green-600 group-hover:text-green-700" />
                  </div>
                  <div>
                    <h4 className="font-bold text-gray-900">Assinatura Automática</h4>
                    <p className="text-sm text-gray-500 mt-1">Ideal para não se preocupar! Cadastre o cartão de crédito e a cobrança será automática todo mês/ano.</p>
                  </div>
                </button>
              </div>

              {processingPayment && (
                <div className="mt-6 text-center text-blue-600 font-medium">
                  Gerando pagamento seguro... Aguarde.
                </div>
              )}
            </div>
          </div>
        </div>
      )}

    </div>
  );
}
