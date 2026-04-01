import React, { useState } from 'react';
import useSWR from 'swr';
import { fetchWithAuth } from '../lib/api';
import { FolderOpen, Upload, FileText, FileSpreadsheet, Image as ImageIcon, Trash2, Download } from 'lucide-react';

export default function Library() {
  const { data, error, mutate } = useSWR('/files.php?action=list', fetchWithAuth);
  const [uploading, setUploading] = useState(false);
  const [uploadError, setUploadError] = useState('');

  const handleFileUpload = async (e) => {
    const file = e.target.files[0];
    if (!file) return;

    if (file.size > 5 * 1024 * 1024) {
      setUploadError('O arquivo excede o limite de 5MB.');
      return;
    }

    setUploading(true);
    setUploadError('');

    const formData = new FormData();
    formData.append('file', file);

    try {
      await fetchWithAuth('/files.php?action=upload', {
        method: 'POST',
        body: formData
      });
      mutate();
    } catch (err) {
      setUploadError(err.message);
    } finally {
      setUploading(false);
      e.target.value = null; // reset input
    }
  };

  const handleDelete = async (id) => {
    if (confirm('Tem certeza que deseja excluir este arquivo?')) {
      try {
        await fetchWithAuth(`/files.php?id=${id}`, { method: 'DELETE' });
        mutate();
      } catch (err) {
        alert('Erro ao excluir: ' + err.message);
      }
    }
  };

  const handleDownload = async (id, name) => {
    try {
       const token = localStorage.getItem('token');
       const baseUrl = import.meta.env.DEV ? '/api' : 'api';
       const response = await fetch(`${baseUrl}/files.php?action=download&id=${id}`, {
          headers: { 'Authorization': `Bearer ${token}` }
       });

       if (!response.ok) throw new Error('Erro ao baixar');

       const blob = await response.blob();
       const url = window.URL.createObjectURL(blob);
       const a = document.createElement('a');
       a.style.display = 'none';
       a.href = url;
       a.download = name;
       document.body.appendChild(a);
       a.click();
       window.URL.revokeObjectURL(url);
    } catch (e) {
       alert(e.message);
    }
  };

  const getFileIcon = (type) => {
    if (type.includes('pdf')) return <FileText size={24} className="text-red-500" />;
    if (type.includes('spreadsheet') || type.includes('excel')) return <FileSpreadsheet size={24} className="text-green-500" />;
    if (type.includes('image')) return <ImageIcon size={24} className="text-blue-500" />;
    return <FileText size={24} className="text-slate-500" />;
  };

  const formatSize = (bytes) => {
    if (bytes === 0) return '0 B';
    const k = 1024;
    const sizes = ['B', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
  };

  if (error) return <div className="text-red-500">Erro ao carregar arquivos: {error.message}</div>;

  return (
    <div className="space-y-6">
      <div className="flex justify-between items-center mb-6">
        <h2 className="text-2xl font-bold text-navy-900 flex items-center gap-2">
          <FolderOpen size={24} className="text-highlight" /> Biblioteca de Arquivos
        </h2>
      </div>

      <div className="bg-white p-6 rounded-lg border border-slate-200 shadow-sm">
        <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
          <div>
            <h3 className="text-lg font-semibold text-navy-900">Meus Materiais</h3>
            <p className="text-sm text-slate-500">Faça upload de PDFs, Planilhas (XLSX) e Imagens. Limite de 5MB por arquivo.</p>
          </div>

          <div className="relative">
            <input
              type="file"
              id="file-upload"
              accept=".pdf,.xlsx,.xls,image/png,image/jpeg,image/webp"
              className="hidden"
              onChange={handleFileUpload}
              disabled={uploading}
            />
            <label
              htmlFor="file-upload"
              className={`flex items-center gap-2 px-4 py-2 ${uploading ? 'bg-slate-400 cursor-not-allowed' : 'bg-highlight hover:bg-highlight-hover cursor-pointer'} text-white rounded font-medium shadow-sm transition-colors`}
            >
              <Upload size={20} />
              {uploading ? 'Enviando...' : 'Novo Upload'}
            </label>
          </div>
        </div>

        {uploadError && (
          <div className="mb-4 p-3 bg-red-50 border border-red-200 text-red-600 rounded text-sm">
            {uploadError}
          </div>
        )}

        {!data ? (
          <div className="text-slate-500 text-center py-8">Carregando arquivos...</div>
        ) : data.files.length === 0 ? (
          <div className="text-center py-12 border-2 border-dashed border-slate-200 rounded-lg">
            <FolderOpen size={48} className="mx-auto text-slate-300 mb-3" />
            <p className="text-slate-500 font-medium">Nenhum arquivo na sua biblioteca</p>
            <p className="text-sm text-slate-400 mt-1">Faça o upload do seu primeiro material didático.</p>
          </div>
        ) : (
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            {data.files.map(file => (
              <div key={file.id} className="flex items-center justify-between p-4 bg-slate-50 border border-slate-200 rounded-lg hover:border-slate-300 transition-colors group">
                <div className="flex items-center gap-3 overflow-hidden">
                  <div className="shrink-0 bg-white p-2 rounded shadow-sm border border-slate-100">
                     {getFileIcon(file.file_type)}
                  </div>
                  <div className="min-w-0">
                    <p className="text-sm font-semibold text-navy-900 truncate" title={file.original_name}>
                      {file.original_name}
                    </p>
                    <p className="text-xs text-slate-500">{formatSize(file.file_size)}</p>
                  </div>
                </div>
                <div className="flex items-center gap-1 shrink-0 opacity-0 group-hover:opacity-100 transition-opacity">
                  <button
                    onClick={() => handleDownload(file.id, file.original_name)}
                    className="p-1.5 text-blue-600 hover:bg-blue-100 rounded"
                    title="Baixar"
                  >
                    <Download size={16} />
                  </button>
                  <button
                    onClick={() => handleDelete(file.id)}
                    className="p-1.5 text-red-600 hover:bg-red-100 rounded"
                    title="Excluir"
                  >
                    <Trash2 size={16} />
                  </button>
                </div>
              </div>
            ))}
          </div>
        )}
      </div>
    </div>
  );
}