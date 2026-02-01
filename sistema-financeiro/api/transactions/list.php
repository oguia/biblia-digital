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

$month = $_GET['month'] ?? date('m');
$year = $_GET['year'] ?? date('Y');

// Filtro por mês e ano
$stmt = $pdo->prepare("SELECT * FROM transactions WHERE user_id = ? AND MONTH(date) = ? AND YEAR(date) = ? ORDER BY date DESC, created_at DESC");
$stmt->execute([$userId, $month, $year]);
$transactions = $stmt->fetchAll();

echo json_encode($transactions);
?>
