<?php
// Configuración centralizada para CORS y CSP

function setupCORS() {
    header("Access-Control-Allow-Origin: https://dentaldoc.proyectobinor.com/");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    
    // Manejar preflight OPTIONS
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit;
    }
}

function setupCSP() {
    header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://www.google.com https://www.gstatic.com https://www.google-analytics.com https://unpkg.com; style-src 'self' 'unsafe-inline'; connect-src 'self' https://dentaldoc.proyectobinor.com https://www.google.com https://www.gstatic.com https://unpkg.com; worker-src 'self' blob:; frame-ancestors 'none';");
    header("X-Frame-Options: DENY");
    header("X-Content-Type-Options: nosniff");
}

function setupSecurityHeaders() {
    setupCORS();
    setupCSP();
}
?>