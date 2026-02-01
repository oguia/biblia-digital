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

// Busca descrições únicas usadas anteriormente
$stmtDesc = $pdo->prepare("SELECT DISTINCT description FROM transactions WHERE user_id = ? ORDER BY description ASC LIMIT 50");
$stmtDesc->execute([$userId]);
$descriptions = $stmtDesc->fetchAll(PDO::FETCH_COLUMN);

// Busca categorias únicas usadas anteriormente
$stmtCat = $pdo->prepare("SELECT DISTINCT category FROM transactions WHERE user_id = ? ORDER BY category ASC LIMIT 50");
$stmtCat->execute([$userId]);
$categories = $stmtCat->fetchAll(PDO::FETCH_COLUMN);

echo json_encode([
    'descriptions' => $descriptions,
    'categories' => $categories
]);
?>
