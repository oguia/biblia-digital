import React, { useState } from 'react';
import { Search, Menu, X } from 'lucide-react';
import { motion, AnimatePresence } from 'framer-motion';

// Mock logo for now, assuming it will be passed or imported
import logo from '/512-app.png';

interface HeaderProps {
  onSearch: (query: string) => void;
  categories: string[];
  selectedCategory: string;
  onSelectCategory: (category: string) => void;
}

export const Header: React.FC<HeaderProps> = ({ onSearch, categories, selectedCategory, onSelectCategory }) => {
  const [isMenuOpen, setIsMenuOpen] = useState(false);

  return (
    <header className="bg-white shadow-sm sticky top-0 z-50">
      <div className="container mx-auto px-4 py-4">
        <div className="flex items-center justify-between mb-4">
          <div className="flex items-center space-x-3">
            <img src={logo} alt="Mais Deus Logo" className="h-12 w-auto" />
            <h1 className="text-xl md:text-2xl font-bold text-[var(--color-dark-grey)] leading-tight">
              Perguntas Reais <br />
              <span className="text-[var(--color-primary-red)]">Respostas Bíblicas</span>
            </h1>
          </div>

          <button
            className="md:hidden p-2 text-gray-600"
            onClick={() => setIsMenuOpen(!isMenuOpen)}
          >
            {isMenuOpen ? <X size={24} /> : <Menu size={24} />}
          </button>
        </div>

        {/* Search Bar */}
        <div className="relative max-w-2xl mx-auto mb-4">
          <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <Search className="h-5 w-5 text-gray-400" />
          </div>
          <input
            type="text"
            className="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg leading-5 bg-gray-50 placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:border-[var(--color-primary-red)] focus:ring-1 focus:ring-[var(--color-primary-red)] sm:text-sm transition duration-150 ease-in-out"
            placeholder="Qual é a sua dúvida? (ex: Salvação, Jesus, Sofrimento)"
            onChange={(e) => onSearch(e.target.value)}
          />
        </div>

        {/* Categories (Desktop) */}
        <div className="hidden md:flex flex-wrap justify-center gap-2 pb-2">
          <button
            onClick={() => onSelectCategory('Todas')}
            className={`px-4 py-1.5 rounded-full text-sm font-medium transition-colors ${
              selectedCategory === 'Todas'
                ? 'bg-[var(--color-primary-red)] text-white'
                : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
            }`}
          >
            Todas
          </button>
          {categories.map((cat) => (
            <button
              key={cat}
              onClick={() => onSelectCategory(cat)}
              className={`px-4 py-1.5 rounded-full text-sm font-medium transition-colors ${
                selectedCategory === cat
                  ? 'bg-[var(--color-primary-red)] text-white'
                  : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
              }`}
            >
              {cat}
            </button>
          ))}
        </div>

        {/* Mobile Menu (Categories) */}
        <AnimatePresence>
          {isMenuOpen && (
            <motion.div
              initial={{ height: 0, opacity: 0 }}
              animate={{ height: 'auto', opacity: 1 }}
              exit={{ height: 0, opacity: 0 }}
              className="md:hidden overflow-hidden"
            >
              <div className="py-2 grid grid-cols-2 gap-2">
                <button
                    onClick={() => { onSelectCategory('Todas'); setIsMenuOpen(false); }}
                    className={`px-3 py-2 rounded-md text-sm font-medium text-center ${
                      selectedCategory === 'Todas'
                        ? 'bg-[var(--color-primary-red)] text-white'
                        : 'bg-gray-100 text-gray-700'
                    }`}
                  >
                    Todas
                  </button>
                {categories.map((cat) => (
                  <button
                    key={cat}
                    onClick={() => { onSelectCategory(cat); setIsMenuOpen(false); }}
                    className={`px-3 py-2 rounded-md text-sm font-medium text-center ${
                      selectedCategory === cat
                        ? 'bg-[var(--color-primary-red)] text-white'
                        : 'bg-gray-100 text-gray-700'
                    }`}
                  >
                    {cat}
                  </button>
                ))}
              </div>
            </motion.div>
          )}
        </AnimatePresence>
      </div>
    </header>
  );
};
