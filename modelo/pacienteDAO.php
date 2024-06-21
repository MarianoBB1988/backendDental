<?php

require_once  '../conexion/conexion.php';
require_once  'Respuesta/respuesta.php';

class paciente
{

    public function obtenerPaciente($ci)
    {
        $connection = connection();
        $sql = "SELECT * FROM paciente where ci=$ci";
        $respuesta = $connection->query($sql);
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
        // $respuesta = $connection->query($sql);

    }

    public function obtenerPacientesOrdenados($columna, $orden)
    {
        $connection = connection();
        $sql = "SELECT * FROM paciente ORDER BY $columna $orden";
        $respuesta = $connection->query($sql);
        $resultado = $respuesta->fetch_all(MYSQLI_ASSOC);
        return $resultado;
    }

    public function agregarPacienteDAO($nombre, $apellido, $ci, $telefono, $email, $direccion, $genero, $fecha, $observaciones, $extension)
    {
        $sql = "INSERT INTO paciente VALUES (null, '$nombre', '$apellido', '$ci', '$telefono', '$email', '$fecha', '$genero', '$direccion', '$observaciones', '$extension')";
        $connection = connection();
        $respuesta = $connection->query($sql);

        if ($respuesta) {
            return new Respuesta(true, "Paciente agregado", $respuesta);
        } else {
            return new Respuesta(false, "Error al agregar el paciente", $respuesta);
        }
    }

      
    public function modificarPacienteDAO($id,$nombre, $apellido, $ci, $telefono, $email, $direccion, $genero, $fecha, $observaciones, $extension){
        $sql="UPDATE paciente SET nombre='$nombre',apellido='$apellido', ci='$ci', telefono='$telefono', email='$email', direccion='$direccion', genero='$genero', fecha='$fecha', observaciones='$observaciones', extension='$extension' WHERE id=$id";
         $connection = connection();
         $respuesta = $connection->query($sql);
         return $respuesta;
         
     }

     public function modificarExtensionDAO($ci, $extension){
        $sql="UPDATE paciente SET extension='$extension' WHERE ci=$ci";
         $connection = connection();
         $respuesta = $connection->query($sql);
         return $respuesta;
         
     }

     public function eliminarPacienteDAO($id){
        $sql = "DELETE FROM paciente WHERE id = '$id'";
        $connection = connection();
        $respuesta = $connection->query($sql);
        
        if ($respuesta){
            return new Respuesta(true, "Paciente eliminado", $respuesta);
        }else{
            return new Respuesta(false, "Error al eliminar el paciente", $respuesta);
        }
    }
}
