<?php
define('DB_FILE', __DIR__ . '/../database.sqlite');

function get_jwt_secret() {
    $secret_file = __DIR__ . '/../jwt_secret.txt';
    if (!file_exists($secret_file)) {
        $new_secret = bin2hex(random_bytes(32));
        file_put_contents($secret_file, $new_secret);
        chmod($secret_file, 0600);
    }
    return trim(file_get_contents($secret_file));
}

// Read JWT secret from file generated securely on the server
define('JWT_SECRET', get_jwt_secret());

function getDB() {
    $dsn = "sqlite:" . DB_FILE;
    $pdo = new PDO($dsn);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
}
