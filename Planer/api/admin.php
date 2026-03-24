<?php
// Planer/api/admin.php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

require_once 'db.php';
require_once 'auth.php'; // Includes verify_auth_token

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// All endpoints in this file require authentication AND admin privileges
$user = verify_auth_token($db);

if ($user['is_admin'] != 1) {
    http_response_code(403);
    echo json_encode(['error' => 'Forbidden: Admins only']);
    exit;
}

$action = $_GET['action'] ?? '';
$input = json_decode(file_get_contents('php://input'), true);

if ($action === 'generate_invite') {
    $duration = isset($input['duration_days']) ? intval($input['duration_days']) : 36500; // Default lifetime
    $code = strtoupper(substr(md5(uniqid(rand(), true)), 0, 8)); // Generate 8 char code

    $stmt = $db->prepare("INSERT INTO invite_codes (code, created_by, plan_duration_days) VALUES (?, ?, ?)");
    if ($stmt->execute([$code, $user['id'], $duration])) {
        echo json_encode(['success' => true, 'code' => $code]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to generate code']);
    }
} elseif ($action === 'list_invites') {
    $stmt = $db->query("SELECT ic.*, u.name as used_by_name FROM invite_codes ic LEFT JOIN users u ON ic.used_by = u.id ORDER BY ic.created_at DESC");
    $codes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['codes' => $codes]);
} elseif ($action === 'upload_bncc') {
    // Process CSV file
    if (!isset($_FILES['csv_file'])) {
        http_response_code(400);
        echo json_encode(['error' => 'No file uploaded']);
        exit;
    }

    $file = $_FILES['csv_file']['tmp_name'];
    if (!is_uploaded_file($file)) {
        http_response_code(400);
        echo json_encode(['error' => 'Upload failed']);
        exit;
    }

    $handle = fopen($file, "r");
    if ($handle !== FALSE) {
        $db->beginTransaction();

        // Optional: clear existing bncc_skills if needed, or just insert/ignore. Let's do INSERT OR IGNORE.
        $stmt = $db->prepare("INSERT OR IGNORE INTO bncc_skills (code, description, component, year) VALUES (?, ?, ?, ?)");

        $row_num = 0;
        $inserted = 0;
        while (($data = fgetcsv($handle, 10000, ",")) !== FALSE) {
            $row_num++;
            // Skip header
            if ($row_num == 1) continue;

            // Expected columns (Code, Component, Year, Description) - Adjust as needed
            // Make sure to trim to handle whitespace
            $code = trim($data[0] ?? '');
            $component = trim($data[1] ?? '');
            $year = trim($data[2] ?? '');
            $description = trim($data[3] ?? '');

            if ($code && $description) {
                if ($stmt->execute([$code, $description, $component, $year])) {
                   if ($stmt->rowCount() > 0) $inserted++;
                }
            }
        }
        $db->commit();
        fclose($handle);

        echo json_encode(['success' => true, 'message' => "Imported $inserted new BNCC skills"]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Could not read CSV file']);
    }
} elseif ($action === 'stats') {
    $usersCount = $db->query("SELECT COUNT(*) FROM users")->fetchColumn();
    $plansCount = $db->query("SELECT COUNT(*) FROM lesson_plans")->fetchColumn();
    $skillsCount = $db->query("SELECT COUNT(*) FROM bncc_skills")->fetchColumn();

    echo json_encode([
        'total_users' => $usersCount,
        'total_lesson_plans' => $plansCount,
        'total_bncc_skills' => $skillsCount
    ]);
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Unknown admin action']);
}
