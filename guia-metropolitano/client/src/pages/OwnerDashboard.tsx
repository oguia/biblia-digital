import { useState, useEffect } from 'react';
import { useNavigate, Link } from 'react-router-dom';
import axios from 'axios';
import { LayoutDashboard, Users, Phone, MessageCircle, Edit, Star, LogOut, PlusCircle } from 'lucide-react';

interface Business {
  id: number;
  name: string;
  slug: string;
  category_name: string;
  views: number;
  whatsapp_clicks: number;
  phone_clicks: number;
  is_verified: boolean;
  is_featured: boolean;
  image_url?: string;
}

const OwnerDashboard = () => {
  const navigate = useNavigate();
  const [user, setUser] = useState<any>(null);
  const [businesses, setBusinesses] = useState<Business[]>([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const storedUser = localStorage.getItem('user');
    if (!storedUser) {
      navigate('/login');
      return;
    }
    const parsedUser = JSON.parse(storedUser);
    setUser(parsedUser);

    // Fetch Businesses
    const fetchBusinesses = async () => {
      try {
        const apiPath = import.meta.env.DEV ? 'http://localhost:8000/api/owner_business.php' : './api/owner_business.php';
        const res = await axios.get(`${apiPath}?user_id=${parsedUser.id}`);
        setBusinesses(res.data);
      } catch (err) {
        console.error("Error fetching businesses", err);
      } finally {
        setLoading(false);
      }
    };
    fetchBusinesses();
  }, [navigate]);

  const handleLogout = () => {
    localStorage.removeItem('user');
    navigate('/login');
  };

  if (loading) return <div className="min-h-screen flex items-center justify-center bg-slate-50">Carregando...</div>;

  return (
    <div className="min-h-screen bg-slate-50 pb-20">
      {/* Header */}
      <header className="bg-white border-b border-slate-200 sticky top-0 z-30">
        <div className="container mx-auto px-4 py-4 flex justify-between items-center">
          <div className="flex items-center gap-3">
             <LayoutDashboard className="text-green-600" />
             <h1 className="text-xl font-bold text-slate-900">Painel do Cliente</h1>
          </div>
          <div className="flex items-center gap-4">
             <span className="text-sm text-slate-500 hidden md:inline">Olá, {user?.name}</span>
             <button onClick={handleLogout} className="text-red-500 hover:bg-red-50 p-2 rounded-lg transition-colors">
               <LogOut size={20} />
             </button>
          </div>
        </div>
      </header>

      <div className="container mx-auto px-4 py-8">

        {/* Welcome / Empty State */}
        {businesses.length === 0 ? (
          <div className="text-center py-20 bg-white rounded-2xl shadow-sm border border-slate-200">
            <h2 className="text-2xl font-bold text-slate-800 mb-4">Você ainda não tem empresas cadastradas.</h2>
            <p className="text-slate-500 mb-8 max-w-md mx-auto">Comece agora a divulgar seu negócio para milhares de pessoas em Curitiba.</p>
            <Link to="/anuncie" className="inline-flex items-center gap-2 bg-green-600 text-white px-8 py-4 rounded-xl font-bold hover:bg-green-700 transition-colors shadow-lg hover:shadow-green-500/30">
              <PlusCircle size={24} />
              Cadastrar Meu Negócio
            </Link>
          </div>
        ) : (
          <div className="space-y-8">
            <div className="flex justify-between items-center">
              <h2 className="text-2xl font-bold text-slate-800">Seus Negócios</h2>
              <Link to="/anuncie" className="text-green-600 font-bold hover:bg-green-50 px-4 py-2 rounded-lg transition-colors text-sm">
                + Adicionar Outro
              </Link>
            </div>

            <div className="grid grid-cols-1 gap-6">
              {businesses.map(biz => (
                <div key={biz.id} className="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                  {/* Business Header */}
                  <div className="p-6 border-b border-slate-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div className="flex items-center gap-4">
                      <div className="w-16 h-16 bg-slate-100 rounded-lg overflow-hidden flex-shrink-0">
                        {biz.image_url ? (
                          <img src={biz.image_url} alt={biz.name} className="w-full h-full object-cover" />
                        ) : (
                          <span className="w-full h-full flex items-center justify-center text-slate-400 text-2xl font-bold">{biz.name[0]}</span>
                        )}
                      </div>
                      <div>
                        <h3 className="text-xl font-bold text-slate-900 flex items-center gap-2">
                          {biz.name}
                          {biz.is_verified && <span className="text-xs bg-blue-100 text-blue-600 px-2 py-0.5 rounded-full font-bold">Verificado</span>}
                          {biz.is_featured && <span className="text-xs bg-yellow-100 text-yellow-600 px-2 py-0.5 rounded-full font-bold flex items-center gap-1"><Star size={10} fill="currentColor" /> Destaque</span>}
                        </h3>
                        <p className="text-sm text-slate-500">{biz.category_name}</p>
                      </div>
                    </div>
                    <div className="flex gap-2">
                      <a href={`#/negocio/${biz.slug}`} target="_blank" className="text-slate-400 hover:text-blue-500 p-2 border border-slate-200 rounded-lg hover:bg-blue-50 transition-colors" title="Ver Página">
                        <Users size={20} />
                      </a>
                      <button className="text-slate-400 hover:text-green-500 p-2 border border-slate-200 rounded-lg hover:bg-green-50 transition-colors" title="Editar">
                        <Edit size={20} />
                      </button>
                    </div>
                  </div>

                  {/* Stats Grid */}
                  <div className="grid grid-cols-3 divide-x divide-slate-100 bg-slate-50/50">
                    <div className="p-6 text-center hover:bg-white transition-colors">
                      <div className="text-sm text-slate-500 mb-1 flex items-center justify-center gap-1"><Users size={16} /> Visualizações</div>
                      <div className="text-2xl font-bold text-slate-900">{biz.views || 0}</div>
                    </div>
                    <div className="p-6 text-center hover:bg-white transition-colors">
                      <div className="text-sm text-slate-500 mb-1 flex items-center justify-center gap-1"><MessageCircle size={16} /> Cliques WhatsApp</div>
                      <div className="text-2xl font-bold text-green-600">{biz.whatsapp_clicks || 0}</div>
                    </div>
                    <div className="p-6 text-center hover:bg-white transition-colors">
                      <div className="text-sm text-slate-500 mb-1 flex items-center justify-center gap-1"><Phone size={16} /> Cliques Telefone</div>
                      <div className="text-2xl font-bold text-blue-600">{biz.phone_clicks || 0}</div>
                    </div>
                  </div>

                  {/* Actions / Upgrade */}
                  {!biz.is_featured && (
                    <div className="p-4 bg-yellow-50 flex items-center justify-between flex-wrap gap-4">
                      <div>
                        <h4 className="font-bold text-yellow-800 flex items-center gap-2">
                          <Star size={18} fill="currentColor" />
                          Quer aparecer no topo?
                        </h4>
                        <p className="text-sm text-yellow-700">Empresas em destaque recebem 3x mais cliques.</p>
                      </div>
                      <button className="bg-yellow-500 hover:bg-yellow-600 text-white font-bold px-6 py-2 rounded-lg shadow-sm transition-colors">
                        Ser Destaque (R$ 29/mês)
                      </button>
                    </div>
                  )}
                </div>
              ))}
            </div>
          </div>
        )}

      </div>
    </div>
  );
};

export default OwnerDashboard;
