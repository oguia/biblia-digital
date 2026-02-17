<?php

class Product {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getConnection();
    }

    public function getAll($limit = null, $offset = 0) {
        $sql = "SELECT p.*, c.nome as categoria_nome
                FROM produtos p
                LEFT JOIN categorias c ON p.categoria_id = c.id
                ORDER BY p.nome ASC";
        if ($limit) {
            $sql .= " LIMIT :limit OFFSET :offset";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        }
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    public function count() {
        return $this->pdo->query("SELECT COUNT(*) FROM produtos")->fetchColumn();
    }

    public function countLowStock() {
        return $this->pdo->query("SELECT COUNT(*) FROM produtos WHERE quantidade <= estoque_minimo")->fetchColumn();
    }

    public function sumStock() {
        return $this->pdo->query("SELECT SUM(quantidade) FROM produtos")->fetchColumn();
    }

    public function getLowStockProducts($limit = 5) {
        $stmt = $this->pdo->prepare("SELECT * FROM produtos WHERE quantidade <= estoque_minimo ORDER BY quantidade ASC LIMIT :limit");
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM produtos WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function getByCode($codigo) {
        $stmt = $this->pdo->prepare("SELECT * FROM produtos WHERE codigo = :codigo");
        $stmt->bindParam(':codigo', $codigo);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function create($data) {
        $sql = "INSERT INTO produtos (codigo, nome, descricao, categoria_id, quantidade, estoque_minimo, unidade, codigo_barras)
                VALUES (:codigo, :nome, :descricao, :categoria_id, :quantidade, :estoque_minimo, :unidade, :codigo_barras)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }

    public function update($id, $data) {
        $sql = "UPDATE produtos SET
                codigo = :codigo,
                nome = :nome,
                descricao = :descricao,
                categoria_id = :categoria_id,
                estoque_minimo = :estoque_minimo,
                unidade = :unidade,
                codigo_barras = :codigo_barras
                WHERE id = :id";
        $data['id'] = $id;
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }

    public function updateStock($id, $newQuantity) {
        $stmt = $this->pdo->prepare("UPDATE produtos SET quantidade = :quantidade WHERE id = :id");
        $stmt->bindParam(':quantidade', $newQuantity);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM produtos WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
