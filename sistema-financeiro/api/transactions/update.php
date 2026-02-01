<?php
require_once '../cors.php';
require_once '../config.php';
require_once '../auth_helper.php';

if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    http_response_code(405);
    exit;
}

$userId = requireAuth();
$input = getJsonInput();
$id = $input['id'] ?? null;
$status = $input['status'] ?? null;

if (!$id || !in_array($status, ['paid', 'pending'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Dados inválidos']);
    exit;
}

$pdo = getDB();
// Garante que só atualiza se pertencer ao usuário logado
$stmt = $pdo->prepare("UPDATE transactions SET status = ? WHERE id = ? AND user_id = ?");

try {
    $stmt->execute([$status, $id, $userId]);
    if ($stmt->rowCount() > 0) {
        echo json_encode(['message' => 'Status atualizado']);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Transação não encontrada']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro ao atualizar']);
}
?>
