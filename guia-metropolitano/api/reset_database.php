<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: text/json; charset=UTF-8");

require_once 'config.php';

try {
    // 1. Drop existing tables to ensure clean slate
    $tables = ['leads', 'coupons', 'businesses', 'categories', 'users'];
    foreach ($tables as $table) {
        $pdo->exec("DROP TABLE IF EXISTS $table");
    }

    // 2. Load schema from file
    $schema = file_get_contents('schema_sqlite.sql');
    if (!$schema) {
        throw new Exception("Schema file not found.");
    }

    // Execute multiple queries (PDO exec doesn't always support multiple statements in SQLite depending on driver, but usually fine)
    // Splitting by semicolon is safer
    $statements = explode(';', $schema);
    foreach ($statements as $statement) {
        if (trim($statement)) {
            $pdo->exec($statement);
        }
    }

    // 3. Run Seeder Logic (optional, maybe just structure)
    // Let's run the seeder to have initial data
    ob_start();
    include 'seeder_ai_only.php';
    $output = ob_get_clean();

    echo json_encode([
        "success" => true,
        "message" => "Banco de dados recriado com sucesso usando schema_sqlite.sql!",
        "seeder_output" => $output
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
