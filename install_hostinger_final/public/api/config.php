<?php
// Configuration for Database Connection

// Default to localhost for development. Change these for production.
define('DB_HOST', 'localhost');
define('DB_NAME', 'biblia_maisdeus');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// CORS Settings - Allow all for development, restrict for production
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}
?>
