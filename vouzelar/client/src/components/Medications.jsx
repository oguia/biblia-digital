import React, { useState, useEffect } from 'react';
import { Pill, Users, Plus, AlertTriangle, ArrowLeft } from 'lucide-react';

export default function Medications({ setView }) {
  const [patients, setPatients] = useState([]);
  const [selectedPatient, setSelectedPatient] = useState(null);
  const [medications, setMedications] = useState([]);
  const [loading, setLoading] = useState(true);

  // New medication form
  const [showForm, setShowForm] = useState(false);
  const [form, setForm] = useState({ name: '', dosage: '', stock_current: 30, times_per_day: 1 });

  const token = localStorage.getItem('vouzelar_token');

  useEffect(() => {
    fetchPatients();
  }, []);

  const fetchPatients = async () => {
    const res = await fetch('./api/caregiver.php?action=patients', {
      headers: { 'Authorization': `Bearer ${token}` }
    });
    if (res.ok) {
      const data = await res.json();
      setPatients(data);
      if (data.length > 0) {
        setSelectedPatient(data[0].id);
        fetchMedications(data[0].id);
      } else {
        setLoading(false);
      }
    }
  };

  const fetchMedications = async (patientId) => {
    setLoading(true);
    const res = await fetch(`./api/caregiver.php?action=medications&patient_id=${patientId}`, {
      headers: { 'Authorization': `Bearer ${token}` }
    });
    if (res.ok) {
      const data = await res.json();
      setMedications(data);
    }
    setLoading(false);
  };

  const handlePatientChange = (e) => {
    const id = e.target.value;
    setSelectedPatient(id);
    fetchMedications(id);
  };

  const handleAddMed = async (e) => {
    e.preventDefault();
    const res = await fetch('./api/caregiver.php?action=medications', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'Authorization': `Bearer ${token}` },
      body: JSON.stringify({ ...form, patient_id: selectedPatient })
    });
    if (res.ok) {
      setShowForm(false);
      fetchMedications(selectedPatient);
    }
  };

  return (
    <div className="min-h-screen bg-gray-50 pb-20">
      <header className="bg-white text-gray-900 p-4 shadow-sm flex items-center gap-3 sticky top-0 z-10">
        <button onClick={() => setView('dashboard')} className="p-2 hover:bg-gray-100 rounded-full">
          <ArrowLeft size={20} />
        </button>
        <h1 className="text-xl font-bold">Gerenciar Remédios</h1>
      </header>

      <main className="p-4 max-w-lg mx-auto space-y-4">
        {patients.length === 0 ? (
          <div className="bg-white p-6 rounded-xl text-center shadow-sm">
            <Users className="mx-auto h-12 w-12 text-gray-400 mb-3" />
            <p className="text-gray-600 mb-4">Você ainda não cadastrou nenhum paciente.</p>
            <button onClick={() => setView('patients')} className="bg-primary-600 text-white px-4 py-2 rounded-lg font-medium">
              Cadastrar Paciente
            </button>
          </div>
        ) : (
          <>
            <div className="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
              <label className="block text-sm font-medium text-gray-700 mb-1">Selecione o Paciente</label>
              <select
                value={selectedPatient || ''}
                onChange={handlePatientChange}
                className="block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm p-2 bg-gray-50"
              >
                {patients.map(p => (
                  <option key={p.id} value={p.id}>{p.name}</option>
                ))}
              </select>
            </div>

            <div className="flex justify-between items-center mt-6 mb-2">
              <h2 className="font-semibold text-gray-900 text-lg">Inventário Digital</h2>
              <button
                onClick={() => setShowForm(!showForm)}
                className="text-primary-600 hover:text-primary-700 flex items-center text-sm font-medium"
              >
                <Plus size={16} className="mr-1" /> Adicionar
              </button>
            </div>

            {showForm && (
              <form onSubmit={handleAddMed} className="bg-white p-4 rounded-xl shadow-sm border border-primary-200 mb-4 animate-fade-in">
                <div className="space-y-3">
                  <input type="text" placeholder="Nome do Remédio (Ex: Losartana)" required className="w-full border p-2 rounded" value={form.name} onChange={e => setForm({...form, name: e.target.value})} />
                  <div className="grid grid-cols-2 gap-2">
                    <input type="text" placeholder="Dosagem (Ex: 50mg)" className="w-full border p-2 rounded" value={form.dosage} onChange={e => setForm({...form, dosage: e.target.value})} />
                    <input type="number" placeholder="Estoque Atual" required className="w-full border p-2 rounded" value={form.stock_current} onChange={e => setForm({...form, stock_current: e.target.value})} />
                  </div>
                  <div className="flex items-center gap-2">
                    <span className="text-sm text-gray-600">Comprimidos por dia:</span>
                    <input type="number" min="1" required className="w-20 border p-2 rounded" value={form.times_per_day} onChange={e => setForm({...form, times_per_day: e.target.value})} />
                  </div>
                  <button type="submit" className="w-full bg-primary-600 text-white p-2 rounded font-medium mt-2">Salvar</button>
                </div>
              </form>
            )}

            {loading ? <p className="text-center py-4 text-gray-500">Carregando...</p> : (
              <div className="space-y-3">
                {medications.length === 0 ? (
                  <p className="text-center text-gray-500 py-4 bg-white rounded-xl shadow-sm border border-dashed border-gray-300">Nenhum remédio cadastrado.</p>
                ) : medications.map(med => (
                  <div key={med.id} className="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-start gap-4">
                    <div className={`p-3 rounded-full ${med.stock_status === 'low' ? 'bg-red-50 text-red-500' : 'bg-primary-50 text-primary-500'}`}>
                      <Pill size={24} />
                    </div>
                    <div className="flex-1">
                      <h3 className="font-bold text-gray-900">{med.name} <span className="text-xs text-gray-500 font-normal">{med.dosage}</span></h3>
                      <p className="text-sm text-gray-600 mt-1">Estoque: {med.stock_current} comp.</p>

                      {med.stock_status === 'low' ? (
                        <p className="text-xs text-red-600 font-medium mt-1 flex items-center">
                          <AlertTriangle size={12} className="mr-1" /> Acaba em {med.days_remaining} dias! Repor.
                        </p>
                      ) : (
                        <p className="text-xs text-green-600 font-medium mt-1">
                          Calculadora: Dura aprox. {med.days_remaining} dias
                        </p>
                      )}
                    </div>
                  </div>
                ))}
              </div>
            )}
          </>
        )}
      </main>
    </div>
  );
}
