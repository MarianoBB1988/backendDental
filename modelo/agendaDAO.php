<?php
require_once  '../conexion/conexion.php';
require_once  'Respuesta/respuesta.php';

class agenda
{

    function obtenerAgenda($fecha)
    {
        $connection = connection();
        $sql = "SELECT agenda.*, paciente.nombre, paciente.apellido, paciente.ci FROM agenda INNER JOIN paciente on paciente.id=agenda.id_paciente WHERE agenda.fecha='$fecha' ORDER BY hora ASC";
        $respuesta = $connection->query($sql);
        $resultado = $respuesta->fetch_all(MYSQLI_ASSOC);
        return $resultado;
    }

    public function agendarDAO($idPaciente, $hora, $fecha, $motivo){
        $sql = "INSERT INTO agenda(id, fecha, hora, motivo, id_paciente, estado) VALUES (0, '$fecha', '$hora', '$motivo', '$idPaciente', 0)";
        $connection = connection();
        $respuesta = $connection->query($sql);
        if ($respuesta){
            return new Respuesta(true, "Paciente agendado", $respuesta);
        }else{
            return new Respuesta(false, "Error al agendar al paciente", $respuesta);
        }
        
    }

    public function eliminarAgendaDAO($id){
        $sql = "DELETE FROM agenda WHERE id = '$id'";
        $connection = connection();
        $respuesta = $connection->query($sql);
        if ($respuesta){
            return new Respuesta(true, "Agenda eliminado", $respuesta);
        }else{
            return new Respuesta(false, "Error al eliminar la agenda", $respuesta);
        }
    }

    function modificarDAO($id,$idPaciente, $hora, $fecha, $motivo)
    {
        try{
            $connection = connection();
            $sql = "UPDATE agenda SET id_paciente=$idPaciente, hora= '$hora', fecha='$fecha', motivo='$motivo' WHERE id=$id";
            $respuesta = $connection->query($sql);

            if ($respuesta){
                return new Respuesta(true, "agenda modificada", $respuesta);
            }else{
                return new Respuesta(false, "Error al modificar agenda", $respuesta);
            }
        }catch(Exception $e){   
            return $e->getMessage();
        }
       
    }

    function cambiarEstado($id,$estado)
    {
        try{
            $connection = connection();
            $sql = "UPDATE agenda SET estado=$estado WHERE id=$id";
            $respuesta = $connection->query($sql);
           // $resultado = $respuesta->fetch_all(MYSQLI_ASSOC);
            if ($respuesta){
                return new Respuesta(true, "estado modificado", $respuesta);
            }else{
                return new Respuesta(false, "Error al cambiar el estado", $respuesta);
            }
        }catch(Exception $e){   
            return $e->getMessage();
        }
       
    }
}
