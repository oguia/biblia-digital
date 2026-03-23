<?php
// api/time_entries.php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

require 'db.php';

$user = getAuthUser($pdo);
if (!$user) {
    sendJson(['error' => 'Unauthorized'], 401);
}

$action = $_GET['action'] ?? '';
$input = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($action === 'current') {
        // Get active timer
        $stmt = $pdo->prepare("SELECT * FROM time_entries WHERE user_id = ? AND status IN ('running', 'paused') ORDER BY id DESC LIMIT 1");
        $stmt->execute([$user['id']]);
        sendJson(['entry' => $stmt->fetch()]);
    } else {
        // List history
        $stmt = $pdo->prepare("SELECT t.*, p.name as project_name, p.rate_type, p.rate_amount, p.expected_hours
                               FROM time_entries t
                               LEFT JOIN projects p ON t.project_id = p.id
                               WHERE t.user_id = ?
                               ORDER BY t.start_time DESC");
        $stmt->execute([$user['id']]);
        sendJson(['entries' => $stmt->fetchAll()]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'start') {
        // Prevent multiple running
        $stmt = $pdo->prepare("SELECT id FROM time_entries WHERE user_id = ? AND status IN ('running', 'paused')");
        $stmt->execute([$user['id']]);
        if ($stmt->fetch()) {
            sendJson(['error' => 'Timer already active'], 400);
        }

        $project_id = isset($input['project_id']) && $input['project_id'] !== '' ? intval($input['project_id']) : null;
        $start_time = date('Y-m-d H:i:s');

        $stmt = $pdo->prepare("INSERT INTO time_entries (user_id, project_id, start_time, status) VALUES (?, ?, ?, 'running')");
        $stmt->execute([$user['id'], $project_id, $start_time]);
        sendJson(['success' => true, 'id' => $pdo->lastInsertId(), 'start_time' => $start_time]);
    } elseif ($action === 'pause') {
        $stmt = $pdo->prepare("SELECT id FROM time_entries WHERE user_id = ? AND status = 'running' ORDER BY id DESC LIMIT 1");
        $stmt->execute([$user['id']]);
        $entry = $stmt->fetch();
        if (!$entry) sendJson(['error' => 'No running timer'], 404);

        $pause_start = date('Y-m-d H:i:s');
        $stmt = $pdo->prepare("UPDATE time_entries SET status = 'paused', pause_start = ? WHERE id = ?");
        $stmt->execute([$pause_start, $entry['id']]);
        sendJson(['success' => true]);
    } elseif ($action === 'resume') {
        $stmt = $pdo->prepare("SELECT id, pause_start, total_pause_seconds FROM time_entries WHERE user_id = ? AND status = 'paused' ORDER BY id DESC LIMIT 1");
        $stmt->execute([$user['id']]);
        $entry = $stmt->fetch();
        if (!$entry) sendJson(['error' => 'No paused timer'], 404);

        $now = date('Y-m-d H:i:s');
        $pause_duration = strtotime($now) - strtotime($entry['pause_start']);
        $new_total_pause = $entry['total_pause_seconds'] + $pause_duration;

        $stmt = $pdo->prepare("UPDATE time_entries SET status = 'running', pause_start = NULL, total_pause_seconds = ? WHERE id = ?");
        $stmt->execute([$new_total_pause, $entry['id']]);
        sendJson(['success' => true]);
    } elseif ($action === 'stop') {
        $stmt = $pdo->prepare("SELECT * FROM time_entries WHERE user_id = ? AND status IN ('running', 'paused') ORDER BY id DESC LIMIT 1");
        $stmt->execute([$user['id']]);
        $entry = $stmt->fetch();
        if (!$entry) sendJson(['error' => 'No active timer'], 404);

        $end_time = date('Y-m-d H:i:s');
        $total_pause_seconds = intval($entry['total_pause_seconds']);

        // If it was paused when stopped, add the current pause duration
        if ($entry['status'] === 'paused' && $entry['pause_start']) {
             $pause_duration = strtotime($end_time) - strtotime($entry['pause_start']);
             $total_pause_seconds += $pause_duration;
        }

        $stmt = $pdo->prepare("UPDATE time_entries SET status = 'completed', end_time = ?, pause_start = NULL, total_pause_seconds = ? WHERE id = ?");
        $stmt->execute([$end_time, $total_pause_seconds, $entry['id']]);
        sendJson(['success' => true, 'end_time' => $end_time]);
    } elseif ($action === 'manual') {
        // Manually add entry
        $project_id = isset($input['project_id']) && $input['project_id'] !== '' ? intval($input['project_id']) : null;
        if (empty($input['start_time']) || empty($input['end_time'])) {
            sendJson(['error' => 'Start and end time required'], 400);
        }

        $stmt = $pdo->prepare("INSERT INTO time_entries (user_id, project_id, start_time, end_time, status) VALUES (?, ?, ?, ?, 'completed')");
        $stmt->execute([$user['id'], $project_id, $input['start_time'], $input['end_time']]);
        sendJson(['success' => true]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = intval($_GET['id'] ?? 0);
    $stmt = $pdo->prepare("DELETE FROM time_entries WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $user['id']]);
    sendJson(['success' => true]);
}
