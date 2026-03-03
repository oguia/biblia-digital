import { useState } from 'react';
import axios from 'axios';
import { Store, Upload, CheckCircle2, AlertCircle, ArrowLeft } from 'lucide-react';
import { Link } from 'react-router-dom';

export default function PainelLojista() {
  const [lojistaId, setLojistaId] = useState<number | null>(null);
  const [nomeLojista, setNomeLojista] = useState('');

  // Login State
  const [email, setEmail] = useState('');
  const [senha, setSenha] = useState('');

  // Cadastro Oferta State
  const [titulo, setTitulo] = useState('');
  const [preco, setPreco] = useState('');
  const [categoria, setCategoria] = useState('Supermercado');
  const [imagem, setImagem] = useState<File | null>(null); // Placeholder for image upload

  const [mensagem, setMensagem] = useState({ tipo: '', texto: '' });
  const [loading, setLoading] = useState(false);

  const baseURL = import.meta.env.DEV ? 'http://localhost:8000/api' : '/api';

  const handleLogin = async (e: React.FormEvent) => {
    e.preventDefault();
    setLoading(true);
    try {
      const res = await axios.post(`${baseURL}/lojista.php`, {
        action: 'login',
        email,
        senha
      });
      if (res.data.success) {
        setLojistaId(res.data.id_lojista);
        setNomeLojista(res.data.nome_fantasia);
        setMensagem({ tipo: '', texto: '' });
      }
    } catch (error: any) {
      setMensagem({ tipo: 'erro', texto: error.response?.data?.error || 'Erro ao fazer login' });
    } finally {
      setLoading(false);
    }
  };

  const handleCadastrarOferta = async (e: React.FormEvent) => {
    e.preventDefault();
    setLoading(true);
    setMensagem({ tipo: '', texto: '' });

    try {
      const res = await axios.post(`${baseURL}/ofertas.php`, {
        titulo,
        preco,
        loja: nomeLojista,
        categoria,
        id_lojista: lojistaId,
        imagem_url: null // TODO: Implementar upload de imagem e enviar URL
      });

      if (res.data.success) {
        setMensagem({ tipo: 'sucesso', texto: 'Oferta enviada! Aguardando pagamento para ser publicada.' });
        setTitulo('');
        setPreco('');
      }
    } catch (error: any) {
      setMensagem({ tipo: 'erro', texto: 'Erro ao cadastrar oferta.' });
    } finally {
      setLoading(false);
    }
  };

  if (!lojistaId) {
    return (
      <div className="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div className="sm:mx-auto sm:w-full sm:max-w-md text-center">
          <Link to="/" className="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-orange-600 mb-6 transition-colors">
            <ArrowLeft size={16} /> Voltar para o início
          </Link>
          <Store className="mx-auto h-12 w-12 text-orange-600" />
          <h2 className="mt-6 text-center text-3xl font-extrabold text-gray-900">
            Acesso do Lojista
          </h2>
          <p className="mt-2 text-center text-sm text-gray-600">
            Anuncie suas ofertas para milhares de clientes na região
          </p>
        </div>

        <div className="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
          <div className="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10 border border-gray-100">
            {mensagem.texto && (
              <div className="mb-4 bg-red-50 p-4 rounded-md flex items-center gap-3 text-red-700 text-sm">
                <AlertCircle size={20} />
                {mensagem.texto}
              </div>
            )}
            <form className="space-y-6" onSubmit={handleLogin}>
              <div>
                <label className="block text-sm font-medium text-gray-700">Email da Loja</label>
                <div className="mt-1">
                  <input
                    type="email"
                    required
                    value={email}
                    onChange={e => setEmail(e.target.value)}
                    className="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm"
                  />
                </div>
              </div>

              <div>
                <label className="block text-sm font-medium text-gray-700">Senha</label>
                <div className="mt-1">
                  <input
                    type="password"
                    required
                    value={senha}
                    onChange={e => setSenha(e.target.value)}
                    className="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm"
                  />
                </div>
              </div>

              <div>
                <button
                  type="submit"
                  disabled={loading}
                  className="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 disabled:opacity-50"
                >
                  {loading ? 'Entrando...' : 'Entrar'}
                </button>
              </div>
            </form>

            <div className="mt-6 text-center">
              <p className="text-sm text-gray-500">Para cadastro de novas lojas, entre em contato com o suporte.</p>
            </div>
          </div>
        </div>
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-gray-50 pb-12">
      <header className="bg-white shadow">
        <div className="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
          <h1 className="text-2xl font-bold text-gray-900 flex items-center gap-2">
            <Store className="text-orange-600" /> Painel da Loja: {nomeLojista}
          </h1>
          <button
            onClick={() => setLojistaId(null)}
            className="text-sm text-gray-500 hover:text-gray-900"
          >
            Sair
          </button>
        </div>
      </header>

      <main className="max-w-4xl mx-auto mt-8 px-4 sm:px-6 lg:px-8">
        <div className="bg-white shadow overflow-hidden sm:rounded-lg border border-gray-200">
          <div className="px-4 py-5 sm:px-6 bg-gray-50 border-b border-gray-200">
            <h3 className="text-lg leading-6 font-medium text-gray-900">Nova Oferta Patrocinada</h3>
            <p className="mt-1 max-w-2xl text-sm text-gray-500">
              Preencha os dados abaixo para criar um novo anúncio no feed principal.
            </p>
          </div>

          <div className="p-6">
            {mensagem.texto && (
              <div className={`mb-6 p-4 rounded-md flex items-center gap-3 text-sm ${mensagem.tipo === 'sucesso' ? 'bg-green-50 text-green-800' : 'bg-red-50 text-red-800'}`}>
                {mensagem.tipo === 'sucesso' ? <CheckCircle2 size={20} /> : <AlertCircle size={20} />}
                {mensagem.texto}
              </div>
            )}

            <form onSubmit={handleCadastrarOferta} className="space-y-6">
              <div className="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">

                <div className="sm:col-span-4">
                  <label className="block text-sm font-medium text-gray-700">Título do Produto</label>
                  <div className="mt-1">
                    <input
                      type="text"
                      required
                      value={titulo}
                      onChange={e => setTitulo(e.target.value)}
                      placeholder="Ex: Picanha Bovina a Vácuo 1kg"
                      className="shadow-sm focus:ring-orange-500 focus:border-orange-500 block w-full sm:text-sm border-gray-300 rounded-md p-2 border"
                    />
                  </div>
                </div>

                <div className="sm:col-span-2">
                  <label className="block text-sm font-medium text-gray-700">Preço (R$)</label>
                  <div className="mt-1 relative rounded-md shadow-sm">
                    <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                      <span className="text-gray-500 sm:text-sm">R$</span>
                    </div>
                    <input
                      type="number"
                      step="0.01"
                      required
                      value={preco}
                      onChange={e => setPreco(e.target.value)}
                      className="focus:ring-orange-500 focus:border-orange-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md p-2 border"
                      placeholder="0.00"
                    />
                  </div>
                </div>

                <div className="sm:col-span-3">
                  <label className="block text-sm font-medium text-gray-700">Categoria</label>
                  <div className="mt-1">
                    <select
                      value={categoria}
                      onChange={e => setCategoria(e.target.value)}
                      className="shadow-sm focus:ring-orange-500 focus:border-orange-500 block w-full sm:text-sm border-gray-300 rounded-md p-2 border"
                    >
                      <option value="Supermercado">Supermercado</option>
                      <option value="Construção">Construção</option>
                    </select>
                  </div>
                </div>

                <div className="sm:col-span-6">
                  <label className="block text-sm font-medium text-gray-700">Foto da Oferta (Encarte)</label>
                  <div className="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md bg-gray-50 hover:bg-gray-100 transition-colors cursor-pointer">
                    <div className="space-y-1 text-center">
                      <Upload className="mx-auto h-12 w-12 text-gray-400" />
                      <div className="flex text-sm text-gray-600 justify-center">
                        <label className="relative cursor-pointer bg-white rounded-md font-medium text-orange-600 hover:text-orange-500 focus-within:outline-none px-2 py-1">
                          <span>Fazer Upload</span>
                          <input type="file" className="sr-only" accept="image/*" onChange={(e) => setImagem(e.target.files?.[0] || null)} />
                        </label>
                      </div>
                      <p className="text-xs text-gray-500">
                        {imagem ? imagem.name : 'PNG, JPG, GIF até 5MB'}
                      </p>
                    </div>
                  </div>
                </div>
              </div>

              <div className="pt-5 border-t border-gray-200 flex justify-end gap-3 items-center">
                <span className="text-sm text-gray-500">Custo do Anúncio: <strong className="text-gray-900">R$ 5,00 / dia</strong></span>
                <button
                  type="submit"
                  disabled={loading}
                  className="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 disabled:opacity-50"
                >
                  {loading ? 'Processando...' : 'Ir para Pagamento'}
                </button>
              </div>
            </form>
          </div>
        </div>
      </main>
    </div>
  );
}
