<?php
session_start();
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../src/controllers/AuthController.php';

$client = createGoogleClient();

if (!isset($_GET['id'])) {
    header('Location: ../../index.php');
    exit;
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "Receta no especificada.";
    exit;
}

//inicializacion de variables
$recetaUri = htmlspecialchars($_GET['id']);
$query = '';

//controladores
$comentarioController = new Controllers\comentarioController();
$edamamController = new Controllers\EdamamController();

try {
    // Obtener los detalles de la receta utilizando la URI
    $receta = $edamamController->obtenerDetalleReceta($recetaUri);
    if (!$receta) {
        echo "Receta no encontrada.";
        exit;
    }
} catch (Exception $e) {
    error_log("Error al obtener detalles de la receta externa: " . $e->getMessage());
    echo "Ocurrió un error.";
    exit;
}

// Consultar comentarios para recetas externas usando la URL de la receta
try {
    $comentarios = $comentarioController->listarComentariosExternos($receta['url']);
    $recetasExternas = $edamamController->buscarRecetas($receta['label'], 0, 5);
} catch (PDOException $e) {
    error_log("Error al obtener comentarios: " . $e->getMessage());
    $comentarios = [];
}
// Verificar si se hizo una búsqueda
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['q'])) {
    $query = trim($_GET['q']);

}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($receta['label']) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/detalles_receta.css">
    <link rel="stylesheet" href="../css/detalles.css">
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
    <div class="recipe-header">
        <h1><?= htmlspecialchars($receta['label']) ?></h1>
        <div class="recipe-details">
            <p>Calorías: <?= round($receta['calories']) ?></p>
            <p>Porciones: <?= htmlspecialchars($receta['yield']) ?></p>
            <p>Por: <?= htmlspecialchars($receta['source']) ?></p>
        </div>
        <?php if ($receta['image']): ?>
            <div class="recipe-image-container">
                <img class="recipe-image" src="<?= htmlspecialchars($receta['image']) ?>"
                     alt="<?= htmlspecialchars($receta['label']) ?>">
            </div>
        <?php endif; ?>
    </div>

    <section class="ingredients-section">
        <h2>Ingredientes</h2>
        <ul>
            <?php foreach ($receta['ingredientLines'] as $ingrediente): ?>
                <li><?= htmlspecialchars($ingrediente) ?></li>
            <?php endforeach; ?>
        </ul>
        <p><a href="<?= htmlspecialchars($receta['url']) ?>" target="_blank">Ver receta completa
                en <?= htmlspecialchars($receta['source']) ?></a></p>
    </section>

    <section class="comments-section">
        <?php if (isset($_SESSION['user'])): ?>
            <form action="agregar_comentario_externo.php" method="POST">
                <input type="hidden" name="id" value="<?= urlencode($recetaUri) ?>">
                <input type="hidden" name="receta_url" value="<?= htmlspecialchars($receta['url']) ?>">
                <label for="comentario">Comentario:</label><br>
                <textarea id="comentario" name="comentario" required></textarea><br><br>
                <label for="calificacion">Calificación (1-5):</label>
                <input type="number" id="calificacion" name="calificacion" min="1" max="5" required><br><br>
                <button type="submit">Enviar Comentario</button>
            </form>
        <?php else: ?>
            <p><a href="<?= loginUrl($client) ?>">Iniciar sesión con Google</a> para dejar un comentario.</p>
        <?php endif; ?>

        <?php foreach ($comentarios as $comentario): ?>
            <div class="comentario">
                <p><strong><?= htmlspecialchars($comentario['nombre']) ?></strong>
                    (<?= htmlspecialchars($comentario['calificacion']) ?>/5)</p>
                <p><?= nl2br(htmlspecialchars($comentario['comentario'])) ?></p>
                <p><em><?= htmlspecialchars($comentario['creado_en']) ?></em></p>
            </div>
        <?php endforeach; ?>
    </section>


    <section class="related-recipes">
        <h2>Recetas Relacionadas</h2>
        <div class="recipes-scroll">
            <div class="recipes-container">
                <?php foreach ($recetasExternas['hits'] as $recetaExterna): ?>
                    <div class="receta-externa">
                        <a href="detalle_receta_externa.php?id=<?= urlencode($recetaExterna['recipe']['uri']) ?>">
                            <img src="<?= htmlspecialchars($recetaExterna['recipe']['image']) ?>"
                                 alt="<?= htmlspecialchars($recetaExterna['recipe']['label']) ?>"
                                 style="max-width: 150px; max-height: 150px; display: block;">
                            <?= htmlspecialchars($recetaExterna['recipe']['label']) ?>
                        </a>
                        <p>Calorías: <?= intval($recetaExterna['recipe']['calories']) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <a href="../../index.php" class="back-button">Volver a la Lista de Recetas</a>
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
<script src="../js/liveSearch.js"></script>
</body>
</html>
