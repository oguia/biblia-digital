import React, { useEffect, useState } from 'react';
import Layout from '../components/Layout';
import api from '../api';
import { format } from 'date-fns';
import { ArrowDownRight, ArrowUpRight, GitCommit, Download, Camera } from 'lucide-react';
import BarcodeScanner from '../components/BarcodeScanner';
import { exportToCSV } from '../utils/export';

const Kardex = ({ user }) => {
  const [movements, setMovements] = useState([]);
  const [products, setProducts] = useState([]);
  const [showModal, setShowModal] = useState(false);
  const [showScanner, setShowScanner] = useState(false);
  const [formData, setFormData] = useState({ product_id: '', type: 'in', quantity: '', reason: '' });

  const loadData = () => {
    api.get('products&action=kardex').then(res => setMovements(res.data));
    api.get('products&action=list').then(res => setProducts(res.data));
  };

  useEffect(() => { loadData(); }, []);

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      await api.post('products&action=movement', formData);
      setShowModal(false);
      loadData();
      setFormData({ product_id: '', type: 'in', quantity: '', reason: '' });
    } catch (err) {
      alert(err.response?.data?.error || 'Erro ao movimentar estoque');
    }
  };

  const handleExport = () => {
      const exportData = movements.map(m => ({
          Data: format(new Date(m.created_at), 'dd/MM/yyyy HH:mm'),
          Produto: m.product_name,
          Tipo: m.type,
          Quantidade: m.quantity,
          Motivo: m.reason,
          Usuario: m.user_name
      }));
      exportToCSV('movimentacoes.csv', exportData);
  };

  const handleScan = (code) => {
      const p = products.find(prod => prod.code === code);
      if(p) {
          setFormData({...formData, product_id: p.id});
          alert('Produto encontrado: ' + p.name);
      } else {
          alert('Código não encontrado: ' + code);
      }
  };

  return (
    <Layout user={user}>
      <div className="flex justify-between items-center mb-6">
        <h1 className="text-2xl font-bold text-brand-dark">Movimentações (Kardex)</h1>
        <div className="flex gap-2">
            <button onClick={handleExport} className="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg flex items-center gap-2 hover:bg-gray-300 transition">
              <Download size={20} /> Exportar
            </button>
            <button onClick={() => setShowModal(true)} className="bg-brand-orange text-white px-4 py-2 rounded-lg hover:bg-orange-600 transition">
              Registrar Movimento
            </button>
        </div>
      </div>

      <div className="bg-white rounded-xl shadow-sm overflow-hidden overflow-x-auto">
        <table className="min-w-full divide-y divide-gray-200">
          <thead className="bg-gray-50">
            <tr>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Data</th>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Produto</th>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipo</th>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Qtd</th>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Motivo</th>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Usuário</th>
            </tr>
          </thead>
          <tbody className="bg-white divide-y divide-gray-200">
            {movements.map(m => (
              <tr key={m.id}>
                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{format(new Date(m.created_at), 'dd/MM/yyyy HH:mm')}</td>
                <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{m.product_name}</td>
                <td className="px-6 py-4 whitespace-nowrap text-sm">
                  {m.type === 'in' && <span className="flex items-center text-green-600"><ArrowUpRight size={16} className="mr-1"/> Entrada</span>}
                  {m.type === 'out' && <span className="flex items-center text-red-600"><ArrowDownRight size={16} className="mr-1"/> Saída</span>}
                  {m.type === 'adjustment' && <span className="flex items-center text-brand-orange"><GitCommit size={16} className="mr-1"/> Ajuste</span>}
                </td>
                <td className="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-700">{m.quantity}</td>
                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{m.reason || '-'}</td>
                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{m.user_name}</td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>

      {showModal && (
        <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
          <div className="bg-white rounded-xl p-6 max-w-md w-full">
            <h2 className="text-xl font-bold mb-4">Novo Movimento</h2>
            <form onSubmit={handleSubmit} className="space-y-4">

              <div className="flex gap-2">
                  <select required className="flex-1 px-3 py-2 border rounded focus:ring-brand-orange" value={formData.product_id} onChange={e => setFormData({...formData, product_id: e.target.value})}>
                    <option value="">Selecione o Produto...</option>
                    {products.map(p => <option key={p.id} value={p.id}>{p.name} (Atual: {p.current_stock})</option>)}
                  </select>
                  <button type="button" onClick={() => setShowScanner(true)} className="px-3 bg-gray-100 border rounded hover:bg-gray-200"><Camera size={20}/></button>
              </div>

              <div className="flex gap-4">
                <label className="flex items-center gap-2"><input type="radio" name="type" value="in" checked={formData.type === 'in'} onChange={e => setFormData({...formData, type: e.target.value})} className="text-brand-orange" /> Entrada</label>
                <label className="flex items-center gap-2"><input type="radio" name="type" value="out" checked={formData.type === 'out'} onChange={e => setFormData({...formData, type: e.target.value})} className="text-brand-orange" /> Saída</label>
                <label className="flex items-center gap-2"><input type="radio" name="type" value="adjustment" checked={formData.type === 'adjustment'} onChange={e => setFormData({...formData, type: e.target.value})} className="text-brand-orange" /> Ajuste</label>
              </div>

              <input type="number" required placeholder={formData.type === 'adjustment' ? 'Novo Estoque Real' : 'Quantidade'} min="1" className="w-full px-3 py-2 border rounded focus:ring-brand-orange" value={formData.quantity} onChange={e => setFormData({...formData, quantity: e.target.value})} />
              <input type="text" placeholder="Motivo (Opcional)" className="w-full px-3 py-2 border rounded focus:ring-brand-orange" value={formData.reason} onChange={e => setFormData({...formData, reason: e.target.value})} />

              <div className="flex justify-end gap-2 mt-6">
                <button type="button" onClick={() => setShowModal(false)} className="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded">Cancelar</button>
                <button type="submit" className="px-4 py-2 bg-brand-orange text-white rounded hover:bg-orange-600">Salvar</button>
              </div>
            </form>
          </div>
        </div>
      )}

      {showScanner && <BarcodeScanner onScan={handleScan} onClose={() => setShowScanner(false)} />}
    </Layout>
  );
};

export default Kardex;
