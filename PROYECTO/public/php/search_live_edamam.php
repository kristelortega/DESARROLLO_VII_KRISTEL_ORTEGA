<?php
require_once __DIR__ . '/../../src/controllers/EdamamController.php';

use Controllers\EdamamController;

if (!isset($_GET['q']) || empty(trim($_GET['q']))) {
    echo json_encode([]);
    exit;
}

$query = trim($_GET['q']);
$edamamController = new EdamamController();

try {
    // Buscar recetas en la API de Edamam
    $results = $edamamController->buscarRecetas($query, 0, 5);

    // Procesar los datos para simplificar el formato de respuesta
    $formattedResults = array_map(function ($hit) {
        return [
            'label' => $hit['recipe']['label'],
            'image' => $hit['recipe']['image'],
            'uri' => $hit['recipe']['uri']
        ];
    }, $results['hits']);

    echo json_encode($formattedResults);
} catch (Exception $e) {
    error_log("Error en búsqueda en vivo de Edamam: " . $e->getMessage());
    echo json_encode([]);
}
