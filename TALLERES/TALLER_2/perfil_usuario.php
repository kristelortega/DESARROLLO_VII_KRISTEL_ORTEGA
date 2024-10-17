
<?php
// Definición de variables
$nombre_completo = "Kristel Ortega";
$edad = 20;
$correo = "kristel.ortega@utp.ac.pa";
$telefono = "6793-5514";

// Definición de una constante
define("OCUPACION", "Estudiante");

// Creación del párrafo utilizando diferentes métodos de concatenación e impresión
echo "Mi nombre completo es " . $nombre_completo . ", tengo " . $edad . " años, mi correo electrónico es " . $correo . " y mi número de teléfono es " . $telefono . ". Mi ocupación actual es " . OCUPACION . ".<br>";

print "Mi nombre completo es $nombre_completo, tengo $edad años, mi correo electrónico es $correo y mi número de teléfono es $telefono. Mi ocupación actual es " . OCUPACION . ".<br>";

printf("Mi nombre completo es %s, tengo %d años, mi correo electrónico es %s y mi número de teléfono es %s. Mi ocupación actual es %s.<br>",
       $nombre_completo, $edad, $correo, $telefono, OCUPACION);

// Mostrar el tipo y valor de cada variable y la constante
echo "<pre>";
var_dump($nombre_completo);
var_dump($edad);
var_dump($correo);
var_dump($telefono);
var_dump(OCUPACION);
echo "</pre>";
?>
