import React from 'react';
import { motion } from 'framer-motion';
import { Calendar, MapPin, Users, History } from 'lucide-react';

interface ContextProps {
  who: string[];
  when: string;
  where: string;
  events: string;
}

export const ContextOverlay: React.FC<ContextProps> = ({ who, when, where, events }) => {
  return (
    <div className="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
      <motion.div
        initial={{ opacity: 0, y: 20 }}
        animate={{ opacity: 1, y: 0 }}
        className="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-md border-l-4 border-red-600"
      >
        <div className="flex items-center gap-2 mb-2 text-red-600 dark:text-red-400 font-bold uppercase text-xs tracking-wider">
          <Calendar className="w-4 h-4" />
          Quando
        </div>
        <p className="text-sm text-gray-700 dark:text-gray-300 font-medium">{when}</p>
      </motion.div>

      <motion.div
        initial={{ opacity: 0, y: 20 }}
        animate={{ opacity: 1, y: 0 }}
        transition={{ delay: 0.1 }}
        className="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-md border-l-4 border-blue-600"
      >
        <div className="flex items-center gap-2 mb-2 text-blue-600 dark:text-blue-400 font-bold uppercase text-xs tracking-wider">
          <MapPin className="w-4 h-4" />
          Onde
        </div>
        <p className="text-sm text-gray-700 dark:text-gray-300 font-medium">{where}</p>
      </motion.div>

      <motion.div
        initial={{ opacity: 0, y: 20 }}
        animate={{ opacity: 1, y: 0 }}
        transition={{ delay: 0.2 }}
        className="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-md border-l-4 border-green-600"
      >
        <div className="flex items-center gap-2 mb-2 text-green-600 dark:text-green-400 font-bold uppercase text-xs tracking-wider">
          <Users className="w-4 h-4" />
          Quem
        </div>
        <div className="flex flex-wrap gap-1">
          {who.map((person, i) => (
            <span key={i} className="inline-block bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-100 text-xs px-2 py-1 rounded-full">
              {person}
            </span>
          ))}
        </div>
      </motion.div>

      <motion.div
        initial={{ opacity: 0, y: 20 }}
        animate={{ opacity: 1, y: 0 }}
        transition={{ delay: 0.3 }}
        className="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-md border-l-4 border-purple-600"
      >
        <div className="flex items-center gap-2 mb-2 text-purple-600 dark:text-purple-400 font-bold uppercase text-xs tracking-wider">
          <History className="w-4 h-4" />
          História
        </div>
        <p className="text-xs text-gray-600 dark:text-gray-400 italic leading-relaxed">
          {events}
        </p>
      </motion.div>
    </div>
  );
};
