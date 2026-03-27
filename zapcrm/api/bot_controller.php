<?php
// zapcrm/api/bot_controller.php
require 'db.php';
$user = verifyAuth($db);

if ($user['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['error' => 'Forbidden']);
    die();
}

$action = $_GET['action'] ?? '';

// Determine Webhook URL dynamically to pass to Node
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$domainName = $_SERVER['HTTP_HOST'];
$basePath = dirname($_SERVER['REQUEST_URI']);
if ($basePath === '/' || $basePath === '\\') $basePath = '';
$webhookUrl = $protocol . $domainName . $basePath . '/index.php/webhook';
$portFile = __DIR__ . '/bot_port.txt';
$authFile = __DIR__ . '/bot_auth.txt';

if ($action === 'start') {
    $botPath = __DIR__ . '/bot/bot.cjs';
    if (!file_exists($botPath)) {
        $botPath = __DIR__ . '/../bot/index.js';
    }

    @exec("pkill -f 'node.*bot\.cjs'");
    @exec("pkill -f 'node.*bot/index\.js'");
    @unlink($portFile);

    // Generate secure random token for inter-process communication
    $botToken = bin2hex(random_bytes(32));
    file_put_contents($authFile, $botToken);

    sleep(1);

    // Start bot with webhook URL and auth token env vars
    $cmd = "WEBHOOK_URL=" . escapeshellarg($webhookUrl) . " BOT_TOKEN=" . escapeshellarg($botToken) . " node " . escapeshellarg($botPath) . " > bot_log.txt 2>&1 &";
    exec($cmd);

    $stmt = $db->prepare("UPDATE settings SET value_data = 'online' WHERE key_name = 'bot_status'");
    $stmt->execute();

    echo json_encode(['success' => true, 'message' => 'Bot starting...', 'webhook' => $webhookUrl]);
} elseif ($action === 'stop') {
    @exec("pkill -f 'node.*bot\.cjs'");
    @exec("pkill -f 'node.*bot/index\.js'");
    @unlink($portFile);
    @unlink($authFile);

    $stmt = $db->prepare("UPDATE settings SET value_data = 'offline' WHERE key_name = 'bot_status'");
    $stmt->execute();

    echo json_encode(['success' => true, 'message' => 'Bot stopped']);
} elseif ($action === 'status') {
    $isRunning = false;

    if (file_exists($portFile)) {
        $botPort = trim(file_get_contents($portFile));
        $botToken = file_exists($authFile) ? trim(file_get_contents($authFile)) : '';

        if (is_numeric($botPort)) {
            $ch = curl_init("http://127.0.0.1:{$botPort}/ping");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bearer {$botToken}"]);
            curl_setopt($ch, CURLOPT_TIMEOUT, 2);
            $result = curl_exec($ch);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            $isRunning = ($httpcode === 200);
        }
    }

    if (!$isRunning) {
        $stmt = $db->prepare("UPDATE settings SET value_data = 'offline' WHERE key_name = 'bot_status'");
        $stmt->execute();
    }

    $qrPath = __DIR__ . '/qr.png';
    $qrBase64 = null;
    if (file_exists($qrPath)) {
        $qrBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($qrPath));
    }

    echo json_encode(['running' => $isRunning, 'qr' => $qrBase64]);
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid action']);
}
?>
