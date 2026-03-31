<?php
require_once 'db.php';
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

if (!$token) {
    respond(['error' => 'Não autorizado'], 401);
}

$payload = json_decode(base64_decode($token), true);
if (!$payload || !isset($payload['user_id'])) {
    respond(['error' => 'Token inválido'], 401);
}

$userId = $payload['user_id'];
$userRole = $payload['role'];

$db = DB::getInstance()->getConnection();
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';
$input = json_decode(file_get_contents('php://input'), true);

// Endpoint to manage patients (elderly profile)
if ($action === 'patients') {
    if ($method === 'GET') {
        $stmt = $db->prepare("SELECT id, name, email FROM users WHERE family_group_id = (SELECT family_group_id FROM users WHERE id = ?) AND role = 'patient'");
        $stmt->execute([$userId]);
        respond($stmt->fetchAll());
    }

    if ($method === 'POST') {
        if ($userRole === 'patient') respond(['error' => 'Permissão negada'], 403);

        $name = $input['name'] ?? '';
        $login = $input['login'] ?? ''; // unique id for patient to login easily
        $password = $input['password'] ?? '';

        if (!$name || !$login || !$password) respond(['error' => 'Preencha todos os campos.'], 400);

        // Find family_group_id
        $stmtGrp = $db->prepare("SELECT family_group_id FROM users WHERE id = ?");
        $stmtGrp->execute([$userId]);
        $familyId = $stmtGrp->fetchColumn();

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $db->prepare("INSERT INTO users (family_group_id, name, email, password_hash, role) VALUES (?, ?, ?, ?, 'patient')");
        if ($stmt->execute([$familyId, $name, $login, $hash])) {
            respond(['message' => 'Paciente cadastrado com sucesso!', 'id' => $db->lastInsertId()]);
        }
        respond(['error' => 'Erro ao cadastrar.'], 500);
    }
}

// Endpoint to manage medications and stock
if ($action === 'medications') {
    if ($method === 'GET') {
        $patientId = $_GET['patient_id'] ?? null;
        if (!$patientId) respond(['error' => 'Patient ID required'], 400);

        $stmt = $db->prepare("SELECT * FROM medications WHERE patient_id = ?");
        $stmt->execute([$patientId]);

        $meds = $stmt->fetchAll();
        // Calculate days remaining
        foreach ($meds as &$med) {
            $daysLeft = 0;
            if ($med['times_per_day'] > 0) {
                $daysLeft = floor($med['stock_current'] / $med['times_per_day']);
            }
            $med['days_remaining'] = $daysLeft;
            $med['stock_status'] = $med['stock_current'] <= $med['stock_minimum'] ? 'low' : 'ok';
        }
        respond($meds);
    }

    if ($method === 'POST') {
        if ($userRole === 'patient') respond(['error' => 'Permissão negada'], 403);

        $patientId = $input['patient_id'] ?? null;
        $name = $input['name'] ?? '';
        $dosage = $input['dosage'] ?? '';
        $stockCurrent = $input['stock_current'] ?? 0;
        $timesPerDay = $input['times_per_day'] ?? 1;
        $specificTimes = json_encode($input['specific_times'] ?? []);

        if (!$patientId || !$name) respond(['error' => 'Dados incompletos'], 400);

        $stmt = $db->prepare("INSERT INTO medications (patient_id, name, dosage, times_per_day, specific_times, stock_current) VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$patientId, $name, $dosage, $timesPerDay, $specificTimes, $stockCurrent])) {
            respond(['message' => 'Remédio adicionado.', 'id' => $db->lastInsertId()]);
        }
    }
}

respond(['error' => 'Endpoint não encontrado.'], 404);
