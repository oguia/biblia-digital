<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/config.php';

$user = authenticate();

// Only owners can manage users
if ($user['role'] !== 'owner') {
    http_response_code(403);
    echo json_encode(['error' => 'Acesso negado. Apenas proprietários podem gerenciar usuários.']);
    exit();
}

$tenant_id = $user['tenant_id'];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $pdo->prepare("SELECT id, name, email, role, created_at FROM users WHERE tenant_id = ?");
    $stmt->execute([$tenant_id]);
    echo json_encode(['data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
}
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (empty($data['name']) || empty($data['email']) || empty($data['password'])) {
        http_response_code(400);
        echo json_encode(['message' => 'Nome, email e senha são obrigatórios']);
        exit();
    }

    // Check if email already exists globally
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$data['email']]);
    if ($stmt->fetch()) {
        http_response_code(400);
        echo json_encode(['message' => 'Este email já está em uso por outro usuário.']);
        exit();
    }

    $role = in_array($data['role'], ['operator', 'owner']) ? $data['role'] : 'operator';
    $password_hash = password_hash($data['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (tenant_id, name, email, password_hash, role) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$tenant_id, $data['name'], $data['email'], $password_hash, $role]);

    echo json_encode(['message' => 'Usuário criado com sucesso', 'id' => $pdo->lastInsertId()]);
}
elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = $_GET['id'] ?? null;

    // Prevent owner from deleting themselves
    if ($id == $user['id']) {
        http_response_code(400);
        echo json_encode(['error' => 'Você não pode excluir sua própria conta.']);
        exit();
    }

    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ? AND tenant_id = ? AND role != 'owner'");
    $stmt->execute([$id, $tenant_id]);

    echo json_encode(['message' => 'Usuário removido com sucesso']);
}
