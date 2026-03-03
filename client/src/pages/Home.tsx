import { useState, useEffect } from 'react';
import axios from 'axios';
import { Search, MapPin, Tag, ShoppingCart, Loader2, ArrowRight, ServerCrash } from 'lucide-react';
import { Link } from 'react-router-dom';

interface Oferta {
  id: number;
  titulo: string;
  preco: number;
  loja: string;
  imagem_url?: string;
  categoria: string;
  data_publicacao: string;
  fonte: string;
}

export default function Home() {
  const [ofertas, setOfertas] = useState<Oferta[]>([]);
  const [loading, setLoading] = useState(true);
  const [errorStatus, setErrorStatus] = useState(false);
  const [categoriaAtiva, setCategoriaAtiva] = useState('Todas');
  const [busca, setBusca] = useState('');

  const categorias = ['Todas', 'Supermercado', 'Construção'];

  useEffect(() => {
    carregarOfertas();
  }, [categoriaAtiva]);

  const carregarOfertas = async () => {
    setLoading(true);
    setErrorStatus(false);
    try {
      // Ajuste o caminho da API conforme necessário no deploy Hostinger
      const baseURL = import.meta.env.DEV ? 'http://localhost:8000/api' : '/api';
      const params = new URLSearchParams();
      if (categoriaAtiva !== 'Todas') params.append('categoria', categoriaAtiva);
      if (busca) params.append('busca', busca);

      const res = await axios.get(`${baseURL}/ofertas.php?${params.toString()}`);

      // Validação extremamente forte para evitar a quebra do React (.map is not a function)
      // Se a Hostinger retornar HTML de erro (ex: 404, 500, ou "Database is locked"), tratamos como []
      if (Array.isArray(res.data)) {
         setOfertas(res.data);
      } else {
         console.warn("API não retornou um array de ofertas válido:", res.data);
         setOfertas([]);
      }
    } catch (error) {
      console.error("Erro fatal ao carregar ofertas da API:", error);
      setOfertas([]);
      setErrorStatus(true);
    } finally {
      setLoading(false);
    }
  };

  const handleBusca = (e: React.FormEvent) => {
    e.preventDefault();
    carregarOfertas();
  };

  const formatarData = (dataString: string) => {
    try {
      const data = new Date(dataString);
      return new Intl.DateTimeFormat('pt-BR', { day: '2-digit', month: 'short', hour: '2-digit', minute: '2-digit' }).format(data);
    } catch (e) {
      return '';
    }
  };

  return (
    <div className="min-h-screen bg-gray-50 flex flex-col">
      {/* Header Estilo Promobit */}
      <header className="bg-orange-600 text-white shadow-md sticky top-0 z-50">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
          <div className="flex flex-col md:flex-row items-center justify-between gap-4">

            {/* Logo e Nome */}
            <div className="flex items-center gap-3 w-full md:w-auto">
              <img src="/logo.png" alt="Logo" className="h-10 w-10 bg-white rounded-full p-1 object-contain" onError={(e) => { e.currentTarget.style.display = 'none'; }} />
              <div>
                <h1 className="text-2xl font-black tracking-tight leading-none">CuriOfertas</h1>
                <p className="text-xs font-medium text-orange-200">Região Metropolitana</p>
              </div>
            </div>

            {/* Barra de Busca */}
            <form onSubmit={handleBusca} className="w-full md:flex-1 max-w-2xl relative">
              <input
                type="text"
                placeholder="Busque por produtos, marcas ou lojas..."
                className="w-full py-3 px-4 pr-12 rounded-lg text-gray-900 border-none outline-none focus:ring-2 focus:ring-orange-400 shadow-inner bg-white"
                value={busca}
                onChange={(e) => setBusca(e.target.value)}
              />
              <button type="submit" className="absolute right-2 top-1/2 -translate-y-1/2 p-2 bg-orange-100 text-orange-600 rounded-md hover:bg-orange-200 transition-colors">
                <Search size={20} />
              </button>
            </form>

            {/* Botoes Laterais */}
            <div className="flex gap-3 w-full md:w-auto justify-end">
               <Link to="/painel" className="text-sm font-semibold bg-white/20 hover:bg-white/30 px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                 Área do Lojista
                 <ArrowRight size={16} />
               </Link>
            </div>
          </div>
        </div>
      </header>

      {/* Navegação de Categorias */}
      <div className="bg-white border-b border-gray-200 overflow-x-auto shadow-sm">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex space-x-8 py-3">
            {categorias.map(cat => (
              <button
                key={cat}
                onClick={() => setCategoriaAtiva(cat)}
                className={`whitespace-nowrap px-1 py-2 font-medium text-sm border-b-2 transition-colors ${
                  categoriaAtiva === cat
                    ? 'border-orange-600 text-orange-600'
                    : 'border-transparent text-gray-500 hover:text-gray-900'
                }`}
              >
                {cat}
              </button>
            ))}
          </div>
        </div>
      </div>

      {/* Grid de Ofertas */}
      <main className="flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">
        {loading ? (
          <div className="flex justify-center items-center h-64">
             <Loader2 className="animate-spin text-orange-600" size={48} />
          </div>
        ) : errorStatus ? (
          <div className="text-center py-20 bg-white rounded-2xl shadow-sm border border-gray-100 max-w-lg mx-auto">
            <ServerCrash className="mx-auto h-16 w-16 text-red-300 mb-4" />
            <h3 className="text-xl font-bold text-gray-900">Erro de Comunicação</h3>
            <p className="text-gray-500 mt-2 text-sm px-4">Nosso sistema (Backend) não respondeu como esperado. Se for o primeiro acesso, certifique-se que o SQLite está permissivo na Hostinger.</p>
          </div>
        ) : !Array.isArray(ofertas) || ofertas.length === 0 ? (
          <div className="text-center py-20 bg-white rounded-2xl shadow-sm border border-gray-100 max-w-lg mx-auto">
            <ShoppingCart className="mx-auto h-16 w-16 text-gray-300 mb-4" />
            <h3 className="text-xl font-bold text-gray-900">Nenhuma oferta encontrada</h3>
            <p className="text-gray-500 mt-2">Tente buscar por termos diferentes ou verifique as categorias.</p>
          </div>
        ) : (
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            {ofertas.map(oferta => (
              <div key={oferta.id} className="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow border border-gray-100 overflow-hidden group flex flex-col">

                {/* Imagem */}
                <div className="relative aspect-square bg-gray-50 p-6 flex items-center justify-center border-b border-gray-50">
                  {oferta.imagem_url ? (
                     <img src={oferta.imagem_url} alt={oferta.titulo} className="max-h-full max-w-full object-contain group-hover:scale-105 transition-transform duration-300" />
                  ) : (
                     <div className="text-gray-300 flex flex-col items-center">
                       <ShoppingCart size={48} />
                       <span className="text-xs mt-2">Sem imagem</span>
                     </div>
                  )}
                  {oferta.fonte === 'manual' && (
                    <span className="absolute top-2 left-2 bg-green-100 text-green-800 text-[10px] font-bold px-2 py-1 rounded-full uppercase tracking-wider">
                      Lojista Local
                    </span>
                  )}
                  {oferta.fonte === 'auto' && (
                    <span className="absolute top-2 left-2 bg-blue-100 text-blue-800 text-[10px] font-bold px-2 py-1 rounded-full uppercase tracking-wider">
                      Radar Auto
                    </span>
                  )}
                </div>

                {/* Conteudo */}
                <div className="p-5 flex-1 flex flex-col">
                  <div className="flex items-start justify-between gap-2 mb-2">
                    <h3 className="font-semibold text-gray-900 line-clamp-2 text-sm md:text-base group-hover:text-orange-600 transition-colors">
                      {oferta.titulo}
                    </h3>
                  </div>

                  <div className="mt-auto pt-4 flex flex-col gap-1">
                    <span className="text-2xl font-black text-orange-600">
                      R$ {Number(oferta.preco).toFixed(2).replace('.', ',')}
                    </span>

                    <div className="flex items-center text-gray-500 text-xs gap-1 mt-2">
                      <MapPin size={14} className="text-gray-400" />
                      <span className="truncate font-medium">{oferta.loja}</span>
                    </div>

                    <div className="flex items-center text-gray-400 text-xs gap-1 mt-1 justify-between">
                       <span className="flex items-center gap-1">
                          <Tag size={12} /> {oferta.categoria}
                       </span>
                       <span>
                          {formatarData(oferta.data_publicacao)}
                       </span>
                    </div>
                  </div>
                </div>

                <button className="w-full py-3 bg-gray-50 text-gray-700 font-semibold text-sm border-t border-gray-100 hover:bg-orange-50 hover:text-orange-600 transition-colors">
                  Pegar Oferta
                </button>
              </div>
            ))}
          </div>
        )}
      </main>

      {/* Footer */}
      <footer className="bg-white border-t border-gray-200 py-8 text-center text-sm text-gray-500 mt-auto">
         <p>© {new Date().getFullYear()} CuriOfertas. Todos os direitos reservados.</p>
         <p className="mt-1 text-xs">Agregador de ofertas para Curitiba e Região Metropolitana.</p>
      </footer>
    </div>
  );
}