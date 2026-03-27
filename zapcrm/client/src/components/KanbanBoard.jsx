import React, { useState, useEffect } from 'react';
import axios from 'axios';
import { Send, User, Bot, Clock, X } from 'lucide-react';
import { format } from 'date-fns';
import { ptBR } from 'date-fns/locale';

export default function KanbanBoard({ apiUrl }) {
    const [contacts, setContacts] = useState([]);
    const [selectedContact, setSelectedContact] = useState(null);
    const [messages, setMessages] = useState([]);
    const [newMessage, setNewMessage] = useState('');
    const [loading, setLoading] = useState(true);

    const stages = [
        { id: 'novo', name: 'Novo Lead', color: 'bg-blue-50 border-blue-200' },
        { id: 'atendimento', name: 'Bot Atendendo', color: 'bg-amber-50 border-amber-200' },
        { id: 'humano', name: 'Falar com Humano', color: 'bg-purple-50 border-purple-200' },
        { id: 'finalizado', name: 'Finalizado', color: 'bg-emerald-50 border-emerald-200' }
    ];

    const fetchContacts = async () => {
        try {
            const res = await axios.get(`${apiUrl}/contacts`);
            setContacts(res.data);
            setLoading(false);
        } catch (err) {
            console.error("Erro ao buscar contatos", err);
        }
    };

    useEffect(() => {
        fetchContacts();
        const interval = setInterval(fetchContacts, 5000);
        return () => clearInterval(interval);
    }, []);

    const fetchMessages = async (contactId) => {
        try {
            const res = await axios.get(`${apiUrl}/messages?contact_id=${contactId}`);
            setMessages(res.data);
        } catch (err) {
            console.error("Erro ao buscar mensagens", err);
        }
    };

    useEffect(() => {
        if (selectedContact) {
            fetchMessages(selectedContact.id);
            const interval = setInterval(() => fetchMessages(selectedContact.id), 3000);
            return () => clearInterval(interval);
        }
    }, [selectedContact]);

    const handleDragStart = (e, contactId) => {
        e.dataTransfer.setData('contactId', contactId);
    };

    const handleDrop = async (e, stageId) => {
        e.preventDefault();
        const contactId = e.dataTransfer.getData('contactId');
        if (!contactId) return;

        const botPaused = (stageId === 'humano' || stageId === 'finalizado') ? 1 : 0;
        setContacts(prev => prev.map(c => c.id == contactId ? { ...c, stage: stageId, bot_paused: botPaused } : c));

        try {
            await axios.put(`${apiUrl}/contacts`, { id: contactId, stage: stageId });
        } catch (err) {
            console.error("Erro ao atualizar estágio", err);
            fetchContacts();
        }
    };

    const handleDragOver = (e) => {
        e.preventDefault();
    };

    const sendMessage = async (e) => {
        e.preventDefault();
        if (!newMessage.trim() || !selectedContact) return;

        const content = newMessage;
        setNewMessage('');

        const tempMsg = { id: Date.now(), contact_id: selectedContact.id, sender: 'user', content, timestamp: new Date().toISOString() };
        setMessages(prev => [...prev, tempMsg]);

        try {
            await axios.post(`${apiUrl}/messages`, { contact_id: selectedContact.id, content });
        } catch (err) {
            console.error("Erro ao enviar mensagem", err);
            setMessages(prev => prev.filter(m => m.id !== tempMsg.id));
        }
    };

    return (
        <div className="h-full flex flex-col p-6 overflow-hidden">
            <h1 className="text-2xl font-bold text-slate-800 mb-6 flex-shrink-0">Funil de Atendimento</h1>

            <div className="flex-1 flex gap-4 overflow-x-auto overflow-y-hidden pb-4 snap-x">
                {stages.map(stage => (
                    <div
                        key={stage.id}
                        className={`min-w-[300px] sm:min-w-[320px] w-80 rounded-xl border flex flex-col ${stage.color} p-4 snap-center`}
                        onDrop={(e) => handleDrop(e, stage.id)}
                        onDragOver={handleDragOver}
                    >
                        <h2 className="font-bold text-slate-700 mb-4 flex justify-between items-center bg-white/50 p-2 rounded-lg backdrop-blur-sm">
                            {stage.name}
                            <span className="bg-white text-slate-700 w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold shadow-sm">
                                {contacts.filter(c => c.stage === stage.id).length}
                            </span>
                        </h2>

                        <div className="flex-1 overflow-y-auto space-y-3 custom-scrollbar pr-1">
                            {contacts.filter(c => c.stage === stage.id).map(contact => (
                                <div
                                    key={contact.id}
                                    draggable
                                    onDragStart={(e) => handleDragStart(e, contact.id)}
                                    onClick={() => setSelectedContact(contact)}
                                    className="bg-white p-4 rounded-lg shadow-sm border border-slate-200 cursor-pointer hover:border-emerald-500 hover:shadow-md transition-all group"
                                >
                                    <div className="flex items-center justify-between mb-2">
                                        <div className="font-semibold text-slate-800 text-sm truncate pr-2">
                                            {contact.name || contact.phone}
                                        </div>
                                        {contact.bot_paused == 1 && (
                                            <div className="bg-purple-100 text-purple-700 p-1 rounded-full" title="Aguardando Humano">
                                                <User className="w-3.5 h-3.5" />
                                            </div>
                                        )}
                                    </div>
                                    <div className="text-sm text-slate-500 line-clamp-2 leading-relaxed">
                                        {contact.last_message || 'Iniciou conversa'}
                                    </div>
                                    <div className="mt-3 flex items-center justify-between text-[11px] text-slate-400">
                                        <div className="flex items-center gap-1">
                                            <Clock className="w-3 h-3" />
                                            {format(new Date(contact.last_interaction), "dd/MM HH:mm")}
                                        </div>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>
                ))}
            </div>

            {selectedContact && (
                <div className="fixed inset-0 bg-slate-900/40 backdrop-blur-sm flex justify-end z-50">
                    <div className="w-full max-w-md bg-white h-full flex flex-col shadow-2xl animate-in slide-in-from-right duration-200">
                        <div className="p-4 border-b flex items-center justify-between bg-slate-50">
                            <div className="flex items-center gap-3">
                                <div className="w-10 h-10 bg-emerald-100 text-emerald-700 rounded-full flex items-center justify-center font-bold text-lg">
                                    {(selectedContact.name || selectedContact.phone).charAt(0).toUpperCase()}
                                </div>
                                <div>
                                    <h3 className="font-bold text-slate-800">{selectedContact.name || 'Desconhecido'}</h3>
                                    <p className="text-xs text-slate-500 font-mono">{selectedContact.phone}</p>
                                </div>
                            </div>
                            <button onClick={() => setSelectedContact(null)} className="p-2 hover:bg-slate-200 rounded-full text-slate-500 transition-colors">
                                <X className="w-5 h-5" />
                            </button>
                        </div>

                        <div className="flex-1 overflow-y-auto p-4 space-y-4 bg-[#efeae2] bg-opacity-50 relative">
                            {/* WhatsApp Pattern background could go here */}
                            {messages.map((msg, i) => {
                                const isClient = msg.sender === 'client';
                                const isBot = msg.sender === 'bot';

                                return (
                                    <div key={i} className={`flex ${isClient ? 'justify-start' : 'justify-end'}`}>
                                        <div className={`max-w-[85%] rounded-lg p-3 shadow-sm ${
                                            isClient ? 'bg-white text-slate-800 rounded-tl-none' :
                                            isBot ? 'bg-[#dcf8c6] text-slate-800 rounded-tr-none' :
                                            'bg-emerald-600 text-white rounded-tr-none'
                                        }`}>
                                            <div className="text-[15px] whitespace-pre-wrap">{msg.content}</div>
                                            <div className={`text-[10px] mt-1 flex justify-end items-center gap-1 ${isClient || isBot ? 'text-slate-500' : 'text-emerald-100'}`}>
                                                {isBot && <Bot className="w-3 h-3" />}
                                                {!isClient && !isBot && <User className="w-3 h-3" />}
                                                {format(new Date(msg.timestamp), "HH:mm")}
                                            </div>
                                        </div>
                                    </div>
                                );
                            })}
                        </div>

                        <div className="p-3 bg-[#f0f2f5] border-t">
                            <form onSubmit={sendMessage} className="flex gap-2 items-end">
                                <textarea
                                    value={newMessage}
                                    onChange={e => setNewMessage(e.target.value)}
                                    placeholder="Digite uma mensagem..."
                                    className="flex-1 p-3 max-h-32 min-h-[44px] resize-none border-none rounded-lg focus:outline-none focus:ring-0 text-sm"
                                    rows="1"
                                    onKeyDown={e => {
                                        if (e.key === 'Enter' && !e.shiftKey) {
                                            e.preventDefault();
                                            sendMessage(e);
                                        }
                                    }}
                                />
                                <button type="submit" disabled={!newMessage.trim()} className="bg-emerald-600 text-white p-3 rounded-full hover:bg-emerald-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors flex-shrink-0">
                                    <Send className="w-5 h-5" />
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            )}
        </div>
    );
}
