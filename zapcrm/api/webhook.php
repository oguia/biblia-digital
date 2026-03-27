<?php
// zapcrm/api/webhook.php
require_once 'db.php';
require_once 'gemini.php';

// Verify Webhook Authenticity
$headers = getallheaders();
$authHeader = $headers['Authorization'] ?? '';
$authFile = __DIR__ . '/bot_auth.txt';
$validToken = file_exists($authFile) ? trim(file_get_contents($authFile)) : '';

if (empty($validToken) || $authHeader !== "Bearer " . $validToken) {
    http_response_code(401);
    die(json_encode(['error' => 'Unauthorized Webhook']));
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['from']) || !isset($data['text'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid data']);
    die();
}

$phone = $data['from'];
$messageText = trim($data['text']);
$senderName = $data['pushName'] ?? 'Cliente';

// Find or Create Contact
$stmt = $db->prepare("SELECT * FROM contacts WHERE phone = ?");
$stmt->execute([$phone]);
$contact = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$contact) {
    $stmt = $db->prepare("INSERT INTO contacts (phone, name, stage, last_message) VALUES (?, ?, 'novo', ?)");
    $stmt->execute([$phone, $senderName, $messageText]);
    $contactId = $db->lastInsertId();
    $contact = ['id' => $contactId, 'bot_paused' => 0, 'stage' => 'novo'];
} else {
    $contactId = $contact['id'];
    $stmt = $db->prepare("UPDATE contacts SET last_message = ?, last_interaction = CURRENT_TIMESTAMP WHERE id = ?");
    $stmt->execute([$messageText, $contactId]);
}

// Insert client message
$stmt = $db->prepare("INSERT INTO messages (contact_id, sender, content) VALUES (?, 'client', ?)");
$stmt->execute([$contactId, $messageText]);

// Check Bot Paused or Stage
if ($contact['bot_paused'] == 1 || $contact['stage'] === 'humano' || $contact['stage'] === 'finalizado') {
    // Human is taking care of this, so the bot should not respond.
    http_response_code(200);
    echo json_encode(['status' => 'ignored (paused/human stage)']);
    die();
}

// Ensure the bot is "online" via settings
$stmt = $db->query("SELECT value_data FROM settings WHERE key_name = 'bot_status'");
$status = $stmt->fetchColumn();
if ($status !== 'online') {
    http_response_code(200);
    echo json_encode(['status' => 'bot is offline in settings']);
    die();
}

// Fetch Gemini API Key
$stmt = $db->query("SELECT value_data FROM settings WHERE key_name = 'gemini_api_key'");
$apiKey = $stmt->fetchColumn();

if (empty($apiKey)) {
    http_response_code(200);
    echo json_encode(['status' => 'no api key']);
    die();
}

// Build Knowledge Context
$stmt = $db->query("SELECT content FROM knowledge");
$knowledgeList = $stmt->fetchAll(PDO::FETCH_COLUMN);
$contextText = implode("\n\n", $knowledgeList);

// Query Gemini
$gemini = new GeminiAPI($apiKey);
$reply = $gemini->generateResponse($messageText, $contextText);

if ($reply === '[TRANSFERIR_PARA_HUMANO]' || strpos($reply, '[TRANSFERIR_PARA_HUMANO]') !== false) {
    // Get Handoff Message
    $stmt = $db->query("SELECT value_data FROM settings WHERE key_name = 'handoff_message'");
    $handoffMessage = $stmt->fetchColumn();
    $reply = $handoffMessage ? $handoffMessage : "Vou te transferir para um de nossos especialistas. Aguarde um momento!";

    // Pause bot and change stage
    $stmt = $db->prepare("UPDATE contacts SET bot_paused = 1, stage = 'humano' WHERE id = ?");
    $stmt->execute([$contactId]);
}

// Log Bot Reply
$stmt = $db->prepare("INSERT INTO messages (contact_id, sender, content) VALUES (?, 'bot', ?)");
$stmt->execute([$contactId, $reply]);

// Return reply to Node.js bot server which is expecting a JSON response
http_response_code(200);
echo json_encode([
    'reply' => true,
    'message' => $reply
]);
?>
