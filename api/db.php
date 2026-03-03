<?php
// Configurações e conexão centralizada com o banco
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$db_file = __DIR__ . '/database.sqlite';
$is_new_db = !file_exists($db_file);

try {
    $db = new PDO("sqlite:$db_file");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($is_new_db) {
        $db->exec("
            CREATE TABLE lojistas (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                nome_fantasia TEXT NOT NULL,
                email TEXT UNIQUE NOT NULL,
                senha TEXT NOT NULL,
                telefone TEXT,
                data_cadastro DATETIME DEFAULT CURRENT_TIMESTAMP
            );

            CREATE TABLE ofertas (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                titulo TEXT NOT NULL,
                preco REAL NOT NULL,
                loja TEXT NOT NULL,
                imagem_url TEXT,
                categoria TEXT,
                data_publicacao DATETIME DEFAULT CURRENT_TIMESTAMP,
                data_validade DATETIME,
                fonte TEXT, -- 'auto' ou 'manual'
                id_lojista INTEGER,
                status TEXT DEFAULT 'ativo', -- ativo, pendente_pagamento
                FOREIGN KEY(id_lojista) REFERENCES lojistas(id)
            );

            CREATE TABLE pagamentos (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                id_oferta INTEGER NOT NULL,
                id_lojista INTEGER NOT NULL,
                mercado_pago_id TEXT,
                status TEXT DEFAULT 'pendente', -- pendente, aprovado, recusado
                valor REAL NOT NULL,
                data_criacao DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY(id_oferta) REFERENCES ofertas(id),
                FOREIGN KEY(id_lojista) REFERENCES lojistas(id)
            );
        ");

        // Inserir alguns dados de teste
        $db->exec("
            INSERT INTO ofertas (titulo, preco, loja, categoria, fonte) VALUES
            ('Arroz Branco 5kg', 24.90, 'Condor', 'Supermercado', 'auto'),
            ('Cimento Votorantim 50kg', 32.50, 'Balaroti', 'Construção', 'auto');
        ");
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Erro de conexão com o banco de dados: " . $e->getMessage()]);
    exit;
}
?>