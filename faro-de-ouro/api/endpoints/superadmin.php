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
    } elseif ($action == 'settings') {
        $stmt = $db->query("SELECT mp_access_token FROM settings LIMIT 1");
        $settings = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
        $stmt = $db->query("SELECT email FROM users WHERE role = 'superadmin' LIMIT 1");
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        $settings['email'] = $admin['email'];
        echo json_encode($settings);
    } elseif ($action == 'suggestions') {
        $stmt = $db->query("SELECT s.*, u.name as user_name, u.email as user_email FROM suggestions s JOIN users u ON s.user_id = u.id ORDER BY s.created_at DESC");
        echo json_encode($stmt->fetchAll());
    }
} elseif ($method == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    if ($action == 'login_as') {
        $tenant_id = $data['tenant_id'];
        $stmt = $db->prepare("SELECT * FROM users WHERE tenant_id = ? AND role = 'owner' LIMIT 1");
        $stmt->execute([$tenant_id]);
        $targetUser = $stmt->fetch();
        if ($targetUser) {
            $payload = [
                'id' => $targetUser['id'],
                'email' => $targetUser['email'],
                'name' => 'Support ('.$targetUser['name'].')',
                'role' => $targetUser['role'],
                'tenant_id' => $targetUser['tenant_id'],
                'exp' => time() + (86400)
            ];
            echo json_encode([
                'token' => generate_jwt($payload),
                'user' => ['id' => $targetUser['id'], 'name' => 'Support ('.$targetUser['name'].')', 'role' => $targetUser['role'], 'tenant_id' => $targetUser['tenant_id']],
                'access_status' => 'active'
            ]);
        } else {
            http_response_code(404); echo json_encode(['error' => 'No owner found for this tenant']);
        }
        die();
    }
    $data = json_decode(file_get_contents('php://input'), true);
    if ($action == 'create_plan') {
        // Create MP Plan
        $stmt = $db->query("SELECT mp_access_token FROM settings LIMIT 1");
        $settings = $stmt->fetch();
        if(!$settings || empty($settings['mp_access_token'])) {
             http_response_code(400);
             die(json_encode(['error' => 'Configure o Access Token do Mercado Pago em Configurações primeiro.']));
        }

        $mp_link = $data['mp_link'] ?? '';
        $plan_name = $data['name'];
        $price = $data['price'];
        $months = $data['duration_months'];
        $token = $settings['mp_access_token'];

        // Auto-generate via MP API if mp_link is empty
        if(empty($mp_link)) {
            $payload = [
                'reason' => 'Faro de Ouro - ' . $plan_name,
                'auto_recurring' => [
                    'frequency' => $months,
                    'frequency_type' => 'months',
                    'transaction_amount' => (float)$price,
                    'currency_id' => 'BRL'
                ],
                'back_url' => 'https://farodeouro.com.br/#/app'
            ];

            $ch = curl_init('https://api.mercadopago.com/preapproval_plan');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
                'Content-Type: application/json'
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode == 201 || $httpCode == 200) {
                $mpData = json_decode($response, true);
                $mp_link = $mpData['init_point'] ?? '';
            } else {
                 http_response_code(400);
                 die(json_encode(['error' => 'Erro na API do Mercado Pago: ' . $response]));
            }
        }

        $stmt = $db->prepare("INSERT INTO plans (name, duration_months, price, mp_link) VALUES (?, ?, ?, ?)");
        $stmt->execute([$plan_name, $months, $price, $mp_link]);
        echo json_encode(['success' => true]);
    } elseif ($action == 'create_coupon') {
        $stmt = $db->prepare("INSERT INTO coupons (code, discount_percent, free_months) VALUES (?, ?, ?)");
        $stmt->execute([$data['code'], $data['discount_percent'] ?? 0, $data['free_months'] ?? 0]);
        echo json_encode(['success' => true]);
    } elseif ($action == 'update_credentials') {
        $email = $data['email'];
        $password = $data['password'];
        if ($password) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $db->prepare("UPDATE users SET email = ?, password = ? WHERE role = 'superadmin'");
            $stmt->execute([$email, $hash]);
        } else {
            $stmt = $db->prepare("UPDATE users SET email = ? WHERE role = 'superadmin'");
            $stmt->execute([$email]);
        }
        echo json_encode(['success' => true]);
    } elseif ($action == 'update_settings') {
        $mp = $data['mp_access_token'];
        $stmt = $db->query("SELECT COUNT(*) FROM settings");
        if ($stmt->fetchColumn() == 0) {
            $stmt = $db->prepare("INSERT INTO settings (mp_access_token) VALUES (?)");
            $stmt->execute([$mp]);
        } else {
            $stmt = $db->prepare("UPDATE settings SET mp_access_token = ?");
            $stmt->execute([$mp]);
        }
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
