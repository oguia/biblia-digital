<?php
require_once 'config.php';

$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents('php://input'), true);

// Auth check (Simple mock)
$userId = $_SERVER['HTTP_X_USER_ID'] ?? ($data['user_id'] ?? null);

if ($method === 'GET') {
    // Get business details
    $id = $_GET['id'] ?? null;
    $slug = $_GET['slug'] ?? null;

    if ($id) {
        $stmt = $pdo->prepare("SELECT b.*, c.name as category_name FROM businesses b LEFT JOIN categories c ON b.category_id = c.id WHERE b.id = ?");
        $stmt->execute([$id]);
    } elseif ($slug) {
        $stmt = $pdo->prepare("SELECT b.*, c.name as category_name FROM businesses b LEFT JOIN categories c ON b.category_id = c.id WHERE b.slug = ?");
        $stmt->execute([$slug]);
    } else {
        // List user's businesses
        if (!$userId) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }
        $stmt = $pdo->prepare("SELECT * FROM businesses WHERE user_id = ?");
        $stmt->execute([$userId]);
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        exit;
    }

    $business = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($business) {
        echo json_encode($business);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Business not found']);
    }
    exit;
}

if ($method === 'POST') {
    // Create new business
    if (!$userId) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    // Validate fields
    if (empty($data['name']) || empty($data['category_id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required fields']);
        exit;
    }

    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $data['name']))) . '-' . time();

    try {
        $stmt = $pdo->prepare("INSERT INTO businesses (user_id, name, slug, description, category_id, address, city, state, phone, whatsapp, website, image_url, lat, lng) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $userId,
            $data['name'],
            $slug,
            $data['description'] ?? '',
            $data['category_id'],
            $data['address'] ?? '',
            $data['city'] ?? 'Curitiba',
            $data['state'] ?? 'PR',
            $data['phone'] ?? '',
            $data['whatsapp'] ?? '',
            $data['website'] ?? '',
            $data['image_url'] ?? '',
            $data['lat'] ?? 0,
            $data['lng'] ?? 0
        ]);
        echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}

if ($method === 'PUT') {
    // Update business
    if (!$userId) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    $id = $_GET['id'] ?? null;
    if (!$id) {
         http_response_code(400); echo json_encode(['error' => 'Missing ID']); exit;
    }

    // Check ownership
    $stmt = $pdo->prepare("SELECT user_id FROM businesses WHERE id = ?");
    $stmt->execute([$id]);
    if ($stmt->fetchColumn() != $userId) {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Build update query dynamically
    $fields = [];
    $params = [];
    $allowed = ['name', 'description', 'category_id', 'address', 'city', 'phone', 'whatsapp', 'website', 'image_url', 'lat', 'lng'];

    foreach ($allowed as $field) {
        if (isset($data[$field])) {
            $fields[] = "$field = ?";
            $params[] = $data[$field];
        }
    }

    if (empty($fields)) {
        echo json_encode(['success' => true, 'message' => 'No changes']);
        exit;
    }

    $params[] = $id;
    try {
        $sql = "UPDATE businesses SET " . implode(', ', $fields) . " WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}
