<?php
// Cabeceras de seguridad
header("Access-Control-Allow-Origin: https://dentaldoc.proyectobinor.com/");

header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Security-Policy: default-src 'self'; script-src 'self'; style-src 'self'; frame-ancestors 'none';");
header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");

require_once '../modelo/loginDAO.php';

$funcion = $_GET['funcion'];

switch ($funcion) {
    case "login":
        login();
        break;
    case "logout":
        logout();
        break;
}

function login()
{
    $usario = $_POST['usuario'];
    $password = $_POST['password'];
    $respuesta = (new login())->login($usario, $password);
    if ($respuesta == null) {
        echo json_encode($respuesta);
    } else {
        session_start();
        $_SESSION['sesion'] = [
            "user" => $usario,
            "tipo" => $respuesta['tipo'],
            "name" => $respuesta['nombre'],
            "bd" => $respuesta['cliente']
        ];
        echo json_encode($respuesta);
    }
}

function logout()
{
    session_start();
    session_destroy();
}
