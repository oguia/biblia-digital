<?php
// Planer/api/payments.php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

require_once 'db.php';
require_once 'auth.php';
// require_once 'config.php'; -- Now using DB settings

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Fetch settings
$stmt = $db->query("SELECT * FROM settings");
$settingsRaw = $stmt->fetchAll(PDO::FETCH_ASSOC);
$settings = [];
foreach ($settingsRaw as $row) {
    $settings[$row['key']] = $row['value'];
}
$mp_access_token = $settings['mp_access_token'] ?? '';
$site_url = $settings['site_url'] ?? '';

$user = verify_auth_token($db);
$action = $_GET['action'] ?? '';
$input = json_decode(file_get_contents('php://input'), true);

if ($action === 'create_preference') {
    $plan_type = $input['plan_type'] ?? '';
    $billing_type = $input['billing_type'] ?? 'manual'; // 'manual' (PIX/Boleto/Cartão 1x) or 'automatic' (Subscription)

    $price = 0;
    $title = '';
    $frequency_type = '';

    if ($plan_type === 'monthly') {
        $price = 19.90;
        $title = 'Plano Mensal - Planer';
        $frequency_type = 'months';
    } elseif ($plan_type === 'yearly') {
        $price = 149.00;
        $title = 'Plano Anual - Planer';
        $frequency_type = 'years';
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Plano inválido']);
        exit;
    }

    if ($billing_type === 'automatic') {
        // Create an automatic subscription (preapproval_plan)
        $url = "https://api.mercadopago.com/preapproval_plan";
        $data = [
            "reason" => $title . " (Renovação Automática)",
            "auto_recurring" => [
                "frequency" => 1,
                "frequency_type" => $frequency_type,
                "transaction_amount" => (float) $price,
                "currency_id" => "BRL"
            ],
            "back_url" => $site_url . "/#/profile?payment=success"
        ];

        // Let's execute the plan creation immediately so we get an init_point to redirect the user
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Authorization: Bearer " . $mp_access_token
        ]);

        $response = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $result = json_decode($response, true);

        if ($http_status >= 200 && $http_status < 300 && isset($result['init_point'])) {
            echo json_encode(['init_point' => $result['init_point']]);
            exit;
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Erro ao conectar com Mercado Pago (Assinatura).', 'details' => $result]);
            exit;
        }
    } else {
        // Create a manual one-time payment (Checkout Preference)
        $url = "https://api.mercadopago.com/checkout/preferences";
        $data = [
            "items" => [
                [
                    "title" => $title . " (Renovação Manual)",
                    "quantity" => 1,
                    "unit_price" => (float) $price,
                    "currency_id" => "BRL"
                ]
            ],
            "back_urls" => [
                "success" => $site_url . "/#/profile?payment=success",
                "failure" => $site_url . "/#/profile?payment=failure",
                "pending" => $site_url . "/#/profile?payment=pending"
            ],
            "auto_return" => "approved",
            "external_reference" => "user_" . $user['id'] . "_plan_" . $plan_type,
            "payment_methods" => [
                 "installments" => 1
            ]
        ];
    }

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "Authorization: Bearer " . $mp_access_token
    ]);

    $response = curl_exec($ch);
    $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $result = json_decode($response, true);

    if ($http_status >= 200 && $http_status < 300 && isset($result['init_point'])) {
        echo json_encode(['preference_id' => $result['id'] ?? null, 'init_point' => $result['init_point']]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Erro ao conectar com Mercado Pago.', 'details' => $result]);
    }
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Unknown action']);
}