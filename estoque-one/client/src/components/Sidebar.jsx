import React from 'react';
import { Link, useLocation, useNavigate } from 'react-router-dom';
import { LayoutDashboard, Package, History, Settings, LogOut, MessageSquare, Tags } from 'lucide-react';
import clsx from 'clsx';

const Sidebar = ({ user }) => {
  const location = useLocation();
  const navigate = useNavigate();

  const handleLogout = () => {
    localStorage.removeItem('estoque_token');
    navigate('/login');
  };

  const navItems = user?.role === 'superadmin' ? [
    { name: 'Dashboard', path: '/admin', icon: LayoutDashboard },
    { name: 'Planos', path: '/admin/plans', icon: Settings },
  ] : [
    { name: 'Dashboard', path: '/app', icon: LayoutDashboard },
    { name: 'Categorias', path: '/app/categories', icon: Tags },
    { name: 'Produtos', path: '/app/products', icon: Package },
    { name: 'Movimentações', path: '/app/kardex', icon: History },
    { name: 'Sugestões', path: '/app/suggestions', icon: MessageSquare },
  ];

  return (
    <div className="w-64 bg-brand-dark text-white min-h-screen p-4 flex flex-col">
      <div className="flex items-center gap-3 mb-8 px-2">
        <img src="/logo.png" alt="Estoque One" className="w-10 h-10 object-contain bg-white rounded p-1" />
        <span className="font-bold text-xl text-brand-orange">Estoque One</span>
      </div>

      <nav className="flex-1 space-y-2">
        {navItems.map((item) => (
          <Link
            key={item.path}
            to={item.path}
            className={clsx(
              "flex items-center gap-3 px-4 py-3 rounded-lg transition-colors",
              location.pathname === item.path
                ? "bg-brand-orange text-white"
                : "text-gray-300 hover:bg-gray-800"
            )}
          >
            <item.icon size={20} />
            <span>{item.name}</span>
          </Link>
        ))}
      </nav>

      <div className="mt-auto border-t border-gray-700 pt-4">
        <div className="px-4 py-2 text-sm text-gray-400 mb-2">
          {user?.name}
          <div className="text-xs">{user?.role === 'superadmin' ? 'Super Admin' : 'Empresa/PF'}</div>
        </div>
        <button
          onClick={handleLogout}
          className="flex items-center gap-3 px-4 py-3 text-gray-300 hover:bg-gray-800 rounded-lg w-full transition-colors"
        >
          <LogOut size={20} />
          <span>Sair</span>
        </button>
      </div>
    </div>
  );
};

export default Sidebar;
