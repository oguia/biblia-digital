import { useState } from 'react';
import { Link } from 'react-router-dom';
import { Search, MapPin, Menu, X } from 'lucide-react';

const Header = () => {
  const [isOpen, setIsOpen] = useState(false);
  // const navigate = useNavigate(); // Unused

  return (
    <header className="bg-slate-900 text-white shadow-lg sticky top-0 z-50">
      <div className="container mx-auto px-4 py-3 flex justify-between items-center">
        {/* Logo */}
        <Link to="/" className="flex items-center gap-2 group">
          <div className="relative">
            <MapPin className="w-8 h-8 text-green-500 transition-transform group-hover:-translate-y-1" />
            <Search className="w-4 h-4 text-white absolute bottom-0 right-0 bg-slate-900 rounded-full p-0.5 border border-slate-900" />
          </div>
          <div className="flex flex-col leading-none">
            <span className="font-bold text-lg tracking-tight">O Guia</span>
            <span className="text-xs text-slate-400 uppercase tracking-widest">Metropolitano</span>
          </div>
        </Link>

        {/* Desktop Nav */}
        <nav className="hidden md:flex items-center gap-6">
          <Link to="/" className="hover:text-green-400 transition-colors">Início</Link>
          <Link to="/categorias" className="hover:text-green-400 transition-colors">Categorias</Link>
          <Link to="/anuncie" className="bg-green-600 hover:bg-green-500 text-white px-5 py-2 rounded-full font-medium transition-colors shadow-lg hover:shadow-green-500/20">
            Anuncie Grátis
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
          <Link to="/" className="text-lg py-2 border-b border-slate-700" onClick={() => setIsOpen(false)}>Início</Link>
          <Link to="/categorias" className="text-lg py-2 border-b border-slate-700" onClick={() => setIsOpen(false)}>Categorias</Link>
          <Link to="/anuncie" className="text-lg py-2 text-green-400 font-bold" onClick={() => setIsOpen(false)}>Anuncie Seu Negócio</Link>
        </div>
      )}
    </header>
  );
};

export default Header;
