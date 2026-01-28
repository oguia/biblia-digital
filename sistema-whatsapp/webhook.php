<?php
require_once 'functions.php';

// 1. Verificação do Webhook (GET)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $mode = $_GET['hub_mode'] ?? '';
    $token = $_GET['hub_verify_token'] ?? '';
    $challenge = $_GET['hub_challenge'] ?? '';

    if ($mode === 'subscribe' && $token === WEBHOOK_VERIFY_TOKEN) {
        http_response_code(200);
        echo $challenge;
        exit;
    } else {
        http_response_code(403);
        exit;
    }
}

// 2. Recebimento de Mensagens (POST)
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data) {
    http_response_code(400); // Bad Request
    exit;
}

// Log cru para debug (opcional, pode encher o disco)
// file_put_contents('webhook_log.txt', print_r($data, true), FILE_APPEND);

// Navegar pelo JSON do WhatsApp
if (
    isset($data['entry'][0]['changes'][0]['value']['messages'][0])
) {
    $message = $data['entry'][0]['changes'][0]['value']['messages'][0];
    $phone_number = $message['from']; // 5511999999999
    $msg_type = $message['type'];

    // Obter ou criar contato
    $conn = db_connect();
    $stmt = $conn->prepare("SELECT id, status, name FROM contacts WHERE phone = ?");
    $stmt->bind_param("s", $phone_number);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $contact = $result->fetch_assoc();
        $contact_id = $contact['id'];
        $status = $contact['status'];
    } else {
        // Novo contato
        $profile_name = $data['entry'][0]['changes'][0]['value']['contacts'][0]['profile']['name'] ?? 'Desconhecido';
        $stmt_ins = $conn->prepare("INSERT INTO contacts (phone, name, status) VALUES (?, ?, 'NEW')");
        $stmt_ins->bind_param("ss", $phone_number, $profile_name);
        $stmt_ins->execute();
        $contact_id = $stmt_ins->insert_id;
        $status = 'NEW';
    }
    $stmt->close();
    $conn->close();

    // Processar Mensagem
    $body = '';
    if ($msg_type == 'text') {
        $body = $message['text']['body'];
    } elseif ($msg_type == 'interactive') {
        if (isset($message['interactive']['list_reply'])) {
            $body = "[Opção Selecionada] " . $message['interactive']['list_reply']['title'];
            $selected_id = $message['interactive']['list_reply']['id']; // ex: dept_1
        } elseif (isset($message['interactive']['button_reply'])) {
             $body = "[Botão] " . $message['interactive']['button_reply']['title'];
        }
    } else {
        $body = "[$msg_type]"; // Imagem, audio, etc.
    }

    // Salvar mensagem recebida
    log_message($contact_id, 'in', $body);

    // Lógica do Chatbot
    if ($status == 'NEW' || $status == 'CLOSED') {
        // Enviar Menu
        send_menu_options($phone_number);
        update_contact_status($contact_id, 'WAITING_OPTION');

    } elseif ($status == 'WAITING_OPTION') {
        // Esperando escolha
        if ($msg_type == 'interactive' && isset($selected_id) && strpos($selected_id, 'dept_') === 0) {
            // Cliente selecionou um departamento
            $dept_id = str_replace('dept_', '', $selected_id);

            // Atualizar contato
            $conn = db_connect();
            $stmt = $conn->prepare("UPDATE contacts SET department_id = ?, status = 'OPEN' WHERE id = ?");
            $stmt->bind_param("ii", $dept_id, $contact_id);
            $stmt->execute();
            $conn->close();

            send_whatsapp_message($phone_number, "Obrigado! Um atendente irá falar com você em breve.");
        } else {
            // Resposta inválida, reenviar menu? Ou deixar passar se for texto?
            // Vamos reenviar o menu para forçar a escolha, ou orientar.
            send_whatsapp_message($phone_number, "Por favor, selecione uma das opções do menu.");
            send_menu_options($phone_number);
        }

    } elseif ($status == 'OPEN') {
        // Conversa aberta, atendente deve ver no painel.
        // Nada a fazer além de logar (já feito acima).
        // Poderia ter uma auto-resposta "Estamos analisando..." se demorar muito?
        // Deixar quieto para não ser chato.
    }
}

http_response_code(200);
