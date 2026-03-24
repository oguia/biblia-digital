import React, { useState } from 'react';
import useSWR from 'swr';
import { fetchWithAuth } from '../lib/api';
import { User, Key, CreditCard, CheckCircle, ExternalLink } from 'lucide-react';
import { format, parseISO, differenceInDays } from 'date-fns';
import { ptBR } from 'date-fns/locale';

export default function Profile() {
  const { data, error, mutate } = useSWR('/auth.php?action=me', fetchWithAuth);
  const [inviteCode, setInviteCode] = useState('');
  const [redeemLoading, setRedeemLoading] = useState(false);
  const [paymentLoading, setPaymentLoading] = useState(false);
  const [redeemMessage, setRedeemMessage] = useState({ type: '', text: '' });

  const handleSubscribe = async (planType) => {
    setPaymentLoading(true);
    try {
      const res = await fetchWithAuth('/payments.php?action=create_preference', {
        method: 'POST',
        body: { plan_type: planType }
      });
      if (res.init_point) {
        window.location.href = res.init_point;
      }
    } catch (err) {
      alert('Erro ao iniciar pagamento: ' + err.message);
    } finally {
      setPaymentLoading(false);
    }
  };

  const handleRedeem = async (e) => {
    e.preventDefault();
    setRedeemLoading(true);
    setRedeemMessage({ type: '', text: '' });

    try {
      const res = await fetchWithAuth('/profile.php?action=redeem_code', {
        method: 'POST',
        body: { code: inviteCode }
      });
      setRedeemMessage({ type: 'success', text: res.message });
      setInviteCode('');
      mutate(); // Refresh user data to show new plan
    } catch (err) {
      setRedeemMessage({ type: 'error', text: err.message });
    } finally {
      setRedeemLoading(false);
    }
  };

  if (error) return <div className="text-red-500">Erro ao carregar perfil.</div>;
  if (!data) return <div className="text-slate-500">Carregando perfil...</div>;

  const user = data.user;

  const getPlanStatus = () => {
     if (user.plan_type === 'lifetime' || user.is_admin === 1) {
        return { text: 'Acesso Vitalício / Ilimitado', color: 'text-green-600', bg: 'bg-green-100', isExpired: false };
     }

     if (!user.plan_expires_at) return { text: 'Plano Gratuito', color: 'text-slate-600', bg: 'bg-slate-100', isExpired: true };

     const expiresAt = new Date(user.plan_expires_at);
     const now = new Date();
     const daysLeft = differenceInDays(expiresAt, now);

     if (daysLeft < 0) {
        return { text: 'Expirado', color: 'text-red-600', bg: 'bg-red-100', isExpired: true };
     }

     if (user.plan_type === 'trial') {
        return { text: `Teste Grátis (${daysLeft} dias restantes)`, color: 'text-yellow-600', bg: 'bg-yellow-100', isExpired: false };
     }

     return { text: `Premium (Expira em ${format(expiresAt, 'dd/MM/yyyy')})`, color: 'text-blue-600', bg: 'bg-blue-100', isExpired: false };
  };

  const status = getPlanStatus();

  return (
    <div className="max-w-3xl mx-auto space-y-6">
      <h2 className="text-2xl font-bold text-navy-900 flex items-center gap-2">
        <User size={24} className="text-highlight" /> Meu Perfil
      </h2>

      {/* User Info */}
      <div className="bg-white p-6 rounded-lg shadow-sm border border-slate-200">
        <div className="flex items-start justify-between">
           <div>
              <h3 className="text-lg font-bold text-navy-900">{user.name}</h3>
              <p className="text-slate-500">{user.email}</p>
           </div>
           <span className={`px-3 py-1 rounded-full text-xs font-bold ${status.bg} ${status.color}`}>
              {status.text}
           </span>
        </div>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
         {/* Subscription Plans */}
         <div className="bg-white p-6 rounded-lg shadow-sm border border-slate-200">
            <h3 className="text-lg font-bold text-navy-900 flex items-center gap-2 mb-4 border-b border-slate-100 pb-2">
               <CreditCard size={20} className="text-highlight" /> Assinatura (Mercado Pago)
            </h3>

            <p className="text-sm text-slate-600 mb-6">
               O Planer oferece todas as ferramentas profissionais para organizar suas aulas.
               Escolha o plano ideal e libere exportação para PDF, calendário completo e estatísticas.
            </p>

            <div className="space-y-4">
               <div className="border border-slate-200 rounded-lg p-4 relative overflow-hidden">
                  <div className="absolute top-0 right-0 bg-blue-100 text-blue-800 text-xs font-bold px-2 py-1 rounded-bl-lg">Mais Popular</div>
                  <h4 className="font-bold text-navy-900">Plano Anual</h4>
                  <p className="text-2xl font-bold text-highlight my-1">R$ 149<span className="text-sm text-slate-500 font-normal">/ano</span></p>
                  <p className="text-xs text-slate-500 mb-3">Apenas R$ 12,41 por mês.</p>
                  <button
                     onClick={() => handleSubscribe('yearly')}
                     disabled={paymentLoading}
                     className="w-full flex items-center justify-center gap-2 py-2 bg-navy-800 text-white rounded hover:bg-navy-900 font-medium transition-colors text-sm disabled:opacity-50"
                  >
                     {paymentLoading ? 'Aguarde...' : 'Assinar Anual (Mercado Pago)'} <ExternalLink size={14} />
                  </button>
               </div>

               <div className="border border-slate-200 rounded-lg p-4">
                  <h4 className="font-bold text-navy-900">Plano Mensal</h4>
                  <p className="text-2xl font-bold text-slate-700 my-1">R$ 19,90<span className="text-sm text-slate-500 font-normal">/mês</span></p>
                  <button
                     onClick={() => handleSubscribe('monthly')}
                     disabled={paymentLoading}
                     className="w-full flex items-center justify-center gap-2 py-2 bg-slate-100 text-slate-700 rounded hover:bg-slate-200 font-medium transition-colors text-sm mt-3 disabled:opacity-50"
                  >
                     {paymentLoading ? 'Aguarde...' : 'Assinar Mensal (Mercado Pago)'} <ExternalLink size={14} />
                  </button>
               </div>
            </div>
         </div>

         {/* Redeem Invite Code */}
         <div className="bg-white p-6 rounded-lg shadow-sm border border-slate-200 h-fit">
            <h3 className="text-lg font-bold text-navy-900 flex items-center gap-2 mb-4 border-b border-slate-100 pb-2">
               <Key size={20} className="text-highlight" /> Resgatar Código
            </h3>
            <p className="text-sm text-slate-600 mb-4">
               Recebeu um código de convite para acesso vitalício? Digite abaixo para liberar sua conta.
            </p>

            {redeemMessage.text && (
               <div className={`p-3 mb-4 rounded text-sm ${redeemMessage.type === 'success' ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-red-50 text-red-700 border border-red-200'}`}>
                  {redeemMessage.text}
               </div>
            )}

            <form onSubmit={handleRedeem} className="space-y-4">
               <div>
                  <input
                     type="text"
                     value={inviteCode}
                     onChange={(e) => setInviteCode(e.target.value.toUpperCase())}
                     placeholder="Ex: AB12CD34"
                     className="w-full rounded-md border-slate-300 shadow-sm focus:border-highlight focus:ring focus:ring-highlight focus:ring-opacity-50 py-2 px-3 border uppercase font-mono tracking-widest text-center"
                     required
                  />
               </div>
               <button
                  type="submit"
                  disabled={redeemLoading || !inviteCode}
                  className="w-full flex items-center justify-center gap-2 py-2 bg-highlight text-white rounded hover:bg-highlight-hover font-medium transition-colors disabled:opacity-50"
               >
                  <CheckCircle size={18} />
                  {redeemLoading ? 'Validando...' : 'Ativar Código'}
               </button>
            </form>
         </div>
      </div>
    </div>
  );
}