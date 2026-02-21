<?php
// ogm/public/db_check.php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../app/Core/Database.php';

try {
    $db = Database::getInstance()->getConnection();

    // Get DB Name
    $stmt = $db->query("SELECT DATABASE()");
    $dbName = $stmt->fetchColumn();

    echo "<h1>Diagnóstico de Banco de Dados</h1>";
    echo "<p><strong>Banco Conectado:</strong> " . htmlspecialchars($dbName) . "</p>";

    // Check Companies
    $stmt = $db->query("SELECT id, name FROM companies LIMIT 10");
    $companies = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<h2>Primeiras 10 Empresas no Banco:</h2>";
    echo "<ul>";
    if (empty($companies)) {
        echo "<li><em>Nenhuma empresa encontrada (Tabela vazia?)</em></li>";
    } else {
        foreach ($companies as $c) {
            echo "<li>ID: " . $c['id'] . " - <strong>" . htmlspecialchars($c['name']) . "</strong></li>";
        }
    }
    echo "</ul>";

    echo "<hr>";
    echo "<p>Se você vê 'Supermercado Exemplo' aqui, você está conectado a um banco que tem os dados antigos.</p>";
    echo "<p>Se você vê 'Madalosso', 'Barolo', etc., os dados estão CORRETOS e o problema é Cache do navegador.</p>";

} catch (Exception $e) {
    echo "<h1>Erro de Conexão</h1>";
    echo "<p>" . $e->getMessage() . "</p>";
}
