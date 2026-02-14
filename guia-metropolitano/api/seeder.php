<?php
require_once 'config.php';
require_once 'classes/Gemini.php';

// Usage: php seeder.php [category] [location]
// If no arguments, it runs through a default list.

$defaultCategories = [
    'Pizzaria', 'Advogado Trabalhista', 'Mecânica Automotiva',
    'Salão de Beleza', 'Pet Shop', 'Encanador 24h',
    'Eletricista Residencial', 'Clínica Dentária', 'Academia', 'Floricultura'
];

$location = $argv[2] ?? 'Curitiba';
$categories = isset($argv[1]) ? [$argv[1]] : $defaultCategories;

function searchDuckDuckGo($query) {
    // Basic curl to DDG HTML version
    // Note: DDG might rate limit this. In production, use a proxy or a real SERP API.
    $url = "https://html.duckduckgo.com/html/?q=" . urlencode($query);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Rotating User Agents would be better
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36");
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);

    $html = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200) {
        return null;
    }
    return $html;
}

$gemini = new Gemini(GEMINI_API_KEY);

foreach ($categories as $category) {
    echo "Processing category: $category in $location...\n";

    // Search queries variants
    $queries = [
        "$category em $location site:instagram.com", // Good for small businesses
        "$category em $location site:facebook.com",
        "melhores $category em $location"
    ];

    foreach ($queries as $query) {
        echo "  Searching: $query\n";
        $html = searchDuckDuckGo($query);

        if (!$html) {
            echo "    Failed to fetch results.\n";
            continue;
        }

        // Extract search result snippets
        // Regex to find result body
        preg_match_all('/<a class="result__a" href="([^"]+)">([^<]+)<\/a>.*?<a class="result__snippet" href="[^"]+">([^<]+)<\/a>/s', $html, $matches, PREG_SET_ORDER);

        if (empty($matches)) {
            echo "    No results found in HTML parsing.\n";
            continue;
        }

        echo "    Found " . count($matches) . " raw results. Analyzing with AI...\n";

        // Batch process with Gemini to save API calls?
        // Or process one by one for better accuracy. Let's do batches of 5.

        $batch = [];
        foreach ($matches as $match) {
            $batch[] = "Title: " . strip_tags($match[2]) . " | Snippet: " . strip_tags($match[3]) . " | URL: " . $match[1];
            if (count($batch) >= 5) break;
        }

        $prompt = "I have a list of search results for '$category' in '$location'. \n" .
                  "Extract valid businesses from this list. Ignore directories or generic lists. \n" .
                  "For each business, return a JSON object with: \n" .
                  "- name (string)\n" .
                  "- description (string, based on snippet)\n" .
                  "- address (string, infer city/neighborhood if possible, else '$location')\n" .
                  "- phone (string, if found in snippet, else null)\n" .
                  "- website (string, the URL provided)\n" .
                  "Return a JSON ARRAY of objects. If none found, return []. \n\n" .
                  "Data:\n" . implode("\n\n", $batch);

        $jsonResponse = $gemini->generate($prompt);

        // Clean markdown
        $jsonResponse = str_replace(['```json', '```'], '', $jsonResponse);
        $businesses = json_decode($jsonResponse, true);

        if (!is_array($businesses)) {
            echo "    Failed to parse AI response.\n";
            continue;
        }

        foreach ($businesses as $biz) {
            if (empty($biz['name'])) continue;

            // Upsert Logic
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $biz['name'])));

            // Check if exists
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
                // Try to find a generic one or create
                // Simplified: Insert dynamic category
                try {
                    $slugCat = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $category)));
                    $stmtIns = $pdo->prepare("INSERT INTO categories (name, slug) VALUES (?, ?)");
                    $stmtIns->execute([$category, $slugCat]);
                    $catId = $pdo->lastInsertId();
                } catch (Exception $e) {
                    $catId = 1; // Fallback
                }
            }

            try {
                $stmt = $pdo->prepare("INSERT INTO businesses (name, slug, description, category_id, address, city, phone, website, is_verified, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0, NOW())");
                $stmt->execute([
                    $biz['name'],
                    $slug . '-' . uniqid(), // Ensure unique slug
                    $biz['description'] ?? "Serviço de $category.",
                    $catId,
                    $biz['address'] ?? $location,
                    'Curitiba',
                    $biz['phone'] ?? null,
                    $biz['website'] ?? null
                ]);
                echo "      + Inserted: {$biz['name']}\n";
            } catch (Exception $e) {
                echo "      ! Error: " . $e->getMessage() . "\n";
            }
        }

        sleep(2); // Respect rate limits
    }
}

echo "Seeding complete.\n";
