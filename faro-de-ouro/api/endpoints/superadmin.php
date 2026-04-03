<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';

$user = require_auth();
if ($user['role'] !== 'superadmin') {
    http_response_code(403);
    die(json_encode(['error' => 'Superadmin access required']));
}

$db = getDB();
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

if ($method == 'GET') {
    if ($action == 'tenants') {
        $stmt = $db->query("SELECT * FROM tenants ORDER BY created_at DESC");
        echo json_encode($stmt->fetchAll());
    } elseif ($action == 'plans') {
        $stmt = $db->query("SELECT * FROM plans ORDER BY price ASC");
        echo json_encode($stmt->fetchAll());
    } elseif ($action == 'coupons') {
        $stmt = $db->query("SELECT * FROM coupons ORDER BY id DESC");
        echo json_encode($stmt->fetchAll());
    } elseif ($action == 'suggestions') {
        $stmt = $db->query("SELECT s.*, u.name as user_name, u.email as user_email FROM suggestions s JOIN users u ON s.user_id = u.id ORDER BY s.created_at DESC");
        echo json_encode($stmt->fetchAll());
    }
} elseif ($method == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    if ($action == 'create_plan') {
        $stmt = $db->prepare("INSERT INTO plans (name, duration_months, price, mp_link) VALUES (?, ?, ?, ?)");
        $stmt->execute([$data['name'], $data['duration_months'], $data['price'], $data['mp_link']]);
        echo json_encode(['success' => true]);
    } elseif ($action == 'create_coupon') {
        $stmt = $db->prepare("INSERT INTO coupons (code, discount_percent, free_months) VALUES (?, ?, ?)");
        $stmt->execute([$data['code'], $data['discount_percent'] ?? 0, $data['free_months'] ?? 0]);
        echo json_encode(['success' => true]);
    } elseif ($action == 'grant_access') {
        // Manually grant/extend access to a tenant
        $tenant_id = $data['tenant_id'];
        $days = $data['days'];
        $stmt = $db->prepare("UPDATE tenants SET plan_expires_at = datetime('now', '+$days days') WHERE id = ?");
        $stmt->execute([$tenant_id]);
        echo json_encode(['success' => true]);
    }
}
