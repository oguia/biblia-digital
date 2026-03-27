import React, { useState, useEffect } from 'react';
import { HashRouter as Router, Routes, Route, Navigate, Link, useNavigate } from 'react-router-dom';
import axios from 'axios';
import { LogIn, MessageSquare, Settings, BookOpen, LogOut, Users, StopCircle, PlayCircle, Loader2, User } from 'lucide-react';
import KanbanBoard from './components/KanbanBoard';
import KnowledgeBase from './components/KnowledgeBase';
import SettingsPanel from './components/SettingsPanel';
import TeamPanel from './components/TeamPanel';
import MyProfile from './components/MyProfile';

// Determine API URL (dynamic relative for hostinger)
const apiUrl = window.location.hostname === 'localhost' ? 'http://127.0.0.1:8000/zapcrm/api/index.php' : './api/index.php';

axios.interceptors.request.use((config) => {
    const token = localStorage.getItem('token');
    if (token) config.headers.Authorization = `Bearer ${token}`;
    return config;
});

function Login({ setAuth }) {
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [error, setError] = useState('');
    const navigate = useNavigate();

    const handleLogin = async (e) => {
        e.preventDefault();
        try {
            const res = await axios.post(`${apiUrl}/login`, { email, password });
            localStorage.setItem('token', res.data.token);
            localStorage.setItem('user', JSON.stringify(res.data.user));
            setAuth(res.data.user);
            navigate('/kanban');
        } catch (err) {
            setError('Credenciais inválidas');
        }
    };

    return (
        <div className="min-h-screen bg-slate-100 flex items-center justify-center p-4">
            <div className="bg-white p-8 rounded-xl shadow-lg w-full max-w-md">
                <div className="flex justify-center mb-6">
                    <div className="w-16 h-16 bg-emerald-500 rounded-full flex items-center justify-center">
                        <MessageSquare className="text-white w-8 h-8" />
                    </div>
                </div>
                <h1 className="text-2xl font-bold text-center text-slate-800 mb-6">ZapCRM</h1>
                {error && <div className="bg-red-50 text-red-600 p-3 rounded mb-4 text-sm">{error}</div>}
                <form onSubmit={handleLogin} className="space-y-4">
                    <div>
                        <label className="block text-sm font-medium text-slate-700 mb-1">E-mail</label>
                        <input type="email" value={email} onChange={e => setEmail(e.target.value)} className="w-full p-2 border border-slate-300 rounded focus:ring-emerald-500 focus:border-emerald-500" required />
                    </div>
                    <div>
                        <label className="block text-sm font-medium text-slate-700 mb-1">Senha</label>
                        <input type="password" value={password} onChange={e => setPassword(e.target.value)} className="w-full p-2 border border-slate-300 rounded focus:ring-emerald-500 focus:border-emerald-500" required />
                    </div>
                    <button type="submit" className="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2 px-4 rounded transition-colors flex items-center justify-center gap-2">
                        <LogIn className="w-4 h-4" /> Entrar
                    </button>
                </form>
            </div>
        </div>
    );
}

function Layout({ children, user, logout }) {
    return (
        <div className="min-h-screen flex bg-slate-50">
            {/* Sidebar */}
            <aside className="w-64 bg-slate-900 text-slate-300 flex flex-col hidden md:flex">
                <div className="h-16 flex items-center px-6 bg-slate-950 text-white font-bold text-xl gap-2">
                    <MessageSquare className="text-emerald-500" /> ZapCRM
                </div>
                <nav className="flex-1 py-4">
                    <Link to="/kanban" className="flex items-center gap-3 px-6 py-3 hover:bg-slate-800 hover:text-white transition-colors">
                        <MessageSquare className="w-5 h-5" /> Atendimentos
                    </Link>
                    <Link to="/profile" className="flex items-center gap-3 px-6 py-3 hover:bg-slate-800 hover:text-white transition-colors">
                        <User className="w-5 h-5" /> Meu Perfil
                    </Link>
                    {user?.role === 'admin' && (
                        <>
                            <Link to="/team" className="flex items-center gap-3 px-6 py-3 hover:bg-slate-800 hover:text-white transition-colors border-t border-slate-800 mt-2 pt-4">
                                <Users className="w-5 h-5 text-indigo-400" /> Equipe
                            </Link>
                            <Link to="/knowledge" className="flex items-center gap-3 px-6 py-3 hover:bg-slate-800 hover:text-white transition-colors">
                                <BookOpen className="w-5 h-5 text-emerald-400" /> Treinar IA
                            </Link>
                            <Link to="/settings" className="flex items-center gap-3 px-6 py-3 hover:bg-slate-800 hover:text-white transition-colors border-b border-slate-800 pb-4 mb-2">
                                <Settings className="w-5 h-5 text-slate-400" /> Configurações
                            </Link>
                        </>
                    )}
                </nav>
                <div className="p-4 bg-slate-950">
                    <div className="mb-4 flex items-center gap-3 px-2">
                        <div className="w-8 h-8 rounded-full bg-emerald-600 flex items-center justify-center text-white font-bold">
                            {user?.name?.charAt(0)}
                        </div>
                        <div className="text-sm">
                            <div className="text-white font-medium">{user?.name}</div>
                            <div className="text-slate-500 text-xs">{user?.role === 'admin' ? 'Administrador' : 'Atendente'}</div>
                        </div>
                    </div>
                    <button onClick={logout} className="w-full flex items-center justify-center gap-2 px-4 py-2 text-sm text-slate-400 hover:text-white hover:bg-slate-800 rounded transition-colors">
                        <LogOut className="w-4 h-4" /> Sair
                    </button>
                </div>
            </aside>
            {/* Main Content */}
            <main className="flex-1 flex flex-col h-screen overflow-hidden">
                {/* Mobile header placeholder */}
                <div className="md:hidden h-14 bg-slate-900 text-white flex items-center justify-between px-4">
                    <div className="font-bold flex items-center gap-2"><MessageSquare className="w-5 h-5 text-emerald-500"/> ZapCRM</div>
                    <button onClick={logout}><LogOut className="w-5 h-5 text-slate-400" /></button>
                </div>
                <div className="flex-1 overflow-auto">
                    {children}
                </div>
            </main>
        </div>
    );
}

export default function App() {
    const [user, setUser] = useState(null);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        const storedUser = localStorage.getItem('user');
        if (storedUser) {
            setUser(JSON.parse(storedUser));
        }
        setLoading(false);
    }, []);

    const logout = () => {
        localStorage.removeItem('token');
        localStorage.removeItem('user');
        setUser(null);
    };

    if (loading) return <div>Carregando...</div>;

    return (
        <Router>
            <Routes>
                <Route path="/login" element={!user ? <Login setAuth={setUser} /> : <Navigate to="/kanban" />} />
                <Route path="/*" element={
                    user ? (
                        <Layout user={user} logout={logout}>
                            <Routes>
                                <Route path="/kanban" element={<KanbanBoard apiUrl={apiUrl} />} />
                                <Route path="/profile" element={<MyProfile apiUrl={apiUrl} />} />
                                {user.role === 'admin' && (
                                    <>
                                        <Route path="/team" element={<TeamPanel apiUrl={apiUrl} />} />
                                        <Route path="/knowledge" element={<KnowledgeBase apiUrl={apiUrl} />} />
                                        <Route path="/settings" element={<SettingsPanel apiUrl={apiUrl} />} />
                                    </>
                                )}
                                <Route path="*" element={<Navigate to="/kanban" />} />
                            </Routes>
                        </Layout>
                    ) : <Navigate to="/login" />
                } />
            </Routes>
        </Router>
    );
}
