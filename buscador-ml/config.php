<?php
// Acesso: /app/buscador-ml/config.php

define('APP_PASSWORD', 'curador123'); // Mude para uma senha forte

// Credenciais da API do WooCommerce (Geradas no WP em: WooCommerce > Configurações > Avançado > API REST)
define('WC_URL', 'https://farodeouro.com.br'); // Seu site WP onde o WooCommerce está instalado
define('WC_CONSUMER_KEY', 'ck_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx');
define('WC_CONSUMER_SECRET', 'cs_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx');

// Credenciais do Mercado Livre Developers (Obrigatórias para evitar bloqueio 403 na Hostinger)
// 1. Crie uma aplicação em: https://developers.mercadolivre.com.br/devcenter/
// 2. Em "URI de redirecionamento", coloque a URL exata do arquivo auth.php (ex: https://farodeouro.com.br/buscador/auth.php)
define('ML_APP_ID', 'SEU_APP_ID_AQUI');
define('ML_SECRET_KEY', 'SEU_SECRET_KEY_AQUI');
define('ML_REDIRECT_URI', 'https://farodeouro.com.br/buscador/auth.php');
?>