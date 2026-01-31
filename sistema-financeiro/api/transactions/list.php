<?php
require_once '../cors.php';
require_once '../config.php';
require_once '../auth_helper.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    exit;
}

$userId = requireAuth();
$pdo = getDB();

// Filtros opcionais (mes/ano) podem ser adicionados via $_GET
$stmt = $pdo->prepare("SELECT * FROM transactions WHERE user_id = ? ORDER BY date DESC, created_at DESC LIMIT 100");
$stmt->execute([$userId]);
$transactions = $stmt->fetchAll();

echo json_encode($transactions);
?>
