<?php
// Seguridad HTTP

$nonce = base64_encode(random_bytes(16));

header("Content-Security-Policy: default-src 'self'; script-src 'self' 'nonce-$nonce'; style-src 'self' 'nonce-$nonce'; object-src 'none'; base-uri 'self'; frame-ancestors 'none';");
header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");
header_remove("X-Powered-By");
header_remove("Last-Modified");
header_remove("Date");
header("Strict-Transport-Security: max-age=31536000; includeSubDomains; preload");

// Solo si estás trabajando en desarrollo y frontend está en otro origen:
if ($_SERVER['HTTP_HOST'] === 'localhost') {
    header("Access-Control-Allow-Origin: http://localhost:5173");
}
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
?>
