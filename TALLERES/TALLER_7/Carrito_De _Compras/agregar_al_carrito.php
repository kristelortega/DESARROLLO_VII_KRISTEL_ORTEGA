<?php
include 'config_sesion.php';

// Verificar si el ID del producto existe
if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];

    // Inicializar el carrito si no existe
    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = [];
    }

    // Agregar el producto o incrementar la cantidad
    if (isset($_SESSION['carrito'][$id])) {
        $_SESSION['carrito'][$id]['cantidad']++;
    } else {
        // Obtener datos del producto
        $productos = [
            1 => ['nombre' => 'Mancuernas (5kg)', 'precio' => 20.00],
            2 => ['nombre' => 'Esterilla de Yoga', 'precio' => 15.00],
            3 => ['nombre' => 'Cuerda para saltar', 'precio' => 8.00],
            4 => ['nombre' => 'Banda elástica de resistencia', 'precio' => 12.50],
            5 => ['nombre' => 'Rodillo abdominal', 'precio' => 18.00],
        ];

        // Si el producto existe, agregar al carrito
        if (isset($productos[$id])) {
            $_SESSION['carrito'][$id] = [
                'nombre' => $productos[$id]['nombre'],
                'precio' => $productos[$id]['precio'],
                'cantidad' => 1
            ];
        }
    }
}

// Redirigir al carrito
header("Location: ver_carrito.php");
exit();
?>
