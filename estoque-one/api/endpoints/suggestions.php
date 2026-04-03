<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';

$user = require_auth();
$db = getDB();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (!empty($data['message'])) {
        $stmt = $db->prepare("INSERT INTO suggestions (user_id, message) VALUES (?, ?)");
        $stmt->execute([$user['id'], $data['message']]);
        echo json_encode(['success' => true]);
    }
}
