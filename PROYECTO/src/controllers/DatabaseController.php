<?php
namespace Controllers;

require_once __DIR__ . '/../../vendor/autoload.php';

use Models\Database;

class DatabaseController
{
    private $dbConnection;

    public function __construct()
    {
        // Instanciar la clase Database y obtener la conexión
        $this->dbConnection = (new Database())->getConnection();
    }

    public function getConnection()
    {
        // Devolver la conexión a la base de datos
        return $this->dbConnection;
    }
}
