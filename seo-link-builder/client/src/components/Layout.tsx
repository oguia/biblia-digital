import { useState } from 'react';
import { Outlet, Link, useLocation } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import {
  Home,
  Link as LinkIcon,
  Globe,
  Settings,
  LogOut,
  Menu,
  X,
  CreditCard
} from 'lucide-react';
import { clsx } from 'clsx';

const Layout = () => {
  const { user, logout } = useAuth();
  const location = useLocation();
  const [sidebarOpen, setSidebarOpen] = useState(false);

  const navigation = [
    { name: 'Dashboard', href: '/', icon: Home },
    { name: 'My Projects', href: '/projects', icon: LinkIcon },
    { name: 'Buy Credits', href: '/credits', icon: CreditCard },
  ];

  if (user?.role === 'admin') {
    navigation.push({ name: 'Targets & Discovery', href: '/targets', icon: Globe });
    navigation.push({ name: 'Submission Worker', href: '/worker', icon: Settings });
  }

  return (
    <div className="min-h-screen bg-gray-100 flex text-gray-900">
      {/* Mobile Sidebar Overlay */}
      <div className={clsx("fixed inset-0 z-40 bg-gray-600 bg-opacity-75 md:hidden", sidebarOpen ? "block" : "hidden")} onClick={() => setSidebarOpen(false)}></div>

      {/* Sidebar */}
      <div className={clsx("fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg transform transition-transform md:translate-x-0 md:static md:inset-auto md:flex md:w-64 md:flex-col", sidebarOpen ? "translate-x-0" : "-translate-x-full")}>
        <div className="flex h-16 items-center justify-center border-b px-4">
          <h1 className="text-xl font-bold text-blue-600">SEO Master</h1>
          <button className="ml-auto md:hidden" onClick={() => setSidebarOpen(false)}>
            <X className="h-6 w-6" />
          </button>
        </div>
        <div className="flex-1 overflow-y-auto py-4">
          <nav className="space-y-1 px-2">
            {navigation.map((item) => (
              <Link
                key={item.name}
                to={item.href}
                className={clsx(
                  location.pathname === item.href ? 'bg-gray-100 text-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900',
                  'group flex items-center rounded-md px-2 py-2 text-base font-medium'
                )}
                onClick={() => setSidebarOpen(false)}
              >
                <item.icon className="mr-4 h-6 w-6 flex-shrink-0" />
                {item.name}
              </Link>
            ))}
          </nav>
        </div>
        <div className="border-t p-4">
          <div className="flex items-center mb-4">
             <div className="ml-1 overflow-hidden">
                <p className="text-sm font-medium text-gray-700 truncate">{user?.email}</p>
                <p className="text-xs font-medium text-green-600">{user?.credits} Credits</p>
             </div>
          </div>
          <button
             onClick={() => logout()}
             className="w-full flex items-center justify-center rounded-md border border-transparent bg-red-100 py-2 px-4 text-sm font-medium text-red-700 hover:bg-red-200"
          >
             <LogOut className="mr-2 h-4 w-4" /> Logout
          </button>
        </div>
      </div>

      {/* Main Content */}
      <div className="flex flex-1 flex-col overflow-hidden">
        <header className="flex h-16 items-center justify-between bg-white px-4 shadow-sm md:hidden">
          <button onClick={() => setSidebarOpen(true)} className="text-gray-500 focus:outline-none">
            <Menu className="h-6 w-6" />
          </button>
          <span className="text-lg font-bold text-gray-900">SEO Master</span>
          <div className="w-6"></div>
        </header>
        <main className="flex-1 overflow-y-auto p-4 md:p-8 bg-gray-50">
          <Outlet />
        </main>
      </div>
    </div>
  );
};

export default Layout;