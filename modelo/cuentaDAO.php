<?php

require_once  '../conexion/con_clientes.php';
require_once  'Respuesta/respuesta.php';

class Cuenta
{


    // Método para agregar una cuenta
    public function agregarCuentaDAO($idProcedimiento, $unidad, $costo, $estado, $fecha)
    {
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
    public function modificarCuentaDAO($idProcedimiento, $unidad, $costo, $estado, $fecha)
    {
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
    public function modificarEstadoDAO($id, $estado)
    {
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

    function obtenerImpagos()
    {
        $connection = connection();
        $sql = "SELECT paciente.nombre as nomPaciente, procedimiento.adjunto as extension, paciente.id as idPaciente, paciente.fecha as fechaNacimiento, paciente.apellido as apellido, paciente.ci as ci, procedimiento.id, procedimiento.pieza, procedimiento.sector, procedimiento.nombre, procedimiento.fecha, procedimiento.descripcion, procedimiento.patologia, procedimiento.medicacion, procedimiento.estado, cuenta.estado as estadoCuenta, cuenta.costo, cuenta.unidad, cuenta.id as idCuenta 
            FROM procedimiento 
            RIGHT JOIN paciente ON procedimiento.id_paciente = paciente.id 
            INNER JOIN cuenta ON cuenta.id_procedimiento = procedimiento.id 
            WHERE cuenta.estado='Impago' 
            ORDER BY procedimiento.fecha ASC";

        $stmt = $connection->prepare($sql);
        // $stmt->bind_param('s', $ci);
        $stmt->execute();
        $respuesta = $stmt->get_result();
        $procedimientos = $respuesta->fetch_all(MYSQLI_ASSOC);

        foreach ($procedimientos as &$fila) {
            foreach ($fila as $clave => &$valor) {
                if ($valor === null) {
                    $valor = ""; // Suplanta null por una cadena vacía
                }
            }
        }

        return $procedimientos;
    }
}
