<?php
require 'config.php';

$user = requireAuth($pdo);
if ($user['role'] !== 'admin') {
    jsonResponse(['error' => 'Forbidden'], 403);
}

// Find a project and target pair that hasn't been submitted
// Simple strategy: Just pick one.
// Optimization: Pick projects that have credits? (For now assuming unlimited or checked elsewhere)

$sql = "
    SELECT p.id as project_id, p.url as project_url, p.keywords, p.base_description,
           t.id as target_id, t.url as target_url, t.type
    FROM projects p
    JOIN users u ON p.user_id = u.id
    CROSS JOIN targets t
    WHERE p.status = 'active'
      AND t.status = 'active'
      AND (u.credits > 0 OR u.role = 'admin')
      AND NOT EXISTS (
          SELECT 1 FROM submissions s
          WHERE s.project_id = p.id AND s.target_id = t.id
      )
    LIMIT 1
";

$stmt = $pdo->query($sql);
$task = $stmt->fetch(PDO::FETCH_ASSOC);

if ($task) {
    jsonResponse(['task' => $task]);
} else {
    // Return 200 with null task to indicate queue empty
    jsonResponse(['task' => null]);
}
?>