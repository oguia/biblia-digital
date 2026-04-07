<?php
$_SERVER['REQUEST_METHOD'] = 'POST';
$_GET['action'] = 'import_csv';

// Mock DB and Auth
function require_auth() { return ['id' => 1, 'role' => 'owner']; }
function require_tenant($user) { return 1; }
function getDB() {
    $db = new PDO('sqlite::memory:');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->exec("CREATE TABLE products (id INTEGER PRIMARY KEY, tenant_id INT, code TEXT, name TEXT, price REAL, min_stock INT, current_stock INT, category_id INT, supplier_id INT);");
    $db->exec("CREATE TABLE movements (id INTEGER PRIMARY KEY, tenant_id INT, product_id INT, user_id INT, type TEXT, quantity INT, reason TEXT, created_at DATETIME DEFAULT CURRENT_TIMESTAMP);");
    return $db;
}

$_FILES['file'] = [
    'name' => 'test.csv',
    'type' => 'text/csv',
    'tmp_name' => __DIR__ . '/test_csv.csv',
    'error' => 0,
    'size' => filesize(__DIR__ . '/test_csv.csv')
];

require __DIR__ . '/api/endpoints/products.php';

echo "\n--- BD Content ---\n";
$db = getDB(); // actually it recreates, I should not test like this, let's just see output
