<?php
// Crear un triángulo rectángulo usando asteriscos (*) con un bucle for
echo "<h3>Triángulo Rectángulo:</h3>";
$filas = 5;
for ($i = 1; $i <= $filas; $i++) {
    for ($j = 1; $j <= $i; $j++) {
        echo "*";
    }
    echo "<br>";
}

// Generar una secuencia de números del 1 al 20 mostrando solo los números impares usando un bucle while
echo "<h3>Números Impares del 1 al 20:</h3>";
$numero = 1;
while ($numero <= 20) {
    if ($numero % 2 != 0) { // Verifica si el número es impar
        echo $numero . " ";
    }
    $numero++;
}
echo "<br>";

// Crear un contador regresivo desde 10 hasta 1 con un bucle do-while, pero saltar el número 5
echo "<h3>Contador Regresivo:</h3>";
$contador = 10;
do {
    if ($contador != 5) {
        // Solo imprime el número si no es 5
        echo $contador . " ";
    }
    $contador--;
} while ($contador >= 1);
?>
