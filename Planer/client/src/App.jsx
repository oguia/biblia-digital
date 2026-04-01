import React, { useEffect, useState } from 'react';
import { Router, Route, Switch, useLocation } from 'wouter';
import { useHashLocation } from 'wouter/use-hash-location';
import { LogOut, Book, User, Settings, FolderOpen, Calendar } from 'lucide-react';

import Login from './pages/Login';
import Register from './pages/Register';
import AdminDashboard from './pages/AdminDashboard';
import PlansList from './pages/PlansList';
import PlanForm from './pages/PlanForm';
import Library from './pages/Library';
import PlanPdfView from './pages/PlanPdfView';
import LessonCalendar from './pages/LessonCalendar';
import Profile from './pages/Profile';
import { getCurrentUser } from './lib/api';

// Simple placeholder layout
function Layout({ children, user, setUser }) {
  const [, setLocation] = useHashLocation();

  const handleLogout = () => {
    localStorage.removeItem('token');
    localStorage.removeItem('user');
    setUser(null);
    setLocation('/login');
  };

  if (!user) {
     return <div className="min-h-screen bg-slate-50">{children}</div>;
  }

  return (
    <div className="min-h-screen bg-slate-50 text-slate-900 flex flex-col md:flex-row">
      {/* Sidebar */}
      <aside className="bg-navy-900 text-white w-full md:w-64 flex-shrink-0 shadow-lg md:min-h-screen flex flex-col">
        <div className="p-4 flex items-center justify-between md:block">
          <h1 className="text-2xl font-bold tracking-wider text-highlight flex items-center gap-2">
            <Book size={28} />
            Planer
          </h1>
        </div>
        <nav className="p-4 space-y-2 hidden md:block flex-1">
          <a href="#/" className="flex items-center gap-3 p-3 rounded hover:bg-navy-800 transition-colors">
            <Book size={20} /> Planos de Aula
          </a>
          <a href="#/calendar" className="flex items-center gap-3 p-3 rounded hover:bg-navy-800 transition-colors">
            <Calendar size={20} /> Calendário
          </a>
          <a href="#/library" className="flex items-center gap-3 p-3 rounded hover:bg-navy-800 transition-colors">
            <FolderOpen size={20} /> Repositório
          </a>

          <div className="my-4 border-t border-navy-700"></div>

          <a href="#/profile" className="flex items-center gap-3 p-3 rounded hover:bg-navy-800 transition-colors">
            <User size={20} /> Meu Perfil
          </a>

          {user.is_admin === 1 && (
            <a href="#/admin" className="flex items-center gap-3 p-3 rounded hover:bg-navy-800 transition-colors text-yellow-400 hover:text-yellow-300">
              <Settings size={20} /> Painel Admin
            </a>
          )}
        </nav>

        <div className="p-4 mt-auto hidden md:block">
             <button onClick={handleLogout} className="w-full flex items-center gap-3 p-3 rounded hover:bg-red-500/20 text-red-400 transition-colors">
            <LogOut size={20} /> Sair
          </button>
        </div>
      </aside>

      {/* Main Content */}
      <main className="flex-1 w-full bg-slate-50 min-h-screen">
         {/* Mobile Header */}
         <header className="md:hidden flex justify-between items-center bg-navy-900 text-white p-4 shadow-md sticky top-0 z-10">
            <h2 className="text-lg font-bold flex items-center gap-2"><Book size={20} /> Planer</h2>
            <button onClick={handleLogout} className="text-red-400 hover:text-red-300"><LogOut size={20} /></button>
         </header>

        <div className="p-4 md:p-8 max-w-6xl mx-auto">
          {children}
        </div>
      </main>
    </div>
  );
}


function App() {
  const [user, setUser] = useState(null);
  const [loading, setLoading] = useState(true);
  const [location, setLocation] = useHashLocation();

  useEffect(() => {
    const checkAuth = async () => {
      const token = localStorage.getItem('token');
      if (!token) {
        setUser(null);
        setLoading(false);
        if (location !== '/login' && location !== '/register') {
          setLocation('/login');
        }
        return;
      }

      try {
        const res = await getCurrentUser();
        setUser(res.user);
        if (location === '/login' || location === '/register') {
           setLocation('/');
        }
      } catch (e) {
        setUser(null);
        if (location !== '/login' && location !== '/register') {
          setLocation('/login');
        }
      } finally {
        setLoading(false);
      }
    };
    checkAuth();
  }, [location, setLocation]);

  if (loading) return <div className="flex h-screen items-center justify-center bg-slate-50"><div className="animate-spin rounded-full h-12 w-12 border-b-2 border-highlight"></div></div>;

  return (
    <Router hook={useHashLocation}>
      <Layout user={user} setUser={setUser}>
        <Switch>
          <Route path="/login" component={Login} />
          <Route path="/register" component={Register} />
          <Route path="/admin">
            {user?.is_admin ? <AdminDashboard /> : <PlansList />}
          </Route>
          <Route path="/plan/new" component={PlanForm} />
          <Route path="/plan/:id/pdf" component={PlanPdfView} />
          <Route path="/plan/:id" component={PlanForm} />
          <Route path="/library" component={Library} />
          <Route path="/calendar" component={LessonCalendar} />
          <Route path="/profile" component={Profile} />
          <Route path="/" component={PlansList} />
        </Switch>
      </Layout>
    </Router>
  );
}

export default App;
