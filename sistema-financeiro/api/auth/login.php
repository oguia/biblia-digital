<?php
require_once '../cors.php';
require_once '../config.php';
require_once '../auth_helper.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método não permitido']);
    exit;
}

$input = getJsonInput();
$email = $input['email'] ?? '';
$password = $input['password'] ?? '';

if (!$email || !$password) {
    http_response_code(400);
    echo json_encode(['error' => 'Preencha email e senha']);
    exit;
}

$pdo = getDB();

$stmt = $pdo->prepare("SELECT id, name, password FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password'])) {
    // Gerar token
    $payload = [
        'user_id' => $user['id'],
        'name' => $user['name'],
        'exp' => time() + (60 * 60 * 24 * 7) // Expira em 7 dias
    ];
    $token = generateJWT($payload);

    echo json_encode([
        'token' => $token,
        'user' => [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $email
        ]
    ]);
} else {
    http_response_code(401);
    echo json_encode(['error' => 'Credenciais inválidas']);
}
?>
