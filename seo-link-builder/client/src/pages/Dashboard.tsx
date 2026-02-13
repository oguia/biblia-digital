import { useAuth } from '../context/AuthContext';
import { Link } from 'react-router-dom';

const Dashboard = () => {
  const { user } = useAuth();

  return (
    <div className="space-y-6">
      <h1 className="text-3xl font-bold text-gray-900">Welcome, {user?.email}</h1>
      <div className="grid grid-cols-1 gap-6 md:grid-cols-3">
        <div className="rounded-lg bg-white p-6 shadow-sm">
          <h3 className="text-lg font-medium text-gray-900">Credits Available</h3>
          <p className="mt-2 text-3xl font-bold text-blue-600">{user?.credits}</p>
          <Link to="/credits" className="mt-4 block text-sm text-blue-600 hover:underline">Buy more credits</Link>
        </div>
        <div className="rounded-lg bg-white p-6 shadow-sm">
          <h3 className="text-lg font-medium text-gray-900">Active Projects</h3>
          <p className="mt-2 text-3xl font-bold text-green-600">--</p>
          <Link to="/projects" className="mt-4 block text-sm text-green-600 hover:underline">View projects</Link>
        </div>
        {user?.role === 'admin' && (
          <div className="rounded-lg bg-white p-6 shadow-sm">
            <h3 className="text-lg font-medium text-gray-900">System Targets</h3>
            <p className="mt-2 text-3xl font-bold text-purple-600">--</p>
            <Link to="/targets" className="mt-4 block text-sm text-purple-600 hover:underline">Manage targets</Link>
          </div>
        )}
      </div>

      <div className="rounded-lg bg-white p-6 shadow-sm">
         <h2 className="text-xl font-semibold mb-4 text-gray-800">Quick Actions</h2>
         <div className="flex gap-4">
            <Link to="/projects" className="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Submit New Link</Link>
            {user?.role === 'admin' && <Link to="/worker" className="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">Start Worker</Link>}
         </div>
      </div>
    </div>
  );
};

export default Dashboard;