<?php
require_once 'db.php';
require_once 'jwt.php';

// Carregar o Access Token de um arquivo seguro (ou usar o seu aqui temporariamente)
// O ideal é colocar seu Access Token de Produção ou Teste aqui (iniciando com APP_USR-... ou TEST-...)
$mp_token_file = __DIR__ . '/.mp_token';
if (file_exists($mp_token_file)) {
    define('MP_ACCESS_TOKEN', trim(file_get_contents($mp_token_file)));
} else {
    // INSIRA AQUI O SEU ACCESS TOKEN DO MERCADO PAGO SE NÃO QUISER USAR O ARQUIVO .mp_token
    define('MP_ACCESS_TOKEN', 'TEST-7468165518055562-xxxxxx-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx-xxxxxxxxx');
}

function respond($data, $status = 200) {
    http_response_code($status);
    echo json_encode($data);
    exit;
}

$db = DB::getInstance()->getConnection();
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';
$input = json_decode(file_get_contents('php://input'), true);

if ($action === 'redeem_code' && $method === 'POST') {
    $headers = getallheaders();
    $token = str_replace('Bearer ', '', $headers['Authorization'] ?? '');
    $payload = JWT::decode($token);
    if (!$payload) respond(['error' => 'Token inválido ou expirado.'], 401);

    $userId = $payload['user_id'];
    $code = strtoupper(trim($input['code'] ?? ''));

    if (empty($code)) respond(['error' => 'Código não informado.'], 400);

    // Verify code
    $stmt = $db->prepare("SELECT id, is_used FROM invite_codes WHERE code = ?");
    $stmt->execute([$code]);
    $invite = $stmt->fetch();

    if (!$invite) respond(['error' => 'Código de convite inválido.'], 400);
    if ($invite['is_used']) respond(['error' => 'Este código já foi utilizado.'], 400);

    // Mark code as used
    $stmtUpd = $db->prepare("UPDATE invite_codes SET is_used = 1, used_by = ? WHERE id = ?");
    $stmtUpd->execute([$userId, $invite['id']]);

    // Update user to lifetime family plan
    $stmtUsr = $db->prepare("UPDATE users SET plan = 'family', subscription_status = 'lifetime', trial_ends_at = NULL WHERE id = ?");
    $stmtUsr->execute([$userId]);

    respond(['message' => 'Código resgatado! Você agora possui acesso vitalício ao Plano Família.']);
}

// Create checkout preference link
if ($action === 'create_preference' && $method === 'POST') {
    $headers = getallheaders();
    $token = str_replace('Bearer ', '', $headers['Authorization'] ?? '');
    $payload = JWT::decode($token);
if (!$payload) respond(['error' => 'Token inválido ou expirado.'], 401);
    if (!$payload) respond(['error' => 'Unauthorized'], 401);

    $userId = $payload['user_id'];
    $plan = $input['plan'] ?? 'individual';
    $price = $plan === 'family' ? 19.90 : 5.00;

    $title = $plan === 'family' ? 'Assinatura Plano Família VouZelar' : 'Assinatura Plano Individual VouZelar';

    // Build the Mercado Pago Preference Body
    $preferenceData = [
        "items" => [
            [
                "title" => $title,
                "description" => "Acesso aos recursos premium por 30 dias",
                "quantity" => 1,
                "currency_id" => "BRL",
                "unit_price" => (float)$price
            ]
        ],
        "payer" => [
            "name" => "Usuário",
            "email" => "email_do_usuario_$userId@test.com" // O ideal é pegar o email do DB
        ],
        "back_urls" => [
            "success" => "https://vouzelar.maisdeus.com/#/dashboard",
            "failure" => "https://vouzelar.maisdeus.com/#/dashboard",
            "pending" => "https://vouzelar.maisdeus.com/#/dashboard"
        ],
        "auto_return" => "approved",
        "external_reference" => "USER_$userId"
    ];

    $ch = curl_init('https://api.mercadopago.com/checkout/preferences');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($preferenceData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . MP_ACCESS_TOKEN
    ]);

    // Ignorar verificação SSL se estiver local, remover para produção se preferir
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode >= 200 && $httpCode < 300) {
        $mpResponse = json_decode($response, true);

        // init_point é a URL de checkout (ou sandbox_init_point se estiver com chave TEST-)
        $url = (strpos(MP_ACCESS_TOKEN, 'TEST') !== false && isset($mpResponse['sandbox_init_point']))
            ? $mpResponse['sandbox_init_point']
            : $mpResponse['init_point'];

        respond(['init_point' => $url]);
    } else {
        error_log("MP Error: " . $response);
        respond(['error' => 'Erro ao criar preferência de pagamento. Verifique seu Access Token.'], 500);
    }
}

// Webhook for when payment is completed
if ($action === 'webhook' && $method === 'POST') {
    // MP Webhooks will hit this endpoint with data about the payment

    $topic = $_GET['topic'] ?? ($input['type'] ?? '');
    $id = $_GET['id'] ?? ($input['data']['id'] ?? null);

    if (!$id) {
        http_response_code(400);
        exit;
    }

    if ($topic === 'payment') {
        // 1. We would fetch the full payment info via cURL GET to https://api.mercadopago.com/v1/payments/{$id}
        // 2. We extract the external_reference (our user_id) and the status ('approved')
        // 3. We update our database

        // Mocking the update
        $userId = 1; // Simulated ID from webhook external_reference
        $status = 'approved';

        if ($status === 'approved') {
            $stmt = $db->prepare("UPDATE users SET subscription_status = 'active', trial_ends_at = datetime('now', '+30 days') WHERE id = ?");
            $stmt->execute([$userId]);
            error_log("Mercado Pago Webhook: User $userId subscription updated.");
        }
    }

    http_response_code(200);
    echo "OK";
}
