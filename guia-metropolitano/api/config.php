<?php
// guia-metropolitano/api/config.php

// Allow CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'guia_metropolitano');
define('DB_USER', 'root');
define('DB_PASS', '');

// Gemini API Key (Using the one from the environment or a constant placeholder)
// Ideally this should be loaded from an environment variable or a secure file.
define('GEMINI_API_KEY', getenv('GEMINI_API_KEY') ?: 'YOUR_GEMINI_API_KEY_HERE');

// Mercado Pago
define('MP_ACCESS_TOKEN', getenv('MP_ACCESS_TOKEN') ?: 'YOUR_MP_ACCESS_TOKEN');
define('MP_PUBLIC_KEY', getenv('MP_PUBLIC_KEY') ?: 'YOUR_MP_PUBLIC_KEY');

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // For local development with different credentials or no DB yet
    // echo json_encode(["error" => "Database connection failed: " . $e->getMessage()]);
    // exit;
}
