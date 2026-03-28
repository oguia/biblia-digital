<?php

class Category {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getConnection();
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM categorias ORDER BY nome ASC");
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM categorias WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function getByName($nome) {
        $stmt = $this->pdo->prepare("SELECT * FROM categorias WHERE nome = :nome LIMIT 1");
        $stmt->bindParam(':nome', $nome);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function create($nome) {
        $stmt = $this->pdo->prepare("INSERT INTO categorias (nome) VALUES (:nome)");
        $stmt->bindParam(':nome', $nome);
        return $stmt->execute();
    }

    public function update($id, $nome) {
        $stmt = $this->pdo->prepare("UPDATE categorias SET nome = :nome WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nome', $nome);
        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM categorias WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
