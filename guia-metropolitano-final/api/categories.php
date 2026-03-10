<?php
require_once 'config.php';

$method = $_SERVER['REQUEST_METHOD'];

// Helper to get user ID from header (Mocked for now)
// In real app, validate JWT token here.
$userId = $_SERVER['HTTP_X_USER_ID'] ?? null;

if ($method === 'GET') {
    // List categories
    try {
        $stmt = $pdo->query("SELECT * FROM categories ORDER BY name ASC");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit;
}
