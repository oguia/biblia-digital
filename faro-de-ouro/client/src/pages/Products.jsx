import React, { useEffect, useState } from 'react';
import Layout from '../components/Layout';
import api from '../api';
import { Plus, Trash2, Camera, Download, Upload, Edit3, Filter } from 'lucide-react';
import BarcodeScanner from '../components/BarcodeScanner';
import { exportToCSV, exportToWord } from '../utils/export';

const Products = ({ user }) => {
  const [products, setProducts] = useState([]);
  const [categories, setCategories] = useState([]);
  const [suppliers, setSuppliers] = useState([]);

  const [showModal, setShowModal] = useState(false);
  const [showScanner, setShowScanner] = useState(false);
  const [showImportModal, setShowImportModal] = useState(false);
  const [importFile, setImportFile] = useState(null);
  const [importing, setImporting] = useState(false);

  const [formData, setFormData] = useState({ id: '', code: '', name: '', category_id: '', supplier_id: '', price: '', min_stock: '', current_stock: '', unit: '', location: '', observation: '' });
  const [editMode, setEditMode] = useState(false);
  const [search, setSearch] = useState('');
  const [selectedCategory, setSelectedCategory] = useState('');

  const loadData = () => {
    api.get('products&action=list').then(res => setProducts(res.data));
    api.get('categories&action=list').then(res => setCategories(res.data));
    api.get('suppliers&action=list').then(res => setSuppliers(res.data));
  };

  useEffect(() => { loadData(); }, []);

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (editMode) {
      await api.put('products&action=update', formData);
      // Se alterou estoque atual durante a edição, vamos lançar como ajuste de estoque:
      if (formData.original_stock !== formData.current_stock) {
        await api.post('products&action=movement', {
          product_id: formData.id,
          type: 'adjustment',
          quantity: formData.current_stock,
          reason: 'Ajuste manual pela tela de Produtos'
        });
      }
    } else {
      await api.post('products&action=create', formData);
    }
    setShowModal(false);
    setEditMode(false);
    loadData();
    setFormData({ id: '', code: '', name: '', category_id: '', supplier_id: '', price: '', min_stock: '', current_stock: '', unit: '', location: '', observation: '' });
  };

  const handleEdit = (product) => {
    setFormData({
      id: product.id,
      code: product.code || '',
      name: product.name,
      category_id: product.category_id || '',
      supplier_id: product.supplier_id || '',
      price: product.price,
      min_stock: product.min_stock,
      current_stock: product.current_stock,
      original_stock: product.current_stock,
      unit: product.unit || '',
      location: product.location || '',
      observation: product.observation || ''
    });
    setEditMode(true);
    setShowModal(true);
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

  const filteredProducts = products.filter(p => {
    const matchSearch = p.name.toLowerCase().includes(search.toLowerCase()) ||
                        (p.code && p.code.toLowerCase().includes(search.toLowerCase()));
    const matchCategory = selectedCategory === '' || p.category_id == selectedCategory;
    return matchSearch && matchCategory;
  });

  const getExportData = () => {
    return filteredProducts.map(p => ({
      ID: p.id,
      Código: p.code,
      Nome: p.name,
      Categoria: p.category_name,
      Fornecedor: p.supplier_name,
      Preço: p.price,
      Faro_Atual: p.current_stock,
      Unidade: p.unit,
      Faro_Min: p.min_stock,
      Localização: p.location,
      Observação: p.observation
    }));
  };

  const handleExportCSV = () => {
    exportToCSV('produtos.csv', getExportData());
  };

  const handleExportWord = () => {
    exportToWord('produtos.doc', getExportData());
  };

  const handleDeleteAll = async () => {
    if(window.confirm('TEM CERTEZA ABSOLUTA? Isso irá apagar TODOS os produtos e todo o histórico de movimentações (Kardex). Esta ação não pode ser desfeita.')) {
      if(window.confirm('Confirme novamente: Deseja realmente APAGAR TUDO?')) {
        try {
          await api.delete('products&action=delete_all');
          loadData();
          alert('Todos os produtos foram apagados com sucesso.');
        } catch (error) {
          console.error("Erro ao apagar tudo:", error);
          alert('Ocorreu um erro ao apagar. Verifique o console.');
        }
      }
    }
  };

  const handleQuickStockEdit = async (product) => {
    const newStock = window.prompt(`Ajuste rápido do Faro (Estoque) do item:\n${product.name}\n\nQuantidade Atual: ${product.current_stock}\nDigite o novo estoque total:`, product.current_stock);

    if (newStock !== null && newStock.trim() !== '') {
      const parsedStock = parseInt(newStock, 10);
      if (!isNaN(parsedStock) && parsedStock !== product.current_stock) {
        try {
          await api.post('products&action=movement', {
            product_id: product.id,
            type: 'adjustment',
            quantity: parsedStock,
            reason: 'Ajuste rápido (Tabela)'
          });
          loadData();
        } catch (err) {
          alert('Erro ao atualizar o estoque: ' + (err.response?.data?.error || err.message));
        }
      }
    }
  };

  const handleQuickPriceEdit = async (product) => {
    const newPrice = window.prompt(`Ajuste rápido do Preço do item:\n${product.name}\n\nPreço Atual: R$ ${Number(product.price).toFixed(2)}\nDigite o novo preço:`, product.price);

    if (newPrice !== null && newPrice.trim() !== '') {
      let parsedPrice = parseFloat(newPrice.replace(',', '.'));
      if (!isNaN(parsedPrice) && parsedPrice !== parseFloat(product.price)) {
        try {
          await api.put('products&action=update', {
            ...product,
            price: parsedPrice
          });
          loadData();
        } catch (err) {
          alert('Erro ao atualizar o preço: ' + (err.response?.data?.error || err.message));
        }
      }
    }
  };

  const totalProducts = filteredProducts.length;
  const totalStock = filteredProducts.reduce((acc, p) => acc + (parseInt(p.current_stock) || 0), 0);
  const totalValue = filteredProducts.reduce((acc, p) => acc + ((parseInt(p.current_stock) || 0) * (parseFloat(p.price) || 0)), 0);

  return (
    <Layout user={user}>
      <div className="flex justify-between items-center mb-6 flex-wrap gap-4">
        <h1 className="text-2xl font-bold text-brand-dark">Produtos</h1>
        <div className="flex gap-2 flex-wrap">
          <button onClick={() => setShowImportModal(true)} className="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg flex items-center gap-2 hover:bg-gray-50">
            <Upload size={20} /> Importar Planilha
          </button>

          <div className="relative group">
            <button className="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg flex items-center gap-2 hover:bg-gray-300 transition">
              <Download size={20} /> Exportar
            </button>
            <div className="absolute right-0 mt-2 w-48 bg-white border rounded shadow-lg hidden group-hover:block z-10">
              <button onClick={handleExportCSV} className="block w-full text-left px-4 py-2 hover:bg-gray-100 text-sm">Excel (CSV)</button>
              <button onClick={handleExportWord} className="block w-full text-left px-4 py-2 hover:bg-gray-100 text-sm">Word (.doc)</button>
            </div>
          </div>

          <button onClick={handleDeleteAll} className="bg-red-100 text-red-700 px-4 py-2 rounded-lg flex items-center gap-2 hover:bg-red-200 transition" title="Apagar todos os produtos e histórico">
            <Trash2 size={20} /> Limpar Tudo
          </button>
          <button onClick={() => { setEditMode(false); setFormData({ id: '', code: '', name: '', category_id: '', supplier_id: '', price: '', min_stock: '', current_stock: '', unit: '', location: '', observation: '' }); setShowModal(true); }} className="bg-brand-orange text-white px-4 py-2 rounded-lg flex items-center gap-2 hover:bg-orange-600 transition">
            <Plus size={20} /> Novo
          </button>
        </div>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div className="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
          <div>
            <p className="text-sm text-gray-500 font-medium">Cadastros</p>
            <p className="text-2xl font-bold text-brand-dark">{totalProducts}</p>
          </div>
          <div className="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-600">🏷️</div>
        </div>
        <div className="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
          <div>
            <p className="text-sm text-gray-500 font-medium">Faro Físico Total</p>
            <p className="text-2xl font-bold text-brand-dark">{totalStock} un.</p>
          </div>
          <div className="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center text-green-600">📦</div>
        </div>
        <div className="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
          <div>
            <p className="text-sm text-gray-500 font-medium">Valor Total Estimado</p>
            <p className="text-2xl font-bold text-brand-dark">R$ {totalValue.toFixed(2)}</p>
          </div>
          <div className="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center text-brand-orange">💰</div>
        </div>
      </div>

      <div className="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-4">
        <div className="p-4 border-b bg-gray-50 flex flex-col md:flex-row gap-4 items-center">
          <div className="flex-1 w-full relative">
            <input
              type="text"
              placeholder="Buscar por nome ou código..."
              className="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-brand-orange"
              value={search}
              onChange={(e) => setSearch(e.target.value)}
            />
            <Filter className="absolute left-3 top-2.5 text-gray-400" size={20} />
          </div>
          <div className="w-full md:w-64 flex items-center gap-2">
            <Filter size={18} className="text-gray-400" />
            <select
              className="w-full px-3 py-2 border rounded-lg focus:ring-brand-orange text-sm bg-white"
              value={selectedCategory}
              onChange={(e) => setSelectedCategory(e.target.value)}
            >
              <option value="">Todas as Categorias</option>
              {categories.map(c => <option key={c.id} value={c.id}>{c.name}</option>)}
            </select>
          </div>
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
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Localização</th>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Preço</th>
              <th className="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Ações</th>
            </tr>
          </thead>
          <tbody className="bg-white divide-y divide-gray-200">
            {filteredProducts.map(p => (
              <tr key={p.id}>
                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{p.code || '-'}</td>
                <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 max-w-xs truncate" title={p.name}>{p.name}</td>
                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500 max-w-[150px] truncate" title={p.category_name}>{p.category_name || '-'}</td>
                <td className="px-6 py-4 whitespace-nowrap text-sm">
                  <button onClick={() => handleQuickStockEdit(p)} className="hover:bg-gray-100 p-1 rounded transition group flex items-center gap-1" title="Clique para ajustar o Faro (Estoque)">
                    <span className={`px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${p.current_stock <= p.min_stock ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'}`}>
                      {p.current_stock} {p.unit && <span className="ml-1 text-gray-600 opacity-80">{p.unit}</span>}
                    </span>
                    <Edit3 size={12} className="text-gray-400 opacity-0 group-hover:opacity-100" />
                  </button>
                </td>
                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500 max-w-[120px] truncate" title={p.location}>{p.location || '-'}</td>
                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  <button onClick={() => handleQuickPriceEdit(p)} className="hover:bg-gray-100 p-1 rounded transition group flex items-center gap-1" title="Clique para ajustar o Preço">
                    R$ {Number(p.price).toFixed(2)}
                    <Edit3 size={12} className="text-gray-400 opacity-0 group-hover:opacity-100" />
                  </button>
                </td>
                <td className="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <button onClick={() => handleEdit(p)} className="text-blue-600 hover:text-blue-900 ml-4"><Edit3 size={18} /></button>
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
            <h2 className="text-xl font-bold mb-4">{editMode ? 'Editar Produto / Ajustar Faro' : 'Novo Produto'}</h2>
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

              <div className="grid grid-cols-3 gap-4">
                <input type="number" step="0.01" placeholder="Preço (R$)" className="w-full px-3 py-2 border rounded focus:ring-brand-orange" value={formData.price} onChange={e => setFormData({...formData, price: e.target.value})} />
                <div>
                  <label className="text-xs text-gray-500 mb-1 block">{editMode ? 'Faro (Estoque Atual)' : 'Estoque Inicial'}</label>
                  <input type="number" placeholder="Estoque Atual" required className="w-full px-3 py-2 border rounded focus:ring-brand-orange" value={formData.current_stock} onChange={e => setFormData({...formData, current_stock: e.target.value})} />
                </div>
                <div>
                  <label className="text-xs text-gray-500 mb-1 block">Unidade de Medida</label>
                  <input type="text" placeholder="Ex: un, kg, cx" className="w-full px-3 py-2 border rounded focus:ring-brand-orange" value={formData.unit} onChange={e => setFormData({...formData, unit: e.target.value})} />
                </div>
              </div>

              <div className="grid grid-cols-2 gap-4">
                <div>
                  <label className="text-xs text-gray-500 mb-1 block">Estoque Mínimo (Alerta)</label>
                  <input type="number" placeholder="Estoque Mínimo" required className="w-full px-3 py-2 border rounded focus:ring-brand-orange" value={formData.min_stock} onChange={e => setFormData({...formData, min_stock: e.target.value})} />
                </div>
                <div>
                  <label className="text-xs text-gray-500 mb-1 block">Localização / Endereço</label>
                  <input type="text" placeholder="Ex: Bloco A, Estante 3" className="w-full px-3 py-2 border rounded focus:ring-brand-orange" value={formData.location} onChange={e => setFormData({...formData, location: e.target.value})} />
                </div>
              </div>

              <div>
                <label className="text-xs text-gray-500 mb-1 block">Observação</label>
                <textarea placeholder="Observações adicionais..." className="w-full px-3 py-2 border rounded focus:ring-brand-orange" value={formData.observation} onChange={e => setFormData({...formData, observation: e.target.value})}></textarea>
              </div>
              <div className="flex justify-end gap-2 mt-6">
                <button type="button" onClick={() => {setShowModal(false); setEditMode(false);}} className="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded">Cancelar</button>
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
              <p className="mb-2 font-semibold">Formatos Aceitos (.CSV):</p>
              <ul className="list-disc pl-5 space-y-1 mb-4">
                <li><strong>Planilha Faro:</strong> Nome, Código, Preço, Estoque Atual, Estoque Mín.</li>
                <li><strong>Planilha GPC-ARA:</strong> O sistema detecta e importa a sua planilha original de Controle de Estoque Oficial automaticamente, usando a SEQUÊNCIA ou CÓDIGO CONTÁBIL.</li>
              </ul>
              <p className="mt-4 text-xs text-blue-800">
                Dica: Se o sistema encontrar um Código já existente, ele apenas atualizará o estoque e preço. O estoque atual de novos produtos do GPC iniciará zerado para conferência.
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
