<?php
// Permitir solicitudes solo desde el origen específico
header("Access-Control-Allow-Origin: http://localhost:5173");
// Permitir el envío de cookies desde un origen diferente
header("Access-Control-Allow-Credentials: true");
// Permitir los métodos HTTP especificados (GET, POST, etc.)
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
// Permitir los encabezados HTTP especificados
header("Access-Control-Allow-Headers: Content-Type, Authorization");

require_once '../modelo/usuarioDAO.php';

$funcion = $_GET['funcion'];

switch ($funcion) {
    case "agregar":
        agregar();
        break;
    case "modificar":
        modificar();
        break;
    case "eliminar":
        eliminar();
        break;
    case "obtener":
        obtener();
        break;
    case "login":
        login();
        break;
    case "logout":
        logout();
        break;
}

function obtener()
{

    $resultado = (new usuario())->obtenerUsuarios();
    echo json_encode($resultado);
}

function agregar()
{
    // Obtener datos del formulario
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $contraseña=$_POST['contraseña'];
    $nombre_usuario = $_POST['nombre_usuario'];
    $tipo = $_POST['tipo'];

    // Crear una instancia de la clase Usuario y llamar al método agregarUsuario
    $usuario = new Usuario();
    $resultado = $usuario->agregarUsuario($nombre, $apellido, $nombre_usuario, $tipo, $contraseña);

    // Devolver el resultado en formato JSON
    echo json_encode($resultado);
}

function login()
{
    $usario = $_POST['usuario'];
    $password = $_POST['password'];
    $respuesta = (new usuario())->login($usario, $password);
    if ($respuesta == null) {
        echo json_encode($respuesta);
    } else {
        session_start();
        $_SESSION['sesion'] = [
            "user" => $usario,
            "tipo" => $respuesta['tipo'],
            "name" => $respuesta['nombre']
        ];
        echo json_encode($respuesta);
    }
}

function logout()
{
    session_start();
    session_destroy();
   
}
