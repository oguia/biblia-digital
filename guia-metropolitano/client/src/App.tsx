import { HashRouter as Router, Routes, Route } from 'react-router-dom';
import Header from './components/Header';
import Home from './pages/Home';
import SearchResults from './pages/SearchResults';
import Login from './pages/Login';
import RegisterBusiness from './pages/RegisterBusiness';
// import Footer from './components/Footer'; // Placeholder

const FooterPlaceholder = () => (
  <footer className="bg-slate-900 text-slate-500 py-8 text-center border-t border-slate-800">
    <div className="container mx-auto px-4">
      <p>&copy; {new Date().getFullYear()} O Guia Metropolitano. Feito com tecnologia de ponta para Curitiba.</p>
    </div>
  </footer>
);

function App() {
  return (
    <Router>
      <div className="min-h-screen bg-slate-50 font-sans text-slate-900 flex flex-col">
        <Header />
        <main className="flex-grow">
          <Routes>
            <Route path="/" element={<Home />} />
            <Route path="/busca" element={<SearchResults />} />
            <Route path="/login" element={<Login />} />
            <Route path="/anuncie" element={<RegisterBusiness />} />
            {/* Future routes: /categorias, /negocio/:slug */}
          </Routes>
        </main>
        <FooterPlaceholder />
      </div>
    </Router>
  );
}

export default App;
