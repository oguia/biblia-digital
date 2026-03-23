import { Outlet, NavLink, useNavigate } from 'react-router-dom';
import axios from 'axios';
import { Home, Briefcase, Clock, LogOut, Menu, X } from 'lucide-react';
import { useState } from 'react';

export default function Layout({ user, setUser }) {
  const navigate = useNavigate();
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false);

  const handleLogout = async () => {
    try {
      await axios.post('/auth.php?action=logout');
    } catch (e) {
      // Ignore
    } finally {
      localStorage.removeItem('token');
      setUser(null);
      navigate('/login');
    }
  };

  const navLinks = [
    { to: '/', icon: Home, label: 'Dashboard' },
    { to: '/projects', icon: Briefcase, label: 'Projetos / Serviços' },
    { to: '/history', icon: Clock, label: 'Histórico' },
  ];

  return (
    <div className="flex h-screen bg-gray-50 flex-col md:flex-row">
      {/* Mobile Header */}
      <div className="md:hidden bg-blue-700 text-white p-4 flex justify-between items-center shadow-md z-20">
        <h1 className="text-xl font-bold">Cartão Ponto</h1>
        <button onClick={() => setMobileMenuOpen(!mobileMenuOpen)} className="p-1 focus:outline-none focus:ring-2 focus:ring-white rounded">
          {mobileMenuOpen ? <X /> : <Menu />}
        </button>
      </div>

      {/* Sidebar */}
      <aside className={`
        ${mobileMenuOpen ? 'translate-x-0' : '-translate-x-full'}
        md:translate-x-0 transition-transform duration-300 ease-in-out
        fixed md:static inset-y-0 left-0 w-64 bg-white border-r border-gray-200 shadow-lg md:shadow-none z-10 flex flex-col pt-16 md:pt-0
      `}>
        <div className="p-6 hidden md:block">
          <h1 className="text-2xl font-bold text-blue-700">Cartão Ponto</h1>
        </div>

        <div className="px-6 py-4 mb-4 border-b border-gray-100">
          <p className="text-sm text-gray-500">Logado como</p>
          <p className="font-semibold text-gray-800 truncate">{user.name}</p>
          <span className="inline-block mt-1 px-2 py-0.5 text-xs bg-blue-100 text-blue-800 rounded-full font-medium">
            {user.type === 'pj' ? 'Empresa (PJ)' : 'Pessoa Física'}
          </span>
        </div>

        <nav className="flex-1 px-4 space-y-2">
          {navLinks.map(({ to, icon: Icon, label }) => (
            <NavLink
              key={to}
              to={to}
              onClick={() => setMobileMenuOpen(false)}
              className={({ isActive }) =>
                `flex items-center px-4 py-3 rounded-lg transition-colors ${
                  isActive
                    ? 'bg-blue-50 text-blue-700 font-medium'
                    : 'text-gray-600 hover:bg-gray-50 hover:text-blue-600'
                }`
              }
            >
              <Icon className="h-5 w-5 mr-3" />
              {label}
            </NavLink>
          ))}
        </nav>

        <div className="p-4 border-t border-gray-200">
          <button
            onClick={handleLogout}
            className="flex items-center w-full px-4 py-2 text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
          >
            <LogOut className="h-5 w-5 mr-3" />
            Sair
          </button>
        </div>
      </aside>

      {/* Main Content */}
      <main className="flex-1 overflow-y-auto bg-gray-50 relative pt-4 md:pt-0">
        <div className="p-4 md:p-8 pb-20 md:pb-8">
          <Outlet />
        </div>
      </main>

      {/* Overlay for mobile */}
      {mobileMenuOpen && (
        <div
          className="fixed inset-0 bg-black bg-opacity-50 z-0 md:hidden"
          onClick={() => setMobileMenuOpen(false)}
        ></div>
      )}
    </div>
  );
}
