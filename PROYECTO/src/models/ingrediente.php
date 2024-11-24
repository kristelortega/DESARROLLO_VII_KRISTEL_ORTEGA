<?php
namespace models;

require_once __DIR__ . '/../../vendor/autoload.php';

use PDO;
class Ingrediente {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function obtenerPorReceta($receta_id) {
        $stmt = $this->pdo->prepare("SELECT nombre, cantidad FROM ingredientes WHERE receta_id = :receta_id");
        $stmt->execute(['receta_id' => $receta_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerDetalles($user_id){
        $stmt = $this->pdo->prepare("SELECT recetas.*, usuarios.nombre AS autor 
                           FROM recetas 
                           JOIN usuarios ON recetas.usuario_id = usuarios.id 
                           WHERE recetas.id = :id");
        $stmt->execute(['id' => $user_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function obtenerIngredientes($id){
        $stmt = $this->pdo->prepare("SELECT nombre, cantidad FROM ingredientes WHERE receta_id = :receta_id");
        $stmt->execute(['receta_id' => $id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function crear($receta_id, $nombre, $cantidad) {
        $stmt = $this->pdo->prepare("INSERT INTO ingredientes (receta_id, nombre, cantidad) 
                                     VALUES (:receta_id, :nombre, :cantidad)");
        $stmt->execute([
            'receta_id' => $receta_id,
            'nombre' => $nombre,
            'cantidad' => $cantidad
        ]);
    }

    public function eliminarPorReceta($receta_id) {
        $stmt = $this->pdo->prepare("DELETE FROM ingredientes WHERE receta_id = :receta_id");
        $stmt->execute(['receta_id' => $receta_id]);
    }
}
