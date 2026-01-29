<?php
// Este script serve para iniciar o servidor Node.js MANUALMENTE (se não usar Passenger).
// Se você usa o "Setup Node.js App" do cPanel para rodar 24h, NÃO use este script.

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verifica se já está rodando
$check = shell_exec("pgrep -f 'server.js'");
if ($check) {
    echo "O servidor já está rodando (PID: $check).<br>";
    echo "Se você quiser reiniciar, use o stop_server.php primeiro.";
    exit;
}

// Configuração
$node_path = 'node'; // Ou caminho completo ex: /home/user/nodevenv/.../bin/node
$server_script = __DIR__ . '/server/server.js';
$log_file = __DIR__ . '/node_output.log';

// Inicia em background
$cmd = "nohup $node_path $server_script > $log_file 2>&1 &";
shell_exec($cmd);

echo "Comando enviado: $cmd <br>";
echo "Servidor iniciado. Verifique o arquivo node_output.log para detalhes.";
