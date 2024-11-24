<?php
session_start();
require_once __DIR__ . '/../../src/controllers/AuthController.php';

$client = createGoogleClient();
$client->revokeToken(); // Revoca el token de acceso de Google

session_destroy(); // Destruye la sesión del usuario

header('Location: ../../index.php'); // Redirige al usuario a la página de inicio
exit;
