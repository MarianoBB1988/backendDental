<?php

require_once '../conexion/conexion.php';
require_once 'Respuesta/respuesta.php';

class login {

    public function login($usuario, $password)
    {
        $connection = connection();
        $sql = "SELECT * FROM usuario WHERE nombre_usuario = ? AND password = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param('ss', $usuario, $password);
        $stmt->execute();
        $respuesta = $stmt->get_result();
        $resultado = $respuesta->fetch_assoc();
        return $resultado;
    }


}
