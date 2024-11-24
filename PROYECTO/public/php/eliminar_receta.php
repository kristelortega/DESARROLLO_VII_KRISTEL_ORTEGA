<?php
// eliminar_receta.php
session_start();
require_once __DIR__ . "/../../vendor/autoload.php";
$dbController = new Controllers\DatabaseController();

if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $pdo = $dbController->getConnection();

    try {
        // Obtener la imagen para eliminarla del servidor
        $stmtImagen = $pdo->prepare("SELECT imagen FROM recetas WHERE id = :id AND usuario_id = :usuario_id");
        $stmtImagen->execute([
            'id' => $id,
            'usuario_id' => $_SESSION['user']['id']
        ]);
        $imagen = $stmtImagen->fetchColumn();

        // Eliminar la receta (esto también eliminará los ingredientes y comentarios por ON DELETE CASCADE)
        $stmt = $pdo->prepare("DELETE FROM recetas WHERE id = :id AND usuario_id = :usuario_id");
        $stmt->execute([
            'id' => $id,
            'usuario_id' => $_SESSION['user']['id']
        ]);

        // Eliminar la imagen del servidor
        if ($imagen) {
            $imagenRuta = __DIR__ . '/../uploads/' . $imagen;
            if (file_exists($imagenRuta)) {
                unlink($imagenRuta);
            }
        }

        header('Location: recetas.php?success=receta_eliminada');
    } catch (PDOException $e) {
        error_log("Error al eliminar la receta: " . $e->getMessage());
        echo "Ocurrió un error al eliminar la receta.";
    }
} else {
    header('Location: ../index.php');
    exit;
}
?>
