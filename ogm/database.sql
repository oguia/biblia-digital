-- Database Schema for O Guia Metropolitano

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "-03:00";

-- 1. Users Table (Admins and Company Owners)
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL, -- bcrypt hash
  `role` ENUM('admin', 'owner', 'user') DEFAULT 'user',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Categories Table
CREATE TABLE IF NOT EXISTS `categories` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(150) NOT NULL UNIQUE,
  `slug` VARCHAR(150) NOT NULL UNIQUE,
  `icon` VARCHAR(50) DEFAULT NULL, -- lucide icon name
  `description` TEXT,
  `views` INT DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Neighborhoods Table (Bairros)
CREATE TABLE IF NOT EXISTS `neighborhoods` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(150) NOT NULL UNIQUE,
  `slug` VARCHAR(150) NOT NULL UNIQUE,
  `city` VARCHAR(150) DEFAULT 'Curitiba',
  `state` VARCHAR(2) DEFAULT 'PR'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Plans Table
CREATE TABLE IF NOT EXISTS `plans` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(50) NOT NULL, -- Free, Premium, Gold
  `price` DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
  `features` TEXT, -- JSON description of features
  `duration_days` INT DEFAULT 30
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Companies Table
CREATE TABLE IF NOT EXISTS `companies` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT DEFAULT NULL, -- Owner
  `category_id` INT DEFAULT NULL,
  `neighborhood_id` INT DEFAULT NULL,
  `plan_id` INT DEFAULT 1, -- Default to Free
  `name` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL UNIQUE,
  `description` TEXT,
  `address` VARCHAR(255),
  `number` VARCHAR(20),
  `zip_code` VARCHAR(20),
  `phone` VARCHAR(20),
  `whatsapp` VARCHAR(20),
  `latitude` DECIMAL(10, 8),
  `longitude` DECIMAL(11, 8),
  `image_url` VARCHAR(255), -- Main generated facade image
  `logo_url` VARCHAR(255),
  `website` VARCHAR(255),
  `status` ENUM('pending', 'active', 'blocked') DEFAULT 'active',
  `is_featured` BOOLEAN DEFAULT FALSE,
  `views` INT DEFAULT 0,
  `whatsapp_clicks` INT DEFAULT 0,
  `phone_clicks` INT DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`neighborhood_id`) REFERENCES `neighborhoods`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`plan_id`) REFERENCES `plans`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Reviews Table
CREATE TABLE IF NOT EXISTS `reviews` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `company_id` INT NOT NULL,
  `user_id` INT DEFAULT NULL, -- Nullable if anonymous reviews allowed (though usually discouraged)
  `name` VARCHAR(100), -- For guest reviews
  `rating` INT CHECK (rating BETWEEN 1 AND 5),
  `comment` TEXT,
  `is_approved` BOOLEAN DEFAULT TRUE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`company_id`) REFERENCES `companies`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. AI Logs (For RAG History)
CREATE TABLE IF NOT EXISTS `ai_logs` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_query` TEXT NOT NULL,
  `ai_response` TEXT,
  `context_used` TEXT, -- JSON dump of company IDs used in context
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert Default Plans
INSERT INTO `plans` (`name`, `price`, `features`) VALUES
('Gratuito', 0.00, '{"listing": true, "gallery": false, "priority": 0}'),
('Premium', 29.90, '{"listing": true, "gallery": true, "priority": 1, "whatsapp": true}'),
('Destaque', 59.90, '{"listing": true, "gallery": true, "priority": 2, "whatsapp": true, "homepage": true}');

-- Indexes for Search
CREATE INDEX idx_company_name ON companies(name);
CREATE INDEX idx_company_status ON companies(status);
CREATE FULLTEXT INDEX ft_company_search ON companies(name, description);
