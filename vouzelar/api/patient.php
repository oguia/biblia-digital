<?php
require_once 'db.php';
require_once 'jwt.php';
session_start();

function respond($data, $status = 200) {
    http_response_code($status);
    echo json_encode($data);
    exit;
}

// Basic Authentication Middleware
$headers = getallheaders();
$authHeader = $headers['Authorization'] ?? '';
$token = str_replace('Bearer ', '', $authHeader);

if (!$token) respond(['error' => 'Não autorizado'], 401);

$payload = JWT::decode($token);
if (!$payload) respond(['error' => 'Token inválido ou expirado.'], 401);
if (!$payload || !isset($payload['user_id'])) respond(['error' => 'Token inválido'], 401);

$userId = $payload['user_id'];
$userRole = $payload['role'];

$db = DB::getInstance()->getConnection();
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';
$input = json_decode(file_get_contents('php://input'), true);

if ($action === 'today_meds') {
    if ($method === 'GET') {
        // Find meds for this patient
        $stmt = $db->prepare("SELECT id, name, dosage FROM medications WHERE patient_id = ?");
        $stmt->execute([$userId]);
        $meds = $stmt->fetchAll();

        // Mocking the daily checklist logic for the MVP
        // In a real scenario, this would check dose_history to see if already taken today
        $checklist = [];
        foreach ($meds as $m) {
            $checklist[] = [
                'id' => $m['id'],
                'name' => $m['name'],
                'dosage' => $m['dosage'],
                'status' => 'pending' // pending, taken
            ];
        }
        respond($checklist);
    }
}

if ($action === 'take_med') {
    if ($method === 'POST') {
        $medId = $input['medication_id'] ?? null;
        if (!$medId) respond(['error' => 'ID missing'], 400);

        // Record dose
        $stmt = $db->prepare("INSERT INTO dose_history (medication_id, patient_id, scheduled_time, taken_at, status) VALUES (?, ?, datetime('now'), datetime('now'), 'taken')");
        $stmt->execute([$medId, $userId]);

        // Decrease stock
        $stmtStock = $db->prepare("UPDATE medications SET stock_current = stock_current - 1 WHERE id = ? AND stock_current > 0");
        $stmtStock->execute([$medId]);

        // TODO: Here we could trigger a check for Push Notifications to the family if stock <= stock_minimum

        respond(['message' => 'Dose confirmada!']);
    }
}

respond(['error' => 'Ação não suportada.'], 400);
