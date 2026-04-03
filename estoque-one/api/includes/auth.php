<?php
function base64url_encode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function base64url_decode($data) {
    return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
}

function generate_jwt($payload) {
    $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
    $base64UrlHeader = base64url_encode($header);
    $base64UrlPayload = base64url_encode(json_encode($payload));
    $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, JWT_SECRET, true);
    $base64UrlSignature = base64url_encode($signature);
    return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
}

function verify_jwt($jwt) {
    $parts = explode('.', $jwt);
    if (count($parts) != 3) return false;
    list($header64, $payload64, $sign64) = $parts;

    $signature = base64url_decode($sign64);
    $expected_signature = hash_hmac('sha256', $header64 . "." . $payload64, JWT_SECRET, true);

    if (!hash_equals($signature, $expected_signature)) return false;

    $payload = json_decode(base64url_decode($payload64), true);
    if (isset($payload['exp']) && $payload['exp'] < time()) return false;

    return $payload;
}

function get_auth_user() {
    $headers = apache_request_headers();
    $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';
    if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
        $jwt = $matches[1];
        return verify_jwt($jwt);
    }
    return false;
}

function require_auth() {
    $user = get_auth_user();
    if (!$user) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        die();
    }
    return $user;
}

function require_tenant($user) {
    if (!$user['tenant_id']) {
        http_response_code(403);
        echo json_encode(['error' => 'No tenant assigned']);
        die();
    }
    return $user['tenant_id'];
}
