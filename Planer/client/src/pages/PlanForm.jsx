import React, { useState, useEffect } from 'react';
import { useLocation, useRoute } from 'wouter';
import { fetchWithAuth } from '../lib/api';
import { Save, ArrowLeft, Search, Plus, Trash2, X, AlertCircle } from 'lucide-react';
import useSWR from 'swr';

export default function PlanForm() {
  const [, setLocation] = useLocation();
  const [match, params] = useRoute('/plan/:id');
  const isEdit = match && params.id !== 'new';
  const planId = isEdit ? params.id : null;

  const [formData, setFormData] = useState({
    title: '',
    component: '',
    lesson_date: '',
    objects_of_knowledge: '',
    biblical_relation: '',
    activity_description: '',
    materials: '',
    skills: [] // Array of skill objects
  });

  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');

  // BNCC Search State
  const [searchQuery, setSearchQuery] = useState('');
  const [searchResults, setSearchResults] = useState([]);
  const [searching, setSearching] = useState(false);
  const [showSearch, setShowSearch] = useState(false);

  useEffect(() => {
    if (isEdit) {
      setLoading(true);
      fetchWithAuth(`/plans.php?action=get&id=${planId}`)
        .then(res => {
          setFormData({
            title: res.plan.title || '',
            component: res.plan.component || '',
            lesson_date: res.plan.lesson_date || '',
            objects_of_knowledge: res.plan.objects_of_knowledge || '',
            biblical_relation: res.plan.biblical_relation || '',
            activity_description: res.plan.activity_description || '',
            materials: res.plan.materials || '',
            skills: res.plan.skills || []
          });
        })
        .catch(err => setError(err.message))
        .finally(() => setLoading(false));
    }
  }, [isEdit, planId]);

  useEffect(() => {
    const delayDebounceFn = setTimeout(() => {
      if (searchQuery.length >= 2) {
        setSearching(true);
        fetchWithAuth(`/plans.php?action=bncc_search&q=${searchQuery}`)
          .then(res => setSearchResults(res.skills))
          .catch(err => console.error(err))
          .finally(() => setSearching(false));
      } else {
        setSearchResults([]);
      }
    }, 500);

    return () => clearTimeout(delayDebounceFn);
  }, [searchQuery]);

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData(prev => ({ ...prev, [name]: value }));
  };

  const handleAddSkill = (skill) => {
    if (!formData.skills.find(s => s.id === skill.id)) {
      setFormData(prev => ({ ...prev, skills: [...prev.skills, skill] }));
    }
    setSearchQuery('');
    setShowSearch(false);
  };

  const handleRemoveSkill = (skillId) => {
    setFormData(prev => ({
      ...prev,
      skills: prev.skills.filter(s => s.id !== skillId)
    }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    setError('');

    const payload = {
      ...formData,
      skills: formData.skills.map(s => s.id) // Only send IDs
    };

    try {
      if (isEdit) {
        await fetchWithAuth(`/plans.php?id=${planId}`, {
          method: 'PUT',
          body: payload
        });
      } else {
        await fetchWithAuth('/plans.php', {
          method: 'POST',
          body: payload
        });
      }
      setLocation('/');
    } catch (err) {
      setError(err.message);
    } finally {
      setLoading(false);
    }
  };

  if (loading && isEdit && !formData.title) return <div>Carregando...</div>;

  return (
    <div className="max-w-4xl mx-auto">
      <div className="flex items-center justify-between mb-6">
        <button
          onClick={() => setLocation('/')}
          className="flex items-center gap-2 text-slate-500 hover:text-navy-900 transition-colors"
        >
          <ArrowLeft size={20} /> Voltar
        </button>
        <h2 className="text-2xl font-bold text-navy-900">
          {isEdit ? 'Editar Plano de Aula' : 'Novo Plano de Aula'}
        </h2>
      </div>

      {error && (
        <div className="bg-red-50 text-red-600 p-4 rounded-lg mb-6 flex items-start gap-3 border border-red-200">
          <AlertCircle size={20} className="mt-0.5" />
          <div>
             <p className="font-semibold">Erro ao salvar plano</p>
             <p className="text-sm">{error}</p>
          </div>
        </div>
      )}

      <form onSubmit={handleSubmit} className="space-y-6 bg-white p-6 md:p-8 rounded-lg shadow-sm border border-slate-200">

        {/* Basic Info */}
        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div className="md:col-span-2">
            <label className="block text-sm font-semibold text-slate-700 mb-2">Título do Plano</label>
            <input
              type="text"
              name="title"
              required
              value={formData.title}
              onChange={handleChange}
              className="w-full rounded-md border-slate-300 shadow-sm focus:border-highlight focus:ring focus:ring-highlight focus:ring-opacity-50 text-lg py-2 px-3 border"
              placeholder="Ex: Aula sobre Revolução Industrial"
            />
          </div>

          <div>
            <label className="block text-sm font-semibold text-slate-700 mb-2">Componente Curricular (Disciplina)</label>
            <input
              type="text"
              name="component"
              required
              value={formData.component}
              onChange={handleChange}
              className="w-full rounded-md border-slate-300 shadow-sm focus:border-highlight focus:ring focus:ring-highlight focus:ring-opacity-50 py-2 px-3 border"
              placeholder="Ex: História, Matemática, Português"
            />
          </div>

          <div>
            <label className="block text-sm font-semibold text-slate-700 mb-2">Data da Aula</label>
            <input
              type="date"
              name="lesson_date"
              value={formData.lesson_date}
              onChange={handleChange}
              className="w-full rounded-md border-slate-300 shadow-sm focus:border-highlight focus:ring focus:ring-highlight focus:ring-opacity-50 py-2 px-3 border text-slate-700"
            />
          </div>
        </div>

        {/* BNCC Integration */}
        <div className="border-t border-b border-slate-100 py-6 my-6">
          <label className="block text-sm font-semibold text-slate-700 mb-2 flex items-center justify-between">
            <span>Habilidades e Códigos BNCC</span>
            <span className="text-xs font-normal text-slate-500">{formData.skills.length} selecionada(s)</span>
          </label>

          <div className="mb-4">
             <div className="relative">
                <div className="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none">
                   <Search size={18} className="text-slate-400" />
                </div>
                <input
                  type="text"
                  value={searchQuery}
                  onChange={(e) => {
                     setSearchQuery(e.target.value);
                     setShowSearch(true);
                  }}
                  onFocus={() => setShowSearch(true)}
                  className="w-full rounded-md border-slate-300 shadow-sm focus:border-highlight focus:ring focus:ring-highlight focus:ring-opacity-50 py-2 pl-10 pr-3 border bg-slate-50"
                  placeholder="Buscar habilidade por código (ex: EF15LP01) ou palavra-chave..."
                />
             </div>

             {/* Search Dropdown */}
             {showSearch && (searchQuery.length >= 2 || searching) && (
               <div className="absolute z-10 w-full mt-1 bg-white rounded-md shadow-lg border border-slate-200 max-h-60 overflow-auto">
                 {searching ? (
                   <div className="p-3 text-sm text-slate-500 text-center">Buscando...</div>
                 ) : searchResults.length > 0 ? (
                   <ul className="py-1">
                     {searchResults.map((skill) => (
                       <li
                         key={skill.id}
                         onClick={() => handleAddSkill(skill)}
                         className="px-4 py-2 hover:bg-slate-50 cursor-pointer flex items-start gap-3 border-b border-slate-100 last:border-0"
                       >
                         <span className="font-mono text-xs font-semibold bg-blue-100 text-blue-800 px-2 py-1 rounded shrink-0 mt-0.5">
                           {skill.code}
                         </span>
                         <span className="text-sm text-slate-700 line-clamp-2" title={skill.description}>
                           {skill.description}
                         </span>
                         <Plus size={16} className="text-slate-400 ml-auto shrink-0 mt-1" />
                       </li>
                     ))}
                   </ul>
                 ) : (
                   <div className="p-3 text-sm text-slate-500 text-center">Nenhuma habilidade encontrada para "{searchQuery}"</div>
                 )}
                 <div className="p-2 border-t border-slate-100 bg-slate-50 text-right">
                    <button type="button" onClick={() => setShowSearch(false)} className="text-xs font-medium text-slate-500 hover:text-slate-700">Fechar</button>
                 </div>
               </div>
             )}
          </div>

          {/* Selected Skills Badges */}
          <div className="flex flex-wrap gap-2 mt-3">
             {formData.skills.map(skill => (
                <div key={skill.id} className="inline-flex items-center bg-blue-50 border border-blue-200 rounded text-sm px-2 py-1 gap-2 max-w-full">
                   <div className="flex flex-col max-w-xs sm:max-w-md md:max-w-lg lg:max-w-2xl">
                      <span className="font-mono font-bold text-blue-800 text-xs">{skill.code}</span>
                      <span className="text-slate-700 text-xs truncate" title={skill.description}>{skill.description}</span>
                   </div>
                   <button
                     type="button"
                     onClick={() => handleRemoveSkill(skill.id)}
                     className="text-blue-400 hover:text-red-500 hover:bg-red-50 p-1 rounded transition-colors"
                     title="Remover"
                   >
                      <X size={14} />
                   </button>
                </div>
             ))}
             {formData.skills.length === 0 && (
                <p className="text-sm text-slate-500 italic">Nenhuma habilidade selecionada ainda.</p>
             )}
          </div>
        </div>

        {/* Detailed Fields */}
        <div className="space-y-6">
          <div>
            <label className="block text-sm font-semibold text-slate-700 mb-2">Objetos de Conhecimento (Conteúdo temático)</label>
            <textarea
              name="objects_of_knowledge"
              rows={3}
              value={formData.objects_of_knowledge}
              onChange={handleChange}
              className="w-full rounded-md border-slate-300 shadow-sm focus:border-highlight focus:ring focus:ring-highlight focus:ring-opacity-50 py-2 px-3 border"
              placeholder="Descreva o conteúdo principal que será abordado na aula..."
            />
          </div>

          <div>
            <label className="block text-sm font-semibold text-slate-700 mb-2">Relação com Ensino Bíblico / Cosmovisão Cristã</label>
            <textarea
              name="biblical_relation"
              rows={3}
              value={formData.biblical_relation}
              onChange={handleChange}
              className="w-full rounded-md border-slate-300 shadow-sm focus:border-highlight focus:ring focus:ring-highlight focus:ring-opacity-50 py-2 px-3 border bg-blue-50/50"
              placeholder="Como este tema se relaciona com princípios bíblicos? (Ex: Versículos, princípios morais, reflexões...)"
            />
          </div>

          <div>
            <label className="block text-sm font-semibold text-slate-700 mb-2">Descrição da Atividade (Metodologia)</label>
            <textarea
              name="activity_description"
              rows={5}
              required
              value={formData.activity_description}
              onChange={handleChange}
              className="w-full rounded-md border-slate-300 shadow-sm focus:border-highlight focus:ring focus:ring-highlight focus:ring-opacity-50 py-2 px-3 border"
              placeholder="1. Introdução: ...&#10;2. Desenvolvimento: ...&#10;3. Conclusão: ..."
            />
          </div>

          <div>
            <label className="block text-sm font-semibold text-slate-700 mb-2">Referências e Material Didático</label>
            <textarea
              name="materials"
              rows={2}
              value={formData.materials}
              onChange={handleChange}
              className="w-full rounded-md border-slate-300 shadow-sm focus:border-highlight focus:ring focus:ring-highlight focus:ring-opacity-50 py-2 px-3 border"
              placeholder="Ex: Livro Didático pág 45-50, Vídeo no YouTube, Folha impressa, etc."
            />
          </div>
        </div>

        {/* Submit */}
        <div className="pt-6 mt-6 border-t border-slate-100 flex justify-end gap-4">
          <button
            type="button"
            onClick={() => setLocation('/')}
            className="px-6 py-2.5 rounded text-slate-600 hover:bg-slate-100 font-medium transition-colors"
          >
            Cancelar
          </button>
          <button
            type="submit"
            disabled={loading}
            className="flex items-center gap-2 px-6 py-2.5 bg-navy-800 text-white rounded hover:bg-navy-900 font-medium shadow-sm transition-colors disabled:opacity-50"
          >
            <Save size={20} />
            {loading ? 'Salvando...' : 'Salvar Plano'}
          </button>
        </div>

      </form>
    </div>
  );
}