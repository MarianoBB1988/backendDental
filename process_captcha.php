<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtén el valor del reCAPTCHA enviado desde el frontend
    $captcha = $_POST['g-recaptcha-response'];

    // Tu clave secreta de reCAPTCHA
    $secretKey = "6LeosCMrAAAAAIUfov-tT-lgeXAx5fYaar8nfGqi"; // Reemplaza con tu clave secreta

    // La dirección IP del cliente
    $remoteIp = $_SERVER['REMOTE_ADDR'];

    // Realiza la verificación con la API de Google
    $url = "https://www.google.com/recaptcha/api/siteverify";
    $data = [
        'secret' => $secretKey,
        'response' => $captcha,
        'remoteip' => $remoteIp
    ];

    // Enviar la solicitud POST a la API de Google
    $options = [
        'http' => [
            'method' => 'POST',
            'content' => http_build_query($data),
            'header' => "Content-type: application/x-www-form-urlencoded\r\n"
        ]
    ];
    $context = stream_context_create($options);
    $verifyResponse = file_get_contents($url, false, $context);
    $responseKeys = json_decode($verifyResponse);

    // Verificar si el reCAPTCHA fue exitoso
    if ($responseKeys->success) {
        // El reCAPTCHA es válido, procesa el formulario
        echo "Captcha verificado exitosamente!";
    } else {
        // El reCAPTCHA no es válido
        echo "Error de verificación del captcha.";
    }
}
?>
