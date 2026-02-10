import React from 'react';
import { MapContainer, TileLayer, Marker, Popup } from 'react-leaflet';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

// Fix Leaflet icons in React
import icon from 'leaflet/dist/images/marker-icon.png';
import iconShadow from 'leaflet/dist/images/marker-shadow.png';

let DefaultIcon = L.icon({
    iconUrl: icon,
    shadowUrl: iconShadow,
    iconSize: [25, 41],
    iconAnchor: [12, 41]
});

L.Marker.prototype.options.icon = DefaultIcon;

interface MapLocation {
  name: string;
  latitude: number;
  longitude: number;
  description: string;
  type: string;
}

interface LivingMapProps {
  locations: MapLocation[];
}

export const LivingMap: React.FC<LivingMapProps> = ({ locations }) => {
  if (locations.length === 0) {
    return (
      <div className="flex items-center justify-center h-64 bg-muted rounded-lg border-2 border-dashed border-muted-foreground/25">
        <p className="text-muted-foreground">Nenhum local mencionado neste capítulo.</p>
      </div>
    );
  }

  const center: [number, number] = [locations[0].latitude, locations[0].longitude];

  return (
    <div className="h-64 md:h-96 w-full rounded-xl overflow-hidden shadow-md border z-0 relative">
      <MapContainer center={center} zoom={6} scrollWheelZoom={false} className="h-full w-full">
        <TileLayer
          attribution='&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
          url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
        />
        {locations.map((loc, idx) => (
          <Marker key={idx} position={[loc.latitude, loc.longitude]}>
            <Popup>
              <strong className="text-primary">{loc.name}</strong><br />
              <span className="text-xs text-muted-foreground">{loc.description}</span>
            </Popup>
          </Marker>
        ))}
      </MapContainer>
    </div>
  );
};
