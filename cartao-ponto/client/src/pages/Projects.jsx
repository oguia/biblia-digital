import { useState, useEffect } from 'react';
import axios from 'axios';
import { Plus, Edit2, Trash2, Save, X } from 'lucide-react';

export default function Projects() {
  const [projects, setProjects] = useState([]);
  const [loading, setLoading] = useState(true);
  const [showForm, setShowForm] = useState(false);

  const initialForm = { id: null, name: '', description: '', rate_type: 'hourly', rate_amount: '', expected_hours: 8, active: 1 };
  const [formData, setFormData] = useState(initialForm);

  const loadProjects = async () => {
    try {
      const res = await axios.get('/projects.php');
      setProjects(res.data.projects || []);
    } catch (err) {
      console.error(err);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    loadProjects();
  }, []);

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      if (formData.id) {
        await axios.put(`/projects.php?id=${formData.id}`, formData);
      } else {
        await axios.post('/projects.php', formData);
      }
      loadProjects();
      setShowForm(false);
      setFormData(initialForm);
    } catch (err) {
      alert('Erro ao salvar projeto');
    }
  };

  const handleEdit = (proj) => {
    setFormData(proj);
    setShowForm(true);
  };

  const handleDelete = async (id) => {
    if (confirm('Tem certeza que deseja excluir este projeto/serviço?')) {
      try {
        await axios.delete(`/projects.php?id=${id}`);
        loadProjects();
      } catch (err) {
        alert('Erro ao excluir projeto');
      }
    }
  };

  const formatCurrency = (val) => Number(val).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });

  if (loading) return <div className="p-8 text-center text-gray-500">Carregando projetos...</div>;

  return (
    <div className="max-w-4xl mx-auto space-y-6">
      <div className="flex justify-between items-center">
        <h1 className="text-2xl font-bold text-gray-800">Projetos & Serviços</h1>
        {!showForm && (
          <button
            onClick={() => { setFormData(initialForm); setShowForm(true); }}
            className="flex items-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium shadow-sm transition-colors"
          >
            <Plus className="h-5 w-5 mr-1" /> Adicionar
          </button>
        )}
      </div>

      {showForm && (
        <div className="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
          <div className="flex justify-between items-center mb-4">
            <h2 className="text-lg font-bold text-gray-800">{formData.id ? 'Editar Serviço' : 'Novo Serviço'}</h2>
            <button onClick={() => setShowForm(false)} className="text-gray-400 hover:text-gray-600"><X className="h-5 w-5"/></button>
          </div>
          <form onSubmit={handleSubmit} className="space-y-4">
            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">Nome do Projeto/Serviço *</label>
                <input required type="text" value={formData.name} onChange={e => setFormData({...formData, name: e.target.value})} className="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" placeholder="Ex: Cliente XYZ, Manutenção, etc." />
              </div>
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">Descrição</label>
                <input type="text" value={formData.description || ''} onChange={e => setFormData({...formData, description: e.target.value})} className="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" placeholder="Opcional" />
              </div>

              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">Tipo de Cobrança</label>
                <select value={formData.rate_type} onChange={e => setFormData({...formData, rate_type: e.target.value})} className="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                  <option value="hourly">Por Hora</option>
                  <option value="daily">Por Dia (Diária)</option>
                  <option value="monthly">Por Mês (Fixo)</option>
                </select>
              </div>
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">Valor do Serviço (R$) *</label>
                <input required type="number" step="0.01" min="0" value={formData.rate_amount} onChange={e => setFormData({...formData, rate_amount: e.target.value})} className="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" placeholder="Ex: 50.00" />
              </div>

              {formData.rate_type !== 'hourly' && (
                <div className="md:col-span-2 bg-blue-50 p-4 rounded-md border border-blue-100">
                  <label className="block text-sm font-medium text-blue-800 mb-1">
                    {formData.rate_type === 'daily' ? 'Horas esperadas por dia' : 'Horas esperadas por mês'}
                  </label>
                  <p className="text-xs text-blue-600 mb-2">Usado para calcular a proporção de ganhos no cronômetro.</p>
                  <input required type="number" step="0.5" min="1" value={formData.expected_hours} onChange={e => setFormData({...formData, expected_hours: e.target.value})} className="w-full px-3 py-2 border border-blue-300 rounded-md focus:ring-blue-500 focus:border-blue-500" placeholder="Ex: 8 (diário) ou 220 (mensal)" />
                </div>
              )}
            </div>

            <div className="flex justify-end pt-4 space-x-3 border-t border-gray-100">
              <button type="button" onClick={() => setShowForm(false)} className="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">Cancelar</button>
              <button type="submit" className="flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-sm transition-colors">
                <Save className="h-4 w-4 mr-2" /> Salvar Projeto
              </button>
            </div>
          </form>
        </div>
      )}

      {/* Projects List */}
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        {projects.length === 0 && !showForm && (
          <div className="col-span-full py-12 text-center text-gray-500 bg-white rounded-xl border border-gray-200 border-dashed">
            Nenhum serviço cadastrado ainda. Clique em "Adicionar" para começar.
          </div>
        )}

        {projects.map(proj => (
          <div key={proj.id} className={`bg-white p-5 rounded-xl shadow-sm border border-gray-200 flex flex-col justify-between ${proj.active == 0 ? 'opacity-60' : ''}`}>
            <div>
              <div className="flex justify-between items-start mb-2">
                <h3 className="font-bold text-gray-800 text-lg truncate pr-2">{proj.name}</h3>
                <span className={`px-2 py-1 text-xs font-semibold rounded-full ${proj.rate_type === 'hourly' ? 'bg-purple-100 text-purple-700' : proj.rate_type === 'daily' ? 'bg-orange-100 text-orange-700' : 'bg-green-100 text-green-700'}`}>
                  {proj.rate_type === 'hourly' ? 'Hora' : proj.rate_type === 'daily' ? 'Dia' : 'Mês'}
                </span>
              </div>
              {proj.description && <p className="text-gray-500 text-sm mb-3 line-clamp-2">{proj.description}</p>}

              <div className="mt-4 bg-gray-50 p-3 rounded-lg flex justify-between items-center border border-gray-100">
                <span className="text-sm text-gray-600">Valor</span>
                <span className="font-bold text-gray-800">{formatCurrency(proj.rate_amount)}</span>
              </div>
            </div>

            <div className="flex justify-end mt-4 pt-3 border-t border-gray-100 space-x-2">
              <button onClick={() => handleEdit(proj)} className="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Editar">
                <Edit2 className="h-4 w-4" />
              </button>
              <button onClick={() => handleDelete(proj.id)} className="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Excluir">
                <Trash2 className="h-4 w-4" />
              </button>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
}
