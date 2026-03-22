<?php
// Acesso: /app/buscador-ml/config.php

define('APP_PASSWORD', 'curador123'); // Mude para uma senha forte

// Credenciais da API do WooCommerce (Geradas no WP em: WooCommerce > Configurações > Avançado > API REST)
define('WC_URL', 'https://farodeouro.com.br'); // Seu site WP onde o WooCommerce está instalado
define('WC_CONSUMER_KEY', 'ck_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx');
define('WC_CONSUMER_SECRET', 'cs_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx');

// Credencial do ScraperAPI (Gratuito - 1.000 buscas/mês)
// Evita que a Hostinger seja bloqueada pelo Mercado Livre (Anti-bot Cloudflare/Datadome)
// Crie sua conta grátis em: https://www.scraperapi.com/
define('SCRAPER_API_KEY', 'SUA_CHAVE_AQUI');
?>