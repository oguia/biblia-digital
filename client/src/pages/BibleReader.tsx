import { useState, useEffect, useRef } from "react";
import { Button } from "@/components/ui/button";
import { Card } from "@/components/ui/card";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";
import { Input } from "@/components/ui/input";
import { ChevronLeft, ChevronRight, Highlighter, Trash2, Moon, Sun } from "lucide-react";
import { useTheme } from "@/contexts/ThemeContext";
import { getMockChapterData } from "@/lib/mockBibleData";

interface Verse {
  number: number;
  text: string;
}

interface Chapter {
  number: number;
  verses: Verse[];
}

interface HighlightedVerse {
  bookAbbrev: string;
  chapter: number;
  verse: number;
  text: string;
  version: string;
}

const VERSIONS = ["nvi", "ra", "acf", "kjv"];
const BOOKS = [
  { name: "Gênesis", abbrev: "gn" },
  { name: "Êxodo", abbrev: "ex" },
  { name: "Levítico", abbrev: "lv" },
  { name: "Números", abbrev: "nm" },
  { name: "Deuteronômio", abbrev: "dt" },
  { name: "Josué", abbrev: "js" },
  { name: "Juízes", abbrev: "jz" },
  { name: "Rute", abbrev: "rt" },
  { name: "1 Samuel", abbrev: "1sm" },
  { name: "2 Samuel", abbrev: "2sm" },
  { name: "1 Reis", abbrev: "1rs" },
  { name: "2 Reis", abbrev: "2rs" },
  { name: "1 Crônicas", abbrev: "1cr" },
  { name: "2 Crônicas", abbrev: "2cr" },
  { name: "Esdras", abbrev: "esd" },
  { name: "Neemias", abbrev: "ne" },
  { name: "Ester", abbrev: "et" },
  { name: "Jó", abbrev: "jó" },
  { name: "Salmos", abbrev: "sl" },
  { name: "Provérbios", abbrev: "pv" },
  { name: "Eclesiastes", abbrev: "ec" },
  { name: "Cântico dos Cânticos", abbrev: "ct" },
  { name: "Isaías", abbrev: "is" },
  { name: "Jeremias", abbrev: "jr" },
  { name: "Lamentações", abbrev: "lm" },
  { name: "Ezequiel", abbrev: "ez" },
  { name: "Daniel", abbrev: "dn" },
  { name: "Oséias", abbrev: "os" },
  { name: "Joel", abbrev: "jl" },
  { name: "Amós", abbrev: "am" },
  { name: "Obadias", abbrev: "ob" },
  { name: "Jonas", abbrev: "jn" },
  { name: "Miquéias", abbrev: "mq" },
  { name: "Naum", abbrev: "na" },
  { name: "Habacuque", abbrev: "hc" },
  { name: "Sofonias", abbrev: "sf" },
  { name: "Ageu", abbrev: "ag" },
  { name: "Zacarias", abbrev: "zc" },
  { name: "Malaquias", abbrev: "ml" },
  { name: "Mateus", abbrev: "mt" },
  { name: "Marcos", abbrev: "mc" },
  { name: "Lucas", abbrev: "lc" },
  { name: "João", abbrev: "jo" },
  { name: "Atos", abbrev: "at" },
  { name: "Romanos", abbrev: "rm" },
  { name: "1 Coríntios", abbrev: "1co" },
  { name: "2 Coríntios", abbrev: "2co" },
  { name: "Gálatas", abbrev: "gl" },
  { name: "Efésios", abbrev: "ef" },
  { name: "Filipenses", abbrev: "fp" },
  { name: "Colossenses", abbrev: "cl" },
  { name: "1 Tessalonicenses", abbrev: "1ts" },
  { name: "2 Tessalonicenses", abbrev: "2ts" },
  { name: "1 Timóteo", abbrev: "1tm" },
  { name: "2 Timóteo", abbrev: "2tm" },
  { name: "Tito", abbrev: "tt" },
  { name: "Filemom", abbrev: "fm" },
  { name: "Hebreus", abbrev: "hb" },
  { name: "Tiago", abbrev: "tg" },
  { name: "1 Pedro", abbrev: "1pd" },
  { name: "2 Pedro", abbrev: "2pd" },
  { name: "1 João", abbrev: "1jo" },
  { name: "2 João", abbrev: "2jo" },
  { name: "3 João", abbrev: "3jo" },
  { name: "Judas", abbrev: "jd" },
  { name: "Apocalipse", abbrev: "ap" },
];

