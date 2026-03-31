<?php
require_once 'db.php';
session_start();

function respond($data, $status = 200) {
    http_response_code($status);
    echo json_encode($data);
    exit;
}

// Ensure the user is Super Admin
$headers = getallheaders();
$authHeader = $headers['Authorization'] ?? '';
$token = str_replace('Bearer ', '', $authHeader);

if (!$token) respond(['error' => 'Não autorizado'], 401);

$payload = json_decode(base64_decode($token), true);
if (!$payload || !isset($payload['user_id'])) respond(['error' => 'Token inválido'], 401);

if ($payload['role'] !== 'superadmin') {
    respond(['error' => 'Acesso Negado. Esta área é restrita aos donos do sistema.'], 403);
}

$db = DB::getInstance()->getConnection();
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    // 1. Total users
    $stmt = $db->query("SELECT COUNT(*) FROM users");
    $totalUsers = $stmt->fetchColumn();

    // 2. Breakdown by role
    $stmt = $db->query("SELECT role, COUNT(*) as count FROM users GROUP BY role");
    $usersByRole = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

    // 3. Breakdown by plan (for admins/managers only)
    $stmt = $db->query("SELECT plan, COUNT(*) as count FROM users WHERE role = 'admin' OR role = 'superadmin' GROUP BY plan");
    $usersByPlan = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

    // 4. Estimate MRR (Monthly Recurring Revenue)
    // Assume active subscriptions are paying (for MVP purposes)
    $mrr = 0;
    if (isset($usersByPlan['individual'])) $mrr += $usersByPlan['individual'] * 5.00;
    if (isset($usersByPlan['family'])) $mrr += $usersByPlan['family'] * 19.90;

    // 5. Top Medications Tracked
    $stmt = $db->query("SELECT name, COUNT(*) as tracking_count FROM medications GROUP BY name ORDER BY tracking_count DESC LIMIT 5");
    $topMeds = $stmt->fetchAll();

    respond([
        'total_users' => $totalUsers,
        'users_by_role' => $usersByRole,
        'users_by_plan' => $usersByPlan,
        'estimated_mrr' => $mrr,
        'top_meds' => $topMeds
    ]);
}

respond(['error' => 'Method not allowed'], 405);
