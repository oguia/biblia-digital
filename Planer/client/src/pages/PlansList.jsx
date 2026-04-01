import React from 'react';
import { useLocation, Link } from 'wouter';
import useSWR from 'swr';
import { fetchWithAuth } from '../lib/api';
import { Plus, Edit, Trash2, Calendar as CalIcon, BookOpen, AlertCircle } from 'lucide-react';
import { format, parseISO } from 'date-fns';
import { ptBR } from 'date-fns/locale';
import ProgressDashboard from '../components/ProgressDashboard';

export default function PlansList() {
  const [, setLocation] = useLocation();
  const { data, error, mutate } = useSWR('/plans.php?action=list', fetchWithAuth);

  const handleDelete = async (id) => {
    if (confirm('Tem certeza que deseja excluir este plano de aula?')) {
      try {
        await fetchWithAuth(`/plans.php?id=${id}`, { method: 'DELETE' });
        mutate();
      } catch (e) {
        alert('Erro ao excluir: ' + e.message);
      }
    }
  };

  if (error) {
    if (error.message.includes('assinatura')) {
        return (
            <div className="bg-red-50 p-6 rounded-lg text-center border border-red-200">
                <AlertCircle className="w-12 h-12 text-red-500 mx-auto mb-4" />
                <h3 className="text-xl font-semibold text-red-800 mb-2">Sua assinatura expirou</h3>
                <p className="text-red-600 mb-4">{error.message}</p>
                <Link href="/profile" className="inline-block px-6 py-2 bg-red-600 text-white font-medium rounded-md hover:bg-red-700">
                    Renovar Agora
                </Link>
            </div>
        )
    }
    return <div className="text-red-500">Erro ao carregar planos: {error.message}</div>;
  }
  if (!data) return <div className="text-slate-500">Carregando planos...</div>;

  return (
    <div>
      <div className="flex justify-between items-center mb-6">
        <h2 className="text-2xl font-bold text-navy-900 flex items-center gap-2">
          <BookOpen size={24} className="text-highlight" /> Meus Planos de Aula
        </h2>
        <Link
          href="/plan/new"
          className="flex items-center gap-2 px-4 py-2 bg-highlight text-white rounded hover:bg-highlight-hover font-medium shadow-sm transition-colors"
        >
          <Plus size={20} /> <span className="hidden sm:inline">Novo Plano</span>
        </Link>
      </div>

      {data.plans.length === 0 ? (
        <div className="bg-white p-12 rounded-lg text-center border border-slate-200 shadow-sm">
          <BookOpen size={48} className="mx-auto text-slate-300 mb-4" />
          <h3 className="text-lg font-medium text-navy-900">Nenhum plano criado</h3>
          <p className="text-slate-500 mt-2 mb-6">Comece agora mesmo a organizar suas aulas de forma eficiente.</p>
          <Link href="/plan/new" className="inline-flex items-center gap-2 px-4 py-2 bg-navy-800 text-white rounded hover:bg-navy-900 transition-colors">
            <Plus size={20} /> Criar Primeiro Plano
          </Link>
        </div>
      ) : (
        <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
          {data.plans.map(plan => (
            <div key={plan.id} className="bg-white p-5 rounded-lg border border-slate-200 shadow-sm hover:shadow-md transition-shadow relative group">
              <div className="absolute top-4 right-4 flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                <button onClick={() => setLocation(`/plan/${plan.id}/pdf`)} className="p-1.5 text-green-600 bg-green-50 rounded hover:bg-green-100" title="Gerar PDF / Imprimir">
                  <BookOpen size={16} />
                </button>
                <button onClick={() => setLocation(`/plan/${plan.id}`)} className="p-1.5 text-blue-600 bg-blue-50 rounded hover:bg-blue-100" title="Editar">
                  <Edit size={16} />
                </button>
                <button onClick={() => handleDelete(plan.id)} className="p-1.5 text-red-600 bg-red-50 rounded hover:bg-red-100" title="Excluir">
                  <Trash2 size={16} />
                </button>
              </div>

              <h3 className="text-lg font-semibold text-navy-900 mb-2 pr-16 truncate" title={plan.title}>
                {plan.title || 'Sem título'}
              </h3>

              <div className="text-sm text-slate-600 mb-3 space-y-1">
                <p className="flex items-center gap-2">
                  <span className="font-medium text-slate-700">Disciplina:</span> {plan.component || 'N/A'}
                </p>
                {plan.lesson_date && (
                  <p className="flex items-center gap-2 text-highlight font-medium">
                    <CalIcon size={14} />
                    {format(parseISO(plan.lesson_date), "dd 'de' MMMM, yyyy", { locale: ptBR })}
                  </p>
                )}
              </div>

              <div className="mt-4 pt-4 border-t border-slate-100 flex flex-wrap gap-1">
                {plan.skills && plan.skills.slice(0, 3).map(skill => (
                  <span key={skill.id} className="inline-block px-2 py-1 bg-slate-100 text-xs font-mono text-slate-600 rounded">
                    {skill.code}
                  </span>
                ))}
                {plan.skills && plan.skills.length > 3 && (
                  <span className="inline-block px-2 py-1 bg-slate-100 text-xs text-slate-600 rounded">
                    +{plan.skills.length - 3} mais
                  </span>
                )}
                {(!plan.skills || plan.skills.length === 0) && (
                  <span className="text-xs text-slate-400 italic">Nenhuma hab. BNCC</span>
                )}
              </div>
            </div>
          ))}
        </div>
      )}

      {/* Show Progress Dashboard only when there are plans */}
      {data.plans.length > 0 && <ProgressDashboard />}
    </div>
  );
}