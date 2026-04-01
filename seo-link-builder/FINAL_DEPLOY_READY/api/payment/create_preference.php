<?php
// seo-link-builder/api/payment/create_preference.php
require '../config.php';

// Check Auth
$user = requireAuth($pdo);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['error' => 'Method not allowed'], 405);
}

$input = json_decode(file_get_contents('php://input'), true);
$packageKey = $input['package_id'] ?? '10_credits';

$packages = [
    '10_credits' => ['credits' => 10, 'price' => 29.90, 'title' => '10 SEO Links'],
    '50_credits' => ['credits' => 50, 'price' => 129.90, 'title' => '50 SEO Links'],
    '100_credits' => ['credits' => 100, 'price' => 199.90, 'title' => '100 SEO Links'],
];

if (!isset($packages[$packageKey])) {
    jsonResponse(['error' => 'Invalid package'], 400);
}

$pkg = $packages[$packageKey];

// If no token, return error
if (!defined('MERCADO_PAGO_ACCESS_TOKEN') || empty(MERCADO_PAGO_ACCESS_TOKEN)) {
    jsonResponse(['error' => 'Payment Gateway not configured (Missing Token)'], 500);
}

// Mercado Pago API
$url = "https://api.mercadopago.com/checkout/preferences";
$data = [
    "items" => [
        [
            "title" => $pkg['title'],
            "quantity" => 1,
            "currency_id" => "BRL",
            "unit_price" => (float)$pkg['price']
        ]
    ],
    // "back_urls" => ... (configure in MP Dashboard or here)
    "auto_return" => "approved",
    "external_reference" => json_encode(['user_id' => $user['id'], 'credits' => $pkg['credits']])
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . MERCADO_PAGO_ACCESS_TOKEN,
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    jsonResponse(['error' => 'Curl error: ' . curl_error($ch)], 500);
}
curl_close($ch);

$mp = json_decode($response, true);

if (isset($mp['init_point'])) {
    jsonResponse(['init_point' => $mp['init_point']]);
} else {
    // Log error
    error_log('MP Error: ' . $response);
    jsonResponse(['error' => 'Failed to create preference', 'details' => $mp], 500);
}
?>