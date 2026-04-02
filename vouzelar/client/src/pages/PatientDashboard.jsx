import React, { useState, useEffect } from 'react';
import { useLocation } from 'wouter';
import { LogOut, CheckCircle, Clock } from 'lucide-react';

export default function PatientDashboard() {
  const [, setLocation] = useLocation();
  const [user, setUser] = useState(null);
  const [meds, setMeds] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const userStr = localStorage.getItem('vouzelar_user');
    if (!userStr) {
      setLocation('/login');
      return;
    }
    setUser(JSON.parse(userStr));
    fetchMeds();
  }, []);

  const fetchMeds = async () => {
    const token = localStorage.getItem('vouzelar_token');
    try {
      const res = await fetch('./api/patient.php?action=today_meds', {
        headers: { 'Authorization': `Bearer ${token}` }
      });
      if (res.ok) {
        const data = await res.json();
        setMeds(data);
      }
    } finally {
      setLoading(false);
    }
  };

  const handleTakeMed = async (medId) => {
    const token = localStorage.getItem('vouzelar_token');
    await fetch('./api/patient.php?action=take_med', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'Authorization': `Bearer ${token}` },
      body: JSON.stringify({ medication_id: medId })
    });

    // Optimistic UI update
    setMeds(meds.map(m => m.id === medId ? { ...m, status: 'taken' } : m));

    // Simple TTS feedback for onboarding/accessibility
    if ('speechSynthesis' in window) {
      const utterance = new SpeechSynthesisUtterance("Muito bem, remédio tomado!");
      utterance.lang = "pt-BR";
      window.speechSynthesis.speak(utterance);
    }
  };

  const handleLogout = () => {
    localStorage.removeItem('vouzelar_token');
    localStorage.removeItem('vouzelar_user');
    setLocation('/login');
  };

  if (!user || loading) return <div className="p-8 text-2xl text-center mt-20">Carregando seus remédios...</div>;

  const pendingMeds = meds.filter(m => m.status === 'pending');
  const takenMeds = meds.filter(m => m.status === 'taken');

  return (
    <div className="min-h-screen bg-gray-50 flex flex-col">
      <header className="bg-white p-6 shadow-sm flex justify-between items-center">
        <div>
          <h1 className="text-3xl font-bold text-gray-900">Olá, {user.name}</h1>
          <p className="text-xl text-gray-600 mt-1">Sua lista de hoje:</p>
        </div>
        <button onClick={handleLogout} className="p-4 bg-gray-100 rounded-full active:bg-gray-200">
          <LogOut size={32} className="text-gray-700" />
        </button>
      </header>

      <main className="flex-1 p-4 max-w-2xl mx-auto w-full flex flex-col gap-6 mt-4">

        {pendingMeds.length === 0 ? (
          <div className="bg-green-100 text-green-800 p-8 rounded-3xl text-center shadow-sm">
            <CheckCircle size={64} className="mx-auto mb-4" />
            <h2 className="text-4xl font-bold mb-2">Tudo certo!</h2>
            <p className="text-2xl">Você já tomou todos os remédios de agora.</p>
          </div>
        ) : (
          <div className="space-y-6">
            <h2 className="text-2xl font-semibold text-gray-800 px-2">Para tomar agora:</h2>
            {pendingMeds.map(med => (
              <div key={med.id} className="bg-white rounded-3xl shadow-lg border-2 border-gray-100 overflow-hidden">
                <div className="p-6 bg-gray-50 border-b border-gray-100">
                  <h3 className="text-3xl font-bold text-gray-900">{med.name}</h3>
                  <p className="text-2xl text-gray-600 mt-2">{med.dosage}</p>
                </div>
                <div className="p-4 flex flex-col gap-4">
                  <button
                    onClick={() => handleTakeMed(med.id)}
                    className="patient-btn bg-primary-500 text-white hover:bg-primary-600 patient-text"
                  >
                    <CheckCircle size={32} /> Já Tomei
                  </button>
                  <button className="patient-btn bg-yellow-100 text-yellow-800 hover:bg-yellow-200 patient-text">
                    <Clock size={32} /> Vou tomar depois
                  </button>
                </div>
              </div>
            ))}
          </div>
        )}

        {takenMeds.length > 0 && (
          <div className="mt-8">
            <h3 className="text-xl text-gray-500 font-medium mb-4 px-2">Já tomados hoje:</h3>
            <div className="space-y-3">
              {takenMeds.map(med => (
                <div key={med.id} className="bg-white/60 p-4 rounded-2xl flex items-center gap-3">
                  <CheckCircle className="text-green-500" size={28} />
                  <span className="text-xl text-gray-600 line-through">{med.name}</span>
                </div>
              ))}
            </div>
          </div>
        )}

      </main>
    </div>
  );
}
