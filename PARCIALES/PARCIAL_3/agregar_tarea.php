<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Procesar el formulario de tareas
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $_POST['titulo'];
    $fecha_limite = $_POST['fecha_limite'];

}

?>