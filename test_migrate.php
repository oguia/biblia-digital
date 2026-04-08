<?php
// Mock server for testing index.php
$_SERVER['REQUEST_METHOD'] = 'GET';
$_GET['route'] = 'migrate';

ob_start();
require __DIR__ . '/faro-de-ouro/api/index.php';
$output = ob_get_clean();

echo "OUTPUT:\n$output\n";
