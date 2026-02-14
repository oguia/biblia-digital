<?php
require_once 'config.php';

// Mercado Pago Integration
// Creates a preference for payment

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$planType = $data['plan_type'] ?? 'premium'; // 'premium' or 'featured'
$businessId = $data['business_id'] ?? null;

if (!$businessId) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing business ID']);
    exit;
}

// Define prices
$prices = [
    'premium' => 29.90, // BRL
    'featured' => 59.90
];

if (!isset($prices[$planType])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid plan type']);
    exit;
}

$price = $prices[$planType];
$title = "Plano " . ucfirst($planType) . " - Guia Metropolitano";

// Prepare MP request
$preferenceData = [
    "items" => [
        [
            "title" => $title,
            "quantity" => 1,
            "unit_price" => $price,
            "currency_id" => "BRL"
        ]
    ],
    "external_reference" => json_encode(['business_id' => $businessId, 'plan' => $planType]),
    "back_urls" => [
        "success" => "https://oguiametropolitano.com.br/sucesso",
        "failure" => "https://oguiametropolitano.com.br/falha",
        "pending" => "https://oguiametropolitano.com.br/pendente"
    ],
    "auto_return" => "approved"
];

$ch = curl_init('https://api.mercadopago.com/checkout/preferences');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($preferenceData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . MP_ACCESS_TOKEN
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode === 201) {
    $json = json_decode($response, true);
    echo json_encode([
        'preference_id' => $json['id'],
        'init_point' => $json['init_point'],
        'sandbox_init_point' => $json['sandbox_init_point']
    ]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Mercado Pago Error', 'details' => $response]);
}
