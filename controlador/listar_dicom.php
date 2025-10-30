<?php
// Permitir solicitudes desde múltiples orígenes para debugging
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Permitir el envío de cookies desde un origen diferente
header("Access-Control-Allow-Credentials: true");
// Permitir los métodos HTTP especificados
header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, OPTIONS");
// Permitir los encabezados HTTP especificados
header("Access-Control-Allow-Headers: Content-Type, Authorization, access-control-allow-origin, X-Requested-With");

// Cabeceras necesarias para Web Workers y SharedArrayBuffer
header("Cross-Origin-Embedder-Policy: require-corp");
header("Cross-Origin-Opener-Policy: same-origin");
header("Cross-Origin-Resource-Policy: cross-origin");

// Manejar preflight OPTIONS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Función para recorrer el directorio y obtener las rutas de los archivos DICOM
function listarArchivosDicom($directorioBase, $relativePath) {
    $archivosDicom = [];

    if (!is_dir($directorioBase)) {
        return $archivosDicom;
    }

    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directorioBase));
    foreach ($iterator as $archivo) {
        if (pathinfo($archivo, PATHINFO_EXTENSION) === 'dcm') {
            // Verificar si el archivo está en un subdirectorio
            $rutaArchivoCompleta = str_replace('\\', '/', $archivo->getPathname());
            $rutaRelativaDesdeBase = str_replace($directorioBase, '', $rutaArchivoCompleta);
            $rutaRelativaDesdeBase = ltrim($rutaRelativaDesdeBase, '/');
            
            // Debug logging
            error_log("[DICOM DEBUG] Archivo encontrado: " . $archivo->getPathname());
            error_log("[DICOM DEBUG] Directorio base: " . $directorioBase);
            error_log("[DICOM DEBUG] Ruta archivo completa: " . $rutaArchivoCompleta);
            error_log("[DICOM DEBUG] Ruta relativa desde base: " . $rutaRelativaDesdeBase);
            error_log("[DICOM DEBUG] Relative path param: " . $relativePath);
            
            // Crear la ruta completa incluyendo subdirectorios
            $rutaCompleta = $relativePath . $rutaRelativaDesdeBase;
            
            error_log("[DICOM DEBUG] Ruta completa final: " . $rutaCompleta);
            
            // Crear URL segura usando el endpoint serve_dicom.php
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
            $url = $protocol . $_SERVER['HTTP_HOST'] . '/backendDental/controlador/serve_dicom.php?file=' . urlencode($rutaCompleta);
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
$directorioPacs = str_replace('\\', '/', __DIR__ . '/pacs/'.$cliente.'/'.$id.'/');

error_log("[DICOM DEBUG] Directorio PACS normalizado: " . $directorioPacs);
error_log("[DICOM DEBUG] Relative path que se pasa: " . $cliente.'/'.$id.'/');

// Obtener las rutas de los archivos DICOM
$imagenesDicom = listarArchivosDicom($directorioPacs, $cliente.'/'.$id.'/');

// Devolver el resultado como JSON sin escapar las barras
echo json_encode($imagenesDicom, JSON_UNESCAPED_SLASHES);
?>