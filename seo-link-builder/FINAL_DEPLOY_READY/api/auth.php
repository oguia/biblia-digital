<?php
require 'config.php';

// Parse JSON input
$input = json_decode(file_get_contents('php://input'), true);
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'register':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            jsonResponse(['error' => 'Method not allowed'], 405);
        }

        $email = $input['email'] ?? '';
        $password = $input['password'] ?? '';

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 6) {
            jsonResponse(['error' => 'Invalid email or password (min 6 chars)'], 400);
        }

        // Check if user exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            jsonResponse(['error' => 'Email already registered'], 409);
        }

        // Create user
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (email, password_hash, credits) VALUES (?, ?, 0)");
        if ($stmt->execute([$email, $hash])) {
            $_SESSION['user_id'] = $pdo->lastInsertId();
            jsonResponse(['message' => 'User registered successfully', 'user' => getCurrentUser($pdo)]);
        } else {
            jsonResponse(['error' => 'Registration failed'], 500);
        }
        break;

    case 'login':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            jsonResponse(['error' => 'Method not allowed'], 405);
        }

        $email = $input['email'] ?? '';
        $password = $input['password'] ?? '';

        $stmt = $pdo->prepare("SELECT id, password_hash FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            jsonResponse(['message' => 'Login successful', 'user' => getCurrentUser($pdo)]);
        } else {
            jsonResponse(['error' => 'Invalid credentials'], 401);
        }
        break;

    case 'logout':
        session_destroy();
        jsonResponse(['message' => 'Logged out']);
        break;

    case 'me':
        $user = getCurrentUser($pdo);
        if ($user) {
            jsonResponse(['user' => $user]);
        } else {
            jsonResponse(['user' => null], 200); // Return null instead of 401 for frontend check
        }
        break;

    default:
        jsonResponse(['error' => 'Invalid action'], 400);
}
?>