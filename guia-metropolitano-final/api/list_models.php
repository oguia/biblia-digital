<?php
require_once 'config.php';

header('Content-Type: text/plain');

echo "--- Gemini Model Lister ---\n";
$url = 'https://generativelanguage.googleapis.com/v1beta/models?key=' . GEMINI_API_KEY;

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// Disable SSL for Hostinger
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

$response = curl_exec($ch);
curl_close($ch);

$json = json_decode($response, true);

if (isset($json['models'])) {
    echo "Available Models:\n";
    foreach ($json['models'] as $model) {
        if (in_array('generateContent', $model['supportedGenerationMethods'])) {
            echo "- " . $model['name'] . "\n";
        }
    }
} else {
    echo "Error fetching models:\n$response";
}
