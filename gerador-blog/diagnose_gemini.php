<?php
// Acesso: /app/gerador-blog/diagnose_gemini.php
// Este script consulta a API do Google para listar exatamente quais modelos a sua chave específica tem permissão para usar.

require_once 'config.php';

header('Content-Type: text/html; charset=utf-8');

echo "<h2>Diagnóstico da Chave do Google Gemini</h2>";

$api_key = trim(GEMINI_API_KEY);

if (empty($api_key) || $api_key === 'SUA_CHAVE_DO_GOOGLE_AQUI') {
    die("<b>Erro:</b> Você precisa configurar a sua GEMINI_API_KEY no arquivo <code>config.php</code> primeiro.");
}

echo "<p>Testando comunicação com o Google Cloud (ListModels)...</p>";

$url = "https://generativelanguage.googleapis.com/v1beta/models?key=" . $api_key;

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// Desligar verificação SSL para evitar problemas com a Hostinger
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http_code !== 200) {
    echo "<h3 style='color:red;'>Erro na comunicação com o Google (HTTP {$http_code})</h3>";
    $error_data = json_decode($response, true);
    if (isset($error_data['error']['message'])) {
        echo "<p><b>Detalhe do Erro:</b> " . htmlspecialchars($error_data['error']['message']) . "</p>";
    } else {
        echo "<pre>" . htmlspecialchars($response) . "</pre>";
    }
    echo "<p>Se o erro for 'API key not valid', significa que a chave que você colou no config.php está errada ou inativa.</p>";
    exit;
}

$data = json_decode($response, true);

if (!isset($data['models'])) {
    echo "<p>A API retornou sucesso, mas não listou nenhum modelo. Resposta do Google:</p>";
    echo "<pre>" . htmlspecialchars($response) . "</pre>";
    exit;
}

echo "<h3 style='color:green;'>Sucesso! Sua chave é válida.</h3>";
echo "<p>Abaixo estão todos os modelos que a <b>sua conta</b> tem permissão para usar para gerar conteúdo (generateContent):</p>";

echo "<ul>";
$found_compatible = false;
$recommended_model = "";

foreach ($data['models'] as $model) {
    // We only care about models that support generateContent
    if (in_array('generateContent', $model['supportedGenerationMethods'])) {
        $model_name = htmlspecialchars($model['name']);

        // Let's highlight the best ones
        if (preg_match('/gemini-2\.[0-5]-pro/', $model_name) || strpos($model_name, 'gemini-1.5-pro') !== false) {
            echo "<li><strong style='color:blue;'>{$model_name} (Recomendado/Excelente)</strong></li>";
            if(empty($recommended_model)) $recommended_model = $model_name;
            $found_compatible = true;
        } elseif (preg_match('/gemini-2\.[0-5]-flash/', $model_name) || strpos($model_name, 'gemini-1.5-flash') !== false || strpos($model_name, 'gemini-flash-latest') !== false) {
            echo "<li><strong style='color:blue;'>{$model_name} (Recomendado/Rápido)</strong></li>";
            if(empty($recommended_model)) $recommended_model = $model_name;
            $found_compatible = true;
        } elseif (strpos($model_name, 'gemini-1.0-pro') !== false || strpos($model_name, 'gemini-pro-latest') !== false) {
            echo "<li><strong>{$model_name} (Funciona bem)</strong></li>";
            if(empty($recommended_model)) $recommended_model = $model_name;
            $found_compatible = true;
        } else {
            echo "<li>{$model_name}</li>";
            // Even if it's an unrecognized cutting edge model, we know it supports generateContent, so it's technically compatible
            $found_compatible = true;
            if(empty($recommended_model)) $recommended_model = $model_name;
        }
    }
}
echo "</ul>";

if ($found_compatible) {
    echo "<div style='background:#f4f4f4; padding: 15px; border-left: 5px solid #007bff; margin-top:20px;'>";
    echo "<b>O que fazer agora?</b><br><br>";
    echo "1. Escolha UM dos modelos azuis acima (por exemplo: <code>{$recommended_model}</code>).<br>";
    echo "2. Abra o arquivo <code>api.php</code>.<br>";
    echo "3. Encontre a linha:<br>";
    echo "<code>\$url = \"https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-pro:generateContent?key=\" . \$gemini_key;</code><br>";
    echo "4. Troque <code>models/gemini-1.5-pro</code> pelo nome exato que o Google recomendou acima.<br>";
    echo "5. Salve o arquivo e tente gerar o artigo novamente!";
    echo "</div>";
} else {
    echo "<p style='color:red;'>Nenhum modelo compatível com 'generateContent' (Gemini) foi encontrado na sua conta. Você pode estar usando uma chave legada do PaLM 2 (text-bison) ou seu projeto no Google Cloud não ativou a Generative Language API para os modelos mais novos.</p>";
}

?>