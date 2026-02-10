import React from 'react';
import { motion } from 'framer-motion';

interface TimelineEvent {
  year: number;
  title: string;
  description: string;
  era: string;
}

interface TimelineSliderProps {
  events: TimelineEvent[];
}

export const TimelineSlider: React.FC<TimelineSliderProps> = ({ events }) => {
  return (
    <div className="relative py-8 px-4 overflow-x-auto">
      <div className="absolute top-1/2 left-0 w-full h-1 bg-gray-300 dark:bg-gray-700 -translate-y-1/2" />

      <div className="flex items-center gap-12 min-w-max px-8">
        {events.map((event, index) => (
          <motion.div
            key={index}
            initial={{ scale: 0 }}
            animate={{ scale: 1 }}
            transition={{ delay: index * 0.1 }}
            className="relative flex flex-col items-center group cursor-pointer"
          >
            {/* Year Badge */}
            <div className="mb-4 px-3 py-1 bg-primary text-primary-foreground rounded-full text-xs font-bold shadow-lg transform group-hover:scale-110 transition-transform">
              {event.year < 0 ? `${Math.abs(event.year)} a.C.` : `${event.year} d.C.`}
            </div>

            {/* Point */}
            <div className="w-4 h-4 rounded-full bg-primary border-4 border-background z-10" />

            {/* Content */}
            <div className="mt-4 p-4 bg-card rounded-lg border shadow-sm w-48 text-center absolute top-8 opacity-0 group-hover:opacity-100 transition-opacity z-20 pointer-events-none group-hover:pointer-events-auto">
              <h4 className="font-bold text-sm mb-1">{event.title}</h4>
              <p className="text-xs text-muted-foreground line-clamp-3">{event.description}</p>
              <span className="text-[10px] uppercase tracking-widest text-primary mt-2 block">{event.era}</span>
            </div>

            {/* Static Title (always visible) */}
            <div className="mt-2 text-xs font-medium text-muted-foreground w-24 text-center truncate group-hover:opacity-0 transition-opacity">
              {event.title}
            </div>
          </motion.div>
        ))}
      </div>
    </div>
  );
};
