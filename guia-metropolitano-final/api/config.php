<?php
// guia-metropolitano/api/config.php

// Allow CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'guia_metropolitano');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_FILE', __DIR__ . '/guia.sqlite'); // SQLite fallback file

// Gemini API Key
define('GEMINI_API_KEY', getenv('GEMINI_API_KEY') ?: 'YOUR_GEMINI_API_KEY_HERE');

// Mercado Pago
define('MP_ACCESS_TOKEN', getenv('MP_ACCESS_TOKEN') ?: 'YOUR_MP_ACCESS_TOKEN');
define('MP_PUBLIC_KEY', getenv('MP_PUBLIC_KEY') ?: 'YOUR_MP_PUBLIC_KEY');

try {
    // Try MySQL first
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Fallback to SQLite if MySQL fails
    try {
        $pdo = new PDO("sqlite:" . DB_FILE);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Enable foreign keys for SQLite
        $pdo->exec("PRAGMA foreign_keys = ON;");

        // Check if tables exist, if not, apply schema (adapted for SQLite)
        $result = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='businesses'");
        if (!$result->fetch()) {
            // Apply schema manually or via apply_schema.php logic
            // Ideally call a function to init schema
        }
    } catch (PDOException $e2) {
        // If both fail
        // echo json_encode(["error" => "Database connection failed (MySQL & SQLite): " . $e2->getMessage()]);
        // exit;
    }
}
