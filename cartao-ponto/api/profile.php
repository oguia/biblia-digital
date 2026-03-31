<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    die();
}

require 'db.php';

$user = getAuthUser($pdo);
if (!$user) {
    sendJson(['error' => 'Unauthorized'], 401);
}

$input = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    if (empty($input['name']) || empty($input['email']) || empty($input['type'])) {
        sendJson(['error' => 'Nome, e-mail e tipo são obrigatórios'], 400);
    }

    // Check if email is already taken by another user
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
    $stmt->execute([$input['email'], $user['id']]);
    if ($stmt->fetch()) {
        sendJson(['error' => 'Este e-mail já está em uso por outra conta'], 400);
    }

    try {
        if (!empty($input['password'])) {
            $hash = password_hash($input['password'], PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, type = ?, password = ? WHERE id = ?");
            $stmt->execute([$input['name'], $input['email'], $input['type'], $hash, $user['id']]);
        } else {
            $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, type = ? WHERE id = ?");
            $stmt->execute([$input['name'], $input['email'], $input['type'], $user['id']]);
        }

        // Return updated user
        $stmt = $pdo->prepare("SELECT id, name, email, type, is_admin, plan_expires_at FROM users WHERE id = ?");
        $stmt->execute([$user['id']]);
        $updatedUser = $stmt->fetch();

        sendJson(['success' => true, 'user' => $updatedUser]);
    } catch (PDOException $e) {
        sendJson(['error' => 'Erro ao atualizar o perfil: ' . $e->getMessage()], 500);
    }
} else {
    sendJson(['error' => 'Método inválido'], 405);
}
