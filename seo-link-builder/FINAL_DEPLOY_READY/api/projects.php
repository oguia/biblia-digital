<?php
require 'config.php';

$user = requireAuth($pdo);
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $stmt = $pdo->prepare("SELECT * FROM projects WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$user['id']]);
        $projects = $stmt->fetchAll();
        jsonResponse(['projects' => $projects]);
        break;

    case 'POST':
        $input = json_decode(file_get_contents('php://input'), true);
        $url = $input['url'] ?? '';
        $keywords = $input['keywords'] ?? '';
        $desc = $input['base_description'] ?? '';

        if (empty($url)) {
            jsonResponse(['error' => 'URL is required'], 400);
        }

        $stmt = $pdo->prepare("INSERT INTO projects (user_id, url, keywords, base_description) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$user['id'], $url, $keywords, $desc])) {
            jsonResponse(['message' => 'Project created', 'id' => $pdo->lastInsertId()]);
        } else {
            jsonResponse(['error' => 'Failed to create project'], 500);
        }
        break;

    case 'DELETE':
         $id = $_GET['id'] ?? null;
         if (!$id) {
             jsonResponse(['error' => 'ID required'], 400);
         }

         $stmt = $pdo->prepare("DELETE FROM projects WHERE id = ? AND user_id = ?");
         $stmt->execute([$id, $user['id']]);
         jsonResponse(['message' => 'Project deleted']);
         break;

    default:
        jsonResponse(['error' => 'Method not allowed'], 405);
}
?>