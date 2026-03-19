<?php
// Acesso: /app/buscador-ml/import.php
session_start();
require_once 'config.php';

// Proteção de Rota
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    http_response_code(401);
    echo json_encode(['error' => 'Não autorizado.']);
    exit;
}

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['product'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Dados do produto ausentes.']);
    exit;
}

// Validação de CSRF Token (Segurança contra requisições forjadas)
if (empty($_POST['csrf_token']) || empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Falha na validação de segurança (CSRF token inválido). Atualize a página e tente novamente.']);
    exit;
}

$product_data = json_decode($_POST['product'], true);

if (!$product_data || empty($product_data['title'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Dados do produto inválidos.']);
    exit;
}

// Verifica chaves da API
if (empty(WC_CONSUMER_KEY) || empty(WC_CONSUMER_SECRET) || strpos(WC_CONSUMER_KEY, 'ck_xxxx') !== false) {
    http_response_code(500);
    echo json_encode(['error' => 'Chaves da API do WooCommerce não configuradas no config.php.']);
    exit;
}

// Prepara o Payload para a API Rest do WooCommerce (Criar Produto Externo)
$wc_payload = [
    'name' => $product_data['title'],
    'type' => 'external',
    'status' => 'draft', // Salva como rascunho por segurança para o admin revisar no WP
    'regular_price' => (string)$product_data['price'],
    'description' => '<p>Importado do Mercado Livre via Curador Urbano.</p>',
    'short_description' => '',
    'external_url' => $product_data['permalink'],
    'button_text' => 'Comprar Agora',
    'images' => [
        [
            'src' => $product_data['image']
        ]
    ]
];

// Opcional: Adicionar "Destaque" se for catálogo ML
if (isset($product_data['is_catalog']) && $product_data['is_catalog']) {
    $wc_payload['featured'] = true;
}

// Endpoint da API do WooCommerce
$wc_endpoint = rtrim(WC_URL, '/') . '/wp-json/wc/v3/products';

// Inicializa cURL para enviar os dados para o WooCommerce
$ch = curl_init();

// Configura Autenticação Basic (Keys do WC)
curl_setopt($ch, CURLOPT_USERPWD, WC_CONSUMER_KEY . ":" . WC_CONSUMER_SECRET);

// Configurações da requisição POST
curl_setopt($ch, CURLOPT_URL, $wc_endpoint);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($wc_payload));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json'
]);

// Como o script está rodando no mesmo servidor que o WooCommerce (Hostinger),
// a chamada cURL em loopback (HTTPS para si mesmo) falha com "tlsv1 alert internal error"
// devido à configuração de SNI/proxy local. Desativamos a verificação de SSL apenas para este loopback.
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

// Executa
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if (curl_errno($ch)) {
    $error_msg = curl_error($ch);
    curl_close($ch);
    http_response_code(500);
    echo json_encode(['error' => "Erro de conexão com o site Faro de Ouro: $error_msg"]);
    exit;
}

curl_close($ch);

// Interpreta a resposta do WooCommerce
$response_data = json_decode($response, true);

if ($http_code === 201 && isset($response_data['id'])) {
    // Sucesso! Produto criado.
    echo json_encode([
        'success' => true,
        'wp_id' => $response_data['id'],
        'message' => 'Produto criado como rascunho.'
    ]);
} else {
    // Falha na API do WooCommerce
    $error_message = $response_data['message'] ?? "Erro desconhecido na API do WooCommerce";
    http_response_code($http_code);
    echo json_encode([
        'error' => "Erro do WooCommerce (HTTP $http_code): $error_message",
        'details' => $response_data
    ]);
}
?>