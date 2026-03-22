<?php
// Acesso: /app/gerador-blog/diagnose_wp.php
// Diagnóstico do WordPress REST API (Application Passwords)
require_once 'config.php';

header('Content-Type: text/html; charset=utf-8');

echo "<h2>Diagnóstico da Conexão com o WordPress (WP REST API)</h2>";

if (empty(WP_ADMIN_USERNAME) || empty(WP_APP_PASSWORD) || strpos(WP_APP_PASSWORD, 'xxxx') !== false) {
    die("<h3 style='color:red;'>Erro de Configuração</h3><p>O seu <b>WP_ADMIN_USERNAME</b> ou <b>WP_APP_PASSWORD</b> no arquivo <code>config.php</code> ainda não foram configurados (estão vazios ou com 'xxxx').</p>");
}

echo "<p>Testando comunicação com a sua loja WordPress...</p>";
echo "<ul>";
echo "<li>URL: <code>" . WP_URL . "</code></li>";
echo "<li>Usuário: <code>" . WP_ADMIN_USERNAME . "</code></li>";
// Ocultamos parte da senha por segurança
$hidden_pass = substr(WP_APP_PASSWORD, 0, 4) . ' **** **** ' . substr(WP_APP_PASSWORD, -4);
echo "<li>Senha de Aplicativo: <code>{$hidden_pass}</code></li>";
echo "</ul>";

$url = rtrim(WP_URL, '/') . '/wp-json/wp/v2/users/me';

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_USERPWD, WP_ADMIN_USERNAME . ":" . WP_APP_PASSWORD);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http_code === 200) {
    $data = json_decode($response, true);
    echo "<h3 style='color:green;'>Sucesso! (HTTP 200)</h3>";
    echo "<p>Sua Senha de Aplicativo funcionou perfeitamente. O WordPress reconheceu você como: <b>" . htmlspecialchars($data['name']) . "</b>.</p>";
    echo "<p>Seu sistema deve conseguir postar rascunhos normalmente agora.</p>";
    exit;
}

// Em caso de erro...
echo "<h3 style='color:red;'>Erro na Autenticação (HTTP {$http_code})</h3>";
$data = json_decode($response, true);

if (isset($data['code']) && isset($data['message'])) {
    echo "<p><b>O WordPress respondeu:</b> [{$data['code']}] " . htmlspecialchars($data['message']) . "</p>";
} else {
    echo "<pre>" . htmlspecialchars($response) . "</pre>";
}

echo "<div style='background:#fff3cd; padding: 15px; border-left: 5px solid #ffc107; margin-top:20px;'>";
echo "<b>Como resolver o Erro 401 (Não Autorizado) no WordPress?</b><br><br>";
echo "<b>1. Você NÃO pode usar sua senha normal de login do Painel WP.</b><br>";
echo "Por segurança, o WordPress bloqueia acesso à API REST usando a senha comum. Você precisa criar uma <b>Senha de Aplicativo</b> especial.<br><br>";
echo "<b>Passo a Passo para criar:</b><br>";
echo "<ol>";
echo "<li>Entre no seu painel do WordPress (ex: seusite.com/wp-admin).</li>";
echo "<li>No menu lateral, vá em <b>Usuários > Perfil</b> (ou Todos os Usuários e edite o seu).</li>";
echo "<li>Role a página até o final, na seção chamada <b>Senhas de Aplicativo</b> (Application Passwords).</li>";
echo "<li>No campo 'Novo nome de senha de aplicativo', digite algo como <code>Gerador de Blog</code>.</li>";
echo "<li>Clique no botão <b>Adicionar nova senha de aplicativo</b>.</li>";
echo "<li>O WordPress vai gerar uma senha enorme cheia de espaços (ex: <code>abcd efgh ijkl mnop qrst uvwx</code>). <b>Copie essa senha exatamente como ela é.</b></li>";
echo "<li>Cole essa nova senha no seu arquivo <code>config.php</code> dentro das aspas de <b>WP_APP_PASSWORD</b>.</li>";
echo "</ol>";

echo "<b>2. Problema de Servidor (Hostinger) cortando Cabeçalhos HTTP</b><br>";
echo "Se você já fez o passo 1 e continua dando o erro <b>401 (Authorization header not found)</b>, é porque o servidor da Hostinger está bloqueando as credenciais de API por padrão.<br>";
echo "<b>Para consertar:</b><br>";
echo "Vá no gerenciador de arquivos da Hostinger, abra o arquivo <b><code>.htaccess</code></b> que fica na raiz do seu site WordPress principal (junto com wp-config.php) e adicione esta linha no começo dele:<br>";
echo "<pre style='background:#222; color:#fff; padding:10px;'>SetEnvIf Authorization \"(.*)\" HTTP_AUTHORIZATION=$1</pre>";
echo "</div>";

?>