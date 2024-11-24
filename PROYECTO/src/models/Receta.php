<?php
// src/models/Receta.php
namespace models;
use PDO;
class Receta {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function obtenerTodas() {
        $stmt = $this->pdo->query("SELECT recetas.id, recetas.titulo, recetas.descripcion, recetas.imagen, usuarios.nombre AS autor 
                                   FROM recetas 
                                   JOIN usuarios ON recetas.usuario_id = usuarios.id 
                                   ORDER BY recetas.creado_en DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para obtener recetas basadas en una consulta de búsqueda
    public function obtenerTodasPorQuery($query) {
        $stmt = $this->pdo->prepare("SELECT recetas.id, recetas.titulo, recetas.descripcion, recetas.imagen, usuarios.nombre AS autor 
                                   FROM recetas 
                                   JOIN usuarios ON recetas.usuario_id = usuarios.id 
                                   WHERE recetas.titulo LIKE :query OR recetas.descripcion LIKE :query
                                   ORDER BY recetas.creado_en DESC");
        $stmt->execute(['query' => "%$query%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM recetas WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function crear($usuario_id, $titulo, $descripcion, $tiempo_preparacion, $imagen) {
        $stmt = $this->pdo->prepare("INSERT INTO recetas (usuario_id, titulo, descripcion, tiempo_preparacion, imagen) 
                                     VALUES (:usuario_id, :titulo, :descripcion, :tiempo_preparacion, :imagen)");
        $stmt->execute([
            'usuario_id' => $usuario_id,
            'titulo' => $titulo,
            'descripcion' => $descripcion,
            'tiempo_preparacion' => $tiempo_preparacion,
            'imagen' => $imagen
        ]);
        return $this->pdo->lastInsertId();
    }
    public function agregarIngrediente($receta_id, $nombre, $cantidad) {
        $stmt = $this->pdo->prepare("INSERT INTO ingredientes (receta_id, nombre, cantidad) 
                                 VALUES (:receta_id, :nombre, :cantidad)");
        $stmt->execute([
            'receta_id' => $receta_id,
            'nombre' => $nombre,
            'cantidad' => $cantidad
        ]);
    }




    public function actualizar($id, $titulo, $descripcion, $tiempo_preparacion, $usuario_id, $imagen = null) {
        if ($imagen) {
            $stmt = $this->pdo->prepare("UPDATE recetas SET titulo = :titulo, descripcion = :descripcion, 
                                     tiempo_preparacion = :tiempo_preparacion, imagen = :imagen 
                                     WHERE id = :id AND usuario_id = :usuario_id");
            $stmt->execute([
                'titulo' => $titulo,
                'descripcion' => $descripcion,
                'tiempo_preparacion' => $tiempo_preparacion,
                'imagen' => $imagen,
                'id' => $id,
                'usuario_id' => $usuario_id
            ]);
        } else {
            $stmt = $this->pdo->prepare("UPDATE recetas SET titulo = :titulo, descripcion = :descripcion, 
                                     tiempo_preparacion = :tiempo_preparacion 
                                     WHERE id = :id AND usuario_id = :usuario_id");
            $stmt->execute([
                'titulo' => $titulo,
                'descripcion' => $descripcion,
                'tiempo_preparacion' => $tiempo_preparacion,
                'id' => $id,
                'usuario_id' => $usuario_id
            ]);
        }
    }

    public function actualizarIngredientes($receta_id, $ingredientes) {
        // Eliminar los ingredientes existentes
        $stmtEliminar = $this->pdo->prepare("DELETE FROM ingredientes WHERE receta_id = :receta_id");
        $stmtEliminar->execute(['receta_id' => $receta_id]);

        // Insertar nuevos ingredientes
        $stmtIngrediente = $this->pdo->prepare("INSERT INTO ingredientes (receta_id, nombre, cantidad) 
                                            VALUES (:receta_id, :nombre, :cantidad)");
        foreach ($ingredientes['nombre'] as $index => $nombre) {
            $cantidad = $ingredientes['cantidad'][$index];
            $stmtIngrediente->execute([
                'receta_id' => $receta_id,
                'nombre' => trim($nombre),
                'cantidad' => trim($cantidad)
            ]);
        }
    }
    public function eliminar($id) {
        $stmt = $this->pdo->prepare("DELETE FROM recetas WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
}
