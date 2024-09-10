<?php

require_once  '../conexion/conexion.php';
require_once  'Respuesta/respuesta.php';

class Cuenta {
  

    // Método para agregar una cuenta
    public function agregarCuentaDAO($idProcedimiento, $unidad, $costo, $estado, $fecha) {
        $connection = connection();
        $sql = "INSERT INTO cuenta (id_procedimiento, estado, unidad, costo, fecha) VALUES (?, ?, ?, ?, ?)";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param('issds', $idProcedimiento, $estado, $unidad, $costo, $fecha);
        
        if ($stmt->execute()) {
            return new Respuesta(true, "Cuenta agregada", $stmt->insert_id);
        } else {
            return new Respuesta(false, "Error al agregar la cuenta: " . $stmt->error, null);
        }
    }

    // Método para modificar una cuenta existente
    public function modificarCuentaDAO($idProcedimiento, $unidad, $costo, $estado, $fecha) {
        $connection = connection();
        $sql = "UPDATE cuenta SET estado=?, unidad=?, costo=?, fecha=? WHERE id_procedimiento=?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param('ssdsi', $estado, $unidad, $costo, $fecha, $idProcedimiento);
        
        if ($stmt->execute()) {
            return new Respuesta(true, "Cuenta modificada", $stmt->affected_rows);
        } else {
            return new Respuesta(false, "Error al modificar la cuenta: " . $stmt->error, null);
        }
    }

    // Método para modificar solo el estado de una cuenta
    public function modificarEstadoDAO($id, $estado) {
        $connection = connection();
        $sql = "UPDATE cuenta SET estado=? WHERE id=?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param('si', $estado, $id);
        
        if ($stmt->execute()) {
            return new Respuesta(true, "Estado modificado", $stmt->affected_rows);
        } else {
            return new Respuesta(false, "Error al cambiar el estado: " . $stmt->error, null);
        }
    }
}

?>