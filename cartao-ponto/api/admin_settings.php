<?php
require 'db.php';

$user = getAuthUser($pdo);
if (!$user || $user['is_admin'] != 1) {
    sendJson(['error' => 'Unauthorized access'], 403);
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $stmt = $pdo->query("SELECT * FROM settings");
    $settings_raw = $stmt->fetchAll();

    $settings = [
        'mp_access_token' => ''
    ];

    foreach ($settings_raw as $row) {
        $settings[$row['key']] = $row['value'];
    }

    sendJson(['settings' => $settings]);
}

if ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['mp_access_token'])) {
        $stmt = $pdo->prepare("INSERT INTO settings (key, value) VALUES ('mp_access_token', ?)
                               ON CONFLICT(key) DO UPDATE SET value = excluded.value");
        $stmt->execute([trim($data['mp_access_token'])]);

        sendJson(['message' => 'Configurações salvas com sucesso']);
    }

    sendJson(['error' => 'Dados inválidos'], 400);
}
