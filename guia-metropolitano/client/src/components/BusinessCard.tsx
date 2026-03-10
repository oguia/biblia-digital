import React from 'react';
import { Link } from 'react-router-dom';
import { Phone, MapPin, CheckCircle, ExternalLink, MessageCircle } from 'lucide-react';
import { motion } from 'framer-motion';
import { useTranslation } from 'react-i18next';

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
  compact?: boolean;
}

const BusinessCard: React.FC<BusinessProps> = ({ business, compact = false }) => {
  const { t } = useTranslation();
  const [imgError, setImgError] = React.useState(false);

  const whatsappLink = business.whatsapp
    ? `https://wa.me/55${business.whatsapp.replace(/\D/g, '')}?text=Olá, vi no Guia Metropolitano!`
    : business.phone
      ? `https://wa.me/55${business.phone.replace(/\D/g, '')}?text=Olá`
      : null;

  return (
    <motion.div
      initial={{ opacity: 0, y: 20 }}
      animate={{ opacity: 1, y: 0 }}
      className={`bg-white rounded-xl shadow-sm hover:shadow-lg transition-all border ${business.is_featured ? 'border-yellow-400 ring-1 ring-yellow-400/50' : 'border-slate-200'} overflow-hidden flex flex-col ${compact ? '' : 'md:flex-row'}`}
    >
      {/* Image / Thumbnail */}
      <Link to={`/negocio/${business.slug}`} className={`${compact ? 'h-40 w-full' : 'md:w-48 h-48 md:h-auto'} bg-slate-100 flex-shrink-0 relative group-hover:opacity-95 transition-opacity cursor-pointer overflow-hidden`}>
        {!imgError && business.image_url ? (
          <img
            src={business.image_url}
            alt={business.name}
            className="w-full h-full object-cover"
            onError={() => setImgError(true)}
          />
        ) : (
          <div className="w-full h-full flex items-center justify-center bg-slate-100 text-slate-400">
             <span className="text-4xl font-bold opacity-20">{business.name.charAt(0)}</span>
          </div>
        )}
        {business.is_featured && (
           <div className="absolute top-2 left-2 bg-yellow-400 text-yellow-900 text-xs font-bold px-2 py-1 rounded-full uppercase tracking-wider shadow-sm">
             {t('business.featured')}
           </div>
        )}
      </Link>

      {/* Content */}
      <div className={`${compact ? 'p-4' : 'p-6'} flex-grow flex flex-col justify-between`}>
        <div>
          <div className="flex justify-between items-start">
            <div className="w-full">
               {business.category_name && (
                 <span className="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1 block">{business.category_name}</span>
               )}
               <Link to={`/negocio/${business.slug}`} className="hover:text-green-600 transition-colors block w-full">
                 <h3 className={`${compact ? 'text-lg' : 'text-xl'} font-bold text-slate-900 flex items-center gap-2 truncate`}>
                   {business.name}
                   {business.is_verified && <CheckCircle className="w-4 h-4 text-blue-500 fill-blue-50 flex-shrink-0" />}
                 </h3>
               </Link>
            </div>
          </div>

          <p className="text-slate-600 mt-2 text-sm line-clamp-2 leading-relaxed">{business.description}</p>

          <div className="flex items-center gap-1.5 mt-3 text-slate-500 text-xs">
            <MapPin className="w-3.5 h-3.5 flex-shrink-0 opacity-70" />
            <span className="truncate max-w-[250px]">{business.address}</span>
          </div>
        </div>

        {/* Actions */}
        <div className={`mt-4 flex flex-wrap gap-2 ${compact ? 'text-xs' : ''}`}>
          {whatsappLink && (
            <a
              href={whatsappLink}
              target="_blank"
              rel="noopener noreferrer"
              className="flex-1 bg-green-600 hover:bg-green-500 text-white py-2 px-3 rounded-lg font-bold flex items-center justify-center gap-1.5 transition-colors shadow-sm"
            >
              <MessageCircle className="w-4 h-4" />
              WhatsApp
            </a>
          )}

          {business.phone && !whatsappLink && (
             <a
               href={`tel:${business.phone}`}
               className="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 py-2 px-3 rounded-lg font-bold flex items-center justify-center gap-1.5 transition-colors"
             >
               <Phone className="w-4 h-4" />
               Ligar
             </a>
          )}

          <Link to={`/negocio/${business.slug}`} className="px-3 py-2 border border-slate-200 rounded-lg hover:bg-slate-50 text-slate-600 transition-colors flex items-center justify-center">
            <ExternalLink className="w-4 h-4" />
          </Link>
        </div>
      </div>
    </motion.div>
  );
};

export default BusinessCard;
