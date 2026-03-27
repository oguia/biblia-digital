<?php
// zapcrm/api/index.php
require 'db.php';
// Basic Auth & CRUD router

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Add a route to handle the webhook so we don't need a separate file
if (strpos($uri, '/webhook') !== false && $method === 'POST') {
    require 'webhook.php';
    die();
}

$data = json_decode(file_get_contents('php://input'), true);

if (strpos($uri, '/login') !== false && $method === 'POST') {
    $email = $data['email'] ?? '';
    $password = $data['password'] ?? '';

    $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Fetch JWT Secret
        $stmt = $db->query("SELECT value_data FROM settings WHERE key_name = 'jwt_secret'");
        $jwtSecret = $stmt->fetchColumn();

        // Secure JWT token generation
        $headerArray = ['alg' => 'HS256', 'typ' => 'JWT'];
        $payloadArray = ['user_id' => $user['id'], 'role' => $user['role'], 'exp' => time() + 86400]; // 1 day

        $headerBase64Url = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode(json_encode($headerArray)));
        $payloadBase64Url = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode(json_encode($payloadArray)));

        $signature = hash_hmac('sha256', $headerBase64Url . "." . $payloadBase64Url, $jwtSecret, true);
        $signatureBase64Url = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

        $token = $headerBase64Url . "." . $payloadBase64Url . "." . $signatureBase64Url;

        echo json_encode(['token' => $token, 'user' => ['id' => $user['id'], 'name' => $user['name'], 'email' => $user['email'], 'role' => $user['role']]]);
    } else {
        http_response_code(401);
        echo json_encode(['error' => 'Invalid credentials']);
    }
    die();
}

$user = verifyAuth($db);

if (strpos($uri, '/contacts') !== false) {
    if ($method === 'GET') {
        $stmt = $db->query("SELECT * FROM contacts ORDER BY last_interaction DESC");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    } elseif ($method === 'PUT') {
        $id = $data['id'] ?? null;
        $stage = $data['stage'] ?? null;
        if ($id && $stage) {
            // If moving back to bot stages, resume bot
            $botPaused = ($stage === 'humano' || $stage === 'finalizado') ? 1 : 0;

            $stmt = $db->prepare("UPDATE contacts SET stage = ?, bot_paused = ? WHERE id = ?");
            $stmt->execute([$stage, $botPaused, $id]);
            echo json_encode(['success' => true]);
        }
    }
} elseif (strpos($uri, '/messages') !== false) {
    if ($method === 'GET') {
        $contactId = $_GET['contact_id'] ?? null;
        if ($contactId) {
            $stmt = $db->prepare("SELECT * FROM messages WHERE contact_id = ? ORDER BY timestamp ASC");
            $stmt->execute([$contactId]);
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        }
    } elseif ($method === 'POST') {
        // Agent sending message
        $contactId = $data['contact_id'] ?? null;
        $content = $data['content'] ?? null;
        if ($contactId && $content) {
            $stmt = $db->prepare("INSERT INTO messages (contact_id, sender, content) VALUES (?, 'user', ?)");
            $stmt->execute([$contactId, $content]);

            // Get contact phone
            $stmtPhone = $db->prepare("SELECT phone FROM contacts WHERE id = ?");
            $stmtPhone->execute([$contactId]);
            $phone = $stmtPhone->fetchColumn();

            // Fetch bot config from DB
            $stmtUrl = $db->query("SELECT value_data FROM settings WHERE key_name = 'bot_url'");
            $botBaseUrl = rtrim($stmtUrl->fetchColumn(), '/');

            $stmtToken = $db->query("SELECT value_data FROM settings WHERE key_name = 'bot_token'");
            $botToken = $stmtToken->fetchColumn();

            if (empty($botBaseUrl)) {
                 http_response_code(400);
                 die(json_encode(['error' => 'URL do Bot não configurada.']));
            }

            $botUrl = $botBaseUrl . "/send";
            $postData = json_encode(['to' => $phone, 'message' => $content]);

            $ch = curl_init($botUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                "Authorization: Bearer {$botToken}"
            ]);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5); // Don't hang if bot offline

            // Bypass SSL for hostinger subdomains if needed
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

            $result = curl_exec($ch);
            curl_close($ch);

            echo json_encode(['success' => true]);
        }
    }
} elseif (strpos($uri, '/users') !== false) {
    if ($method === 'GET' && $user['role'] === 'admin') {
        $stmt = $db->query("SELECT id, name, email, role FROM users");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    } elseif ($method === 'POST' && $user['role'] === 'admin') {
        $name = $data['name'] ?? '';
        $email = $data['email'] ?? '';
        $password = password_hash($data['password'], PASSWORD_DEFAULT);
        $role = $data['role'] ?? 'agent';

        try {
            $stmt = $db->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $email, $password, $role]);
            echo json_encode(['success' => true]);
        } catch(PDOException $e) {
            http_response_code(400);
            echo json_encode(['error' => 'Email já existe.']);
        }
    } elseif ($method === 'PUT') {
        // Change password (admin can change anyone, user can change own)
        $id = $data['id'] ?? $user['id'];
        $password = $data['password'] ?? '';
        $name = $data['name'] ?? null;

        if ($user['role'] !== 'admin' && $id != $user['id']) {
            http_response_code(403);
            die(json_encode(['error' => 'Forbidden']));
        }

        $updates = [];
        $params = [];
        if (!empty($password)) {
            $updates[] = "password = ?";
            $params[] = password_hash($password, PASSWORD_DEFAULT);
        }
        if (!empty($name)) {
            $updates[] = "name = ?";
            $params[] = $name;
        }

        if (count($updates) > 0) {
            $params[] = $id;
            $stmt = $db->prepare("UPDATE users SET " . implode(', ', $updates) . " WHERE id = ?");
            $stmt->execute($params);
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No fields to update']);
        }
    } elseif ($method === 'DELETE' && $user['role'] === 'admin') {
        $id = $_GET['id'] ?? null;
        if ($id && $id != $user['id']) {
            $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode(['success' => true]);
        }
    }
} elseif (strpos($uri, '/settings') !== false && $user['role'] === 'admin') {
    if ($method === 'GET') {
        $stmt = $db->query("SELECT * FROM settings");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    } elseif ($method === 'POST') {
        foreach ($data as $key => $val) {
            $stmt = $db->prepare("UPDATE settings SET value_data = ? WHERE key_name = ?");
            $stmt->execute([$val, $key]);
        }
        echo json_encode(['success' => true]);
    }
} elseif (strpos($uri, '/knowledge') !== false && $user['role'] === 'admin') {
    if ($method === 'GET') {
        $stmt = $db->query("SELECT id, type, title, timestamp FROM knowledge ORDER BY timestamp DESC");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    } elseif ($method === 'POST') {
        $title = $data['title'] ?? 'Text';
        $content = $data['content'] ?? '';
        $type = 'text';

        $stmt = $db->prepare("INSERT INTO knowledge (type, title, content) VALUES (?, ?, ?)");
        $stmt->execute([$type, $title, $content]);
        echo json_encode(['success' => true, 'id' => $db->lastInsertId()]);
    } elseif ($method === 'DELETE') {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $stmt = $db->prepare("DELETE FROM knowledge WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode(['success' => true]);
        }
    }
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Endpoint not found or unauthorized']);
}
?>
