<?php
require 'api/db.php';

$name = 'Rodrigo Santos';
$email = 'oguiametropolitano@gmail.com';
$password = password_hash('admin123', PASSWORD_DEFAULT);
$type = 'pf';
$is_admin = 1;
// Expires 100 years from now
$expires = date('Y-m-d H:i:s', strtotime('+100 years'));

$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user) {
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, type, is_admin, plan_expires_at) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$name, $email, $password, $type, $is_admin, $expires]);
    echo "User created successfully.\n";
} else {
    echo "User already exists.\n";
}
