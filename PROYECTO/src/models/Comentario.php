<?php

namespace Models;

use PDO;

class Comentario
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function findAllByRecetaId($receta_id)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT comentarios.*, usuarios.nombre 
                                      FROM comentarios 
                                      JOIN usuarios ON comentarios.usuario_id = usuarios.id 
                                      WHERE comentarios.receta_id = :receta_id 
                                      ORDER BY comentarios.creado_en DESC");
            $stmt->execute(['receta_id' => $receta_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error al obtener comentarios: " . $e->getMessage());
            return [];
        }
    }

    public function agregarComentarioExterno($usuario_id, $comentario, $calificacion, $receta_url)
    {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO comentarios (usuario_id, comentario, calificacion, receta_url) 
                           VALUES (:usuario_id, :comentario, :calificacion, :receta_url)");
            $stmt->execute([
                'usuario_id' => $usuario_id,
                'comentario' => $comentario,
                'calificacion' => $calificacion,
                'receta_url' => $receta_url,
            ]);
        } catch (Exception $e) {
            error_log("Error al obtener comentarios: " . $e->getMessage());
        }
    }

    public function findAllExternos($receta)
    {
        try {
            $recetaUrl = is_array($receta) ? $receta['url'] : $receta;

            $stmt = $this->pdo->prepare("SELECT comentarios.*, usuarios.nombre 
                                     FROM comentarios 
                                     JOIN usuarios ON comentarios.usuario_id = usuarios.id 
                                     WHERE comentarios.receta_url = :receta_url 
                                     ORDER BY comentarios.creado_en DESC");
            $stmt->execute(['receta_url' => $recetaUrl]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener comentarios: " . $e->getMessage());
            return [];
        }
    }


    public function create($receta_id, $usuario_id, $comentario, $calificacion)
    {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO comentarios (receta_id, usuario_id, comentario, calificacion) 
                VALUES (:receta_id, :usuario_id, :comentario, :calificacion)
            ");
            $stmt->execute([
                'receta_id' => $receta_id,
                'usuario_id' => $usuario_id,
                'comentario' => $comentario,
                'calificacion' => $calificacion
            ]);
            return true;
        } catch (Exception $e) {
            error_log("Error al crear comentario: " . $e->getMessage());
            return false;
        }
    }

    public function delete($id, $usuario_id)
    {
        try {
            $stmt = $this->pdo->prepare("
                DELETE FROM comentarios WHERE id = :id AND usuario_id = :usuario_id
            ");
            $stmt->execute([
                'id' => $id,
                'usuario_id' => $usuario_id
            ]);
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            error_log("Error al eliminar comentario: " . $e->getMessage());
            return false;
        }
    }

    public function deleteExterno($url, $usuario_id)
    {
        try {
            $stmt = $this->pdo->prepare("
                DELETE FROM comentarios WHERE receta_url = :url AND usuario_id = :usuario_id
            ");
            $stmt->execute([
                'url' => $url,
                'usuario_id' => $usuario_id
            ]);
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            error_log("Error al eliminar comentario: " . $e->getMessage());
            return false;
        }
    }
}
