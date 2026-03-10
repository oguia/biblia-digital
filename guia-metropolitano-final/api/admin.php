<?php
require_once 'config.php';

header('Content-Type: application/json');

// Mock Admin Auth (In production, use session/JWT)
$headers = getallheaders();
$adminSecret = $headers['X-Admin-Secret'] ?? '';

// Simple hardcoded secret for MVP
if ($adminSecret !== 'guia-admin-secret-123') {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents('php://input'), true);

if ($method === 'GET') {
    // List all businesses for moderation
    try {
        $stmt = $pdo->query("
            SELECT b.*, c.name as category_name, u.email as owner_email
            FROM businesses b
            LEFT JOIN categories c ON b.category_id = c.id
            LEFT JOIN users u ON b.user_id = u.id
            ORDER BY b.created_at DESC
        ");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}

if ($method === 'POST') {
    // Actions: verify, feature, delete
    $action = $data['action'] ?? '';
    $id = $data['id'] ?? null;

    if (!$id) {
        http_response_code(400); echo json_encode(['error' => 'Missing ID']); exit;
    }

    try {
        if ($action === 'verify') {
            $stmt = $pdo->prepare("UPDATE businesses SET is_verified = 1 WHERE id = ?");
            $stmt->execute([$id]);
        } elseif ($action === 'unverify') {
            $stmt = $pdo->prepare("UPDATE businesses SET is_verified = 0 WHERE id = ?");
            $stmt->execute([$id]);
        } elseif ($action === 'feature') {
            $stmt = $pdo->prepare("UPDATE businesses SET is_featured = 1 WHERE id = ?");
            $stmt->execute([$id]);
        } elseif ($action === 'unfeature') {
            $stmt = $pdo->prepare("UPDATE businesses SET is_featured = 0 WHERE id = ?");
            $stmt->execute([$id]);
        } elseif ($action === 'delete') {
            $stmt = $pdo->prepare("DELETE FROM businesses WHERE id = ?");
            $stmt->execute([$id]);
        } else {
            http_response_code(400); echo json_encode(['error' => 'Invalid action']); exit;
        }
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}
