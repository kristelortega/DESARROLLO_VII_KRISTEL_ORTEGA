<?php

// Clase Estudiante
class Estudiante {
    private $id;
    private $nombre;
    private $edad;
    private $carrera;
    private $materias;

    public function __construct($id, $nombre, $edad, $carrera) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->edad = $edad;
        $this->carrera = $carrera;
        $this->materias = [];
    }

    public function agregarMateria($materia, $calificacion): void {
        $this->materias[$materia] = $calificacion;
    }

    public function obtenerPromedio(): float {
        $total = array_sum($this->materias);
        $cantidadMaterias = count($this->materias);
        return $cantidadMaterias > 0 ? $total / $cantidadMaterias : 0;
    }

    public function obtenerDetalles(): array {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'edad' => $this->edad,
            'carrera' => $this->carrera,
            'materias' => $this->materias,
            'promedio' => $this->obtenerPromedio()
        ];
    }

    public function __toString(): string {
        return "ID: {$this->id}, Nombre: {$this->nombre}, Carrera: {$this->carrera}, Promedio: {$this->obtenerPromedio()}";
    }
}

// Clase SistemaGestionEstudiantes
class SistemaGestionEstudiantes {
    private $estudiantes = [];
    private $graduados = [];

    public function agregarEstudiante(Estudiante $estudiante): void {
        $this->estudiantes[$estudiante->obtenerDetalles()['id']] = $estudiante;
    }

    public function obtenerEstudiante($id): ?Estudiante {
        return $this->estudiantes[$id] ?? null;
    }

    public function listarEstudiantes(): array {
        return $this->estudiantes;
    }

    public function calcularPromedioGeneral(): float {
        $totalPromedio = array_reduce($this->estudiantes, function ($carry, $estudiante) {
            return $carry + $estudiante->obtenerPromedio();
        }, 0);
        return count($this->estudiantes) > 0 ? $totalPromedio / count($this->estudiantes) : 0;
    }

    public function obtenerEstudiantesPorCarrera($carrera): array {
        return array_filter($this->estudiantes, function ($estudiante) use ($carrera) {
            return $estudiante->obtenerDetalles()['carrera'] === $carrera;
        });
    }

    public function obtenerMejorEstudiante(): ?Estudiante {
        return array_reduce($this->estudiantes, function ($mejor, $estudiante) {
            return $mejor === null || $estudiante->obtenerPromedio() > $mejor->obtenerPromedio() ? $estudiante : $mejor;
        }, null);
    }

    public function generarReporteRendimiento(): array {
        $reporte = [];
        foreach ($this->estudiantes as $estudiante) {
            foreach ($estudiante->obtenerDetalles()['materias'] as $materia => $calificacion) {
                if (!isset($reporte[$materia])) {
                    $reporte[$materia] = [
                        'total' => 0, 'contador' => 0, 'max' => $calificacion, 'min' => $calificacion
                    ];
                }
                $reporte[$materia]['total'] += $calificacion;
                $reporte[$materia]['contador']++;
                $reporte[$materia]['max'] = max($reporte[$materia]['max'], $calificacion);
                $reporte[$materia]['min'] = min($reporte[$materia]['min'], $calificacion);
            }
        }
        foreach ($reporte as &$datos) {
            $datos['promedio'] = $datos['total'] / $datos['contador'];
        }
        return $reporte;
    }

    public function graduarEstudiante($id): void {
        if (isset($this->estudiantes[$id])) {
            $this->graduados[$id] = $this->estudiantes[$id];
            unset($this->estudiantes[$id]);
        }
    }

    public function generarRanking(): array {
        usort($this->estudiantes, function ($a, $b) {
            return $b->obtenerPromedio() <=> $a->obtenerPromedio();
        });
        return $this->estudiantes;
    }

    public function buscarEstudiantes($termino): array {
        $termino = strtolower($termino);
        return array_filter($this->estudiantes, function ($estudiante) use ($termino) {
            $detalles = $estudiante->obtenerDetalles();
            return strpos(strtolower($detalles['nombre']), $termino) !== false || 
                   strpos(strtolower($detalles['carrera']), $termino) !== false;
        });
    }

    public function estadisticasPorCarrera(): array {
        $estadisticas = [];
        foreach ($this->estudiantes as $estudiante) {
            $carrera = $estudiante->obtenerDetalles()['carrera'];
            if (!isset($estadisticas[$carrera])) {
                $estadisticas[$carrera] = [
                    'cantidad' => 0, 
                    'promedio' => 0, 
                    'mejor_estudiante' => null
                ];
            }
            $estadisticas[$carrera]['cantidad']++;
            $estadisticas[$carrera]['promedio'] += $estudiante->obtenerPromedio();
            if ($estadisticas[$carrera]['mejor_estudiante'] === null || 
                $estudiante->obtenerPromedio() > $estadisticas[$carrera]['mejor_estudiante']->obtenerPromedio()) {
                $estadisticas[$carrera]['mejor_estudiante'] = $estudiante;
            }
        }
        foreach ($estadisticas as &$datos) {
            $datos['promedio'] /= $datos['cantidad'];
        }
        return $estadisticas;
    }
}

// Sección de prueba

$sistema = new SistemaGestionEstudiantes();

// Crear estudiantes
$est1 = new Estudiante(1, 'Juan Perez', 20, 'Ingeniería');
$est1->agregarMateria('Matemáticas', 85);
$est1->agregarMateria('Física', 90);

$est2 = new Estudiante(2, 'Ana Gómez', 22, 'Derecho');
$est2->agregarMateria('Derecho Penal', 95);
$est2->agregarMateria('Derecho Civil', 89);

$est3 = new Estudiante(3, 'Luis Martínez', 21, 'Medicina');
$est3->agregarMateria('Anatomía', 88);
$est3->agregarMateria('Fisiología', 92);

$est4 = new Estudiante(4, 'Clara Rojas', 23, 'Ingeniería');
$est4->agregarMateria('Química', 91);
$est4->agregarMateria('Matemáticas', 86);

// Agregar estudiantes al sistema
$sistema->agregarEstudiante($est1);
$sistema->agregarEstudiante($est2);
$sistema->agregarEstudiante($est3);
$sistema->agregarEstudiante($est4);

// Listar estudiantes
echo "Listado de estudiantes:\n";
print_r($sistema->listarEstudiantes());

// Buscar estudiantes por carrera
echo "Estudiantes en Ingeniería:\n";
print_r($sistema->buscarEstudiantes('Ingeniería'));

// Calcular promedio general del sistema
echo "Promedio General: " . $sistema->calcularPromedioGeneral() . PHP_EOL;

// Graduar un estudiante
$sistema->graduarEstudiante(1);

// Generar ranking de estudiantes
echo "Ranking de estudiantes:\n";
print_r($sistema->generarRanking());

// Generar reporte de rendimiento por materia
echo "Reporte de rendimiento:\n";
print_r($sistema->generarReporteRendimiento());

// Estadísticas por carrera
echo "Estadísticas por carrera:\n";
print_r($sistema->estadisticasPorCarrera());

?>
