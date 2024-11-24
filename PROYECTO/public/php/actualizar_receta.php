<?php
session_start();
require_once __DIR__ . '/../../vendor/autoload.php';

use Controllers\RecetaController;

if (!isset($_SESSION['user'])) {
    header('Location: ../../index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $titulo = trim($_POST['titulo']);
    $descripcion = trim($_POST['descripcion']);
    $tiempo_preparacion = intval($_POST['tiempo_preparacion']);
    $ingredientes = $_POST['ingredientes'];
    $usuario_id = $_SESSION['user']['id'];

    // Manejo de la imagen
    $imagenNombre = null;
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../uploads/';
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
        $recetaController = new RecetaController();

        // Actualizar receta
        $recetaController->actualizar($id, $titulo, $descripcion, $tiempo_preparacion, $usuario_id, $imagenNombre);

        // Actualizar ingredientes
        $recetaController->actualizarIngredientes($id, $ingredientes);

        header('Location: detalle_receta.php?id=' . $id . '&success=receta_actualizada');
    } catch (Exception $e) {
        error_log("Error al actualizar la receta: " . $e->getMessage());
        echo "Ocurrió un error al actualizar la receta.";
    }
} else {
    header('Location: index.php');
    exit;
}
