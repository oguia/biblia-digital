<?php
require_once 'config.php';

header('Content-Type: text/plain');

echo "--- Gemini API Debugger ---\n";
echo "Key configured: " . substr(GEMINI_API_KEY, 0, 5) . "..." . substr(GEMINI_API_KEY, -5) . "\n";

// Updated to 2.0-flash based on user's available models
$url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=' . GEMINI_API_KEY;

$data = [
    'contents' => [
        [
            'parts' => [
                ['text' => 'Hello, say "Connection Successful!"']
            ]
        ]
    ]
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_VERBOSE, true); // Output debug info

// FIX FOR HOSTINGER: Disable SSL Verification (Temporary)
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);

curl_close($ch);

echo "\n--- Result ---\n";
echo "HTTP Code: $httpCode\n";

if ($error) {
    echo "cURL Error: $error\n";
    echo "Diagnosis: The server could not connect to Google. Check your Firewall or SSL settings.\n";
} else {
    echo "Response Body:\n$response\n";

    $json = json_decode($response, true);
    if (isset($json['error'])) {
        echo "\nAPI Error Details: " . $json['error']['message'] . "\n";
        echo "Status: " . $json['error']['status'] . "\n";
    } elseif (isset($json['candidates'][0]['content']['parts'][0]['text'])) {
        echo "\nSUCCESS! The API is working. The text received was:\n";
        echo $json['candidates'][0]['content']['parts'][0]['text'] . "\n";
    }
}
