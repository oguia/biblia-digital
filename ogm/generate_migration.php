<?php

$jsonFile = __DIR__ . '/temp_assets/empresas_curitiba_500_geo.json';
$outputFile = __DIR__ . '/import_data.sql';

if (!file_exists($jsonFile)) {
    die("JSON file not found at: $jsonFile\n");
}

$data = json_decode(file_get_contents($jsonFile), true);

if (!$data) {
    die("Failed to decode JSON.\n");
}

$categories = [];
$neighborhoods = [];
$companies = [];

echo "Processing " . count($data) . " companies...\n";

// Helper function to create slugs
function slugify($text) {
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    $text = strtolower($text);
    if (empty($text)) {
        return 'n-a-' . uniqid();
    }
    return $text;
}

$sql = "-- Generated Import Script\n\n";
$sql .= "SET FOREIGN_KEY_CHECKS=0;\n";
$sql .= "TRUNCATE TABLE companies;\n";
$sql .= "TRUNCATE TABLE categories;\n";
$sql .= "TRUNCATE TABLE neighborhoods;\n";
$sql .= "SET FOREIGN_KEY_CHECKS=1;\n\n";

// First Pass: Collect Unique Categories and Neighborhoods
foreach ($data as $item) {
    $catName = trim($item['categoria']);
    if (!empty($catName) && !isset($categories[$catName])) {
        $categories[$catName] = slugify($catName);
    }

    $bairroName = trim($item['endereco']['bairro'] ?? '');
    if (!empty($bairroName) && !isset($neighborhoods[$bairroName])) {
        $neighborhoods[$bairroName] = slugify($bairroName);
    }
}

// Generate Category Inserts
$sql .= "-- Categories\n";
$catMap = []; // Name -> ID (We will assume auto_increment starts at 1)
$i = 1;
foreach ($categories as $name => $slug) {
    $safeName = addslashes($name);
    $sql .= "INSERT INTO categories (id, name, slug) VALUES ($i, '$safeName', '$slug');\n";
    $catMap[$name] = $i;
    $i++;
}
$sql .= "\n";

// Generate Neighborhood Inserts
$sql .= "-- Neighborhoods\n";
$neighborhoodMap = [];
$j = 1;
foreach ($neighborhoods as $name => $slug) {
    $safeName = addslashes($name);
    $sql .= "INSERT INTO neighborhoods (id, name, slug, city) VALUES ($j, '$safeName', '$slug', 'Curitiba');\n";
    $neighborhoodMap[$name] = $j;
    $j++;
}
$sql .= "\n";

// Generate Company Inserts
$sql .= "-- Companies\n";
foreach ($data as $item) {
    $catName = trim($item['categoria']);
    $catId = $catMap[$catName] ?? 'NULL';

    $bairroName = trim($item['endereco']['bairro'] ?? '');
    $neighborhoodId = $neighborhoodMap[$bairroName] ?? 'NULL';

    $name = addslashes($item['nome']);
    $slug = slugify($item['nome']);

    // Ensure slug uniqueness (simple logic for now)
    // In a real migration, we'd check DB or keep a map of used slugs.
    // For 500 records, collisions are unlikely but possible.
    static $usedSlugs = [];
    if (isset($usedSlugs[$slug])) {
        $slug .= '-' . uniqid();
    }
    $usedSlugs[$slug] = true;

    $description = "Uma das melhores opções de " . addslashes($catName) . " em " . addslashes($bairroName) . ".";

    $address = addslashes($item['endereco']['rua'] ?? '');
    $number = addslashes($item['endereco']['numero'] ?? '');
    $zip = addslashes($item['endereco']['cep'] ?? '');
    $phone = addslashes($item['telefone'] ?? '');

    // Clean phone for whatsapp (only digits)
    $whatsapp = preg_replace('/[^0-9]/', '', $phone);
    // Add country code if missing (assuming Brazil +55)
    if (strlen($whatsapp) >= 10 && substr($whatsapp, 0, 2) != '55') {
        $whatsapp = '55' . $whatsapp;
    }

    $lat = $item['latitude'] ?? 'NULL';
    $lng = $item['longitude'] ?? 'NULL';

    // Default image path (will be handled by facade generator later)
    $imageUrl = '/img_exemplo.png';

    $sql .= "INSERT INTO companies (category_id, neighborhood_id, name, slug, description, address, number, zip_code, phone, whatsapp, latitude, longitude, image_url, status) VALUES ";
    $sql .= "($catId, $neighborhoodId, '$name', '$slug', '$description', '$address', '$number', '$zip', '$phone', '$whatsapp', $lat, $lng, '$imageUrl', 'active');\n";
}

file_put_contents($outputFile, $sql);

echo "Migration SQL generated at: $outputFile\n";
