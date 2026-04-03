import axios from 'axios';

const api = axios.create({
  baseURL: import.meta.env.VITE_API_URL || 'http://localhost/faro-de-ouro/api/index.php?route=',
});

api.interceptors.request.use((config) => {
  const token = localStorage.getItem('faro_token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

export default api;
