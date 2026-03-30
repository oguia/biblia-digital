<?php
require_once '../cors.php';
require_once '../config.php';
require_once '../auth_helper.php';

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    http_response_code(405);
    exit;
}

$userId = requireAuth();
$input = getJsonInput();
$id = $input['id'] ?? $_GET['id'] ?? null;

if (!$id) {
    http_response_code(400);
    echo json_encode(['error' => 'ID da transação não fornecido']);
    exit;
}

$pdo = getDB();
// Garante que só deleta se pertencer ao usuário logado
$stmt = $pdo->prepare("DELETE FROM transactions WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $userId]);

if ($stmt->rowCount() > 0) {
    echo json_encode(['message' => 'Transação removida']);
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Transação não encontrada']);
}
?>
