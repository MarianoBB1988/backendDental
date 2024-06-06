<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");

require_once '../modelo/procedimientoDAO.php';

$funcion = $_GET['funcion'];

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
}

function obtener(){
    $ci=$_GET['ci'];
    $resultado = (new procedimiento())->obtenerProcedimientoDAO($ci);
    echo json_encode($resultado);
 }

 function agregar(){
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $pieza = $_POST['pieza'];
    $sector = $_POST['sector'];
    $idPaciente = $_POST['idPaciente'];
    $fecha = $_POST['fecha'];
    $estado = $_POST['estado'];
    $medicacion = $_POST['medicacion'];
    $patologia = $_POST['patologia'];
    $resultado = (new procedimiento())->agregarProcedimientoDAO($nombre, $descripcion, $pieza, $sector, $idPaciente, $fecha, $estado, $medicacion, $patologia);
    echo json_encode($resultado);
 }

 function modificar(){
    $id=$_GET['id'];
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






?>