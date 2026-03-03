<?php
require_once 'db.php';

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $categoria = isset($_GET['categoria']) ? $_GET['categoria'] : null;
        $busca = isset($_GET['busca']) ? $_GET['busca'] : null;
        $loja = isset($_GET['loja']) ? $_GET['loja'] : null;

        $query = "SELECT * FROM ofertas WHERE status = 'ativo'";
        $params = [];

        if ($categoria && $categoria !== 'Todas') {
            $query .= " AND categoria = ?";
            $params[] = $categoria;
        }

        if ($busca) {
            $query .= " AND (titulo LIKE ? OR loja LIKE ?)";
            $params[] = '%' . $busca . '%';
            $params[] = '%' . $busca . '%';
        }

        if ($loja && $loja !== 'Todas') {
            $query .= " AND loja = ?";
            $params[] = $loja;
        }

        $query .= " ORDER BY data_publicacao DESC LIMIT 100";

        $stmt = $db->prepare($query);
        $stmt->execute($params);
        $ofertas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($ofertas);
        break;

    case 'POST':
        // Cadastro manual de ofertas por lojistas
        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['titulo'], $data['preco'], $data['loja'], $data['categoria'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Dados incompletos']);
            exit;
        }

        $titulo = $data['titulo'];
        $preco = floatval($data['preco']);
        $loja = $data['loja'];
        $categoria = $data['categoria'];
        $imagem_url = isset($data['imagem_url']) ? $data['imagem_url'] : null;
        $id_lojista = isset($data['id_lojista']) ? $data['id_lojista'] : null;

        $status = $id_lojista ? 'pendente_pagamento' : 'ativo'; // Se for lojista, exige pagamento

        try {
            $stmt = $db->prepare("INSERT INTO ofertas (titulo, preco, loja, imagem_url, categoria, fonte, id_lojista, status) VALUES (?, ?, ?, ?, ?, 'manual', ?, ?)");
            $stmt->execute([$titulo, $preco, $loja, $imagem_url, $categoria, $id_lojista, $status]);

            $oferta_id = $db->lastInsertId();

            echo json_encode([
                'success' => true,
                'oferta_id' => $oferta_id,
                'status' => $status,
                'message' => 'Oferta cadastrada com sucesso'
            ]);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Erro ao cadastrar oferta: ' . $e->getMessage()]);
        }
        break;
}
?>