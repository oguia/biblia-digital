<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['admin_id'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$action = $_GET['action'] ?? '';
$conn = db_connect();

header('Content-Type: application/json');

// --- INSTÂNCIAS (Super Admin) ---
if ($action === 'create_instance' && $_SESSION['instance_id'] === null) {
    $data = json_decode(file_get_contents('php://input'), true);
    // Chama Node para criar
    $res = node_api_request('/instance', ['name' => $data['name']]);
    echo json_encode($res);

} elseif ($action === 'get_instances') {
    // Se for Super Admin vê todas, senão vê só a sua
    $sql = "SELECT id, name, status, qrcode FROM instances";
    if ($_SESSION['instance_id'] !== null) {
        $sql .= " WHERE id = " . intval($_SESSION['instance_id']);
    }
    $res = $conn->query($sql);
    $rows = [];
    while($r = $res->fetch_assoc()) $rows[] = $r;
    echo json_encode($rows);

// --- CONTATOS E CHAT ---
} elseif ($action === 'get_contacts') {
    // Filtrar pela instância do usuário ou filtro selecionado
    $instance_filter = isset($_GET['instance_id']) ? intval($_GET['instance_id']) : $_SESSION['instance_id'];

    // Se não for super admin, força o ID
    if ($_SESSION['instance_id'] !== null) $instance_filter = $_SESSION['instance_id'];

    if (!$instance_filter) {
        // Super admin vendo tudo? Retornar vazio ou todos (vamos retornar todos por enquanto)
         $sql = "SELECT c.*, i.name as instance_name,
            (SELECT body FROM messages m WHERE m.contact_id = c.id ORDER BY m.id DESC LIMIT 1) as last_msg,
            (SELECT created_at FROM messages m WHERE m.contact_id = c.id ORDER BY m.id DESC LIMIT 1) as last_time
            FROM contacts c
            JOIN instances i ON c.instance_id = i.id
            ORDER BY last_activity DESC";
    } else {
        $sql = "SELECT c.*, i.name as instance_name,
            (SELECT body FROM messages m WHERE m.contact_id = c.id ORDER BY m.id DESC LIMIT 1) as last_msg,
            (SELECT created_at FROM messages m WHERE m.contact_id = c.id ORDER BY m.id DESC LIMIT 1) as last_time
            FROM contacts c
            JOIN instances i ON c.instance_id = i.id
            WHERE c.instance_id = $instance_filter
            ORDER BY last_activity DESC";
    }

    $result = $conn->query($sql);
    $contacts = [];
    while ($row = $result->fetch_assoc()) $contacts[] = $row;
    echo json_encode($contacts);

} elseif ($action === 'get_messages') {
    $contact_id = intval($_GET['contact_id']);
    // Verificar permissão
    if ($_SESSION['instance_id'] !== null) {
        $check = $conn->query("SELECT id FROM contacts WHERE id = $contact_id AND instance_id = " . $_SESSION['instance_id']);
        if ($check->num_rows == 0) {
            echo json_encode([]); exit;
        }
    }

    $stmt = $conn->prepare("SELECT * FROM messages WHERE contact_id = ? ORDER BY created_at ASC");
    $stmt->bind_param("i", $contact_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $messages = [];
    while ($row = $result->fetch_assoc()) $messages[] = $row;
    echo json_encode($messages);

} elseif ($action === 'send_message') {
    $data = json_decode(file_get_contents('php://input'), true);
    $contact_id = intval($data['contact_id']);
    $text = $data['text'];

    // Pegar dados do contato
    $res = $conn->query("SELECT phone, instance_id FROM contacts WHERE id = $contact_id");
    $contact = $res->fetch_assoc();

    if ($contact) {
        // Enviar via Node API
        $node_res = node_api_request('/send', [
            'instance_id' => $contact['instance_id'],
            'phone' => $contact['phone'],
            'message' => $text
        ]);
        echo json_encode($node_res);
    }

// --- ADMIN MANAGEMENT ---
} elseif ($action === 'create_user' && $_SESSION['instance_id'] === null) {
    $data = json_decode(file_get_contents('php://input'), true);
    $name = $data['name'];
    $email = $data['email'];
    $pass = password_hash($data['password'], PASSWORD_DEFAULT);
    $instance = $data['instance_id'] ? intval($data['instance_id']) : NULL;

    $stmt = $conn->prepare("INSERT INTO admins (name, email, password, instance_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $name, $email, $pass, $instance);

    if ($stmt->execute()) echo json_encode(['success' => true]);
    else echo json_encode(['error' => $stmt->error]);
}

$conn->close();
