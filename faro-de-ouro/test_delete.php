<?php
$_SERVER['REQUEST_METHOD'] = 'DELETE';
$_GET['action'] = 'delete_all';

// Mock DB and Auth
function require_auth() { return ['id' => 1, 'role' => 'owner']; }
function require_tenant($user) { return 1; }

function getDB() {
    $db = new PDO('sqlite::memory:');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->exec("CREATE TABLE products (id INTEGER PRIMARY KEY, tenant_id INT, code TEXT, name TEXT, price REAL, min_stock INT, current_stock INT, category_id INT, supplier_id INT);");
    $db->exec("CREATE TABLE movements (id INTEGER PRIMARY KEY, tenant_id INT, product_id INT, user_id INT, type TEXT, quantity INT, reason TEXT, created_at DATETIME DEFAULT CURRENT_TIMESTAMP);");

    // Insert dummy data
    $db->exec("INSERT INTO products (tenant_id, name) VALUES (1, 'Test Product')");
    $db->exec("INSERT INTO movements (tenant_id, product_id) VALUES (1, 1)");
    return $db;
}

try {
    require __DIR__ . '/api/endpoints/products.php';
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
