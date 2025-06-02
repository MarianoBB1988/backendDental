<?php
header('Content-Type: application/json; charset=utf-8');

// URL de Gaceta Dental
$url = 'https://gacetadental.com/';

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$html = curl_exec($ch);
curl_close($ch);

$noticias = [];

if ($html !== false) {
    libxml_use_internal_errors(true);
    $dom = new DOMDocument();
    $dom->loadHTML($html);
    $xpath = new DOMXPath($dom);

    // Selecciona los artículos principales usando <article> o <div> con clases comunes
    $articulos = $xpath->query("//article | //div[contains(@class, 'post') or contains(@class, 'entry')]" );

    foreach ($articulos as $articulo) {
        // Título y enlace
        $tituloNode = $xpath->query(".//h2[contains(@class, 'entry-title')]//a | .//h3[contains(@class, 'entry-title')]//a | .//h2//a | .//h3//a", $articulo)->item(0);
        if ($tituloNode instanceof DOMElement) {
            $titulo = trim($tituloNode->nodeValue);
            $urlNoticia = $tituloNode->hasAttribute('href') ? $tituloNode->getAttribute('href') : null;
        } else {
            $titulo = null;
            $urlNoticia = null;
        }

        // Imagen
        $imgNode = $xpath->query(".//figure//img | .//div[contains(@class, 'post-thumbnail')]//img | .//div[contains(@class, 'entry-image')]//img | .//img", $articulo)->item(0);
        if ($imgNode instanceof DOMElement) {
            $imagen = $imgNode->hasAttribute('src') ? $imgNode->getAttribute('src') : null;
        } else {
            $imagen = null;
        }

        // Resumen
        $resumenNode = $xpath->query(".//div[contains(@class, 'entry-summary')] | .//div[contains(@class, 'excerpt')] | .//p[contains(@class, 'entry-summary')] | .//p[contains(@class, 'excerpt')] | .//p", $articulo)->item(0);
        $resumen = $resumenNode ? trim($resumenNode->nodeValue) : '';

        if ($titulo && $urlNoticia) {
            $noticias[] = [
                'titulo' => $titulo,
                'url' => $urlNoticia,
                'resumen' => $resumen,
                'imagen' => $imagen,
            ];
        }
        if (count($noticias) >= 8) break; // Limita a 8 noticias
    }
}

echo json_encode(['noticias' => $noticias], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
?>