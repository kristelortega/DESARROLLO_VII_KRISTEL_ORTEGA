<?php

interface Detalle {
    public function obtenerDetallesEspecificos(): string;
}

abstract class Entrada implements Detalle {
    public $id;
    public $fecha_creacion;
    public $tipo;
    public $titulo;
    public $descripcion;

    public function __construct($datos = []) {
        foreach ($datos as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }
    abstract public function obtenerDetallesEspecificos(): string;
}

class GestorBlog {
    private $entradas = [];

    public function cargarEntradas() {
        if (file_exists('blog.json')) {
            $json = file_get_contents('blog.json');
            $data = json_decode($json, true);
            foreach ($data as $entradaData) {
                $this->entradas[] = new Entrada($entradaData);
            }
        }
    }

    public function guardarEntradas() {
        $data = array_map(function($entrada) {
            return get_object_vars($entrada);
        }, $this->entradas);
        file_put_contents('blog.json', json_encode($data, JSON_PRETTY_PRINT));
    }

    public function obtenerEntradas() {
        return $this->entradas;
    }

    public function agregarEntrada(Entrada $entrada){
        $this-> entradas[] = $entrada;
    }
    public function eliminarEntrada(Entrada $entrada){
        foreach ($this -> entradas as $key => $entrada) {
            if($entrada-> id == $entrada){
                unset($this -> entradas[$key]);
                break;
            }
        }
    }
    
}   

 class EntradaUnaColumna extends Entrada{
    public $titulo;
    public $descricion;

  public function obtenerDetallesEspecificos(): string{
    return "Entrada de una columna: $this->titulo";
  }
    

}

class EntradaDosColumnas extends Entrada {
    public $titulo1;
    public $descripcion1;
    public $titulo2;
    public $descripcion2;

    public function obtenerDetallesEspecificos(): string{
        return "Entrada de 2 columnas: $this->titulo1" + " $this->titulo2";
      }

}

class EntradaTresColumnas extends Entrada{
 public $titulo1;
 public $descripcion1;
 public $titulo2;
 public $descripcion2;
 public $titulo3;

 public $descripcion3;

 public function obtenerDetallesEspecificos(): string{
    return "Entrada de 2 columnas: $this->titulo1" + " $this->titulo2" + "$this->titulo3";
  }

}

