<?php
// Planer/api/profile.php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

require_once 'db.php';
require_once 'auth.php'; // Includes verify_auth_token

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

$user = verify_auth_token($db);
$action = $_GET['action'] ?? '';
$input = json_decode(file_get_contents('php://input'), true);

if ($action === 'redeem_code') {
    if (!isset($input['code'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Código não fornecido']);
        exit;
    }

    $code = strtoupper(trim($input['code']));

    $db->beginTransaction();
    try {
        // Check if code exists and is unused
        $stmt = $db->prepare("SELECT * FROM invite_codes WHERE code = ? AND used_by IS NULL");
        $stmt->execute([$code]);
        $invite = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$invite) {
            $db->rollBack();
            http_response_code(400);
            echo json_encode(['error' => 'Código inválido ou já utilizado.']);
            exit;
        }

        // Update invite code as used
        $updateInvite = $db->prepare("UPDATE invite_codes SET used_by = ?, used_at = CURRENT_TIMESTAMP WHERE id = ?");
        $updateInvite->execute([$user['id'], $invite['id']]);

        // Update user's plan
        $duration = $invite['plan_duration_days'];
        $new_expires = date('Y-m-d H:i:s', strtotime("+$duration days"));
        $plan_type = $duration > 3650 ? 'lifetime' : 'premium'; // If more than 10 years, consider it lifetime

        $updateUser = $db->prepare("UPDATE users SET plan_type = ?, plan_expires_at = ? WHERE id = ?");
        $updateUser->execute([$plan_type, $new_expires, $user['id']]);

        $db->commit();
        echo json_encode(['success' => true, 'message' => 'Código resgatado com sucesso! Plano atualizado.']);
    } catch (Exception $e) {
        $db->rollBack();
        http_response_code(500);
        echo json_encode(['error' => 'Erro interno ao resgatar código.']);
    }
} elseif ($action === 'update_profile') {
    if (!isset($input['name']) || !isset($input['email'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Nome e Email são obrigatórios.']);
        exit;
    }

    $name = trim($input['name']);
    $email = trim($input['email']);
    $password = $input['password'] ?? '';

    // Check if email is being changed and if it already exists
    if ($email !== $user['email']) {
        $stmt = $db->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->execute([$email, $user['id']]);
        if ($stmt->fetch()) {
            http_response_code(409);
            echo json_encode(['error' => 'Este email já está em uso por outra conta.']);
            exit;
        }
    }

    try {
        if (!empty($password)) {
            // Update with new password
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $db->prepare("UPDATE users SET name = ?, email = ?, password = ? WHERE id = ?");
            $stmt->execute([$name, $email, $hashed, $user['id']]);
        } else {
            // Update without changing password
            $stmt = $db->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
            $stmt->execute([$name, $email, $user['id']]);
        }

        echo json_encode(['success' => true, 'message' => 'Perfil atualizado com sucesso.']);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Erro ao atualizar perfil.']);
    }
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Unknown action']);
}
