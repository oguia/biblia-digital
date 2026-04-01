<?php
require 'config.php';
require 'classes/Crawler.php';

// Auth Check (Admin only)
$user = requireAuth($pdo);
if ($user['role'] !== 'admin') {
    jsonResponse(['error' => 'Forbidden'], 403);
}

$input = json_decode(file_get_contents('php://input'), true);
$query = $input['query'] ?? '';

if (empty($query)) {
    jsonResponse(['error' => 'Query is required'], 400);
}

$crawler = new Crawler();
$results = $crawler->search($query);

// Filter out already known targets
$filtered = [];
foreach ($results as $res) {
    // Check if URL exists in DB
    $stmt = $pdo->prepare("SELECT id FROM targets WHERE url = ?");
    $stmt->execute([$res['url']]);
    if (!$stmt->fetch()) {
        $filtered[] = $res;
    }
}

jsonResponse(['results' => $filtered]);
?>