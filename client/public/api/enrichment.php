<?php
require_once 'db.php';

$bookAbbrev = $_GET['book'] ?? 'gn';
$chapterNum = $_GET['chapter'] ?? 1;

if (!$bookAbbrev || !$chapterNum) {
    http_response_code(400);
    echo json_encode(["error" => "Missing book or chapter parameters"]);
    exit();
}

try {
    // Get IDs
    $stmt = $pdo->prepare("SELECT c.id FROM chapters c JOIN books b ON c.book_id = b.id WHERE b.abbrev = ? AND c.number = ?");
    $stmt->execute([$bookAbbrev, $chapterNum]);
    $chapter = $stmt->fetch();

    if (!$chapter) {
        http_response_code(404);
        echo json_encode(["error" => "Chapter not found"]);
        exit();
    }

    $chapterId = $chapter['id'];

    // 1. Historical Context
    $stmt = $pdo->prepare("SELECT who_involved, when_text, where_text, historical_events FROM historical_context WHERE chapter_id = ?");
    $stmt->execute([$chapterId]);
    $context = $stmt->fetch();

    // 2. Timeline Events
    $stmt = $pdo->prepare("SELECT year, title, description, era FROM timeline_events WHERE chapter_id = ? ORDER BY year ASC");
    $stmt->execute([$chapterId]);
    $timeline = $stmt->fetchAll();

    // 3. Map Locations
    $stmt = $pdo->prepare("SELECT name, latitude, longitude, description, type FROM map_locations WHERE chapter_id = ?");
    $stmt->execute([$chapterId]);
    $map = $stmt->fetchAll();

    // 4. Spiritual Application
    $stmt = $pdo->prepare("SELECT truth, alert, action FROM spiritual_applications WHERE chapter_id = ?");
    $stmt->execute([$chapterId]);
    $application = $stmt->fetch();

    echo json_encode([
        "context" => $context,
        "timeline" => $timeline,
        "map" => $map,
        "application" => $application
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
}
?>
