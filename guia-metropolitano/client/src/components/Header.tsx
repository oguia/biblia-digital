import { useState } from 'react';
import { Link } from 'react-router-dom';
import { Menu, X, Globe } from 'lucide-react';
import { useTranslation } from 'react-i18next';

const Header = () => {
  const [isOpen, setIsOpen] = useState(false);
  const { t, i18n } = useTranslation();

  const toggleLanguage = () => {
    i18n.changeLanguage(i18n.language === 'pt' ? 'en' : 'pt');
  };

  return (
    <header className="bg-slate-900 text-white shadow-lg sticky top-0 z-50">
      <div className="container mx-auto px-4 py-3 flex justify-between items-center">
        {/* Logo */}
        <Link to="/" className="flex items-center gap-2 group">
          <img
            src="./logo.svg"
            alt="O Guia Metropolitano"
            className="h-10 md:h-12 w-auto transition-transform group-hover:-translate-y-1"
          />
        </Link>

        {/* Desktop Nav */}
        <nav className="hidden md:flex items-center gap-6">
          <Link to="/" className="hover:text-green-400 transition-colors">{t('header.home')}</Link>
          <Link to="/categorias" className="hover:text-green-400 transition-colors">{t('header.categories')}</Link>

          <button onClick={toggleLanguage} className="flex items-center gap-1 text-slate-300 hover:text-white transition-colors">
             <Globe size={18} />
             <span className="uppercase text-xs font-bold">{i18n.language}</span>
          </button>

          <Link to="/login" className="text-slate-300 hover:text-white transition-colors font-medium">
            Login
          </Link>
          <Link to="/anuncie" className="bg-green-600 hover:bg-green-500 text-white px-5 py-2 rounded-full font-medium transition-colors shadow-lg hover:shadow-green-500/20">
            {t('header.advertise')}
          </Link>
        </nav>

        {/* Mobile Menu Button */}
        <button className="md:hidden text-white" onClick={() => setIsOpen(!isOpen)}>
          {isOpen ? <X size={28} /> : <Menu size={28} />}
        </button>
      </div>

      {/* Mobile Nav */}
      {isOpen && (
        <div className="md:hidden bg-slate-800 border-t border-slate-700 py-4 px-4 flex flex-col gap-4">
          <Link to="/" className="text-lg py-2 border-b border-slate-700" onClick={() => setIsOpen(false)}>{t('header.home')}</Link>
          <Link to="/categorias" className="text-lg py-2 border-b border-slate-700" onClick={() => setIsOpen(false)}>{t('header.categories')}</Link>
          <Link to="/login" className="text-lg py-2 border-b border-slate-700" onClick={() => setIsOpen(false)}>Login / Painel</Link>
          <button onClick={() => { toggleLanguage(); setIsOpen(false); }} className="text-left text-lg py-2 border-b border-slate-700 flex items-center gap-2">
             <Globe size={18} /> Mudar Idioma ({i18n.language.toUpperCase()})
          </button>
          <Link to="/anuncie" className="text-lg py-2 text-green-400 font-bold" onClick={() => setIsOpen(false)}>{t('header.advertise_business')}</Link>
        </div>
      )}
    </header>
  );
};

export default Header;
