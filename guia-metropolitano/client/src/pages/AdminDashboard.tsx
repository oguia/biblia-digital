import { useState } from 'react';
import axios from 'axios';
import { CheckCircle, XCircle, Trash2, Star, Eye } from 'lucide-react';

interface Business {
  id: number;
  name: string;
  slug: string;
  is_verified: boolean;
  is_featured: boolean;
  category_name: string;
  owner_email?: string;
  created_at: string;
}

const AdminDashboard = () => {
  const [secret, setSecret] = useState('');
  const [businesses, setBusinesses] = useState<Business[]>([]);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');

  const fetchBusinesses = async () => {
    setLoading(true);
    setError('');
    try {
      const apiPath = import.meta.env.DEV ? 'http://localhost:8000/api/admin.php' : './api/admin.php';
      const res = await axios.get(apiPath, {
        headers: { 'X-Admin-Secret': secret }
      });
      setBusinesses(res.data);
    } catch (err) {
      setError('Unauthorized or Error fetching data');
      setBusinesses([]);
    } finally {
      setLoading(false);
    }
  };

  const handleAction = async (id: number, action: string) => {
    try {
      const apiPath = import.meta.env.DEV ? 'http://localhost:8000/api/admin.php' : './api/admin.php';
      await axios.post(apiPath, { id, action }, {
        headers: { 'X-Admin-Secret': secret }
      });
      // Refresh list locally
      setBusinesses(prev => prev.map(b => {
        if (b.id !== id) return b;
        if (action === 'verify') return { ...b, is_verified: true };
        if (action === 'unverify') return { ...b, is_verified: false };
        if (action === 'feature') return { ...b, is_featured: true };
        if (action === 'unfeature') return { ...b, is_featured: false };
        return b;
      }).filter(b => action !== 'delete' || b.id !== id));
    } catch (err) {
      alert('Action failed');
    }
  };

  if (!businesses.length && !loading) {
    return (
      <div className="min-h-screen flex items-center justify-center bg-slate-100">
        <div className="bg-white p-8 rounded-xl shadow-lg w-full max-w-md">
          <h1 className="text-2xl font-bold mb-4">Admin Login</h1>
          {error && <p className="text-red-500 mb-4">{error}</p>}
          <input
            type="password"
            placeholder="Admin Secret Key"
            className="w-full p-3 border rounded mb-4"
            value={secret}
            onChange={e => setSecret(e.target.value)}
          />
          <button
            onClick={fetchBusinesses}
            className="w-full bg-slate-900 text-white py-3 rounded font-bold hover:bg-slate-800"
          >
            Access Dashboard
          </button>
        </div>
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-slate-50 p-8">
      <div className="max-w-6xl mx-auto">
        <div className="flex justify-between items-center mb-8">
          <h1 className="text-3xl font-bold text-slate-900">Admin Dashboard</h1>
          <button onClick={() => setBusinesses([])} className="text-red-500 hover:underline">Logout</button>
        </div>

        <div className="bg-white rounded-xl shadow-sm overflow-hidden border border-slate-200">
          <table className="w-full text-left border-collapse">
            <thead className="bg-slate-50 border-b border-slate-200">
              <tr>
                <th className="p-4 font-semibold text-slate-600">ID</th>
                <th className="p-4 font-semibold text-slate-600">Name</th>
                <th className="p-4 font-semibold text-slate-600">Category</th>
                <th className="p-4 font-semibold text-slate-600">Status</th>
                <th className="p-4 font-semibold text-slate-600 text-right">Actions</th>
              </tr>
            </thead>
            <tbody>
              {businesses.map(biz => (
                <tr key={biz.id} className="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                  <td className="p-4 text-slate-500">#{biz.id}</td>
                  <td className="p-4 font-medium text-slate-900">
                    {biz.name}
                    {biz.owner_email && <div className="text-xs text-slate-400">{biz.owner_email}</div>}
                  </td>
                  <td className="p-4 text-slate-600">{biz.category_name}</td>
                  <td className="p-4 space-y-1">
                    {biz.is_verified ? (
                      <span className="inline-flex items-center gap-1 text-xs font-bold text-green-600 bg-green-50 px-2 py-1 rounded-full border border-green-200">
                        <CheckCircle size={12} /> Verified
                      </span>
                    ) : (
                      <span className="inline-flex items-center gap-1 text-xs font-bold text-slate-500 bg-slate-100 px-2 py-1 rounded-full">
                        Unverified
                      </span>
                    )}
                    {biz.is_featured && (
                      <span className="inline-flex items-center gap-1 text-xs font-bold text-yellow-600 bg-yellow-50 px-2 py-1 rounded-full border border-yellow-200 ml-2">
                        <Star size={12} fill="currentColor" /> Featured
                      </span>
                    )}
                  </td>
                  <td className="p-4 text-right space-x-2">
                    <button
                      onClick={() => window.open(`#/negocio/${biz.slug}`, '_blank')}
                      className="text-slate-400 hover:text-blue-500 p-2 rounded hover:bg-blue-50 transition-colors"
                      title="View"
                    >
                      <Eye size={18} />
                    </button>

                    {biz.is_verified ? (
                      <button
                        onClick={() => handleAction(biz.id, 'unverify')}
                        className="text-slate-400 hover:text-orange-500 p-2 rounded hover:bg-orange-50 transition-colors"
                        title="Unverify"
                      >
                        <XCircle size={18} />
                      </button>
                    ) : (
                      <button
                        onClick={() => handleAction(biz.id, 'verify')}
                        className="text-slate-400 hover:text-green-500 p-2 rounded hover:bg-green-50 transition-colors"
                        title="Verify"
                      >
                        <CheckCircle size={18} />
                      </button>
                    )}

                    {biz.is_featured ? (
                      <button
                        onClick={() => handleAction(biz.id, 'unfeature')}
                        className="text-yellow-500 hover:text-yellow-600 p-2 rounded hover:bg-yellow-50 transition-colors"
                        title="Unfeature"
                      >
                        <Star size={18} fill="currentColor" />
                      </button>
                    ) : (
                      <button
                        onClick={() => handleAction(biz.id, 'feature')}
                        className="text-slate-400 hover:text-yellow-500 p-2 rounded hover:bg-yellow-50 transition-colors"
                        title="Feature"
                      >
                        <Star size={18} />
                      </button>
                    )}

                    <button
                      onClick={() => { if(window.confirm('Delete this business?')) handleAction(biz.id, 'delete'); }}
                      className="text-slate-400 hover:text-red-500 p-2 rounded hover:bg-red-50 transition-colors"
                      title="Delete"
                    >
                      <Trash2 size={18} />
                    </button>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  );
};

export default AdminDashboard;
