<?php
$term = urlencode("losartana 50mg");
$url = "https://consultaremedios.com.br/busca?termo=" . $term;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64)");
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$html = curl_exec($ch);
curl_close($ch);

// Search for any mention of "R$" to understand the DOM
preg_match_all('/.{0,30}R\$ [0-9]+,[0-9]+.{0,30}/s', strip_tags($html), $matches);

print_r(array_slice($matches[0], 0, 5));
