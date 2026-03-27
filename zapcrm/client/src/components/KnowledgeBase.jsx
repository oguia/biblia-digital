import React, { useState, useEffect } from 'react';
import axios from 'axios';
import { BookOpen, Plus, Trash2, FileText, Upload, AlertCircle } from 'lucide-react';
import * as pdfjsLib from 'pdfjs-dist';

// Ensure worker is configured for PDF.js (vite compatible approach)
pdfjsLib.GlobalWorkerOptions.workerSrc = `//cdnjs.cloudflare.com/ajax/libs/pdf.js/${pdfjsLib.version}/pdf.worker.min.js`;

export default function KnowledgeBase({ apiUrl }) {
    const [knowledgeList, setKnowledgeList] = useState([]);
    const [title, setTitle] = useState('');
    const [content, setContent] = useState('');
    const [loading, setLoading] = useState(true);
    const [adding, setAdding] = useState(false);
    const [extracting, setExtracting] = useState(false);

    const fetchKnowledge = async () => {
        try {
            const res = await axios.get(`${apiUrl}/knowledge`);
            setKnowledgeList(res.data);
            setLoading(false);
        } catch (err) {
            console.error(err);
        }
    };

    useEffect(() => {
        fetchKnowledge();
    }, []);

    const handleSubmit = async (e) => {
        e.preventDefault();
        if (!title.trim() || !content.trim()) return;
        setAdding(true);
        try {
            await axios.post(`${apiUrl}/knowledge`, { title, content });
            setTitle('');
            setContent('');
            fetchKnowledge();
        } catch (err) {
            console.error(err);
        } finally {
            setAdding(false);
        }
    };

    const handleDelete = async (id) => {
        if (!window.confirm("Remover este conhecimento da IA?")) return;
        try {
            await axios.delete(`${apiUrl}/knowledge?id=${id}`);
            fetchKnowledge();
        } catch (err) {
            console.error(err);
        }
    };

    const handleFileUpload = async (e) => {
        const file = e.target.files[0];
        if (!file) return;

        if (file.type !== 'application/pdf') {
            alert('Por favor, selecione um arquivo PDF.');
            return;
        }

        setExtracting(true);
        try {
            const arrayBuffer = await file.arrayBuffer();
            const pdf = await pdfjsLib.getDocument({ data: arrayBuffer }).promise;

            let extractedText = '';
            for (let i = 1; i <= pdf.numPages; i++) {
                const page = await pdf.getPage(i);
                const textContent = await page.getTextContent();
                const pageText = textContent.items.map(item => item.str).join(' ');
                extractedText += pageText + '\n\n';
            }

            setTitle(file.name.replace('.pdf', ''));
            setContent(extractedText.trim());
        } catch (err) {
            console.error("Erro ao extrair PDF", err);
            alert("Não foi possível ler este PDF. O arquivo pode estar protegido ou corrompido.");
        } finally {
            setExtracting(false);
            e.target.value = null; // reset input
        }
    };

    return (
        <div className="p-8 max-w-5xl mx-auto">
            <div className="flex items-center gap-3 mb-8">
                <div className="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center">
                    <BookOpen className="w-6 h-6 text-emerald-600" />
                </div>
                <div>
                    <h1 className="text-2xl font-bold text-slate-800">Treinar Inteligência Artificial</h1>
                    <p className="text-slate-500">Adicione textos ou faça upload de PDFs para a IA aprender.</p>
                </div>
            </div>

            <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div className="lg:col-span-2">
                    <div className="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                        <div className="flex justify-between items-center mb-6">
                            <h2 className="text-lg font-bold text-slate-800 flex items-center gap-2">
                                <Plus className="w-5 h-5 text-emerald-500" /> Adicionar Novo Conhecimento
                            </h2>
                            <label className="bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium py-2 px-4 rounded-lg cursor-pointer transition-colors flex items-center gap-2 text-sm">
                                {extracting ? <AlertCircle className="w-4 h-4 animate-spin" /> : <Upload className="w-4 h-4" />}
                                {extracting ? 'Lendo PDF...' : 'Importar de PDF'}
                                <input type="file" accept=".pdf" className="hidden" onChange={handleFileUpload} disabled={extracting} />
                            </label>
                        </div>

                        <form onSubmit={handleSubmit} className="space-y-4">
                            <div>
                                <label className="block text-sm font-medium text-slate-700 mb-1">Título / Assunto</label>
                                <input
                                    type="text"
                                    value={title}
                                    onChange={e => setTitle(e.target.value)}
                                    placeholder="Ex: Tabela de Preços"
                                    className="w-full p-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all"
                                    required
                                />
                            </div>
                            <div>
                                <label className="block text-sm font-medium text-slate-700 mb-1">Conteúdo (O que a IA precisa saber)</label>
                                <textarea
                                    value={content}
                                    onChange={e => setContent(e.target.value)}
                                    placeholder="Ex: O serviço custa R$150. Pode colar um texto grande aqui ou importar um PDF acima."
                                    className="w-full p-3 border border-slate-300 rounded-lg h-40 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all resize-y text-sm"
                                    required
                                />
                            </div>
                            <button
                                type="submit"
                                disabled={adding || extracting}
                                className="bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-3 px-6 rounded-lg transition-colors flex items-center justify-center w-full gap-2 disabled:opacity-70"
                            >
                                {adding ? "Salvando..." : "Treinar IA com este texto"}
                            </button>
                        </form>
                    </div>

                    <div className="mt-6 bg-amber-50 border border-amber-200 p-4 rounded-xl flex gap-3 text-amber-800">
                        <FileText className="w-5 h-5 flex-shrink-0" />
                        <div className="text-sm">
                            <strong>Dica:</strong> O botão "Importar de PDF" lê o arquivo no seu próprio computador e preenche a caixa de texto. Você pode revisar e alterar antes de salvar na IA!
                        </div>
                    </div>
                </div>

                <div>
                    <h2 className="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                        <BookOpen className="w-5 h-5 text-slate-500" /> Base de Dados Atual
                    </h2>
                    {loading ? (
                        <div className="text-slate-500 text-sm">Carregando conhecimentos...</div>
                    ) : knowledgeList.length === 0 ? (
                        <div className="text-slate-500 text-sm italic bg-slate-50 p-4 rounded-lg border border-dashed border-slate-300 text-center">
                            Nenhum conhecimento cadastrado ainda. O bot responderá de forma genérica.
                        </div>
                    ) : (
                        <div className="space-y-3">
                            {knowledgeList.map(item => (
                                <div key={item.id} className="bg-white p-4 rounded-xl shadow-sm border border-slate-200 group hover:border-emerald-400 transition-colors">
                                    <div className="flex justify-between items-start mb-2">
                                        <div className="font-medium text-slate-800">{item.title}</div>
                                        <button
                                            onClick={() => handleDelete(item.id)}
                                            className="text-slate-400 hover:text-red-500 transition-colors p-1"
                                            title="Remover"
                                        >
                                            <Trash2 className="w-4 h-4" />
                                        </button>
                                    </div>
                                    <div className="text-xs text-slate-500 flex items-center gap-1">
                                        <FileText className="w-3 h-3" /> Tipo: Texto extraído
                                    </div>
                                </div>
                            ))}
                        </div>
                    )}
                </div>
            </div>
        </div>
    );
}
