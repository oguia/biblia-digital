<?php
// Configuração do Banco de Dados
define('DB_HOST', 'localhost');
define('DB_NAME', 'u123456789_financeiro'); // Exemplo Hostinger
define('DB_USER', 'u123456789_admin');      // Exemplo Hostinger
define('DB_PASS', 'SuaSenhaForte123!');

function getDB() {
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        return new PDO($dsn, DB_USER, DB_PASS, $options);
    } catch (PDOException $e) {
        // Em produção, não exiba o erro detalhado
        http_response_code(500);
        echo json_encode(["error" => "Erro de conexão com o banco de dados"]);
        exit;
    }
}
?>
