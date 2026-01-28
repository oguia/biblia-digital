<?php
require_once 'functions.php';

// Configurações de Tempo (em minutos)
$warning_time = 15; // Avisar se não respondido em 15 min
$close_time = 1440; // Fechar se inativo por 24h (1440 min)

$conn = db_connect();

// 1. Verificar contatos 'OPEN' esperando resposta do atendente (last message is 'in')
// que excederam o tempo e não foram alertados recentemente (ex: nos últimos 60min)
$limit_date = date('Y-m-d H:i:s', strtotime("-$warning_time minutes"));

$sql = "SELECT c.id, c.name, c.phone, m.created_at as msg_time
        FROM contacts c
        JOIN messages m ON c.id = m.contact_id
        WHERE c.status = 'OPEN'
        AND m.id = (SELECT MAX(id) FROM messages WHERE contact_id = c.id)
        AND m.type = 'in'
        AND m.created_at < ?
        AND (c.last_alert IS NULL OR c.last_alert < DATE_SUB(NOW(), INTERVAL 60 MINUTE))";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $limit_date);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    // Enviar alerta para os admins
    // Na prática, em hospedagem compartilhada, mail() funciona.
    $subject = "[ALERTA] Cliente esperando: " . $row['name'];
    $msg = "O cliente {$row['name']} ({$row['phone']}) está esperando resposta desde {$row['msg_time']}.";

    // Pegar email do admin principal (ou todos)
    $res_adm = $conn->query("SELECT email FROM admins");
    while($adm = $res_adm->fetch_assoc()) {
        mail($adm['email'], $subject, $msg);
    }

    // Atualizar last_alert
    $conn->query("UPDATE contacts SET last_alert = NOW() WHERE id = " . $row['id']);
    echo "Alerta enviado para contato ID " . $row['id'] . "<br>";
}

// 2. Fechar conversas inativas por muito tempo (Opcional)
$close_date = date('Y-m-d H:i:s', strtotime("-$close_time minutes"));
$conn->query("UPDATE contacts SET status = 'CLOSED' WHERE status = 'OPEN' AND last_activity < '$close_date'");

$conn->close();
