<?php

namespace Controllers;

require_once __DIR__ . '/../models/Edamam.php';

use Models\Edamam;

class EdamamController
{
    private $edamam;

    public function __construct()
    {
        $this->edamam = new Edamam(); // Instancia del modelo
    }

    public function buscarRecetas($query, $from = 0, $to = 10)
    {
        // Llama al modelo para buscar recetas
        return $this->edamam->buscarRecetas($query, $from, $to);
    }

    public function obtenerDetalleReceta($uri)
    {
        return $this->edamam->obtenerDetalleReceta($uri);
    }

    public function obtenerRecetasIniciales($from = 0, $to = 10)
    {
        return $this->edamam->obtenerRecetasIniciales($from, $to);
    }


}
