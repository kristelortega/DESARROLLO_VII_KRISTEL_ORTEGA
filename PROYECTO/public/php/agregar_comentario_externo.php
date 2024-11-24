<?php
session_start();
require_once __DIR__ . '/../../vendor/autoload.php';

$comentarioController = new Controllers\ComentarioController();

if (!isset($_SESSION['user'])) {
    header('Location: ../../index.php');
    exit;
}
$uri = isset($_POST['id']) ? $_POST['id'] : null;
$usuario_id = $_SESSION['user']['google_id'];
$receta_url = isset($_POST['receta_url']) ? $_POST['receta_url'] : null;
$comentario = isset($_POST['comentario']) ? $_POST['comentario'] : '';
$calificacion = isset($_POST['calificacion']) ? intval($_POST['calificacion']) : 0;

if (!$receta_url || !$comentario || $calificacion < 1 || $calificacion > 5) {
    echo "Datos inválidos.";
    exit;
}

try {
    $comentarioController->agregarComentarioExterno($usuario_id, $comentario, $calificacion, $receta_url);

    echo "Comentario agregado.";
    header("Location: detalle_receta_externa.php?id=" . $uri);
    exit;
} catch (PDOException $e) {
    error_log("Error al agregar comentario: " . $e->getMessage());
    echo "Error al agregar comentario.";
}
