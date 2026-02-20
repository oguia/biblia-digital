<?php
// Script de Diagnóstico Rápido
// Acesso: http://seusite.com/almoxarifado/test_db.php

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Teste de Diagnóstico</h1>";
echo "<h3>1. Verificação do PHP</h3>";
echo "Versão PHP: " . phpversion() . "<br>";

echo "<h3>2. Verificação do Arquivo de Configuração</h3>";
$configFile = __DIR__ . '/config/config.php';
if (file_exists($configFile)) {
    echo "Arquivo config/config.php encontrado.<br>";
    require_once $configFile;
    echo "Constantes carregadas:<br>";
    echo "DB_HOST: " . DB_HOST . "<br>";
    echo "DB_NAME: " . DB_NAME . "<br>";
    echo "DB_USER: " . DB_USER . "<br>";
    // Não exibir senha por segurança
} else {
    echo "<span style='color:red'>ERRO: Arquivo config/config.php NÃO encontrado!</span><br>";
    exit;
}

echo "<h3>3. Teste de Conexão com Banco de Dados</h3>";
try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<span style='color:green'>SUCESSO: Conexão com o banco estabelecida!</span><br>";

    // Testar tabela usuarios
    $stmt = $pdo->query("SELECT COUNT(*) FROM usuarios");
    $count = $stmt->fetchColumn();
    echo "Tabela 'usuarios' encontrada com $count registros.<br>";

} catch (PDOException $e) {
    echo "<span style='color:red'>ERRO DE CONEXÃO: " . $e->getMessage() . "</span><br>";
    echo "Verifique se o usuário, senha e nome do banco estão corretos no arquivo config/config.php.<br>";
}

echo "<h3>4. Teste de Roteamento (.htaccess)</h3>";
if (isset($_GET['url'])) {
    echo "Roteamento via URL parece estar funcionando (url=" . htmlspecialchars($_GET['url']) . ").<br>";
} else {
    echo "Acesse este arquivo via index.php para testar roteamento (ex: index.php/test_db.php - se der 404, o .htaccess pode estar falhando).<br>";
}

echo "<hr><p>Após testar, remova este arquivo por segurança.</p>";
