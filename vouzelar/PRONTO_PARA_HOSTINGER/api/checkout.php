<?php
require_once 'db.php';
require_once 'jwt.php';

// We simulate MP for the MVP
define('MP_ACCESS_TOKEN', 'TEST-7468165518055562-xxxxxx-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx-xxxxxxxxx');

function respond($data, $status = 200) {
    http_response_code($status);
    echo json_encode($data);
    exit;
}

$db = DB::getInstance()->getConnection();
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';
$input = json_decode(file_get_contents('php://input'), true);

// Create checkout preference link
if ($action === 'create_preference' && $method === 'POST') {
    $headers = getallheaders();
    $token = str_replace('Bearer ', '', $headers['Authorization'] ?? '');
    $payload = JWT::decode($token);
if (!$payload) respond(['error' => 'Token inválido ou expirado.'], 401);
    if (!$payload) respond(['error' => 'Unauthorized'], 401);

    $userId = $payload['user_id'];
    $plan = $input['plan'] ?? 'individual';
    $price = $plan === 'family' ? 19.90 : 5.00;

    // In a real integration, we'd send a POST to https://api.mercadopago.com/checkout/preferences
    // For this MVP, we simulate a response

    $mockInitPoint = "https://sandbox.mercadopago.com.br/checkout/v1/redirect?pref_id=TEST-" . rand(10000, 99999) . "-$userId";

    respond(['init_point' => $mockInitPoint]);
}

// Webhook for when payment is completed
if ($action === 'webhook' && $method === 'POST') {
    // MP Webhooks will hit this endpoint with data about the payment

    $topic = $_GET['topic'] ?? ($input['type'] ?? '');
    $id = $_GET['id'] ?? ($input['data']['id'] ?? null);

    if (!$id) {
        http_response_code(400);
        exit;
    }

    if ($topic === 'payment') {
        // 1. We would fetch the full payment info via cURL GET to https://api.mercadopago.com/v1/payments/{$id}
        // 2. We extract the external_reference (our user_id) and the status ('approved')
        // 3. We update our database

        // Mocking the update
        $userId = 1; // Simulated ID from webhook external_reference
        $status = 'approved';

        if ($status === 'approved') {
            $stmt = $db->prepare("UPDATE users SET subscription_status = 'active', trial_ends_at = datetime('now', '+30 days') WHERE id = ?");
            $stmt->execute([$userId]);
            error_log("Mercado Pago Webhook: User $userId subscription updated.");
        }
    }

    http_response_code(200);
    echo "OK";
}
