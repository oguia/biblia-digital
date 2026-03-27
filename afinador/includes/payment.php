<?php
require_once 'config.php';

function createPreference($userId, $userEmail) {
    $url = "https://api.mercadopago.com/checkout/preferences";

    $data = [
        "items" => [
            [
                "title" => PREMIUM_TITLE,
                "quantity" => 1,
                "currency_id" => "BRL",
                "unit_price" => (float)PREMIUM_PRICE
            ]
        ],
        "payer" => [
            "email" => $userEmail
        ],
        "back_urls" => [
            "success" => APP_URL . "/success.php",
            "failure" => APP_URL . "/upgrade.php",
            "pending" => APP_URL . "/upgrade.php"
        ],
        "auto_return" => "approved",
        "notification_url" => APP_URL . "/webhook.php",
        "external_reference" => (string)$userId
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "Authorization: Bearer " . MP_ACCESS_TOKEN
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode == 201) {
        $json = json_decode($response, true);
        return $json['init_point']; // URL to redirect user to
    } else {
        return false;
    }
}
?>
