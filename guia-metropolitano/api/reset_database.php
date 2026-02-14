<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: text/json; charset=UTF-8");

require_once 'config.php';

try {
    // 1. Drop existing tables
    $pdo->exec("DROP TABLE IF EXISTS businesses");
    $pdo->exec("DROP TABLE IF EXISTS categories");

    // 2. Re-create tables
    $pdo->exec("CREATE TABLE IF NOT EXISTS categories (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        slug TEXT UNIQUE NOT NULL,
        icon TEXT DEFAULT 'Briefcase'
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS businesses (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        slug TEXT UNIQUE NOT NULL,
        description TEXT,
        category_id INTEGER,
        address TEXT,
        phone TEXT,
        whatsapp TEXT,
        lat REAL,
        lng REAL,
        rating REAL DEFAULT 0,
        image_url TEXT,
        featured INTEGER DEFAULT 0,
        FOREIGN KEY(category_id) REFERENCES categories(id)
    )");

    // 3. Run Seeder Logic
    // We can include the seeder logic directly or just replicate it.
    // To ensure consistency, let's include the seeder file but we need to suppress its output if it echoes stuff.
    // Or better, just copy the logic since seeder is a standalone script.

    // Instead of copying, let's just run the seeder script via include if it's safe.
    // The seeder script outputs text. Let's capture it.

    ob_start();
    include 'seeder_ai_only.php';
    $output = ob_get_clean();

    echo json_encode([
        "success" => true,
        "message" => "Banco de dados resetado com sucesso!",
        "debug" => $output
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
