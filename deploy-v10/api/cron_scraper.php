<?php
// Script Leitor de Encartes Locais via Google Gemini
require_once 'db.php';

// ATENÇÃO: COLOQUE A CHAVE DO GEMINI AQUI:
$GEMINI_API_KEY = 'SUA_CHAVE_AQUI';

$log = [];
function addLog($msg) {
    global $log;
    $timestamp = date("Y-m-d H:i:s");
    $log[] = "[$timestamp] $msg";
    echo "[$timestamp] $msg\n";
}

addLog("Iniciando Leitor de Encartes Automático...");

// 1. PASTA ONDE VOCÊ VAI JOGAR AS IMAGENS: public_html/encartes
$diretorio_encartes = __DIR__ . '/../encartes/';

if (!is_dir($diretorio_encartes)) {
    mkdir($diretorio_encartes, 0755, true);
    addLog("Pasta 'encartes' criada na raiz. Jogue as imagens dos mercados lá e rode novamente.");
    echo json_encode(['status' => 'Pasta criada', 'logs' => $log]);
    exit;
}

// 2. LER ARQUIVOS DA PASTA
$arquivos = scandir($diretorio_encartes);
$imagens_para_processar = [];

foreach ($arquivos as $arquivo) {
    // Ignora pastas e arquivos ocultos ou já processados
    if ($arquivo === '.' || $arquivo === '..' || strpos($arquivo, 'processado_') === 0) continue;

    $extensao = strtolower(pathinfo($arquivo, PATHINFO_EXTENSION));
    if (in_array($extensao, ['jpg', 'jpeg', 'png', 'webp'])) {

        // DEDUZIR A LOJA PELO NOME DO ARQUIVO (ex: "condor-encarte.jpg" -> Loja: Condor)
        $nome_limpo = strtolower($arquivo);
        $loja = 'Mercado Local';
        $categoria = 'Supermercado';

        if (strpos($nome_limpo, 'condor') !== false) { $loja = 'Condor'; }
        elseif (strpos($nome_limpo, 'muffato') !== false) { $loja = 'Super Muffato'; }
        elseif (strpos($nome_limpo, 'carrefour') !== false) { $loja = 'Carrefour'; }
        elseif (strpos($nome_limpo, 'extra') !== false) { $loja = 'Extra'; }
        elseif (strpos($nome_limpo, 'balaroti') !== false) { $loja = 'Balaroti'; $categoria = 'Construção'; }
        elseif (strpos($nome_limpo, 'leroy') !== false) { $loja = 'Leroy Merlin'; $categoria = 'Construção'; }

        $imagens_para_processar[] = [
            'arquivo' => $arquivo,
            'caminho_completo' => $diretorio_encartes . $arquivo,
            'loja' => $loja,
            'categoria' => $categoria
        ];
    }
}

if (empty($imagens_para_processar)) {
    addLog("Nenhuma imagem nova encontrada na pasta 'encartes'.");
    echo json_encode(['status' => 'Sem novas imagens', 'logs' => $log]);
    exit;
}

addLog("Encontradas " . count($imagens_para_processar) . " novas imagens para ler.");

// 3. CONECTAR NO GOOGLE GEMINI
addLog("Buscando modelo inteligente na sua conta Google...");
$ch_models = curl_init("https://generativelanguage.googleapis.com/v1beta/models?key=" . trim($GEMINI_API_KEY));
curl_setopt($ch_models, CURLOPT_RETURNTRANSFER, true);
$models_response = curl_exec($ch_models);
curl_close($ch_models);
$available_models = json_decode($models_response, true);
$model_name = 'gemini-1.5-flash';

if (isset($available_models['models'])) {
    foreach ($available_models['models'] as $model) {
        if (strpos($model['name'], 'gemini-1.5') !== false && in_array('generateContent', $model['supportedGenerationMethods'] ?? [])) {
            $model_name = str_replace('models/', '', $model['name']);
            break;
        }
    }
}

