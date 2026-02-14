import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import { Search, Star, ArrowRight } from 'lucide-react';
import { getCategories } from '../services/api';

const Home = () => {
  const [query, setQuery] = useState('');
  const [categories, setCategories] = useState<any[]>([]);
  const navigate = useNavigate();

  useEffect(() => {
    getCategories().then(setCategories);
  }, []);

  const handleSearch = (e: React.FormEvent) => {
    e.preventDefault();
    if (query.trim()) {
      navigate(`/busca?q=${encodeURIComponent(query)}`);
    }
  };

  return (
    <div className="flex flex-col min-h-screen">
      {/* Hero Section */}
      <section className="bg-slate-900 text-white pt-20 pb-32 px-4 relative overflow-hidden">
        {/* Background Pattern */}
        <div className="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/city-lights.png')]"></div>

        <div className="container mx-auto max-w-4xl relative z-10 text-center">
          <h1 className="text-4xl md:text-6xl font-bold mb-6 tracking-tight">
            Encontre tudo em <span className="text-green-500">Curitiba</span>
          </h1>
          <p className="text-xl text-slate-300 mb-10 max-w-2xl mx-auto">
            O guia inteligente que conecta você aos melhores serviços da cidade.
            Simples, rápido e direto no WhatsApp.
          </p>

          <form onSubmit={handleSearch} className="relative max-w-2xl mx-auto group">
            <div className="absolute inset-y-0 left-4 flex items-center pointer-events-none">
              <Search className="h-6 w-6 text-slate-400 group-focus-within:text-green-500 transition-colors" />
            </div>
            <input
              type="text"
              value={query}
              onChange={(e) => setQuery(e.target.value)}
              placeholder="O que você precisa? (ex: Mecânico no Batel, Pizzaria aberta...)"
              className="w-full pl-14 pr-4 py-5 rounded-2xl bg-white text-slate-900 text-lg shadow-2xl focus:ring-4 focus:ring-green-500/30 focus:outline-none transition-all placeholder:text-slate-400"
            />
            <button
              type="submit"
              className="absolute right-2 top-2 bottom-2 bg-green-600 hover:bg-green-500 text-white px-6 rounded-xl font-bold transition-all shadow-md"
            >
              Buscar
            </button>
          </form>

          {/* Quick Tags */}
          <div className="mt-6 flex flex-wrap justify-center gap-2 text-sm text-slate-400">
            <span>Populares:</span>
            {['Pizzaria', 'Advogado', 'Pet Shop', 'Mecânica'].map(tag => (
              <button
                key={tag}
                onClick={() => navigate(`/busca?q=${tag}`)}
                className="hover:text-white hover:underline decoration-green-500 decoration-2 underline-offset-4 transition-all"
              >
                {tag}
              </button>
            ))}
          </div>
        </div>
      </section>

      {/* Categories Grid */}
      <section className="py-20 bg-slate-50 -mt-20 rounded-t-[3rem] relative z-20">
        <div className="container mx-auto px-4">
          <div className="flex justify-between items-end mb-10">
            <div>
              <h2 className="text-3xl font-bold text-slate-900">Categorias</h2>
              <p className="text-slate-500 mt-2">Navegue pelos serviços mais procurados</p>
            </div>
            <button onClick={() => navigate('/categorias')} className="text-green-600 font-semibold flex items-center hover:translate-x-1 transition-transform">
              Ver todas <ArrowRight className="ml-1 w-4 h-4" />
            </button>
          </div>

          <div className="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            {categories.slice(0, 12).map((cat) => (
              <div
                key={cat.id}
                onClick={() => navigate(`/busca?q=${cat.name}`)}
                className="bg-white p-6 rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all cursor-pointer border border-slate-100 flex flex-col items-center text-center group"
              >
                <div className="w-12 h-12 bg-green-50 rounded-full flex items-center justify-center mb-4 group-hover:bg-green-100 transition-colors">
                  {/* Icons could be dynamic based on 'icon' field, defaulting to Star for now */}
                  <Star className="w-6 h-6 text-green-600" />
                </div>
                <h3 className="font-semibold text-slate-800 group-hover:text-green-700 transition-colors">{cat.name}</h3>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Call to Action for Business Owners */}
      <section className="py-20 bg-white border-t border-slate-100">
        <div className="container mx-auto px-4">
          <div className="bg-gradient-to-r from-slate-900 to-slate-800 rounded-3xl p-8 md:p-16 text-white flex flex-col md:flex-row items-center justify-between gap-8 relative overflow-hidden">
            <div className="relative z-10 max-w-xl">
              <h2 className="text-3xl md:text-4xl font-bold mb-4">Tem um negócio em Curitiba?</h2>
              <p className="text-lg text-slate-300 mb-8">
                Cadastre-se gratuitamente e apareça para milhares de clientes. Destaque sua empresa no mapa e receba contatos direto no WhatsApp.
              </p>
              <button
                onClick={() => navigate('/anuncie')}
                className="bg-green-500 hover:bg-green-400 text-slate-900 px-8 py-4 rounded-xl font-bold text-lg shadow-lg hover:shadow-green-500/50 transition-all"
              >
                Cadastrar Agora
              </button>
            </div>
            {/* Abstract visual */}
            <div className="md:w-1/3 flex justify-center relative z-10">
               <div className="bg-white/10 backdrop-blur-sm p-6 rounded-2xl border border-white/20 rotate-3 transform hover:rotate-0 transition-all duration-500">
                  <div className="flex items-center gap-4 mb-4">
                    <div className="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center">
                       <Search className="text-white" />
                    </div>
                    <div>
                      <div className="h-2 w-24 bg-white/50 rounded mb-2"></div>
                      <div className="h-2 w-16 bg-white/30 rounded"></div>
                    </div>
                  </div>
                  <div className="h-2 w-full bg-white/20 rounded mb-2"></div>
                  <div className="h-2 w-full bg-white/20 rounded mb-2"></div>
                  <div className="mt-4 bg-green-500 text-center py-2 rounded-lg text-xs font-bold text-slate-900">CHAMAR NO ZAP</div>
               </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  );
};

export default Home;
