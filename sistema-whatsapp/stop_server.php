<?php
// Script para matar o processo Node.js
// Útil para o Cronjob das 18:00 (Fim do expediente)

$process_name = 'server.js';

// Busca o PID
$pids = shell_exec("pgrep -f '$process_name'");

if ($pids) {
    $pids = explode("\n", trim($pids));
    foreach ($pids as $pid) {
        if (!empty($pid)) {
            shell_exec("kill $pid");
            echo "Processo $pid ($process_name) encerrado.<br>";
        }
    }
} else {
    echo "Nenhum processo $process_name encontrado.";
}
