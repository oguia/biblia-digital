import React, { useEffect, useState } from 'react';
import Layout from '../components/Layout';
import api from '../api';
import { Plus, Trash2, Camera, Download, Upload } from 'lucide-react';
import BarcodeScanner from '../components/BarcodeScanner';
import { exportToCSV } from '../utils/export';

const Products = ({ user }) => {
  const [products, setProducts] = useState([]);
  const [categories, setCategories] = useState([]);
  const [suppliers, setSuppliers] = useState([]);

  const [showModal, setShowModal] = useState(false);
  const [showScanner, setShowScanner] = useState(false);
  const [showImportModal, setShowImportModal] = useState(false);
  const [importFile, setImportFile] = useState(null);
  const [importing, setImporting] = useState(false);

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

  const handleImportSubmit = async (e) => {
    e.preventDefault();
    if (!importFile) return;

    setImporting(true);
    const formDataObj = new FormData();
    formDataObj.append('file', importFile);

    try {
      // Create a specific fetch request since we are sending FormData (not JSON)
      const token = localStorage.getItem('faro_token');
      const response = await fetch('./api/index.php?route=products&action=import_csv', {
        method: 'POST',
        headers: {
          'Authorization': `Bearer ${token}`
        },
        body: formDataObj
      });

      const result = await response.json();

      if (!response.ok) {
        throw new Error(result.error || 'Erro ao importar planilha');
      }

      alert(`Importação concluída!\n\nNovos: ${result.imported}\nAtualizados: ${result.updated}\nIgnorados (sem nome): ${result.skipped}`);
      setShowImportModal(false);
      setImportFile(null);
      loadData();
    } catch (err) {
      alert(err.message);
    } finally {
      setImporting(false);
    }
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
      Faro_Atual: p.current_stock,
      Faro_Min: p.min_stock
    }));
    exportToCSV('produtos.csv', exportData);
  };

  return (
    <Layout user={user}>
      <div className="flex justify-between items-center mb-8 flex-wrap gap-4">
        <h1 className="text-2xl font-bold text-brand-dark">Produtos</h1>
        <div className="flex gap-2 flex-wrap">
          <button onClick={() => setShowImportModal(true)} className="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg flex items-center gap-2 hover:bg-gray-50">
            <Upload size={20} /> Importar Planilha
          </button>
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
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Faro</th>
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

      {showImportModal && (
        <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
          <div className="bg-white rounded-xl p-6 max-w-md w-full">
            <h2 className="text-xl font-bold mb-4 text-brand-dark">Importar Planilha (CSV)</h2>
            <div className="text-sm text-gray-600 mb-6 bg-blue-50 p-4 rounded-lg border border-blue-100">
              <p className="mb-2 font-semibold">Instruções:</p>
              <ul className="list-disc pl-5 space-y-1 mb-4">
                <li>Salve sua planilha no formato <strong>.CSV</strong></li>
                <li>A primeira linha deve conter os cabeçalhos.</li>
                <li>As colunas devem estar <strong>exatamente</strong> nesta ordem:</li>
              </ul>
              <div className="bg-white p-2 text-xs font-mono rounded border border-gray-200 overflow-x-auto whitespace-nowrap">
                Nome, Código, Preço, Estoque Atual, Estoque Mínimo
              </div>
              <p className="mt-4 text-xs text-blue-800">
                Dica: Se o sistema encontrar um Código já existente, ele apenas atualizará o estoque e preço.
              </p>
            </div>

            <form onSubmit={handleImportSubmit} className="space-y-4">
              <input
                type="file"
                accept=".csv"
                required
                onChange={e => setImportFile(e.target.files[0])}
                className="w-full px-3 py-2 border rounded focus:ring-brand-orange bg-gray-50 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-brand-orange file:text-white hover:file:bg-orange-600 cursor-pointer"
              />
              <div className="flex justify-end gap-2 mt-6">
                <button type="button" onClick={() => setShowImportModal(false)} className="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded transition" disabled={importing}>Cancelar</button>
                <button type="submit" className="px-4 py-2 bg-brand-dark text-white rounded hover:bg-gray-800 transition flex items-center gap-2" disabled={importing}>
                  {importing ? 'Importando...' : 'Enviar Planilha'}
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </Layout>
  );
};

export default Products;
