<?php
// crear_receta.php
require_once __DIR__ . '/../../src/controllers/AuthController.php';
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../../index.php');
    exit;
}
$client = createGoogleClient();
$query = '';

// Verificar si se hizo una búsqueda
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['q'])) {
    $query = trim($_GET['q']);

}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Crear Nueva Receta</title>
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

<main>
    <h1>Crear Nueva Receta</h1>
<form action="guardar_receta.php" method="POST" enctype="multipart/form-data">
    <label for="titulo">Título:</label>
    <input type="text" id="titulo" name="titulo" required><br><br>

    <label for="descripcion">Descripción:</label>
    <textarea id="descripcion" name="descripcion" required></textarea><br><br>

    <label for="tiempo_preparacion">Tiempo de Preparación (minutos):</label>
    <input type="number" id="tiempo_preparacion" name="tiempo_preparacion" required><br><br>

    <label for="imagen">Imagen:</label>
    <input type="file" id="imagen" name="imagen" accept="image/*"><br><br>

    <h3>Ingredientes</h3>
    <div id="ingredientes">
        <div class="ingrediente">
            <input type="text" name="ingredientes[nombre][]" placeholder="Nombre del ingrediente" required>
            <input type="text" name="ingredientes[cantidad][]" placeholder="Cantidad" required>
            <button type="button" onclick="eliminarIngrediente(this)">Eliminar</button>
        </div>
    </div>
    <button type="button" onclick="agregarIngrediente()">Agregar Ingrediente</button><br><br>

    <button type="submit">Guardar Receta</button>
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
