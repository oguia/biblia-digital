<?php
require 'config.php';

$user = requireAuth($pdo);
$method = $_SERVER['REQUEST_METHOD'];

// Only Admin can manage targets
if ($user['role'] !== 'admin') {
    jsonResponse(['error' => 'Forbidden'], 403);
}

switch ($method) {
    case 'GET':
        $stmt = $pdo->query("SELECT * FROM targets ORDER BY created_at DESC");
        $targets = $stmt->fetchAll(PDO::FETCH_ASSOC);
        jsonResponse(['targets' => $targets]);
        break;

    case 'POST':
        $input = json_decode(file_get_contents('php://input'), true);
        $url = $input['url'] ?? '';
        $type = $input['type'] ?? 'unknown';

        if (empty($url)) jsonResponse(['error' => 'URL required'], 400);

        try {
            $stmt = $pdo->prepare("INSERT INTO targets (url, type, status) VALUES (?, ?, 'active')");
            $stmt->execute([$url, $type]);
            jsonResponse(['message' => 'Target added', 'id' => $pdo->lastInsertId()]);
        } catch (PDOException $e) {
            jsonResponse(['error' => 'Duplicate or DB Error'], 409);
        }
        break;

    case 'PUT':
        $id = $_GET['id'] ?? null;
        if (!$id) jsonResponse(['error' => 'ID required'], 400);

        $input = json_decode(file_get_contents('php://input'), true);
        $status = $input['status'] ?? null;

        if ($status) {
            $stmt = $pdo->prepare("UPDATE targets SET status = ? WHERE id = ?");
            $stmt->execute([$status, $id]);
        }
        jsonResponse(['message' => 'Target updated']);
        break;

    case 'DELETE':
        $id = $_GET['id'] ?? null;
        if (!$id) jsonResponse(['error' => 'ID required'], 400);

        $stmt = $pdo->prepare("DELETE FROM targets WHERE id = ?");
        $stmt->execute([$id]);
        jsonResponse(['message' => 'Target deleted']);
        break;

    default:
        jsonResponse(['error' => 'Method not allowed'], 405);
}
?>