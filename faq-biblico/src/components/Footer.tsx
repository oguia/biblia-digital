import React from 'react';
import { Heart, ExternalLink, Mail } from 'lucide-react';

export const Footer: React.FC = () => {
  return (
    <footer className="bg-[var(--color-dark-grey)] text-gray-300 py-8 mt-12">
      <div className="container mx-auto px-4">
        <div className="flex flex-col md:flex-row justify-between items-center gap-6">
          <div className="text-center md:text-left">
            <h3 className="text-white font-bold text-lg mb-2">MaisDeus.com</h3>
            <p className="text-sm text-gray-400">
              Levando a Palavra ao Mundo.
            </p>
          </div>

          <div className="flex gap-6">
             <a
              href="https://maisdeus.com"
              target="_blank"
              rel="noopener noreferrer"
              className="hover:text-white transition-colors flex items-center gap-2"
            >
              <ExternalLink size={16} /> Site Principal
            </a>
            <a
              href="mailto:maisdeus@maisdeus.com"
              className="hover:text-white transition-colors flex items-center gap-2"
            >
              <Mail size={16} /> Contato
            </a>
          </div>
        </div>

        <div className="border-t border-gray-700 mt-8 pt-8 flex flex-col md:flex-row justify-between items-center text-sm text-gray-500">
          <p>&copy; {new Date().getFullYear()} Mais Deus. Todos os direitos reservados.</p>
          <p className="flex items-center gap-1 mt-2 md:mt-0">
            Feito com <Heart size={12} className="text-red-500 fill-current" /> para o Reino
          </p>
        </div>
      </div>
    </footer>
  );
};
