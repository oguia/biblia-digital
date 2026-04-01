// Planer/client/src/lib/api.js

// Ensure we use relative paths in production to support subfolders on Hostinger
const API_BASE = import.meta.env.DEV ? '/api' : 'api';

export async function fetchWithAuth(url, options = {}) {
    const token = localStorage.getItem('token');

    if (!options.headers) {
        options.headers = {};
    }

    if (token) {
        options.headers['Authorization'] = `Bearer ${token}`;
    }

    // Only set Content-Type if it's not a FormData request
    if (!(options.body instanceof FormData)) {
        options.headers['Content-Type'] = 'application/json';
        if (options.body && typeof options.body !== 'string') {
            options.body = JSON.stringify(options.body);
        }
    }

    // Ensure URL doesn't have double slashes if API_BASE is relative
    const cleanUrl = url.startsWith('/') ? url.substring(1) : url;
    const fetchUrl = `${API_BASE}/${cleanUrl}`;

    const res = await fetch(fetchUrl, options);

    if (res.status === 401) {
        // Logout logic if token expired
        localStorage.removeItem('token');
        localStorage.removeItem('user');
        window.location.href = '#/login';
        throw new Error('Unauthorized');
    }

    // Wrap JSON parsing to catch and throw meaningful errors instead of syntax errors
    let json;
    const text = await res.text();
    try {
        json = JSON.parse(text);
    } catch (err) {
        console.error("API response is not valid JSON:", text);
        throw new Error(`O servidor retornou um erro inesperado. Resposta: ${text.substring(0, 50)}...`);
    }

    if (!res.ok) {
        throw new Error(json?.error || 'Erro na API');
    }

    return json;
}

export function login(email, password) {
    return fetchWithAuth('/auth.php?action=login', {
        method: 'POST',
        body: { email, password }
    });
}

export function register(name, email, password) {
    return fetchWithAuth('/auth.php?action=register', {
        method: 'POST',
        body: { name, email, password }
    });
}

export function getCurrentUser() {
    return fetchWithAuth('/auth.php?action=me');
}
