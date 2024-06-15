<?php

require_once  '../conexion/conexion.php';
require_once  'Respuesta/respuesta.php';

class usuario
{

    public function login($usuario, $password){
        $connection = connection();
        $sql = "SELECT * FROM usuario WHERE `nombre`='$usuario' and `password`='$password'";

        $respuesta =  $connection->query($sql);
        $resultado = $respuesta->fetch_assoc();
         return $resultado;
    }


}