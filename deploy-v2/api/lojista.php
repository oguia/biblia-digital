<?php
// Script simulação de login/cadastro de lojista
require_once 'db.php';
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $action = isset($data['action']) ? $data['action'] : '';

    if ($action === 'register') {
        if (!isset($data['nome_fantasia'], $data['email'], $data['senha'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Dados incompletos']);
            exit;
        }

        $nome = $data['nome_fantasia'];
        $email = $data['email'];
        $senha = password_hash($data['senha'], PASSWORD_DEFAULT);
        $telefone = isset($data['telefone']) ? $data['telefone'] : null;

        try {
            $stmt = $db->prepare("INSERT INTO lojistas (nome_fantasia, email, senha, telefone) VALUES (?, ?, ?, ?)");
            $stmt->execute([$nome, $email, $senha, $telefone]);
            $id = $db->lastInsertId();

            echo json_encode(['success' => true, 'id_lojista' => $id, 'nome_fantasia' => $nome]);
        } catch (PDOException $e) {
            http_response_code(400); // Provavelmente email duplicado
            echo json_encode(['error' => 'Erro ao cadastrar. Email já existe?']);
        }
    } elseif ($action === 'login') {
        if (!isset($data['email'], $data['senha'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Dados incompletos']);
            exit;
        }

        $email = $data['email'];
        $senha = $data['senha'];

        $stmt = $db->prepare("SELECT id, nome_fantasia, senha FROM lojistas WHERE email = ?");
        $stmt->execute([$email]);
        $lojista = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($lojista && password_verify($senha, $lojista['senha'])) {
            echo json_encode(['success' => true, 'id_lojista' => $lojista['id'], 'nome_fantasia' => $lojista['nome_fantasia']]);
        } else {
            http_response_code(401);
            echo json_encode(['error' => 'Credenciais inválidas']);
        }
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Ação inválida']);
    }
} else {
    // Retorna mensagem padrão pra quem abrir a URL direta no navegador (evita tela em branco)
    echo json_encode(['status' => 'API Lojista Online']);
}
?>