<?php
include "utilidades_texto.php";

$frases = [
    "Alicia perdio la cabeza.",
    "Lucia cambio su celular.",
    "JeanPaul es amigo de Ricardo."
];

//Inicio de HTML
echo "<html><head><title>Analisis de Texto>/title></head><body>";
echo "<table border= '1'>";
echo "<tr><th>Frases</th><th>Vocales</th><th>Frase Invertida</th></tr>";

//Bucle
foreach ($frases as $frase) {
    $num_palabras = contar_palabras($frase);
    $num_voacles = contar_vocales($frase);
    $frase_invertida = invertir_palabras($frase);

    echo "<tr>";
    echo "<td>{$frase}</td>";
    echo "<td>{$num_palabras}</td>";
    echo "<td>{$frase_invertida}</td>";
    echo "</tr>";
}

//Cierre HTML
echo "</table>";
echo "</body></html>";

>?