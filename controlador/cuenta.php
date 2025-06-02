<?php
// Permitir solicitudes solo desde el origen específico
header("Access-Control-Allow-Origin: http://localhost:5173");

// Permitir el envío de cookies desde un origen diferente
header("Access-Control-Allow-Credentials: true");
// Permitir los métodos HTTP especificados (GET, POST, etc.)
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
// Permitir los encabezados HTTP especificados
header("Access-Control-Allow-Headers: Content-Type, Authorization");

require_once '../modelo/cuentaDAO.php';
session_start();
$funcion = $_GET['funcion'];
if ($_SESSION['sesion']) {
  switch ($funcion) {
    case "agregar":
    
      break;
    case "eliminar":
    
      break;
    case "modificar":
    
      break;
    case "obtener":
     
      break;

   case "cambiarEstado":
    cambiarestado();
   break;
   case "obtenerImpagos":
    obtenerImpagos();
    break;
  }
}

function cambiarestado()
{
  $id = $_POST['id'];
  $estado = $_POST['estado'];
  $resultado = (new cuenta())->modificarEstadoDAO($id,$estado);
  echo json_encode($resultado);
}

function obtenerImpagos()
{

  $resultado = (new cuenta())->obtenerImpagos();
  echo json_encode($resultado);
}
