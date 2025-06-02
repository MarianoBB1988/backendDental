<?php

require_once '../conexion/conexion.php';
require_once 'Respuesta/respuesta.php';

class login {

    public function login($usuario, $password)
    {
        $connection = connection_cli();
        $sql = "SELECT * FROM usuario WHERE nombre_usuario = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param('s', $usuario);
        $stmt->execute();
        $respuesta = $stmt->get_result();
        $resultado = $respuesta->fetch_assoc();
        if ($resultado) {
            error_log("[LOGIN DEBUG] Intento login usuario: $usuario");
            error_log("[LOGIN DEBUG] Hash en BD: " . $resultado['password']);
            error_log("[LOGIN DEBUG] Password recibido: $password");
            if (password_verify($password, $resultado['password'])) {
                error_log("[LOGIN DEBUG] password_verify: OK");
                return $resultado;
            } else {
                error_log("[LOGIN DEBUG] password_verify: FAIL");
            }
        }
        return null;
    }


}
