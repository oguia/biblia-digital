<?php
// Script para buscar encartes em lojas grandes e extrair infos via Google Gemini
require_once 'db.php';

// ATENÇÃO: COLOQUE A CHAVE DO GEMINI AQUI PARA A IA LER IMAGENS
$GEMINI_API_KEY = 'SUA_CHAVE_GEMINI_AQUI';

// Logs para cronjob
$log = [];
function addLog($msg) {
    global $log;
    $timestamp = date("Y-m-d H:i:s");
    $log[] = "[$timestamp] $msg";
    echo "[$timestamp] $msg\n";
}

addLog("Iniciando cron_scraper.php");

// 1. Simular raspagem de encarte (Scraping básico com DOMDocument)
// Na prática, lojas como Condor e Muffato bloqueiam bots simples.
// Para a Hostinger, o ideal é usar RSS Feeds se disponíveis, ou extração do HTML da página de "ofertas da semana".
// Aqui faremos um Mock de URLs de encartes achados na "raspagem" para enviar à IA.

$urls_encartes_encontrados = [
    // Simulação de encartes encontrados no scraper
    [ 'loja' => 'Condor', 'imagem_url' => 'https://exemplo.com/encarte_condor_hoje.jpg', 'categoria' => 'Supermercado' ]
];

addLog("Encontrados " . count($urls_encartes_encontrados) . " possíveis encartes.");

// 2. Enviar imagens para o Google Gemini extrair produtos
foreach ($urls_encartes_encontrados as $encarte) {
    addLog("Processando encarte da loja: " . $encarte['loja']);

    if ($GEMINI_API_KEY === 'SUA_CHAVE_GEMINI_AQUI') {
        addLog("API KEY do Gemini não configurada. Pulando IA.");
        continue;
    }

    // Estrutura do prompt para o Gemini 1.5 Flash (ideal para imagens)
    $prompt = "Você é um assistente especialista em ler encartes de supermercado. Extraia as 3 melhores ofertas desta imagem. Retorne estritamente no formato JSON: [{\"titulo\": \"Nome do Produto e Quantidade\", \"preco\": 10.99}]";

    // Chamada cURL para o Gemini (Visão)
    $url_gemini = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=" . $GEMINI_API_KEY;

    $payload = [
        "contents" => [
            [
                "parts" => [
                    ["text" => $prompt],
                    // OBS: O Gemini requer base64 ou URL no File API.
                    // Para simplificar, estamos passando a URL no texto, o Gemini atual não suporta URL direta no base64,
                    // Em produção na Hostinger seria feito o download da imagem e envio em base64 aqui.
                    ["text" => "URL da Imagem para análise: " . $encarte['imagem_url']]
                ]
            ]
        ],
        "generationConfig" => [
            "response_mime_type" => "application/json"
        ]
    ];

    $ch = curl_init($url_gemini);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code === 200) {
        $result = json_decode($response, true);
        if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
            $json_text = $result['candidates'][0]['content']['parts'][0]['text'];
            $ofertas_extraidas = json_decode($json_text, true);

            if (is_array($ofertas_extraidas)) {
                addLog("Gemini extraiu " . count($ofertas_extraidas) . " ofertas com sucesso.");

                // 3. Salvar no banco (Bot)
                $stmt = $db->prepare("INSERT INTO ofertas (titulo, preco, loja, imagem_url, categoria, fonte, status) VALUES (?, ?, ?, ?, ?, 'auto', 'ativo')");

                foreach ($ofertas_extraidas as $of) {
                    if (isset($of['titulo'], $of['preco'])) {
                        try {
                            $stmt->execute([
                                $of['titulo'],
                                floatval($of['preco']),
                                $encarte['loja'],
                                $encarte['imagem_url'],
                                $encarte['categoria']
                            ]);
                            addLog("Oferta salva: " . $of['titulo'] . " - R$ " . $of['preco']);
                        } catch (PDOException $e) {
                            addLog("Erro ao salvar no banco: " . $e->getMessage());
                        }
                    }
                }
            } else {
                 addLog("Erro no JSON do Gemini: " . $json_text);
            }
        }
    } else {
        addLog("Erro na API Gemini (HTTP $http_code): " . $response);
    }
}

addLog("Cronjob finalizado.");
file_put_contents(__DIR__ . '/cron_log.txt', implode("\n", $log) . "\n", FILE_APPEND);
?>