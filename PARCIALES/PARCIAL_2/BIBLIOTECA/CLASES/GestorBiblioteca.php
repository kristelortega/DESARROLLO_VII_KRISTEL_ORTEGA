<?php
require_once 'Libro.php';
require_once 'Revista.php';
require_once 'DVD.php';

class GestorBiblioteca {
    private $recursos = [];

    public function cargarRecursosDesdeJSON($archivo) {
        $datos = json_decode(file_get_contents($archivo), true);
        foreach ($datos as $dato) {
            switch ($dato['tipo']) {
                case 'libro':
                    $this->recursos[] = new Libro($dato['id'], $dato['titulo'], $dato['estado'], $dato['isbn']);
                    break;
                case 'revista':
                    $this->recursos[] = new Revista($dato['id'], $dato['titulo'], $dato['estado'], $dato['numeroEdicion']);
                    break;
                case 'dvd':
                    $this->recursos[] = new DVD($dato['id'], $dato['titulo'], $dato['estado'], $dato['duracion']);
                    break;
            }
        }
    }

    public function agregarRecurso(RecursoBiblioteca $recurso) {
        $this->recursos[] = $recurso;
    }

    public function eliminarRecurso($id) {
        foreach ($this->recursos as $key => $recurso) {
            if ($recurso->id == $id) {
                unset($this->recursos[$key]);
                break;
            }
        }
    }

    public function actualizarRecurso(RecursoBiblioteca $recursoActualizado) {
        foreach ($this->recursos as $key => $recurso) {
            if ($recurso->id == $recursoActualizado->id) {
                $this->recursos[$key] = $recursoActualizado;
                break;
            }
        }
    }

    public function actualizarEstadoRecurso($id, $nuevoEstado) {
        foreach ($this->recursos as $recurso) {
            if ($recurso->id == $id) {
                $recurso->estado = $nuevoEstado;
                break;
            }
        }
    }

    public function buscarRecursosPorEstado($estado) {
        return array_filter($this->recursos, function($recurso) use ($estado) {
            return $recurso->estado == $estado;
        });
    }

    public function listarRecursos($filtroEstado = '', $campoOrden = 'id', $direccionOrden = 'ASC') {
        $lista = $this->recursos;
        if ($filtroEstado) {
            $lista = $this->buscarRecursosPorEstado($filtroEstado);
        }
        usort($lista, function($a, $b) use ($campoOrden, $direccionOrden) {
            if ($direccionOrden === 'ASC') {
                return $a->$campoOrden <=> $b->$campoOrden;
            } else {
                return $b->$campoOrden <=> $a->$campoOrden;
            }
        });
        return $lista;
    }
}
?>