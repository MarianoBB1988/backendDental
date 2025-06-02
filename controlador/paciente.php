<?php
// Permitir solicitudes solo desde el origen específico
header("Access-Control-Allow-Origin: http://localhost:5173");

// Permitir el envío de cookies desde un origen diferente
header("Access-Control-Allow-Credentials: true");
// Permitir los métodos HTTP especificados (GET, POST, etc.)
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
// Permitir los encabezados HTTP especificados
header("Access-Control-Allow-Headers: Content-Type, Authorization");

require_once '../modelo/pacienteDAO.php';
session_start();

if (isset($_SESSION['sesion'])) {
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
    case "obtenerTodos":
        obtenerTodos();
        break;
    case "obtenerOrdenados":
        obtenerOrdenados();
        break;
    case "obtenerId":
        obtenerId();
        break;
    case "subirImagen":
        subir_imagen();
        break;
    case "mayorAtencion":
        mayorAtencion();
        break;
}
}


function mayorAtencion()
{
    $resultado = (new paciente())->mayorAtencion();
    echo json_encode($resultado);
}

function obtener()
{
    $ci = $_GET['ci'];
    $resultado = (new paciente())->obtenerPaciente($ci);
    echo json_encode($resultado);
}

function obtenerTodos()
{

    $resultado = (new paciente())->obtenerPacientes();
    echo json_encode($resultado);
}

function obtenerOrdenados()
{
    $columna = $_GET['columna'];
    $orden = $_GET['orden'];
    $resultado = (new paciente())->obtenerPacientesORdenados($columna, $orden);
    echo json_encode($resultado);
}

function obtenerId()
{
    $ci = $_GET['ci'];

    $resultado = (new paciente())->obtenerIdDAO($ci);
    echo json_encode($resultado);
}

function subir_imagen()
{
    try {
        $ci = $_POST['ci'];
        // $archivo = $_FILES['imgPerfil']['name'];
        $extension = pathinfo($_FILES['imgPerfil']['name'], PATHINFO_EXTENSION);
        move_uploaded_file($_FILES['imgPerfil']['tmp_name'], './img/' . $ci . '.' . $extension);
        $resultado = (new paciente())->modificarExtensionDAO($ci, $extension);
        echo json_encode($resultado);
    } catch (Exception $e) {
        return $e->getMessage();
    }
}

function agregar()
{
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $ci = $_POST['ci'];
    $telefono = $_POST['telefono'];
    $email = $_POST['email'];
    $direccion = $_POST['direccion'];
    $genero = $_POST['genero'];
    $fecha = $_POST['fecha'];
    $observaciones = $_POST['observaciones'];
    $extension = pathinfo($_FILES['imgPerfil']['name'], PATHINFO_EXTENSION);
    move_uploaded_file($_FILES['imgPerfil']['tmp_name'], './img/' . $ci . '.' . $extension);
    $resultado = (new paciente())->agregarPacienteDAO($nombre, $apellido, $ci, $telefono, $email, $direccion, $genero, $fecha, $observaciones, $extension);
    echo json_encode($resultado);
}

function modificar()
{
    $id = $_GET['id'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $ci = $_POST['ci'];
    $telefono = $_POST['telefono'];
    $email = $_POST['email'];
    $direccion = $_POST['direccion'];
    $genero = $_POST['genero'];
    $fecha = $_POST['fecha'];
    $observaciones = $_POST['observaciones'];
    $extension = $_POST['extension'];
    if ($extension) {
        $resultado = (new paciente())->modificarPacienteDAO($id, $nombre, $apellido, $ci, $telefono, $email, $direccion, $genero, $fecha, $observaciones,  $extension);
    } else {
        $extension = pathinfo($_FILES['imgPerfil']['name'], PATHINFO_EXTENSION);
        move_uploaded_file($_FILES['imgPerfil']['tmp_name'], './img/' . $ci . '.' . $extension);
        $resultado = (new paciente())->modificarPacienteDAO($id, $nombre, $apellido, $ci, $telefono, $email, $direccion, $genero, $fecha, $observaciones,  $extension);
    }

    echo json_encode($resultado);
}



function eliminar()
{
    $id = $_GET['id'];
    $resultado = (new paciente())->eliminarPacienteDAO($id);
    echo json_encode($resultado);
}
