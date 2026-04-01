import React, { useState } from 'react';
import { Calendar, dateFnsLocalizer, Views } from 'react-big-calendar';
import format from 'date-fns/format';
import parse from 'date-fns/parse';
import startOfWeek from 'date-fns/startOfWeek';
import getDay from 'date-fns/getDay';
import { ptBR } from 'date-fns/locale';
import 'react-big-calendar/lib/css/react-big-calendar.css';
import useSWR from 'swr';
import { fetchWithAuth } from '../lib/api';
import { useLocation } from 'wouter';
import { Calendar as CalIcon, BookOpen } from 'lucide-react';

const locales = {
  'pt-BR': ptBR,
};

const localizer = dateFnsLocalizer({
  format,
  parse,
  startOfWeek,
  getDay,
  locales,
});

export default function LessonCalendar() {
  const { data, error } = useSWR('/plans.php?action=list', fetchWithAuth);
  const [, setLocation] = useLocation();

  if (error) return <div className="text-red-500 p-4">Erro ao carregar planos.</div>;
  if (!data) return <div className="text-slate-500 p-4">Carregando calendário...</div>;

  const events = data.plans
    .filter(plan => plan.lesson_date)
    .map(plan => {
      // Create date object handling timezone issues (assume YYYY-MM-DD from DB)
      const dateParts = plan.lesson_date.split('-');
      const date = new Date(dateParts[0], dateParts[1] - 1, dateParts[2]);

      return {
        id: plan.id,
        title: plan.title || 'Sem título',
        start: date,
        end: date,
        allDay: true,
        resource: plan
      };
    });

  const handleSelectEvent = (event) => {
    setLocation(`/plan/${event.id}`);
  };

  const CustomEvent = ({ event }) => {
    return (
      <div className="text-xs truncate" title={event.title}>
        <span className="font-semibold">{event.resource.component?.substring(0, 3).toUpperCase()}</span> - {event.title}
      </div>
    );
  };

  return (
    <div className="h-[calc(100vh-120px)] min-h-[600px] flex flex-col bg-white rounded-lg shadow-sm border border-slate-200 p-4">
      <div className="flex items-center justify-between mb-4 pb-4 border-b border-slate-100">
        <h2 className="text-2xl font-bold text-navy-900 flex items-center gap-2">
          <CalIcon size={24} className="text-highlight" /> Calendário Acadêmico
        </h2>
        <div className="text-sm text-slate-500 flex items-center gap-2">
           <div className="w-3 h-3 bg-[#3174ad] rounded-full"></div> Aulas Agendadas
        </div>
      </div>

      <div className="flex-1 rounded overflow-hidden border border-slate-100">
        <Calendar
          localizer={localizer}
          events={events}
          startAccessor="start"
          endAccessor="end"
          style={{ height: '100%' }}
          culture="pt-BR"
          messages={{
            next: "Próximo",
            previous: "Anterior",
            today: "Hoje",
            month: "Mês",
            week: "Semana",
            day: "Dia",
            agenda: "Agenda",
            date: "Data",
            time: "Hora",
            event: "Plano de Aula",
            noEventsInRange: "Nenhum plano agendado neste período.",
            showMore: total => `+${total} mais`
          }}
          views={['month', 'agenda']}
          defaultView={Views.MONTH}
          onSelectEvent={handleSelectEvent}
          components={{
            event: CustomEvent
          }}
          eventPropGetter={(event, start, end, isSelected) => {
            return {
              className: 'bg-highlight border-none rounded shadow-sm text-white px-1'
            };
          }}
        />
      </div>
    </div>
  );
}