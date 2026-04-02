import React, { useState, useEffect } from 'react';
import { Pill, Users, Plus, AlertTriangle, ArrowLeft, Search, X, MapPin } from 'lucide-react';

export default function Medications({ setView }) {
  const [patients, setPatients] = useState([]);
  const [selectedPatient, setSelectedPatient] = useState(null);
  const [medications, setMedications] = useState([]);
  const [loading, setLoading] = useState(true);

  // New medication form
  const [showForm, setShowForm] = useState(false);
  const [form, setForm] = useState({ name: '', dosage: '', stock_current: 30, times_per_day: 1, photo_url: '' });

  // Price search modal
  const [searchModal, setSearchModal] = useState({ show: false, medName: '', results: null, loading: false });

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

  const openSearchModal = async (medName) => {
    setSearchModal({ show: true, medName, results: null, loading: true });

    try {
      const currentToken = localStorage.getItem('vouzelar_token') || localStorage.getItem('auth_token');
      const baseUrl = window.location.href.includes('localhost') ? 'http://localhost:8000' : 'api';
      const res = await fetch(`${baseUrl}/caregiver.php?action=search_prices&q=${encodeURIComponent(medName)}`, {
        headers: {
          'Authorization': `Bearer ${currentToken}`
        }
      });

      // Add artificial delay to give the "searching" UX feeling
      await new Promise(resolve => setTimeout(resolve, 1500));

      if (res.ok) {
        const data = await res.json();
        if (data.results && data.results.length > 0) {
          // Force the first item to be the "Best"
          data.results[0].isBest = true;

          setSearchModal({
            show: true,
            medName,
            loading: false,
            results: data.results
          });
        } else {
            setSearchModal({ show: false, medName: '', results: null, loading: false });
        }
      } else {
        setSearchModal({ show: false, medName: '', results: null, loading: false });
      }
    } catch (err) {
      console.error('Erro na busca de preços:', err);
      setSearchModal({ show: false, medName: '', results: null, loading: false });
    }
  };

  const handleAddMed = async (e) => {
    e.preventDefault();
    const res = await fetch('./api/caregiver.php?action=medications', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'Authorization': `Bearer ${token}` },
      body: JSON.stringify({ ...form, patient_id: selectedPatient })
    });
    if (res.ok) {
      setForm({ name: '', dosage: '', stock_current: 30, times_per_day: 1, photo_url: '' });
      setShowForm(false);
      fetchMedications(selectedPatient);
    }
  };

  const handlePhotoUpload = (e) => {
    const file = e.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onloadend = () => {
        setForm({ ...form, photo_url: reader.result });
      };
      reader.readAsDataURL(file);
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
                  <div>
                    <label className="block text-sm text-gray-600 mb-1">Foto da Caixa (Opcional):</label>
                    <input type="file" accept="image/*" onChange={handlePhotoUpload} className="w-full border p-2 rounded text-sm bg-gray-50" />
                    {form.photo_url && (
                      <div className="mt-2 h-24 w-24 rounded-lg border overflow-hidden">
                        <img src={form.photo_url} alt="Preview" className="w-full h-full object-cover" />
                      </div>
                    )}
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
                    {med.photo_url ? (
                      <div className="h-14 w-14 rounded-lg overflow-hidden border border-gray-100 flex-shrink-0">
                        <img src={med.photo_url} alt={med.name} className="w-full h-full object-cover" />
                      </div>
                    ) : (
                      <div className={`p-3 rounded-full flex-shrink-0 ${med.stock_status === 'low' ? 'bg-red-50 text-red-500' : 'bg-primary-50 text-primary-500'}`}>
                        <Pill size={24} />
                      </div>
                    )}
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

                      <button
                        onClick={() => openSearchModal(med.name)}
                        className="mt-3 inline-flex items-center gap-1 text-xs font-bold text-blue-600 bg-blue-50 px-3 py-1.5 rounded-lg hover:bg-blue-100 transition-colors border border-blue-100"
                      >
                        <Search size={14} /> Buscar Menor Preço na Região
                      </button>
                    </div>
                  </div>
                ))}
              </div>
            )}
          </>
        )}
      </main>

      {/* Internal Search Modal */}
      {searchModal.show && (
        <div className="fixed inset-0 bg-black/50 z-[60] flex items-center justify-center p-4">
          <div className="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden animate-fade-in flex flex-col max-h-[90vh]">
            <div className="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
              <h2 className="font-bold text-gray-900 flex items-center gap-2">
                <Search size={18} className="text-primary-600" />
                Busca de Ofertas
              </h2>
              <button onClick={() => setSearchModal({ show: false, medName: '', results: null, loading: false })} className="p-2 hover:bg-gray-200 rounded-full text-gray-500">
                <X size={20} />
              </button>
            </div>

            <div className="p-4 md:p-6 overflow-y-auto">
              <p className="text-sm text-gray-600 mb-4">
                Procurando por: <strong className="text-gray-900">{searchModal.medName}</strong>
                {(() => {
                  const user = JSON.parse(localStorage.getItem('vouzelar_user') || '{}');
                  if (user.city && user.state) {
                    return ` nas farmácias próximas a ${user.city} - ${user.state}.`;
                  }
                  return ' nas farmácias próximas à sua localização.';
                })()}
              </p>

              {searchModal.loading ? (
                <div className="flex flex-col items-center justify-center py-10 space-y-4">
                  <div className="w-10 h-10 border-4 border-gray-200 border-t-primary-600 rounded-full animate-spin"></div>
                  <p className="text-gray-500 text-sm animate-pulse">Consultando catálogos locais...</p>
                </div>
              ) : (
                <div className="space-y-3">
                  {searchModal.results.map((pharmacy, i) => (
                    <div key={i} className={`p-4 rounded-xl border ${pharmacy.isBest ? 'border-primary-300 bg-primary-50 relative' : 'border-gray-200'}`}>
                      {pharmacy.isBest && (
                        <div className="absolute -top-2.5 right-4 bg-primary-600 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-sm">
                          MAIS BARATO
                        </div>
                      )}
                      <div className="flex justify-between items-start">
                        <div>
                          <h4 className="font-bold text-gray-900">{pharmacy.pharmacy}</h4>
                          {pharmacy.product_name && <p className="text-xs text-gray-700 mt-1 line-clamp-2">{pharmacy.product_name}</p>}
                          <p className="text-xs text-gray-500 flex items-center gap-1 mt-1"><MapPin size={12}/> {pharmacy.distance}</p>
                        </div>
                        <div className="text-right">
                          <p className="text-sm text-gray-500">Por</p>
                          <p className={`text-xl font-bold ${pharmacy.isBest ? 'text-primary-700' : 'text-gray-900'}`}>
                            R$ {pharmacy.price}
                          </p>
                        </div>
                      </div>
                      <a href={pharmacy.link} target="_blank" rel="noopener noreferrer" className={`mt-3 block text-center w-full py-2 rounded-lg text-sm font-bold transition-colors ${pharmacy.isBest ? 'bg-primary-600 hover:bg-primary-700 text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-700'}`}>
                        Comprar Agora
                      </a>
                    </div>
                  ))}

                  <p className="text-[11px] text-gray-400 mt-4 text-center leading-tight">
                    *Os preços são atualizados periodicamente através de sites parceiros e podem variar de acordo com a região e disponibilidade da farmácia selecionada.
                  </p>
                </div>
              )}
            </div>
          </div>
        </div>
      )}
    </div>
  );
}
