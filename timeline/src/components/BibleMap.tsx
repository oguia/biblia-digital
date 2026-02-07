import { MapContainer, TileLayer, Marker, Popup, useMap } from 'react-leaflet';
import L from 'leaflet';
import { timelineData, TimelineEvent } from '../data';
import 'leaflet/dist/leaflet.css';
import { useEffect, useRef } from 'react';

// Fix for default marker icon in React-Leaflet
import icon from 'leaflet/dist/images/marker-icon.png';
import iconShadow from 'leaflet/dist/images/marker-shadow.png';

let DefaultIcon = L.icon({
    iconUrl: icon,
    shadowUrl: iconShadow,
    iconSize: [25, 41],
    iconAnchor: [12, 41],
});

L.Marker.prototype.options.icon = DefaultIcon;

function MapController({ selectedEvent }: { selectedEvent: TimelineEvent | null }) {
  const map = useMap();

  useEffect(() => {
    if (selectedEvent) {
      map.flyTo(selectedEvent.coordinates, 8, {
        animate: true,
        duration: 1.5
      });
      // Also open popup if possible, but requires ref to marker which is hard here.
      // Alternatively, we can just center.
    }
  }, [selectedEvent, map]);

  return null;
}

interface BibleMapProps {
    selectedEvent: TimelineEvent | null;
    onMarkerClick: (event: TimelineEvent) => void;
}

export function BibleMap({ selectedEvent, onMarkerClick }: BibleMapProps) {
  const markerRefs = useRef<{ [key: string]: L.Marker | null }>({});

  // Effect to open popup when selectedEvent changes
  useEffect(() => {
      if (selectedEvent && markerRefs.current[selectedEvent.id]) {
          markerRefs.current[selectedEvent.id]?.openPopup();
      }
  }, [selectedEvent]);

  return (
    <div className="h-full w-full rounded-lg overflow-hidden border border-gray-300 shadow-lg relative z-0">
      <MapContainer
        center={[31.7683, 35.2137]} // Jerusalem coordinates
        zoom={4}
        scrollWheelZoom={true}
        className="h-full w-full"
      >
        <MapController selectedEvent={selectedEvent} />
        <TileLayer
          attribution='&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
          url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
        />
        {timelineData.map((event) => (
          <Marker
            key={event.id}
            position={event.coordinates}
            ref={element => {
                if (element) {
                    markerRefs.current[event.id] = element;
                }
            }}
            eventHandlers={{
                click: () => onMarkerClick(event),
            }}
          >
            <Popup>
              <div className="p-1 min-w-[200px]">
                <h3 className="font-bold text-lg text-blue-800">{event.title}</h3>
                <p className="text-sm font-semibold text-gray-700 mb-1">{event.date}</p>
                <p className="text-sm italic text-gray-600 mb-2">{event.location}</p>
                <p className="text-sm text-gray-800">{event.description}</p>
                {/* Button inside popup to scroll to timeline? Maybe later. */}
              </div>
            </Popup>
          </Marker>
        ))}
      </MapContainer>
    </div>
  );
}
