<?php
// api/owner_business.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-User-ID");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once 'config.php';

$userId = $_SERVER['HTTP_X_USER_ID'] ?? $_GET['user_id'] ?? null;

if (!$userId) {
    http_response_code(401);
    echo json_encode(["error" => "User ID required"]);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    // Get business(es) for this user
    try {
        $stmt = $pdo->prepare("
            SELECT b.*, c.name as category_name
            FROM businesses b
            LEFT JOIN categories c ON b.category_id = c.id
            WHERE b.user_id = ?
        ");
        $stmt->execute([$userId]);
        $businesses = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($businesses);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["error" => $e->getMessage()]);
    }
}

if ($method === 'POST') {
    // Create or Update
    $data = json_decode(file_get_contents("php://input"), true);

    $action = $data['action'] ?? 'update'; // 'create', 'update', 'claim'

    try {
        if ($action === 'create') {
            // Validate input
            if (empty($data['name']) || empty($data['category'])) {
                throw new Exception("Name and Category required");
            }

            // Handle Category (find or create)
            $catName = $data['category'];
            $stmt = $pdo->prepare("SELECT id FROM categories WHERE name = ?");
            $stmt->execute([$catName]);
            $catId = $stmt->fetchColumn();
            if (!$catId) {
                $slugCat = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $catName)));
                $stmtIns = $pdo->prepare("INSERT INTO categories (name, slug) VALUES (?, ?)");
                $stmtIns->execute([$catName, $slugCat]);
                $catId = $pdo->lastInsertId();
            }

            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $data['name']))) . '-' . uniqid();
            $createdAt = date('Y-m-d H:i:s');

            $stmt = $pdo->prepare("
                INSERT INTO businesses (user_id, name, slug, description, category_id, address, city, phone, whatsapp, lat, lng, created_at)
                VALUES (?, ?, ?, ?, ?, ?, 'Curitiba', ?, ?, ?, ?, ?)
            ");

            $stmt->execute([
                $userId,
                $data['name'],
                $slug,
                $data['description'] ?? '',
                $catId,
                $data['address'] ?? '',
                $data['phone'] ?? '',
                $data['whatsapp'] ?? '',
                $data['lat'] ?? -25.4284,
                $data['lng'] ?? -49.2733,
                $createdAt
            ]);

            echo json_encode(["success" => true, "id" => $pdo->lastInsertId()]);

        } elseif ($action === 'update') {
            $bizId = $data['id'];
            if (!$bizId) throw new Exception("Business ID required for update");

            // Verify ownership
            $stmt = $pdo->prepare("SELECT id FROM businesses WHERE id = ? AND user_id = ?");
            $stmt->execute([$bizId, $userId]);
            if (!$stmt->fetch()) {
                http_response_code(403);
                echo json_encode(["error" => "Unauthorized: You do not own this business"]);
                exit;
            }

            $stmt = $pdo->prepare("
                UPDATE businesses
                SET name = ?, description = ?, address = ?, phone = ?, whatsapp = ?, website = ?, image_url = ?
                WHERE id = ?
            ");

            $stmt->execute([
                $data['name'],
                $data['description'],
                $data['address'],
                $data['phone'],
                $data['whatsapp'],
                $data['website'],
                $data['image_url'],
                $bizId
            ]);

            echo json_encode(["success" => true]);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["error" => $e->getMessage()]);
    }
}
