<?php
session_start();
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../src/controllers/AuthController.php';

$dbController = new Controllers\DatabaseController();
if (!isset($_SESSION['user'])) {
    header('Location: ../../index.php');
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$client = createGoogleClient();
$id = intval($_GET['id']);
$pdo = $dbController->getConnection();
$query = '';

try {
    // Obtener detalles de la receta y validar que el usuario tiene permiso
    $stmt = $pdo->prepare("SELECT * FROM recetas WHERE id = :id AND usuario_id = :usuario_id");
    $stmt->execute([
        'id' => $id,
        'usuario_id' => $_SESSION['user']['google_id']
    ]);
    $receta = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$receta) {
        echo "Receta no encontrada o no tienes permiso para editarla.";
        exit;
    }

    // Obtener ingredientes
    $stmtIngredientes = $pdo->prepare("SELECT nombre, cantidad FROM ingredientes WHERE receta_id = :receta_id");
    $stmtIngredientes->execute(['receta_id' => $id]);
    $ingredientes = $stmtIngredientes->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error al obtener la receta: " . $e->getMessage());
    echo "Ocurrió un error.";
    exit;
}

// Verificar si se hizo una búsqueda
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['q'])) {
    $query = trim($_GET['q']);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>Editar Receta</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/crear_Receta.css">
    <link rel="icon" href="../../src/drawable/logo.png" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
<header>
    <div class="header-content">
        <div class="logo-container">
            <a href="../../index.php">
                <img src="../../src/drawable/logo.png" alt="Recipe Search Logo">
            </a>
            <h1 class="site-title">Recipe Search</h1>
        </div>
        <?php if (isset($_SESSION['user'])): ?>
            <a href="logout.php" onclick="return confirm('¿Cerrar Sesion?')">
                <button class="google-signin-button">
                    <svg viewBox="0 0 24 24">
                        <path fill="#4285F4"
                              d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853"
                              d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05"
                              d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path fill="#EA4335"
                              d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    <span><?= htmlspecialchars($_SESSION['user']['nombre']) ?></span>
                </button>
            </a>
        <?php else: ?>
            <a href="<?= loginUrl($client) ?>">
                <button class="google-signin-button">
                    <svg viewBox="0 0 24 24">
                        <path fill="#4285F4"
                              d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853"
                              d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05"
                              d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path fill="#EA4335"
                              d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    <span>Sign in</span>
                </button>
            </a>
        <?php endif; ?>
    </div>
</header>
<main>
<form id="searchForm" method="GET" action="../../index.php">
    <input
            type="text"
            id="searchInput"
            name="q"
            placeholder="Buscar recetas..."
            value="<?= htmlspecialchars($query) ?>"
            autocomplete="off">
    <button type="submit">Buscar</button>
</form>
<div id="liveSearchResults"></div>

<h1>Editar Receta</h1>
<form action="actualizar_receta.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $receta['id'] ?>">

    <label for="titulo">Título:</label>
    <input type="text" id="titulo" name="titulo" value="<?= htmlspecialchars($receta['titulo']) ?>" required><br><br>

    <label for="descripcion">Descripción:</label>
    <textarea id="descripcion" name="descripcion" required><?= htmlspecialchars($receta['descripcion']) ?></textarea><br><br>

    <label for="tiempo_preparacion">Tiempo de Preparación (minutos):</label>
    <input type="number" id="tiempo_preparacion" name="tiempo_preparacion" value="<?= htmlspecialchars($receta['tiempo_preparacion']) ?>" required><br><br>

    <?php if ($receta['imagen']): ?>
        <p>Imagen Actual:</p>
        <img src="../../src/uploads/<?= htmlspecialchars($receta['imagen']) ?>" alt="<?= htmlspecialchars($receta['titulo']) ?>" style="max-width: 200px;"><br>
        <label for="imagen">Cambiar Imagen:</label>
        <input type="file" id="imagen" name="imagen" accept="image/*"><br><br>
    <?php else: ?>
        <label for="imagen">Imagen:</label>
        <input type="file" id="imagen" name="imagen" accept="image/*"><br><br>
    <?php endif; ?>

    <h3>Ingredientes</h3>
    <div id="ingredientes">
        <?php foreach ($ingredientes as $ingrediente): ?>
            <div class="ingrediente">
                <input type="text" name="ingredientes[nombre][]" placeholder="Nombre del ingrediente" value="<?= htmlspecialchars($ingrediente['nombre']) ?>" required>
                <input type="text" name="ingredientes[cantidad][]" placeholder="Cantidad" value="<?= htmlspecialchars($ingrediente['cantidad']) ?>" required>
                <button type="button" onclick="eliminarIngrediente(this)">Eliminar</button>
            </div>
        <?php endforeach; ?>
    </div>
    <button type="button" onclick="agregarIngrediente()">Agregar Ingrediente</button><br><br>

    <button type="submit">Actualizar Receta</button>
</form>
</main>
<footer>
    <div class="footer-content">
        <p>&copy; <?= date('Y') ?> Recipe Search. Todos los derechos reservados.</p>
        <div class="footer-links">
            <a href="#">Política de Privacidad</a> |
            <a href="#">Términos de Servicio</a> |
            <a href="#">Contacto</a>
        </div>
    </div>
</footer>
<script src="../js/agregarIngrediente.js"></script>
<script src="../js/liveSearch.js"></script>
</body>
</html>
