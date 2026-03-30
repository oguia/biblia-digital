<?php
// Permitir acesso de qualquer origem (ou restrinja ao seu domínio em produção)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

// Bloqueio simples de User-Agents conhecidos de cópia
$ua = $_SERVER['HTTP_USER_AGENT'] ?? '';
if (preg_match('/(HTTrack|wget|curl|libwww|python|nikto|sqlmap)/i', $ua)) {
    http_response_code(403);
    die('Acesso negado.');
}

// Responder imediatamente a requisições OPTIONS (Pre-flight)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Configurar cabeçalho de resposta JSON
header("Content-Type: application/json; charset=UTF-8");

// Helper para obter dados JSON do corpo da requisição
function getJsonInput() {
    $input = json_decode(file_get_contents('php://input'), true);
    return $input ?? [];
}
?>
