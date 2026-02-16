<?php

$dbFile = __DIR__ . '/ogm_verification.sqlite';
if (file_exists($dbFile)) unlink($dbFile);

try {
    $pdo = new PDO("sqlite:$dbFile");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create Tables
    $pdo->exec("CREATE TABLE users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT,
            email TEXT UNIQUE,
            password TEXT,
            role TEXT DEFAULT 'owner',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )");

    $pdo->exec("CREATE TABLE categories (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT,
            slug TEXT,
            views INTEGER DEFAULT 0
        )");

    $pdo->exec("CREATE TABLE neighborhoods (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT,
            slug TEXT,
            city TEXT
        )");

    $pdo->exec("CREATE TABLE companies (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER,
            category_id INTEGER,
            neighborhood_id INTEGER,
            name TEXT,
            slug TEXT,
            description TEXT,
            address TEXT,
            number TEXT,
            zip_code TEXT,
            phone TEXT,
            whatsapp TEXT,
            latitude REAL,
            longitude REAL,
            image_url TEXT,
            status TEXT DEFAULT 'active',
            is_featured INTEGER DEFAULT 0,
            views INTEGER DEFAULT 0,
            phone_clicks INTEGER DEFAULT 0,
            whatsapp_clicks INTEGER DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id)
        )");

    $pdo->exec("CREATE TABLE reviews (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            company_id INTEGER,
            rating INTEGER,
            comment TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )");

    // Insert Dummy Data
    // Admin User
    $pdo->exec("INSERT INTO users (name, email, password, role) VALUES ('Admin', 'admin@oguiametropolitano.com.br', '" . password_hash('admin123', PASSWORD_BCRYPT) . "', 'admin')");

    $pdo->exec("INSERT INTO categories (name, slug) VALUES ('Restaurante', 'restaurante')");
    $pdo->exec("INSERT INTO neighborhoods (name, slug) VALUES ('Batel', 'batel')");
    $pdo->exec("INSERT INTO companies (category_id, neighborhood_id, name, slug, description, address, number, latitude, longitude, is_featured, status)
                VALUES (1, 1, 'Pizzaria Batel', 'pizzaria-batel', 'Melhor pizza do Batel', 'Av Batel', '100', -25.44, -49.29, 1, 'active')");

    echo "Verification DB created at: $dbFile\n";

} catch (PDOException $e) {
    die("DB Error: " . $e->getMessage());
}
