<?php
require_once __DIR__ . '/config.php';

if (!isset($pdo)) {
    die("Database connection failed. Check config.php.\n");
}

$driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
echo "Using database driver: $driver\n";

$schemaFile = ($driver === 'sqlite') ? __DIR__ . '/schema_sqlite.sql' : __DIR__ . '/schema.sql';

if (!file_exists($schemaFile)) {
    die("Schema file not found: $schemaFile\n");
}

echo "Applying schema updates from " . basename($schemaFile) . " ...\n";

try {
    $sql = file_get_contents($schemaFile);

    // SQLite executes multiple statements fine in PDO usually
    // MySQL needs to be configured, but standard usage is okay.
    $pdo->exec($sql);
    echo "Schema updated successfully.\n";
} catch (PDOException $e) {
    echo "Error updating schema: " . $e->getMessage() . "\n";
}
