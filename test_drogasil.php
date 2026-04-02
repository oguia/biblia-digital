<?php
// Most farmacias use SPAs (React/Vue/Angular) or heavily bot-protected Cloudflare.
// Let's try the Consulta Remedios API directly (GraphQL or internal Ajax).

$term = urlencode("losartana");
$url = "https://consultaremedios.com.br/api/v2/search/products?q=" . $term;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36");
$html = curl_exec($ch);
curl_close($ch);

echo substr($html, 0, 300);
