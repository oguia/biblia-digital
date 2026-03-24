<?php
// Planer/api/plans.php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

require_once 'db.php';
require_once 'auth.php'; // Includes verify_auth_token

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

$user = verify_auth_token($db);
$action = $_GET['action'] ?? '';
$input = json_decode(file_get_contents('php://input'), true);
$method = $_SERVER['REQUEST_METHOD'];

// Helper to check plan limit or active subscription
function check_subscription($user) {
    if ($user['is_admin'] == 1) return true;
    if ($user['plan_type'] === 'lifetime') return true;
    if ($user['plan_expires_at'] && strtotime($user['plan_expires_at']) > time()) return true;
    return false;
}

if ($method === 'GET') {
    if ($action === 'list') {
        $stmt = $db->prepare("SELECT * FROM lesson_plans WHERE user_id = ? ORDER BY lesson_date DESC, created_at DESC");
        $stmt->execute([$user['id']]);
        $plans = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetch skills for each plan
        foreach ($plans as &$plan) {
            $skillStmt = $db->prepare("SELECT s.* FROM bncc_skills s JOIN lesson_plan_skills ps ON s.id = ps.skill_id WHERE ps.lesson_plan_id = ?");
            $skillStmt->execute([$plan['id']]);
            $plan['skills'] = $skillStmt->fetchAll(PDO::FETCH_ASSOC);
        }

        echo json_encode(['plans' => $plans]);
    } elseif ($action === 'get') {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing plan ID']);
            exit;
        }

        $stmt = $db->prepare("SELECT * FROM lesson_plans WHERE id = ? AND user_id = ?");
        $stmt->execute([$id, $user['id']]);
        $plan = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($plan) {
            $skillStmt = $db->prepare("SELECT s.* FROM bncc_skills s JOIN lesson_plan_skills ps ON s.id = ps.skill_id WHERE ps.lesson_plan_id = ?");
            $skillStmt->execute([$plan['id']]);
            $plan['skills'] = $skillStmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['plan' => $plan]);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Plan not found']);
        }
    } elseif ($action === 'bncc_search') {
        $q = $_GET['q'] ?? '';
        $component = $_GET['component'] ?? '';
        $year = $_GET['year'] ?? '';

        $query = "SELECT * FROM bncc_skills WHERE 1=1";
        $params = [];

        if ($q) {
            $query .= " AND (code LIKE ? OR description LIKE ?)";
            $params[] = "%$q%";
            $params[] = "%$q%";
        }
        if ($component) {
            $query .= " AND component LIKE ?";
            $params[] = "%$component%";
        }
        if ($year) {
            $query .= " AND year LIKE ?";
            $params[] = "%$year%";
        }

        $query .= " LIMIT 50"; // Limit results for performance

        $stmt = $db->prepare($query);
        $stmt->execute($params);
        $skills = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['skills' => $skills]);
    } elseif ($action === 'list' || $action === '') {
        // Default to list if action is empty or list
        $stmt = $db->prepare("SELECT * FROM lesson_plans WHERE user_id = ? ORDER BY lesson_date DESC, created_at DESC");
        $stmt->execute([$user['id']]);
        $plans = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($plans as &$plan) {
            $skillStmt = $db->prepare("SELECT s.* FROM bncc_skills s JOIN lesson_plan_skills ps ON s.id = ps.skill_id WHERE ps.lesson_plan_id = ?");
            $skillStmt->execute([$plan['id']]);
            $plan['skills'] = $skillStmt->fetchAll(PDO::FETCH_ASSOC);
        }

        echo json_encode(['plans' => $plans]);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Unknown GET action']);
    }
} elseif ($method === 'POST') {
    if (!check_subscription($user)) {
        http_response_code(403);
        echo json_encode(['error' => 'Sua assinatura expirou. Renove para criar novos planos.']);
        exit;
    }

    $db->beginTransaction();
    try {
        $stmt = $db->prepare("INSERT INTO lesson_plans (user_id, title, component, objects_of_knowledge, biblical_relation, activity_description, materials, lesson_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $user['id'],
            $input['title'] ?? 'Plano sem título',
            $input['component'] ?? '',
            $input['objects_of_knowledge'] ?? '',
            $input['biblical_relation'] ?? '',
            $input['activity_description'] ?? '',
            $input['materials'] ?? '',
            $input['lesson_date'] ?? null
        ]);

        $plan_id = $db->lastInsertId();

        if (!empty($input['skills']) && is_array($input['skills'])) {
            $skillStmt = $db->prepare("INSERT INTO lesson_plan_skills (lesson_plan_id, skill_id) VALUES (?, ?)");
            foreach ($input['skills'] as $skill_id) {
                $skillStmt->execute([$plan_id, $skill_id]);
            }
        }

        $db->commit();
        echo json_encode(['success' => true, 'id' => $plan_id]);
    } catch (Exception $e) {
        $db->rollBack();
        http_response_code(500);
        echo json_encode(['error' => 'Failed to create plan: ' . $e->getMessage()]);
    }

} elseif ($method === 'PUT') {
    $id = $_GET['id'] ?? null;
    if (!$id) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing plan ID']);
        exit;
    }

    // Verify ownership
    $stmt = $db->prepare("SELECT id FROM lesson_plans WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $user['id']]);
    if (!$stmt->fetch()) {
        http_response_code(404);
        echo json_encode(['error' => 'Plan not found or not yours']);
        exit;
    }

    $db->beginTransaction();
    try {
        $stmt = $db->prepare("UPDATE lesson_plans SET title = ?, component = ?, objects_of_knowledge = ?, biblical_relation = ?, activity_description = ?, materials = ?, lesson_date = ? WHERE id = ?");
        $stmt->execute([
            $input['title'] ?? '',
            $input['component'] ?? '',
            $input['objects_of_knowledge'] ?? '',
            $input['biblical_relation'] ?? '',
            $input['activity_description'] ?? '',
            $input['materials'] ?? '',
            $input['lesson_date'] ?? null,
            $id
        ]);

        // Update skills: delete old, insert new
        $db->prepare("DELETE FROM lesson_plan_skills WHERE lesson_plan_id = ?")->execute([$id]);

        if (!empty($input['skills']) && is_array($input['skills'])) {
            $skillStmt = $db->prepare("INSERT INTO lesson_plan_skills (lesson_plan_id, skill_id) VALUES (?, ?)");
            foreach ($input['skills'] as $skill_id) {
                $skillStmt->execute([$id, $skill_id]);
            }
        }

        $db->commit();
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        $db->rollBack();
        http_response_code(500);
        echo json_encode(['error' => 'Failed to update plan']);
    }

} elseif ($method === 'DELETE') {
    $id = $_GET['id'] ?? null;
    if (!$id) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing plan ID']);
        exit;
    }

    $stmt = $db->prepare("DELETE FROM lesson_plans WHERE id = ? AND user_id = ?");
    if ($stmt->execute([$id, $user['id']])) {
        echo json_encode(['success' => true]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to delete plan']);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}
