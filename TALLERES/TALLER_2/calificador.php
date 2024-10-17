<?php
// Declaración de la variable calificación
$calificacion = 75; 

// Estructura if-elseif-else
if ($calificacion >= 90) {
    $letra = 'A';
} elseif ($calificacion >= 80) {
    $letra = 'B';
} elseif ($calificacion >= 70) {
    $letra = 'C';
} elseif ($calificacion >= 60) {
    $letra = 'D';
} else {
    $letra = 'F';
}

// Mensaje con la letra de la calificación
echo "Tu calificación es $letra.<br>";

// Aprobado o Reprobado con operador ternario
$estado = ($letra == 'A' || $letra == 'B' || $letra == 'C' || $letra == 'D') ? "Aprobado" : "Reprobado";
echo "Estado: $estado<br>";

// Switch
switch ($letra) {
    case 'A':
        echo "Excelente trabajo";
        break;
    case 'B':
        echo "Buen trabajo";
        break;
    case 'C':
        echo "Trabajo aceptable";
        break;
    case 'D':
        echo "Necesitas mejorar";
        break;
    case 'F':
        echo "Debes esforzarte más";
        break;
    default:
        echo "Calificación inválida";
        break;
}
?>