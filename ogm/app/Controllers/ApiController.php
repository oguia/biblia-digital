<?php

class ApiController extends Controller {
    public function chat() {
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents('php://input'), true);
        $query = $input['query'] ?? null;

        if (!$query) {
            echo json_encode(['error' => 'No query provided']);
            return;
        }

        try {
            // Need Gemini Service here (Plan Step 5)
            // For now, return a placeholder
            $gemini = new GeminiService();
            $response = $gemini->ask($query);
            echo json_encode(['response' => $response]);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}
