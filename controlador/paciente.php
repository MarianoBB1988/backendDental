<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");

require_once '../modelo/pacienteDAO.php';

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
function subir_imagen($nombreImg)
{
    $archivo = $_FILES['imgPerfil']['name'];
    $rutaTemproal = $_FILES['imgPerfil']['tmp_name'];
    $extension = pathinfo($_FILES['imgPerfil']['name'], PATHINFO_EXTENSION);
    move_uploaded_file($archivo['tmp_name'], "/img/" . $nombreImg . $extension);
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
    $extension = pathinfo($_FILES['imgPerfil']['name'],PATHINFO_EXTENSION);
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
    $extension = pathinfo($_FILES['imgPerfil']['name'], PATHINFO_EXTENSION);
    move_uploaded_file($_FILES['imgPerfil']['tmp_name'], './img/' . $ci . '.' . $extension);
    $resultado = (new paciente())->modificarPacienteDAO($id, $nombre, $apellido, $ci, $telefono, $email, $direccion, $genero, $fecha, $observaciones,  $extension);
    echo json_encode($resultado);
}



function eliminar()
{
    $id = $_GET['id'];
    $resultado = (new paciente())->eliminarPacienteDAO($id);
    echo json_encode($resultado);
}
