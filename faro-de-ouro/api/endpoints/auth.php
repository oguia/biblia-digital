<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';

$db = getDB();
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';
$data = json_decode(file_get_contents('php://input'), true);

if ($method == 'POST' && $action == 'register') {
    $name = $data['name'] ?? '';
    $email = $data['email'] ?? '';
    $password = $data['password'] ?? '';
    $type = $data['type'] ?? 'PF'; // PF or PJ
    $document = $data['document'] ?? '';
    $tenantName = $data['tenantName'] ?? $name;

    if (!$email || !$password || !$document) {
        http_response_code(400);
        die(json_encode(['error' => 'Missing fields']));
    }

    try {
        $db->beginTransaction();

        // 7 days trial
        $trial_ends = date('Y-m-d H:i:s', strtotime('+7 days'));

        $stmt = $db->prepare("INSERT INTO tenants (name, type, document, trial_ends_at) VALUES (?, ?, ?, ?)");
        $stmt->execute([$tenantName, $type, $document, $trial_ends]);
        $tenant_id = $db->lastInsertId();

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $db->prepare("INSERT INTO users (tenant_id, name, email, password, role) VALUES (?, ?, ?, ?, 'owner')");
        $stmt->execute([$tenant_id, $name, $email, $hash]);
        $user_id = $db->lastInsertId();

        $db->commit();
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        $db->rollBack();
        http_response_code(400);
        echo json_encode(['error' => 'Email already registered or error: ' . $e->getMessage()]);
    }
} elseif ($method == 'POST' && $action == 'login') {
    $email = $data['email'] ?? '';
    $password = $data['password'] ?? '';

    $stmt = $db->prepare("SELECT u.*, t.trial_ends_at, t.plan_expires_at FROM users u LEFT JOIN tenants t ON u.tenant_id = t.id WHERE u.email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $payload = [
            'id' => $user['id'],
            'email' => $user['email'],
            'name' => $user['name'],
            'role' => $user['role'],
            'tenant_id' => $user['tenant_id'],
            'exp' => time() + (86400 * 30) // 30 days
        ];

        $access_status = 'active';
        if ($user['role'] !== 'superadmin') {
            $now = time();
            $trial = strtotime($user['trial_ends_at']);
            $plan = $user['plan_expires_at'] ? strtotime($user['plan_expires_at']) : 0;

            if ($now > $trial && $now > $plan) {
                $access_status = 'expired';
            }
        }

        echo json_encode([
            'token' => generate_jwt($payload),
            'user' => [
                'id' => $user['id'],
                'name' => $user['name'],
                'role' => $user['role'],
                'tenant_id' => $user['tenant_id']
            ],
            'access_status' => $access_status
        ]);
    } else {
        http_response_code(401);
        echo json_encode(['error' => 'Invalid credentials', 'debug_user_found' => !!$user, 'debug_email' => $email]);
    }
} elseif ($method == 'POST' && $action == 'apply_coupon') {
    $user = require_auth();
    $code = $data['code'] ?? '';
    $stmt = $db->prepare("SELECT * FROM coupons WHERE code = ? AND is_active = 1");
    $stmt->execute([$code]);
    $coupon = $stmt->fetch();
    if ($coupon) {
        $months = $coupon['free_months'];
        $days = $months * 30;
        $stmt = $db->prepare("UPDATE tenants SET plan_expires_at = datetime('now', '+$days days') WHERE id = ?");
        $stmt->execute([$user['tenant_id']]);
        echo json_encode(['success' => true]);
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid coupon']);
    }
} elseif ($method == 'GET' && $action == 'me') {
    $user = require_auth();
    if ($user['role'] !== 'superadmin') {
        $stmt = $db->prepare("SELECT trial_ends_at, plan_expires_at FROM tenants WHERE id = ?");
        $stmt->execute([$user['tenant_id']]);
        $tenant = $stmt->fetch();

        $now = time();
        $trial = strtotime($tenant['trial_ends_at']);
        $plan = $tenant['plan_expires_at'] ? strtotime($tenant['plan_expires_at']) : 0;

        $user['access_status'] = ($now > $trial && $now > $plan) ? 'expired' : 'active';
    } else {
         $user['access_status'] = 'active';
    }
    echo json_encode(['user' => $user]);
}
