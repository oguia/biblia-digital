import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import Header from './components/Header';
import Home from './pages/Home';
import SearchResults from './pages/SearchResults';
import BusinessDetails from './pages/BusinessDetails';
import Login from './pages/Login';
import RegisterBusiness from './pages/RegisterBusiness';
import AIConsultantWidget from './components/AIConsultantWidget';
import AdminDashboard from './pages/AdminDashboard';
import OwnerDashboard from './pages/OwnerDashboard';
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
            <Route path="/negocio/:slug" element={<BusinessDetails />} />
            <Route path="/login" element={<Login />} />
            <Route path="/anuncie" element={<RegisterBusiness />} />
            <Route path="/admin" element={<AdminDashboard />} />
            <Route path="/dashboard" element={<OwnerDashboard />} />
          </Routes>
        </main>
        <FooterPlaceholder />
        <AIConsultantWidget />
      </div>
    </Router>
  );
}

export default App;
