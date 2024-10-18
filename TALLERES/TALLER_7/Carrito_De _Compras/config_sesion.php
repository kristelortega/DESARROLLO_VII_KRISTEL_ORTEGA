<?php
// Iniciar sesión con configuraciones seguras
session_start([
    'cookie_lifetime' => 86400, // 24 horas
    'cookie_httponly' => true,  // Cookies solo accesibles vía HTTP (mitiga ataques XSS)
    'use_strict_mode' => true,  // Evita la reutilización de IDs de sesión
    'use_only_cookies' => true, // Solo usar cookies para las sesiones
    'cookie_secure' => isset($_SERVER['HTTPS']), // Cookies solo en HTTPS si está activo
]);

// Configurar la cabecera para prevenir ataques XSS
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
?>
