<?php
require_once 'Empleado.php';
require_once 'Evaluable.php';

class Gerente extends Empleado implements Evaluable {
    private $departamento;
    private $bono;

    public function __construct($nombre, $idEmpleado, $salarioBase, $departamento) {
        parent::__construct($nombre, $idEmpleado, $salarioBase);
        $this->departamento = $departamento;
        $this->bono = 0;
    }

    public function getDepartamento() {
        return $this->departamento;
    }

    public function setDepartamento($departamento) {
        $this->departamento = $departamento;
    }

    // Método para asignar un bono
    public function asignarBono($bono) {
        $this->bono = $bono;
    }

    public function obtenerSalarioTotal() {
        return $this->getSalarioBase() + $this->bono;
    }

    // Implementación del método evaluarDesempenio de la interfaz Evaluable
    public function evaluarDesempenio() {
        return "Evaluación del Gerente: Gestión eficiente del departamento $this->departamento.";
    }

    public function obtenerInformacion() {
        return parent::obtenerInformacion() . ", Departamento: $this->departamento, Bono: $this->bono";
    }
}
?>
