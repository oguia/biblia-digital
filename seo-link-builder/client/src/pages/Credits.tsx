import { useState } from 'react';
import api from '../api/api';
import { Loader, ShoppingCart } from 'lucide-react';

const packages = [
  { id: '10_credits', credits: 10, price: 29.90, name: 'Starter Pack' },
  { id: '50_credits', credits: 50, price: 129.90, name: 'Pro Pack' },
  { id: '100_credits', credits: 100, price: 199.90, name: 'Agency Pack' },
];

const Credits = () => {
  const [loading, setLoading] = useState<string | null>(null);

  const buyCredits = async (packageId: string) => {
    setLoading(packageId);
    try {
      const { data } = await api.post('/payment/create_preference.php', { package_id: packageId });
      if (data.init_point) {
        window.location.href = data.init_point;
      } else {
        alert('Payment setup failed (Check API Key)');
      }
    } catch (err) {
      alert('Payment setup failed');
    } finally {
      setLoading(null);
    }
  };

  return (
    <div className="space-y-6 text-center max-w-4xl mx-auto">
      <h1 className="text-3xl font-bold text-gray-900">Buy Credits</h1>
      <p className="text-gray-600">Choose a package to boost your SEO campaigns.</p>

      <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
        {packages.map((pkg) => (
          <div key={pkg.id} className="bg-white rounded-lg shadow-lg p-8 border border-gray-100 flex flex-col hover:shadow-xl transition-shadow">
             <h3 className="text-xl font-bold text-gray-800">{pkg.name}</h3>
             <div className="my-4">
               <span className="text-4xl font-bold text-blue-600">{pkg.credits}</span>
               <span className="text-gray-500 ml-2">Links</span>
             </div>
             <div className="text-2xl font-semibold text-gray-700 mb-6">
               R$ {pkg.price.toFixed(2)}
             </div>
             <button
               onClick={() => buyCredits(pkg.id)}
               disabled={loading !== null}
               className="mt-auto bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 flex justify-center items-center font-bold"
             >
               {loading === pkg.id ? <Loader className="animate-spin" /> : <>Buy Now <ShoppingCart className="ml-2 w-5 h-5" /></>}
             </button>
          </div>
        ))}
      </div>
    </div>
  );
};

export default Credits;