<?php

require_once  '../conexion/con_clientes.php';
require_once  'Respuesta/respuesta.php';

class paciente
{

    public function obtenerPaciente($ci)
    {
        $connection = connection();
        $sql = "SELECT * FROM paciente WHERE ci = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param('i', $ci); // 's' significa que $ci es un string
        $stmt->execute();
        $respuesta = $stmt->get_result();
        $resultado = $respuesta->fetch_all(MYSQLI_ASSOC);
        return $resultado;
    }

    
    public function mayorAtencion()
    {
        $connection = connection();
        $sql = "SELECT * FROM vistapacientesmayoratencion";
        $stmt = $connection->prepare($sql);
       
        $stmt->execute();
        $respuesta = $stmt->get_result();
        $resultado = $respuesta->fetch_all(MYSQLI_ASSOC);
        return $resultado;
    }


    public function obtenerPacientes()
    {
        $connection = connection();
        $sql = "SELECT * FROM paciente ORDER BY id DESC";
        $respuesta = $connection->query($sql);
        $resultado = $respuesta->fetch_all(MYSQLI_ASSOC);
        return $resultado;
    }

    public function obtenerPacientesOrdenados($columna, $orden)
    {
        $connection = connection();
        $sql = "SELECT * FROM paciente ORDER BY $columna $orden";
        // Nota: Las columnas y el orden deberían ser sanitizados o validados antes de usarse directamente
        $respuesta = $connection->query($sql);
        $resultado = $respuesta->fetch_all(MYSQLI_ASSOC);
        return $resultado;
    }

    public function agregarPacienteDAO($nombre, $apellido, $ci, $telefono, $email, $direccion, $genero, $fecha, $observaciones, $extension)
    {
        $sql = "INSERT INTO paciente (nombre, apellido, ci, telefono, email, fecha, genero, direccion, observaciones, extension) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $connection = connection();
        $stmt = $connection->prepare($sql);
        $stmt->bind_param('ssssssssss', $nombre, $apellido, $ci, $telefono, $email, $fecha, $genero, $direccion, $observaciones, $extension);

        if ($stmt->execute()) {
            return new Respuesta(true, "Paciente agregado", $stmt->insert_id);
        } else {
            return new Respuesta(false, "Error al agregar el paciente: " . $stmt->error, null);
        }
    }

    function obtenerIdDAO($ci)
    {
        $connection = connection();
        $sql = "SELECT id FROM paciente WHERE ci = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param('s', $ci);
        $stmt->execute();
        $respuesta = $stmt->get_result();
        $id = $respuesta->fetch_all(MYSQLI_ASSOC);

        return  $id;
    }

    public function modificarPacienteDAO($id, $nombre, $apellido, $ci, $telefono, $email, $direccion, $genero, $fecha, $observaciones, $extension)
    {
        $sql = "UPDATE paciente SET nombre = ?, apellido = ?, ci = ?, telefono = ?, email = ?, direccion = ?, genero = ?, fecha = ?, observaciones = ?, extension = ? WHERE id = ?";
        $connection = connection();
        $stmt = $connection->prepare($sql);
        $stmt->bind_param('ssssssssssi', $nombre, $apellido, $ci, $telefono, $email, $direccion, $genero, $fecha, $observaciones, $extension, $id);

        if ($stmt->execute()) {
            return new Respuesta(true, "Paciente modificado", $stmt->affected_rows);
        } else {
            return new Respuesta(false, "Error al modificar el paciente: " . $stmt->error, null);
        }
    }

    public function modificarExtensionDAO($ci, $extension)
    {
        $sql = "UPDATE paciente SET extension = ? WHERE ci = ?";
        $connection = connection();
        $stmt = $connection->prepare($sql);
        $stmt->bind_param('ss', $extension, $ci);

        if ($stmt->execute()) {
            return new Respuesta(true, "Extensión modificada", $stmt->affected_rows);
        } else {
            return new Respuesta(false, "Error al modificar la extensión: " . $stmt->error, null);
        }
    }

    public function eliminarPacienteDAO($id)
    {
        $sql = "DELETE FROM paciente WHERE id = ?";
        $connection = connection();
        $stmt = $connection->prepare($sql);
        $stmt->bind_param('i', $id);

        if ($stmt->execute()) {
            return new Respuesta(true, "Paciente eliminado", $stmt->affected_rows);
        } else {
            return new Respuesta(false, "Error al eliminar el paciente: " . $stmt->error, null);
        }
    }
}
