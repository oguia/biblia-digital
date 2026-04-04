<?php
require_once __DIR__ . '/../includes/config.php';

$db = getDB();
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

if ($method == 'GET' && $action == 'plans') {
    $stmt = $db->query("SELECT * FROM plans");
    echo json_encode($stmt->fetchAll());
} elseif ($method == 'GET' && $action == 'debug_users') {
    // SECURITY WARNING: remove in production. Adding temporarily to debug issue.
    $stmt = $db->query("SELECT id, email, role, password FROM users");
    echo json_encode($stmt->fetchAll());
}
