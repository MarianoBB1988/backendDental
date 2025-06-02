<?php
// Permitir solicitudes solo desde el origen específico
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Content-Type: application/json");

// Permitir el envío de cookies desde un origen diferente
header("Access-Control-Allow-Credentials: true");
// Permitir los métodos HTTP especificados (GET, POST, PATCH, PUT, OPTIONS)
header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, OPTIONS");
// Permitir los encabezados HTTP especificados
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Función para recorrer el directorio y obtener las rutas de los archivos DICOM
function listarArchivosDicom($directorioBase) {
    $archivosDicom = [];

    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directorioBase));
    foreach ($iterator as $archivo) {
        if (pathinfo($archivo, PATHINFO_EXTENSION) === 'dcm') {
            $rutaRelativa = str_replace('\\', '/', $archivo); // Reemplazar barras invertidas por barras diagonales
            $rutaRelativa = str_replace($_SERVER['DOCUMENT_ROOT'], '', $rutaRelativa); // Obtener la ruta relativa desde la raíz del servidor
            $url = 'http://' . $_SERVER['HTTP_HOST'] . '/backendDental/controlador/' . $rutaRelativa; // Asegurarse de que haya una barra después del host
            $archivosDicom[] = $url;
        }
    }
    sort($archivosDicom);
    return $archivosDicom;
}
session_start();

$id=$_GET['id'];
$cliente=$_SESSION['sesion']['bd'];

// Directorio que contiene los archivos DICOM
$directorioPacs = 'pacs/'.$cliente.'/'.$id.'/';

// Obtener las rutas de los archivos DICOM
$imagenesDicom = listarArchivosDicom($directorioPacs);

// Devolver el resultado como JSON sin escapar las barras
echo json_encode($imagenesDicom, JSON_UNESCAPED_SLASHES);
?>
