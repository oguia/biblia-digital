<?php
$url = "https://generativelanguage.googleapis.com/v1beta/models?key=INVALID_KEY";
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
echo "HTTP: $http_code\n";
echo "Response: $response\n";
