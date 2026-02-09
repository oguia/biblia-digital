import { useState, useEffect } from 'react';
import { api, BibleResponse, EnrichmentResponse } from '@/lib/api';
import { ContextOverlay } from '@/components/MaisDeus/ContextOverlay';
import { TimelineSlider } from '@/components/MaisDeus/TimelineSlider';
import { LivingMap } from '@/components/MaisDeus/LivingMap';
import { ApplicationCard } from '@/components/MaisDeus/ApplicationCard';
import { Button } from '@/components/ui/button';
import { ChevronLeft, ChevronRight, History, Map as MapIcon, BookOpen, Loader2 } from 'lucide-react';
import { useLocation } from 'wouter';
import { toast } from 'sonner';

export default function BibleHappeningNow() {
  const [bibleData, setBibleData] = useState<BibleResponse | null>(null);
  const [enrichment, setEnrichment] = useState<EnrichmentResponse | null>(null);
  const [loading, setLoading] = useState(true);
  const [showTimeline, setShowTimeline] = useState(false);
  const [showMap, setShowMap] = useState(false);

  // Default to Genesis 1
  const [book, setBook] = useState('gn');
  const [chapter, setChapter] = useState(1);
  const [version, setVersion] = useState('nvi');

  useEffect(() => {
    const loadData = async () => {
      setLoading(true);
      try {
        const [bibleRes, enrichmentRes] = await Promise.all([
          api.getBibleText(book, chapter, version),
          api.getEnrichment(book, chapter)
        ]);
        setBibleData(bibleRes);
        setEnrichment(enrichmentRes);
      } catch (err) {
        console.error("Failed to load data", err);
      } finally {
        setLoading(false);
      }
    };
    loadData();
  }, [book, chapter, version]);

  const toggleTimeline = () => setShowTimeline(!showTimeline);
  const toggleMap = () => setShowMap(!showMap);

  if (loading) {
    return (
      <div className="flex h-screen items-center justify-center">
        <Loader2 className="h-8 w-8 animate-spin text-primary" />
      </div>
    );
  }

  if (!bibleData || !enrichment) return <div>Erro ao carregar.</div>;

  const handleSaveProgress = async () => {
    try {
      await api.saveProgress(1, book, chapter);
      toast.success("Leitura concluída!", {
        description: "Deus se alegra com sua constância. Continue firme!"
      });
    } catch (e) {
      toast.error("Erro ao salvar progresso.");
    }
  };

  return (
    <div className="min-h-screen bg-background pb-20">

      {/* Top Bar - Mais Deus Branding */}
      <header className="bg-gradient-to-r from-[#9F1414] to-[#2C323E] text-white p-4 sticky top-0 z-50 shadow-md">
        <div className="container mx-auto flex justify-between items-center">
          <div className="flex items-center gap-3">
            <img src="/logo.png" alt="Mais Deus Logo" className="h-10 w-auto object-contain drop-shadow-lg" />
            <div className="hidden sm:block">
              <h1 className="text-lg font-bold tracking-tight leading-none">MAISDEUS.COM</h1>
              <span className="text-[10px] text-white/80 uppercase tracking-widest font-medium block">Bíblia Acontecendo Agora</span>
            </div>
          </div>

          <div className="flex gap-2">
            <Button
              variant="secondary"
              size="sm"
              onClick={toggleTimeline}
              className={`gap-2 ${showTimeline ? 'bg-white text-primary' : 'bg-white/10 text-white hover:bg-white/20'}`}
            >
              <History className="w-4 h-4" />
              <span className="hidden sm:inline">Onde estou na História?</span>
            </Button>
            <Button
              variant="secondary"
              size="sm"
              onClick={toggleMap}
              className={`gap-2 ${showMap ? 'bg-white text-primary' : 'bg-white/10 text-white hover:bg-white/20'}`}
            >
              <MapIcon className="w-4 h-4" />
              <span className="hidden sm:inline">Mapa Vivo</span>
            </Button>
          </div>
        </div>
      </header>

      {/* Timeline Collapse */}
      {showTimeline && (
        <div className="bg-muted border-b shadow-inner animate-in slide-in-from-top-4 duration-300">
          <div className="container mx-auto">
            <TimelineSlider events={enrichment.timeline} />
          </div>
        </div>
      )}

      {/* Main Content */}
      <main className="container mx-auto px-4 py-8 grid grid-cols-1 lg:grid-cols-12 gap-8">

        {/* Left Column: Bible Text */}
        <div className="lg:col-span-8 space-y-8">

          {/* Chapter Header */}
          <div className="flex items-center justify-between border-b pb-4">
            <div>
              <h2 className="text-3xl font-extrabold text-foreground">{bibleData.book.name} {bibleData.chapter.number}</h2>
              <p className="text-muted-foreground text-sm uppercase tracking-wide font-medium mt-1">
                {enrichment.timeline[0]?.era || 'Antigo Testamento'} • {enrichment.timeline[0]?.year < 0 ? `${Math.abs(enrichment.timeline[0]?.year)} a.C.` : `${enrichment.timeline[0]?.year} d.C.`}
              </p>
            </div>
            <div className="flex gap-2">
               <Button variant="outline" size="icon" disabled={chapter <= 1} onClick={() => setChapter(c => c - 1)}>
                 <ChevronLeft className="w-4 h-4" />
               </Button>
               <Button variant="outline" size="icon" disabled={chapter >= bibleData.chapter.total} onClick={() => setChapter(c => c + 1)}>
                 <ChevronRight className="w-4 h-4" />
               </Button>
            </div>
          </div>

          {/* Context Overlay (Always visible or collapsible? Requirement says "Ao abrir... o usuário vê") */}
          <ContextOverlay
            who={JSON.parse(enrichment.context.who_involved)}
            when={enrichment.context.when_text}
            where={enrichment.context.where_text}
            events={enrichment.context.historical_events}
          />

          {/* Bible Text */}
          <div className="prose prose-lg dark:prose-invert max-w-none font-serif leading-loose">
            {bibleData.verses.map((verse) => (
              <span key={verse.number} className="hover:bg-yellow-50 dark:hover:bg-yellow-900/20 transition-colors rounded px-1 cursor-pointer">
                <sup className="text-xs font-sans text-primary/50 mr-1 select-none">{verse.number}</sup>
                {verse.text}{' '}
              </span>
            ))}
          </div>

          {/* Map (Inline if toggled, or always visible at bottom?) Requirement: "Cada leitura acende pontos no mapa" */}
          {showMap && (
            <div className="mt-8 animate-in fade-in zoom-in duration-300">
              <h3 className="text-xl font-bold mb-4 flex items-center gap-2">
                <MapIcon className="w-5 h-5 text-primary" />
                Mapa Bíblico Vivo
              </h3>
              <LivingMap locations={enrichment.map} />
            </div>
          )}

        </div>

        {/* Right Column: Application & Insights (Sticky) */}
        <div className="lg:col-span-4 space-y-6">
          <div className="sticky top-24 space-y-6">

            {/* Application Card (The "Have to do with me" part) */}
            <ApplicationCard
              truth={enrichment.application.truth}
              alert={enrichment.application.alert}
              action={enrichment.application.action}
            />

            {/* Spiritual Journey Progress (Mock) */}
            <div className="bg-card border rounded-xl p-6 shadow-sm">
              <h4 className="font-bold text-sm uppercase tracking-wide mb-4 flex items-center gap-2">
                <BookOpen className="w-4 h-4 text-primary" />
                Sua Jornada
              </h4>
              <div className="space-y-4">
                <div>
                  <div className="flex justify-between text-xs mb-1">
                    <span>Gênesis</span>
                    <span>2%</span>
                  </div>
                  <div className="h-2 bg-muted rounded-full overflow-hidden">
                    <div className="h-full bg-primary w-[2%]" />
                  </div>
                </div>
                <p className="text-xs text-muted-foreground italic">
                  "Continue firme! Deus fala através da constância."
                </p>
                <Button variant="default" className="w-full text-xs bg-primary hover:bg-primary/90" onClick={handleSaveProgress}>
                  Marcar como Lido
                </Button>
              </div>
            </div>

          </div>
        </div>

      </main>
    </div>
  );
}
