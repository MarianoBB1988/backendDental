<?php
// Permitir solicitudes solo desde el origen específico
header("Access-Control-Allow-Origin: http://localhost:5173");
// Permitir el envío de cookies desde un origen diferente
header("Access-Control-Allow-Credentials: true");
// Permitir los métodos HTTP especificados (GET, POST, etc.)
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
// Permitir los encabezados HTTP especificados
header("Access-Control-Allow-Headers: Content-Type, Authorization");

require_once '../modelo/procedimientoDAO.php';
session_start();
$funcion = $_GET['funcion'];
if ($_SESSION['sesion']) {
  switch ($funcion) {
    case "agregar":
      agregar();
      break;
    case "eliminar":
      eliminar();
      break;
    case "modificar":
      modificar();
      break;
    case "obtener":
      obtener();
      break;

    case "obtenerOrdenados":
      obtenerOrdenados();
      break;
  }
}

function obtenerOrdenados()
{
  $columna = $_GET['columna'];
  $orden = $_GET['orden'];
  $idPaciente = $_GET['idPaciente'];
  $resultado = (new procedimiento())->obtenerProcedimientosORdenados($columna, $orden, $idPaciente);
  echo json_encode($resultado);
}

function obtener()
{
  $ci = $_GET['ci'];
  $resultado = (new procedimiento())->obtenerProcedimientoDAO($ci);
  echo json_encode($resultado);
}

function agregar()
{
  $nombre = $_POST['nombre'];
  $descripcion = $_POST['descripcion'];
  $pieza = $_POST['pieza'];
  $sector = $_POST['sector'];
  $idPaciente = $_POST['idPaciente'];
  $fecha = $_POST['fecha'];
  $estado = $_POST['estado'];
  $medicacion = $_POST['medicacion'];
  $patologia = $_POST['patologia'];
  //$adjunto = $_POST['adjunto'];
  $adjunto = $_FILES['adjunto'];
  $resultado = (new procedimiento())->agregarProcedimientoDAO($nombre, $descripcion, $pieza, $sector, $idPaciente, $fecha, $estado, $medicacion, $patologia, $adjunto);
  
  echo json_encode($resultado);
}

function modificar()
{
  $id = $_GET['id'];
  $nombre = $_POST['nombre'];
  $descripcion = $_POST['descripcion'];
  $pieza = $_POST['pieza'];
  $sector = $_POST['sector'];
  $idPaciente = $_POST['idPaciente'];
  $fecha = $_POST['fecha'];
  $estado = $_POST['estado'];
  $medicacion = $_POST['medicacion'];
  $patologia = $_POST['patologia'];
  $resultado = (new procedimiento())->modificarProcedimientoDAO($id, $nombre, $descripcion, $pieza, $sector, $idPaciente, $fecha, $estado, $medicacion, $patologia);
  echo json_encode($resultado);
}

function eliminar()
{
  $id = $_GET['id'];
  $resultado = (new procedimiento())->eliminarProcedimientoDAO($id);
  echo json_encode($resultado);
}
