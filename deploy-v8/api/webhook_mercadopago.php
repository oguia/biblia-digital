<?php
// Script para receber notificações de pagamento do Mercado Pago (Webhook)
require_once 'db.php';
require_once 'mp_config.php';

// O Mercado Pago envia o ID do pagamento via GET ou no JSON do body
$data = json_decode(file_get_contents('php://input'), true);

if (isset($_GET['data_id'])) {
    $payment_id = $_GET['data_id'];
} elseif (isset($data['data']['id'])) {
    $payment_id = $data['data']['id'];
} else {
    http_response_code(400);
    echo "ID do pagamento nao encontrado";
    exit;
}

// 1. Gera o Token via ClientID/Secret
$token = getMercadoPagoToken($MP_CLIENT_ID, $MP_CLIENT_SECRET);

if (!$token) {
    // Se o dono não configurou o ID/Secret, tentamos uma chave de acesso fallback, mas o ideal é o Token OAuth
    $token = 'SEU_ACCESS_TOKEN_DO_MERCADO_PAGO'; // Ou colocar direto o Access Token aqui
}

// Consultar o status do pagamento na API do Mercado Pago
$ch = curl_init("https://api.mercadopago.com/v1/payments/" . $payment_id);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer " . $token
]);
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http_code === 200) {
    $payment_info = json_decode($response, true);
    $status = $payment_info['status'];

    // A referência externa (external_reference) que usamos para identificar qual oferta foi paga
    $oferta_id = $payment_info['external_reference'];

    if ($status === 'approved') {
        // Atualizar o status do pagamento no nosso banco
        $stmt = $db->prepare("UPDATE pagamentos SET status = 'aprovado', mercado_pago_id = ? WHERE id_oferta = ?");
        $stmt->execute([$payment_id, $oferta_id]);

        // Ativar a oferta no site (De 'pendente_pagamento' para 'ativo')
        $stmt_oferta = $db->prepare("UPDATE ofertas SET status = 'ativo' WHERE id = ?");
        $stmt_oferta->execute([$oferta_id]);

        // Registrar no log
        file_put_contents(__DIR__ . '/mp_webhook_log.txt', date("Y-m-d H:i:s") . " - Pagamento $payment_id APROVADO para Oferta $oferta_id\n", FILE_APPEND);

        http_response_code(200);
        echo "Sucesso";
    } else {
        file_put_contents(__DIR__ . '/mp_webhook_log.txt', date("Y-m-d H:i:s") . " - Pagamento $payment_id STATUS: $status para Oferta $oferta_id\n", FILE_APPEND);
        http_response_code(200);
        echo "Status recebido, aguardando aprovação.";
    }
} else {
    http_response_code(400);
    echo "Erro ao consultar API do Mercado Pago usando o Token fornecido. Verifique seu Client ID / Secret.";
}
?>