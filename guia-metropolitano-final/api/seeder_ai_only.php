<?php
require_once 'config.php';
require_once 'classes/Gemini.php';

// Usage:
// Browser: seeder_ai_only.php?cat=Pizzaria&loc=Curitiba
// CLI: php seeder_ai_only.php "Pizzaria" "Curitiba"

$isCli = (php_sapi_name() === 'cli');

if ($isCli) {
    $location = $argv[2] ?? 'Curitiba';
    $categories = isset($argv[1]) ? [$argv[1]] : ['Pizzaria'];
} else {
    $location = $_GET['loc'] ?? 'Curitiba';
    $catParam = $_GET['cat'] ?? null;
    $categories = $catParam ? [$catParam] : ['Restaurantes', 'Advogados', 'Mecânicas', 'Salões de Beleza', 'Pet Shops', 'Encanadores', 'Eletricistas', 'Clínicas'];

    echo "<!DOCTYPE html><html><head><title>AI Seeder</title></head><body style='font-family:monospace; background:#1e1e1e; color:#4ade80; padding:20px;'>";
    echo "<h1>Starting AI-Only Seeder (No Scraping)...</h1>";
    echo "<p>Generating REALISTIC business data with images...</p><pre>";

    if (function_exists('apache_setenv')) { @apache_setenv('no-gzip', 1); }
    @ini_set('zlib.output_compression', 0);
    @ini_set('implicit_flush', 1);
    for ($i = 0; $i < ob_get_level(); $i++) { ob_end_flush(); }
    ob_implicit_flush(1);
}

// Image Mapping Strategy
function getCategoryImage($category) {
    // Clean category for URL
    $keyword = strtolower(str_replace([' ', 'ã', 'ç', 'õ', 'é', 'ê'], ['-', 'a', 'c', 'o', 'e', 'e'], $category));
    // Using LoremFlickr for reliable "real" looking placeholder images
    return "https://loremflickr.com/800/600/$keyword,business/all";
}

// Fallback Generator
function generateFakeBusinesses($category, $location, $count = 3) {
    $businesses = [];
    for ($i = 1; $i <= $count; $i++) {
        $businesses[] = [
            'name' => "$category Modelo $i",
            'description' => "Empresa modelo de demonstração para a categoria $category em $location. Entre em contato para saber mais.",
            'address' => "Rua Exemplo, " . rand(100, 999) . " - Centro, $location",
            'phone' => "(41) 99999-" . rand(1000, 9999),
            'whatsapp' => "554199999" . rand(1000, 9999),
            'lat' => -25.4284 + (rand(-100, 100) / 10000),
            'lng' => -49.2733 + (rand(-100, 100) / 10000),
            'image_url' => getCategoryImage($category)
        ];
    }
    return $businesses;
}

$gemini = new Gemini(GEMINI_API_KEY);

foreach ($categories as $category) {
    echo "Generating data for: $category in $location...\n";
    if (!$isCli) flush();

    $prompt = "Generate a JSON list of 6 REAL existing businesses for the category '$category' in '$location, Brazil'. \n" .
              "IMPORTANT: \n" .
              "1. Use ACTUAL existing business names. Do not invent generic names like 'Services Curitiba'. \n" .
              "2. Use REAL addresses if possible, or highly realistic ones in correct neighborhoods. \n" .
              "3. Use a realistic phone format (41) XXXX-XXXX. \n" .
              "4. For description, write a compelling, Instagram-style bio (emojis allowed, focus on benefits). \n" .
              "For each business, provide: \n" .
              "- name (string)\n" .
              "- description (string)\n" .
              "- address (string)\n" .
              "- phone (string)\n" .
              "- whatsapp (string, digits only, starting with 5541)\n" .
              "- lat (number)\n" .
              "- lng (number)\n" .
              "Return ONLY a JSON array. No markdown.";

    $jsonResponse = $gemini->generate($prompt);
    $businesses = [];

    if (!$jsonResponse) {
        echo "  ! AI Error/No Response. Using Fallback.\n";
        $businesses = generateFakeBusinesses($category, $location);
    } else {
        $jsonResponse = str_replace(['```json', '```'], '', $jsonResponse);
        $businesses = json_decode($jsonResponse, true);
    }

    if (!is_array($businesses) || empty($businesses)) {
        echo "  ! JSON Parse Error. Using Fallback.\n";
        $businesses = generateFakeBusinesses($category, $location);
    }

    foreach ($businesses as $biz) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $biz['name'])));

        // Check duplicate
        $stmt = $pdo->prepare("SELECT id FROM businesses WHERE slug LIKE ?");
        $stmt->execute([$slug . '%']);
        if ($stmt->fetch()) {
            echo "  - Skipping duplicate: {$biz['name']}\n";
            continue;
        }

        // Get/Create Category
        $stmt = $pdo->prepare("SELECT id FROM categories WHERE name LIKE ?");
        $stmt->execute(["%$category%"]);
        $catId = $stmt->fetchColumn();

        if (!$catId) {
            try {
                $slugCat = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $category)));
                $stmtIns = $pdo->prepare("INSERT INTO categories (name, slug) VALUES (?, ?)");
                $stmtIns->execute([$category, $slugCat]);
                $catId = $pdo->lastInsertId();
            } catch (Exception $e) {
                // If slug duplicate but name different, just grab the first one or default
                $catId = 1;
            }
        }

        // Assign Image (AI doesn't generate images, we map them)
        $imageUrl = getCategoryImage($category);

        try {
            $createdAt = date('Y-m-d H:i:s');
            $stmt = $pdo->prepare("INSERT INTO businesses (name, slug, description, category_id, address, city, phone, whatsapp, lat, lng, image_url, is_verified, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0, ?)");
            $stmt->execute([
                $biz['name'],
                $slug . '-' . uniqid(), // Ensure uniqueness
                $biz['description'] ?? "Serviço de destaque em Curitiba.",
                $catId,
                $biz['address'] ?? $location,
                'Curitiba',
                $biz['phone'] ?? null,
                $biz['whatsapp'] ?? null,
                $biz['lat'] ?? -25.4284,
                $biz['lng'] ?? -49.2733,
                $imageUrl,
                $createdAt
            ]);
            echo "  + Inserted: {$biz['name']}\n";
            if (!$isCli) flush();
        } catch (Exception $e) {
            echo "  ! DB Error: " . $e->getMessage() . "\n";
        }
    }
}

echo "\nSeeding complete. Check your homepage!</pre>";
if (!$isCli) echo "</body></html>";
