<?php
session_start();
require_once __DIR__ . '/../../vendor/autoload.php';

use Controllers\RecetaController;

if (!isset($_SESSION['user'])) {
    header('Location: ../../index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener datos del formulario
    $titulo = trim($_POST['titulo']);
    $descripcion = trim($_POST['descripcion']);
    $tiempo_preparacion = intval($_POST['tiempo_preparacion']);
    $ingredientes = $_POST['ingredientes'];
    $usuario_id = $_SESSION['user']['google_id'];

    // Manejo de la imagen
    $imagenNombre = null;
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '../../src/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $imagenTmp = $_FILES['imagen']['tmp_name'];
        $imagenNombre = time() . '_' . basename($_FILES['imagen']['name']);
        $imagenRuta = $uploadDir . $imagenNombre;

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileType = mime_content_type($imagenTmp);
        if (in_array($fileType, $allowedTypes)) {
            move_uploaded_file($imagenTmp, $imagenRuta);
        } else {
            echo "Tipo de imagen no permitido.";
            exit;
        }
    }

    try {
        // Usar el controlador para manejar la lógica
        $recetaController = new RecetaController();
        $receta_id = $recetaController->crear($usuario_id, $titulo, $descripcion, $tiempo_preparacion, $imagenNombre);

        // Agregar ingredientes a la receta
        foreach ($ingredientes['nombre'] as $index => $nombre) {
            $cantidad = $ingredientes['cantidad'][$index];
            $recetaController->agregarIngrediente($receta_id, trim($nombre), trim($cantidad));
        }

        header('Location: ../../index.php?success=receta_creada');
    } catch (Exception $e) {
        error_log("Error al guardar la receta: " . $e->getMessage());
        echo "Ocurrió un error al guardar la receta.";
    }
} else {
    header('Location: crear_receta.php');
    exit;
}
