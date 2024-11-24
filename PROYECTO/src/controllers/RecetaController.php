<?php namespace Controllers;
require_once __DIR__ . '/../models/Receta.php';

use Models\Receta;

class RecetaController
{
    private $recetaModel;

    public function __construct()
    {
        $dbController = new DatabaseController();
        $dbConnection = $dbController->getConnection();
        $this->recetaModel = new Receta($dbConnection);
    }

    public function obtenerTodas()
    {
        return $this->recetaModel->obtenerTodas();
    }

    public function obtenerTodasPorQuery($query)
    {
        return $this->recetaModel->obtenerTodasPorQuery($query);
    }

    public function obtenerPorId($id)
    {
        return $this->recetaModel->obtenerPorId($id);
    }

    public function crear($usuario_id, $titulo, $descripcion, $tiempo_preparacion, $imagen)
    {
        return $this->recetaModel->crear($usuario_id, $titulo, $descripcion, $tiempo_preparacion, $imagen);
    }

    public function agregarIngrediente($receta_id, $nombre, $cantidad) {
        $this->recetaModel->agregarIngrediente($receta_id, $nombre, $cantidad);
    }


    public function actualizar($id, $titulo, $descripcion, $tiempo_preparacion, $usuario_id, $imagen = null) {
        $this->recetaModel->actualizar($id, $titulo, $descripcion, $tiempo_preparacion, $usuario_id, $imagen);
    }

    public function actualizarIngredientes($receta_id, $ingredientes) {
        $this->recetaModel->actualizarIngredientes($receta_id, $ingredientes);
    }


    public function eliminar($id)
    {
        return $this->recetaModel->eliminar($id);
    }

    public function obtenerTodasPaginadas($paginaActual, $resultadosPorPagina)
    {
        $offset = ($paginaActual - 1) * $resultadosPorPagina;
        return $this->recetaModel->obtenerTodasPaginadas($offset, $resultadosPorPagina);
    }

}
