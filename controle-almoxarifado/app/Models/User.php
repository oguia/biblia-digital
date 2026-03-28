<?php

class User {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getConnection();
    }

    public function checkCredentials($email, $password) {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE email = :email LIMIT 1");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['senha'])) {
            return $user;
        }
        return false;
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT id, nome, email, nivel, criado_em FROM usuarios WHERE id = :id LIMIT 1");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }
}
