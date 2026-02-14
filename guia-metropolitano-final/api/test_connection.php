<?php
// Simple script to test outbound connectivity
echo "<h1>Server Connectivity Test</h1>";

function checkUrl($url) {
    echo "<p>Checking <strong>$url</strong>... ";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    // Mimic browser
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36");

    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    if ($httpCode >= 200 && $httpCode < 400) {
        echo "<span style='color:green'>OK (HTTP $httpCode)</span>";
    } else {
        echo "<span style='color:red'>FAILED (HTTP $httpCode)</span>. Error: $error";
    }
    echo "</p>";
}

checkUrl("https://www.google.com");
checkUrl("https://generativelanguage.googleapis.com"); // Gemini
checkUrl("https://duckduckgo.com"); // Scraper

echo "<h2>Check PHP Config</h2>";
echo "cURL Enabled: " . (function_exists('curl_init') ? 'Yes' : 'No') . "<br>";
echo "Allow URL Fopen: " . (ini_get('allow_url_fopen') ? 'Yes' : 'No') . "<br>";

require_once 'config.php';
echo "<h2>Check API Key</h2>";
echo "GEMINI_API_KEY Configured: " . ((defined('GEMINI_API_KEY') && GEMINI_API_KEY !== 'YOUR_GEMINI_API_KEY_HERE' && !empty(GEMINI_API_KEY)) ? "<span style='color:green'>YES</span>" : "<span style='color:red'>NO (Please edit config.php)</span>");
