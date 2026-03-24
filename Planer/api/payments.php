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

    $price = 0;
    $title = '';

    if ($plan_type === 'monthly') {
        $price = 19.90;
        $title = 'Plano Mensal - Planer';
    } elseif ($plan_type === 'yearly') {
        $price = 149.00;
        $title = 'Plano Anual - Planer';
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Plano inválido']);
        exit;
    }

    $url = "https://api.mercadopago.com/checkout/preferences";

    $data = [
        "items" => [
            [
                "title" => $title,
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
             "excluded_payment_types" => [
                  ["id" => "ticket"] // Optionally exclude boleto for instant access SaaS
             ],
             "installments" => 12
        ]
    ];

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

    if ($http_status >= 200 && $http_status < 300 && isset($result['id'])) {
        echo json_encode(['preference_id' => $result['id'], 'init_point' => $result['init_point']]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Erro ao conectar com Mercado Pago.', 'details' => $result]);
    }
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Unknown action']);
}