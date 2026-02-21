<?php

class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        $config = require __DIR__ . '/../../config/config.php';
        $db = $config['db'];

        if (getenv('DB_CONNECTION') === 'sqlite') {
            $dsn = "sqlite:" . getenv('DB_DATABASE'); // Path to sqlite file
            $user = null;
            $pass = null;
        } else {
            $dsn = "mysql:host={$db['host']};dbname={$db['name']};charset={$db['charset']}";
            $user = $db['user'];
            $pass = $db['pass'];
        }

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $user, $pass, $options);
        } catch (\PDOException $e) {
            // In production, log error and show generic message
            error_log($e->getMessage());
            die("Database connection error. Please check logs.");
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->pdo;
    }

    public function __call($method, $args) {
        return call_user_func_array([$this->pdo, $method], $args);
    }
}
