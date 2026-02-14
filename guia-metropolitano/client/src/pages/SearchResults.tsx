import { useEffect, useState } from 'react';
import { useSearchParams } from 'react-router-dom';
import { MapContainer, TileLayer, Marker, Popup } from 'react-leaflet';
import L from 'leaflet';
import { searchBusinesses } from '../services/api';
import BusinessCard from '../components/BusinessCard';
import { MapPin, List, Map as MapIcon } from 'lucide-react';

// Fix Leaflet marker icons in React
import icon from 'leaflet/dist/images/marker-icon.png';
import iconShadow from 'leaflet/dist/images/marker-shadow.png';

let DefaultIcon = L.icon({
    iconUrl: icon,
    shadowUrl: iconShadow,
    iconSize: [25, 41],
    iconAnchor: [12, 41]
});
L.Marker.prototype.options.icon = DefaultIcon;

const SearchResults = () => {
  const [searchParams] = useSearchParams();
  const query = searchParams.get('q') || '';
  const [results, setResults] = useState<any[]>([]);
  const [loading, setLoading] = useState(true);
  const [viewMode, setViewMode] = useState<'list' | 'map'>('list');

  useEffect(() => {
    const fetchResults = async () => {
      setLoading(true);
      const data = await searchBusinesses(query);
      setResults(data);
      setLoading(false);
    };
    fetchResults();
  }, [query]);

  // Center map on Curitiba default or first result
  const defaultCenter: [number, number] = [-25.4284, -49.2733];
  const mapCenter: [number, number] = results.length > 0 && results[0].lat ? [results[0].lat, results[0].lng] : defaultCenter;

  return (
    <div className="bg-slate-50 min-h-screen pb-20">
      {/* Header for Results */}
      <div className="bg-white border-b border-slate-200 sticky top-16 z-40">
        <div className="container mx-auto px-4 py-4 flex justify-between items-center">
          <div>
            <h1 className="text-xl font-bold text-slate-800">
              Resultados para <span className="text-green-600">"{query}"</span>
            </h1>
            <p className="text-sm text-slate-500">{results.length} locais encontrados</p>
          </div>

          {/* View Toggle (Mobile mostly) */}
          <div className="flex bg-slate-100 p-1 rounded-lg">
            <button
              onClick={() => setViewMode('list')}
              className={`p-2 rounded-md flex items-center gap-2 text-sm font-medium transition-colors ${viewMode === 'list' ? 'bg-white shadow text-slate-900' : 'text-slate-500'}`}
            >
              <List size={18} /> Lista
            </button>
            <button
              onClick={() => setViewMode('map')}
              className={`p-2 rounded-md flex items-center gap-2 text-sm font-medium transition-colors ${viewMode === 'map' ? 'bg-white shadow text-slate-900' : 'text-slate-500'}`}
            >
              <MapIcon size={18} /> Mapa
            </button>
          </div>
        </div>
      </div>

      <div className="container mx-auto px-4 py-6 flex flex-col lg:flex-row gap-6 h-[calc(100vh-180px)]">

        {/* List View */}
        <div className={`flex-1 overflow-y-auto pr-2 custom-scrollbar ${viewMode === 'map' ? 'hidden lg:block' : ''}`}>
           {loading ? (
             <div className="space-y-4">
               {[1,2,3].map(i => (
                 <div key={i} className="h-48 bg-slate-200 rounded-2xl animate-pulse"></div>
               ))}
             </div>
           ) : (
             <div className="space-y-6">
               {results.map(biz => (
                 <BusinessCard key={biz.id} business={biz} />
               ))}
               {results.length === 0 && (
                 <div className="text-center py-20 text-slate-500">
                   <MapPin className="mx-auto w-12 h-12 text-slate-300 mb-4" />
                   <p className="text-lg">Nenhum resultado encontrado.</p>
                   <p className="text-sm">Tente buscar por "Pizzaria" ou "Mecânica".</p>
                 </div>
               )}
             </div>
           )}
        </div>

        {/* Map View */}
        <div className={`flex-1 bg-slate-200 rounded-2xl overflow-hidden shadow-inner relative ${viewMode === 'list' ? 'hidden lg:block' : 'h-full'}`}>
           <MapContainer center={mapCenter} zoom={13} style={{ height: '100%', width: '100%' }}>
             <TileLayer
               attribution='&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
               url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
             />
             {results.map(biz => (
               biz.lat && biz.lng && (
                 <Marker key={biz.id} position={[biz.lat, biz.lng]}>
                   <Popup>
                     <div className="font-bold">{biz.name}</div>
                     <div className="text-xs text-slate-500">{biz.address}</div>
                     {biz.whatsapp && (
                       <a href={`https://wa.me/55${biz.whatsapp}`} target="_blank" className="block mt-2 text-green-600 font-bold text-xs text-center border border-green-200 bg-green-50 rounded py-1">
                         Chamar no Zap
                       </a>
                     )}
                   </Popup>
                 </Marker>
               )
             ))}
           </MapContainer>
        </div>

      </div>
    </div>
  );
};

export default SearchResults;
