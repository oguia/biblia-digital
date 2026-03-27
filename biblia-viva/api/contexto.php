<?php
header('Content-Type: application/json; charset=utf-8');

require_once '../includes/functions.php';

$livroId = isset($_GET['livro']) ? (int)$_GET['livro'] : 1;
$capitulo = isset($_GET['cap']) ? (int)$_GET['cap'] : 1;

try {
    $model = new BibliaModel();

    // Buscar contextos
    $contextoGeo = $model->getContextoGeografico($livroId, $capitulo);
    $cronologia = $model->getCronologia($livroId, $capitulo);
    $aplicacao = $model->getAplicacaoPratica($livroId, $capitulo);

    echo json_encode([
        'success' => true,
        'data' => [
            'geo' => $contextoGeo,
            'cronologia' => $cronologia,
            'aplicacao' => $aplicacao
        ]
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
