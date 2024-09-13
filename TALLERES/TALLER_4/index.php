<?php
require_once 'Gerente.php';
require_once 'Desarrollador.php';
require_once 'Empresa.php';

// Crear una instancia de la empresa
$empresa = new Empresa();

// Crear empleados
$gerente1 = new Gerente("Carlos", 1001, 5000, "Ventas");
$gerente1->asignarBono(1000);

$desarrollador1 = new Desarrollador("Ana", 1002, 4000, "PHP", 5);
$desarrollador2 = new Desarrollador("Luis", 1003, 4200, "Java", 3);

// Agregar empleados a la empresa
$empresa->agregarEmpleado($gerente1);
$empresa->agregarEmpleado($desarrollador1);
$empresa->agregarEmpleado($desarrollador2);

// Listar empleados
echo "Listado de Empleados:\n";
$empresa->listarEmpleados();

// Calcular nómina total
echo "\nNómina Total: " . $empresa->calcularNominaTotal() . "\n";

// Realizar evaluaciones de desempeño
echo "\nEvaluaciones de Desempeño:\n";
$empresa->realizarEvaluaciones();

?>