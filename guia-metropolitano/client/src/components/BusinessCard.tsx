import React from 'react';
import { Phone, MapPin, CheckCircle, ExternalLink, MessageCircle } from 'lucide-react';
import { motion } from 'framer-motion';

interface BusinessProps {
  business: {
    id: number;
    name: string;
    slug: string;
    description: string;
    address: string;
    phone?: string;
    whatsapp?: string;
    image_url?: string;
    is_verified: boolean;
    is_featured: boolean;
    category_name?: string;
  };
}

const BusinessCard: React.FC<BusinessProps> = ({ business }) => {
  const whatsappLink = business.whatsapp
    ? `https://wa.me/55${business.whatsapp.replace(/\D/g, '')}?text=Olá, vi no Guia Metropolitano!`
    : business.phone
      ? `https://wa.me/55${business.phone.replace(/\D/g, '')}?text=Olá`
      : null;

  return (
    <motion.div
      initial={{ opacity: 0, y: 20 }}
      animate={{ opacity: 1, y: 0 }}
      className={`bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all border ${business.is_featured ? 'border-yellow-400 ring-1 ring-yellow-400/50' : 'border-slate-100'} overflow-hidden flex flex-col md:flex-row`}
    >
      {/* Image / Thumbnail */}
      <div className="md:w-48 h-48 md:h-auto bg-slate-200 flex-shrink-0 relative">
        {business.image_url ? (
          <img src={business.image_url} alt={business.name} className="w-full h-full object-cover" />
        ) : (
          <div className="w-full h-full flex items-center justify-center bg-slate-100 text-slate-400">
             <span className="text-4xl font-bold opacity-20">{business.name.charAt(0)}</span>
          </div>
        )}
        {business.is_featured && (
           <div className="absolute top-2 left-2 bg-yellow-400 text-yellow-900 text-xs font-bold px-2 py-1 rounded-full uppercase tracking-wider shadow-sm">
             Destaque
           </div>
        )}
      </div>

      {/* Content */}
      <div className="p-6 flex-grow flex flex-col justify-between">
        <div>
          <div className="flex justify-between items-start">
            <div>
               {business.category_name && (
                 <span className="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1 block">{business.category_name}</span>
               )}
               <h3 className="text-xl font-bold text-slate-900 flex items-center gap-2">
                 {business.name}
                 {business.is_verified && <CheckCircle className="w-5 h-5 text-blue-500 fill-blue-50" />}
               </h3>
            </div>
          </div>

          <p className="text-slate-600 mt-2 text-sm line-clamp-2">{business.description}</p>

          <div className="flex items-center gap-2 mt-4 text-slate-500 text-sm">
            <MapPin className="w-4 h-4 flex-shrink-0" />
            <span className="truncate">{business.address}</span>
          </div>
        </div>

        {/* Actions */}
        <div className="mt-6 flex flex-wrap gap-3">
          {whatsappLink && (
            <a
              href={whatsappLink}
              target="_blank"
              rel="noopener noreferrer"
              className="flex-1 bg-green-500 hover:bg-green-600 text-white py-3 px-4 rounded-xl font-bold flex items-center justify-center gap-2 transition-colors shadow-lg shadow-green-500/20"
            >
              <MessageCircle className="w-5 h-5" />
              Chamar no Zap
            </a>
          )}

          {business.phone && !whatsappLink && (
             <a
               href={`tel:${business.phone}`}
               className="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 py-3 px-4 rounded-xl font-bold flex items-center justify-center gap-2 transition-colors"
             >
               <Phone className="w-5 h-5" />
               Ligar
             </a>
          )}

          <button className="px-4 py-3 border border-slate-200 rounded-xl hover:bg-slate-50 text-slate-600 transition-colors">
            <ExternalLink className="w-5 h-5" />
          </button>
        </div>
      </div>
    </motion.div>
  );
};

export default BusinessCard;
