<?php
// api/admin.php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    die();
}

require 'db.php';

$user = getAuthUser($pdo);
if (!$user || !isset($user['is_admin']) || intval($user['is_admin']) !== 1) {
    sendJson(['error' => 'Acesso negado'], 403);
}

$action = $_GET['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($action === 'invites') {
        $stmt = $pdo->prepare("
            SELECT i.*, u.name as used_by_name, u.email as used_by_email
            FROM invites i
            LEFT JOIN users u ON i.used_by = u.id
            ORDER BY i.created_at DESC
        ");
        $stmt->execute();
        sendJson(['invites' => $stmt->fetchAll()]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'generate_invite') {
        // Generate a random 10-char alphanumeric code
        $code = strtoupper(substr(md5(uniqid(rand(), true)), 0, 10));

        $stmt = $pdo->prepare("INSERT INTO invites (code, created_by) VALUES (?, ?)");
        $stmt->execute([$code, $user['id']]);

        sendJson(['success' => true, 'code' => $code]);
    }
}

sendJson(['error' => 'Invalid action'], 400);
