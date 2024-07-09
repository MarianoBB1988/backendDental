<?php

require_once  '../conexion/conexion.php';
require_once  'Respuesta/respuesta.php';

class cuenta
{

    public function agregarCuentaDAO($idProcedimiento, $unidad, $costo, $estado, $fecha)
    {
       
        $connection = connection();
        $sql = "INSERT INTO cuenta VALUES (0, $idProcedimiento, '$estado', '$unidad', '$costo', '$fecha')";
        $respuesta = $connection->query($sql);
        if ($respuesta) {
            return new Respuesta(true, "Cuenta agregado", $respuesta);
        } else {
            return new Respuesta(false, "Error al agregar la cuenta", $respuesta);
        }
    }

    public function modificarCuentaDAO($idProcedimiento, $unidad, $costo, $estado, $fecha)
    {
       
        $connection = connection();
        $sql = "UPDATE cuenta SET estado='$estado', unidad='$unidad', costo='$costo', fecha='$fecha' WHERE id_procedimiento=$idProcedimiento";
        $respuesta = $connection->query($sql);
       
         if ($respuesta) {
            return new Respuesta(true, "Cuenta modficada", $respuesta);
        } else {
            return new Respuesta(false, "Error al modificar la cuenta", $respuesta);
        } 
    }

    public function modificarEstadoDAO($id, $estado)
    {
        $sql = "UPDATE cuenta SET estado='$estado' WHERE id=$id";
        $connection = connection();
        $respuesta = $connection->query($sql);
        if ($respuesta){
            return new Respuesta(true, "estado modificado", $respuesta);
        }else{
            return new Respuesta(false, "Error al cambiar el estado", $respuesta);
        }
    }

    
}

?>