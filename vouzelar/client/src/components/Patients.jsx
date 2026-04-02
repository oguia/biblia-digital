import React, { useState, useEffect } from 'react';
import { ArrowLeft, Users, Plus, UserPlus, FileText } from 'lucide-react';

export default function Patients({ setView }) {
  const [patients, setPatients] = useState([]);
  const [loading, setLoading] = useState(true);

  const [showForm, setShowForm] = useState(false);
  const [form, setForm] = useState({ name: '', login: '', password: '' });
  const [error, setError] = useState('');

  const token = localStorage.getItem('vouzelar_token');

  useEffect(() => {
    fetchPatients();
  }, []);

  const fetchPatients = async () => {
    setLoading(true);
    const res = await fetch('./api/caregiver.php?action=patients', {
      headers: { 'Authorization': `Bearer ${token}` }
    });
    if (res.ok) {
      const data = await res.json();
      setPatients(data);
    }
    setLoading(false);
  };

  const handleAddPatient = async (e) => {
    e.preventDefault();
    setError('');

    const res = await fetch('./api/caregiver.php?action=patients', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'Authorization': `Bearer ${token}` },
      body: JSON.stringify(form)
    });

    if (res.ok) {
      setShowForm(false);
      setForm({ name: '', login: '', password: '' });
      fetchPatients();
    } else {
      const data = await res.json();
      setError(data.error || 'Erro ao cadastrar paciente.');
    }
  };

  return (
    <div className="min-h-screen bg-gray-50 pb-20">
      <header className="bg-white text-gray-900 p-4 shadow-sm flex items-center gap-3 sticky top-0 z-10">
        <button onClick={() => setView('dashboard')} className="p-2 hover:bg-gray-100 rounded-full">
          <ArrowLeft size={20} />
        </button>
        <h1 className="text-xl font-bold">Pacientes</h1>
      </header>

      <main className="p-4 max-w-lg mx-auto space-y-4">
        <div className="flex justify-between items-center mb-2">
          <h2 className="font-semibold text-gray-900 text-lg">Perfis de Idosos</h2>
          <button
            onClick={() => setShowForm(!showForm)}
            className="text-primary-600 hover:text-primary-700 flex items-center text-sm font-medium"
          >
            <Plus size={16} className="mr-1" /> Adicionar
          </button>
        </div>

        {showForm && (
          <form onSubmit={handleAddPatient} className="bg-white p-4 rounded-xl shadow-sm border border-primary-200 mb-4 animate-fade-in">
            <h3 className="font-medium text-gray-800 mb-3">Novo Paciente</h3>
            {error && <p className="text-red-500 text-sm mb-2">{error}</p>}
            <div className="space-y-3">
              <input type="text" placeholder="Nome Completo (Ex: Dona Maria)" required className="w-full border p-2 rounded" value={form.name} onChange={e => setForm({...form, name: e.target.value})} />
              <input type="text" placeholder="Login p/ o Paciente (Ex: maria123)" required className="w-full border p-2 rounded" value={form.login} onChange={e => setForm({...form, login: e.target.value})} />
              <input type="text" placeholder="Senha do Paciente" required className="w-full border p-2 rounded" value={form.password} onChange={e => setForm({...form, password: e.target.value})} />
              <button type="submit" className="w-full bg-primary-600 text-white p-2 rounded font-medium mt-2">Criar Perfil</button>
            </div>
            <p className="text-xs text-gray-500 mt-3 text-center">
              Use este login e senha no celular do paciente para acessar a interface simplificada.
            </p>
          </form>
        )}

        {loading ? <p className="text-center py-4 text-gray-500">Carregando...</p> : (
          <div className="space-y-3">
            {patients.length === 0 ? (
              <div className="text-center py-8 bg-white rounded-xl shadow-sm border border-dashed border-gray-300">
                <UserPlus className="mx-auto h-12 w-12 text-gray-300 mb-3" />
                <p className="text-gray-500">Nenhum paciente cadastrado.</p>
              </div>
            ) : patients.map(p => (
              <div key={p.id} className="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col gap-3">
                <div className="flex items-center justify-between">
                  <div className="flex items-center gap-3">
                    <div className="bg-primary-50 p-3 rounded-full text-primary-600">
                      <Users size={24} />
                    </div>
                    <div>
                      <h3 className="font-bold text-gray-900">{p.name}</h3>
                      <p className="text-sm text-gray-500">Login: {p.email}</p>
                    </div>
                  </div>
                  <button
                    onClick={() => setView('medications')}
                    className="text-primary-600 text-sm font-medium"
                  >
                    Ver Remédios
                  </button>
                </div>

                <div className="border-t border-gray-100 pt-3 flex justify-end">
                   <button
                     onClick={async () => {
                        try {
                           const baseUrl = window.location.href.includes('localhost') ? 'http://localhost:8000' : 'api';
                           const res = await fetch(`${baseUrl}/caregiver.php?action=report&patient_id=${p.id}`, {
                             headers: { 'Authorization': `Bearer ${token}` }
                           });
                           if (res.ok) {
                              const data = await res.json();

                              // Generate simple HTML for printing (acting as PDF)
                              const printWindow = window.open('', '_blank');
                              printWindow.document.write(`
                                <html>
                                  <head>
                                    <title>Relatório de Saúde - ${data.patient_name}</title>
                                    <style>
                                      body { font-family: Arial, sans-serif; padding: 20px; color: #333; }
                                      h1 { color: #1a56db; border-bottom: 2px solid #e5e7eb; padding-bottom: 10px; }
                                      .stats { display: flex; gap: 20px; margin-bottom: 30px; }
                                      .stat-box { background: #f3f4f6; padding: 15px; border-radius: 8px; flex: 1; text-align: center; }
                                      .stat-box h2 { margin: 0; font-size: 24px; color: #111827; }
                                      .stat-box p { margin: 5px 0 0; font-size: 14px; color: #6b7280; }
                                      table { w-full; border-collapse: collapse; margin-top: 20px; width: 100%; }
                                      th, td { border: 1px solid #d1d5db; padding: 10px; text-align: left; }
                                      th { background: #f9fafb; font-weight: bold; }
                                      .taken { color: #059669; font-weight: bold; }
                                      .missed { color: #dc2626; font-weight: bold; }
                                    </style>
                                  </head>
                                  <body>
                                    <h1>Relatório de Adesão ao Tratamento</h1>

                                    <div class="stats">
                                      <div class="stat-box">
                                        <p>Paciente</p>
                                        <h2>${data.patient_name}</h2>
                                      </div>
                                      <div class="stat-box">
                                        <p>Taxa de Adesão Geral</p>
                                        <h2>${data.adherence_rate}%</h2>
                                      </div>
                                      <div class="stat-box">
                                        <p>Período de Análise</p>
                                        <h2>Últimas 100 doses</h2>
                                      </div>
                                    </div>

                                    <h3>Histórico de Doses</h3>
                                    <table>
                                      <thead>
                                        <tr>
                                          <th>Data/Hora Programada</th>
                                          <th>Medicamento</th>
                                          <th>Status</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                        ${data.history.length === 0 ? '<tr><td colspan="3" style="text-align:center">Nenhum histórico encontrado.</td></tr>' :
                                          data.history.map(h => `
                                            <tr>
                                              <td>${new Date(h.scheduled_time).toLocaleString('pt-BR')}</td>
                                              <td>${h.medication_name}</td>
                                              <td class="${h.status === 'taken' ? 'taken' : (h.status === 'missed' ? 'missed' : '')}">
                                                ${h.status === 'taken' ? 'Tomado' : (h.status === 'missed' ? 'Esquecido' : 'Pendente')}
                                              </td>
                                            </tr>
                                          `).join('')
                                        }
                                      </tbody>
                                    </table>

                                    <p style="margin-top: 40px; text-align: center; font-size: 12px; color: #9ca3af;">
                                      Gerado por VouZelar App em ${new Date().toLocaleDateString('pt-BR')}
                                    </p>
                                  </body>
                                </html>
                              `);
                              printWindow.document.close();
                              setTimeout(() => {
                                printWindow.print();
                              }, 500);
                           } else {
                              alert("Erro ao gerar relatório.");
                           }
                        } catch(e) {
                           console.error(e);
                           alert("Erro de conexão.");
                        }
                     }}
                     className="flex items-center gap-1 text-sm font-bold text-gray-700 bg-gray-100 hover:bg-gray-200 px-3 py-1.5 rounded-lg transition-colors"
                   >
                     <FileText size={16} /> Gerar PDF do Histórico
                   </button>
                </div>
              </div>
            ))}
          </div>
        )}
      </main>
    </div>
  );
}
