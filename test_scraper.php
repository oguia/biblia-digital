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

// Extremely simple regex to find product cards (might need tweaking based on their actual HTML)
// Trying to find something that looks like a price or a pharmacy name
preg_match_all('/<div class="product-block__price">.*?R\$ ([0-9,]+).*?<\/div>/s', $html, $prices);
preg_match_all('/<a class="product-block__title".*?href="(.*?)".*?>(.*?)<\/a>/s', $html, $titles);

print_r($prices[1]);
print_r($titles[1]);
print_r($titles[2]);
