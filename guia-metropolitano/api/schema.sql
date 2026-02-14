CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    slug VARCHAR(100) NOT NULL UNIQUE,
    icon VARCHAR(50) DEFAULT 'search',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS businesses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT DEFAULT NULL, -- Nullable if scraped/unclaimed
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    description TEXT,
    category_id INT,
    address VARCHAR(255),
    city VARCHAR(100) DEFAULT 'Curitiba',
    state VARCHAR(2) DEFAULT 'PR',
    lat DECIMAL(10, 8),
    lng DECIMAL(11, 8),
    phone VARCHAR(20),
    whatsapp VARCHAR(20),
    website VARCHAR(255),
    image_url VARCHAR(255),
    is_verified BOOLEAN DEFAULT FALSE,
    is_featured BOOLEAN DEFAULT FALSE,
    plan_tier ENUM('free', 'basic', 'premium') DEFAULT 'free',
    plan_expires_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

CREATE TABLE IF NOT EXISTS leads (
    id INT AUTO_INCREMENT PRIMARY KEY,
    business_id INT NOT NULL,
    type ENUM('whatsapp_click', 'call_click', 'view') NOT NULL,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (business_id) REFERENCES businesses(id)
);

-- Pre-populate some core categories
INSERT IGNORE INTO categories (name, slug, icon) VALUES
('Restaurantes', 'restaurantes', 'utensils'),
('Advogados', 'advogados', 'scale'),
('Mecânicas', 'mecanicas', 'wrench'),
('Salões de Beleza', 'saloes-de-beleza', 'scissors'),
('Pet Shops', 'pet-shops', 'paw'),
('Encanadores', 'encanadores', 'droplet'),
('Eletricistas', 'eletricistas', 'zap'),
('Clínicas', 'clinicas', 'stethoscope');
