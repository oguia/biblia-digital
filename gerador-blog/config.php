<?php
// Acesso: /app/gerador-blog/config.php

define('APP_PASSWORD', 'curador123'); // A senha do seu painel do Gerador

// 1. O link do seu site WordPress
define('WP_URL', 'https://farodeouro.com.br');

// 2. Chaves do WooCommerce (Para ler os produtos da loja)
// As mesmas que você gerou para o "Buscador ML" (WooCommerce > Configurações > Avançado > API REST)
define('WC_CONSUMER_KEY', 'ck_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx');
define('WC_CONSUMER_SECRET', 'cs_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx');

// 3. Senha de Aplicativo do WordPress (Para criar o post no Blog)
// Vá em Usuários > Perfil no WordPress. Role até o fim em "Senhas de Aplicativo".
// Dê um nome (ex: Gerador Blog) e clique em Adicionar Nova Senha.
// Preencha seu login de administrador (ex: admin) e a senha gerada (com ou sem espaços, tanto faz).
define('WP_ADMIN_USERNAME', 'SEU_LOGIN_DE_ADMIN_AQUI');
define('WP_APP_PASSWORD', 'xxxx xxxx xxxx xxxx xxxx xxxx');

// 4. Chave Gratuita da Inteligência Artificial do Google Gemini (Para escrever o artigo)
// Crie de graça em: https://aistudio.google.com/app/apikey (Faça login com seu Gmail)
define('GEMINI_API_KEY', 'SUA_CHAVE_DO_GOOGLE_AQUI');
?>