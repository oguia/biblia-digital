<?php
require_once 'config.php';
require_once 'classes/Gemini.php';

header('Content-Type: application/json');

$query = $_GET['q'] ?? '';

if (empty($query)) {
    // If empty, return featured businesses
    $stmt = $pdo->query("SELECT b.*, c.name as category_name FROM businesses b LEFT JOIN categories c ON b.category_id = c.id WHERE b.is_featured = 1 OR b.is_verified = 1 ORDER BY RAND() LIMIT 10");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    exit;
}

// 1. Try to search normally first (SQL LIKE)
$searchTerm = "%{$query}%";
$stmt = $pdo->prepare("
    SELECT b.*, c.name as category_name
    FROM businesses b
    LEFT JOIN categories c ON b.category_id = c.id
    WHERE b.name LIKE :q
    OR b.description LIKE :q
    OR c.name LIKE :q
    ORDER BY b.is_featured DESC, b.is_verified DESC
    LIMIT 20
");
$stmt->execute(['q' => $searchTerm]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 2. If few results, use Gemini to understand intent and broaden search
if (count($results) < 3 && defined('GEMINI_API_KEY')) {
    $gemini = new Gemini(GEMINI_API_KEY);
    $interpretation = $gemini->interpretSearch($query);

    if ($interpretation && !empty($interpretation['category_keyword'])) {
        $catKeyword = "%" . $interpretation['category_keyword'] . "%";
        $locKeyword = !empty($interpretation['location_keyword']) ? "%" . $interpretation['location_keyword'] . "%" : "%";

        $stmt = $pdo->prepare("
            SELECT b.*, c.name as category_name
            FROM businesses b
            LEFT JOIN categories c ON b.category_id = c.id
            WHERE (c.name LIKE :cat OR b.description LIKE :cat)
            AND (b.address LIKE :loc OR b.city LIKE :loc)
            ORDER BY b.is_featured DESC
            LIMIT 20
        ");
        $stmt->execute(['cat' => $catKeyword, 'loc' => $locKeyword]);
        $aiResults = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Merge without duplicates
        $existingIds = array_column($results, 'id');
        foreach ($aiResults as $aiRes) {
            if (!in_array($aiRes['id'], $existingIds)) {
                $results[] = $aiRes;
            }
        }
    }
}

echo json_encode($results);
