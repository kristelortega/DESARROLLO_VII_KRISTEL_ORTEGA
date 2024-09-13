<?php
class Empresa {
    private $empleados = [];

    // Método para agregar empleados
    public function agregarEmpleado(Empleado $empleado) {
        $this->empleados[] = $empleado;
    }

    // Método para listar todos los empleados
    public function listarEmpleados() {
        foreach ($this->empleados as $empleado) {
            echo $empleado->obtenerInformacion() . "\n";
        }
    }

    // Método para calcular la nómina total
    public function calcularNominaTotal() {
        $nominaTotal = 0;
        foreach ($this->empleados as $empleado) {
            // Si el empleado es un gerente, se considera el salario total (incluido el bono)
            if ($empleado instanceof Gerente) {
                $nominaTotal += $empleado->obtenerSalarioTotal();
            } else {
                $nominaTotal += $empleado->getSalarioBase();
            }
        }
        return $nominaTotal;
    }

    // Método para realizar evaluaciones de desempeño
    public function realizarEvaluaciones() {
        foreach ($this->empleados as $empleado) {
            if ($empleado instanceof Evaluable) {
                echo $empleado->evaluarDesempenio() . "\n";
            } else {
                echo "Este empleado no es evaluable.\n";
            }
        }
    }
}
?>
