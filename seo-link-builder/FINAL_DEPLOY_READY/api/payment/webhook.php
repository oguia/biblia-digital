<?php
// seo-link-builder/api/payment/webhook.php
require '../config.php';

// Mercado Pago Webhook Handler
// Handles 'payment' notifications
$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['type']) || $input['type'] !== 'payment') {
    http_response_code(200);
    exit;
}

$paymentId = $input['data']['id'] ?? null;
if (!$paymentId) exit;

if (!defined('MERCADO_PAGO_ACCESS_TOKEN') || empty(MERCADO_PAGO_ACCESS_TOKEN)) {
    http_response_code(500);
    exit;
}

// Verify payment status
$url = "https://api.mercadopago.com/v1/payments/$paymentId";
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . MERCADO_PAGO_ACCESS_TOKEN
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

$payment = json_decode($response, true);

if ($payment && isset($payment['status']) && $payment['status'] === 'approved') {
    $meta = json_decode($payment['external_reference'] ?? '{}', true);

    if (isset($meta['user_id']) && isset($meta['credits'])) {
        $userId = $meta['user_id'];
        $credits = $meta['credits'];
        $amount = $payment['transaction_amount'];

        // Check duplicate
        $stmt = $pdo->prepare("SELECT id FROM transactions WHERE payment_id = ?");
        $stmt->execute([$paymentId]);
        if (!$stmt->fetch()) {
            try {
                $pdo->beginTransaction();
                $stmt = $pdo->prepare("INSERT INTO transactions (user_id, amount, credits, payment_id, status) VALUES (?, ?, ?, ?, 'approved')");
                $stmt->execute([$userId, $amount, $credits, $paymentId]);

                $stmt = $pdo->prepare("UPDATE users SET credits = credits + ? WHERE id = ?");
                $stmt->execute([$credits, $userId]);
                $pdo->commit();
            } catch (Exception $e) {
                $pdo->rollBack();
                error_log("Webhook Error: " . $e->getMessage());
            }
        }
    }
}

http_response_code(200);
?>