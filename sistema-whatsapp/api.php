<?php
session_start();
require_once 'functions.php';

// Proteção básica: Só admins logados podem acessar a API
if (!isset($_SESSION['admin_id'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$action = $_GET['action'] ?? '';
$conn = db_connect();

header('Content-Type: application/json');

if ($action === 'get_contacts') {
    // Retorna contatos 'OPEN' (Atendendo) e 'WAITING_OPTION' (Fila)
    $sql = "SELECT c.*, d.name as dept_name,
            (SELECT body FROM messages m WHERE m.contact_id = c.id ORDER BY m.id DESC LIMIT 1) as last_msg
            FROM contacts c
            LEFT JOIN departments d ON c.department_id = d.id
            WHERE c.status IN ('OPEN', 'WAITING_OPTION')
            ORDER BY FIELD(c.status, 'OPEN', 'WAITING_OPTION'), c.last_activity DESC";
    $result = $conn->query($sql);
    $contacts = [];
    while ($row = $result->fetch_assoc()) {
        $contacts[] = $row;
    }
    echo json_encode($contacts);

} elseif ($action === 'get_messages') {
    $contact_id = intval($_GET['contact_id']);
    $stmt = $conn->prepare("SELECT * FROM messages WHERE contact_id = ? ORDER BY created_at ASC");
    $stmt->bind_param("i", $contact_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $messages = [];
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
    echo json_encode($messages);

} elseif ($action === 'send_message') {
    // POST request
    $data = json_decode(file_get_contents('php://input'), true);
    $contact_id = intval($data['contact_id']);
    $text = $data['text'];

    // Pegar telefone
    $stmt = $conn->prepare("SELECT phone FROM contacts WHERE id = ?");
    $stmt->bind_param("i", $contact_id);
    $stmt->execute();
    $contact = $stmt->get_result()->fetch_assoc();

    if ($contact) {
        // Enviar via WhatsApp API
        $res = send_whatsapp_message($contact['phone'], $text);

        // Salvar no banco
        log_message($contact_id, 'out', $text);

        // Atualizar atividade
        update_contact_status($contact_id, 'OPEN'); // Garante que está open

        echo json_encode(['success' => true, 'api_response' => json_decode($res)]);
    } else {
        echo json_encode(['error' => 'Contact not found']);
    }

} elseif ($action === 'close_chat') {
    $data = json_decode(file_get_contents('php://input'), true);
    $contact_id = intval($data['contact_id']);
    update_contact_status($contact_id, 'CLOSED');
    echo json_encode(['success' => true]);

} elseif ($action === 'get_settings') {
    // Retorna agentes e departamentos
    $agents = [];
    $res = $conn->query("SELECT id, name, email FROM admins");
    while($row = $res->fetch_assoc()) $agents[] = $row;

    $depts = [];
    $res = $conn->query("SELECT * FROM departments");
    while($row = $res->fetch_assoc()) $depts[] = $row;

    echo json_encode(['agents' => $agents, 'departments' => $depts]);

} elseif ($action === 'add_agent') {
    $data = json_decode(file_get_contents('php://input'), true);
    $name = $data['name'];
    $email = $data['email'];
    $pass = password_hash($data['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO admins (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $pass);
    if ($stmt->execute()) echo json_encode(['success' => true]);
    else echo json_encode(['error' => $stmt->error]);

} elseif ($action === 'add_dept') {
    $data = json_decode(file_get_contents('php://input'), true);
    $name = $data['name'];
    $desc = $data['description'];

    $stmt = $conn->prepare("INSERT INTO departments (name, description) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $desc);
    if ($stmt->execute()) echo json_encode(['success' => true]);
    else echo json_encode(['error' => $stmt->error]);
}

$conn->close();
