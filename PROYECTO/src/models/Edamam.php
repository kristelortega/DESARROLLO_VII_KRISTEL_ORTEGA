<?php

namespace Models;
require_once __DIR__ . '/../../config/config.php';

class Edamam
{
    private $appId;
    private $appKey;
    private $baseUrl;

    public function __construct()
    {
        $this->appId = $_ENV['EDAMAM_APP_ID'];
        $this->appKey = $_ENV['EDAMAM_API_KEY'];
        $this->baseUrl = 'https://api.edamam.com/search';
    }

    public function buscarRecetas($query, $from = 0, $to = 10)
    {
        $url = $this->baseUrl . "?q=" . urlencode($query) . "&app_id=" . $this->appId . "&app_key=" . $this->appKey . "&from=$from&to=" . ($from + $to - 1);

        try {
            $response = file_get_contents($url);

            if ($response === false) {
                // Manejar el error de la solicitud
                error_log("Error al obtener datos de la API de Edamam: " . error_get_last()['message']);
                return []; // Devolver un array vacío para evitar errores posteriores
            }

            return json_decode($response, true);
        } catch (\Exception $e) {
            error_log("Error al buscar recetas en Edamam: " . $e->getMessage());
            return [];
        }
    }
    public function obtenerDetalleReceta($uri)
    {
        // Formatea el URI para evitar problemas de encoding
        $uri = urlencode($uri);
        $url = $this->baseUrl . "?r=" . $uri . "&app_id=" . $this->appId . "&app_key=" . $this->appKey;

        try {
            $response = file_get_contents($url);
            $result = json_decode($response, true);
            return isset($result[0]) ? $result[0] : null; // Edamam devuelve un array con una receta si existe
        } catch (\Exception $e) {
            error_log("Error al obtener detalles de la receta: " . $e->getMessage());
            return null;
        }
    }
    public function obtenerRecetasIniciales($from = 0, $to = 10)
    {
        // Consulta predeterminada, puede ser "popular", "chicken", o algo similar
        $query = "popular";

        $url = $this->baseUrl . "?q=" . urlencode($query) . "&app_id=" . $this->appId . "&app_key=" . $this->appKey . "&from=$from&to=$to";

        try {
            $response = file_get_contents($url);
            return json_decode($response, true);
        } catch (\Exception $e) {
            error_log("Error al obtener recetas iniciales: " . $e->getMessage());
            return [];
        }
    }


}
