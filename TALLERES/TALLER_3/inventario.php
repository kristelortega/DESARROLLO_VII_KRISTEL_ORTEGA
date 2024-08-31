<?php
//Creación de funciones

// Función para leer el inventario desde el archivo JSON
function leerInventario($archivo) {
    $contenido = file_get_contents($archivo);
    $inventario = json_decode($contenido, true);
    return $inventario;
}

// Función para ordenar el inventario
function ordenarInventarioPorNombre(&$inventario) {
    usort($inventario, function($a, $b) {
        return strcmp($a['titulo'], $b['titulo']);
    });
}

// Función para mostrar resumen del inventario ordenado
function mostrarResumenInventario($inventario) {
    foreach ($inventario as $libro) {
        echo "Título: {$libro['titulo']}, Autor: {$libro['autor']}, Año: {$libro['anio']}, Precio: {$libro['precio']}, Cantidad: {$libro['cantidad']}\n";
    }
}

// Funció para calcular el vaor total del inventario
function calcularValorTotalInventario($inventario) {
    $valorTotal = array_sum(array_map(function($libro) {
        return $libro['precio'] * $libro['cantidad'];
    }, $inventario));
    return $valorTotal;
}

// Función para generar un informe de productos con stock bajo
function generarInforme($inventario) {
    // Filtrar productos con stock bajo (cantidad < 5)
    $stockBajo = array_filter($inventario, function($libro) {
        return $libro['cantidad'] < 5;
    });

    // Mostrar el informe
    if (empty($stockBajo)) {
        echo "No hay libros con stock bajo.\n";
    } else {
        echo "Libros con stock bajo:\n";
        foreach ($stockBajo as $libro) {
            echo "Título: {$libro['titulo']}, Cantidad: {$libro['cantidad']}\n";
        }
    }
}

// Script
$archivoInventario = 'inventario.json';

$inventario = leerInventario($archivoInventario);
ordenarInventarioPorNombre($inventario);

echo "Resumen del inventario de libros ordenados:\n";
mostrarResumenInventario($inventario);

$valorTotal = calcularValorTotalInventario($inventario);
echo "\nValor total del inventario de libros: $valorTotal\n";

echo "\nInforme de libros con stock bajo:\n";
generarInforme($inventario);

?>