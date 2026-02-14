<?php
require_once 'config.php';
require_once 'classes/Gemini.php';

// Usage:
// CLI: php seeder.php [category] [location]
// Browser: seeder.php?cat=Pizzaria&loc=Curitiba

$defaultCategories = [
    'Pizzaria', 'Advogado Trabalhista', 'Mecânica Automotiva',
    'Salão de Beleza', 'Pet Shop', 'Encanador 24h',
    'Eletricista Residencial', 'Clínica Dentária', 'Academia', 'Floricultura'
];

$isCli = (php_sapi_name() === 'cli');

if ($isCli) {
    $location = $argv[2] ?? 'Curitiba';
    $categories = isset($argv[1]) ? [$argv[1]] : $defaultCategories;
} else {
    // Browser Mode
    $location = $_GET['loc'] ?? 'Curitiba';
    $catParam = $_GET['cat'] ?? null;
    $categories = $catParam ? [$catParam] : $defaultCategories;

    // Basic HTML header for output
    echo "<!DOCTYPE html><html><head><title>Seeding...</title></head><body style='font-family:monospace; background:#222; color:#0f0; padding:20px;'>";
    echo "<h1>Starting Seeder...</h1>";
    echo "<p>Category: " . implode(', ', $categories) . " | Location: $location</p><pre>";

    // Flush buffer
    if (function_exists('apache_setenv')) {
        @apache_setenv('no-gzip', 1);
    }
    @ini_set('zlib.output_compression', 0);
    @ini_set('implicit_flush', 1);
    for ($i = 0; $i < ob_get_level(); $i++) { ob_end_flush(); }
    ob_implicit_flush(1);
}

function searchDuckDuckGo($query) {
    $userAgents = [
        "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36",
        "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0.3 Safari/605.1.15",
        "Mozilla/5.0 (Linux; Android 10; SM-G960U) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.114 Mobile Safari/537.36"
    ];

    $url = "https://html.duckduckgo.com/html/?q=" . urlencode($query);

    // Retry logic
    for ($i = 0; $i < 3; $i++) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, $userAgents[$i % count($userAgents)]);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        // Add headers to mimic real browser
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8",
            "Accept-Language: pt-BR,pt;q=0.9,en-US;q=0.8,en;q=0.7",
            "Referer: https://duckduckgo.com/"
        ]);

        $html = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200 && !empty($html)) {
             // Simple check if we got actual results
             if (strpos($html, 'result__a') !== false) {
                 return $html;
             }
        }
        sleep(2); // Wait before retry
    }
    return null;
}

$gemini = new Gemini(GEMINI_API_KEY);

foreach ($categories as $category) {
    echo "Processing category: $category in $location...\n";
    if (!$isCli) { echo "<script>window.scrollTo(0,document.body.scrollHeight);</script>"; flush(); }

    $queries = [
        "$category em $location site:instagram.com",
        "melhores $category em $location"
    ];

    foreach ($queries as $query) {
        echo "  Searching: $query\n";
        if (!$isCli) flush();

        $html = searchDuckDuckGo($query);
        if (!$html) {
            echo "    Failed to fetch results (Blocked or Network Error). Try again later or check server IP reputation.\n";
            continue;
        }

        preg_match_all('/<a class="result__a" href="([^"]+)">([^<]+)<\/a>.*?<a class="result__snippet" href="[^"]+">([^<]+)<\/a>/s', $html, $matches, PREG_SET_ORDER);

        if (empty($matches)) {
            echo "    No results found in HTML parsing.\n";
            continue;
        }

        echo "    Found " . count($matches) . " raw results. Analyzing with AI...\n";
        if (!$isCli) flush();

        $batch = [];
        foreach ($matches as $match) {
            $batch[] = "Title: " . strip_tags($match[2]) . " | Snippet: " . strip_tags($match[3]) . " | URL: " . $match[1];
            if (count($batch) >= 5) break;
        }

        $prompt = "I have a list of search results for '$category' in '$location'. \n" .
                  "Extract valid businesses from this list. Ignore directories. \n" .
                  "For each business, return a JSON object with: \n" .
                  "- name (string)\n" .
                  "- description (string, based on snippet)\n" .
                  "- address (string, infer city/neighborhood if possible, else '$location')\n" .
                  "- phone (string, if found in snippet, else null)\n" .
                  "- website (string, the URL provided)\n" .
                  "Return a JSON ARRAY of objects. If none found, return []. \n\n" .
                  "Data:\n" . implode("\n\n", $batch);

        $jsonResponse = $gemini->generate($prompt);
        $jsonResponse = str_replace(['```json', '```'], '', $jsonResponse);
        $businesses = json_decode($jsonResponse, true);

        if (!is_array($businesses)) {
            echo "    Failed to parse AI response.\n";
            continue;
        }

        foreach ($businesses as $biz) {
            if (empty($biz['name'])) continue;

            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $biz['name'])));

            $stmt = $pdo->prepare("SELECT id FROM businesses WHERE slug LIKE ?");
            $stmt->execute([$slug . '%']);
            if ($stmt->fetch()) {
                echo "      Skipping duplicate: {$biz['name']}\n";
                continue;
            }

            // Get/Create Category ID
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
                    $catId = 1;
                }
            }

            try {
                $stmt = $pdo->prepare("INSERT INTO businesses (name, slug, description, category_id, address, city, phone, website, is_verified, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0, NOW())");
                $stmt->execute([
                    $biz['name'],
                    $slug . '-' . uniqid(),
                    $biz['description'] ?? "Serviço de $category.",
                    $catId,
                    $biz['address'] ?? $location,
                    'Curitiba',
                    $biz['phone'] ?? null,
                    $biz['website'] ?? null
                ]);
                echo "      + Inserted: {$biz['name']}\n";
                if (!$isCli) flush();
            } catch (Exception $e) {
                echo "      ! Error: " . $e->getMessage() . "\n";
            }
        }
        sleep(2);
    }
}

echo "\nSeeding complete.</pre>";
if (!$isCli) echo "</body></html>";
