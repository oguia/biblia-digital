CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    password_hash TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS categories (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL UNIQUE,
    slug TEXT NOT NULL UNIQUE,
    icon TEXT DEFAULT 'search',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS businesses (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER,
    name TEXT NOT NULL,
    slug TEXT NOT NULL UNIQUE,
    description TEXT,
    category_id INTEGER,
    address TEXT,
    city TEXT DEFAULT 'Curitiba',
    state TEXT DEFAULT 'PR',
    lat REAL,
    lng REAL,
    phone TEXT,
    whatsapp TEXT,
    website TEXT,
    image_url TEXT,
    google_place_id TEXT UNIQUE,
    views INTEGER DEFAULT 0,
    whatsapp_clicks INTEGER DEFAULT 0,
    phone_clicks INTEGER DEFAULT 0,
    is_verified INTEGER DEFAULT 0,
    is_featured INTEGER DEFAULT 0,
    plan_tier TEXT DEFAULT 'free',
    plan_expires_at DATETIME,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

CREATE TABLE IF NOT EXISTS coupons (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    business_id INTEGER NOT NULL,
    title TEXT NOT NULL,
    description TEXT,
    code TEXT,
    discount_value TEXT,
    valid_until DATE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (business_id) REFERENCES businesses(id)
);

CREATE TABLE IF NOT EXISTS leads (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    business_id INTEGER NOT NULL,
    type TEXT NOT NULL,
    ip_address TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (business_id) REFERENCES businesses(id)
);

-- Pre-populate some core categories
INSERT OR IGNORE INTO categories (name, slug, icon) VALUES
('Restaurantes', 'restaurantes', 'utensils'),
('Advogados', 'advogados', 'scale'),
('Mecânicas', 'mecanicas', 'wrench'),
('Salões de Beleza', 'saloes-de-beleza', 'scissors'),
('Pet Shops', 'pet-shops', 'paw'),
('Encanadores', 'encanadores', 'droplet'),
('Eletricistas', 'eletricistas', 'zap'),
('Clínicas', 'clinicas', 'stethoscope');
