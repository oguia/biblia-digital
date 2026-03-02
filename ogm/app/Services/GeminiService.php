<?php

class GeminiService {
    private $apiKey;
    private $db;

    public function __construct() {
        $config = require __DIR__ . '/../../config/config.php';
        $this->apiKey = $config['app']['gemini_api_key'];
        $this->db = Database::getInstance()->getConnection();
    }

    public function ask($userQuery) {
        // 1. Retrieve Context (Simple RAG)
        $context = $this->retrieveContext($userQuery);

        // 2. Build Prompt
        $systemPrompt = "Você é o GuiaBot, um assistente virtual útil e amigável do site 'O Guia Metropolitano' de Curitiba. " .
                        "Sua função é recomendar empresas locais com base APENAS no contexto fornecido abaixo. " .
                        "Se a resposta não estiver no contexto, diga educadamente que não encontrou a informação específica, " .
                        "mas sugira que o usuário busque por outras categorias no menu. " .
                        "Não invente informações. Seja conciso e direto. Formate a resposta em HTML simples (p, ul, li, strong).";

        $prompt = "Contexto:\n" . $context . "\n\n" .
                  "Pergunta do Usuário: " . $userQuery . "\n\n" .
                  "Resposta:";

        // 3. Call Gemini API
        $response = $this->callGemini($systemPrompt . "\n\n" . $prompt);

        // 4. Log Interaction
        $this->logInteraction($userQuery, $response);

        return $response;
    }

    private function logInteraction($query, $response) {
        try {
            $stmt = $this->db->prepare("INSERT INTO ai_logs (user_query, ai_response) VALUES (:q, :r)");
            $stmt->execute(['q' => $query, 'r' => $response]);
        } catch (Exception $e) {
            // Silently fail logging to not disrupt user experience
            error_log("Failed to log AI interaction: " . $e->getMessage());
        }
    }

    private function retrieveContext($query) {
        // Simple keyword extraction (naive approach)
        // In a real system, we'd use embeddings or fulltext search.
        // We'll use the Company model's search method.

        $terms = explode(' ', $query);
        $terms = array_filter($terms, function($t) { return strlen($t) > 3; }); // Filter small words

        if (empty($terms)) return "Nenhuma informação relevante encontrada.";

        // Construct a broad search query
        // We'll just search for the whole string first, then fall back to terms if needed.
        // For simplicity here, let's search for the user query directly in the Company search.

        $companyModel = new Company();
        // search($query, $category, $neighborhood, $page, $limit)
        $results = $companyModel->search($query, null, null, 1, 5);

        if (empty($results)) {
             // Fallback: try finding a category in the query
             $categoryModel = new Category();
             $categories = $categoryModel->all();
             foreach ($categories as $cat) {
                 if (stripos($query, $cat['name']) !== false) {
                     $results = $companyModel->search('', $cat['slug'], null, 1, 5);
                     break;
                 }
             }
        }

        if (empty($results)) {
            return "Nenhuma empresa encontrada relacionada à sua busca.";
        }

        $contextText = "";
        foreach ($results as $company) {
            $contextText .= "- Nome: {$company['name']}\n";
            $contextText .= "  Categoria: {$company['category_name']}\n";
            $contextText .= "  Bairro: {$company['neighborhood_name']}\n";
            $contextText .= "  Endereço: {$company['address']}\n";
            $contextText .= "  Descrição: {$company['description']}\n";
            $contextText .= "  Link: /empresa/{$company['slug']}\n\n";
        }

        return $contextText;
    }

    private function callGemini($prompt) {
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key=" . $this->apiKey;

        $data = [
            "contents" => [
                [
                    "parts" => [
                        ["text" => $prompt]
                    ]
                ]
            ]
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json"
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            return "Erro ao conectar com a IA: " . curl_error($ch);
        }

        curl_close($ch);

        $json = json_decode($response, true);

        if (isset($json['candidates'][0]['content']['parts'][0]['text'])) {
            return $json['candidates'][0]['content']['parts'][0]['text'];
        } else {
            // Log raw response for debugging if needed
            // error_log(print_r($json, true));
            return "Desculpe, não consegui gerar uma resposta no momento.";
        }
    }
}
