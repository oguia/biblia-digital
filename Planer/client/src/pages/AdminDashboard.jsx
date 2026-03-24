import React, { useState } from 'react';
import { useEffect } from 'react';
import useSWR from 'swr';
import { fetchWithAuth } from '../lib/api';
import { Download, Plus, Upload, Users, BookOpen, CheckCircle, Clock, Settings, Save } from 'lucide-react';

export default function AdminDashboard() {
  const [csvFile, setCsvFile] = useState(null);
  const [uploading, setUploading] = useState(false);
  const [message, setMessage] = useState('');

  // Settings state
  const [settingsForm, setSettingsForm] = useState({ mp_access_token: '', site_url: '' });
  const [settingsLoading, setSettingsLoading] = useState(false);
  const [settingsMessage, setSettingsMessage] = useState({ type: '', text: '' });

  const { data: stats } = useSWR('/admin.php?action=stats', fetchWithAuth);
  const { data: invites, mutate: mutateInvites } = useSWR('/admin.php?action=list_invites', fetchWithAuth);
  const { data: settingsData, mutate: mutateSettings } = useSWR('/admin.php?action=get_settings', fetchWithAuth);

  useEffect(() => {
    if (settingsData?.settings) {
      setSettingsForm({
        mp_access_token: settingsData.settings.mp_access_token || '',
        site_url: settingsData.settings.site_url || ''
      });
    }
  }, [settingsData]);

  const handleSaveSettings = async (e) => {
    e.preventDefault();
    setSettingsLoading(true);
    setSettingsMessage({ type: '', text: '' });

    try {
      const res = await fetchWithAuth('/admin.php?action=save_settings', {
        method: 'POST',
        body: settingsForm
      });
      setSettingsMessage({ type: 'success', text: res.message });
      mutateSettings();
    } catch (e) {
      setSettingsMessage({ type: 'error', text: e.message });
    } finally {
      setSettingsLoading(false);
    }
  };

  const handleGenerateInvite = async () => {
    try {
      await fetchWithAuth('/admin.php?action=generate_invite', { method: 'POST' });
      mutateInvites();
    } catch (e) {
      alert('Erro ao gerar convite: ' + e.message);
    }
  };

  const handleCsvUpload = async (e) => {
    e.preventDefault();
    if (!csvFile) return;

    setUploading(true);
    setMessage('');

    const formData = new FormData();
    formData.append('csv_file', csvFile);

    try {
      const res = await fetchWithAuth('/admin.php?action=upload_bncc', {
        method: 'POST',
        body: formData
      });
      setMessage(res.message);
    } catch (e) {
      setMessage('Erro: ' + e.message);
    } finally {
      setUploading(false);
      setCsvFile(null);
    }
  };

  return (
    <div className="space-y-6">
      <h2 className="text-2xl font-bold text-navy-900">Painel Administrativo</h2>

      {/* Stats */}
      <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div className="bg-white p-6 rounded-lg shadow-sm border border-slate-200 flex items-center gap-4">
          <div className="bg-blue-100 p-3 rounded-full text-blue-600">
            <Users size={24} />
          </div>
          <div>
            <p className="text-sm text-slate-500 font-medium">Total de Usuários</p>
            <p className="text-2xl font-bold text-navy-900">{stats?.total_users || 0}</p>
          </div>
        </div>
        <div className="bg-white p-6 rounded-lg shadow-sm border border-slate-200 flex items-center gap-4">
          <div className="bg-green-100 p-3 rounded-full text-green-600">
            <BookOpen size={24} />
          </div>
          <div>
            <p className="text-sm text-slate-500 font-medium">Planos de Aula</p>
            <p className="text-2xl font-bold text-navy-900">{stats?.total_lesson_plans || 0}</p>
          </div>
        </div>
        <div className="bg-white p-6 rounded-lg shadow-sm border border-slate-200 flex items-center gap-4">
          <div className="bg-purple-100 p-3 rounded-full text-purple-600">
            <CheckCircle size={24} />
          </div>
          <div>
            <p className="text-sm text-slate-500 font-medium">Habilidades BNCC</p>
            <p className="text-2xl font-bold text-navy-900">{stats?.total_bncc_skills || 0}</p>
          </div>
        </div>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
        {/* BNCC Import */}
        <div className="bg-white p-6 rounded-lg shadow-sm border border-slate-200">
          <h3 className="text-lg font-semibold text-navy-900 mb-4 flex items-center gap-2">
            <Upload size={20} className="text-highlight" />
            Importar Base BNCC
          </h3>
          <p className="text-sm text-slate-500 mb-4">
            Faça upload de um arquivo CSV contendo: Código, Componente, Ano e Descrição.
          </p>
          <form onSubmit={handleCsvUpload} className="space-y-4">
            <input
              type="file"
              accept=".csv"
              onChange={(e) => setCsvFile(e.target.files[0])}
              className="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-highlight file:text-white hover:file:bg-highlight-hover cursor-pointer"
            />
            {message && <p className="text-sm font-medium text-green-600">{message}</p>}
            <button
              type="submit"
              disabled={!csvFile || uploading}
              className="px-4 py-2 bg-navy-800 text-white rounded hover:bg-navy-900 disabled:opacity-50"
            >
              {uploading ? 'Importando...' : 'Importar CSV'}
            </button>
          </form>
        </div>

        {/* Invite Codes */}
        <div className="bg-white p-6 rounded-lg shadow-sm border border-slate-200">
          <h3 className="text-lg font-semibold text-navy-900 mb-4 flex items-center gap-2">
            <Clock size={20} className="text-highlight" />
            Códigos de Convite (Acesso Vitalício)
          </h3>
          <button
            onClick={handleGenerateInvite}
            className="mb-4 flex items-center gap-2 px-4 py-2 bg-highlight text-white rounded hover:bg-highlight-hover"
          >
            <Plus size={16} /> Gerar Novo Código
          </button>

          <div className="overflow-y-auto max-h-60 border rounded-md">
            <table className="min-w-full divide-y divide-slate-200">
              <thead className="bg-slate-50 sticky top-0">
                <tr>
                  <th className="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase">Código</th>
                  <th className="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase">Status</th>
                </tr>
              </thead>
              <tbody className="bg-white divide-y divide-slate-200">
                {invites?.codes?.map(invite => (
                  <tr key={invite.id}>
                    <td className="px-4 py-2 text-sm font-mono font-medium text-navy-900">{invite.code}</td>
                    <td className="px-4 py-2 text-sm text-slate-500">
                      {invite.used_by ? (
                        <span className="text-red-500">Usado por {invite.used_by_name}</span>
                      ) : (
                        <span className="text-green-500 font-medium">Disponível</span>
                      )}
                    </td>
                  </tr>
                ))}
                {!invites?.codes?.length && (
                  <tr>
                    <td colSpan="2" className="px-4 py-4 text-center text-sm text-slate-500">
                      Nenhum código gerado.
                    </td>
                  </tr>
                )}
              </tbody>
            </table>
          </div>
        </div>
      </div>

      {/* Settings Panel */}
      <div className="bg-white p-6 rounded-lg shadow-sm border border-slate-200 mt-6">
        <h3 className="text-lg font-semibold text-navy-900 mb-4 flex items-center gap-2">
          <Settings size={20} className="text-highlight" />
          Configurações do Sistema
        </h3>

        {settingsMessage.text && (
          <div className={`p-3 mb-4 rounded text-sm ${settingsMessage.type === 'success' ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-red-50 text-red-700 border border-red-200'}`}>
             {settingsMessage.text}
          </div>
        )}

        <form onSubmit={handleSaveSettings} className="space-y-4 max-w-3xl">
          <div>
            <label className="block text-sm font-medium text-slate-700 mb-1">Mercado Pago Access Token (Produção)</label>
            <input
              type="password"
              value={settingsForm.mp_access_token}
              onChange={(e) => setSettingsForm(prev => ({ ...prev, mp_access_token: e.target.value }))}
              placeholder="APP_USR-123456789..."
              className="w-full rounded-md border-slate-300 shadow-sm focus:border-highlight focus:ring focus:ring-highlight focus:ring-opacity-50 py-2 px-3 border text-slate-900"
              required
            />
            <p className="text-xs text-slate-500 mt-1">
              Necessário para cobrar assinaturas. Obtenha no painel de desenvolvedores do Mercado Pago.
            </p>
          </div>

          <div>
            <label className="block text-sm font-medium text-slate-700 mb-1">URL Base do Site</label>
            <input
              type="url"
              value={settingsForm.site_url}
              onChange={(e) => setSettingsForm(prev => ({ ...prev, site_url: e.target.value }))}
              placeholder="https://meusite.com.br/planer"
              className="w-full rounded-md border-slate-300 shadow-sm focus:border-highlight focus:ring focus:ring-highlight focus:ring-opacity-50 py-2 px-3 border text-slate-900"
              required
            />
            <p className="text-xs text-slate-500 mt-1">
              Sem a barra final. Usado para redirecionar o usuário após o pagamento (Webhook / Callbacks).
            </p>
          </div>

          <div className="pt-2">
            <button
              type="submit"
              disabled={settingsLoading}
              className="flex items-center gap-2 px-4 py-2 bg-navy-800 text-white rounded hover:bg-navy-900 font-medium transition-colors disabled:opacity-50"
            >
              <Save size={18} />
              {settingsLoading ? 'Salvando...' : 'Salvar Configurações'}
            </button>
          </div>
        </form>
      </div>

    </div>
  );
}