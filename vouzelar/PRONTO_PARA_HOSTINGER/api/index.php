<?php
/**
 * Entry point for VouZelar API
 * Simple front controller pattern
 */

// Enable basic error reporting during dev (Disable in production!)
ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');

// Handle preflight OPTIONS requests for CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once 'db.php';

// Very basic routing based on URI
$requestUri = $_SERVER['REQUEST_URI'];
// Strip base path to get just the endpoint name
// Assuming API is deployed at /api/ (or /vouzelar/api/ locally)
$parsedUrl = parse_url($requestUri);
$path = $parsedUrl['path'];
$endpoint = basename($path);

// For testing API connectivity
if ($endpoint === 'ping' || $endpoint === 'api' || $endpoint === 'index.php') {
    echo json_encode(['status' => 'success', 'message' => 'VouZelar API is running!']);
    exit;
}

// Will add real routes in next step
http_response_code(404);
echo json_encode(['error' => 'Endpoint not found', 'path' => $path]);
