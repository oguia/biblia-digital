<?php
// Planer/api/config.php

/**
 * CONFIGURAÇÕES DO SISTEMA PLANER
 *
 * Antes de colocar o sistema em produção, você deve alterar as variáveis abaixo.
 */

// 1. URL DO SEU SITE
// Coloque a URL exata de onde o Planer está instalado (sem barra no final).
// Exemplo: 'https://meusite.com.br/planer'
define('SITE_URL', 'http://localhost:8000');

// 2. TOKEN DO MERCADO PAGO
// Acesse https://www.mercadopago.com.br/developers/panel
// Crie uma aplicação, vá em "Credenciais de Produção" e copie o "Access Token"
define('MP_ACCESS_TOKEN', 'COLOQUE_SEU_ACCESS_TOKEN_DO_MERCADO_PAGO_AQUI');
