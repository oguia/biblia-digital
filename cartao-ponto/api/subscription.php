<?php
// api/subscription.php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    die();
}

require 'db.php';

$user = getAuthUser($pdo);
if (!$user) {
    sendJson(['error' => 'Unauthorized'], 401);
}

$action = $_GET['action'] ?? '';
$input = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'redeem_invite') {
        if (empty($input['code'])) {
            sendJson(['error' => 'Código não fornecido'], 400);
        }

        $code = strtoupper(trim($input['code']));

        $stmt = $pdo->prepare("SELECT * FROM invites WHERE code = ?");
        $stmt->execute([$code]);
        $invite = $stmt->fetch();

        if (!$invite) {
            sendJson(['error' => 'Código de convite inválido'], 404);
        }

        if ($invite['used_by']) {
            sendJson(['error' => 'Este código já foi utilizado'], 400);
        }

        // Grant lifetime access (set expiry to way in the future)
        $lifetime = '2099-12-31 23:59:59';

        try {
            $pdo->beginTransaction();

            // Mark invite as used
            $stmt = $pdo->prepare("UPDATE invites SET used_by = ?, used_at = CURRENT_TIMESTAMP WHERE id = ?");
            $stmt->execute([$user['id'], $invite['id']]);

            // Update user plan
            $stmt = $pdo->prepare("UPDATE users SET plan_expires_at = ? WHERE id = ?");
            $stmt->execute([$lifetime, $user['id']]);

            $pdo->commit();
            sendJson(['success' => true, 'plan_expires_at' => $lifetime]);
        } catch (Exception $e) {
            $pdo->rollBack();
            sendJson(['error' => 'Erro ao resgatar o código'], 500);
        }
    }
}

sendJson(['error' => 'Invalid action'], 400);
