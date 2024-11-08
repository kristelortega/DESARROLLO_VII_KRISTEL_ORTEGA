<?php
session_start();
include 'datos.php';

// Verificar si el usuario está autenticado
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit;
}

// Mostrar dashboard según el rol del usuario
$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
</head>
<body>
    <h2>Bienvenido, <?php echo htmlspecialchars($_SESSION['username']); ?> </h2>

    <?php if ($role === 'Profesor'): ?>
        <h3>Calificaciones de Estudiantes</h3>
        <ul>
            <?php foreach ($grades as $grade): ?>
                <li><?php echo $grade['estudiante']; ?>: <?php echo $grade['calificacion']; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php elseif ($role === 'Estudiante'): ?>
        <h3>Tu Calificación</h3>
        <ul>
            <?php
            foreach ($grades as $grade) {
                if ($grade['estudiante'] === $_SESSION['username']) {
                    echo "<li>Calificación: " . $grade['calificacion'] . "</li>";
                    break;
                }
            }
            ?>
        </ul>
    <?php endif; ?>

    <a href="logout.php">Cerrar Sesión</a>
</body>
</html>
