<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Cargar tareas del usuario de la sesión
$tareas = $_SESSION['tareas'] ?? [];

?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>
    <h2>Bienvenido, <?php echo $_SESSION['username']; ?></h2>
    
    <h3>Tus Tareas</h3>
    <ul>
        <?php
        if (count($tareas) > 0) {
            foreach ($tareas as $tarea) {
                echo "<li>" . $tarea['titulo'] . " - " . $tarea['fecha_limite'] . "</li>";
            }
        } else {
            echo "<li>No tienes tareas.</li>";
        }
        ?>
    </ul>

    <a href="agregar_tarea.php">Agregar Tarea</a>
    <br><br>
    <a href="logout.php">Cerrar Sesión</a>
</body>
</html>
