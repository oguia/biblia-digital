<?php
session_start();
require_once __DIR__ . '/db.php';

function registerUser($name, $email, $password) {
    global $pdo;

    // Check if email already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        return ['success' => false, 'message' => 'Este email já está cadastrado.'];
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $hash]);
        return ['success' => true, 'message' => 'Cadastro realizado com sucesso!'];
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Erro ao cadastrar usuário.'];
    }
}

function loginUser($email, $password) {
    global $pdo;

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['is_premium'] = $user['is_premium'];
        return ['success' => true, 'message' => 'Login realizado com sucesso!'];
    } else {
        return ['success' => false, 'message' => 'Email ou senha incorretos.'];
    }
}

function logoutUser() {
    session_destroy();
    header("Location: login.php");
    exit;
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isPremium() {
    return isset($_SESSION['is_premium']) && $_SESSION['is_premium'] == 1;
}

function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit;
    }
}
?>
