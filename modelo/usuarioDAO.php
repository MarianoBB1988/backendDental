<?php

require_once  '../conexion/conexion.php';
require_once  'Respuesta/respuesta.php';

class usuario
{

    public function login($usuario, $password){
        $connection = connection();
        $sql = "SELECT * FROM usuario WHERE `nombre_usuario`='$usuario' and `password`='$password'";

        $respuesta =  $connection->query($sql);
        $resultado = $respuesta->fetch_assoc();
         return $resultado;
    }

    public function obtenerUsuarios()
    {
        $connection = connection();
        $sql = "SELECT * FROM usuario ORDER BY id DESC";
        $respuesta = $connection->query($sql);
        $resultado = $respuesta->fetch_all(MYSQLI_ASSOC);
        return $resultado;
    }
    public function agregarUsuario($nombre, $apellido, $nombre_usuario, $tipo, $contraseña)
    {
        $sql = "INSERT INTO usuario (nombre, apellido, nombre_usuario, tipo, password) VALUES ('$nombre', '$apellido', '$nombre_usuario', '$tipo', '$contraseña')";
        $connection = connection();
        $respuesta = $connection->query($sql);

        if ($respuesta) {
            return new Respuesta(true, "Usuario agregado", $respuesta);
        } else {
            return new Respuesta(false, "Error al agregar el usuario", $respuesta);
        }
    }

    public function modificarUsuario($id, $nombre, $apellido, $nombre_usuario, $tipo)
    {
        $sql = "UPDATE usuario SET nombre='$nombre', apellido='$apellido', nombre_usuario='$nombre_usuario', tipo='$tipo' WHERE id=$id";
        $connection = connection();
        $respuesta = $connection->query($sql);
        return $respuesta;
    }

    public function eliminarUsuario($id)
    {
        $sql = "DELETE FROM usuario WHERE id = '$id'";
        $connection = connection();
        $respuesta = $connection->query($sql);
        
        if ($respuesta) {
            return new Respuesta(true, "Usuario eliminado", $respuesta);
        } else {
            return new Respuesta(false, "Error al eliminar el usuario", $respuesta);
        }
    }

}