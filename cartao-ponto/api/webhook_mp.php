<?php
// webhook_mp.php
require 'db.php';

// Mercado Pago sends notifications via POST, either x-www-form-urlencoded or JSON
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data && isset($_POST['data_id'])) {
    $data = $_POST;
}

if (!isset($data['action']) && !isset($data['type'])) {
    http_response_code(400);
    exit;
}

// Log webhook for debugging
file_put_contents('mp_webhook.log', date('Y-m-d H:i:s') . " - " . print_r($data, true) . "\n", FILE_APPEND);

// Get MP Access Token
$stmt = $pdo->query("SELECT value FROM settings WHERE key = 'mp_access_token'");
$setting = $stmt->fetch();

if (!$setting || empty($setting['value'])) {
    http_response_code(400);
    exit;
}

$access_token = $setting['value'];

// Determine notification type
$id = null;
$type = null;

if (isset($data['action']) && $data['action'] == 'payment.created') {
    $id = $data['data']['id'];
    $type = 'payment';
} else if (isset($data['type']) && $data['type'] == 'payment') {
    $id = $data['data']['id'];
    $type = 'payment';
} else if (isset($data['type']) && $data['type'] == 'subscription_preapproval') {
    $id = $data['data']['id'];
    $type = 'subscription';
} else if (isset($data['action']) && strpos($data['action'], 'subscription') !== false) {
    $id = $data['data']['id'];
    $type = 'subscription';
}

if ($id && $type === 'payment') {
    // Verify one-time payment status with Mercado Pago API
    $ch = curl_init("https://api.mercadopago.com/v1/payments/" . $id);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer " . $access_token
    ]);

    // Bypass SSL issues if any on shared hosting
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code == 200) {
        $paymentInfo = json_decode($response, true);

        if ($paymentInfo['status'] == 'approved') {
            $extRef = json_decode($paymentInfo['external_reference'], true);
            if ($extRef && isset($extRef['user_id']) && isset($extRef['plan'])) {
                processPlanUpgrade($pdo, $extRef['user_id'], $extRef['plan']);
            }
        }
    }
} else if ($id && $type === 'subscription') {
    // Verify subscription status
    $ch = curl_init("https://api.mercadopago.com/preapproval/" . $id);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer " . $access_token
    ]);

    // Bypass SSL issues if any on shared hosting
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code == 200) {
        $subInfo = json_decode($response, true);

        if ($subInfo['status'] == 'authorized') {
            $extRef = json_decode($subInfo['external_reference'], true);
            if ($extRef && isset($extRef['user_id']) && isset($extRef['plan'])) {
                processPlanUpgrade($pdo, $extRef['user_id'], $extRef['plan']);
            }
        }
    }
}

function processPlanUpgrade($pdo, $userId, $plan) {
    // Get current user to see if they already have an active plan
    $stmt = $pdo->prepare("SELECT plan_expires_at FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch();

    if ($user) {
        $current_expires = strtotime($user['plan_expires_at']);
        $now = time();

        // If plan hasn't expired, add to the existing expiry date. Otherwise, start from today.
        $base_date = ($current_expires > $now) ? $current_expires : $now;

        $months = 0;
        if ($plan == 'monthly') $months = 1;
        if ($plan == 'quarterly') $months = 3;
        if ($plan == 'annual') $months = 12;

        $new_expires_at = date('Y-m-d H:i:s', strtotime("+$months months", $base_date));

        // Update user
        $stmt = $pdo->prepare("UPDATE users SET plan_expires_at = ? WHERE id = ?");
        $stmt->execute([$new_expires_at, $userId]);

        file_put_contents('mp_webhook.log', date('Y-m-d H:i:s') . " - SUCCESS: User $userId updated. Expires at $new_expires_at.\n", FILE_APPEND);
    }
}

http_response_code(200);
echo "OK";
