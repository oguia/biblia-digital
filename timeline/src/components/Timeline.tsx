import { VerticalTimeline, VerticalTimelineElement }  from 'react-vertical-timeline-component';
import 'react-vertical-timeline-component/style.min.css';
import { timelineData, TimelineEvent } from '../data';
import { MapPin, BookOpen, Crown, Tent, Flame, Waves, Image as ImageIcon } from 'lucide-react';

const getIcon = (category: string) => {
  switch (category) {
    case 'primevo': return <Waves />;
    case 'patriarcas': return <Tent />;
    case 'exodo': return <Flame />;
    case 'reino': return <Crown />;
    default: return <BookOpen />;
  }
};

const getBackgroundColor = (category: string) => {
    switch (category) {
        case 'primevo': return '#3b82f6'; // blue-500
        case 'patriarcas': return '#d97706'; // amber-600
        case 'exodo': return '#ef4444'; // red-500
        case 'reino': return '#a855f7'; // purple-500
        default: return '#10b981'; // emerald-500
    }
}

interface TimelineProps {
    onSelectEvent: (event: TimelineEvent) => void;
    onViewImages: (event: TimelineEvent) => void;
}

export function Timeline({ onSelectEvent, onViewImages }: TimelineProps) {
  return (
    <VerticalTimeline lineColor="#e5e7eb">
      {timelineData.map((event) => (
        <VerticalTimelineElement
          key={event.id}
          className="vertical-timeline-element--work"
          contentStyle={{ background: '#fff', color: '#333', borderTop: `4px solid ${getBackgroundColor(event.category)}`, boxShadow: '0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1)' }}
          contentArrowStyle={{ borderRight: '7px solid  #fff' }}
          date={event.date}
          iconStyle={{ background: getBackgroundColor(event.category), color: '#fff' }}
          icon={getIcon(event.category)}
        >
          <h3 className="vertical-timeline-element-title font-bold text-lg">{event.title}</h3>
          <h4 className="vertical-timeline-element-subtitle italic text-gray-600">{event.location}</h4>
          <p className="text-gray-700 !font-normal">
            {event.description}
          </p>
          <div className="mt-4 flex flex-wrap gap-2">
            <button
                onClick={() => onSelectEvent(event)}
                className="cursor-pointer flex items-center gap-1 text-sm bg-blue-50 text-blue-700 border border-blue-200 px-3 py-1.5 rounded hover:bg-blue-100 transition-colors font-medium"
            >
                <MapPin size={16} /> Ver no Mapa
            </button>

            {(event.imageOld || event.imageNew) && (
                <button
                    onClick={() => onViewImages(event)}
                    className="cursor-pointer flex items-center gap-1 text-sm bg-amber-50 text-amber-700 border border-amber-200 px-3 py-1.5 rounded hover:bg-amber-100 transition-colors font-medium"
                >
                    <ImageIcon size={16} /> Ver Imagens
                </button>
            )}
          </div>
        </VerticalTimelineElement>
      ))}
    </VerticalTimeline>
  );
}
