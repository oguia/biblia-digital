import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import api, { getCategories } from '../services/api';
import { CheckCircle, Zap } from 'lucide-react';

const RegisterBusiness = () => {
  const navigate = useNavigate();
  const [step, setStep] = useState(1);
  const [categories, setCategories] = useState<any[]>([]);
  const [loading, setLoading] = useState(false);

  const [formData, setFormData] = useState({
    name: '',
    category_id: '',
    description: '',
    phone: '',
    whatsapp: '',
    address: '',
    city: 'Curitiba',
    plan_type: 'free' // 'free' or 'premium'
  });

  const user = JSON.parse(localStorage.getItem('user') || 'null');

  useEffect(() => {
    if (!user) {
      navigate('/login');
      return;
    }
    getCategories().then(setCategories);
  }, [user, navigate]);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setLoading(true);

    try {
      // 1. Create Business
      const res = await api.post('/businesses.php', {
        user_id: user.id,
        ...formData
      });

      const businessId = res.data.id;

      // 2. If Premium, Create Preference
      if (formData.plan_type !== 'free') {
         const prefRes = await api.post('/mp_preference.php', {
           business_id: businessId,
           plan_type: formData.plan_type
         });

         if (prefRes.data.init_point) {
            window.location.href = prefRes.data.init_point; // Redirect to Mercado Pago
            return;
         }
      }

      alert('Negócio cadastrado com sucesso!');
      navigate('/');

    } catch (err) {
      console.error(err);
      alert('Erro ao cadastrar.');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="min-h-screen bg-slate-50 py-12 px-4">
      <div className="max-w-3xl mx-auto">
        <h1 className="text-3xl font-bold text-slate-900 mb-8 text-center">Cadastrar meu Negócio</h1>

        <div className="bg-white rounded-2xl shadow-lg overflow-hidden">
           {/* Steps Indicator */}
           <div className="bg-slate-100 p-4 flex justify-around border-b border-slate-200">
             <button onClick={() => setStep(1)} className={`font-bold ${step === 1 ? 'text-green-600' : 'text-slate-400'}`}>1. Dados</button>
             <button onClick={() => setStep(2)} className={`font-bold ${step === 2 ? 'text-green-600' : 'text-slate-400'}`}>2. Plano</button>
           </div>

           <form onSubmit={handleSubmit} className="p-8">
             {step === 1 && (
               <div className="space-y-6">
                 <div>
                   <label className="block text-sm font-bold text-slate-700 mb-2">Nome do Negócio</label>
                   <input required type="text" className="w-full p-3 border rounded-xl bg-slate-50" value={formData.name} onChange={e => setFormData({...formData, name: e.target.value})} placeholder="Ex: Pizzaria do João" />
                 </div>

                 <div className="grid md:grid-cols-2 gap-6">
                   <div>
                     <label className="block text-sm font-bold text-slate-700 mb-2">Categoria</label>
                     <select required className="w-full p-3 border rounded-xl bg-slate-50" value={formData.category_id} onChange={e => setFormData({...formData, category_id: e.target.value})}>
                       <option value="">Selecione...</option>
                       {categories.map(c => <option key={c.id} value={c.id}>{c.name}</option>)}
                     </select>
                   </div>
                   <div>
                     <label className="block text-sm font-bold text-slate-700 mb-2">WhatsApp (com DDD)</label>
                     <input required type="text" className="w-full p-3 border rounded-xl bg-slate-50" value={formData.whatsapp} onChange={e => setFormData({...formData, whatsapp: e.target.value})} placeholder="41999999999" />
                   </div>
                 </div>

                 <div>
                   <label className="block text-sm font-bold text-slate-700 mb-2">Descrição</label>
                   <textarea className="w-full p-3 border rounded-xl bg-slate-50 h-32" value={formData.description} onChange={e => setFormData({...formData, description: e.target.value})} placeholder="Conte sobre seus serviços..."></textarea>
                 </div>

                 <div>
                    <label className="block text-sm font-bold text-slate-700 mb-2">Endereço Completo</label>
                    <input required type="text" className="w-full p-3 border rounded-xl bg-slate-50" value={formData.address} onChange={e => setFormData({...formData, address: e.target.value})} placeholder="Rua XV de Novembro, 100 - Centro" />
                 </div>

                 <button type="button" onClick={() => setStep(2)} className="w-full bg-slate-900 text-white py-4 rounded-xl font-bold mt-4 hover:bg-slate-800">
                   Próximo: Escolher Plano
                 </button>
               </div>
             )}

             {step === 2 && (
               <div className="space-y-6">
                 <h3 className="text-xl font-bold text-center mb-6">Escolha como aparecer</h3>

                 <div className="grid md:grid-cols-2 gap-4">
                   {/* Free Plan */}
                   <div
                     className={`border-2 p-6 rounded-2xl cursor-pointer transition-all ${formData.plan_type === 'free' ? 'border-slate-900 bg-slate-50 ring-2 ring-slate-200' : 'border-slate-100 hover:border-slate-300'}`}
                     onClick={() => setFormData({...formData, plan_type: 'free'})}
                   >
                     <h4 className="font-bold text-xl mb-2">Grátis</h4>
                     <p className="text-3xl font-bold text-slate-900 mb-4">R$ 0</p>
                     <ul className="text-sm space-y-2 text-slate-600">
                       <li className="flex gap-2"><CheckCircle size={16} /> Presença no Mapa</li>
                       <li className="flex gap-2"><CheckCircle size={16} /> Link para WhatsApp</li>
                     </ul>
                   </div>

                   {/* Premium Plan */}
                   <div
                     className={`border-2 p-6 rounded-2xl cursor-pointer transition-all relative overflow-hidden ${formData.plan_type === 'premium' ? 'border-green-500 bg-green-50 ring-2 ring-green-200' : 'border-slate-100 hover:border-green-300'}`}
                     onClick={() => setFormData({...formData, plan_type: 'premium'})}
                   >
                     <div className="absolute top-0 right-0 bg-green-500 text-white text-xs font-bold px-3 py-1 rounded-bl-xl">MAIS VENDIDO</div>
                     <h4 className="font-bold text-xl mb-2 flex items-center gap-2">Premium <Zap size={18} className="text-yellow-500 fill-yellow-500" /></h4>
                     <p className="text-3xl font-bold text-green-600 mb-4">R$ 29,90<span className="text-sm text-slate-500 font-normal">/mês</span></p>
                     <ul className="text-sm space-y-2 text-slate-700">
                       <li className="flex gap-2 font-bold"><CheckCircle size={16} className="text-green-600" /> Topo das Buscas</li>
                       <li className="flex gap-2 font-bold"><CheckCircle size={16} className="text-green-600" /> Selo de Verificado</li>
                       <li className="flex gap-2"><CheckCircle size={16} /> Destaque Visual (Borda Amarela)</li>
                       <li className="flex gap-2"><CheckCircle size={16} /> 3x Mais Cliques no Zap</li>
                     </ul>
                   </div>
                 </div>

                 <div className="flex gap-4 mt-8">
                   <button type="button" onClick={() => setStep(1)} className="flex-1 border border-slate-300 py-4 rounded-xl font-bold text-slate-600 hover:bg-slate-50">Voltar</button>
                   <button type="submit" disabled={loading} className="flex-[2] bg-green-600 hover:bg-green-500 text-white py-4 rounded-xl font-bold shadow-lg shadow-green-500/20">
                     {loading ? 'Processando...' : (formData.plan_type === 'premium' ? 'Ir para Pagamento' : 'Finalizar Cadastro')}
                   </button>
                 </div>
               </div>
             )}
           </form>
        </div>
      </div>
    </div>
  );
};

export default RegisterBusiness;
