import React, { useRef, useEffect, useState } from 'react';
import { useLocation, useRoute } from 'wouter';
import useSWR from 'swr';
import { fetchWithAuth } from '../lib/api';
import { Download, ArrowLeft, Printer, FileText } from 'lucide-react';
import { format, parseISO } from 'date-fns';
import { ptBR } from 'date-fns/locale';

export default function PlanPdfView() {
  const [, setLocation] = useLocation();
  const [match, params] = useRoute('/plan/:id/pdf');
  const planId = match ? params.id : null;
  const contentRef = useRef(null);

  const { data, error } = useSWR(planId ? `/plans.php?action=get&id=${planId}` : null, fetchWithAuth);

  const handleDownloadPdf = async () => {
    // Dynamic import to keep bundle small on other pages
    const html2pdf = (await import('html2pdf.js')).default;

    const element = contentRef.current;
    const opt = {
      margin:       15,
      filename:     `plano_de_aula_${data?.plan?.title ? data.plan.title.replace(/\s+/g, '_') : 'sem_titulo'}.pdf`,
      image:        { type: 'jpeg', quality: 0.98 },
      html2canvas:  { scale: 2, useCORS: true },
      jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
    };

    html2pdf().set(opt).from(element).save();
  };

  if (error) return <div className="p-8 text-red-500 text-center">Erro ao carregar plano: {error.message}</div>;
  if (!data) return <div className="p-8 text-slate-500 text-center">Carregando visualização oficial...</div>;

  const plan = data.plan;

  return (
    <div className="max-w-4xl mx-auto pb-12">
      {/* Top Actions */}
      <div className="flex items-center justify-between mb-6 bg-white p-4 rounded-lg shadow-sm border border-slate-200 print:hidden">
        <button
          onClick={() => window.history.back()}
          className="flex items-center gap-2 text-slate-600 hover:text-navy-900 transition-colors"
        >
          <ArrowLeft size={20} /> Voltar
        </button>
        <div className="flex items-center gap-3">
           <button
             onClick={() => window.print()}
             className="flex items-center gap-2 px-4 py-2 bg-slate-100 text-slate-700 rounded hover:bg-slate-200 font-medium transition-colors"
           >
             <Printer size={20} /> <span className="hidden sm:inline">Imprimir</span>
           </button>
           <button
             onClick={handleDownloadPdf}
             className="flex items-center gap-2 px-4 py-2 bg-highlight text-white rounded hover:bg-highlight-hover font-medium transition-colors shadow-sm"
           >
             <Download size={20} /> <span className="hidden sm:inline">Baixar PDF</span>
           </button>
        </div>
      </div>

      {/* Official Layout to Export */}
      <div
        ref={contentRef}
        className="bg-white p-8 md:p-12 shadow-md border border-slate-300 min-h-[1056px] print:shadow-none print:border-none print:p-0 mx-auto"
        style={{ maxWidth: '210mm' }} // A4 width approximation
      >
        {/* Header */}
        <div className="border-b-2 border-navy-900 pb-6 mb-8 text-center">
            <h1 className="text-3xl font-bold text-navy-900 mb-2 uppercase tracking-wide">Plano de Aula</h1>
            <h2 className="text-xl font-medium text-slate-700">{plan.title || 'Sem título'}</h2>
        </div>

        {/* Meta Info Grid */}
        <div className="grid grid-cols-2 gap-6 mb-8 bg-slate-50 p-6 rounded-lg border border-slate-200">
            <div>
               <p className="text-sm font-bold text-navy-900 uppercase tracking-wider mb-1">Componente Curricular</p>
               <p className="text-slate-800 text-lg">{plan.component || 'Não informado'}</p>
            </div>
            <div>
               <p className="text-sm font-bold text-navy-900 uppercase tracking-wider mb-1">Data Prevista</p>
               <p className="text-slate-800 text-lg">
                  {plan.lesson_date
                     ? format(parseISO(plan.lesson_date), "dd 'de' MMMM 'de' yyyy", { locale: ptBR })
                     : 'Não informada'}
               </p>
            </div>
        </div>

        {/* Content Sections */}
        <div className="space-y-8">
            <section>
               <h3 className="text-lg font-bold text-navy-900 border-b border-slate-200 pb-2 mb-3 flex items-center gap-2">
                  <FileText size={20} className="text-highlight" /> Objetos de Conhecimento
               </h3>
               <div className="text-slate-800 whitespace-pre-wrap leading-relaxed pl-2">
                  {plan.objects_of_knowledge || 'Nenhum conteúdo descrito.'}
               </div>
            </section>

            <section>
               <h3 className="text-lg font-bold text-navy-900 border-b border-slate-200 pb-2 mb-4">Habilidades BNCC</h3>
               {plan.skills && plan.skills.length > 0 ? (
                  <ul className="space-y-3 pl-2">
                     {plan.skills.map((skill) => (
                        <li key={skill.id} className="flex gap-3 items-start bg-slate-50 p-3 rounded-md border border-slate-100">
                           <span className="font-mono font-bold text-sm bg-blue-100 text-blue-800 px-2 py-1 rounded shrink-0">
                              {skill.code}
                           </span>
                           <span className="text-slate-700 text-sm leading-snug pt-0.5">{skill.description}</span>
                        </li>
                     ))}
                  </ul>
               ) : (
                  <p className="text-slate-600 pl-2 italic">Nenhuma habilidade selecionada.</p>
               )}
            </section>

            {plan.biblical_relation && (
               <section className="bg-blue-50/50 p-5 rounded-lg border-l-4 border-highlight">
                  <h3 className="text-lg font-bold text-navy-900 pb-2 mb-2">Cosmovisão Cristã</h3>
                  <div className="text-slate-800 whitespace-pre-wrap leading-relaxed">
                     {plan.biblical_relation}
                  </div>
               </section>
            )}

            <section>
               <h3 className="text-lg font-bold text-navy-900 border-b border-slate-200 pb-2 mb-3">Metodologia e Atividades</h3>
               <div className="text-slate-800 whitespace-pre-wrap leading-relaxed pl-2 bg-slate-50 p-4 rounded-md border border-slate-100 min-h-[150px]">
                  {plan.activity_description || 'Nenhuma metodologia descrita.'}
               </div>
            </section>

            <section>
               <h3 className="text-lg font-bold text-navy-900 border-b border-slate-200 pb-2 mb-3">Materiais e Referências</h3>
               <div className="text-slate-800 whitespace-pre-wrap leading-relaxed pl-2">
                  {plan.materials || 'Nenhum material especificado.'}
               </div>
            </section>
        </div>

        {/* Footer Signature line */}
        <div className="mt-24 pt-8 border-t border-slate-200 flex justify-center print:mt-16">
            <div className="text-center">
               <div className="w-64 border-t border-navy-900 mb-2 mx-auto"></div>
               <p className="text-sm font-semibold text-navy-900">Assinatura do Professor</p>
               <p className="text-xs text-slate-500 mt-1">Gerado pelo sistema Planer</p>
            </div>
        </div>
      </div>
    </div>
  );
}