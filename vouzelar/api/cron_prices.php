<?php
/**
 * Cron Job script for Hostinger.
 * Should be run daily via Hostinger cPanel or similar (php /home/user/public_html/api/cron_prices.php)
 */

require_once 'db.php';

// Only allow execution from CLI or via a secret token if accessed via web
if (php_sapi_name() !== 'cli' && (!isset($_GET['token']) || $_GET['token'] !== 'cron_secret_123')) {
    http_response_code(403);
    die('Forbidden');
}

$db = DB::getInstance()->getConnection();

echo "Starting daily price check...\n";

try {
    // 1. Get a list of unique medication names currently tracked by users
    $stmt = $db->prepare("SELECT DISTINCT name FROM medications WHERE stock_current > 0");
    $stmt->execute();
    $meds = $stmt->fetchAll(PDO::FETCH_COLUMN);

    if (empty($meds)) {
        echo "No active medications found. Exiting.\n";
        exit;
    }

    echo "Found " . count($meds) . " unique medications.\n";

    // 2. Simulated Price Scraping / API Request
    // In a production MVP without a real API key (like Consulta Remédios or Google Shopping API),
    // we simulate the search and save mock affiliate links.
    // To implement a real scraper here later, we would use cURL to fetch HTML and DOMDocument to parse prices.

    $insertStmt = $db->prepare("
        INSERT INTO medication_prices (medication_name, lowest_price, store_name, store_link, last_updated)
        VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP)
        ON CONFLICT(medication_name) DO UPDATE SET
            lowest_price = excluded.lowest_price,
            store_name = excluded.store_name,
            store_link = excluded.store_link,
            last_updated = CURRENT_TIMESTAMP
    ");

    $stores = [
        ['name' => 'Drogasil (Parceiro)', 'base_url' => 'https://www.drogasil.com.br/busca?w='],
        ['name' => 'Pague Menos (Parceiro)', 'base_url' => 'https://www.paguemenos.com.br/busca?q='],
        ['name' => 'Droga Raia (Parceiro)', 'base_url' => 'https://www.drogaraia.com.br/busca?w=']
    ];

    foreach ($meds as $medName) {
        // Normalize name for search
        $searchTerm = urlencode(strtolower(trim($medName)));

        // Mock a price between R$ 10.00 and R$ 150.00
        $mockPrice = rand(1000, 15000) / 100;

        // Pick a random store
        $store = $stores[array_rand($stores)];
        $affiliateLink = $store['base_url'] . $searchTerm . '&utm_source=vouzelar_app';

        $insertStmt->execute([$medName, $mockPrice, $store['name'], $affiliateLink]);

        echo "Updated price for: $medName -> R$ $mockPrice at {$store['name']}\n";

        // Sleep slightly to prevent rate limiting if this was a real API/Scraper
        usleep(500000);
    }

    echo "Price check completed successfully.\n";

} catch (Exception $e) {
    echo "Error during price check: " . $e->getMessage() . "\n";
}
