<?php
// Planer/api/files.php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

require_once 'db.php';
require_once 'auth.php'; // Includes verify_auth_token

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

$user = verify_auth_token($db);
$action = $_GET['action'] ?? '';
$method = $_SERVER['REQUEST_METHOD'];

$upload_dir = __DIR__ . '/uploads/';

if ($method === 'GET') {
    if ($action === 'list') {
        $stmt = $db->prepare("SELECT id, user_id, filename, original_name, file_type, file_size, uploaded_at FROM files WHERE user_id = ? ORDER BY uploaded_at DESC");
        $stmt->execute([$user['id']]);
        $files = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['files' => $files]);
    } elseif ($action === 'download') {
        $id = $_GET['id'] ?? null;
        if (!$id) exit;

        $stmt = $db->prepare("SELECT * FROM files WHERE id = ? AND user_id = ?");
        $stmt->execute([$id, $user['id']]);
        $file = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($file) {
            $filepath = $upload_dir . $file['filename'];
            if (file_exists($filepath)) {
                // Determine mime type
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime = finfo_file($finfo, $filepath);
                finfo_close($finfo);

                header('Content-Type: ' . $mime);
                header('Content-Disposition: attachment; filename="' . $file['original_name'] . '"');
                header('Content-Length: ' . filesize($filepath));
                readfile($filepath);
                exit;
            }
        }
        http_response_code(404);
        echo json_encode(['error' => 'File not found']);
    }
} elseif ($method === 'POST') {
    if ($action === 'upload') {
        if (!isset($_FILES['file'])) {
            http_response_code(400);
            echo json_encode(['error' => 'No file uploaded']);
            exit;
        }

        $file = $_FILES['file'];
        $max_size = 5 * 1024 * 1024; // 5MB limit

        if ($file['size'] > $max_size) {
            http_response_code(400);
            echo json_encode(['error' => 'File exceeds 5MB limit']);
            exit;
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            http_response_code(500);
            echo json_encode(['error' => 'Upload failed']);
            exit;
        }

        // Allowed types: PDF, XLSX, images
        $allowed_types = [
            'application/pdf' => 'pdf',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx',
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp'
        ];

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!array_key_exists($mime, $allowed_types)) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid file type. Only PDF, XLSX, and images are allowed.']);
            exit;
        }

        // Force a safe extension based on the actual MIME type, ignoring the user-provided extension
        $safe_ext = $allowed_types[$mime];
        $new_filename = uniqid('file_', true) . '.' . $safe_ext;
        $dest_path = $upload_dir . $new_filename;

        if (move_uploaded_file($file['tmp_name'], $dest_path)) {
            $stmt = $db->prepare("INSERT INTO files (user_id, filename, original_name, file_type, file_size) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                $user['id'],
                $new_filename,
                $file['name'],
                $mime,
                $file['size']
            ]);
            echo json_encode(['success' => true]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to save file']);
        }
    }
} elseif ($method === 'DELETE') {
    $id = $_GET['id'] ?? null;
    if (!$id) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing file ID']);
        exit;
    }

    $stmt = $db->prepare("SELECT filename FROM files WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $user['id']]);
    $file = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($file) {
        $filepath = $upload_dir . $file['filename'];
        if (file_exists($filepath)) {
            unlink($filepath);
        }

        $delStmt = $db->prepare("DELETE FROM files WHERE id = ?");
        $delStmt->execute([$id]);

        echo json_encode(['success' => true]);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'File not found']);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}
