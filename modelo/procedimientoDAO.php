<?php

require_once  '../conexion/conexion.php';
require_once  'pacienteDAO.php';
require_once  'Respuesta/respuesta.php';

class procedimiento
{

    public $idProc = 0;

  

    function obtenerProcedimientoDAO($ci)
    {
        $connection = connection();
        $sql = "SELECT paciente.nombre as nomPaciente, procedimiento.adjunto as extension, paciente.id as idPaciente, paciente.fecha as fechaNacimiento, paciente.apellido as apellido, paciente.ci as ci, procedimiento.id, procedimiento.pieza, procedimiento.sector, procedimiento.nombre, procedimiento.fecha, procedimiento.descripcion, procedimiento.patologia, procedimiento.medicacion, procedimiento.estado, cuenta.estado as estadoCuenta, cuenta.costo, cuenta.unidad, cuenta.id as idCuenta FROM procedimiento RIGHT JOIN paciente on procedimiento.id_paciente = paciente.id INNER JOIN cuenta on cuenta.id_procedimiento= procedimiento.id where paciente.ci=$ci ORDER BY procedimiento.fecha ASC";
        $respuesta = $connection->query($sql);
        $procedimientos = $respuesta->fetch_all(MYSQLI_ASSOC);

        foreach ($procedimientos as &$fila) {
            foreach ($fila as $clave => &$valor) {
                if ($valor === null) {
                    $valor = ""; // Suplanta null por una cadena vacía
                }
            }
        }


        return  $procedimientos;
    }

    function obtenerRX()
    {
        $connection = connection();
        $sql = "SELECT paciente.nombre as nomPaciente, procedimiento.adjunto as extension, paciente.id as idPaciente, paciente.fecha as fechaNacimiento, paciente.apellido as apellido, paciente.ci as ci, procedimiento.id, procedimiento.pieza, procedimiento.sector, procedimiento.nombre, procedimiento.fecha, procedimiento.descripcion, procedimiento.patologia, procedimiento.medicacion, procedimiento.estado, cuenta.estado as estadoCuenta, cuenta.costo, cuenta.unidad, cuenta.id as idCuenta FROM procedimiento RIGHT JOIN paciente on procedimiento.id_paciente = paciente.id INNER JOIN cuenta on cuenta.id_procedimiento= procedimiento.id WHERE procedimiento.adjunto='dcm' ORDER BY procedimiento.fecha ASC";
        $respuesta = $connection->query($sql);
        $procedimientos = $respuesta->fetch_all(MYSQLI_ASSOC);

        foreach ($procedimientos as &$fila) {
            foreach ($fila as $clave => &$valor) {
                if ($valor === null) {
                    $valor = ""; // Suplanta null por una cadena vacía
                }
            }
        }


        return  $procedimientos;
    }



    public function obtenerProcedimientosOrdenados($columna, $orden, $idPaciente)
    {
        $connection = connection();
        $sql = "SELECT * FROM procedimiento WHERE id_paciente=$idPaciente ORDER BY $columna $orden";
        $respuesta = $connection->query($sql);
        $resultado = $respuesta->fetch_all(MYSQLI_ASSOC);
        return $resultado;
    }

    public function obtenerPaciente($ci)
    {
        $connection = connection();
        $sql = "SELECT paciente.id FROM paciente where ci=$ci";
        $respuesta = $connection->query($sql);
        $resultado = $respuesta->fetch_all(MYSQLI_ASSOC);
        foreach ($resultado as &$idPaciente) {
            return $idPaciente['id'];
        }
    }

    public function agregarProcedimientoDAO($nombre, $descripcion, $pieza, $sector, $idPaciente, $fecha,  $estado, $medicacion, $patologia, $adjunto)
    {
        global $idProc;//Ya no es necesario usarla global
        $connection = connection();
        $nomImg = $adjunto['name'];
        $extension = pathinfo($nomImg, PATHINFO_EXTENSION);
        $sql = "INSERT INTO procedimiento(pieza, sector, nombre, id_paciente, fecha, descripcion, estado, medicacion, patologia, adjunto) VALUES ('$pieza', '$sector', '$nombre', '$idPaciente', '$fecha', '$descripcion', '$estado', '$medicacion', '$patologia', '$extension')";

        $respuesta = $connection->query($sql);
        $idProc = $connection->insert_id;
        $rutaTemp = $adjunto['tmp_name'];
        if ($extension == 'dcm') {
            move_uploaded_file($rutaTemp, "./pacs/$idProc.$extension");
        } else {
            move_uploaded_file($rutaTemp, "./adjuntos/$idProc.$extension");
        }


        if ($respuesta) {
            return $idProc;
        } else {
            return 0;
        }
    }

   



    public function modificarProcedimientoDAO($id, $nombre, $descripcion, $pieza, $sector, $idPaciente, $fecha,  $estado, $medicacion, $patologia,$adjunto)
    {
      
       
        $nomImg = $adjunto['name'];
        $extension = pathinfo($nomImg, PATHINFO_EXTENSION);
        if($adjunto){
            $sql = "UPDATE procedimiento SET patologia='$patologia',nombre='$nombre', adjunto='$extension', pieza='$pieza', sector='$sector', estado='$estado', medicacion='$medicacion', descripcion='$descripcion', fecha='$fecha' WHERE id=$id";
        }else{
            $sql = "UPDATE procedimiento SET patologia='$patologia',nombre='$nombre', pieza='$pieza', sector='$sector', estado='$estado', medicacion='$medicacion', descripcion='$descripcion', fecha='$fecha' WHERE id=$id";
        }

        $connection = connection();
        $respuesta = $connection->query($sql);
      //  $idProc = $connection->insert_id;
        $rutaTemp = $adjunto['tmp_name'];
        if ($extension == 'dcm') {
            move_uploaded_file($rutaTemp, "./pacs/$id.$extension");
        } else {
            move_uploaded_file($rutaTemp, "./adjuntos/$id.$extension");
        }

        if ($respuesta) {
            return true;
        } else {
            return false;
        }
    }

    public function eliminarProcedimientoDAO($id)
    {
        $sql = "DELETE FROM procedimiento WHERE id = '$id'";
        $connection = connection();
        $respuesta = $connection->query($sql);
        if ($respuesta) {
            return new Respuesta(true, "Procedimiento eliminado", $respuesta);
        } else {
            return new Respuesta(false, "Error al eliminar el procedimiento", $respuesta);
        }
    }
}