export default function BibleReader() {
  const { theme, toggleTheme } = useTheme();
  const [version, setVersion] = useState<string>("nvi");
  const [selectedBook, setSelectedBook] = useState<string>("gn");
  const [chapter, setChapter] = useState<number>(1);
  const [chapters, setChapters] = useState<number>(1);
  const [verses, setVerses] = useState<Verse[]>([]);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);
  const [highlights, setHighlights] = useState<HighlightedVerse[]>([]);
  const [selectedVerses, setSelectedVerses] = useState<Set<number>>(new Set());
  const [touchStart, setTouchStart] = useState<number | null>(null);
  const pageRef = useRef<HTMLDivElement>(null);

  // Load highlights from localStorage
  useEffect(() => {
    const saved = localStorage.getItem("bible-highlights");
    if (saved) {
      try {
        setHighlights(JSON.parse(saved));
      } catch (e) {
        console.error("Failed to load highlights:", e);
      }
    }
  }, []);

  // Save highlights to localStorage
  useEffect(() => {
    localStorage.setItem("bible-highlights", JSON.stringify(highlights));
  }, [highlights]);

  // Fetch verses from API
  useEffect(() => {
    const fetchVerses = async () => {
      setLoading(true);
      setError(null);
      try {
        const mockData = getMockChapterData(version, chapter);
        if (mockData) {
          setVerses(mockData.verses);
          setChapters(mockData.chapter.verses);
        } else {
          const url = `https://www.abibliadigital.com.br/api/verses/${version}/${selectedBook}/${chapter}`;
          const response = await fetch(url);
          
          if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
          }
          
          const data = await response.json();
          
          if (data.verses && Array.isArray(data.verses)) {
            setVerses(data.verses);
            setChapters(data.chapter?.verses || data.verses.length);
          } else {
            throw new Error("Invalid response format");
          }
        }
      } catch (err) {
        const errorMsg = err instanceof Error ? err.message : "Unknown error";
        console.error("Error loading verses:", errorMsg);
        setError(errorMsg);
        setVerses([]);
      } finally {
        setLoading(false);
      }
    };

    fetchVerses();
  }, [version, selectedBook, chapter]);

  const handleTouchStart = (e: React.TouchEvent) => {
    setTouchStart(e.touches[0].clientX);
  };

  const handleTouchEnd = (e: React.TouchEvent) => {
    if (!touchStart) return;

    const touchEnd = e.changedTouches[0].clientX;
    const diff = touchStart - touchEnd;

    // Swipe left - next chapter
    if (diff > 50) {
      if (chapter < chapters) {
        setChapter(chapter + 1);
      }
    }
    // Swipe right - previous chapter
    else if (diff < -50) {
      if (chapter > 1) {
        setChapter(chapter - 1);
      }
    }

    setTouchStart(null);
  };

  const toggleHighlight = (verseNumber: number) => {
    const newSelected = new Set(selectedVerses);
    if (newSelected.has(verseNumber)) {
      newSelected.delete(verseNumber);
    } else {
      newSelected.add(verseNumber);
    }
    setSelectedVerses(newSelected);
  };

  const saveHighlights = () => {
    const book = BOOKS.find((b) => b.abbrev === selectedBook);
    const newHighlights = Array.from(selectedVerses).map((verseNum) => {
      const verse = verses.find((v) => v.number === verseNum);
      return {
        bookAbbrev: selectedBook,
        chapter,
        verse: verseNum,
        text: verse?.text || "",
        version,
      };
    });

    setHighlights((prev) => {
      const filtered = prev.filter(
        (h) =>
          !(
            h.bookAbbrev === selectedBook &&
            h.chapter === chapter &&
            h.version === version
          )
      );
      return [...filtered, ...newHighlights];
    });

    setSelectedVerses(new Set());
  };

  const removeHighlight = (highlight: HighlightedVerse) => {
    setHighlights((prev) =>
      prev.filter(
        (h) =>
          !(
            h.bookAbbrev === highlight.bookAbbrev &&
            h.chapter === highlight.chapter &&
            h.verse === highlight.verse &&
            h.version === highlight.version
          )
      )
    );
  };

  const isVerseHighlighted = (verseNumber: number): boolean => {
    return highlights.some(
      (h) =>
        h.bookAbbrev === selectedBook &&
        h.chapter === chapter &&
        h.verse === verseNumber &&
        h.version === version
    );
  };

  const book = BOOKS.find((b) => b.abbrev === selectedBook);

  return (
    <div className="min-h-screen bg-background text-foreground">
      {/* Header */}
      <header className="sticky top-0 z-40 border-b border-border bg-background/95 backdrop-blur supports-[backdrop-filter]:bg-background/60">
        <div className="container mx-auto px-4 py-4">
          <div className="flex items-center justify-between mb-4">
            <h1 className="text-2xl font-bold">📖 Bíblia Digital</h1>
            <Button
              variant="ghost"
              size="icon"
              onClick={toggleTheme}
              className="rounded-full"
            >
              {theme === "dark" ? (
                <Sun className="h-5 w-5" />
              ) : (
                <Moon className="h-5 w-5" />
              )}
            </Button>
          </div>

          {/* Controls */}
          <div className="grid grid-cols-1 md:grid-cols-4 gap-3">
            <Select value={version} onValueChange={setVersion}>
              <SelectTrigger>
                <SelectValue placeholder="Versão" />
              </SelectTrigger>
              <SelectContent>
                {VERSIONS.map((v) => (
                  <SelectItem key={v} value={v}>
                    {v.toUpperCase()}
                  </SelectItem>
                ))}
              </SelectContent>
            </Select>

            <Select value={selectedBook} onValueChange={setSelectedBook}>
              <SelectTrigger>
                <SelectValue placeholder="Livro" />
              </SelectTrigger>
              <SelectContent className="max-h-72">
                {BOOKS.map((b) => (
                  <SelectItem key={b.abbrev} value={b.abbrev}>
                    {b.name}
                  </SelectItem>
                ))}
              </SelectContent>
            </Select>

            <Select value={chapter.toString()} onValueChange={(v) => setChapter(parseInt(v))}>
              <SelectTrigger>
                <SelectValue placeholder="Capítulo" />
              </SelectTrigger>
              <SelectContent className="max-h-72">
                {Array.from({ length: chapters }, (_, i) => i + 1).map((c) => (
                  <SelectItem key={c} value={c.toString()}>
                    Capítulo {c}
                  </SelectItem>
                ))}
              </SelectContent>
            </Select>

            <div className="flex gap-2">
              <Button
                variant="outline"
                size="icon"
                onClick={() => chapter > 1 && setChapter(chapter - 1)}
                disabled={chapter <= 1}
              >
                <ChevronLeft className="h-4 w-4" />
              </Button>
              <Button
                variant="outline"
                size="icon"
                onClick={() => chapter < chapters && setChapter(chapter + 1)}
                disabled={chapter >= chapters}
              >
                <ChevronRight className="h-4 w-4" />
              </Button>
            </div>
          </div>
        </div>
      </header>

      <div className="container mx-auto px-4 py-8">
        <div className="grid grid-cols-1 lg:grid-cols-4 gap-6">
          {/* Main Bible Reader */}
          <div className="lg:col-span-3">
            {/* Magazine-style page */}
            <div
              ref={pageRef}
              onTouchStart={handleTouchStart}
              onTouchEnd={handleTouchEnd}
              className="bible-page"
            >
              <Card className="p-8 min-h-[600px] bg-card text-card-foreground shadow-lg">
                {loading && (
                  <div className="flex items-center justify-center h-96">
                    <div className="text-center">
                      <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-primary mx-auto mb-4"></div>
                      <p>Carregando...</p>
                    </div>
                  </div>
                )}

                {error && (
                  <div className="text-red-500 text-center py-8">
                    <p>Erro ao carregar: {error}</p>
                  </div>
                )}

                {!loading && !error && verses.length > 0 && (
                  <div className="space-y-6">
                    <div className="border-b pb-4 mb-6">
                      <h2 className="text-3xl font-bold mb-2">{book?.name}</h2>
                      <p className="text-sm text-muted-foreground">
                        Capítulo {chapter} • Versão {version.toUpperCase()}
                      </p>
                    </div>

                    <div className="space-y-4">
                      {verses.map((verse) => (
                        <div
                          key={verse.number}
                          onClick={() => toggleHighlight(verse.number)}
                          className={`p-3 rounded cursor-pointer transition-colors ${
                            selectedVerses.has(verse.number)
                              ? "bg-yellow-200 dark:bg-yellow-900"
                              : isVerseHighlighted(verse.number)
                                ? "bg-yellow-100 dark:bg-yellow-950"
                                : "hover:bg-muted"
                          }`}
                        >
                          <span className="font-bold text-primary mr-2">
                            {verse.number}
                          </span>
                          <span className="text-base leading-relaxed">
                            {verse.text}
                          </span>
                        </div>
                      ))}
                    </div>

                    {selectedVerses.size > 0 && (
                      <div className="flex gap-2 mt-6 pt-4 border-t">
                        <Button
                          onClick={saveHighlights}
                          className="flex items-center gap-2"
                        >
                          <Highlighter className="h-4 w-4" />
                          Marcar {selectedVerses.size} verso(s)
                        </Button>
                        <Button
                          variant="outline"
                          onClick={() => setSelectedVerses(new Set())}
                        >
                          Cancelar
                        </Button>
                      </div>
                    )}
                  </div>
                )}
              </Card>

              {/* Swipe hint */}
              <p className="text-center text-sm text-muted-foreground mt-4">
                💡 Deslize para mudar de capítulo
              </p>
            </div>
          </div>

          {/* Sidebar - Highlights */}
          <div className="lg:col-span-1">
            <Card className="p-4 sticky top-24 max-h-[calc(100vh-120px)] overflow-y-auto">
              <h3 className="font-bold text-lg mb-4 flex items-center gap-2">
                <Highlighter className="h-5 w-5" />
                Marcações
              </h3>

              {highlights.length === 0 ? (
                <p className="text-sm text-muted-foreground">
                  Nenhuma marcação ainda. Selecione versículos para marcar.
                </p>
              ) : (
                <div className="space-y-3">
                  {highlights.map((highlight, idx) => (
                    <div
                      key={idx}
                      className="p-3 bg-yellow-100 dark:bg-yellow-950 rounded text-sm border border-yellow-300 dark:border-yellow-700"
                    >
                      <div className="flex justify-between items-start gap-2 mb-2">
                        <div className="font-semibold text-xs text-yellow-900 dark:text-yellow-100">
                          {BOOKS.find((b) => b.abbrev === highlight.bookAbbrev)
                            ?.name || highlight.bookAbbrev}{" "}
                          {highlight.chapter}:{highlight.verse}
                        </div>
                        <Button
                          variant="ghost"
                          size="sm"
                          onClick={() => removeHighlight(highlight)}
                          className="h-6 w-6 p-0"
                        >
                          <Trash2 className="h-3 w-3" />
                        </Button>
                      </div>
                      <p className="text-xs leading-relaxed text-yellow-900 dark:text-yellow-50 line-clamp-3">
                        {highlight.text}
                      </p>
                    </div>
                  ))}
                </div>
              )}
            </Card>
          </div>
        </div>
      </div>
    </div>
  );
}

