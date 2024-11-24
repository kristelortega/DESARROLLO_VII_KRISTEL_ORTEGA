<?php

namespace Controllers;

require_once __DIR__ . '/../../vendor/autoload.php';

use Models\Comentario;

class ComentarioController
{
    private $comentarioModel;

    public function __construct()
    {
        $dbController = new DatabaseController();
        $dbConnection = $dbController->getConnection();
        $this->comentarioModel = new Comentario($dbConnection);
    }

    public function listarComentarios($receta_id)
    {
        return $this->comentarioModel->findAllByRecetaId($receta_id);
    }

    public function listarComentariosExternos($receta_id){
        return $this->comentarioModel->findAllExternos($receta_id);
    }
    public function agregarComentario($receta_id, $usuario_id, $comentario, $calificacion)
    {
        if (empty($comentario) || empty($calificacion) || $calificacion < 1 || $calificacion > 5) {
            return ['success' => false, 'message' => 'Datos inválidos'];
        }

        $resultado = $this->comentarioModel->create($receta_id, $usuario_id, $comentario, $calificacion);
        return $resultado
            ? ['success' => true, 'message' => 'Comentario agregado exitosamente']
            : ['success' => false, 'message' => 'Error al agregar el comentario'];
    }

    public function agregarComentarioExterno($usuario_id, $comentario, $calificacion, $receta_url)
    {
        if (empty($comentario) || empty($calificacion) || $calificacion < 1 || $calificacion > 5) {
            return ['success' => false, 'message' => 'Datos inválidos'];
        }

        $resultado = $this->comentarioModel->agregarComentarioExterno($usuario_id, $comentario, $calificacion, $receta_url);
        return $resultado
            ? ['success' => true, 'message' => 'Comentario agregado exitosamente']
            : ['success' => false, 'message' => 'Error al agregar el comentario'];
    }

    public function eliminarComentario($id, $usuario_id)
    {
        $resultado = $this->comentarioModel->delete($id, $usuario_id);
        return $resultado
            ? ['success' => true, 'message' => 'Comentario eliminado exitosamente']
            : ['success' => false, 'message' => 'Error al eliminar el comentario o no tienes permisos'];
    }

    public function eliminarComentarioExterno($url, $usuario_id){
        $resultado = $this->comentarioModel->deleteExterno($url, $usuario_id);
        return $resultado
            ? ['success' => true, 'message' => 'Comentario eliminado exitosamente']
            : ['success' => false, 'message' => 'Error al eliminar el comentario o no tienes permisos'];
    }
}
