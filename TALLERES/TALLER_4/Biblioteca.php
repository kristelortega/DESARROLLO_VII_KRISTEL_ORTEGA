<?php
require_once 'Libro.php';
require_once 'LibroDigital.php';

class Biblioteca {
    private $libros = [];

    public function agregarLibro(Prestable $libro) {
        $this->libros[] = $libro;
    }

    public function listarLibros() {
        foreach ($this->libros as $libro) {
            echo $libro->obtenerInformacion() . "\n";
            echo "Disponible: " . ($libro->estaDisponible() ? "Sí" : "No") . "\n\n";
        }
    }

    public function prestarLibro($titulo) {
        foreach ($this->libros as $libro) {
            if ($libro->getTitulo() === $titulo && $libro->estaDisponible()) {
                $libro->prestar();
                return true;
            }
        }
        return false;
    }

    public function devolverLibro($titulo) {
        foreach ($this->libros as $libro) {
            if ($libro->getTitulo() === $titulo && !$libro->estaDisponible()) {
                $libro->devolver();
                return true;
            }
        }
        return false;
    }
}

 
?>