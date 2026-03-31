<?php
require_once 'db.php';

function respond($data, $status = 200) {
    http_response_code($status);
    echo json_encode($data);
    exit;
}

$headers = getallheaders();
$authHeader = $headers['Authorization'] ?? '';
$token = str_replace('Bearer ', '', $authHeader);

if (!$token) respond(['error' => 'Não autorizado'], 401);

$payload = json_decode(base64_decode($token), true);
if (!$payload || !isset($payload['user_id'])) respond(['error' => 'Token inválido'], 401);

$userId = $payload['user_id'];
$db = DB::getInstance()->getConnection();
$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

if ($method === 'POST') {
    $newName = $input['name'] ?? null;
    $newEmail = $input['email'] ?? null;
    $newPassword = $input['password'] ?? null;

    if (!$newName || !$newEmail) respond(['error' => 'Nome e Email são obrigatórios'], 400);

    // Check if new email is already taken by someone else
    $stmt = $db->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
    $stmt->execute([$newEmail, $userId]);
    if ($stmt->fetch()) {
        respond(['error' => 'Este email já está em uso.'], 400);
    }

    if ($newPassword) {
        $hash = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $db->prepare("UPDATE users SET name = ?, email = ?, password_hash = ? WHERE id = ?");
        $stmt->execute([$newName, $newEmail, $hash, $userId]);
    } else {
        $stmt = $db->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
        $stmt->execute([$newName, $newEmail, $userId]);
    }

    // Fetch updated user to send back
    $stmt = $db->prepare("SELECT id, name, email, role, plan, trial_ends_at FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $updatedUser = $stmt->fetch();

    respond(['message' => 'Perfil atualizado com sucesso!', 'user' => $updatedUser]);
}

respond(['error' => 'Method not allowed'], 405);
