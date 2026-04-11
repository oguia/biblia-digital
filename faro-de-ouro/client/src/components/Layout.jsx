import React, { useState } from 'react';
import Sidebar from './Sidebar';
import { Menu } from 'lucide-react';

const Layout = ({ children, user }) => {
  const [isSidebarOpen, setIsSidebarOpen] = useState(false);

  return (
    <div className="flex min-h-screen bg-brand-gray-light">
      <Sidebar user={user} isOpen={isSidebarOpen} setIsOpen={setIsSidebarOpen} />

      <main className="flex-1 flex flex-col min-w-0 lg:ml-64">
        {/* Mobile Header */}
        <header className="lg:hidden bg-white border-b px-4 py-4 flex items-center justify-between sticky top-0 z-30">
          <img src="/logo.png" alt="Faro de Ouro" className="h-8" />
          <button
            onClick={() => setIsSidebarOpen(true)}
            className="p-2 -mr-2 text-gray-600 hover:text-gray-900 focus:outline-none"
          >
            <Menu size={24} />
          </button>
        </header>

        {/* Content Area */}
        <div className="p-4 sm:p-8 overflow-y-auto flex-1">
          {children}
        </div>
      </main>
    </div>
  );
};

export default Layout;
