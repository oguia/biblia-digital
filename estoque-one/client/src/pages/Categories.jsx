import React, { useEffect, useState } from 'react';
import Layout from '../components/Layout';
import api from '../api';

const Categories = ({ user }) => {
  const [categories, setCategories] = useState([]);
  const [suppliers, setSuppliers] = useState([]);
  const [catName, setCatName] = useState('');
  const [supName, setSupName] = useState('');

  const loadData = () => {
    api.get('categories&action=list').then(res => setCategories(res.data));
    api.get('suppliers&action=list').then(res => setSuppliers(res.data));
  };

  useEffect(() => { loadData(); }, []);

  const addCategory = async (e) => {
    e.preventDefault();
    await api.post('categories&action=create', { name: catName });
    setCatName('');
    loadData();
  };

  const addSupplier = async (e) => {
    e.preventDefault();
    await api.post('suppliers&action=create', { name: supName });
    setSupName('');
    loadData();
  };

  return (
    <Layout user={user}>
      <h1 className="text-2xl font-bold text-brand-dark mb-6">Categorias & Fornecedores</h1>

      <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div className="bg-white rounded-xl shadow-sm p-6">
          <h2 className="text-lg font-bold mb-4">Categorias</h2>
          <form onSubmit={addCategory} className="flex gap-2 mb-4">
            <input type="text" required placeholder="Nova Categoria" className="flex-1 px-3 py-2 border rounded focus:ring-brand-orange" value={catName} onChange={e => setCatName(e.target.value)} />
            <button type="submit" className="bg-brand-orange text-white px-4 rounded hover:bg-orange-600">Add</button>
          </form>
          <ul className="divide-y border rounded">
            {categories.map(c => <li key={c.id} className="p-3 text-gray-700">{c.name}</li>)}
            {categories.length === 0 && <li className="p-3 text-gray-400 text-sm">Nenhuma categoria cadastrada.</li>}
          </ul>
        </div>

        <div className="bg-white rounded-xl shadow-sm p-6">
          <h2 className="text-lg font-bold mb-4">Fornecedores</h2>
          <form onSubmit={addSupplier} className="flex gap-2 mb-4">
            <input type="text" required placeholder="Novo Fornecedor" className="flex-1 px-3 py-2 border rounded focus:ring-brand-orange" value={supName} onChange={e => setSupName(e.target.value)} />
            <button type="submit" className="bg-brand-orange text-white px-4 rounded hover:bg-orange-600">Add</button>
          </form>
          <ul className="divide-y border rounded">
            {suppliers.map(s => <li key={s.id} className="p-3 text-gray-700">{s.name}</li>)}
            {suppliers.length === 0 && <li className="p-3 text-gray-400 text-sm">Nenhum fornecedor cadastrado.</li>}
          </ul>
        </div>
      </div>
    </Layout>
  );
};

export default Categories;
