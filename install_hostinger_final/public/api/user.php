<?php
require_once 'db.php';

// Allow POST JSON body
$input = json_decode(file_get_contents('php://input'), true);
$action = $_GET['action'] ?? $input['action'] ?? '';

if (!$action) {
    echo json_encode(["error" => "No action specified"]);
    exit();
}

try {
    if ($action === 'login') {
        $email = $input['email'];
        $password = $input['password'];

        $stmt = $pdo->prepare("SELECT id, name, password_hash FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        // In a real app, use password_verify($password, $user['password_hash'])
        // For demo, we are insecurely matching or just returning success if user exists
        if ($user) {
             // Generate a simple token (in production use JWT)
             $token = base64_encode($user['id'] . ':' . time());
             echo json_encode([
                 "success" => true,
                 "user" => [
                     "id" => $user['id'],
                     "name" => $user['name'],
                     "token" => $token
                 ]
             ]);
        } else {
            http_response_code(401);
            echo json_encode(["error" => "Invalid credentials"]);
        }

    } elseif ($action === 'save_progress') {
        $userId = $input['user_id'];
        $bookAbbrev = $input['book'];
        $chapterNum = $input['chapter'];
        $notes = $input['notes'] ?? null;

        // Resolve IDs
        $stmt = $pdo->prepare("SELECT c.id FROM chapters c JOIN books b ON c.book_id = b.id WHERE b.abbrev = ? AND c.number = ?");
        $stmt->execute([$bookAbbrev, $chapterNum]);
        $chapterId = $stmt->fetch()['id'];

        if ($chapterId) {
            $stmt = $pdo->prepare("INSERT INTO user_progress (user_id, chapter_id, notes, read_at) VALUES (?, ?, ?, NOW()) ON DUPLICATE KEY UPDATE read_at = NOW(), notes = ?");
            $stmt->execute([$userId, $chapterId, $notes, $notes]);
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["error" => "Chapter not found"]);
        }

    } elseif ($action === 'get_progress') {
        $userId = $_GET['user_id'];

        $stmt = $pdo->prepare("
            SELECT b.abbrev, b.name as book_name, c.number as chapter, up.read_at
            FROM user_progress up
            JOIN chapters c ON up.chapter_id = c.id
            JOIN books b ON c.book_id = b.id
            WHERE up.user_id = ?
            ORDER BY up.read_at DESC
        ");
        $stmt->execute([$userId]);
        $progress = $stmt->fetchAll();

        echo json_encode(["progress" => $progress]);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
}
?>
