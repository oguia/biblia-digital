import React, { useState, useEffect } from 'react';
import axios from 'axios';
import { Settings, Smartphone, Key, AlertTriangle, ExternalLink, Copy } from 'lucide-react';

export default function SettingsPanel({ apiUrl }) {
    const [settings, setSettings] = useState({
        gemini_api_key: '',
        bot_url: '',
        bot_token: '',
        handoff_message: ''
    });
    const [saving, setSaving] = useState(false);
    const [webhookUrl, setWebhookUrl] = useState('');

    const fetchSettings = async () => {
        try {
            const res = await axios.get(`${apiUrl}/settings`);
            const data = {};
            res.data.forEach(item => {
                data[item.key_name] = item.value_data;
            });
            setSettings(data);
        } catch (err) {
            console.error(err);
        }
    };

    useEffect(() => {
        fetchSettings();

        // Generate webhook URL to show to the user
        const currentUrl = window.location.href;
        let base = currentUrl.split('#')[0];
        if (base.endsWith('/')) base = base.slice(0, -1);
        if (base.endsWith('index.html')) base = base.replace('/index.html', '');

        setWebhookUrl(`${base}/api/index.php/webhook`);

    }, []);

    const handleChange = (e) => {
        setSettings({ ...settings, [e.target.name]: e.target.value });
    };

    const handleSave = async (e) => {
        e.preventDefault();
        setSaving(true);
        try {
            await axios.post(`${apiUrl}/settings`, settings);
            alert("Configurações salvas!");
        } catch (err) {
            console.error(err);
        } finally {
            setSaving(false);
        }
    };

    const copyToClipboard = (text) => {
        navigator.clipboard.writeText(text);
        alert('Copiado para a área de transferência!');
    };

    return (
        <div className="p-8 max-w-5xl mx-auto">
            <div className="flex items-center gap-3 mb-8">
                <div className="w-12 h-12 bg-slate-200 rounded-xl flex items-center justify-center">
                    <Settings className="w-6 h-6 text-slate-700" />
                </div>
                <div>
                    <h1 className="text-2xl font-bold text-slate-800">Configurações do Sistema</h1>
                    <p className="text-slate-500">Gerencie a conexão da API do WhatsApp e as chaves de Inteligência Artificial.</p>
                </div>
            </div>

            <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
                {/* Bot Connection */}
                <div className="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 flex flex-col">
                    <h2 className="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                        <Smartphone className="w-5 h-5 text-emerald-500" /> Servidor WhatsApp (Node.js)
                    </h2>

                    <div className="space-y-6 flex-1">
                        <div className="bg-slate-50 border border-slate-200 p-4 rounded-xl text-sm text-slate-700">
                            <strong>Instruções para Hostinger:</strong><br/>
                            1. Crie um subdomínio no painel (ex: <i>robo.seusite.com</i>).<br/>
                            2. Crie um <b>Web app Node.js</b> apontando para a pasta extraída do <code>zapcrm-bot.zip</code>.<br/>
                            3. Configure o arquivo <code>.env</code> no Node.js com os dados abaixo.<br/>
                            4. Cole a URL do subdomínio Node.js no campo abaixo.
                        </div>

                        <div>
                            <label className="block text-sm font-semibold text-slate-700 mb-2">URL do Subdomínio Node.js (bot_url)</label>
                            <input
                                type="text"
                                name="bot_url"
                                value={settings.bot_url || ''}
                                onChange={handleChange}
                                placeholder="https://robo.meusite.com.br"
                                className="w-full p-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none text-sm"
                            />
                        </div>

                        {settings.bot_url && (
                            <a
                                href={settings.bot_url}
                                target="_blank"
                                rel="noreferrer"
                                className="flex items-center justify-center gap-2 w-full py-3 bg-slate-800 hover:bg-slate-900 text-white rounded-lg font-medium transition-colors"
                            >
                                Abrir Painel do Robô (QR Code) <ExternalLink className="w-4 h-4" />
                            </a>
                        )}

                        <div className="pt-4 border-t border-slate-100">
                            <h3 className="font-semibold text-slate-800 mb-3 text-sm">Dados para o arquivo .env do Node.js:</h3>

                            <div className="space-y-3">
                                <div>
                                    <label className="block text-xs text-slate-500 mb-1">WEBHOOK_URL</label>
                                    <div className="flex">
                                        <input type="text" readOnly value={webhookUrl} className="flex-1 bg-slate-100 p-2 border border-slate-200 rounded-l-lg text-xs font-mono text-slate-600 outline-none" />
                                        <button type="button" onClick={() => copyToClipboard(webhookUrl)} className="bg-slate-200 px-3 rounded-r-lg hover:bg-slate-300 border border-l-0 border-slate-200 text-slate-600"><Copy className="w-4 h-4" /></button>
                                    </div>
                                </div>
                                <div>
                                    <label className="block text-xs text-slate-500 mb-1">BOT_TOKEN</label>
                                    <div className="flex">
                                        <input type="text" readOnly value={settings.bot_token || ''} className="flex-1 bg-slate-100 p-2 border border-slate-200 rounded-l-lg text-xs font-mono text-slate-600 outline-none" />
                                        <button type="button" onClick={() => copyToClipboard(settings.bot_token || '')} className="bg-slate-200 px-3 rounded-r-lg hover:bg-slate-300 border border-l-0 border-slate-200 text-slate-600"><Copy className="w-4 h-4" /></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {/* API Settings */}
                <form onSubmit={handleSave} className="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 flex flex-col">
                    <h2 className="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                        <Key className="w-5 h-5 text-blue-500" /> Chaves de Integração (IA)
                    </h2>

                    <div className="space-y-6 flex-1">
                        <div>
                            <label className="block text-sm font-semibold text-slate-700 mb-2">Google Gemini API Key</label>
                            <input
                                type="text"
                                name="gemini_api_key"
                                value={settings.gemini_api_key || ''}
                                onChange={handleChange}
                                placeholder="AIzaSy..."
                                className="w-full p-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none font-mono text-sm"
                            />
                            <p className="text-xs text-slate-500 mt-2">
                                Obtenha uma chave gratuita no Google AI Studio para fazer a IA funcionar.
                            </p>
                        </div>

                        <div>
                            <label className="block text-sm font-semibold text-slate-700 mb-2">Mensagem de Transbordo (Handoff)</label>
                            <textarea
                                name="handoff_message"
                                value={settings.handoff_message || ''}
                                onChange={handleChange}
                                placeholder="Vou te transferir para um especialista..."
                                className="w-full p-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none h-24 resize-y text-sm"
                            />
                            <p className="text-xs text-slate-500 mt-2">
                                Mensagem que a IA envia antes de transferir o contato para a coluna "Falar com Humano".
                            </p>
                        </div>
                    </div>

                    <button
                        type="submit"
                        disabled={saving}
                        className="mt-6 w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-xl transition-colors flex items-center justify-center gap-2 disabled:opacity-70 shadow-sm"
                    >
                        {saving ? "Salvando..." : "Salvar Configurações"}
                    </button>
                </form>
            </div>
        </div>
    );
}
