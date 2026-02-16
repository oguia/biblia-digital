<?php
// api/stats.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once 'config.php';

$data = json_decode(file_get_contents("php://input"), true);

$businessId = $data['business_id'] ?? null;
$type = $data['type'] ?? 'view'; // view, whatsapp, phone

if (!$businessId) {
    echo json_encode(["error" => "Business ID required"]);
    exit;
}

try {
    // 1. Log Lead (History)
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $createdAt = date('Y-m-d H:i:s');
    $stmt = $pdo->prepare("INSERT INTO leads (business_id, type, ip_address, created_at) VALUES (?, ?, ?, ?)");
    $stmt->execute([$businessId, $type, $ip, $createdAt]);

    // 2. Update Counter (Fast Access)
    $column = '';
    if ($type === 'whatsapp') $column = 'whatsapp_clicks';
    elseif ($type === 'phone') $column = 'phone_clicks';
    else $column = 'views';

    // Whitelist column to prevent SQL injection
    if ($column) {
        $stmt = $pdo->prepare("UPDATE businesses SET $column = $column + 1 WHERE id = ?");
        $stmt->execute([$businessId]);
    }

    echo json_encode(["success" => true]);

} catch (Exception $e) {
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
}
