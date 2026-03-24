<?php
// Planer/api/webhook_mercadopago.php
header('Content-Type: application/json');
require_once 'db.php';
require_once 'config.php';

// Log incoming request
file_put_contents('mp_webhook.log', file_get_contents('php://input') . "\n", FILE_APPEND);

$input = json_decode(file_get_contents('php://input'), true);

if (isset($input['type']) && $input['type'] === 'payment') {
    $payment_id = $input['data']['id'];

    // Verify payment status with Mercado Pago API
    $url = "https://api.mercadopago.com/v1/payments/" . $payment_id;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer " . MP_ACCESS_TOKEN
    ]);

    $response = curl_exec($ch);
    $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_status === 200) {
        $payment_info = json_decode($response, true);

        if ($payment_info['status'] === 'approved') {
            $external_reference = $payment_info['external_reference']; // e.g., "user_1_plan_monthly"

            if (preg_match('/user_(\d+)_plan_(monthly|yearly)/', $external_reference, $matches)) {
                $user_id = (int)$matches[1];
                $plan_type = $matches[2];

                $duration_days = ($plan_type === 'yearly') ? 365 : 30;
                $new_expires = date('Y-m-d H:i:s', strtotime("+$duration_days days"));

                $db->beginTransaction();
                try {
                    // Update user plan
                    $updateUser = $db->prepare("UPDATE users SET plan_type = ?, plan_expires_at = ? WHERE id = ?");
                    $updateUser->execute(['premium', $new_expires, $user_id]);

                    // Insert into subscriptions log
                    $insertSub = $db->prepare("INSERT INTO subscriptions (user_id, mp_payment_id, plan_type, status, start_date, end_date) VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP, ?)");
                    $insertSub->execute([$user_id, $payment_id, $plan_type, 'approved', $new_expires]);

                    $db->commit();
                } catch (Exception $e) {
                    $db->rollBack();
                    file_put_contents('mp_webhook_error.log', "Database Error: " . $e->getMessage() . "\n", FILE_APPEND);
                }
            }
        }
    }
}

http_response_code(200);
echo json_encode(['status' => 'success']);
