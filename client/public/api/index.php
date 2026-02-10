<?php
header('Content-Type: application/json');

echo json_encode([
    "status" => "online",
    "message" => "Mais Deus Bible API is running",
    "timestamp" => date('c')
]);
?>
