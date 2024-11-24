<?php

require __DIR__ . '/../vendor/autoload.php'; // Ajusta la ruta según tu estructura

// Cargar el archivo .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../', 'config.env');
$dotenv->load();

define('DB_HOST', $_ENV['DB_HOST']);
define('DB_NAME', $_ENV['DB_NAME']);
define('DB_USER', $_ENV['DB_USER']);
define('DB_PASS', $_ENV['DB_PASS']);
define('EDAMAM_APP_ID', $_ENV['EDAMAM_APP_ID']);
define('EDAMAM_API_KEY', $_ENV['EDAMAM_API_KEY']);

