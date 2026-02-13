<?php
require 'config.php';

$user = requireAuth($pdo);
// Only Admin for manual submission updates
if ($user['role'] !== 'admin') {
    jsonResponse(['error' => 'Forbidden'], 403);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    $projectId = $input['project_id'] ?? null;
    $targetId = $input['target_id'] ?? null;
    $status = $input['status'] ?? 'pending'; // 'completed', 'failed'
    $liveUrl = $input['live_url'] ?? null;
    $aiContent = $input['ai_content'] ?? null;

    if (!$projectId || !$targetId) {
        jsonResponse(['error' => 'Project ID and Target ID required'], 400);
    }

    $stmt = $pdo->prepare("INSERT INTO submissions (project_id, target_id, status, live_url, ai_generated_content) VALUES (?, ?, ?, ?, ?)");
    if ($stmt->execute([$projectId, $targetId, $status, $liveUrl, $aiContent])) {
        if ($status === 'completed') {
            // Deduct 1 credit (only if user is NOT admin)
            $deduct = $pdo->prepare("UPDATE users u JOIN projects p ON p.user_id = u.id SET u.credits = u.credits - 1 WHERE p.id = ? AND u.credits > 0 AND u.role != 'admin'");
            $deduct->execute([$projectId]);
        }
        jsonResponse(['message' => 'Submission recorded']);
    } else {
        jsonResponse(['error' => 'Failed to record submission'], 500);
    }
}
?>