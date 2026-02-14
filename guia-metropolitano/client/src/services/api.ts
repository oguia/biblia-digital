import axios from 'axios';

// Detect if we are in development or production relative path
const API_BASE = import.meta.env.DEV ? 'http://localhost:8000/api' : './api';

const api = axios.create({
    baseURL: API_BASE,
    headers: {
        'Content-Type': 'application/json'
    }
});

export const searchBusinesses = async (query: string) => {
    try {
        const response = await api.get(`/search.php?q=${encodeURIComponent(query)}`);
        return response.data;
    } catch (error) {
        console.error("Search error:", error);
        return [];
    }
};

export const getCategories = async () => {
    try {
        const response = await api.get('/categories.php');
        return response.data;
    } catch (error) {
        return [];
    }
};

export const getBusinessDetails = async (slug: string) => {
    try {
        const response = await api.get(`/businesses.php?slug=${slug}`);
        return response.data;
    } catch (error) {
        return null;
    }
};

export default api;
