<?php

require_once '../conexion/conexion.php';
require_once 'Respuesta/respuesta.php';

class usuario
{
    

    public function obtenerUsuarios()
    {
        $connection = connection();
        $sql = "SELECT * FROM usuario ORDER BY id DESC";
        $respuesta = $connection->query($sql);
        $resultado = $respuesta->fetch_all(MYSQLI_ASSOC);
        return $resultado;
    }

    public function obtenerOdontologos()
    {
        $connection = connection();
        $sql = "SELECT * FROM usuario WHERE tipo = 'odontólogo' ORDER BY id DESC";
        $respuesta = $connection->query($sql);
        $resultado = $respuesta->fetch_all(MYSQLI_ASSOC);
        return $resultado;
    }

    public function agregarUsuario($nombre, $apellido, $nombre_usuario, $tipo, $contraseña)
    {
        $connection = connection();
        $sql = "INSERT INTO usuario (nombre, apellido, nombre_usuario, tipo, password) VALUES (?, ?, ?, ?, ?)";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param('sssss', $nombre, $apellido, $nombre_usuario, $tipo, $contraseña);
        
        if ($stmt->execute()) {
            return new Respuesta(true, "Usuario agregado", $stmt->insert_id);
        } else {
            return new Respuesta(false, "Error al agregar el usuario", $stmt->error);
        }
    }

    public function modificarUsuario($id, $nombre, $apellido, $nombre_usuario, $tipo)
    {
        $connection = connection();
        $sql = "UPDATE usuario SET nombre = ?, apellido = ?, nombre_usuario = ?, tipo = ? WHERE id = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param('ssssi', $nombre, $apellido, $nombre_usuario, $tipo, $id);

        $respuesta = $stmt->execute();
        return $respuesta;
    }

    public function eliminarUsuario($id)
    {
        $connection = connection();
        $sql = "DELETE FROM usuario WHERE id = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param('i', $id);

        if ($stmt->execute()) {
            return new Respuesta(true, "Usuario eliminado", $stmt->affected_rows);
        } else {
            return new Respuesta(false, "Error al eliminar el usuario", $stmt->error);
        }
    }
}
