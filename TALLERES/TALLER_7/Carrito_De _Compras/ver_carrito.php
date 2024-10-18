<?php include 'config_sesion.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carrito de Compras</title>
</head>
<body>

<h1>Tu Carrito de Compras</h1>

<?php
if (isset($_SESSION['carrito']) && !empty($_SESSION['carrito'])) {
    $total = 0;

    echo "<table border='1'>";
    echo "<tr><th>Producto</th><th>Precio</th><th>Cantidad</th><th>Acción</th></tr>";
    
    foreach ($_SESSION['carrito'] as $id => $producto) {
        $subtotal = $producto['precio'] * $producto['cantidad'];
        $total += $subtotal;

        echo "<tr>
                <td>{$producto['nombre']}</td>
                <td>\${$producto['precio']}</td>
                <td>{$producto['cantidad']}</td>
                <td><a href='eliminar_del_carrito.php?id={$id}'>Eliminar</a></td>
              </tr>";
    }

    echo "<tr><td colspan='3'>Total</td><td>\${$total}</td></tr>";
    echo "</table>";
} else {
    echo "<p>Tu carrito está vacío</p>";
}
?>

<a href="productos.php">Seguir Comprando</a>
<a href="checkout.php">Finalizar Compra</a>

</body>
</html>
