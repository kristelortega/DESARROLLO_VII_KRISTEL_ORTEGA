<?php
require_once 'RecursoBiblioteca.php';

class DVD extends RecursoBiblioteca {
    private $duracion;

    public function __construct($id, $titulo, $estado, $duracion) {
        parent::__construct($id, $titulo, $estado);
        $this->duracion = $duracion;
    }

    public function obtenerDetallesPrestamo(): string {
        return "DVD: {$this->titulo}, Duración: {$this->duracion} minutos, Estado: {$this->estado}";
    }
}
?>