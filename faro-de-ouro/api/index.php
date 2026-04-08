<?php
// Handle CORS globally
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(204);
    die();
}

header('Content-Type: application/json');

$route = ltrim($_GET['route'] ?? '', '/');

// Simple router
switch ($route) {
    case 'auth':
        require 'endpoints/auth.php';
        break;
    case 'products':
        require 'endpoints/products.php';
        break;
    case 'categories':
        require 'endpoints/categories.php';
        break;
    case 'suppliers':
        require 'endpoints/suppliers.php';
        break;
    case 'superadmin':
        require 'endpoints/superadmin.php';
        break;
    case 'public':
        require 'endpoints/public.php';
        break;
    case 'suggestions':
        require 'endpoints/suggestions.php';
        break;
    case 'webhook':
        require 'endpoints/webhook.php';
        break;
    case 'users':
        require 'endpoints/users.php';
        break;
    case 'migrate':
        require 'includes/init_db.php';
        break;
    default:
        http_response_code(404);
        echo json_encode(['error' => 'Endpoint not found']);
        break;
}
