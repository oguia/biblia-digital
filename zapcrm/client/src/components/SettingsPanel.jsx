import React, { useState, useEffect } from 'react';
import axios from 'axios';
import { Settings, Check, Smartphone, Key, AlertTriangle, RefreshCw, Power } from 'lucide-react';

export default function SettingsPanel({ apiUrl, botUrl }) {
    const [settings, setSettings] = useState({
        gemini_api_key: '',
        bot_status: 'offline',
        handoff_message: ''
    });
    const [saving, setSaving] = useState(false);
    const [botRunning, setBotRunning] = useState(false);
    const [qrCode, setQrCode] = useState(null);
    const [statusLoading, setStatusLoading] = useState(true);

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

    const fetchBotStatus = async () => {
        setStatusLoading(true);
        try {
            const res = await axios.get(`${botUrl}?action=status`);
            setBotRunning(res.data.running);
            setQrCode(res.data.qr);
        } catch (err) {
            console.error(err);
        } finally {
            setStatusLoading(false);
        }
    };

    useEffect(() => {
        fetchSettings();
        fetchBotStatus();
        const interval = setInterval(fetchBotStatus, 5000);
        return () => clearInterval(interval);
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

    const toggleBot = async () => {
        const action = botRunning ? 'stop' : 'start';
        setStatusLoading(true);
        try {
            await axios.get(`${botUrl}?action=${action}`);
            fetchBotStatus();
        } catch (err) {
            console.error(err);
            setStatusLoading(false);
        }
    };

    return (
        <div className="p-8 max-w-4xl mx-auto">
            <div className="flex items-center gap-3 mb-8">
                <div className="w-12 h-12 bg-slate-200 rounded-xl flex items-center justify-center">
                    <Settings className="w-6 h-6 text-slate-700" />
                </div>
                <div>
                    <h1 className="text-2xl font-bold text-slate-800">Configurações do Sistema</h1>
                    <p className="text-slate-500">Gerencie a conexão do WhatsApp e as chaves de API.</p>
                </div>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                {/* Bot Connection */}
                <div className="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                    <h2 className="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                        <Smartphone className="w-5 h-5 text-emerald-500" /> Conexão WhatsApp
                    </h2>

                    <div className="flex flex-col items-center justify-center space-y-6">
                        <div className={`px-4 py-2 rounded-full font-medium flex items-center gap-2 text-sm ${
                            botRunning ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800'
                        }`}>
                            <div className={`w-2.5 h-2.5 rounded-full ${botRunning ? 'bg-emerald-500 animate-pulse' : 'bg-red-500'}`}></div>
                            Status: {botRunning ? 'Conectado / Rodando' : 'Desconectado / Parado'}
                        </div>

                        {!botRunning ? (
                            <div className="text-center text-slate-500 text-sm max-w-xs">
                                Clique no botão abaixo para iniciar o robô do WhatsApp. Se for a primeira vez, um QR Code será gerado.
                            </div>
                        ) : qrCode ? (
                            <div className="text-center space-y-4">
                                <p className="text-slate-600 font-medium">Escaneie o QR Code com seu WhatsApp:</p>
                                <div className="p-2 bg-white border border-slate-200 rounded-xl inline-block shadow-sm">
                                    <img src={qrCode} alt="WhatsApp QR Code" className="w-64 h-64" />
                                </div>
                                <p className="text-xs text-amber-600 max-w-xs mx-auto">
                                    Se o QR Code sumir e o status continuar verde, a conexão foi realizada com sucesso!
                                </p>
                            </div>
                        ) : (
                            <div className="text-center space-y-4">
                                <div className="w-24 h-24 bg-emerald-50 rounded-full flex items-center justify-center mx-auto text-emerald-500">
                                    <Check className="w-10 h-10" />
                                </div>
                                <p className="text-emerald-700 font-medium">WhatsApp Conectado e Pronto!</p>
                            </div>
                        )}

                        <button
                            onClick={toggleBot}
                            disabled={statusLoading}
                            className={`w-full py-3 px-6 rounded-xl font-bold flex items-center justify-center gap-2 transition-colors shadow-sm ${
                                botRunning
                                    ? 'bg-red-50 hover:bg-red-100 text-red-600 border border-red-200'
                                    : 'bg-emerald-600 hover:bg-emerald-700 text-white'
                            } disabled:opacity-50`}
                        >
                            {statusLoading ? <RefreshCw className="w-5 h-5 animate-spin" /> : <Power className="w-5 h-5" />}
                            {statusLoading ? 'Aguarde...' : botRunning ? 'Desligar Robô' : 'Ligar Robô'}
                        </button>

                        <div className="flex bg-blue-50 text-blue-800 p-3 rounded-lg text-xs gap-2 items-start text-left">
                            <AlertTriangle className="w-4 h-4 flex-shrink-0 mt-0.5" />
                            <span>
                                Na Hostinger Compartilhada, mantenha o robô <b>LIGADO</b> apenas durante o horário de atendimento para evitar suspensão por uso contínuo de recursos em background.
                            </span>
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
                                Mensagem que a IA envia antes de transferir o contato para a coluna "Atendimento Humano".
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
