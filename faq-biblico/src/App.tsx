import { useState, useMemo } from 'react';
import { Header } from './components/Header';
import { Footer } from './components/Footer';
import { FAQCard } from './components/FAQCard';
import { faqData } from './data/faq_data';
import { Search } from 'lucide-react';
import 'framer-motion';

function App() {
  const [searchTerm, setSearchTerm] = useState('');
  const [selectedCategory, setSelectedCategory] = useState('Todas');

  const categories = useMemo(() => {
    const uniqueCategories = new Set(faqData.map(item => item.category));
    return Array.from(uniqueCategories).sort();
  }, []);

  const filteredData = useMemo(() => {
    return faqData.filter(item => {
      const matchesSearch = item.question.toLowerCase().includes(searchTerm.toLowerCase()) ||
                            item.answer.toLowerCase().includes(searchTerm.toLowerCase());
      const matchesCategory = selectedCategory === 'Todas' || item.category === selectedCategory;
      return matchesSearch && matchesCategory;
    });
  }, [searchTerm, selectedCategory]);

  return (
    <div className="min-h-screen bg-gray-50 flex flex-col font-sans text-gray-900">
      <Header
        onSearch={setSearchTerm}
        categories={categories}
        selectedCategory={selectedCategory}
        onSelectCategory={setSelectedCategory}
      />

      <main className="flex-grow container mx-auto px-4 py-8">

        {/* Intro Section (Optional, only show if no search/filter active) */}
        {!searchTerm && selectedCategory === 'Todas' && (
          <div className="text-center mb-12">
            <h2 className="text-3xl font-bold text-[var(--color-dark-grey)] mb-4">
              Encontre respostas para sua fé
            </h2>
            <p className="text-gray-600 max-w-2xl mx-auto">
              Navegue por perguntas reais sobre Deus, Jesus, a Bíblia e a vida cristã, com respostas fundamentadas nas Escrituras.
            </p>
          </div>
        )}

        {/* Results Info */}
        <div className="mb-6 flex justify-between items-center text-sm text-gray-500">
          <span>
            Exibindo {filteredData.length} {filteredData.length === 1 ? 'resultado' : 'resultados'}
            {selectedCategory !== 'Todas' && ` em "${selectedCategory}"`}
          </span>
        </div>

        {/* FAQ Grid */}
        {filteredData.length > 0 ? (
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
            {filteredData.map((item) => (
              <FAQCard key={item.id} item={item} />
            ))}
          </div>
        ) : (
          <div className="text-center py-20 bg-white rounded-lg shadow-sm border border-gray-100">
            <div className="inline-block p-4 rounded-full bg-gray-100 mb-4">
              <Search className="h-8 w-8 text-gray-400" />
            </div>
            <h3 className="text-lg font-medium text-gray-900">Nenhuma pergunta encontrada</h3>
            <p className="text-gray-500 mt-2">Tente buscar por outros termos ou selecione outra categoria.</p>
            <button
              onClick={() => { setSearchTerm(''); setSelectedCategory('Todas'); }}
              className="mt-4 px-4 py-2 bg-[var(--color-primary-red)] text-white rounded-md hover:bg-red-700 transition-colors"
            >
              Limpar filtros
            </button>
          </div>
        )}

      </main>

      <Footer />
    </div>
  );
}

export default App;
