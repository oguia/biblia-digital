<?php

class Movement {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getConnection();
    }

    public function getAll($limit = 50, $offset = 0) {
        $sql = "SELECT m.*, p.nome as produto_nome, p.codigo as produto_codigo, u.nome as usuario_nome
                FROM movimentacoes m
                JOIN produtos p ON m.produto_id = p.id
                LEFT JOIN usuarios u ON m.usuario_id = u.id
                ORDER BY m.data_movimentacao DESC
                LIMIT :limit OFFSET :offset";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getByProduct($productId, $limit = 20) {
        $sql = "SELECT m.*, u.nome as usuario_nome
                FROM movimentacoes m
                LEFT JOIN usuarios u ON m.usuario_id = u.id
                WHERE m.produto_id = :produto_id
                ORDER BY m.data_movimentacao DESC
                LIMIT :limit";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':produto_id', $productId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function create($data) {
        $sql = "INSERT INTO movimentacoes (produto_id, usuario_id, tipo, quantidade, observacao)
                VALUES (:produto_id, :usuario_id, :tipo, :quantidade, :observacao)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }

    public function getSummary($period = 'month') {
        // Example for dashboard: sum inputs and outputs for current month
        $sql = "SELECT
                    SUM(CASE WHEN tipo = 'entrada' THEN quantidade ELSE 0 END) as total_entrada,
                    SUM(CASE WHEN tipo = 'saida' THEN quantidade ELSE 0 END) as total_saida
                FROM movimentacoes
                WHERE MONTH(data_movimentacao) = MONTH(CURRENT_DATE())
                AND YEAR(data_movimentacao) = YEAR(CURRENT_DATE())";
        return $this->pdo->query($sql)->fetch();
    }

    public function getChartData($days = 30) {
        $sql = "SELECT
                    DATE(data_movimentacao) as data,
                    SUM(CASE WHEN tipo = 'entrada' THEN quantidade ELSE 0 END) as entrada,
                    SUM(CASE WHEN tipo = 'saida' THEN quantidade ELSE 0 END) as saida
                FROM movimentacoes
                WHERE data_movimentacao >= DATE_SUB(CURDATE(), INTERVAL :days DAY)
                GROUP BY DATE(data_movimentacao)
                ORDER BY data ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':days', (int)$days, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
