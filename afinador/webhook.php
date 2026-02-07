<?php
require_once 'includes/db.php';
require_once 'includes/config.php';

// Retrieve the request's body and parse it as JSON
$input = @file_get_contents("php://input");
$event = json_decode($input, true);

if (!isset($event["type"])) {
    http_response_code(400); // Bad Request
    exit;
}

if ($event["type"] == "payment") {
    $payment_id = $event["data"]["id"];

    // Fetch payment details from Mercado Pago
    $url = "https://api.mercadopago.com/v1/payments/" . $payment_id;

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer " . MP_ACCESS_TOKEN
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode == 200) {
        $payment = json_decode($response, true);

        $status = $payment['status'];
        $external_reference = $payment['external_reference']; // This is our user_id
        $amount = $payment['transaction_amount'];

        if ($status == 'approved') {
            // Update user to premium
            try {
                // Insert payment record
                $stmt = $pdo->prepare("INSERT INTO payments (user_id, payment_id, status, amount) VALUES (?, ?, ?, ?)");
                $stmt->execute([$external_reference, $payment_id, $status, $amount]);

                // Update user status
                $stmt = $pdo->prepare("UPDATE users SET is_premium = 1 WHERE id = ?");
                $stmt->execute([$external_reference]);

                http_response_code(200); // OK
            } catch (PDOException $e) {
                http_response_code(500); // Server Error
            }
        }
    } else {
        http_response_code(404); // Not Found
    }
} else {
    http_response_code(200); // Acknowledge other notifications
}
?>
