<?php
// Permitir solicitudes solo desde el origen específico
header("Access-Control-Allow-Origin: http://localhost:5173");

// Permitir el envío de cookies desde un origen diferente
header("Access-Control-Allow-Credentials: true");
// Permitir los métodos HTTP especificados (GET, POST, etc.)
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
// Permitir los encabezados HTTP especificados
header("Access-Control-Allow-Headers: Content-Type, Authorization");

require_once '../modelo/agendaDAO.php';
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

     case "obtenerAgenda":
        obtenerAgenda();
        break;

    case "cambiarEstado":
        cambiarEstado();
        break;
}
}




function obtener()
{
    $fecha = $_GET['fecha'];
    $id_odontologo=$_GET['id_odontologo'];
  //  $tipo=$_GET['tipo'];
    $resultado = (new agenda())->obtenerAgendaPorUsuario($fecha, $id_odontologo);
   
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

function obtenerAgenda(){
     $fecha = $_GET['fecha'];
      $resultado = (new agenda())->obtenerAgendas($fecha);
    echo json_encode($resultado);
}


function agregar()
{
    $idPaciente = $_POST['idPaciente'];
    $hora = $_POST['hora'];
    $fecha = $_POST['fecha'];
    $duracion = $_POST['duracion'];
    $motivo = $_POST['motivo'];
    $usuario=$_POST['usuario'];

    $resultado = (new agenda())->agendarDAO($idPaciente, $hora, $fecha, $duracion, $motivo, $usuario);
    echo json_encode($resultado);
}

function modificar()
{
    $id = $_GET['id'];
    $idPaciente = $_POST['idPaciente'];
    $hora = $_POST['hora'];
      $duracion = $_POST['duracion'];
    $fecha = $_POST['fecha'];
    $motivo = $_POST['motivo'];
    $resultado = (new agenda())->modificarDAO($id,$idPaciente, $hora, $fecha, $duracion, $motivo);
    echo json_encode($resultado);
}

function cambiarEstado()
{
    $estado = $_GET['es'];
    $id = $_GET['id'];
    $resultado = (new agenda())->cambiarEstado($id, $estado);
    echo json_encode($resultado);
}

function eliminar()
{
    $id = $_GET['id'];
    $resultado = (new agenda())->eliminarAgendaDAO($id);
    echo json_encode($resultado);
}
