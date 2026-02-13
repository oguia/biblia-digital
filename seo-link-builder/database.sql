-- Database Schema for SEO Link Builder SaaS

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin', 'client') DEFAULT 'client',
    credits INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    url VARCHAR(2048) NOT NULL,
    keywords TEXT, -- Comma separated keywords
    base_description TEXT, -- Original description provided by user
    status ENUM('active', 'paused', 'completed') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS targets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    url VARCHAR(2048) NOT NULL UNIQUE,
    domain VARCHAR(255), -- Extracted domain for filtering
    type ENUM('directory', 'blog_comment', 'guest_post', 'forum', 'profile', 'unknown') DEFAULT 'unknown',
    status ENUM('pending', 'active', 'blacklisted') DEFAULT 'pending', -- 'pending' = found by crawler
    source_query VARCHAR(255), -- The search query that found this target
    submission_instructions TEXT, -- Manual notes for the admin
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS submissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    project_id INT NOT NULL,
    target_id INT NOT NULL,
    status ENUM('pending', 'processing', 'completed', 'failed') DEFAULT 'pending',
    live_url VARCHAR(2048), -- The URL where the link is actually live
    ai_generated_content TEXT, -- Unique description generated for this submission
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
    FOREIGN KEY (target_id) REFERENCES targets(id) ON DELETE CASCADE,
    UNIQUE(project_id, target_id) -- Prevent duplicate submissions to same target
);

CREATE TABLE IF NOT EXISTS transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    credits INT NOT NULL,
    payment_provider VARCHAR(50) DEFAULT 'mercadopago',
    payment_id VARCHAR(255), -- External transaction ID
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Insert default admin user (Password: admin123) - Please change immediately!
INSERT INTO users (email, password_hash, role, credits) VALUES
('admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 1000);
