<?php
// Simulación de una bas de datos
function obtenerLibros() {
    return [
        ['titulo' => 'Trono de Cristal', 'autor' => 'Sarah J. Maas', 'fecha' => 2012],
        ['titulo' => 'La Corona de la Noche', 'autor' => 'Kiera Cass', 'fecha' => 2014],
        ['titulo' => 'La Reina Roja', 'autor' => 'Victoria Aveyard', 'fecha' => 2015],
        ['titulo' => 'El Príncipe Cruel', 'autor' => 'Holly Black', 'fecha' => 2018],
        ['titulo' => 'De Sangre y Cenizas', 'autor' => 'Jennifer L. Armentrout', 'fecha' => 2021]
        
    ];
}

// Detalles de libros - HTML
function mostrarDetallesLibro($libro) {
    return "<div class='libro'>
                <h3>{$libro['titulo']}</h3>
                <p><strong>Autor:</strong> {$libro['autor']}</p>
                <p><strong>Fecha:</strong> {$libro['fecha']}</p>
            </div>";
}
?>
