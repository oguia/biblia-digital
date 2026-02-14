<?php
// seo-link-builder/api/list_models.php
// Diagnostic script to list available Gemini models for your API key

require 'config.php';

header('Content-Type: text/plain');

$key = defined('GEMINI_API_KEY') ? GEMINI_API_KEY : '';

if (empty($key)) {
    echo "ERROR: GEMINI_API_KEY is empty in config.php\n";
    exit;
}

echo "API Key Found. Length: " . strlen($key) . "\n\n";

// List all models via v1beta
$url = "https://generativelanguage.googleapis.com/v1beta/models?key={$key}";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo "Connection Error: " . curl_error($ch) . "\n";
    exit;
}

$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$json = json_decode($response, true);

if ($httpCode !== 200) {
    echo "API Error ({$httpCode}): " . ($json['error']['message'] ?? 'Unknown Error') . "\n";
    exit;
}

if (isset($json['models'])) {
    echo "AVAILABLE MODELS FOR YOUR KEY:\n";
    echo "================================\n";
    foreach ($json['models'] as $model) {
        if (in_array('generateContent', $model['supportedGenerationMethods'])) {
            echo "Name: " . $model['name'] . "\n";
            echo "Display: " . ($model['displayName'] ?? 'N/A') . "\n";
            echo "Version: " . ($model['version'] ?? 'N/A') . "\n";
            echo "--------------------------------\n";
        }
    }
} else {
    echo "No models found in response.\n";
    print_r($json);
}
?>