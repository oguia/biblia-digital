import React, { useState } from 'react';
import { useLocation } from 'wouter';
import { LogOut, Bell, Pill, Users } from 'lucide-react';
import Medications from '../components/Medications';
import Patients from '../components/Patients';
import Profile from '../components/Profile';
import SuperAdmin from '../components/SuperAdmin';
import { Settings, Shield } from 'lucide-react';

export default function Dashboard() {
  const [, setLocation] = useLocation();
  const [user, setUser] = useState(() => JSON.parse(localStorage.getItem('vouzelar_user')));
  const [view, setView] = useState('dashboard'); // 'dashboard', 'medications', 'patients'

  const handleLogout = () => {
    localStorage.removeItem('vouzelar_token');
    localStorage.removeItem('vouzelar_user');
    setLocation('/login');
  };

  const requestNotificationPermission = async () => {
    try {
      if ('Notification' in window) {
        const permission = await Notification.requestPermission();
        if (permission === 'granted') {
          alert('Notificações ativadas! Você receberá alertas do VouZelar.');
        } else if (permission === 'denied') {
          alert('Você negou a permissão ou o navegador bloqueou automaticamente. Nota: Notificações Push requerem conexão segura (HTTPS). Se estiver testando localmente sem HTTPS, o navegador bloqueará o pedido automaticamente.');
        } else {
           alert('Permissão de notificação não foi concedida (' + permission + ').');
        }
      } else {
         alert('Seu navegador não suporta notificações Push.');
      }
    } catch (e) {
      console.error(e);
      alert('Erro ao solicitar notificações: ' + e.message);
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
        <div className="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded shadow-sm">
          <div className="flex items-start">
            <div className="flex-shrink-0">
              <Bell className="h-5 w-5 text-yellow-400" />
            </div>
            <div className="ml-3">
              <p className="text-sm text-yellow-700">
                Seu período de testes de 7 dias termina em breve.
                <a href="#" className="font-medium underline ml-1">Assinar Agora</a>
              </p>
            </div>
          </div>
        </div>

        {/* Enable Notifications Call to Action */}
        <div className="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
          <div>
            <h3 className="font-medium text-gray-900">Alertas de Remédios</h3>
            <p className="text-sm text-gray-500">Seja avisado quando esquecerem</p>
          </div>
          <button
            onClick={requestNotificationPermission}
            className="bg-primary-100 text-primary-700 px-3 py-1.5 rounded-lg text-sm font-medium hover:bg-primary-200"
          >
            Ativar Push
          </button>
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
             <div className="flex gap-3 text-sm">
               <div className="w-2 h-2 rounded-full bg-red-500 mt-1.5"></div>
               <div>
                 <p className="text-gray-800 font-medium">Cadastre um paciente primeiro!</p>
                 <p className="text-gray-500">Clique em Pacientes para adicionar o perfil do idoso.</p>
               </div>
             </div>
          </div>
        </div>

      </main>
    </div>
  );
}
