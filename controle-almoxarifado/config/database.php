<?php
require_once __DIR__ . '/config.php';

class Database {
    private static $connection = null;

    public static function getConnection() {
        if (self::$connection === null) {
            try {
                $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ];

                self::$connection = new PDO($dsn, DB_USER, DB_PASS, $options);
            } catch (PDOException $e) {
                // Em produção, não exibir detalhes do erro diretamente
                error_log("Erro de conexão: " . $e->getMessage());
                die("Erro ao conectar ao banco de dados. Verifique o arquivo config/config.php.");
            }
        }
        return self::$connection;
    }
}
