<?php
// zapcrm/api/db.php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    die();
}

// Database Connection
$dbFile = __DIR__ . '/db.sqlite';
$dsn = "sqlite:" . $dbFile;

try {
    $db = new PDO($dsn);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create tables if they don't exist
    $db->exec("CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        email TEXT UNIQUE NOT NULL,
        password TEXT NOT NULL,
        role TEXT DEFAULT 'agent' -- 'admin' or 'agent'
    )");

    $db->exec("CREATE TABLE IF NOT EXISTS contacts (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        phone TEXT UNIQUE NOT NULL,
        name TEXT,
        stage TEXT DEFAULT 'novo', -- 'novo', 'atendimento', 'humano', 'finalizado'
        last_message TEXT,
        last_interaction DATETIME DEFAULT CURRENT_TIMESTAMP,
        bot_paused INTEGER DEFAULT 0 -- 1 = bot will not reply
    )");

    $db->exec("CREATE TABLE IF NOT EXISTS messages (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        contact_id INTEGER NOT NULL,
        sender TEXT NOT NULL, -- 'bot', 'user' (agent), 'client'
        content TEXT NOT NULL,
        timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (contact_id) REFERENCES contacts(id)
    )");

    $db->exec("CREATE TABLE IF NOT EXISTS knowledge (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        type TEXT NOT NULL, -- 'text', 'pdf'
        title TEXT NOT NULL,
        content TEXT NOT NULL, -- Extracted text
        timestamp DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    $db->exec("CREATE TABLE IF NOT EXISTS settings (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        key_name TEXT UNIQUE,
        value_data TEXT
    )");

    // Insert default admin if none exists
    $stmt = $db->query("SELECT COUNT(*) FROM users");
    if ($stmt->fetchColumn() == 0) {
        $defaultPassword = password_hash('admin123', PASSWORD_DEFAULT);
        $db->exec("INSERT INTO users (name, email, password, role) VALUES ('Admin', 'admin@zapcrm.com', '$defaultPassword', 'admin')");
    }

    // Default settings
    $stmt = $db->query("SELECT COUNT(*) FROM settings");
    if ($stmt->fetchColumn() == 0) {
        $db->exec("INSERT INTO settings (key_name, value_data) VALUES ('gemini_api_key', '')");
        $db->exec("INSERT INTO settings (key_name, value_data) VALUES ('bot_url', '')");
        $db->exec("INSERT INTO settings (key_name, value_data) VALUES ('handoff_message', 'Vou te transferir para um de nossos especialistas. Aguarde um momento!')");
        $jwtSecret = bin2hex(random_bytes(32)); // Secure random secret for JWT
        $db->exec("INSERT INTO settings (key_name, value_data) VALUES ('jwt_secret', '$jwtSecret')");
        $botToken = bin2hex(random_bytes(16)); // Secure token for Webhook
        $db->exec("INSERT INTO settings (key_name, value_data) VALUES ('bot_token', '$botToken')");
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed: " . $e->getMessage()]);
    die();
}

function verifyAuth($db) {
    $headers = getallheaders();
    $token = isset($headers['Authorization']) ? str_replace('Bearer ', '', $headers['Authorization']) : null;

    if (!$token) {
        http_response_code(401);
        echo json_encode(['error' => 'No token provided']);
        die();
    }

    try {
        $tokenParts = explode('.', $token);
        if (count($tokenParts) !== 3) throw new Exception("Invalid token format");

        // Fetch JWT Secret
        $stmt = $db->query("SELECT value_data FROM settings WHERE key_name = 'jwt_secret'");
        $jwtSecret = $stmt->fetchColumn();

        // Verify Signature
        $header = $tokenParts[0];
        $payloadRaw = $tokenParts[1];
        $signatureProvided = $tokenParts[2];
        $signatureExpected = base64_encode(hash_hmac('sha256', "$header.$payloadRaw", $jwtSecret, true));
        $signatureExpected = str_replace(['+', '/', '='], ['-', '_', ''], $signatureExpected);

        // Base64Url decode payload
        $payloadDecoded = json_decode(base64_decode(str_replace(['-', '_'], ['+', '/'], $payloadRaw)), true);

        if ($signatureProvided !== $signatureExpected) {
            throw new Exception("Invalid signature");
        }

        if (!$payloadDecoded || !isset($payloadDecoded['user_id'])) throw new Exception("Invalid token payload");

        if (isset($payloadDecoded['exp']) && time() > $payloadDecoded['exp']) {
            throw new Exception("Token expired");
        }

        $stmt = $db->prepare("SELECT id, name, email, role FROM users WHERE id = ?");
        $stmt->execute([$payloadDecoded['user_id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            http_response_code(401);
            echo json_encode(['error' => 'User not found']);
            die();
        }

        return $user;

    } catch (Exception $e) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        die();
    }
}
?>
