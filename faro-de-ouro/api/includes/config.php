<?php
define('DB_FILE', __DIR__ . '/../database.sqlite');
// Read JWT secret from env, or use a default for local development
$env_secret = getenv('JWT_SECRET');
define('JWT_SECRET', $env_secret ? $env_secret : 'dev-secret-faro-de-ouro-123!');

function getDB() {
    $dsn = "sqlite:" . DB_FILE;
    $pdo = new PDO($dsn);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
}
