<?php
header('Content-Type: application/json; charset=utf-8');

// URL de Gaceta Dental
$url = 'https://gacetadental.com/';

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10); // Timeout de 10 segundos
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5); // Timeout de conexión de 5 segundos
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36');
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // En caso de problemas con SSL
$html = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$noticias = [];

if ($html !== false && $httpCode === 200) {
    error_log("[NOTICIAS] Scraping exitoso para gacetadental.com");
    libxml_use_internal_errors(true);
    $dom = new DOMDocument();
    @$dom->loadHTML($html); // @ para suprimir warnings de HTML mal formado
    $xpath = new DOMXPath($dom);

    // Selectores más específicos para gacetadental.com
    $articulos = $xpath->query("//article | //div[@class='post'] | //div[contains(@class, 'td_module')] | //div[contains(@class, 'td-module')] | //div[contains(@class, 'entry')]");
    
    error_log("[NOTICIAS] Se encontraron " . $articulos->length . " artículos potenciales");

    foreach ($articulos as $articulo) {
        // Título y enlace - Selectores más amplios
        $tituloNode = $xpath->query(".//h1//a | .//h2//a | .//h3//a | .//h4//a | .//a[contains(@class, 'entry-title')] | .//a[contains(@class, 'post-title')]", $articulo)->item(0);
        if ($tituloNode instanceof DOMElement) {
            $titulo = trim($tituloNode->nodeValue);
            $urlNoticia = $tituloNode->hasAttribute('href') ? $tituloNode->getAttribute('href') : null;
            
            // Convertir URL relativas a absolutas
            if ($urlNoticia && !preg_match('/^https?:\/\//', $urlNoticia)) {
                $urlNoticia = 'https://gacetadental.com' . $urlNoticia;
            }
        } else {
            $titulo = null;
            $urlNoticia = null;
        }

        // Imagen - Selectores más amplios
        $imgNode = $xpath->query(".//img[not(contains(@class, 'avatar'))]", $articulo)->item(0);
        if ($imgNode instanceof DOMElement) {
            $imagen = $imgNode->hasAttribute('src') ? $imgNode->getAttribute('src') : null;
            // También intentar data-src para lazy loading
            if (!$imagen && $imgNode->hasAttribute('data-src')) {
                $imagen = $imgNode->getAttribute('data-src');
            }
            // Convertir URL relativas a absolutas
            if ($imagen && !preg_match('/^https?:\/\//', $imagen)) {
                $imagen = 'https://gacetadental.com' . $imagen;
            }
        } else {
            $imagen = null;
        }

        // Resumen - Selectores más amplios
        $resumenNode = $xpath->query(".//div[contains(@class, 'excerpt')] | .//div[contains(@class, 'summary')] | .//p[not(ancestor::*[contains(@class, 'meta')])]", $articulo)->item(0);
        $resumen = $resumenNode ? trim(substr($resumenNode->nodeValue, 0, 200)) : '';

        if ($titulo && $urlNoticia && strlen($titulo) > 10) { // Título debe tener al menos 10 caracteres
            $noticias[] = [
                'titulo' => $titulo,
                'url' => $urlNoticia,
                'resumen' => $resumen,
                'imagen' => $imagen,
            ];
            error_log("[NOTICIAS] Noticia agregada: " . substr($titulo, 0, 50) . "...");
        }
        if (count($noticias) >= 8) break; // Limita a 8 noticias
    }
} else {
    error_log("[NOTICIAS] Error al obtener HTML. HTTP Code: " . $httpCode);
}

error_log("[NOTICIAS] Total de noticias encontradas: " . count($noticias));

echo json_encode(['noticias' => $noticias], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
?>