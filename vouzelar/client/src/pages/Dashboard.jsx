import React, { useState, useEffect } from 'react';
import { useLocation } from 'wouter';
import { LogOut, Bell, Pill, Users, CheckCircle, Info, ShieldAlert } from 'lucide-react';
import Medications from '../components/Medications';
import Patients from '../components/Patients';
import Profile from '../components/Profile';
import SuperAdmin from '../components/SuperAdmin';
import { Settings, Shield } from 'lucide-react';
import { registerServiceWorker, subscribeUserToPush } from '../lib/pwa';

export default function Dashboard() {
  const [, setLocation] = useLocation();
  const [user, setUser] = useState(() => JSON.parse(localStorage.getItem('vouzelar_user')));
  const [view, setView] = useState('dashboard'); // 'dashboard', 'medications', 'patients'
  const [pushStatus, setPushStatus] = useState('default'); // 'default', 'granted', 'denied'
  const [isSubscribing, setIsSubscribing] = useState(false);
  const [alerts, setAlerts] = useState([]);
  const [recentDoses, setRecentDoses] = useState([]);

  useEffect(() => {
    if ('Notification' in window) {
      setPushStatus(Notification.permission);
    }
    fetchAlerts();
  }, []);

  const fetchAlerts = async () => {
    try {
      const token = localStorage.getItem('vouzelar_token');
      const baseUrl = window.location.href.includes('localhost') ? 'http://localhost:8000' : 'api';
      const res = await fetch(`${baseUrl}/caregiver.php?action=dashboard_stats`, {
        headers: { 'Authorization': `Bearer ${token}` }
      });
      if (res.ok) {
        const data = await res.json();
        setAlerts(data.alerts || []);
        setRecentDoses(data.recent_doses || []);
      }
    } catch (e) {
      console.error('Failed to fetch alerts:', e);
    }
  };

  const handleLogout = () => {
    localStorage.removeItem('vouzelar_token');
    localStorage.removeItem('vouzelar_user');
    setLocation('/login');
  };

  const requestNotificationPermission = async () => {
    try {
      if (!('Notification' in window) || !('serviceWorker' in navigator)) {
        alert('Seu navegador não suporta notificações Push.');
        return;
      }

      setIsSubscribing(true);
      const permission = await Notification.requestPermission();
      setPushStatus(permission);

      if (permission === 'granted') {
        // Obter VAPID key pública da API
        const token = localStorage.getItem('vouzelar_token');
        const baseUrl = window.location.href.includes('localhost') ? 'http://localhost:8000' : 'api';

        const keyResponse = await fetch(`${baseUrl}/vapid.php`, {
           headers: { 'Authorization': `Bearer ${token}` }
        });

        if (!keyResponse.ok) throw new Error('Falha ao obter chaves push');
        const { publicKey } = await keyResponse.json();

        // Registrar SW e Inscrever no Push
        await registerServiceWorker();
        const subscription = await subscribeUserToPush(publicKey);

        // Enviar a inscrição para o PHP guardar no banco (como token)
        await fetch(`${baseUrl}/profile.php`, {
            method: 'PUT',
            headers: {
              'Content-Type': 'application/json',
              'Authorization': `Bearer ${token}`
            },
            body: JSON.stringify({ push_token: JSON.stringify(subscription) })
        });

        alert('Notificações ativadas! Você receberá alertas do VouZelar.');
      } else if (permission === 'denied') {
        alert('Você negou a permissão ou o navegador bloqueou automaticamente. Nota: Notificações Push requerem conexão segura (HTTPS). Se estiver testando localmente sem HTTPS, o navegador bloqueará o pedido automaticamente.');
      }
    } catch (e) {
      console.error(e);
      alert('Erro ao solicitar notificações: ' + e.message);
    } finally {
      setIsSubscribing(false);
    }
  };

  if (!user) return <div className="p-8">Carregando...</div>;

  if (view === 'medications') {
    return <Medications setView={setView} />;
  }

  if (view === 'patients') {
    return <Patients setView={setView} />;
  }

  if (view === 'profile') {
    return <Profile setView={setView} />;
  }

  if (view === 'superadmin') {
    return <SuperAdmin setView={setView} />;
  }

  return (
    <div className="min-h-screen bg-gray-50 pb-20">
      <header className="bg-primary-600 text-white p-4 shadow-md flex justify-between items-center sticky top-0 z-10">
        <div className="flex items-center gap-3">
          <img src="/logo.png" alt="VouZelar" className="h-10 bg-white p-1 rounded-lg" />
          <div>
            <h1 className="text-xl font-bold">VouZelar</h1>
            <p className="text-sm opacity-90">Olá, {user.name} ({user.plan})</p>
          </div>
        </div>
        <div className="flex gap-2">
          <button onClick={() => setView('profile')} className="p-2 hover:bg-primary-700 rounded-full" title="Configurações de Perfil">
            <Settings size={20} />
          </button>
          <button onClick={handleLogout} className="p-2 hover:bg-primary-700 rounded-full" title="Sair">
            <LogOut size={20} />
          </button>
        </div>
      </header>

      <main className="p-4 max-w-lg mx-auto space-y-4">

        {/* Super Admin Secret Button */}
        {user.role === 'superadmin' && (
           <button
             onClick={() => setView('superadmin')}
             className="w-full bg-gray-900 text-yellow-400 p-3 rounded-xl shadow-md flex items-center justify-center gap-2 font-bold mb-4 hover:bg-black transition-colors"
           >
             <Shield size={20} /> Acessar Painel Global (Super Admin)
           </button>
        )}

        {/* Trial Notice */}
        {user.subscription_status !== 'lifetime' && user.subscription_status !== 'paid' && (
          <div className="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded shadow-sm">
            <div className="flex items-start">
              <div className="flex-shrink-0">
                <Bell className="h-5 w-5 text-yellow-400" />
              </div>
              <div className="ml-3">
                <p className="text-sm text-yellow-700">
                  Seu período de testes de 7 dias está ativo.
                  <button onClick={() => setView('profile')} className="font-bold underline ml-1 cursor-pointer hover:text-yellow-900">Ativar Assinatura Definitiva</button>
                </p>
              </div>
            </div>
          </div>
        )}

        {/* Enable Notifications Call to Action */}
        <div className="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col gap-3">
          <div className="flex items-center justify-between">
            <div>
              <h3 className="font-medium text-gray-900 flex items-center gap-2">
                <Bell size={18} className={pushStatus === 'granted' ? 'text-green-500' : 'text-gray-400'} />
                Alertas de Remédios
              </h3>
              <p className="text-sm text-gray-500">Seja avisado quando esquecerem</p>
            </div>
            {pushStatus === 'granted' ? (
              <span className="bg-green-50 text-green-600 px-3 py-1.5 rounded-lg text-sm font-bold flex items-center gap-1 border border-green-100">
                <CheckCircle size={16} /> Ativo
              </span>
            ) : (
              <button
                onClick={requestNotificationPermission}
                disabled={isSubscribing}
                className={`${isSubscribing ? 'opacity-50 cursor-not-allowed' : ''} bg-red-100 text-red-700 px-4 py-1.5 rounded-lg text-sm font-bold hover:bg-red-200 transition-colors border border-red-200 flex items-center gap-1`}
              >
                <ShieldAlert size={16} /> {isSubscribing ? 'Ativando...' : 'Ativar Push'}
              </button>
            )}
          </div>

          {pushStatus !== 'granted' && (
            <button
              onClick={() => alert("Para receber alertas:\n1. Clique em 'Ativar Push'\n2. Quando o navegador perguntar, escolha 'Permitir'.\n\nSe o botão não funcionar, clique no cadeado 🔒 na barra de endereços do seu navegador, vá em 'Permissões do site', ligue a opção de 'Notificações' e recarregue a página.")}
              className="text-left text-xs text-blue-600 font-medium hover:underline flex items-center gap-1"
            >
              <Info size={14} /> Tendo problemas para ativar? Veja como resolver.
            </button>
          )}
        </div>

        {/* Dashboard Grid */}
        <div className="grid grid-cols-2 gap-4">
          <button
            onClick={() => setView('medications')}
            className="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col items-center justify-center gap-2 hover:border-primary-300 transition-colors"
          >
            <div className="bg-primary-50 p-3 rounded-full text-primary-600">
              <Pill size={24} />
            </div>
            <span className="font-medium text-gray-700 text-sm">Estoque & Remédios</span>
          </button>

          <button
             onClick={() => setView('patients')}
             className="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col items-center justify-center gap-2 hover:border-primary-300 transition-colors"
          >
            <div className="bg-primary-50 p-3 rounded-full text-primary-600">
              <Users size={24} />
            </div>
            <span className="font-medium text-gray-700 text-sm">Pacientes</span>
          </button>
        </div>

        {/* Recent Updates */}
        <div className="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
          <h2 className="font-semibold text-gray-900 mb-4">Avisos Recentes</h2>
          <div className="space-y-3">
             {alerts.map((alert, idx) => (
               <div key={idx} className="flex gap-3 text-sm">
                 <div className={`w-2 h-2 rounded-full mt-1.5 flex-shrink-0 ${
                    alert.type === 'warning' ? 'bg-red-500' :
                    alert.type === 'success' ? 'bg-green-500' : 'bg-blue-500'
                 }`}></div>
                 <div>
                   <p className="text-gray-800 font-medium">{alert.title}</p>
                   <p className="text-gray-500">{alert.message}</p>
                 </div>
               </div>
             ))}
          </div>
        </div>

        {/* Historico de Doses */}
        <div className="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
          <h2 className="font-semibold text-gray-900 mb-4">Doses Confirmadas Recentemente</h2>
          <div className="space-y-3">
             {recentDoses.length === 0 ? (
               <p className="text-sm text-gray-500">Nenhuma dose registrada nas últimas horas.</p>
             ) : (
               recentDoses.map((dose) => {
                 const dateObj = new Date(dose.taken_at + 'Z'); // UTC
                 const formattedDate = dateObj.toLocaleDateString('pt-BR') + ' às ' + dateObj.toLocaleTimeString('pt-BR', {hour: '2-digit', minute:'2-digit'});
                 return (
                   <div key={dose.id} className="flex items-start gap-3 text-sm p-2 bg-gray-50 rounded-lg">
                     <div className="bg-green-100 text-green-600 p-1.5 rounded-full flex-shrink-0 mt-0.5">
                       <CheckCircle size={16} />
                     </div>
                     <div>
                       <p className="text-gray-800 font-medium">{dose.patient_name} tomou <span className="font-bold text-primary-700">{dose.medication_name}</span></p>
                       <p className="text-gray-500 text-xs">{formattedDate}</p>
                     </div>
                   </div>
                 );
               })
             )}
          </div>
        </div>

      </main>
    </div>
  );
}
