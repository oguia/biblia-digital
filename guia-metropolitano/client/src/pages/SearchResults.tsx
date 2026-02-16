import { useEffect, useState } from 'react';
import { useSearchParams, Link } from 'react-router-dom';
import { MapContainer, TileLayer, Marker, Popup, useMap } from 'react-leaflet';
import L from 'leaflet';
import { searchBusinesses } from '../services/api';
import BusinessCard from '../components/BusinessCard';
import { MapPin, List, Map as MapIcon, SlidersHorizontal } from 'lucide-react';
import 'leaflet/dist/leaflet.css';

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

const MapUpdater = ({ businesses, center }: { businesses: any[], center: [number, number] }) => {
  const map = useMap();
  useEffect(() => {
    map.setView(center, 13);
    setTimeout(() => map.invalidateSize(), 300);
  }, [center, map]);
  return null;
};

const SearchResults = () => {
  const [searchParams] = useSearchParams();
  const query = searchParams.get('q') || '';
  const [results, setResults] = useState<any[]>([]);
  const [loading, setLoading] = useState(true);
  const [viewMode, setViewMode] = useState<'list' | 'map'>('list'); // Mobile only state

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
    <div className="flex flex-col h-[calc(100vh-64px)] bg-slate-50 overflow-hidden">
      {/* Sub-Header / Filters */}
      <div className="bg-white border-b border-slate-200 px-4 py-3 flex justify-between items-center shadow-sm z-20">
        <div>
          <h1 className="text-lg font-bold text-slate-800 truncate">
            {query ? `Busca: "${query}"` : 'Explorar Curitiba'}
          </h1>
          <p className="text-xs text-slate-500">{results.length} locais encontrados</p>
        </div>

        {/* Mobile View Toggle (Tabs Style) */}
        <div className="flex bg-slate-100 p-1 rounded-lg lg:hidden">
            <button
              onClick={() => setViewMode('list')}
              className={`px-4 py-1.5 rounded-md text-sm font-medium transition-all ${viewMode === 'list' ? 'bg-white shadow text-slate-900' : 'text-slate-500 hover:text-slate-700'}`}
            >
              Lista
            </button>
            <button
              onClick={() => setViewMode('map')}
              className={`px-4 py-1.5 rounded-md text-sm font-medium transition-all ${viewMode === 'map' ? 'bg-white shadow text-slate-900' : 'text-slate-500 hover:text-slate-700'}`}
            >
              Mapa
            </button>
        </div>

        {/* Desktop Filter Button (Placeholder) */}
        <button className="hidden lg:flex items-center gap-2 text-slate-500 hover:text-slate-800 text-sm font-medium border border-slate-200 px-3 py-1.5 rounded-lg hover:bg-slate-50 transition-colors">
          <SlidersHorizontal size={16} /> Filtros
        </button>
      </div>

      {/* Main Content Area - Split View */}
      <div className="flex flex-1 overflow-hidden relative">

        {/* Left Side: List (Visible on Desktop always, on Mobile if viewMode=list) */}
        <div className={`
          flex-1 lg:flex-[0.4] xl:flex-[0.35] bg-slate-50 overflow-y-auto custom-scrollbar p-4 space-y-4
          ${viewMode === 'map' ? 'hidden lg:block' : 'block'}
        `}>
           {loading ? (
             [1,2,3,4].map(i => (
               <div key={i} className="h-40 bg-slate-200 rounded-xl animate-pulse"></div>
             ))
           ) : results.length === 0 ? (
             <div className="text-center py-20 text-slate-400">
               <MapPin className="mx-auto w-12 h-12 mb-4 opacity-50" />
               <p>Nenhum local encontrado.</p>
             </div>
           ) : (
             results.map(biz => (
               <BusinessCard key={biz.id} business={biz} compact={true} />
             ))
           )}

           <div className="text-center py-8 text-xs text-slate-400">
             Fim dos resultados
           </div>
        </div>

        {/* Right Side: Map (Visible on Desktop always, on Mobile if viewMode=map) */}
        <div className={`
          flex-1 lg:flex-[0.6] xl:flex-[0.65] bg-slate-200 relative z-0
          ${viewMode === 'list' ? 'hidden lg:block' : 'block'}
        `}>
           <MapContainer
             center={mapCenter}
             zoom={13}
             style={{ height: '100%', width: '100%', zIndex: 0 }}
             zoomControl={false}
           >
             <MapUpdater businesses={results} center={mapCenter} />
             <TileLayer
               attribution='&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
               url="https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png"
             />
             {results.map(biz => (
               biz.lat && biz.lng && (
                 <Marker key={biz.id} position={[biz.lat, biz.lng]}>
                   <Popup>
                     <div className="font-sans min-w-[200px]">
                       <h3 className="font-bold text-sm mb-1">{biz.name}</h3>
                       <p className="text-xs text-slate-500 mb-2">{biz.address}</p>
                       <Link to={`/negocio/${biz.slug}`} className="block text-center bg-slate-900 text-white text-xs py-1.5 rounded hover:bg-slate-700 transition-colors">
                          Ver Detalhes
                       </Link>
                     </div>
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
