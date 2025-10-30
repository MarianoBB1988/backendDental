<?php
// Endpoint para servir archivos DICOM de forma segura
header("Access-Control-Allow-Origin: *"); // Permitir todos los orígenes temporalmente para debugging
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
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

// Log de debugging
error_log("[DICOM DEBUG] Request Method: " . $_SERVER['REQUEST_METHOD']);
error_log("[DICOM DEBUG] Request URI: " . $_SERVER['REQUEST_URI']);
error_log("[DICOM DEBUG] GET params: " . print_r($_GET, true));

// Verificar que se recibió el path del archivo
if (!isset($_GET['file'])) {
    error_log("[DICOM ERROR] Parámetro file no recibido");
    http_response_code(400);
    echo json_encode(['error' => 'Parámetro file requerido']);
    exit;
}

$requestedFile = $_GET['file'];
error_log("[DICOM DEBUG] Archivo solicitado: $requestedFile");

// Sanitizar el path para evitar directory traversal
$requestedFile = str_replace(['../', '..\\', '../', '..'], '', $requestedFile);

// Construir la ruta completa del archivo
$basePath = __DIR__ . '/pacs/';
$filePath = $basePath . $requestedFile;

error_log("[DICOM DEBUG] Base path: $basePath");
error_log("[DICOM DEBUG] Full file path: $filePath");
error_log("[DICOM DEBUG] File exists: " . (file_exists($filePath) ? 'YES' : 'NO'));

// Verificar que el archivo existe y está dentro del directorio permitido
$realBasePath = realpath($basePath);
$realFilePath = realpath($filePath);

error_log("[DICOM DEBUG] Real base path: " . ($realBasePath ?: 'NULL'));
error_log("[DICOM DEBUG] Real file path: " . ($realFilePath ?: 'NULL'));
error_log("[DICOM DEBUG] Directory exists: " . (is_dir(dirname($filePath)) ? 'YES' : 'NO'));
error_log("[DICOM DEBUG] Directory name: " . dirname($filePath));

if (!$realFilePath || !$realBasePath || strpos($realFilePath, $realBasePath) !== 0) {
    error_log("[DICOM ERROR] Acceso denegado a archivo: $requestedFile");
    error_log("[DICOM ERROR] Razón - realFilePath: " . ($realFilePath ? 'OK' : 'FAIL'));
    error_log("[DICOM ERROR] Razón - realBasePath: " . ($realBasePath ? 'OK' : 'FAIL')); 
    error_log("[DICOM ERROR] Razón - strpos check: " . (($realFilePath && $realBasePath) ? (strpos($realFilePath, $realBasePath) !== 0 ? 'FAIL' : 'OK') : 'N/A'));
    http_response_code(403);
    echo json_encode(['error' => 'Acceso denegado', 'debug' => [
        'requestedFile' => $requestedFile,
        'filePath' => $filePath,
        'fileExists' => file_exists($filePath),
        'dirExists' => is_dir(dirname($filePath))
    ]]);
    exit;
}

if (!file_exists($realFilePath)) {
    error_log("[DICOM ERROR] Archivo no encontrado: $requestedFile");
    http_response_code(404);
    echo json_encode(['error' => 'Archivo no encontrado']);
    exit;
}

// Verificar que es un archivo DICOM
$extension = strtolower(pathinfo($realFilePath, PATHINFO_EXTENSION));
if ($extension !== 'dcm') {
    error_log("[DICOM ERROR] Tipo de archivo no permitido: $extension");
    http_response_code(400);
    echo json_encode(['error' => 'Tipo de archivo no permitido']);
    exit;
}

try {
    // Configurar headers para archivos DICOM
    header('Content-Type: application/dicom');
    header('Content-Length: ' . filesize($realFilePath));
    header('Content-Disposition: inline; filename="' . basename($realFilePath) . '"');
    header('Cache-Control: public, max-age=3600'); // Cache por 1 hora
    
    // Enviar el archivo
    readfile($realFilePath);
    
    error_log("[DICOM INFO] Archivo servido exitosamente: $requestedFile");
    
} catch (Exception $e) {
    error_log("[DICOM ERROR] Error al servir archivo: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Error interno del servidor']);
}
?>