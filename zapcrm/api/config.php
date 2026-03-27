<?php
// Since Hostinger uses absolute paths depending on the user, let's establish a base URL for the webhook to give to node.js
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$domainName = $_SERVER['HTTP_HOST'] . '/';
$webhookUrl = $protocol . $domainName . ltrim(dirname($_SERVER['REQUEST_URI']), '/') . '/webhook';
// Note: webhook.php handles the logic
?>
