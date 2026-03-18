<?php
// Acesso: /app/buscador-ml/auth.php
session_start();
require_once 'config.php';

// Proteção da Rota
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    die("Acesso negado. Faça login no index.php primeiro.");
}

// Usar caminho absoluto para evitar problemas de permissão em hospedagens compartilhadas
$token_file = __DIR__ . '/ml_tokens.php';

// Passo 1: Redirecionar para o Mercado Livre para Autorizar
if (!isset($_GET['code']) && !isset($_GET['action'])) {
    if (ML_APP_ID === 'SEU_APP_ID_AQUI') {
        die("<h1>Erro:</h1><p>Você precisa preencher o <b>ML_APP_ID</b> e <b>ML_SECRET_KEY</b> no arquivo <code>config.php</code> antes de autorizar o aplicativo.</p>");
    }

    $auth_url = "https://auth.mercadolivre.com.br/authorization?response_type=code&client_id=" . ML_APP_ID . "&redirect_uri=" . urlencode(ML_REDIRECT_URI);

    echo "<!DOCTYPE html><html><head><title>Autorizar Mercado Livre</title><script src='https://cdn.tailwindcss.com'></script></head><body class='bg-gray-100 flex items-center justify-center min-h-screen'>";
    echo "<div class='bg-white p-8 rounded-lg shadow-md max-w-lg text-center'>";
    echo "<h2 class='text-2xl font-bold mb-4 text-[#1A2B3C]'>Vincular Mercado Livre</h2>";
    echo "<p class='text-gray-600 mb-6'>Para que o buscador funcione sem ser bloqueado pela Hostinger (Erro 403), precisamos de uma autorização oficial do Mercado Livre para a sua conta.</p>";
    echo "<a href='{$auth_url}' class='bg-[#C19A6B] hover:bg-[#A88152] text-white font-bold py-3 px-6 rounded transition inline-block'>Autorizar Aplicativo agora</a>";
    echo "</div></body></html>";
    exit;
}

// Passo 2: Mercado Livre redirecionou de volta com um código (Authorization Code)
if (isset($_GET['code'])) {
    $code = $_GET['code'];

    // Trocar o código por um Access Token
    $post_data = http_build_query([
        'grant_type' => 'authorization_code',
        'client_id' => ML_APP_ID,
        'client_secret' => ML_SECRET_KEY,
        'code' => $code,
        'redirect_uri' => ML_REDIRECT_URI
    ]);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.mercadolibre.com/oauth/token');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/x-www-form-urlencoded',
        'Accept: application/json'
    ]);

    // Deixando o SSL verifier no padrão do sistema (ativo) para segurança. Se o servidor do usuário falhar, ele deve atualizar o cacert local.

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $data = json_decode($response, true);

    if ($http_code === 200 && isset($data['access_token'])) {
        // Salvar os tokens de forma segura (O token expira em 6h, o refresh expira em 6 meses)
        $tokens = [
            'access_token' => $data['access_token'],
            'refresh_token' => $data['refresh_token'],
            'expires_at' => time() + $data['expires_in'] - 60 // Expira 1 min antes por margem de erro
        ];

        $secure_content = "<?php die('Acesso negado'); ?>\n" . json_encode($tokens);

        // Tentar salvar o arquivo e checar erros
        $result = file_put_contents($token_file, $secure_content);

        if ($result === false) {
             echo "<!DOCTYPE html><html><head><script src='https://cdn.tailwindcss.com'></script></head><body class='bg-gray-100 p-8 text-center'>";
             echo "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mx-auto max-w-lg mb-4' role='alert'>Erro Crítico: Não foi possível salvar o arquivo <code>ml_tokens.php</code> no servidor. Verifique as permissões de gravação (pasta <code>buscador</code> na Hostinger deve permitir escrita pelo PHP). Caminho tentado: $token_file</div>";
             echo "<a href='auth.php' class='bg-[#1A2B3C] text-white px-6 py-3 rounded font-bold inline-block hover:bg-[#2A445D]'>Tentar Novamente</a>";
             echo "</body></html>";
             exit;
        }

        echo "<!DOCTYPE html><html><head><script src='https://cdn.tailwindcss.com'></script></head><body class='bg-gray-100 p-8 text-center flex flex-col items-center justify-center min-h-screen'>";
        echo "<div class='bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded shadow max-w-lg mb-6' role='alert'><strong>Sucesso!</strong> Mercado Livre Autorizado e token salvo no servidor.<br>O buscador agora funcionará perfeitamente!</div>";
        echo "<a href='index.php' class='bg-[#1A2B3C] text-white px-6 py-3 rounded shadow font-bold inline-block hover:bg-[#2A445D] transition'>Voltar para o Buscador e Pesquisar</a>";
        echo "</body></html>";
    } else {
        echo "<h1>Erro ao gerar token</h1>";
        echo "<p>Resposta do Mercado Livre (HTTP $http_code):</p>";
        echo "<pre>" . print_r($data, true) . "</pre>";
        echo "<br><a href='auth.php'>Tentar novamente</a>";
    }
    exit;
}
?>