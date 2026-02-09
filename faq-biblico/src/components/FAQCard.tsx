import React, { useState } from 'react';
import { ChevronDown, BookOpen, Quote } from 'lucide-react';
import { motion, AnimatePresence } from 'framer-motion';
import type { FAQItem } from '../data/faq_data';

interface FAQCardProps {
  item: FAQItem;
}

export const FAQCard: React.FC<FAQCardProps> = ({ item }) => {
  const [isOpen, setIsOpen] = useState(false);

  return (
    <motion.div
      layout
      className="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-4 hover:shadow-md transition-shadow duration-300"
    >
      <button
        onClick={() => setIsOpen(!isOpen)}
        className="w-full text-left px-6 py-4 flex items-center justify-between focus:outline-none"
      >
        <div className="flex items-start gap-4">
          <div className="flex-shrink-0 mt-1">
            <div className={`w-8 h-8 rounded-full flex items-center justify-center ${isOpen ? 'bg-[var(--color-primary-red)] text-white' : 'bg-gray-100 text-[var(--color-dark-grey)]'}`}>
              <BookOpen size={16} />
            </div>
          </div>
          <div>
            <span className="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-1">
              {item.category}
            </span>
            <h3 className={`text-lg font-bold ${isOpen ? 'text-[var(--color-primary-red)]' : 'text-[var(--color-dark-grey)]'}`}>
              {item.question}
            </h3>
          </div>
        </div>
        <motion.div
          animate={{ rotate: isOpen ? 180 : 0 }}
          transition={{ duration: 0.3 }}
          className="text-gray-400 flex-shrink-0 ml-4"
        >
          <ChevronDown size={24} />
        </motion.div>
      </button>

      <AnimatePresence>
        {isOpen && (
          <motion.div
            initial={{ height: 0, opacity: 0 }}
            animate={{ height: 'auto', opacity: 1 }}
            exit={{ height: 0, opacity: 0 }}
            transition={{ duration: 0.3 }}
          >
            <div className="px-6 pb-6 pt-0 ml-12 border-l-2 border-gray-100">
              <p className="text-gray-700 leading-relaxed mb-4 text-base">
                {item.answer}
              </p>

              <div className="bg-[var(--color-off-white)] p-4 rounded-lg border-l-4 border-[var(--color-primary-red)] relative">
                <Quote className="absolute top-2 left-2 text-gray-300 h-6 w-6 opacity-50" />
                <div className="pl-6">
                  <p className="italic text-gray-800 font-serif text-lg mb-2">
                    "{item.verse}"
                  </p>
                  <p className="text-sm font-bold text-[var(--color-primary-red)] text-right">
                    — {item.reference}
                  </p>
                </div>
              </div>
            </div>
          </motion.div>
        )}
      </AnimatePresence>
    </motion.div>
  );
};
