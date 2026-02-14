import React, { useEffect, useState } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import { MapContainer, TileLayer, Marker, Popup } from 'react-leaflet';
import { Phone, MapPin, CheckCircle, ExternalLink, MessageCircle, Share2, Clock, Globe } from 'lucide-react';
import { getBusinessDetails } from '../services/api';

const BusinessDetails = () => {
  const { slug } = useParams();
  const navigate = useNavigate();
  const [business, setBusiness] = useState<any>(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    if (slug) {
      getBusinessDetails(slug).then(data => {
        setBusiness(data);
        setLoading(false);
      }).catch(err => {
        console.error(err);
        setLoading(false);
      });
    }
  }, [slug]);

  if (loading) return <div className="min-h-screen flex items-center justify-center bg-slate-50"><div className="animate-spin rounded-full h-12 w-12 border-b-2 border-green-500"></div></div>;
  if (!business) return <div className="min-h-screen flex items-center justify-center bg-slate-50 text-slate-500">Negócio não encontrado.</div>;

  const whatsappLink = business.whatsapp
    ? `https://wa.me/55${business.whatsapp.replace(/\D/g, '')}?text=Olá, vi no Guia Metropolitano!`
    : null;

  const handleShare = () => {
    if (navigator.share) {
      navigator.share({
        title: business.name,
        text: `Conheça ${business.name} no Guia Metropolitano!`,
        url: window.location.href,
      });
    } else {
      alert('Copie o link: ' + window.location.href);
    }
  };

  return (
    <div className="bg-slate-50 min-h-screen pb-20">
      {/* Cover Image */}
      <div className="h-64 md:h-96 w-full relative overflow-hidden bg-slate-900">
         {business.image_url ? (
           <img src={business.image_url} alt={business.name} className="w-full h-full object-cover opacity-80" />
         ) : (
           <div className="w-full h-full bg-slate-800 flex items-center justify-center">
             <span className="text-6xl text-white opacity-20 font-bold">{business.name.charAt(0)}</span>
           </div>
         )}
         <div className="absolute inset-0 bg-gradient-to-t from-slate-900/90 to-transparent"></div>

         <div className="absolute bottom-0 left-0 right-0 p-6 md:p-12 text-white">
           <div className="container mx-auto">
             <div className="flex flex-col md:flex-row justify-between items-end gap-6">
               <div>
                  <span className="bg-green-500 text-slate-900 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider mb-2 inline-block">
                    {business.category_name || 'Serviço Local'}
                  </span>
                  <h1 className="text-4xl md:text-5xl font-bold mb-2 flex items-center gap-3">
                    {business.name}
                    {business.is_verified && <CheckCircle className="w-8 h-8 text-blue-400" />}
                  </h1>
                  <p className="text-slate-300 text-lg max-w-2xl">{business.description}</p>
               </div>
               <div className="flex gap-3">
                 <button onClick={handleShare} className="bg-white/10 hover:bg-white/20 backdrop-blur-sm p-3 rounded-full transition-colors">
                   <Share2 className="w-6 h-6 text-white" />
                 </button>
               </div>
             </div>
           </div>
         </div>
      </div>

      <div className="container mx-auto px-4 -mt-10 relative z-10 grid grid-cols-1 md:grid-cols-3 gap-8">

        {/* Main Info Column */}
        <div className="md:col-span-2 space-y-6">

          {/* Action Bar */}
          <div className="bg-white rounded-2xl p-6 shadow-xl flex flex-wrap gap-4">
             {whatsappLink && (
               <a href={whatsappLink} target="_blank" className="flex-1 bg-green-500 hover:bg-green-600 text-white py-4 px-6 rounded-xl font-bold flex items-center justify-center gap-2 transition-transform hover:scale-[1.02] shadow-lg shadow-green-500/25">
                 <MessageCircle className="w-6 h-6" />
                 Conversar no WhatsApp
               </a>
             )}
             {business.phone && (
               <a href={`tel:${business.phone}`} className="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-800 py-4 px-6 rounded-xl font-bold flex items-center justify-center gap-2 transition-colors">
                 <Phone className="w-6 h-6" />
                 Ligar Agora
               </a>
             )}
          </div>

          {/* Details Card */}
          <div className="bg-white rounded-2xl p-8 shadow-sm border border-slate-100">
             <h2 className="text-2xl font-bold text-slate-900 mb-6">Sobre o negócio</h2>
             <div className="space-y-4 text-slate-600 leading-relaxed">
               <p>{business.description}</p>
               <p>Entre em contato para saber mais sobre nossos serviços e ofertas exclusivas.</p>
             </div>

             <div className="mt-8 grid grid-cols-1 sm:grid-cols-2 gap-4">
               {business.website && (
                 <a href={business.website} target="_blank" className="flex items-center gap-3 p-4 bg-slate-50 rounded-xl hover:bg-slate-100 transition-colors">
                   <Globe className="w-5 h-5 text-slate-400" />
                   <span className="font-semibold text-slate-700">Visitar Site</span>
                 </a>
               )}
               <div className="flex items-center gap-3 p-4 bg-slate-50 rounded-xl">
                 <Clock className="w-5 h-5 text-slate-400" />
                 <span className="font-semibold text-slate-700">Aberto hoje • 08:00 - 18:00</span>
               </div>
             </div>
          </div>

          {/* Coupons / Offers (Placeholder for now) */}
          <div className="bg-gradient-to-r from-purple-600 to-indigo-600 rounded-2xl p-8 shadow-lg text-white relative overflow-hidden">
            <div className="relative z-10">
               <h3 className="text-2xl font-bold mb-2">Clube de Vantagens</h3>
               <p className="text-indigo-100 mb-6">Mencione que viu no Guia Metropolitano e ganhe desconto especial!</p>
               <div className="bg-white/10 backdrop-blur-md border border-white/20 rounded-xl p-4 inline-flex items-center gap-4">
                 <span className="font-mono text-xl font-bold tracking-widest">GUIA10</span>
                 <span className="text-xs bg-white text-indigo-600 px-2 py-1 rounded font-bold">COPIAR</span>
               </div>
            </div>
            {/* Decoration */}
            <div className="absolute right-0 top-0 -mr-16 -mt-16 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
          </div>

        </div>

        {/* Sidebar / Map */}
        <div className="space-y-6">
           <div className="bg-white rounded-2xl p-2 shadow-sm border border-slate-100 overflow-hidden">
             <div className="h-64 rounded-xl overflow-hidden bg-slate-200 relative">
                {business.lat && business.lng ? (
                  <MapContainer center={[business.lat, business.lng]} zoom={15} style={{ height: '100%', width: '100%' }}>
                    <TileLayer url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png" />
                    <Marker position={[business.lat, business.lng]} />
                  </MapContainer>
                ) : (
                  <div className="flex items-center justify-center h-full text-slate-400">Mapa indisponível</div>
                )}
             </div>
             <div className="p-4">
               <h4 className="font-bold text-slate-900 mb-1">Localização</h4>
               <p className="text-slate-500 text-sm mb-4">{business.address}, {business.city}</p>
               <a
                 href={`https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(business.address + ', ' + business.city)}`}
                 target="_blank"
                 className="block w-full text-center py-2 border border-slate-200 rounded-lg text-slate-600 font-semibold hover:bg-slate-50 transition-colors text-sm"
               >
                 Ver no Google Maps
               </a>
             </div>
           </div>
        </div>

      </div>
    </div>
  );
};

export default BusinessDetails;
