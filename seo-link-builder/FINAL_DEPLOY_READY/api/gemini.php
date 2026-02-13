<?php
require 'config.php';
require 'classes/Gemini.php';

$user = requireAuth($pdo);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['error' => 'Method not allowed'], 405);
}

$input = json_decode(file_get_contents('php://input'), true);
$prompt = $input['prompt'] ?? '';

if (empty($prompt)) {
    jsonResponse(['error' => 'Prompt is required'], 400);
}

if (empty(GEMINI_API_KEY)) {
    jsonResponse(['error' => 'Gemini API Key is missing in config'], 500);
}

$gemini = new Gemini(GEMINI_API_KEY);
$content = $gemini->generate($prompt);

if (strpos($content, 'Error:') === 0) {
    jsonResponse(['error' => $content], 500);
}

jsonResponse(['content' => $content]);
?>