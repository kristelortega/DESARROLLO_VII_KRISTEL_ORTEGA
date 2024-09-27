<?php
require_once 'RecursoBiblioteca.php';

class Revista extends RecursoBiblioteca {
    private $numeroEdicion;

    public function __construct($id, $titulo, $estado, $numeroEdicion) {
        parent::__construct($id, $titulo, $estado);
        $this->numeroEdicion = $numeroEdicion;
    }

    public function obtenerDetallesPrestamo(): string {
        return "Revista: {$this->titulo}, Edición: {$this->numeroEdicion}, Estado: {$this->estado}";
    }
}
?>