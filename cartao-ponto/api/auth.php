<?php
// api/auth.php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

require 'db.php';

$action = $_GET['action'] ?? '';
$input = json_decode(file_get_contents('php://input'), true);

if ($action === 'register') {
    if (empty($input['name']) || empty($input['email']) || empty($input['password'])) {
        sendJson(['error' => 'All fields are required'], 400);
    }

    $type = $input['type'] ?? 'pf';
    $hash = password_hash($input['password'], PASSWORD_DEFAULT);

    try {
        // Default plan expires in 7 days (trial)
        $expires = date('Y-m-d H:i:s', strtotime('+7 days'));

        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, type, plan_expires_at) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$input['name'], $input['email'], $hash, $type, $expires]);
        $user_id = $pdo->lastInsertId();

        $token = bin2hex(random_bytes(32));
        $stmt = $pdo->prepare("INSERT INTO sessions (user_id, token) VALUES (?, ?)");
        $stmt->execute([$user_id, $token]);

        sendJson(['token' => $token, 'user' => ['id' => $user_id, 'name' => $input['name'], 'email' => $input['email'], 'type' => $type, 'is_admin' => 0, 'plan_expires_at' => $expires]]);
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) { // UNIQUE constraint failed
            sendJson(['error' => 'Email already registered'], 400);
        }
        sendJson(['error' => 'Database error'], 500);
    }
} elseif ($action === 'login') {
    if (empty($input['email']) || empty($input['password'])) {
        sendJson(['error' => 'Email and password are required'], 400);
    }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$input['email']]);
    $user = $stmt->fetch();

    if ($user && password_verify($input['password'], $user['password'])) {
        $token = bin2hex(random_bytes(32));
        $stmt = $pdo->prepare("INSERT INTO sessions (user_id, token) VALUES (?, ?)");
        $stmt->execute([$user['id'], $token]);

        sendJson(['token' => $token, 'user' => ['id' => $user['id'], 'name' => $user['name'], 'email' => $user['email'], 'type' => $user['type'], 'is_admin' => intval($user['is_admin']), 'plan_expires_at' => $user['plan_expires_at']]]);
    } else {
        sendJson(['error' => 'Invalid credentials'], 401);
    }
} elseif ($action === 'logout') {
    $user = getAuthUser($pdo);
    if ($user) {
        $headers = apache_request_headers();
        $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';
        preg_match('/Bearer\s(\S+)/', $authHeader, $matches);
        $token = $matches[1];

        $stmt = $pdo->prepare("DELETE FROM sessions WHERE token = ?");
        $stmt->execute([$token]);
    }
    sendJson(['success' => true]);
} elseif ($action === 'me') {
    $user = getAuthUser($pdo);
    if ($user) {
        unset($user['password']);
        sendJson(['user' => $user]);
    } else {
        sendJson(['error' => 'Unauthorized'], 401);
    }
} else {
    sendJson(['error' => 'Invalid action'], 400);
}
