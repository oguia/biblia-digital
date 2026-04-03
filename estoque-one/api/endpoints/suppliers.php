<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';

$user = require_auth();
$tenant_id = require_tenant($user);
$db = getDB();
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

if ($method == 'GET' && $action == 'list') {
    $stmt = $db->prepare("SELECT * FROM suppliers WHERE tenant_id = ?");
    $stmt->execute([$tenant_id]);
    echo json_encode($stmt->fetchAll());
} elseif ($method == 'POST' && $action == 'create') {
    $data = json_decode(file_get_contents('php://input'), true);
    $stmt = $db->prepare("INSERT INTO suppliers (tenant_id, name, contact) VALUES (?, ?, ?)");
    $stmt->execute([$tenant_id, $data['name'], $data['contact'] ?? '']);
    echo json_encode(['success' => true]);
}
