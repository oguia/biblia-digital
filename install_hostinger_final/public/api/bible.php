<?php
require_once 'db.php';

$bookAbbrev = $_GET['book'] ?? 'gn';
$chapterNum = $_GET['chapter'] ?? 1;
$version = $_GET['version'] ?? 'nvi';

if (!$bookAbbrev || !$chapterNum) {
    http_response_code(400);
    echo json_encode(["error" => "Missing book or chapter parameters"]);
    exit();
}

try {
    // 1. Get Book ID
    $stmt = $pdo->prepare("SELECT id, name, abbrev FROM books WHERE abbrev = ?");
    $stmt->execute([$bookAbbrev]);
    $book = $stmt->fetch();

    if (!$book) {
        http_response_code(404);
        echo json_encode(["error" => "Book not found"]);
        exit();
    }

    // 2. Get Chapter ID
    $stmt = $pdo->prepare("SELECT id, number FROM chapters WHERE book_id = ? AND number = ?");
    $stmt->execute([$book['id'], $chapterNum]);
    $chapter = $stmt->fetch();

    if (!$chapter) {
        http_response_code(404);
        echo json_encode(["error" => "Chapter not found"]);
        exit();
    }

    // 3. Get Verses
    $stmt = $pdo->prepare("SELECT number, text FROM verses WHERE chapter_id = ? AND version = ? ORDER BY number ASC");
    $stmt->execute([$chapter['id'], $version]);
    $verses = $stmt->fetchAll();

    // 4. Get Total Chapters for Navigation
    $stmt = $pdo->prepare("SELECT count(*) as total FROM chapters WHERE book_id = ?");
    $stmt->execute([$book['id']]);
    $totalChapters = $stmt->fetch()['total'];

    echo json_encode([
        "book" => $book,
        "chapter" => [
            "number" => (int)$chapterNum,
            "total" => (int)$totalChapters
        ],
        "verses" => $verses,
        "version" => $version
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
}
?>
