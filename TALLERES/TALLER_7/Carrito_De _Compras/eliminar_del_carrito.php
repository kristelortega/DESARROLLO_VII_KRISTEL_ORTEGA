<?php
include 'config_sesion.php';

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    
    // Eliminar el producto del carrito
    if (isset($_SESSION['carrito'][$id])) {
        unset($_SESSION['carrito'][$id]);
    }
}

// Redirigir al carrito
header("Location: ver_carrito.php");
exit();
?>
