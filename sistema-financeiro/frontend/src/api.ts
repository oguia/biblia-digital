// Em produção, usa 'api' relativo (funciona em subdir). Em dev, usa caminho completo.
const API_BASE = import.meta.env.DEV ? 'http://localhost:8000/sistema-financeiro/api' : 'api';

export const api = {
  async request(endpoint: string, method: string = 'GET', body: any = null) {
    const token = localStorage.getItem('token');
    const headers: any = {
      'Content-Type': 'application/json',
    };
    if (token) {
      headers['Authorization'] = `Bearer ${token}`;
    }

    const config: RequestInit = {
      method,
      headers,
    };

    if (body) {
      config.body = JSON.stringify(body);
    }

    const response = await fetch(`${API_BASE}${endpoint}`, config);

    if (response.status === 401) {
      // Token expirado ou inválido
      localStorage.removeItem('token');
      window.location.href = '/login';
      return;
    }

    const data = await response.json();
    if (!response.ok) {
      throw new Error(data.error || 'Erro na requisição');
    }
    return data;
  }
};
