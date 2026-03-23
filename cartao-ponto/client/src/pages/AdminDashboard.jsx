import { useState, useEffect } from 'react';
import axios from 'axios';
import { Plus, Copy, ShieldCheck, Check, Key } from 'lucide-react';
import { format, parseISO } from 'date-fns';
import ptBR from 'date-fns/locale/pt-BR';

export default function AdminDashboard({ user }) {
  const [invites, setInvites] = useState([]);
  const [loading, setLoading] = useState(true);
  const [generating, setGenerating] = useState(false);
  const [copiedCode, setCopiedCode] = useState(null);

  const loadInvites = async () => {
    try {
      const res = await axios.get('/admin.php?action=invites');
      setInvites(res.data.invites || []);
    } catch (err) {
      console.error(err);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    if (user?.is_admin === 1) {
      loadInvites();
    }
  }, [user]);

  const generateInvite = async () => {
    if (generating) return;
    setGenerating(true);
    try {
      await axios.post('/admin.php?action=generate_invite');
      await loadInvites();
    } catch (err) {
      alert(err.response?.data?.error || 'Erro ao gerar convite');
    } finally {
      setGenerating(false);
    }
  };

  const copyToClipboard = (code) => {
    navigator.clipboard.writeText(code);
    setCopiedCode(code);
    setTimeout(() => setCopiedCode(null), 2000);
  };

  if (!user || user.is_admin !== 1) {
    return (
      <div className="p-8 text-center text-red-600 bg-red-50 rounded-xl border border-red-200">
        <ShieldCheck className="h-12 w-12 mx-auto mb-4" />
        <h2 className="text-xl font-bold">Acesso Negado</h2>
        <p>Você não tem permissão para acessar esta área.</p>
      </div>
    );
  }

  if (loading) return <div className="p-8 text-center text-gray-500">Carregando painel admin...</div>;

  return (
    <div className="max-w-5xl mx-auto space-y-6">
      <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
          <h1 className="text-2xl font-bold text-gray-800 flex items-center">
            <ShieldCheck className="h-7 w-7 text-purple-600 mr-2" />
            Painel Administrativo
          </h1>
          <p className="text-gray-500 text-sm mt-1">Gerencie os códigos de convite gratuitos do sistema.</p>
        </div>

        <button
          onClick={generateInvite}
          disabled={generating}
          className="flex items-center justify-center bg-purple-600 hover:bg-purple-700 text-white px-5 py-2.5 rounded-lg font-medium shadow-sm transition-colors disabled:opacity-50"
        >
          <Plus className="h-5 w-5 mr-1" /> {generating ? 'Gerando...' : 'Gerar Novo Convite'}
        </button>
      </div>

      <div className="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div className="p-6 border-b border-gray-100 bg-gray-50 flex items-center">
          <Key className="h-5 w-5 text-gray-500 mr-2" />
          <h2 className="text-lg font-bold text-gray-800">Códigos de Convite Gerados</h2>
        </div>

        {invites.length === 0 ? (
          <div className="p-12 text-center text-gray-500">
            Nenhum convite gerado ainda. Clique no botão acima para criar o primeiro.
          </div>
        ) : (
          <div className="overflow-x-auto">
            <table className="w-full text-left border-collapse">
              <thead>
                <tr className="bg-gray-50 text-xs uppercase text-gray-500 font-semibold tracking-wider">
                  <th className="p-4 border-b">Código</th>
                  <th className="p-4 border-b">Data de Criação</th>
                  <th className="p-4 border-b">Status</th>
                  <th className="p-4 border-b">Usado Por</th>
                  <th className="p-4 border-b text-right">Ação</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-gray-100 text-sm">
                {invites.map(invite => {
                  const isUsed = invite.used_by !== null;
                  return (
                    <tr key={invite.id} className="hover:bg-gray-50 transition-colors">
                      <td className="p-4">
                        <span className={`font-mono font-bold px-3 py-1.5 rounded-lg border ${isUsed ? 'bg-gray-100 border-gray-200 text-gray-500 line-through' : 'bg-green-50 border-green-200 text-green-700 text-base'}`}>
                          {invite.code}
                        </span>
                      </td>
                      <td className="p-4 text-gray-600">
                        {format(parseISO(invite.created_at), 'dd/MM/yyyy HH:mm')}
                      </td>
                      <td className="p-4">
                        {isUsed ? (
                          <span className="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            Usado
                          </span>
                        ) : (
                          <span className="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Disponível
                          </span>
                        )}
                      </td>
                      <td className="p-4 text-gray-600">
                        {isUsed ? (
                          <div>
                            <p className="font-semibold text-gray-800">{invite.used_by_name}</p>
                            <p className="text-xs">{invite.used_by_email}</p>
                            <p className="text-xs text-gray-400 mt-1">em {format(parseISO(invite.used_at), 'dd/MM/yyyy HH:mm')}</p>
                          </div>
                        ) : (
                          <span className="text-gray-400">-</span>
                        )}
                      </td>
                      <td className="p-4 text-right">
                        {!isUsed && (
                          <button
                            onClick={() => copyToClipboard(invite.code)}
                            className="inline-flex items-center p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors focus:outline-none"
                            title="Copiar Código"
                          >
                            {copiedCode === invite.code ? <Check className="h-5 w-5 text-green-600" /> : <Copy className="h-5 w-5" />}
                          </button>
                        )}
                      </td>
                    </tr>
                  );
                })}
              </tbody>
            </table>
          </div>
        )}
      </div>
    </div>
  );
}
