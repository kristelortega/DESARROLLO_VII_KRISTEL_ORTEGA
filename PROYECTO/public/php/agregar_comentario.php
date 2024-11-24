<?php
// agregar_comentario.php
session_start();
require_once __DIR__ . '/../../vendor/autoload.php';

$comentarioController = new Controllers\ComentarioController();

if (!isset($_SESSION['user'])) {
    header('Location: ../../index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $receta_id = intval($_POST['receta_id']);
    $comentario = trim($_POST['comentario']);
    $calificacion = intval($_POST['calificacion']);

    if ($calificacion < 1 || $calificacion > 5) {
        echo "Calificación inválida.";
        exit;
    }

    try {
        $comentarioController->agregarComentario($receta_id,$_SESSION['user']['google_id'], $comentario, $calificacion);
        header('Location: detalle_receta.php?id=' . $receta_id);
    } catch (PDOException $e) {
        error_log("Error al agregar comentario: " . $e->getMessage());
        echo "Ocurrió un error al agregar el comentario.";
    }
} else {
    header('Location: ../index.php');
    exit;
}

