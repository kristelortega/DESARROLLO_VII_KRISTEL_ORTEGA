<?php
$archivoJSON = 'registros.json';

// Verificar si el archivo JSON existe y leer los datos
if (file_exists($archivoJSON)) {
    $datosRegistros = json_decode(file_get_contents($archivoJSON), true);
} else {
    $datosRegistros = [];
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resumen de Registros</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>Resumen de Registros</h2>

    <?php if (empty($datosRegistros)): ?>
        <p>No hay registros disponibles.</p>
    <?php else: ?>
        <table>
            <tr>
                <th>Nombre</th>
                <th>Email</th>
                <th>Edad</th>
                <th>Sitio Web</th>
                <th>Género</th>
                <th>Intereses</th>
                <th>Comentarios</th>
                <th>Foto de Perfil</th>
                <th>Fecha de Nacimiento</th>
            </tr>
            <?php foreach ($datosRegistros as $registro): ?>
                <tr>
                    <td><?php echo $registro['nombre']; ?></td>
                    <td><?php echo $registro['email']; ?></td>
                    <td><?php echo $registro['edad']; ?></td>
                    <td><?php echo $registro['sitio_web']; ?></td>
                    <td><?php echo $registro['genero']; ?></td>
                    <td><?php echo isset($registro['intereses']) && is_array($registro['intereses']) ? implode(", ", $registro['intereses']) : 'Ninguno'; ?></td>
                    <td><?php echo $registro['comentarios']; ?></td>
                    <td><img src="<?php echo $registro['foto_perfil']; ?>" width="100"></td>
                    <td><?php echo date('d-m-Y', strtotime($registro['fecha_nacimiento'])); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <br><a href='formulario.html'>Volver al formulario</a>
</body>
</html>

