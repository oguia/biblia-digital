<?php
// api/projects.php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

require 'db.php';

$user = getAuthUser($pdo);
if (!$user) {
    sendJson(['error' => 'Unauthorized'], 401);
}

// Block access if plan expired
if (intval($user['is_admin']) !== 1 && (!isset($user['plan_expires_at']) || strtotime($user['plan_expires_at']) < time())) {
    sendJson(['error' => 'Sua assinatura expirou. Acesse a página de planos para renovar.'], 403);
}

$action = $_GET['action'] ?? '';
$input = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // List projects
    $stmt = $pdo->prepare("SELECT * FROM projects WHERE user_id = ? ORDER BY active DESC, created_at DESC");
    $stmt->execute([$user['id']]);
    sendJson(['projects' => $stmt->fetchAll()]);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Create project
    if (empty($input['name'])) {
        sendJson(['error' => 'Project name is required'], 400);
    }

    $desc = $input['description'] ?? '';
    $rate_type = $input['rate_type'] ?? 'hourly';
    $rate_amount = floatval($input['rate_amount'] ?? 0);
    $expected_hours = floatval($input['expected_hours'] ?? 8.0);

    $stmt = $pdo->prepare("INSERT INTO projects (user_id, name, description, rate_type, rate_amount, expected_hours) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$user['id'], $input['name'], $desc, $rate_type, $rate_amount, $expected_hours]);

    sendJson(['id' => $pdo->lastInsertId(), 'success' => true]);
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Update project
    $id = intval($_GET['id'] ?? 0);
    if (!$id || empty($input['name'])) {
        sendJson(['error' => 'Invalid data'], 400);
    }

    $stmt = $pdo->prepare("SELECT * FROM projects WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $user['id']]);
    if (!$stmt->fetch()) {
        sendJson(['error' => 'Project not found'], 404);
    }

    $desc = $input['description'] ?? '';
    $rate_type = $input['rate_type'] ?? 'hourly';
    $rate_amount = floatval($input['rate_amount'] ?? 0);
    $expected_hours = floatval($input['expected_hours'] ?? 8.0);
    $active = isset($input['active']) ? intval($input['active']) : 1;

    $stmt = $pdo->prepare("UPDATE projects SET name = ?, description = ?, rate_type = ?, rate_amount = ?, expected_hours = ?, active = ? WHERE id = ? AND user_id = ?");
    $stmt->execute([$input['name'], $desc, $rate_type, $rate_amount, $expected_hours, $active, $id, $user['id']]);

    sendJson(['success' => true]);
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Delete project
    $id = intval($_GET['id'] ?? 0);
    $stmt = $pdo->prepare("DELETE FROM projects WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $user['id']]);

    sendJson(['success' => true]);
}
