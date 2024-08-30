
<?php
// Ejemplo de uso de explode()
$frase = "Manzana,Naranja,Plátano,Uva";
$frutas = explode(",", $frase);

echo "Frase original: $frase</br>";
echo "Array de frutas:</br>";
print_r($frutas);

// Ejercicio: Crea una variable con una lista de tus 3 cantantes favoritos separadas por guiones (-)
// y usa explode() para convertirla en un array
$misPeliculas = "Wave To Earth,Bangtan Boys,Chase Atlantic"; // Reemplaza esto con tu lista de cantantes
$arrayPeliculas = explode("-", $misPeliculas);

echo "</br>Mis cantantes favoritos:</br>";
print_r($arrayPeliculas);

// Bonus: Usa explode() con un límite
$texto = "Uno,Dos,Tres,Cuatro,Cinco";
$array = explode(",", $texto, 3);

echo "</br>Texto original: $texto</br>";
echo "Array con límite:</br>";
print_r($array);
?>
      
