<?php
// Configurações do Mercado Pago
// Cole seu Client ID e Client Secret aqui para facilitar
$MP_CLIENT_ID = 'SEU_CLIENT_ID_AQUI';
$MP_CLIENT_SECRET = 'SEU_CLIENT_SECRET_AQUI';

// Função para gerar o Access Token (OAuth) usando o ID e Secret
function getMercadoPagoToken($client_id, $client_secret) {
    if ($client_id === 'SEU_CLIENT_ID_AQUI') return null;

    $ch = curl_init("https://api.mercadopago.com/oauth/token");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
        'client_id' => $client_id,
        'client_secret' => $client_secret,
        'grant_type' => 'client_credentials'
    ]));

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code === 200) {
        $data = json_decode($response, true);
        return $data['access_token'] ?? null;
    }
    return null;
}
?>