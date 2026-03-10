<?php
require_once 'config.php';

// Webhook for Mercado Pago

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!isset($data['type']) || $data['type'] !== 'payment') {
    http_response_code(200); // Acknowledge anyway
    exit;
}

$paymentId = $data['data']['id'];

// Verify payment status with MP
$ch = curl_init("https://api.mercadopago.com/v1/payments/$paymentId");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . MP_ACCESS_TOKEN
]);
$response = curl_exec($ch);
curl_close($ch);

$payment = json_decode($response, true);

if ($payment['status'] === 'approved') {
    $externalRef = json_decode($payment['external_reference'], true);
    $businessId = $externalRef['business_id'];
    $plan = $externalRef['plan'];

    // Update business status
    try {
        // Calculate expiration (e.g., 30 days)
        $expiresAt = date('Y-m-d H:i:s', strtotime('+30 days'));

        $stmt = $pdo->prepare("UPDATE businesses SET plan_tier = ?, plan_expires_at = ?, is_verified = 1 WHERE id = ?");
        $stmt->execute([$plan, $expiresAt, $businessId]);

        // Log payment (optional)

    } catch (PDOException $e) {
        error_log("DB Error in webhook: " . $e->getMessage());
    }
}

http_response_code(200);
