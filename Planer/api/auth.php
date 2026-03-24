<?php
// Planer/api/auth.php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Or specify exact origin in prod
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

function verify_auth_token($db) {
    $headers = apache_request_headers();
    $auth_header = isset($headers['Authorization']) ? $headers['Authorization'] : '';

    if (empty($auth_header)) {
        http_response_code(401);
        echo json_encode(['error' => 'Missing token']);
        exit;
    }

    $token = str_replace('Bearer ', '', $auth_header);

    $stmt = $db->prepare("SELECT * FROM users WHERE auth_token = ?");
    $stmt->execute([$token]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        http_response_code(401);
        echo json_encode(['error' => 'Invalid token']);
        exit;
    }

    return $user;
}

$action = $_GET['action'] ?? '';
$input = json_decode(file_get_contents('php://input'), true);

if ($action === 'login') {
    if (!isset($input['email']) || !isset($input['password'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing email or password']);
        exit;
    }

    $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$input['email']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($input['password'], $user['password'])) {
        $token = bin2hex(random_bytes(32));

        $updateStmt = $db->prepare("UPDATE users SET auth_token = ? WHERE id = ?");
        $updateStmt->execute([$token, $user['id']]);

        // Remove password hash from response
        unset($user['password']);
        $user['auth_token'] = $token;

        echo json_encode(['success' => true, 'user' => $user, 'token' => $token]);
    } else {
        http_response_code(401);
        echo json_encode(['error' => 'Invalid credentials']);
    }
} elseif ($action === 'register') {
    if (!isset($input['name']) || !isset($input['email']) || !isset($input['password'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing fields']);
        exit;
    }

    $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$input['email']]);
    if ($stmt->fetch()) {
        http_response_code(409);
        echo json_encode(['error' => 'Email already registered']);
        exit;
    }

    $hashed_password = password_hash($input['password'], PASSWORD_DEFAULT);

    // Default 7 days free trial
    $trial_end = date('Y-m-d H:i:s', strtotime('+7 days'));

    $stmt = $db->prepare("INSERT INTO users (name, email, password, plan_type, plan_expires_at) VALUES (?, ?, ?, 'trial', ?)");
    if ($stmt->execute([$input['name'], $input['email'], $hashed_password, $trial_end])) {
        echo json_encode(['success' => true, 'message' => 'Registration successful']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Database error']);
    }
} elseif ($action === 'me') {
    $user = verify_auth_token($db);
    unset($user['password']);
    echo json_encode(['user' => $user]);
} elseif ($action !== '') {
    http_response_code(404);
    echo json_encode(['error' => 'Unknown action']);
}
