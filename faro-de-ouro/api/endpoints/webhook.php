<?php
require_once __DIR__ . '/../includes/config.php';

$db = getDB();

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (isset($data['action']) && $data['action'] == 'payment.created') {
    $payment_id = $data['data']['id'];

    $stmt = $db->query("SELECT mp_access_token FROM settings LIMIT 1");
    $settings = $stmt->fetch();
    if (!$settings || empty($settings['mp_access_token'])) {
        http_response_code(500);
        die("Token not configured");
    }

    $token = $settings['mp_access_token'];

    // Obter dados do pagamento
    $ch = curl_init("https://api.mercadopago.com/v1/payments/$payment_id");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $token
    ]);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode == 200) {
        $paymentData = json_decode($response, true);
        if ($paymentData['status'] == 'approved') {
            // Find user by email (Mercado Pago payer email)
            $payerEmail = $paymentData['payer']['email'] ?? null;

            if ($payerEmail) {
                $stmt = $db->prepare("SELECT tenant_id FROM users WHERE email = ? LIMIT 1");
                $stmt->execute([$payerEmail]);
                $user = $stmt->fetch();

                if ($user) {
                     // Get preapproval_plan_id to match with our plans or default to +1 month
                     // Simplification: Grant 30 days per payment. A robust implementation would match the exact plan duration.
                     // Instead of flat 31 days, we try to match the exact plan duration from MP recurring amount or assume 30 days minimum
                     $months = 1;
                     if (isset($paymentData['transaction_amount'])) {
                          $amt = $paymentData['transaction_amount'];
                          $stmtPlan = $db->prepare("SELECT duration_months FROM plans WHERE price = ? LIMIT 1");
                          $stmtPlan->execute([$amt]);
                          $planFound = $stmtPlan->fetch();
                          if ($planFound) {
                              $months = $planFound['duration_months'];
                          }
                     }
                     $days = $months * 31;

                     $stmt = $db->prepare("
                         UPDATE tenants
                         SET plan_expires_at = CASE
                             WHEN plan_expires_at IS NOT NULL AND plan_expires_at > CURRENT_TIMESTAMP
                             THEN datetime(plan_expires_at, '+$days days')
                             ELSE datetime('now', '+$days days')
                         END
                         WHERE id = ?
                     ");
                     $stmt->execute([$user['tenant_id']]);
                }
            }
        }
    }
}

http_response_code(200);
echo json_encode(['received' => true]);
