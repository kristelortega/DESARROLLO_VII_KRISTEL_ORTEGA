<?php
namespace Controllers;

require_once __DIR__ . '/../../vendor/autoload.php';

USE Models\Ingrediente;

class ingredienteController{
    private $ingrediente;

    public function __construct(){
        $dbController = new DatabaseController();
        $dbConnection = $dbController->getConnection();
        $this->ingrediente = new Ingrediente($dbConnection);
    }

    public function obtenerDetalles($id){
        return $this->ingrediente->obtenerDetalles($id);
    }

    public function obtenerIngredientes($id){
        return $this->ingrediente->obtenerIngredientes($id);
    }
}