<?php
require_once 'db.php';
require_once 'jwt.php';
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

$payload = JWT::decode($token);
if (!$payload) respond(['error' => 'Token inválido ou expirado.'], 401);
if (!$payload || !isset($payload['user_id'])) respond(['error' => 'Token inválido'], 401);

if ($payload['role'] !== 'superadmin') {
    respond(['error' => 'Acesso Negado. Esta área é restrita aos donos do sistema.'], 403);
}

$db = DB::getInstance()->getConnection();
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? 'stats';

if ($action === 'generate_code' && $method === 'POST') {
    // Generate a random 6 char alphanumeric code
    $code = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 6));

    $stmt = $db->prepare("INSERT INTO invite_codes (code, created_by) VALUES (?, ?)");
    if ($stmt->execute([$code, $payload['user_id']])) {
        respond(['message' => 'Código gerado com sucesso', 'code' => $code]);
    }
    respond(['error' => 'Erro ao gerar código'], 500);
}

if ($action === 'stats' && $method === 'GET') {
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

    // 6. Recent Invite Codes
    $stmtCodes = $db->query("SELECT code, is_used, created_at FROM invite_codes ORDER BY created_at DESC LIMIT 10");
    $codes = $stmtCodes->fetchAll();

    respond([
        'total_users' => $totalUsers,
        'users_by_role' => $usersByRole,
        'users_by_plan' => $usersByPlan,
        'estimated_mrr' => $mrr,
        'top_meds' => $topMeds,
        'recent_codes' => $codes
    ]);
}

respond(['error' => 'Method not allowed'], 405);
