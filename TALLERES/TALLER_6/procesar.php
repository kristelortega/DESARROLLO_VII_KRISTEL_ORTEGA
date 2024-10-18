<?php
require_once 'validaciones.php';
require_once 'sanitizacion.php';

// Procesar datos al enviar el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errores = [];
    $datos = [];

    // Procesar y validar cada campo
    $campos = ['nombre', 'email', 'sitio_web', 'genero', 'intereses', 'comentarios', 'fecha_nacimiento'];
    foreach ($campos as $campo) {
        if (isset($_POST[$campo])) {
            $valor = $_POST[$campo];
            $valorSanitizado = call_user_func("sanitizar" . ucfirst(str_replace('_', '', $campo)), $valor);
            $datos[$campo] = $valorSanitizado;

            if (!call_user_func("validar" . ucfirst(str_replace('_', '', $campo)), $valorSanitizado)) {
                $errores[] = "El campo $campo no es válido.";
            }
        } else {
            // Si no está definido, inicializa el campo
            if ($campo === 'intereses') {
                $datos[$campo] = []; // Inicializa como un array vacío
            }
        }
    }

    // Calcular edad
    if (isset($datos['fecha_nacimiento'])) {
        $fechaNacimiento = new DateTime($datos['fecha_nacimiento']);
        $hoy = new DateTime();
        $edad = $hoy->diff($fechaNacimiento)->y;
        $datos['edad'] = $edad;
    }

    // Procesar la foto de perfil
    if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] !== UPLOAD_ERR_NO_FILE) {
        if (!validarFotoPerfil($_FILES['foto_perfil'])) {
            $errores[] = "La foto de perfil no es válida.";
        } else {
            $nombreArchivo = basename($_FILES['foto_perfil']['name']);
            $rutaDestino = 'uploads/' . uniqid() . '_' . $nombreArchivo; // Nombre único

            if (move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $rutaDestino)) {
                $datos['foto_perfil'] = $rutaDestino;
            } else {
                $errores[] = "Hubo un error al subir la foto de perfil.";
            }
        }
    }

    // Almacenar los datos en un archivo JSON
    if (empty($errores)) {
        // Cargar datos existentes
        $archivoJSON = 'registros.json';
        if (file_exists($archivoJSON)) {
            $datosExistentes = json_decode(file_get_contents($archivoJSON), true);
        } else {
            $datosExistentes = [];
        }

        // Añadir el nuevo registro
        $datosExistentes[] = $datos;

        // Guardar los datos actualizados en el archivo JSON
        file_put_contents($archivoJSON, json_encode($datosExistentes, JSON_PRETTY_PRINT));
    }

    // Mostrar resultados o errores
    if (empty($errores)) {
        echo "<h2>Datos Recibidos:</h2>";
        echo "<table border='1'>";
        foreach ($datos as $campo => $valor) {
            echo "<tr>";
            echo "<th>" . ucfirst($campo) . "</th>";
            if ($campo === 'intereses') {
                echo "<td>" . implode(", ", $valor) . "</td>";
            } elseif ($campo === 'foto_perfil') {
                echo "<td><img src='$valor' width='100'></td>";
            } else {
                echo "<td>$valor</td>";
            }
            echo "</tr>";
        }
        echo "</table>";

        // Enlace al resumen
        echo "<br><a href='resumen.php'>Ver todos los registros</a>";
        echo "<br><a href='formulario.html'>Volver al formulario</a>";
    } else {
        echo "<h2>Errores:</h2>";
        echo "<ul>";
        foreach ($errores as $error) {
            echo "<li>$error</li>";
        }
        echo "</ul>";
        echo "<br><a href='formulario.html'>Volver al formulario</a>";
    }
} else {
    echo "Acceso no permitido.";
}
?>
