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
    case "obtenerOdontologos":
        obtenerOdontologos();
        break;

    case "perfil":
        perfil();
        break;

    case "modificar_imagen_usuario":
        modificar_imagen_usuario();
        break;

    case "resetear_contraseña":
        resetear_contraseña();
        break;
}

function perfil()
{

    $data = json_decode(file_get_contents('php://input'), true);
    $usuario = $data['usuario'];
    $resultado = (new usuario())->perfil($usuario);
    echo json_encode($resultado);
}

function modificar()
{
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $nombre_usuario = strtolower(substr($nombre, 0, 1) . $apellido);
    $tipo = $_POST['tipo'];
    $img_ant = './img/' . $_POST['nombre_usuario'] . '.jpg';
    $img_nuevo = './img/' . $nombre_usuario . '.jpg';
    if (file_exists($img_ant)) {
        copy($img_ant, $img_nuevo);
        // Si quieres eliminar la imagen anterior, descomenta la siguiente línea:
         unlink($img_ant);
    }
    $resultado = (new usuario())->modificarUsuario($id, $nombre, $apellido, $nombre_usuario, $tipo);
    echo json_encode($resultado);
}
function resetear_contraseña()
{
    // $data = json_decode(file_get_contents('php://input'), true);
    $usuario = $_POST['id'];
    $contraseña = $_POST['contraseña'];

    $resultado = (new usuario())->resetearContraseña($usuario, $contraseña);
    echo json_encode($resultado);
}
function obtener()
{

    $resultado = (new usuario())->obtenerUsuarios();
    echo json_encode($resultado);
}

function obtenerOdontologos()
{

    $resultado = (new usuario())->obtenerOdontologos();
    echo json_encode($resultado);
}

function modificar_imagen_usuario()
{
    $usuario = $_POST['usuario'];
    $img = $_FILES['img'];

    // Verifica que no haya errores en la subida
    if ($img['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($img['name'], PATHINFO_EXTENSION);

        $destino = __DIR__ . "/img/{$usuario}.jpg"; // O usa $ext si quieres mantener la extensión original

        // Mueve el archivo subido a la carpeta deseada
        if (move_uploaded_file($img['tmp_name'], $destino)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'No se pudo guardar la imagen']);
        }
    }
}
function agregar()
{
    // Obtener datos del formulario
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $contraseña = $_POST['contraseña'];
    $nombre_usuario = $_POST['nombre_usuario'];
    $tipo = $_POST['tipo'];

    // Crear una instancia de la clase Usuario y llamar al método agregarUsuario
    $usuario = new Usuario();
    $resultado = $usuario->agregarUsuario($nombre, $apellido, $nombre_usuario, $tipo, $contraseña);

    // Devolver el resultado en formato JSON
    echo json_encode($resultado);
}
