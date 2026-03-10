<?php
require_once 'config.php';
require_once 'classes/Gemini.php';

header('Content-Type: application/json');

// Get POST body
$data = json_decode(file_get_contents('php://input'), true);
$userMessage = $data['message'] ?? '';
$history = $data['history'] ?? [];

if (empty($userMessage)) {
    echo json_encode(['reply' => 'Olá! Eu sou o assistente do Guia Metropolitano. Como posso te ajudar a encontrar um serviço hoje?']);
    exit;
}

if (!defined('GEMINI_API_KEY') || GEMINI_API_KEY === 'YOUR_GEMINI_API_KEY_HERE') {
    // Fallback if no API key
    echo json_encode(['reply' => 'Desculpe, meu cérebro de IA está offline no momento (API Key missing). Mas você pode usar a busca acima!']);
    exit;
}

$gemini = new Gemini(GEMINI_API_KEY);

// 1. Understand Intent & Keywords
$interpretation = $gemini->interpretSearch($userMessage);

$contextData = [];
if ($interpretation && !empty($interpretation['category_keyword'])) {
    $catKeyword = "%" . $interpretation['category_keyword'] . "%";
    $locKeyword = !empty($interpretation['location_keyword']) ? "%" . $interpretation['location_keyword'] . "%" : "%";

    try {
        // Fetch candidates
        $stmt = $pdo->prepare("
            SELECT b.name, b.description, b.address, b.phone, b.whatsapp, c.name as category, b.is_verified, b.slug
            FROM businesses b
            LEFT JOIN categories c ON b.category_id = c.id
            WHERE (c.name LIKE :cat OR b.description LIKE :cat OR b.name LIKE :cat)
            AND (b.address LIKE :loc OR b.city LIKE :loc)
            ORDER BY b.is_featured DESC, b.is_verified DESC
            LIMIT 5
        ");
        $stmt->execute(['cat' => $catKeyword, 'loc' => $locKeyword]);
        $contextData = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Ignore DB errors, just no context
    }
}

// 2. Formulate Final Prompt
$systemPrompt = "Você é o 'GuiaBot', um consultor útil e amigável de Curitiba. \n" .
                "Seu objetivo é ajudar o usuário a encontrar serviços locais com base nos dados fornecidos. \n" .
                "Não invente empresas. Use APENAS os dados fornecidos no CONTEXTO abaixo. \n" .
                "Se o contexto estiver vazio, diga que não encontrou nada específico, mas dê dicas gerais ou peça para refinar a busca. \n" .
                "Se encontrar opções, recomende a melhor baseada na pergunta (ex: 'urgente', 'barato'). \n" .
                "Sempre forneça o nome da empresa e o link (formato Markdown: [Nome](/negocio/slug)). \n" .
                "Seja conciso e direto. \n\n" .
                "CONTEXTO DE DADOS REAIS:\n" . json_encode($contextData, JSON_PRETTY_PRINT);

$fullPrompt = $systemPrompt . "\n\n" .
              "Histórico da conversa:\n" . json_encode($history) . "\n\n" .
              "Usuário: " . $userMessage;

// 3. Generate Reply
$reply = $gemini->generate($fullPrompt);

if (!$reply) {
    $reply = "Desculpe, estou tendo dificuldades para processar sua solicitação agora. Tente buscar diretamente na barra de pesquisa.";
}

echo json_encode(['reply' => $reply, 'context_used' => count($contextData)]);
