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

if (!$token) {
    respond(['error' => 'Não autorizado'], 401);
}

$payload = JWT::decode($token);
if (!$payload) respond(['error' => 'Token inválido ou expirado.'], 401);
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

        $stmt = $db->prepare("SELECT id, patient_id, name, dosage, times_per_day, specific_times, stock_current, stock_minimum, photo_url FROM medications WHERE patient_id = ?");
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
        $photoUrl = $input['photo_url'] ?? null; // Added photo support

        if (!$patientId || !$name) respond(['error' => 'Dados incompletos'], 400);

        $stmt = $db->prepare("INSERT INTO medications (patient_id, name, dosage, times_per_day, specific_times, stock_current, photo_url) VALUES (?, ?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$patientId, $name, $dosage, $timesPerDay, $specificTimes, $stockCurrent, $photoUrl])) {
            respond(['message' => 'Remédio adicionado.', 'id' => $db->lastInsertId()]);
        }
    }
}

if ($action === 'dashboard_stats') {
    if ($method === 'GET') {
        // Find family_group_id globally for this request
        $stmtGrp = $db->prepare("SELECT family_group_id FROM users WHERE id = ?");
        $stmtGrp->execute([$userId]);
        $familyId = $stmtGrp->fetchColumn();

        // Find recent active alerts: low stock or missed doses
        $alerts = [];

        // Check low stock
        $stmt = $db->prepare("
            SELECT m.name, m.stock_current, m.stock_minimum, u.name as patient_name
            FROM medications m
            JOIN users u ON m.patient_id = u.id
            WHERE u.family_group_id = ? AND m.stock_current <= m.stock_minimum
        ");
        $stmt->execute([$familyId]);
        $lowStock = $stmt->fetchAll();

        foreach ($lowStock as $med) {
            $alerts[] = [
                'type' => 'warning',
                'title' => 'Estoque Baixo!',
                'message' => "Restam apenas {$med['stock_current']} de {$med['name']} para {$med['patient_name']}."
            ];
        }

        // Just check if we have any patient to show the "no patients" alert
        if (empty($alerts)) {
            $stmtP = $db->prepare("SELECT COUNT(*) FROM users WHERE family_group_id = ? AND role = 'patient'");
            $stmtP->execute([$familyId]);
            $count = $stmtP->fetchColumn();

            if ($count == 0) {
                 $alerts[] = [
                    'type' => 'info',
                    'title' => 'Cadastre um paciente primeiro!',
                    'message' => 'Clique em Pacientes para adicionar o perfil do idoso.'
                ];
            } else {
                 $alerts[] = [
                    'type' => 'success',
                    'title' => 'Tudo em dia!',
                    'message' => 'Não há avisos ou remédios faltando no momento.'
                ];
            }
        }

        // Buscando histórico das últimas 5 doses confirmadas pela família
        $stmtHistory = $db->prepare("
            SELECT d.id, d.taken_at, m.name as medication_name, u.name as patient_name
            FROM dose_history d
            JOIN medications m ON d.medication_id = m.id
            JOIN users u ON d.patient_id = u.id
            WHERE u.family_group_id = ? AND d.status = 'taken'
            ORDER BY d.taken_at DESC LIMIT 5
        ");
        $stmtHistory->execute([$familyId]);
        $history = $stmtHistory->fetchAll();

        respond(['alerts' => $alerts, 'recent_doses' => $history]);
    }
}

// Mock Search Prices API (Since real pharmacies block server-side curl with Cloudflare)
if ($action === 'search_prices') {
    if ($method === 'GET') {
        $term = $_GET['q'] ?? '';
        if (!$term) respond(['error' => 'Missing query'], 400);

        // Generate a deterministic but pseudo-random base price based on the name length and characters
        $baseSeed = crc32(strtolower(trim($term)));
        $basePrice = 15 + ($baseSeed % 70); // Generates a number between 15 and 85
        $basePrice = $basePrice + (($baseSeed % 99) / 100); // Add some cents

        $termUrl = urlencode(trim($term));

        $results = [
            [
                'pharmacy' => 'Droga Raia',
                'distance' => '1.2 km',
                'price' => number_format($basePrice * 1.05, 2, ',', '.'),
                'raw_price' => $basePrice * 1.05,
                'link' => "https://www.drogaraia.com.br/search?w={$termUrl}",
                'isBest' => false
            ],
            [
                'pharmacy' => 'Pague Menos',
                'distance' => '2.5 km',
                'price' => number_format($basePrice, 2, ',', '.'),
                'raw_price' => $basePrice,
                'link' => "https://www.paguemenos.com.br/busca?q={$termUrl}",
                'isBest' => true
            ],
            [
                'pharmacy' => 'Panvel',
                'distance' => '3.0 km',
                'price' => number_format($basePrice * 1.15, 2, ',', '.'),
                'raw_price' => $basePrice * 1.15,
                'link' => "https://www.panvel.com/busca?q={$termUrl}",
                'isBest' => false
            ]
        ];

        // Sort by raw price
        usort($results, function($a, $b) {
            return $a['raw_price'] <=> $b['raw_price'];
        });

        respond(['results' => $results]);
    }
}

respond(['error' => 'Endpoint não encontrado.'], 404);
