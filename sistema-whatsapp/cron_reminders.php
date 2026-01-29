<?php
// CRON: Verificar conversas sem resposta do atendente
// Executar a cada 5 ou 10 minutos
require_once __DIR__ . '/public/config.php';

// Configuração: Tempo para alerta (minutos)
$alert_time = 15;

$conn = db_connect();

// Lógica:
// 1. Contato 'open'
// 2. Última mensagem foi do CLIENTE (from_me = 0)
// 3. Tempo da mensagem > $alert_time
// 4. Ainda não foi alertado (podemos adicionar uma coluna last_alert no futuro, por enquanto manda sempre)

// Query simplificada para pegar mensagens pendentes
$sql = "SELECT c.id, c.name, c.phone, m.created_at, i.name as instance_name
        FROM contacts c
        JOIN messages m ON c.id = m.contact_id
        JOIN instances i ON c.instance_id = i.id
        WHERE c.status = 'open'
        AND m.id = (SELECT MAX(id) FROM messages WHERE contact_id = c.id)
        AND m.from_me = 0
        AND m.created_at < DATE_SUB(NOW(), INTERVAL $alert_time MINUTE)";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Busca emails dos admins
    $emails = [];
    $res_adm = $conn->query("SELECT email FROM admins");
    while($r = $res_adm->fetch_assoc()) $emails[] = $r['email'];

    $to = implode(',', $emails);
    $subject = "[WhatsApp] Alerta de Atendimento Atrasado";

    $body = "As seguintes conversas estão aguardando resposta há mais de $alert_time minutos:\n\n";
    while ($row = $result->fetch_assoc()) {
        $body .= "- {$row['name']} ({$row['phone']}) - Instância: {$row['instance_name']} - Desde: {$row['created_at']}\n";
    }

    // Envia email (mail() do PHP funciona na maioria das hospedagens)
    mail($to, $subject, $body);

    echo "Alerta enviado para: $to";
} else {
    echo "Nenhuma conversa atrasada.";
}

$conn->close();
