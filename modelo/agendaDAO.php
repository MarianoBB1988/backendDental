<?php
require_once  '../conexion/con_clientes.php';
require_once  'Respuesta/respuesta.php';

class agenda
{


    
    function obtenerAgendaPorUsuario($fecha, $id_odontologo) {
        $connection = connection();
        $sql = "SELECT agenda.*, paciente.nombre, paciente.apellido, paciente.ci, paciente.telefono, paciente.extension FROM agenda INNER JOIN paciente ON paciente.id = agenda.id_paciente WHERE agenda.fecha = ? AND agenda.id_odontologo = ? ORDER BY hora ASC;";
        
        $stmt = $connection->prepare($sql);
        $stmt->bind_param('si', $fecha, $id_odontologo);
       $stmt->execute();
   $resultado = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        return $resultado;
    }
    

  
    public function obtenerAgenda($fecha) {
        $connection = connection();
        $sql = "SELECT agenda.*, usuario.nombre as nombreUsuario, usuario.apellido as apellidoUsuario, 
                paciente.nombre, paciente.apellido, paciente.ci, paciente.telefono, paciente.extension 
                FROM agenda 
                INNER JOIN paciente on paciente.id=agenda.id_paciente 
                RIGHT JOIN usuario on usuario.nombre_usuario=agenda.usuario 
                WHERE agenda.fecha = ? 
                ORDER BY hora ASC";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param('s', $fecha);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        return $resultado;
    }
    
      public function obtenerAgendas($fecha) {
        $connection = connection();
        $sql = "SELECT agenda.*, paciente.nombre, paciente.apellido, paciente.ci, paciente.telefono, paciente.extension FROM agenda INNER JOIN paciente ON paciente.id = agenda.id_paciente WHERE agenda.fecha = ? ORDER BY hora ASC;";
        
        $stmt = $connection->prepare($sql);
        $stmt->bind_param('s', $fecha);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        return $resultado;
    }

    public function agendarDAO($idPaciente, $hora, $fecha, $duracion,$motivo, $usuario) {
        $connection = connection(); // Asegúrate de que la función connection() regresa una conexión válida
        $sql = "INSERT INTO agenda (fecha, hora, duracion, motivo, id_paciente, estado, id_odontologo) 
                VALUES (?, ?, ?, ?, ?, 0, ?)";
        
        $stmt = $connection->prepare($sql);

        $stmt->bind_param('ssisii', $fecha, $hora, $duracion, $motivo, $idPaciente, $usuario);

        if ($stmt->execute()) {
            return new Respuesta(true, "Paciente agendado", $stmt->insert_id);
        } else {
            return new Respuesta(false, "Error al agendar al paciente: " . $stmt->error, null);
        }
    }
    

    public function eliminarAgendaDAO($idAgenda) {
        $connection = connection();
        $sql = "DELETE FROM agenda WHERE id = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param('i', $idAgenda);
        
        if ($stmt->execute()) {
            return new Respuesta(true, "Agenda eliminada", 'true');
        } else {
            return new Respuesta(false, "Error al eliminar la agenda: ", $stmt->error);
        }
    }

    function modificarDAO($id, $idPaciente, $hora, $fecha,$duracion, $motivo) {
        try {
            $connection = connection();
            $sql = "UPDATE agenda SET id_paciente = ?, hora = ?, fecha = ?, duracion = ?, motivo = ? WHERE id = ?";
            $stmt = $connection->prepare($sql);
            $stmt->bind_param('issssi', $idPaciente, $hora, $fecha, $duracion, $motivo, $id);
            
            if ($stmt->execute()) {
                return new Respuesta(true, "Agenda modificada", $stmt->affected_rows);
            } else {
                return new Respuesta(false, "Error al modificar agenda", $stmt->error);
            }
        } catch (Exception $e) {   
            return $e->getMessage();
        }
    }
    

    function cambiarEstado($id, $estado) {
        try {
            $connection = connection();
            $sql = "UPDATE agenda SET estado = ? WHERE id = ?";
            $stmt = $connection->prepare($sql);
            $stmt->bind_param('ii', $estado, $id);
            
            if ($stmt->execute()) {
                return new Respuesta(true, "Estado modificado", $stmt->affected_rows);
            } else {
                return new Respuesta(false, "Error al cambiar el estado", $stmt->error);
            }
        } catch (Exception $e) {   
            return $e->getMessage();
        }
    }
    
}
