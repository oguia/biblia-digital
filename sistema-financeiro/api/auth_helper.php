<?php
// O segredo JWT_SECRET deve estar definido em config.php

function base64UrlEncode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function base64UrlDecode($data) {
    return base64_decode(strtr($data, '-_', '+/'));
}

function generateJWT($payload) {
    $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);

    $base64UrlHeader = base64UrlEncode($header);
    $base64UrlPayload = base64UrlEncode(json_encode($payload));

    $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, JWT_SECRET, true);
    $base64UrlSignature = base64UrlEncode($signature);

    return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
}

function verifyJWT($token) {
    $parts = explode('.', $token);
    if (count($parts) != 3) return false;

    $header = $parts[0];
    $payload = $parts[1];
    $signature_provided = $parts[2];

    $signature_check = hash_hmac('sha256', $header . "." . $payload, JWT_SECRET, true);
    $base64UrlSignatureCheck = base64UrlEncode($signature_check);

    if (!hash_equals($signature_provided, $base64UrlSignatureCheck)) {
        return false;
    }

    $payloadDecoded = json_decode(base64UrlDecode($payload), true);

    // Verificar expiração
    if (isset($payloadDecoded['exp']) && $payloadDecoded['exp'] < time()) {
        return false;
    }

    return $payloadDecoded;
}

function getUserIdFromToken() {
    $headers = getallheaders();
    // Tenta pegar do Authorization header
    $authHeader = isset($headers['Authorization']) ? $headers['Authorization'] : '';

    // Fallback para quando o servidor (Apache/Nginx) remove o header Authorization
    if (!$authHeader && isset($_SERVER['HTTP_AUTHORIZATION'])) {
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'];
    }

    if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
        $token = $matches[1];
        $payload = verifyJWT($token);
        if ($payload && isset($payload['user_id'])) {
            return $payload['user_id'];
        }
    }

    return null;
}

function requireAuth() {
    $userId = getUserIdFromToken();
    if (!$userId) {
        http_response_code(401);
        echo json_encode(['error' => 'Acesso não autorizado']);
        exit;
    }
    return $userId;
}
?>
