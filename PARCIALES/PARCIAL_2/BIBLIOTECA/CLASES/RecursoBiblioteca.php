<?php
require_once 'Prestable.php';

abstract class RecursoBiblioteca implements Prestable {
    protected $id;
    protected $titulo;
    protected $estado;

    public function __construct($id, $titulo, $estado) {
        $this->id = $id;
        $this->titulo = $titulo;
        $this->estado = $estado;
    }

    abstract public function obtenerDetallesPrestamo(): string;
}
?>