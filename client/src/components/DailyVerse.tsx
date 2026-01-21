import { useState, useEffect } from "react";
import { Button } from "@/components/ui/button";
import {
  Dialog,
  DialogContent,
  DialogHeader,
  DialogTitle,
  DialogDescription,
} from "@/components/ui/dialog";
import { Loader2, Share2, RefreshCw } from "lucide-react";
import { toast } from "sonner";

interface Verse {
  book: {
    name: string;
    version: string;
  };
  chapter: number;
  number: number;
  text: string;
}

interface StoredVerse {
  date: string;
  data: Verse;
}

export function DailyVerse() {
  const [isOpen, setIsOpen] = useState(false);
  const [verse, setVerse] = useState<Verse | null>(null);
  const [loading, setLoading] = useState(false);

  useEffect(() => {
    if (isOpen && !verse) {
      loadDailyVerse();
    }
  }, [isOpen]);

  const loadDailyVerse = async (forceRefresh = false) => {
    setLoading(true);
    try {
      const today = new Date().toISOString().split("T")[0];
      const stored = localStorage.getItem("daily_verse");

      if (stored && !forceRefresh) {
        const parsed: StoredVerse = JSON.parse(stored);
        if (parsed.date === today) {
          setVerse(parsed.data);
          setLoading(false);
          return;
        }
      }

      // Fetch new verse
      const response = await fetch(
        "https://www.abibliadigital.com.br/api/verses/nvi/random"
      );
      if (!response.ok) throw new Error("Falha ao buscar versículo");

      const data = await response.json();

      const newVerse: Verse = {
        book: {
          name: data.book.name,
          version: data.book.version,
        },
        chapter: data.chapter,
        number: data.number,
        text: data.text,
      };

      // Save to localStorage
      localStorage.setItem(
        "daily_verse",
        JSON.stringify({ date: today, data: newVerse })
      );

      setVerse(newVerse);
    } catch (error) {
      console.error(error);
      toast.error("Erro ao carregar o versículo do dia");
    } finally {
      setLoading(false);
    }
  };

  const handleShare = () => {
    if (!verse) return;
    const text = `"${verse.text}" - ${verse.book.name} ${verse.chapter}:${verse.number}`;
    navigator.clipboard.writeText(text);
    toast.success("Versículo copiado para a área de transferência!");
  };

  return (
    <>
      <div className="p-4 text-center">
        <Button
          onClick={() => setIsOpen(true)}
          className="w-full bg-background border border-primary text-primary hover:bg-muted font-bold rounded-full shadow-sm transition-all hover:shadow-md"
          variant="outline"
        >
          🌟 Ver Versículo do Dia
        </Button>
      </div>

      <Dialog open={isOpen} onOpenChange={setIsOpen}>
        <DialogContent className="sm:max-w-md">
          <DialogHeader>
            <DialogTitle className="text-center text-xl flex items-center justify-center gap-2">
              🌟 Versículo do Dia
            </DialogTitle>
            <DialogDescription className="text-center">
              Palavra de Deus para hoje
            </DialogDescription>
          </DialogHeader>

          <div className="py-6 flex flex-col items-center justify-center min-h-[150px]">
            {loading ? (
              <Loader2 className="h-8 w-8 animate-spin text-primary" />
            ) : verse ? (
              <div className="text-center space-y-4">
                <p className="text-lg font-serif italic leading-relaxed text-foreground/90">
                  "{verse.text}"
                </p>
                <p className="font-bold text-primary">
                  {verse.book.name} {verse.chapter}:{verse.number}
                </p>
              </div>
            ) : (
              <p className="text-muted-foreground">Não foi possível carregar o versículo.</p>
            )}
          </div>

          <div className="flex justify-center gap-2">
            <Button variant="outline" size="sm" onClick={() => loadDailyVerse(true)}>
              <RefreshCw className="mr-2 h-4 w-4" />
              Novo
            </Button>
            <Button size="sm" onClick={handleShare}>
              <Share2 className="mr-2 h-4 w-4" />
              Copiar
            </Button>
          </div>
        </DialogContent>
      </Dialog>
    </>
  );
}
