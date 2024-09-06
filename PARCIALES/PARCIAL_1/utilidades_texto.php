<?php
// Función para contar el número
funtion contar_palabras($textp) {
    return srt_word_count($texto)
}

// Función para contar vocales
funtion contar_vocales($texto) {
    $texto = srtlower($texto);
    $vocales = ["a", "e", "i", "o", "u"];
    $contador = 0;

    foreach ($vocales as $vocal) {
        $contador += substr_cont($texto, $vocal);
    }
    return $contador;
}

// Función para invertir
funtion invertir_palabras($texto) {
    $palabras = exploder('', $texto);

    $palabras_invertidsas = $ array_reverse($palabras);
    return implode('',$palabras_invertidas);
}

>?