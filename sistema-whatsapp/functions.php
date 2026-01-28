<?php
require_once 'config.php';

function db_connect() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $conn->set_charset("utf8mb4");
    return $conn;
}

function log_message($contact_id, $type, $body) {
    $conn = db_connect();
    $stmt = $conn->prepare("INSERT INTO messages (contact_id, type, body) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $contact_id, $type, $body);
    $stmt->execute();
    $stmt->close();
    $conn->close();
}

function update_contact_status($contact_id, $status) {
    $conn = db_connect();
    $stmt = $conn->prepare("UPDATE contacts SET status = ?, last_activity = NOW() WHERE id = ?");
    $stmt->bind_param("si", $status, $contact_id);
    $stmt->execute();
    $conn->close();
}

function send_whatsapp_message($to, $text) {
    $data = [
        'messaging_product' => 'whatsapp',
        'recipient_type' => 'individual',
        'to' => $to,
        'type' => 'text',
        'text' => ['body' => $text]
    ];
    return send_api_request($data);
}

function send_menu_options($to) {
    // Busca departamentos do banco
    $conn = db_connect();
    $result = $conn->query("SELECT * FROM departments");

    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $rows[] = [
            'id' => 'dept_' . $row['id'],
            'title' => mb_substr($row['name'], 0, 24), // Max 24 chars for title
            'description' => mb_substr($row['description'] ?? '', 0, 72)
        ];
    }
    $conn->close();

    if (empty($rows)) {
        return send_whatsapp_message($to, "Olá! No momento não temos departamentos disponíveis.");
    }

    $data = [
        'messaging_product' => 'whatsapp',
        'to' => $to,
        'type' => 'interactive',
        'interactive' => [
            'type' => 'list',
            'header' => ['type' => 'text', 'text' => 'Bem-vindo!'],
            'body' => ['text' => 'Por favor, selecione o departamento desejado:'],
            'footer' => ['text' => 'Selecione abaixo'],
            'action' => [
                'button' => 'Ver Opções',
                'sections' => [
                    [
                        'title' => 'Departamentos',
                        'rows' => $rows
                    ]
                ]
            ]
        ]
    ];

    return send_api_request($data);
}

function send_api_request($data) {
    $url = 'https://graph.facebook.com/v17.0/' . WA_PHONE_ID . '/messages';

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . WA_TOKEN,
        'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);

    return $result;
}

// Função para formatar telefone (remover + e caracteres)
function clean_phone($phone) {
    return preg_replace('/[^0-9]/', '', $phone);
}
