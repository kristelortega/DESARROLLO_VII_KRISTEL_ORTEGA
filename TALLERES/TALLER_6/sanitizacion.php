<?php
function sanitizarNombre($nombre) {
    return htmlspecialchars(trim($nombre), ENT_QUOTES, 'UTF-8');
}

function sanitizarEmail($email) {
    return htmlspecialchars(trim($email), ENT_QUOTES, 'UTF-8');
}

function sanitizarEdad($edad) {
    return intval($edad); // Para edad, puedes convertir a entero directamente
}

function sanitizarSitioWeb($sitioWeb) {
    return htmlspecialchars(trim($sitioWeb), ENT_QUOTES, 'UTF-8');
}

function sanitizarGenero($genero) {
    return htmlspecialchars(trim($genero), ENT_QUOTES, 'UTF-8');
}

function sanitizarIntereses($intereses) {
    return array_map('htmlspecialchars', $intereses); // Sanitiza cada interés
}

function sanitizarComentarios($comentarios) {
    return htmlspecialchars(trim($comentarios), ENT_QUOTES, 'UTF-8');
}

function sanitizarFechaNacimiento($fechaNacimiento) {
    return htmlspecialchars(trim($fechaNacimiento), ENT_QUOTES, 'UTF-8');
}

// Puedes agregar más funciones de sanitización si es necesario.
?>
