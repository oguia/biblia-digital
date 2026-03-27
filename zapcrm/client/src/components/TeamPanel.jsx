import React, { useState, useEffect } from 'react';
import axios from 'axios';
import { Users, UserPlus, Trash2, Shield, Key } from 'lucide-react';

export default function TeamPanel({ apiUrl }) {
    const [users, setUsers] = useState([]);
    const [loading, setLoading] = useState(true);
    const [name, setName] = useState('');
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [role, setRole] = useState('agent');
    const [error, setError] = useState('');

    const fetchUsers = async () => {
        try {
            const res = await axios.get(`${apiUrl}/users`);
            setUsers(res.data);
        } catch (err) {
            console.error(err);
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => {
        fetchUsers();
    }, []);

    const handleAddUser = async (e) => {
        e.preventDefault();
        setError('');
        if (!name || !email || !password) {
            setError('Preencha todos os campos.');
            return;
        }

        try {
            await axios.post(`${apiUrl}/users`, { name, email, password, role });
            setName('');
            setEmail('');
            setPassword('');
            setRole('agent');
            fetchUsers();
        } catch (err) {
            setError(err.response?.data?.error || 'Erro ao adicionar usuário.');
        }
    };

    const handleDeleteUser = async (id, isSelf) => {
        if (isSelf) {
            alert("Você não pode excluir sua própria conta.");
            return;
        }
        if (!window.confirm("Tem certeza que deseja remover este usuário?")) return;

        try {
            await axios.delete(`${apiUrl}/users?id=${id}`);
            fetchUsers();
        } catch (err) {
            console.error(err);
        }
    };

    const handleChangePassword = async (id) => {
        const newPass = prompt("Digite a nova senha para este usuário:");
        if (!newPass) return;

        try {
            await axios.put(`${apiUrl}/users`, { id, password: newPass });
            alert("Senha alterada com sucesso!");
        } catch (err) {
            alert("Erro ao alterar senha.");
        }
    };

    return (
        <div className="p-8 max-w-5xl mx-auto">
            <div className="flex items-center gap-3 mb-8">
                <div className="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center">
                    <Users className="w-6 h-6 text-indigo-600" />
                </div>
                <div>
                    <h1 className="text-2xl font-bold text-slate-800">Gerenciar Equipe</h1>
                    <p className="text-slate-500">Adicione atendentes para gerenciar os leads no Kanban.</p>
                </div>
            </div>

            <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {/* Add User Form */}
                <div className="lg:col-span-1">
                    <form onSubmit={handleAddUser} className="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                        <h2 className="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                            <UserPlus className="w-5 h-5 text-indigo-500" /> Adicionar Membro
                        </h2>

                        {error && <div className="bg-red-50 text-red-600 p-3 rounded-lg text-sm mb-4">{error}</div>}

                        <div className="space-y-4">
                            <div>
                                <label className="block text-sm font-medium text-slate-700 mb-1">Nome Completo</label>
                                <input
                                    type="text"
                                    value={name}
                                    onChange={e => setName(e.target.value)}
                                    className="w-full p-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                                    required
                                />
                            </div>
                            <div>
                                <label className="block text-sm font-medium text-slate-700 mb-1">E-mail</label>
                                <input
                                    type="email"
                                    value={email}
                                    onChange={e => setEmail(e.target.value)}
                                    className="w-full p-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                                    required
                                />
                            </div>
                            <div>
                                <label className="block text-sm font-medium text-slate-700 mb-1">Senha de Acesso</label>
                                <input
                                    type="password"
                                    value={password}
                                    onChange={e => setPassword(e.target.value)}
                                    className="w-full p-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                                    required
                                />
                            </div>
                            <div>
                                <label className="block text-sm font-medium text-slate-700 mb-1">Permissão</label>
                                <select
                                    value={role}
                                    onChange={e => setRole(e.target.value)}
                                    className="w-full p-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 bg-white"
                                >
                                    <option value="agent">Atendente (Apenas Kanban)</option>
                                    <option value="admin">Administrador (Acesso Total)</option>
                                </select>
                            </div>

                            <button
                                type="submit"
                                className="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-3 rounded-lg transition-colors mt-2"
                            >
                                Cadastrar Usuário
                            </button>
                        </div>
                    </form>

                    <div className="mt-4 p-4 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-600">
                        <p className="font-semibold text-slate-700 mb-1 flex items-center gap-1">
                            <Shield className="w-4 h-4" /> Segurança
                        </p>
                        A senha do administrador padrão (admin123) deve ser alterada imediatamente na lista ao lado por questões de segurança.
                    </div>
                </div>

                {/* User List */}
                <div className="lg:col-span-2">
                    <div className="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                        <div className="p-6 border-b border-slate-100 flex justify-between items-center">
                            <h2 className="text-lg font-bold text-slate-800">Membros da Equipe</h2>
                            <span className="bg-slate-100 text-slate-600 px-3 py-1 rounded-full text-xs font-semibold">
                                {users.length} usuários
                            </span>
                        </div>

                        {loading ? (
                            <div className="p-8 text-center text-slate-500">Carregando equipe...</div>
                        ) : (
                            <div className="divide-y divide-slate-100">
                                {users.map(u => {
                                    const currentUser = JSON.parse(localStorage.getItem('user'));
                                    const isSelf = currentUser.id === u.id;

                                    return (
                                        <div key={u.id} className="p-4 hover:bg-slate-50 flex items-center justify-between transition-colors">
                                            <div className="flex items-center gap-4">
                                                <div className="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center text-slate-600 font-bold">
                                                    {u.name.charAt(0).toUpperCase()}
                                                </div>
                                                <div>
                                                    <div className="font-semibold text-slate-800 flex items-center gap-2">
                                                        {u.name}
                                                        {isSelf && <span className="bg-emerald-100 text-emerald-700 text-[10px] px-2 py-0.5 rounded-full uppercase tracking-wider font-bold">Você</span>}
                                                    </div>
                                                    <div className="text-sm text-slate-500">{u.email}</div>
                                                </div>
                                            </div>

                                            <div className="flex items-center gap-6">
                                                <div className="text-xs font-medium px-2.5 py-1 rounded-full bg-slate-100 text-slate-600">
                                                    {u.role === 'admin' ? 'Administrador' : 'Atendente'}
                                                </div>

                                                <div className="flex items-center gap-2">
                                                    <button
                                                        onClick={() => handleChangePassword(u.id)}
                                                        className="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                                        title="Alterar Senha"
                                                    >
                                                        <Key className="w-4 h-4" />
                                                    </button>
                                                    <button
                                                        onClick={() => handleDeleteUser(u.id, isSelf)}
                                                        disabled={isSelf}
                                                        className="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors disabled:opacity-30 disabled:hover:bg-transparent disabled:hover:text-slate-400"
                                                        title="Remover"
                                                    >
                                                        <Trash2 className="w-4 h-4" />
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    );
                                })}
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </div>
    );
}
