<?php
require 'db.php';

$user = getAuthUser($pdo);
if (!$user) {
    sendJson(['error' => 'Unauthorized'], 401);
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['plan'])) {
        sendJson(['error' => 'Plano não selecionado'], 400);
    }

    $plan_key = $data['plan'];
    $payment_type = $data['payment_type'] ?? 'one_time';

    $plans = [
        'monthly' => ['title' => 'Plano Mensal - Cartão Ponto', 'price' => 19.90, 'frequency' => 1, 'frequency_type' => 'months'],
        'quarterly' => ['title' => 'Plano Trimestral - Cartão Ponto', 'price' => 49.90, 'frequency' => 3, 'frequency_type' => 'months'],
        'annual' => ['title' => 'Plano Anual - Cartão Ponto', 'price' => 179.00, 'frequency' => 1, 'frequency_type' => 'years'],
    ];

    if (!isset($plans[$plan_key])) {
        sendJson(['error' => 'Plano inválido'], 400);
    }

    $plan = $plans[$plan_key];

    // Get MP Access Token
    $stmt = $pdo->query("SELECT value FROM settings WHERE key = 'mp_access_token'");
    $setting = $stmt->fetch();

    if (!$setting || empty($setting['value'])) {
        sendJson(['error' => 'Configuração de pagamento não definida pelo administrador.'], 400);
    }

    $access_token = $setting['value'];

    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'];
    $baseUrl = $protocol . "://" . $host;

    // Webhook Notification URL
    // Depending on where this is hosted, this needs to be accessible from outside.
    $notification_url = $baseUrl . '/api/webhook_mp.php';

    if ($payment_type === 'subscription') {
        // Create Preapproval Plan (Recurring Subscription)
        $preapprovalData = [
            "reason" => $plan['title'],
            "auto_recurring" => [
                "frequency" => $plan['frequency'],
                "frequency_type" => $plan['frequency_type'],
                "transaction_amount" => (float) $plan['price'],
                "currency_id" => "BRL"
            ],
            "payer_email" => $user['email'],
            "back_url" => $baseUrl . "/#/subscription",
            "external_reference" => json_encode(['user_id' => $user['id'], 'plan' => $plan_key, 'type' => 'subscription'])
        ];

        $ch = curl_init("https://api.mercadopago.com/preapproval");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($preapprovalData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer " . $access_token,
            "Content-Type: application/json"
        ]);

    } else {
        // Create Preference (One-time Payment)
        $preferenceData = [
            "items" => [
                [
                    "id" => $plan_key,
                    "title" => $plan['title'],
                    "description" => "Assinatura do sistema Cartão Ponto",
                    "quantity" => 1,
                    "currency_id" => "BRL",
                    "unit_price" => (float) $plan['price']
                ]
            ],
            "payer" => [
                "email" => $user['email'],
                "name" => $user['name']
            ],
            "back_urls" => [
                "success" => $baseUrl . "/#/subscription",
                "failure" => $baseUrl . "/#/subscription",
                "pending" => $baseUrl . "/#/subscription"
            ],
            "auto_return" => "approved",
            "external_reference" => json_encode(['user_id' => $user['id'], 'plan' => $plan_key, 'type' => 'one_time']),
            "notification_url" => $notification_url
        ];

        $ch = curl_init("https://api.mercadopago.com/checkout/preferences");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($preferenceData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer " . $access_token,
            "Content-Type: application/json"
        ]);
    }

    // Bypass SSL issues if any on shared hosting
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $responseData = json_decode($response, true);

    if ($http_code == 200 || $http_code == 201) {
        sendJson(['init_point' => $responseData['init_point']]);
    } else {
        error_log("Mercado Pago Error: " . $response);
        sendJson(['error' => 'Erro ao gerar link de pagamento. Tente novamente mais tarde.'], 500);
    }
}
