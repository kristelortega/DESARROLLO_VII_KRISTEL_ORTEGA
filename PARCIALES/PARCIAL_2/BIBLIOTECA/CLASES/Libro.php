<?php
require_once 'RecursoBiblioteca.php';

class Libro extends RecursoBiblioteca {
    private $isbn;

    public function __construct($id, $titulo, $estado, $isbn) {
        parent::__construct($id, $titulo, $estado);
        $this->isbn = $isbn;
    }

    public function obtenerDetallesPrestamo(): string {
        return "Libro: {$this->titulo}, ISBN: {$this->isbn}, Estado: {$this->estado}";
    }
}
?>