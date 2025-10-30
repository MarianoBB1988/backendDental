<?php

require_once '../conexion/con_clientes.php';
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


    function listar_precios(){
         $connection = connection();
        $sql = "SELECT * FROM precios_procedimiento ORDER BY nombre;";
        $stmt = $connection->prepare($sql);

        $stmt->execute();
        $respuesta = $stmt->get_result();
        $resultado = $respuesta->fetch_all(MYSQLI_ASSOC);
        return $resultado;
    
    }

    function obtenerAdjuntos($orden, $columna)
    {
        $connection = connection();
        $sql = "SELECT paciente.nombre as nomPaciente, procedimiento.adjunto as extension, paciente.id as idPaciente, paciente.fecha as fechaNacimiento, paciente.apellido as apellido, paciente.ci as ci, procedimiento.id, procedimiento.pieza,
            procedimiento.sector, procedimiento.nombre, procedimiento.fecha, procedimiento.descripcion, procedimiento.patologia,
            procedimiento.medicacion, procedimiento.estado, usuario.nombre as nomUsuario, usuario.apellido as apeUsuario
            FROM procedimiento
            RIGHT JOIN paciente ON procedimiento.id_paciente = paciente.id
            INNER JOIN usuario ON usuario.nombre_usuario = procedimiento.usuario
            WHERE procedimiento.adjunto NOT IN ('dcm', 'zip') AND procedimiento.adjunto IS NOT NULL AND LENGTH(procedimiento.adjunto) > 0
            ORDER BY $columna $orden;
            ";

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

    function obtenerRX()
    {
        $connection = connection();
        $sql = "SELECT paciente.nombre as nomPaciente, procedimiento.adjunto as extension, paciente.id as idPaciente, paciente.fecha as fechaNacimiento, paciente.apellido as apellido, paciente.ci as ci, procedimiento.id, procedimiento.pieza, procedimiento.sector, procedimiento.nombre, procedimiento.fecha, procedimiento.descripcion, procedimiento.patologia, procedimiento.medicacion, procedimiento.estado, usuario.nombre as nomUsuario, usuario.apellido as apeUsuario 
                FROM procedimiento 
                RIGHT JOIN paciente ON procedimiento.id_paciente = paciente.id 
                INNER JOIN usuario ON usuario.nombre_usuario = procedimiento.usuario 
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
                INNER JOIN usuario ON usuario.nombre_usuario = procedimiento.usuario 
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

    public function obtenerProcedimientosOrdenados($columna, $orden, $ci, $estado)
    {
        $connection = connection();
        if ($estado == 'todos') {
            $sql = "SELECT procedimiento.*, paciente.id, cuenta.estado as estadoCuenta, cuenta.costo, cuenta.unidad, cuenta.id as idCuenta 
            FROM procedimiento
            INNER JOIN paciente ON procedimiento.id_paciente = paciente.id
             INNER JOIN cuenta ON cuenta.id_procedimiento = procedimiento.id 
            WHERE paciente.ci = ?
            ORDER BY $columna $orden";
        } else {
            $sql = "SELECT procedimiento.*, paciente.id, cuenta.estado as estadoCuenta, cuenta.costo, cuenta.unidad, cuenta.id as idCuenta 
            FROM procedimiento
            INNER JOIN paciente ON procedimiento.id_paciente = paciente.id
             INNER JOIN cuenta ON cuenta.id_procedimiento = procedimiento.id 
            WHERE paciente.ci = ? and cuenta.estado='$estado'
            ORDER BY $columna $orden";
        }

        $stmt = $connection->prepare($sql);
        $stmt->bind_param('i', $ci);
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

    public function mayorImplementacion()
    {
        $connection = connection();
        $sql = "SELECT * FROM vistaprocedimientosmasimplementados";
        $stmt = $connection->prepare($sql);

        $stmt->execute();
        $respuesta = $stmt->get_result();
        $resultado = $respuesta->fetch_all(MYSQLI_ASSOC);
        return $resultado;
    }

  public function agregarProcedimientoDAO($nombre, $descripcion, $pieza, $sector, $idPaciente, $fecha, $estado, $medicacion, $patologia, $adjunto, $usuario)
{
    $connection = connection();
    $nomAdjunto = $adjunto['name'];
    $extension = strtolower(pathinfo($nomAdjunto, PATHINFO_EXTENSION));

    // Determinar valor para la base de datos
    if ($extension == 'zip' || $extension == 'dcm') {
        $extensionDB = 'dcm';
    } else {
        $extensionDB = '.' . $extension;
    }

    $sql = "INSERT INTO procedimiento (pieza, sector, nombre, id_paciente, fecha, descripcion, estado, medicacion, patologia, adjunto, usuario) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param('sssisssssss', $pieza, $sector, $nombre, $idPaciente, $fecha, $descripcion, $estado, $medicacion, $patologia, $extensionDB, $usuario);

    session_start();
    $pacsCarpeta = "./pacs/" . $_SESSION['sesion']['bd'];

    if (!is_dir($pacsCarpeta)) mkdir($pacsCarpeta, 0777, true);

    if ($stmt->execute()) {
        $idProc = $connection->insert_id;
        $rutaTemp = $adjunto['tmp_name'];
        $procCarpeta = $pacsCarpeta . "/" . $idProc;

        if (!is_dir($procCarpeta)) mkdir($procCarpeta, 0777, true);

        if ($extension == 'dcm') {
            move_uploaded_file($rutaTemp, "$procCarpeta/$idProc.dcm");
        } elseif ($extension == 'zip') {
            $rutaZip = "$procCarpeta/$idProc.zip";
            move_uploaded_file($rutaTemp, $rutaZip);

            $zip = new ZipArchive;
            if ($zip->open($rutaZip) === TRUE) {
                // Extraemos a la carpeta del procedimiento
                $zip->extractTo($procCarpeta);
                $zip->close();
                unlink($rutaZip);

                // Detectar si hay subcarpetas
                $contenido = glob($procCarpeta . '/*');
                foreach ($contenido as $item) {
                    if (is_dir($item)) {
                        // Mantener subcarpetas tal cual
                        continue;
                    } elseif (is_file($item)) {
                        // Archivos .dcm directamente en el root del procedimiento
                        // Ya están en el lugar correcto, no mover nada
                    }
                }
            } else {
                echo json_encode(['error' => 'Error al abrir el archivo .zip']);
            }
        } else {
            move_uploaded_file($rutaTemp, $procCarpeta . "/" . $idProc . "." . $extension);
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
