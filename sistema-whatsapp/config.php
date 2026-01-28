<?php
// Configurações do Banco de Dados
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'sistema_whatsapp');

// Configurações do WhatsApp (Meta)
// Pegue esses dados em developers.facebook.com
define('WA_TOKEN', 'SEU_TOKEN_AQUI');
define('WA_PHONE_ID', 'SEU_PHONE_ID_AQUI');

// Configuração do Webhook
// Escolha uma senha segura e coloque a mesma no painel da Meta
define('WEBHOOK_VERIFY_TOKEN', 'minha_senha_segura');

// URL Base do sistema (para links, se necessário)
define('BASE_URL', 'https://seusite.com/whatsapp/');