// 4. PROCESSAR CADA IMAGEM DA PASTA
foreach ($imagens_para_processar as $encarte) {
    addLog("Enviando foto '{$encarte['arquivo']}' (Dedução: Loja {$encarte['loja']}) para a IA...");

    $imagem_binaria = file_get_contents($encarte['caminho_completo']);
    $imagem_base64 = base64_encode($imagem_binaria);

    // O MIME TYPE é dinâmico com base no arquivo
    $mime = 'image/jpeg';
    if (strpos(strtolower($encarte['arquivo']), '.png') !== false) $mime = 'image/png';
    if (strpos(strtolower($encarte['arquivo']), '.webp') !== false) $mime = 'image/webp';

    $prompt = "Você é um assistente especialista em ler encartes de ofertas de supermercados e construção do Brasil. Extraia TODAS as ofertas que você conseguir enxergar com clareza nesta imagem. Extraia o nome completo do produto (com peso/litragem) e o preço exato. Retorne ESTRITAMENTE um array JSON com as chaves 'titulo' e 'preco'. Não use caracteres especiais no início ou fim. Exemplo rigoroso: [{\"titulo\": \"Arroz Branco 5kg\", \"preco\": 22.90}, {\"titulo\": \"Cimento 50kg\", \"preco\": 30.50}]";

    $url_gemini = "https://generativelanguage.googleapis.com/v1beta/models/{$model_name}:generateContent?key=" . trim($GEMINI_API_KEY);

    $payload = [
        "contents" => [
            [
                "parts" => [
                    ["text" => $prompt],
                    [
                        "inlineData" => [
                            "mimeType" => $mime,
                            "data" => $imagem_base64
                        ]
                    ]
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

            $json_text = preg_replace('/```json/i', '', $json_text);
            $json_text = preg_replace('/```/', '', $json_text);
            $json_text = trim($json_text);
            preg_match('/\[.*\]|\{.*\}/s', $json_text, $matches);

            if (!empty($matches)) {
                $ofertas_extraidas = json_decode(trim($matches[0]), true);

                if (is_array($ofertas_extraidas) && isset($ofertas_extraidas['titulo'])) {
                    $ofertas_extraidas = [$ofertas_extraidas];
                }

                if (is_array($ofertas_extraidas)) {
                    $count_salvos = 0;
                    addLog("Sucesso! A IA encontrou e leu " . count($ofertas_extraidas) . " produtos reais na imagem.");

                    $stmt = $db->prepare("INSERT INTO ofertas (titulo, preco, loja, imagem_url, categoria, fonte, status) VALUES (?, ?, ?, ?, ?, 'auto', 'ativo')");

                    foreach ($ofertas_extraidas as $of) {
                        if (isset($of['titulo'], $of['preco'])) {
                            try {
                                $preco_limpo = preg_replace('/[^0-9.]/', '', str_replace(',', '.', $of['preco']));

                                // O caminho da imagem para a web
                                $caminho_web = '/encartes/' . $encarte['arquivo'];

                                $stmt->execute([
                                    $of['titulo'],
                                    floatval($preco_limpo),
                                    $encarte['loja'],
                                    $caminho_web,
                                    $encarte['categoria']
                                ]);
                                $count_salvos++;
                                addLog("--> Salvo: " . $of['titulo'] . " - R$ " . $preco_limpo);
                            } catch (PDOException $e) {
                                addLog("Erro no banco: " . $e->getMessage());
                            }
                        }
                    }

                    // RENOMEAR ARQUIVO PARA NÃO LER DE NOVO
                    rename($encarte['caminho_completo'], $diretorio_encartes . 'processado_' . time() . '_' . $encarte['arquivo']);
                    addLog("Arquivo renomeado para evitar leitura dupla no futuro.");

                } else {
                     addLog("Erro JSON interno da IA.");
                }
            } else {
                 addLog("A IA não gerou formato JSON.");
            }
        }
    } else {
        addLog("Erro no servidor da Google. HTTP $http_code");
    }
}

addLog("Fim da operação.");
file_put_contents(__DIR__ . '/cron_log.txt', implode("\n", $log) . "\n", FILE_APPEND);

echo json_encode(['status' => 'Concluido', 'logs' => $log]);
?>