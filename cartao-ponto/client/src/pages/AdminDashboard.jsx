import { useState, useEffect } from 'react';
import axios from 'axios';
import { Plus, Copy, ShieldCheck, Check, Key, Settings, Save } from 'lucide-react';
import { format, parseISO } from 'date-fns';
import ptBR from 'date-fns/locale/pt-BR';

export default function AdminDashboard({ user }) {
  const [invites, setInvites] = useState([]);
  const [settings, setSettings] = useState({ mp_access_token: '' });
  const [loading, setLoading] = useState(true);
  const [generating, setGenerating] = useState(false);
  const [savingSettings, setSavingSettings] = useState(false);
  const [settingsMessage, setSettingsMessage] = useState({ type: '', text: '' });
  const [copiedCode, setCopiedCode] = useState(null);

  const loadData = async () => {
    try {
      const [invitesRes, settingsRes] = await Promise.all([
        axios.get('/admin.php?action=invites'),
        axios.get('/admin_settings.php')
      ]);
      setInvites(invitesRes.data.invites || []);
      if (settingsRes.data.settings) {
        setSettings(settingsRes.data.settings);
      }
    } catch (err) {
      console.error(err);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    if (user?.is_admin === 1) {
      loadData();
    }
  }, [user]);

  const saveSettings = async (e) => {
    e.preventDefault();
    setSavingSettings(true);
    setSettingsMessage({ type: '', text: '' });
    try {
      const res = await axios.post('/admin_settings.php', settings);
      setSettingsMessage({ type: 'success', text: res.data.message || 'Configurações salvas!' });
      setTimeout(() => setSettingsMessage({ type: '', text: '' }), 3000);
    } catch (err) {
      setSettingsMessage({ type: 'error', text: err.response?.data?.error || 'Erro ao salvar configurações' });
    } finally {
      setSavingSettings(false);
    }
  };

  const generateInvite = async () => {
    if (generating) return;
    setGenerating(true);
    try {
      await axios.post('/admin.php?action=generate_invite');
      const invitesRes = await axios.get('/admin.php?action=invites');
      setInvites(invitesRes.data.invites || []);
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
      <div className="mb-8 border-b border-gray-200 pb-4">
        <h1 className="text-2xl font-bold text-gray-800 flex items-center">
          <ShieldCheck className="h-7 w-7 text-purple-600 mr-2" />
          Painel Administrativo
        </h1>
        <p className="text-gray-500 mt-1">Gerencie pagamentos e acessos do sistema.</p>
      </div>

      <div className="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-8">
        <div className="p-6 border-b border-gray-100 bg-gray-50 flex items-center">
          <Settings className="h-5 w-5 text-gray-500 mr-2" />
          <h2 className="text-lg font-bold text-gray-800">Configurações de Pagamento (Mercado Pago)</h2>
        </div>

        <form onSubmit={saveSettings} className="p-6 space-y-4">
          {settingsMessage.text && (
            <div className={`p-4 rounded-lg border ${settingsMessage.type === 'success' ? 'bg-green-50 border-green-200 text-green-700' : 'bg-red-50 border-red-200 text-red-700'}`}>
              {settingsMessage.text}
            </div>
          )}

          <div>
            <label className="block text-gray-700 text-sm font-bold mb-2">Access Token (Produção)</label>
            <input
              type="text"
              name="mp_access_token"
              className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 font-mono text-sm"
              placeholder="APP_USR-..."
              value={settings.mp_access_token || ''}
              onChange={(e) => setSettings({ ...settings, mp_access_token: e.target.value })}
            />
            <p className="text-xs text-gray-500 mt-2">Usado para gerar os links de pagamento dos planos na tela de assinatura.</p>
          </div>

          <div className="flex justify-end pt-2">
            <button
              type="submit"
              disabled={savingSettings}
              className="flex items-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg focus:outline-none shadow-sm transition-colors disabled:opacity-50"
            >
              {savingSettings ? 'Salvando...' : <><Save className="mr-2 h-4 w-4" /> Salvar Configurações</>}
            </button>
          </div>
        </form>
      </div>

      <div className="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div className="p-6 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
          <div className="flex items-center">
            <Key className="h-5 w-5 text-gray-500 mr-2" />
            <h2 className="text-lg font-bold text-gray-800">Códigos de Convite Gerados</h2>
          </div>
          <button
            onClick={generateInvite}
            disabled={generating}
            className="flex items-center justify-center bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 text-sm rounded-lg font-medium shadow-sm transition-colors disabled:opacity-50"
          >
            <Plus className="h-4 w-4 mr-1" /> {generating ? 'Gerando...' : 'Novo Convite'}
          </button>
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
