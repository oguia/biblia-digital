<?php
require_once 'db.php';
require_once 'jwt.php';

session_start();

function respond($data, $status = 200) {
    http_response_code($status);
    echo json_encode($data);
    exit;
}

$db = DB::getInstance()->getConnection();
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';
$input = json_decode(file_get_contents('php://input'), true);

if ($method === 'POST' && $action === 'register') {
    $name = $input['name'] ?? '';
    $email = $input['email'] ?? '';
    $password = $input['password'] ?? '';
    $plan = $input['plan'] ?? 'individual'; // 'individual' or 'family'

    if (!$name || !$email || !$password) {
        respond(['error' => 'Preencha todos os campos.'], 400);
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
         respond(['error' => 'Formato de email inválido.'], 400);
    }

    $domain = substr(strrchr($email, "@"), 1);
    if (!checkdnsrr($domain, "MX")) {
        respond(['error' => 'O domínio do email não parece ser válido ou não aceita emails.'], 400);
    }

    // Check if email exists
    $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        respond(['error' => 'Email já cadastrado.'], 400);
    }

    $cep = $input['cep'] ?? '';
    $city = $input['city'] ?? '';
    $state = $input['state'] ?? '';

    // 7 days trial
    $trialEndsAt = date('Y-m-d H:i:s', strtotime('+7 days'));
    $hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $db->prepare("INSERT INTO users (name, email, password_hash, role, plan, trial_ends_at, cep, city, state) VALUES (?, ?, ?, 'admin', ?, ?, ?, ?, ?)");

    if ($stmt->execute([$name, $email, $hash, $plan, $trialEndsAt, $cep, $city, $state])) {
        $userId = $db->lastInsertId();
        // The admin is their own family group
        $stmtUpdate = $db->prepare("UPDATE users SET family_group_id = ? WHERE id = ?");
        $stmtUpdate->execute([$userId, $userId]);

        // Generate simple auth token (stateless for API)
        $token = JWT::encode(['user_id' => $userId, 'role' => 'admin', 'exp' => time() + 86400 * 30]);

        respond([
            'message' => 'Conta criada com sucesso!',
            'token' => $token,
            'user' => [
                'id' => $userId,
                'name' => $name,
                'role' => 'admin',
                'plan' => $plan,
                'trial_ends_at' => $trialEndsAt
            ]
        ]);
    } else {
        respond(['error' => 'Erro ao criar conta.'], 500);
    }
}

if ($method === 'POST' && $action === 'login') {
    $email = $input['email'] ?? '';
    $password = $input['password'] ?? '';

    if (!$email || !$password) {
        respond(['error' => 'Email e senha são obrigatórios.'], 400);
    }

    $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        $token = JWT::encode(['user_id' => $user['id'], 'role' => $user['role'], 'exp' => time() + 86400 * 30]);

        unset($user['password_hash']); // Don't send hash to client

        respond([
            'message' => 'Login realizado com sucesso.',
            'token' => $token,
            'user' => $user
        ]);
    } else {
        respond(['error' => 'Credenciais inválidas.'], 401);
    }
}

respond(['error' => 'Ação inválida ou método não suportado.', 'action' => $action], 400);
