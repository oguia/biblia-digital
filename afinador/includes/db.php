<?php
// Database Configuration
// NOTE: Update these values when deploying to Hostinger
$host = 'localhost';
$dbname = 'u123456789_afinador'; // Example database name
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // For development purposes only. In production, log this error instead of showing it.
    // die("Connection failed: " . $e->getMessage());

    // Silent fail or redirect to error page in production
    if ($_SERVER['HTTP_HOST'] === 'localhost') {
        die("Connection failed: " . $e->getMessage());
    } else {
        die("Erro de conexão com o banco de dados.");
    }
}
?>
