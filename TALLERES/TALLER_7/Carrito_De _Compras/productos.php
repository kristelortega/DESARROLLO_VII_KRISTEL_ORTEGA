<?php include 'config_sesion.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Productos de Ejercicio</title>
    <style>
        table {
            width: 60%;
            margin: 20px auto;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #000;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        a {
            text-decoration: none;
            color: #007BFF;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<h1 style="text-align: center;">Lista de Productos de Ejercicio</h1>

<table>
    <thead>
        <tr>
            <th>Producto</th>
            <th>Precio</th>
            <th>Acción</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Lista de productos de ejercicio
        $productos = [
            1 => ['nombre' => 'Mancuernas (5kg)', 'precio' => 20.00],
            2 => ['nombre' => 'Esterilla de Yoga', 'precio' => 15.00],
            3 => ['nombre' => 'Cuerda para saltar', 'precio' => 8.00],
            4 => ['nombre' => 'Banda elástica de resistencia', 'precio' => 12.50],
            5 => ['nombre' => 'Rodillo abdominal', 'precio' => 18.00],
        ];

        // Mostrar productos en la tabla
        foreach ($productos as $id => $producto) {
            echo "<tr>
                    <td>{$producto['nombre']}</td>
                    <td>\${$producto['precio']}</td>
                    <td><a href='agregar_al_carrito.php?id={$id}'>Agregar al carrito</a></td>
                  </tr>";
        }
        ?>
    </tbody>
</table>

<div style="text-align: center;">
    <a href="ver_carrito.php">Ver Carrito</a>
</div>

</body>
</html>
