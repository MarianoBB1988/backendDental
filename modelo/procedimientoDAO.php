<?php

require_once  '../conexion/conexion.php';
require_once  'pacienteDAO.php';
require_once  'Respuesta/respuesta.php';

class procedimiento {

    function obtenerProcedimientoDAO($ci){
        $connection = connection();
      try{
        $sql = "SELECT paciente.nombre as nomPaciente, paciente.id as idPaciente, paciente.fecha as fechaNacimiento, paciente.apellido, procedimiento.id, procedimiento.pieza, procedimiento.sector, procedimiento.nombre, procedimiento.fecha, procedimiento.descripcion, procedimiento.patologia, procedimiento.medicacion, procedimiento.estado FROM procedimiento INNER JOIN paciente on procedimiento.id_paciente = paciente.id where paciente.ci=$ci ORDER BY procedimiento.fecha ASC";
        $respuesta = $connection->query($sql);
        $procedimientos = $respuesta->fetch_all(MYSQLI_ASSOC);
        return  $procedimientos;
      }catch (Exception $e){
        return new Respuesta(false, "No se pudo realizar la consulta -",  $e->getMessage());
      
      }
       
    }

    public function obtenerProcedimientosOrdenados($columna, $orden, $idPaciente)
    {
        $connection = connection();
        $sql = "SELECT * FROM procedimiento WHERE id_paciente=$idPaciente ORDER BY $columna $orden";
        $respuesta = $connection->query($sql);
        $resultado = $respuesta->fetch_all(MYSQLI_ASSOC);
        return $resultado;
    }

    public function obtenerPaciente($ci){
        $connection = connection();
        $sql = "SELECT paciente.id FROM paciente where ci=$ci";
        $respuesta = $connection->query($sql);
        $resultado = $respuesta->fetch_all(MYSQLI_ASSOC);
        foreach ($resultado as &$idPaciente) {
            return $idPaciente['id'];
         }
    }

    public function agregarProcedimientoDAO($nombre, $descripcion, $pieza, $sector, $idPaciente, $fecha,  $estado, $medicacion, $patologia){
        $sql = "INSERT INTO procedimiento(pieza, sector, nombre, id_paciente, fecha, descripcion, estado, medicacion, patologia) VALUES ('$pieza', '$sector', '$nombre', '$idPaciente', '$fecha', '$descripcion', '$estado', '$medicacion', '$patologia')";
        $connection = connection();
        $respuesta = $connection->query($sql);
        if ($respuesta){
            return new Respuesta(true, "Procedimiento agregado", $respuesta);
        }else{
            return new Respuesta(false, "Error al agregar el procedimiento", $respuesta);
        }
        
    }

    
    public function modificarProcedimientoDAO($id, $nombre, $descripcion, $pieza, $sector, $idPaciente, $fecha,  $estado, $medicacion, $patologia){
       $sql="UPDATE procedimiento SET patologia='$patologia',nombre='$nombre', estado='$estado', medicacion='$medicacion', descripcion='$descripcion', fecha='$fecha' WHERE id=$id";
        $connection = connection();
        $respuesta = $connection->query($sql);
        return $respuesta;
        
    }

    public function eliminarProcedimientoDAO($id){
        $sql = "DELETE FROM procedimiento WHERE id = '$id'";
        $connection = connection();
        $respuesta = $connection->query($sql);
        if ($respuesta){
            return new Respuesta(true, "Procedimiento eliminado", $respuesta);
        }else{
            return new Respuesta(false, "Error al eliminar el procedimiento", $respuesta);
        }
    }


}

?>