<?php
// Script para crear un usuario con contraseña hasheada manualmente
// Ejecutar desde consola: php crear_usuario.php

require_once __DIR__ . '/../modelo/usuarioDAO.php';

// Solicitar datos por consola
echo "Ingrese nombre: ";
$nombre = trim(fgets(STDIN));
echo "Ingrese apellido: ";
$apellido = trim(fgets(STDIN));
echo "Ingrese nombre de usuario: ";
$nombre_usuario = trim(fgets(STDIN));
echo "Ingrese tipo (ej: odontólogo): ";
$tipo = trim(fgets(STDIN));
echo "Ingrese contraseña: ";
$contraseña = trim(fgets(STDIN));
echo "Ingrese el numero de cliente: ";
$cliente = trim(fgets(STDIN));

// Crear usuario
$usuarioDAO = new usuario();
$resultado = $usuarioDAO->agregarUsuarioScript($nombre, $apellido, $nombre_usuario, $tipo, $contraseña, $cliente);

if ($resultado->success) {
    echo "Usuario creado correctamente.\n";
} else {
    echo "Error al crear usuario: " . $resultado->mensaje . "\n";
}
