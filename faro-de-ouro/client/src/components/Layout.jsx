import React from 'react';
import Sidebar from './Sidebar';

const Layout = ({ children, user }) => {
  return (
    <div className="flex min-h-screen bg-brand-gray-light">
      <Sidebar user={user} />
      <main className="flex-1 p-8 overflow-y-auto">
        {children}
      </main>
    </div>
  );
};

export default Layout;
