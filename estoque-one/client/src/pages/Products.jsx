import React, { useEffect, useState } from 'react';
import Layout from '../components/Layout';
import api from '../api';
import { Plus, Trash2, Camera, Download } from 'lucide-react';
import BarcodeScanner from '../components/BarcodeScanner';
import { exportToCSV } from '../utils/export';

const Products = ({ user }) => {
  const [products, setProducts] = useState([]);
  const [categories, setCategories] = useState([]);
  const [suppliers, setSuppliers] = useState([]);

  const [showModal, setShowModal] = useState(false);
  const [showScanner, setShowScanner] = useState(false);

  const [formData, setFormData] = useState({ code: '', name: '', category_id: '', supplier_id: '', price: '', min_stock: '', current_stock: '' });

  const loadData = () => {
    api.get('products&action=list').then(res => setProducts(res.data));
    api.get('categories&action=list').then(res => setCategories(res.data));
    api.get('suppliers&action=list').then(res => setSuppliers(res.data));
  };

  useEffect(() => { loadData(); }, []);

  const handleSubmit = async (e) => {
    e.preventDefault();
    await api.post('products&action=create', formData);
    setShowModal(false);
    loadData();
    setFormData({ code: '', name: '', category_id: '', supplier_id: '', price: '', min_stock: '', current_stock: '' });
  };

  const handleDelete = async (id) => {
    if(confirm('Tem certeza?')) {
      await api.delete(`products&action=delete&id=${id}`);
      loadData();
    }
  };

  const handleExport = () => {
    const exportData = products.map(p => ({
      ID: p.id,
      Código: p.code,
      Nome: p.name,
      Categoria: p.category_name,
      Fornecedor: p.supplier_name,
      Preço: p.price,
      Estoque_Atual: p.current_stock,
      Estoque_Min: p.min_stock
    }));
    exportToCSV('produtos.csv', exportData);
  };

  return (
    <Layout user={user}>
      <div className="flex justify-between items-center mb-6">
        <h1 className="text-2xl font-bold text-brand-dark">Produtos</h1>
        <div className="flex gap-2">
            <button onClick={handleExport} className="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg flex items-center gap-2 hover:bg-gray-300 transition">
              <Download size={20} /> Exportar
            </button>
            <button onClick={() => setShowModal(true)} className="bg-brand-orange text-white px-4 py-2 rounded-lg flex items-center gap-2 hover:bg-orange-600 transition">
              <Plus size={20} /> Novo
            </button>
        </div>
      </div>

      <div className="bg-white rounded-xl shadow-sm overflow-hidden overflow-x-auto">
        <table className="min-w-full divide-y divide-gray-200">
          <thead className="bg-gray-50">
            <tr>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Código</th>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nome</th>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Categoria</th>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estoque</th>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Preço</th>
              <th className="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Ações</th>
            </tr>
          </thead>
          <tbody className="bg-white divide-y divide-gray-200">
            {products.map(p => (
              <tr key={p.id}>
                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{p.code || '-'}</td>
                <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{p.name}</td>
                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{p.category_name || '-'}</td>
                <td className="px-6 py-4 whitespace-nowrap text-sm">
                  <span className={`px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${p.current_stock <= p.min_stock ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'}`}>
                    {p.current_stock}
                  </span>
                </td>
                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">R$ {Number(p.price).toFixed(2)}</td>
                <td className="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <button onClick={() => handleDelete(p.id)} className="text-red-600 hover:text-red-900 ml-4"><Trash2 size={18} /></button>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>

      {showModal && (
        <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
          <div className="bg-white rounded-xl p-6 max-w-md w-full max-h-screen overflow-y-auto">
            <h2 className="text-xl font-bold mb-4">Novo Produto</h2>
            <form onSubmit={handleSubmit} className="space-y-4">
              <input type="text" placeholder="Nome do Produto" required className="w-full px-3 py-2 border rounded focus:ring-brand-orange" value={formData.name} onChange={e => setFormData({...formData, name: e.target.value})} />

              <div className="flex gap-2">
                  <input type="text" placeholder="Código (Barras/SKU)" className="flex-1 px-3 py-2 border rounded focus:ring-brand-orange" value={formData.code} onChange={e => setFormData({...formData, code: e.target.value})} />
                  <button type="button" onClick={() => setShowScanner(true)} className="px-3 bg-gray-100 border rounded hover:bg-gray-200"><Camera size={20}/></button>
              </div>

              <select className="w-full px-3 py-2 border rounded focus:ring-brand-orange" value={formData.category_id} onChange={e => setFormData({...formData, category_id: e.target.value})}>
                <option value="">Sem Categoria</option>
                {categories.map(c => <option key={c.id} value={c.id}>{c.name}</option>)}
              </select>

              <select className="w-full px-3 py-2 border rounded focus:ring-brand-orange" value={formData.supplier_id} onChange={e => setFormData({...formData, supplier_id: e.target.value})}>
                <option value="">Sem Fornecedor</option>
                {suppliers.map(s => <option key={s.id} value={s.id}>{s.name}</option>)}
              </select>

              <div className="grid grid-cols-2 gap-4">
                <input type="number" step="0.01" placeholder="Preço (R$)" className="w-full px-3 py-2 border rounded focus:ring-brand-orange" value={formData.price} onChange={e => setFormData({...formData, price: e.target.value})} />
                <input type="number" placeholder="Estoque Inicial" required className="w-full px-3 py-2 border rounded focus:ring-brand-orange" value={formData.current_stock} onChange={e => setFormData({...formData, current_stock: e.target.value})} />
              </div>
              <input type="number" placeholder="Estoque Mínimo (Alerta)" required className="w-full px-3 py-2 border rounded focus:ring-brand-orange" value={formData.min_stock} onChange={e => setFormData({...formData, min_stock: e.target.value})} />
              <div className="flex justify-end gap-2 mt-6">
                <button type="button" onClick={() => setShowModal(false)} className="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded">Cancelar</button>
                <button type="submit" className="px-4 py-2 bg-brand-orange text-white rounded hover:bg-orange-600">Salvar</button>
              </div>
            </form>
          </div>
        </div>
      )}

      {showScanner && <BarcodeScanner onScan={(code) => setFormData({...formData, code})} onClose={() => setShowScanner(false)} />}
    </Layout>
  );
};

export default Products;
