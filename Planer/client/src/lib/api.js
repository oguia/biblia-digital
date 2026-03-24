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
    const res = await fetch(`${API_BASE}/${cleanUrl}`, options);

    if (res.status === 401) {
        // Logout logic if token expired
        localStorage.removeItem('token');
        localStorage.removeItem('user');
        window.location.href = '#/login';
        throw new Error('Unauthorized');
    }

    const json = await res.json();
    if (!res.ok) {
        throw new Error(json.error || 'API Error');
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
