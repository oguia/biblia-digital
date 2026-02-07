import { useState } from 'react';
import { Timeline } from './components/Timeline';
import { BibleMap } from './components/BibleMap';
import { ImageModal } from './components/ImageModal';
import { TimelineEvent } from './data';
import { BookOpen } from 'lucide-react';

function App() {
  const [selectedEvent, setSelectedEvent] = useState<TimelineEvent | null>(null);
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [modalEvent, setModalEvent] = useState<TimelineEvent | null>(null);

  const handleSelectEvent = (event: TimelineEvent) => {
    setSelectedEvent(event);
    // Smooth scroll to map on mobile
    if (window.innerWidth < 768) {
       const mapElement = document.getElementById('map-container');
       if (mapElement) {
           mapElement.scrollIntoView({ behavior: 'smooth' });
       }
    }
  };

  const handleViewImages = (event: TimelineEvent) => {
      setModalEvent(event);
      setIsModalOpen(true);
  };

  const handleMarkerClick = (event: TimelineEvent) => {
      setSelectedEvent(event);
      // Optional: scroll to timeline item
  };

  return (
    <div className="min-h-screen bg-slate-50 font-sans flex flex-col">
      {/* Header */}
      <header className="bg-gradient-to-r from-blue-900 to-slate-800 text-white p-4 shadow-lg sticky top-0 z-[100]">
        <div className="container mx-auto flex justify-between items-center">
            <div className="flex items-center gap-2">
                <BookOpen className="text-amber-400" />
                <h1 className="text-xl md:text-2xl font-bold tracking-tight">Linha do Tempo Bíblica</h1>
            </div>
            <div className="text-xs md:text-sm opacity-80 font-light border-l pl-3 border-white/30 hidden sm:block">
                De Gênesis a Apocalipse
            </div>
        </div>
      </header>

      {/* Main Content */}
      <main className="container mx-auto p-4 flex flex-col md:flex-row gap-6 flex-grow">

        {/* Map Section - Mobile: Top, Desktop: Sticky Right */}
        <div className="w-full md:w-5/12 order-1 md:order-2">
            <div className="sticky top-20 h-[50vh] md:h-[calc(100vh-8rem)] rounded-xl overflow-hidden shadow-xl border border-slate-200 bg-white z-0" id="map-container">
                <BibleMap
                    selectedEvent={selectedEvent}
                    onMarkerClick={handleMarkerClick}
                />
            </div>
            <div className="mt-2 text-center text-sm text-gray-500 md:hidden">
                Toque nos marcadores para ver detalhes
            </div>
        </div>

        {/* Timeline Section - Mobile: Bottom, Desktop: Left (Scrollable) */}
        <div className="w-full md:w-7/12 order-2 md:order-1">
            <div className="bg-white rounded-xl shadow-sm border border-slate-200 p-4 md:p-6">
                <div className="flex justify-between items-center mb-6 border-b pb-4">
                    <h2 className="text-2xl font-bold text-slate-800">Eventos Históricos</h2>
                    <span className="text-sm bg-blue-100 text-blue-800 py-1 px-3 rounded-full font-medium">
                        Cronologia
                    </span>
                </div>
                <Timeline
                    onSelectEvent={handleSelectEvent}
                    onViewImages={handleViewImages}
                />
            </div>
        </div>

      </main>

      {/* Footer */}
      <footer className="bg-slate-900 text-slate-400 py-6 text-center mt-auto border-t border-slate-800">
          <div className="container mx-auto px-4">
            <p className="mb-2">&copy; {new Date().getFullYear()} Linha do Tempo Bíblica.</p>
            <p className="text-xs text-slate-600">Desenvolvido com React, Leaflet e Tailwind CSS.</p>
          </div>
      </footer>

      {/* Image Modal */}
      {modalEvent && (
          <ImageModal
            isOpen={isModalOpen}
            onClose={() => setIsModalOpen(false)}
            title={modalEvent.title}
            imageOld={modalEvent.imageOld}
            imageNew={modalEvent.imageNew}
          />
      )}
    </div>
  );
}

export default App;
