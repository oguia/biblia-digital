<?php
// seo-link-builder/api/test_gemini.php
// A simple diagnostic script to check Gemini connectivity without authentication (for debugging only, delete later)

require 'config.php';
require 'classes/Gemini.php';

// Allow this test only if a specific secret query param is present or if we are in dev (localhost)
// For security, let's just output text.

header('Content-Type: text/plain');

$key = defined('GEMINI_API_KEY') ? GEMINI_API_KEY : '';

if (empty($key)) {
    echo "ERROR: GEMINI_API_KEY is empty in config.php. Please set it.\n";
    exit;
}

echo "API Key Length: " . strlen($key) . " (Should be ~39 chars)\n";
echo "Testing connection to Google Gemini...\n";

$gemini = new Gemini($key);
$result = $gemini->generate("Hello, are you working? Reply with just 'Yes'.");

echo "Response from Gemini:\n";
echo "---------------------\n";
echo $result . "\n";
echo "---------------------\n";

if (strpos($result, 'Error') === 0) {
    echo "DIAGNOSIS: API Call Failed.\n";
    if (strpos($result, '401') !== false) echo "-> Cause: Invalid API Key.\n";
    if (strpos($result, '429') !== false) echo "-> Cause: Quota Exceeded (Free tier limit).\n";
    if (strpos($result, 'Connection failed') !== false) echo "-> Cause: Server cannot reach Google (Firewall/DNS).\n";
} else {
    echo "DIAGNOSIS: Success! API is working.\n";
}
?>