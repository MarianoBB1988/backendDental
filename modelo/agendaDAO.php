<?php
require_once  '../conexion/conexion.php';
require_once  'Respuesta/respuesta.php';

class agenda
{


    
    function obtenerAgendaPorUsuario($fecha, $usuario) {
        $connection = connection();
        $sql = "SELECT agenda.*, usuario.nombre as nombreUsuario, usuario.apellido as apellidoUsuario, 
                paciente.nombre, paciente.apellido, paciente.ci, paciente.telefono 
                FROM agenda 
                INNER JOIN paciente ON paciente.id = agenda.id_paciente 
                RIGHT JOIN usuario ON usuario.nombre_usuario = agenda.usuario 
                WHERE agenda.fecha = ? AND agenda.usuario = ? 
                ORDER BY hora ASC";
        
        $stmt = $connection->prepare($sql);
        $stmt->bind_param('ss', $fecha, $usuario);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        return $resultado;
    }
    

  
    public function obtenerAgenda($fecha) {
        $connection = connection();
        $sql = "SELECT agenda.*, usuario.nombre as nombreUsuario, usuario.apellido as apellidoUsuario, 
                paciente.nombre, paciente.apellido, paciente.ci, paciente.telefono 
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

    public function agendarDAO($idPaciente, $hora, $fecha, $motivo, $usuario) {
        $connection = connection(); // Asegúrate de que la función connection() regresa una conexión válida
        $sql = "INSERT INTO agenda (fecha, hora, motivo, id_paciente, estado, usuario) 
                VALUES (?, ?, ?, ?, 0, ?)";
        
        $stmt = $connection->prepare($sql);
        
        $stmt->bind_param('sssis', $fecha, $hora, $motivo, $idPaciente, $usuario);
    
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

    function modificarDAO($id, $idPaciente, $hora, $fecha, $motivo) {
        try {
            $connection = connection();
            $sql = "UPDATE agenda SET id_paciente = ?, hora = ?, fecha = ?, motivo = ? WHERE id = ?";
            $stmt = $connection->prepare($sql);
            $stmt->bind_param('isssi', $idPaciente, $hora, $fecha, $motivo, $id);
            
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
