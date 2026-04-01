<?php
// seo-link-builder/api/config.php

// Prevent direct access to this file
if (basename(__FILE__) == basename($_SERVER['PHP_SELF'])) {
    http_response_code(403);
    die('Forbidden');
}

// Enable CORS for development
// In production, change specific origin to your domain
$origin = $_SERVER['HTTP_ORIGIN'] ?? '*';
header("Access-Control-Allow-Origin: $origin");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Credentials: true");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'u123456789_seo_db');
define('DB_USER', 'u123456789_seo_user');
define('DB_PASS', 'ChangeMe123!');

// API Keys - Replace with your actual keys
// Tries to get from environment first (better security), falls back to empty string
define('GEMINI_API_KEY', getenv('GEMINI_API_KEY') ?: '');
define('MERCADO_PAGO_ACCESS_TOKEN', getenv('MERCADO_PAGO_ACCESS_TOKEN') ?: '');
define('MERCADO_PAGO_PUBLIC_KEY', getenv('MERCADO_PAGO_PUBLIC_KEY') ?: '');

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]);
    exit;
}

// Helper function for JSON responses
function jsonResponse($data, $status = 200) {
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

// Start Session
session_set_cookie_params([
    'samesite' => 'Lax',
    'secure' => false,
    'httponly' => true
]);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Auth Helper
function getCurrentUser($pdo) {
    if (isset($_SESSION['user_id'])) {
        $stmt = $pdo->prepare("SELECT id, email, role, credits FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        return $stmt->fetch();
    }
    return null;
}

function requireAuth($pdo) {
    $user = getCurrentUser($pdo);
    if (!$user) {
        jsonResponse(['error' => 'Unauthorized'], 401);
    }
    return $user;
}
?>