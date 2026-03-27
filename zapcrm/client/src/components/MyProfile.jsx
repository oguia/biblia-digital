import React, { useState } from 'react';
import axios from 'axios';
import { User, Key, Save } from 'lucide-react';

export default function MyProfile({ apiUrl }) {
    const user = JSON.parse(localStorage.getItem('user'));
    const [name, setName] = useState(user.name);
    const [password, setPassword] = useState('');
    const [saving, setSaving] = useState(false);
    const [message, setMessage] = useState('');

    const handleSave = async (e) => {
        e.preventDefault();
        setSaving(true);
        setMessage('');

        try {
            await axios.put(`${apiUrl}/users`, { id: user.id, name, password });

            // Update local storage name
            const updatedUser = { ...user, name };
            localStorage.setItem('user', JSON.stringify(updatedUser));

            setMessage('Perfil atualizado com sucesso!');
            setPassword('');
        } catch (err) {
            setMessage('Erro ao atualizar perfil.');
        } finally {
            setSaving(false);
        }
    };

    return (
        <div className="p-8 max-w-2xl mx-auto">
            <div className="flex items-center gap-3 mb-8">
                <div className="w-12 h-12 bg-slate-200 rounded-xl flex items-center justify-center">
                    <User className="w-6 h-6 text-slate-700" />
                </div>
                <div>
                    <h1 className="text-2xl font-bold text-slate-800">Meu Perfil</h1>
                    <p className="text-slate-500">Atualize seus dados e senha de acesso.</p>
                </div>
            </div>

            <form onSubmit={handleSave} className="bg-white p-8 rounded-2xl shadow-sm border border-slate-200">
                {message && (
                    <div className={`p-4 rounded-lg mb-6 text-sm font-medium ${message.includes('sucesso') ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-red-50 text-red-700 border border-red-200'}`}>
                        {message}
                    </div>
                )}

                <div className="space-y-6">
                    <div>
                        <label className="block text-sm font-semibold text-slate-700 mb-2">Nome de Exibição</label>
                        <input
                            type="text"
                            value={name}
                            onChange={e => setName(e.target.value)}
                            className="w-full p-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-slate-500 outline-none text-slate-800"
                            required
                        />
                    </div>

                    <div>
                        <label className="block text-sm font-semibold text-slate-700 mb-2 flex items-center gap-2">
                            <Key className="w-4 h-4 text-slate-500" /> Nova Senha (Opcional)
                        </label>
                        <input
                            type="password"
                            value={password}
                            onChange={e => setPassword(e.target.value)}
                            placeholder="Deixe em branco para manter a atual"
                            className="w-full p-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-slate-500 outline-none text-slate-800"
                        />
                        <p className="text-xs text-slate-500 mt-2">
                            Para a sua segurança, use uma senha forte com letras e números.
                        </p>
                    </div>

                    <button
                        type="submit"
                        disabled={saving}
                        className="w-full bg-slate-800 hover:bg-slate-900 text-white font-medium py-4 px-6 rounded-xl transition-colors flex items-center justify-center gap-2 disabled:opacity-70 mt-4"
                    >
                        {saving ? "Salvando alterações..." : <><Save className="w-5 h-5" /> Salvar Perfil</>}
                    </button>
                </div>
            </form>
        </div>
    );
}
