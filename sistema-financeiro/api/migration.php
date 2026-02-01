<?php
// Script de migração simples para adicionar a coluna status
require_once 'config.php';

echo "<h1>Atualização do Banco de Dados</h1>";

$pdo = getDB();

try {
    // Verifica se a coluna já existe
    $stmt = $pdo->query("SHOW COLUMNS FROM transactions LIKE 'status'");
    $exists = $stmt->fetch();

    if ($exists) {
        echo "<p style='color:green'>A coluna 'status' já existe. Nenhuma ação necessária.</p>";
    } else {
        // Adiciona a coluna
        $pdo->exec("ALTER TABLE transactions ADD COLUMN status ENUM('paid', 'pending') DEFAULT 'paid'");
        echo "<p style='color:green'>Sucesso! Coluna 'status' adicionada na tabela transactions.</p>";
    }

    echo "<p><a href='../'>Voltar para o Sistema</a></p>";

} catch (PDOException $e) {
    echo "<p style='color:red'>Erro ao atualizar: " . $e->getMessage() . "</p>";
}
?>
