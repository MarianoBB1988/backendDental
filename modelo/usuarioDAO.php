<?php

// Cambia los require_once a rutas absolutas para CLI
require_once __DIR__ . '/../conexion/con_clientes.php';
require_once __DIR__ . '/../conexion/conexion.php';
require_once __DIR__ . '/Respuesta/respuesta.php';

class usuario
{
    

    public function obtenerUsuarios()
    {
        $connection = connection_cli();
        $cliente= $_SESSION['sesion']['bd'];
        $sql = "SELECT * FROM usuario where tipo != 'admin' and cliente='$cliente' ORDER BY id DESC";
        $respuesta = $connection->query($sql);
        $resultado = $respuesta->fetch_all(MYSQLI_ASSOC);
        return $resultado;
    }

    public function obtenerOdontologos()
    {
        $connection = connection_cli();
        $cliente= $_SESSION['sesion']['bd'];
        $sql = "SELECT * FROM usuario WHERE tipo = 'odontólogo' or tipo='admin' and cliente='$cliente' ORDER BY id DESC";
        $respuesta = $connection->query($sql);
        $resultado = $respuesta->fetch_all(MYSQLI_ASSOC);
        return $resultado;
    }

    public function perfil($usuario)
    {
        $connection = connection_cli();
        $sql = "SELECT * FROM usuario WHERE nombre_usuario = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param('s', $usuario);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_assoc();
        return $resultado;
    }

    public function agregarUsuario($nombre, $apellido, $nombre_usuario, $tipo, $contraseña)
    {
        $connection = connection_cli();
        $cliente= $_SESSION['sesion']['bd'];
        $hash = password_hash($contraseña, PASSWORD_DEFAULT);
        $sql = "INSERT INTO usuario (nombre, apellido, nombre_usuario, tipo, password, cliente) VALUES (?, ?, ?, ?, ?,?)";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param('ssssss', $nombre, $apellido, $nombre_usuario, $tipo, $hash, $cliente);
        
        if ($stmt->execute()) {
            return new Respuesta(true, "Usuario agregado", $stmt->insert_id);
        } else {
            return new Respuesta(false, "Error al agregar el usuario", $stmt->error);
        }
    }

     public function agregarUsuarioScript($nombre, $apellido, $nombre_usuario, $tipo, $contraseña,$cliente)
    {
        $connection = connection_cli();
      
        $hash = password_hash($contraseña, PASSWORD_DEFAULT);
        $sql = "INSERT INTO usuario (nombre, apellido, nombre_usuario, tipo, password, cliente) VALUES (?, ?, ?, ?, ?,?)";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param('ssssss', $nombre, $apellido, $nombre_usuario, $tipo, $hash, $cliente);
        
        if ($stmt->execute()) {
            return new Respuesta(true, "Usuario agregado", $stmt->insert_id);
        } else {
            return new Respuesta(false, "Error al agregar el usuario", $stmt->error);
        }
    }

    public function modificarUsuario($id, $nombre, $apellido, $nombre_usuario, $tipo)
    {
        $connection = connection_cli();
        $sql = "UPDATE usuario SET nombre = ?, apellido = ?, nombre_usuario = ?, tipo = ? WHERE id = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param('ssssi', $nombre, $apellido, $nombre_usuario, $tipo, $id);

        $respuesta = $stmt->execute();
        return $respuesta;
    }

    public function resetearContraseña($id, $contraseña)
    {
        $connection = connection_cli();
        $hash = password_hash($contraseña, PASSWORD_DEFAULT);
        $sql = "UPDATE usuario SET password = ? WHERE id = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param('si', $hash, $id);

        if ($stmt->execute()) {
            // Verificación: lee el hash guardado y lo muestra en el log temporalmente
            $sql2 = "SELECT password FROM usuario WHERE id = ?";
            $stmt2 = $connection->prepare($sql2);
            $stmt2->bind_param('i', $id);
            $stmt2->execute();
            $stmt2->bind_result($hash_db);
            $stmt2->fetch();
            error_log("[DEBUG] Hash guardado para usuario $id: $hash_db");
            $stmt2->close();
            return new Respuesta(true, "Contraseña modificada", $stmt->affected_rows);
        } else {
            return new Respuesta(false, "Error al modificar la contraseña", $stmt->error);
        }
    }

    public function eliminarUsuario($id)
    {
        $connection = connection_cli();
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
