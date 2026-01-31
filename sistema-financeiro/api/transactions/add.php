<?php
require_once '../cors.php';
require_once '../config.php';
require_once '../auth_helper.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}

$userId = requireAuth();
$input = getJsonInput();

$type = $input['type'] ?? ''; // 'income' or 'expense'
$amount = $input['amount'] ?? 0;
$description = $input['description'] ?? '';
$category = $input['category'] ?? 'Outros';
$date = $input['date'] ?? date('Y-m-d');

if (!in_array($type, ['income', 'expense']) || $amount <= 0 || !$description) {
    http_response_code(400);
    echo json_encode(['error' => 'Dados inválidos']);
    exit;
}

$pdo = getDB();
$stmt = $pdo->prepare("INSERT INTO transactions (user_id, type, amount, description, category, date) VALUES (?, ?, ?, ?, ?, ?)");

try {
    $stmt->execute([$userId, $type, $amount, $description, $category, $date]);
    http_response_code(201);
    echo json_encode(['message' => 'Lançamento adicionado', 'id' => $pdo->lastInsertId()]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro ao salvar lançamento']);
}
?>
