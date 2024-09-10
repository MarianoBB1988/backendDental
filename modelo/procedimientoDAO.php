<?php

require_once '../conexion/conexion.php';
require_once 'Respuesta/respuesta.php';

class procedimiento
{
    public $idProc = 0;

    function obtenerProcedimientoDAO($ci)
    {
        $connection = connection();
        $sql = "SELECT paciente.nombre as nomPaciente, procedimiento.adjunto as extension, paciente.id as idPaciente, paciente.fecha as fechaNacimiento, paciente.apellido as apellido, paciente.ci as ci, procedimiento.id, procedimiento.pieza, procedimiento.sector, procedimiento.nombre, procedimiento.fecha, procedimiento.descripcion, procedimiento.patologia, procedimiento.medicacion, procedimiento.estado, cuenta.estado as estadoCuenta, cuenta.costo, cuenta.unidad, cuenta.id as idCuenta 
                FROM procedimiento 
                RIGHT JOIN paciente ON procedimiento.id_paciente = paciente.id 
                INNER JOIN cuenta ON cuenta.id_procedimiento = procedimiento.id 
                WHERE paciente.ci = ? 
                ORDER BY procedimiento.fecha ASC";

        $stmt = $connection->prepare($sql);
        $stmt->bind_param('s', $ci);
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

    function obtenerRX()
    {
        $connection = connection();
        $sql = "SELECT paciente.nombre as nomPaciente, procedimiento.adjunto as extension, paciente.id as idPaciente, paciente.fecha as fechaNacimiento, paciente.apellido as apellido, paciente.ci as ci, procedimiento.id, procedimiento.pieza, procedimiento.sector, procedimiento.nombre, procedimiento.fecha, procedimiento.descripcion, procedimiento.patologia, procedimiento.medicacion, procedimiento.estado, usuario.nombre as nomUsuario, usuario.apellido as apeUsuario 
                FROM procedimiento 
                RIGHT JOIN paciente ON procedimiento.id_paciente = paciente.id 
                INNER JOIN usuario ON usuario.id = procedimiento.id_usuario 
                WHERE procedimiento.adjunto = 'dcm' 
                ORDER BY procedimiento.fecha ASC";

        $stmt = $connection->prepare($sql);
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


    function obtenerRXordenados($orden, $columna)
    {
        $connection = connection();
        $sql = "SELECT paciente.nombre as nomPaciente, procedimiento.adjunto as extension, paciente.id as idPaciente, paciente.fecha as fechaNacimiento, paciente.apellido as apellido, paciente.ci as ci, procedimiento.id, procedimiento.pieza, procedimiento.sector, procedimiento.nombre, procedimiento.fecha, procedimiento.descripcion, procedimiento.patologia, procedimiento.medicacion, procedimiento.estado, usuario.nombre as nomUsuario, usuario.apellido as apeUsuario 
                FROM procedimiento 
                RIGHT JOIN paciente ON procedimiento.id_paciente = paciente.id 
                INNER JOIN usuario ON usuario.id = procedimiento.id_usuario 
                WHERE procedimiento.adjunto = 'dcm' 
                ORDER BY $columna $orden;";
        $stmt = $connection->prepare($sql);
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

    public function obtenerProcedimientosOrdenados($columna, $orden, $idPaciente)
    {
        $connection = connection();
        $sql = "SELECT * FROM procedimiento WHERE id_paciente = ? ORDER BY $columna $orden";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param('i', $idPaciente);
        $stmt->execute();
        $respuesta = $stmt->get_result();
        $resultado = $respuesta->fetch_all(MYSQLI_ASSOC);
        return $resultado;
    }

    public function obtenerPaciente($ci)
    {
        $connection = connection();
        $sql = "SELECT id FROM paciente WHERE ci = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param('s', $ci);
        $stmt->execute();
        $respuesta = $stmt->get_result();
        $resultado = $respuesta->fetch_all(MYSQLI_ASSOC);
        return $resultado[0]['id'] ?? null;
    }

    public function agregarProcedimientoDAO($nombre, $descripcion, $pieza, $sector, $idPaciente, $fecha, $estado, $medicacion, $patologia, $adjunto)
    {
        $connection = connection();
        $nomImg = $adjunto['name'];
        $extension = pathinfo($nomImg, PATHINFO_EXTENSION);
        $sql = "INSERT INTO procedimiento (pieza, sector, nombre, id_paciente, fecha, descripcion, estado, medicacion, patologia, adjunto) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $connection->prepare($sql);
        $stmt->bind_param('ssssssssss', $pieza, $sector, $nombre, $idPaciente, $fecha, $descripcion, $estado, $medicacion, $patologia, $extension);

        if ($stmt->execute()) {
            $idProc = $connection->insert_id;
            $rutaTemp = $adjunto['tmp_name'];
            if ($extension == 'dcm') {
                move_uploaded_file($rutaTemp, "./pacs/$idProc.$extension");
            } else {
                move_uploaded_file($rutaTemp, "./adjuntos/$idProc.$extension");
            }
            return $idProc;
        } else {
            return 0;
        }
    }

    public function modificarProcedimientoDAO($id, $nombre, $descripcion, $pieza, $sector, $idPaciente, $fecha, $estado, $medicacion, $patologia, $adjunto)
    {
        $connection = connection();
        $nomImg = $adjunto['name'] ?? '';
        $extension = pathinfo($nomImg, PATHINFO_EXTENSION);

        if ($adjunto) {
            $sql = "UPDATE procedimiento SET patologia = ?, nombre = ?, adjunto = ?, pieza = ?, sector = ?, estado = ?, medicacion = ?, descripcion = ?, fecha = ? WHERE id = ?";
            $stmt = $connection->prepare($sql);
            $stmt->bind_param('sssssssssi', $patologia, $nombre, $extension, $pieza, $sector, $estado, $medicacion, $descripcion, $fecha, $id);
        } else {
            $sql = "UPDATE procedimiento SET patologia = ?, nombre = ?, pieza = ?, sector = ?, estado = ?, medicacion = ?, descripcion = ?, fecha = ? WHERE id = ?";
            $stmt = $connection->prepare($sql);
            $stmt->bind_param('ssssssssi', $patologia, $nombre, $pieza, $sector, $estado, $medicacion, $descripcion, $fecha, $id);
        }

        $respuesta = $stmt->execute();

        if ($adjunto) {
            $rutaTemp = $adjunto['tmp_name'];
            if ($extension == 'dcm') {
                move_uploaded_file($rutaTemp, "./pacs/$id.$extension");
            } else {
                move_uploaded_file($rutaTemp, "./adjuntos/$id.$extension");
            }
        }

        return $respuesta;
    }

    public function eliminarProcedimientoDAO($id)
    {
        $connection = connection();
        $sql = "DELETE FROM procedimiento WHERE id = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param('i', $id);
        $respuesta = $stmt->execute();

        if ($respuesta) {
            return new Respuesta(true, "Procedimiento eliminado", $respuesta);
        } else {
            return new Respuesta(false, "Error al eliminar el procedimiento", $respuesta);
        }
    }
}
