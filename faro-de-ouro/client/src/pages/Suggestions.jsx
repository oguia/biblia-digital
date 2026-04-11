import React, { useState } from 'react';
import Layout from '../components/Layout';
import api from '../api';

const Suggestions = ({ user }) => {
  const [message, setMessage] = useState('');
  const [status, setStatus] = useState('');

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      await api.post('suggestions', { message });
      setStatus('Sugestão enviada com sucesso! Obrigado.');
      setMessage('');
      setTimeout(() => setStatus(''), 3000);
    } catch (err) {
      setStatus('Erro ao enviar.');
    }
  };

  return (
    <Layout user={user}>
      <div className="max-w-2xl mx-auto bg-white p-8 rounded-xl shadow-sm">
        <h1 className="text-2xl font-bold text-brand-dark mb-4">Enviar Sugestão</h1>
        <p className="text-gray-600 mb-6">Sua opinião é muito importante para melhorarmos o Faro de Ouro. Envie suas sugestões, ideias de novas funcionalidades ou relate algum problema diretamente para a nossa equipe.</p>

        {status && <div className="p-4 mb-4 bg-green-50 text-green-700 rounded-lg">{status}</div>}

        <form onSubmit={handleSubmit}>
          <textarea
            required
            rows="5"
            placeholder="Digite sua sugestão aqui..."
            className="w-full px-4 py-3 border rounded-lg focus:ring-brand-orange focus:border-brand-orange resize-none"
            value={message}
            onChange={(e) => setMessage(e.target.value)}
          ></textarea>
          <div className="mt-4 flex justify-end">
            <button type="submit" className="bg-brand-orange text-white px-6 py-2 rounded-lg hover:bg-orange-600 transition">
              Enviar
            </button>
          </div>
        </form>
      </div>
    </Layout>
  );
};

export default Suggestions;
