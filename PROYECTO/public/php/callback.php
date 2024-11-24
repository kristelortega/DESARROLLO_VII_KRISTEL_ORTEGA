<?php
session_start();
require_once __DIR__ . "/../../vendor/autoload.php";
require_once __DIR__ . '/../../src/controllers/AuthController.php';

$client = createGoogleClient();
handleCallback($client);
$dbController = new Controllers\DatabaseController();
$conn = $dbController->getConnection();
$userController = new Models\Usuario($conn);

// Obtener la información del usuario de la sesión
$userInfo = $_SESSION['user'];

// Guardar el usuario en la base de datos
$userController->create($userInfo);

// Redirigir a la página principal después de iniciar sesión
header('Location: ../../index.php');
exit;
