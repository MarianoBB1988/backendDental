<?php
//Deshabilitar la visualización de errores
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');

// Habilitar el registro de errores
ini_set('log_errors', '1');
ini_set('error_log', '../log/php_errors.log');
function connection()
{
           session_start();

// 2. Verificar si la sesión existe y tiene los datos
if (isset($_SESSION['sesion'])) {
    // 3. Acceder a los datos

    $cliente = $_SESSION['sesion']['bd'];
    
  
} else {
    // La sesión no existe, redirigir al login
    header("Location: login.php");
    exit();
}

try{
        $host = "localhost";
        $usuario = "root";
        $password = "";
        $bd = $cliente.'_dental';
        $puerto = 3306;
        $mysql = new mysqli($host, $usuario, $password, $bd, $puerto);
        return $mysql;
    } catch (Exception $e) {
        $error = $e->getMessage();
        echo $error; //return
    }
}

















?>