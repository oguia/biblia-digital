import React, { useState } from 'react';
import { Link, useLocation } from 'react-router-dom';
import { LayoutDashboard, Package, RefreshCw, Tags, Users, LogOut, MessageSquare, ShoppingBag, Settings, Ticket, Menu, X } from 'lucide-react';

const Sidebar = ({ user, isOpen, setIsOpen }) => {
  const location = useLocation().pathname;

  const handleLogout = () => {
    localStorage.removeItem('faro_token');
    window.location.href = '#/login';
  };

  return (
    <>
      {/* Mobile overlay */}
      {isOpen && (
        <div
          className="fixed inset-0 bg-black/50 z-40 lg:hidden"
          onClick={() => setIsOpen(false)}
        />
      )}

      {/* Sidebar */}
      <div className={`fixed inset-y-0 left-0 z-50 w-64 bg-black text-white flex flex-col transition-transform duration-300 ease-in-out ${isOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'}`}>
        <div className="p-6 border-b border-gray-800 bg-white flex justify-between items-center">
          <img src="/logo.png" alt="Faro de Ouro" className="h-10" />
          <button onClick={() => setIsOpen(false)} className="lg:hidden text-gray-500 hover:text-gray-800">
            <X size={24} />
          </button>
        </div>

        <nav className="flex-1 p-4 space-y-2 overflow-y-auto">
          <Link to="/app" className={`flex items-center gap-3 px-4 py-3 rounded-lg transition-colors ${location === '/app' ? 'bg-brand-orange text-brand-dark font-bold' : 'text-gray-300 hover:bg-gray-800'}`}>
            <LayoutDashboard size={20} />
            Dashboard
          </Link>
          <Link to="/app/products" className={`flex items-center gap-3 px-4 py-3 rounded-lg transition-colors ${location === '/app/products' ? 'bg-brand-orange text-brand-dark font-bold' : 'text-gray-300 hover:bg-gray-800'}`}>
            <Package size={20} />
            Produtos
          </Link>
          <Link to="/app/kardex" className={`flex items-center gap-3 px-4 py-3 rounded-lg transition-colors ${location === '/app/kardex' ? 'bg-brand-orange text-brand-dark font-bold' : 'text-gray-300 hover:bg-gray-800'}`}>
            <RefreshCw size={20} />
            Kardex (Movimentações)
          </Link>
          <Link to="/app/categories" className={`flex items-center gap-3 px-4 py-3 rounded-lg transition-colors ${location === '/app/categories' ? 'bg-brand-orange text-brand-dark font-bold' : 'text-gray-300 hover:bg-gray-800'}`}>
            <Tags size={20} />
            Categorias
          </Link>
          <Link to="/app/suggestions" className={`flex items-center gap-3 px-4 py-3 rounded-lg transition-colors ${location === '/app/suggestions' ? 'bg-brand-orange text-brand-dark font-bold' : 'text-gray-300 hover:bg-gray-800'}`}>
            <MessageSquare size={20} />
            Sugestões
          </Link>
          {user?.role === 'owner' && (
            <Link to="/app/users" className={`flex items-center gap-3 px-4 py-3 rounded-lg transition-colors ${location === '/app/users' ? 'bg-brand-orange text-brand-dark font-bold' : 'text-gray-300 hover:bg-gray-800'}`}>
              <Users size={20} />
              Usuários
            </Link>
          )}

          {user?.role === 'superadmin' && (
            <div className="pt-6 mt-6 border-t border-gray-800 space-y-2">
              <div className="px-4 py-2 text-xs font-bold text-gray-500 uppercase tracking-wider">Super Admin</div>
              <Link to="/admin" className={`flex items-center gap-3 px-4 py-3 rounded-lg transition-colors ${location === '/admin' ? 'bg-brand-orange text-brand-dark font-bold' : 'text-gray-300 hover:bg-gray-800'}`}>
                <Users size={20} />
                Painel
              </Link>
              <Link to="/admin/plans" className={`flex items-center gap-3 px-4 py-3 rounded-lg transition-colors ${location === '/admin/plans' ? 'bg-brand-orange text-brand-dark font-bold' : 'text-gray-300 hover:bg-gray-800'}`}>
                <ShoppingBag size={20} />
                Planos
              </Link>
              <Link to="/admin/coupons" className={`flex items-center gap-3 px-4 py-3 rounded-lg transition-colors ${location === '/admin/coupons' ? 'bg-brand-orange text-brand-dark font-bold' : 'text-gray-300 hover:bg-gray-800'}`}>
                <Ticket size={20} />
                Cupons
              </Link>
              <Link to="/admin/settings" className={`flex items-center gap-3 px-4 py-3 rounded-lg transition-colors ${location === '/admin/settings' ? 'bg-brand-orange text-brand-dark font-bold' : 'text-gray-300 hover:bg-gray-800'}`}>
                <Settings size={20} />
                Configurações
              </Link>
            </div>
          )}
        </nav>

        <div className="p-4 border-t border-gray-800">
          <div className="mb-4 px-4">
            <div className="text-sm font-bold text-white">{user?.name}</div>
            <div className="text-xs text-brand-orange capitalize">{user?.role}</div>
          </div>
          <button onClick={handleLogout} className="flex items-center gap-3 px-4 py-2 w-full text-left text-red-400 hover:text-red-300 hover:bg-gray-800 rounded-lg transition-colors">
            <LogOut size={20} />
            Sair
          </button>
        </div>
      </div>
    </>
  );
};

export default Sidebar;
