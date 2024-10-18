<?php
include 'config_sesion.php';

// Al finalizar la compra, vaciar el carrito y recordar el nombre del usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_usuario = htmlspecialchars($_POST['nombre']);

    // Guardar el nombre del usuario en una cookie por 24 horas
    setcookie('usuario', $nombre_usuario, time() + 86400, '/', '', isset($_SERVER['HTTPS']), true);

    // Vaciar el carrito
    unset($_SESSION['carrito']);

    echo "<h1>Gracias por tu compra, $nombre_usuario</h1>";
    echo "<p>Tu carrito ha sido vaciado.</p>";

    // Mostrar el botón para regresar a la página de productos
    echo "<a href='productos.php'>
            <button style='padding: 10px 20px; font-size: 16px; cursor: pointer;'>Volver a comprar</button>
          </a>";
    
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Finalizar Compra</title>
    <style>
        body {
            text-align: center;
            font-family: Arial, sans-serif;
        }
        form {
            margin-top: 20px;
        }
        button {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
        }
    </style>
</head>
<body>

<h1>Finalizar Compra</h1>

<form action="checkout.php" method="POST">
    <label for="nombre">Nombre:</label>
    <input type="text" id="nombre" name="nombre" required>
    <button type="submit">Finalizar</button>
</form>

<a href="ver_carrito.php">Volver al Carrito</a>

</body>
</html>
