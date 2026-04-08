<?php
$db = new PDO('sqlite:test_db.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$db->exec("CREATE TABLE IF NOT EXISTS products (id INTEGER PRIMARY KEY, name TEXT)");

try {
    $db->exec("ALTER TABLE products ADD COLUMN unit TEXT");
    echo "Added column unit\n";
} catch (Exception $e) {
    echo "Unit already exists: " . $e->getMessage() . "\n";
}

try {
    $stmt = $db->prepare("INSERT INTO products (name, unit) VALUES ('Test', 'KG')");
    $stmt->execute();
    echo "Insert successful\n";
} catch (Exception $e) {
    echo "Insert failed: " . $e->getMessage() . "\n";
}
