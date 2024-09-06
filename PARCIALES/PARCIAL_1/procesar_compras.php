<?php
include "funciones_tienda.php";

$productos = ['camisa' => 50, 'pantalon' => 70, 'zapatos' => 80, 'calcetines' => 10, 'gorra' => 25];
$carrito = ['camisa' => 2, 'pantalon' => 1, 'zapatos' => 1, 'calcetines' => 3, 'gorra' => 0];

//Calcular subtotal
$subtotal = 0;
foreach ($carrito as $producto => $cantidad) {
    $subtotal += $productos[$producto] * $cantidad;
}



>?