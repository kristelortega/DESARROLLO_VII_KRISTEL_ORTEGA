<?php
session_start();
require __DIR__ . "/vendor/autoload.php";
require_once __DIR__ . '/src/controllers/AuthController.php';

// Controladores
$client = createGoogleClient();
$recetaController = new Controllers\RecetaController();
$edamamController = new Controllers\EdamamController();

// Inicialización de variables
$query = '';
$localRecetas = [];
$externalRecetas = [];

// Establecer valores predeterminados para paginación
$paginaActual = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1; // Página 1 por defecto
$resultadosPorPagina = 13; // Número de recetas por página

// Calcular el offset para la API de Edamam
$desde = ($paginaActual - 1) * $resultadosPorPagina;
$hasta = $desde + $resultadosPorPagina;

// Verificar si se hizo una búsqueda
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['q'])) {
    $query = trim($_GET['q']);

    // Buscar recetas locales usando el controlador RecetaController
    $localRecetas = $recetaController->obtenerTodasPorQuery($query);

    // Buscar recetas en Edamam, usando $desde como offset
    try {
        $externalRecetas = $edamamController->buscarRecetas($query, $desde, $hasta)['hits'];
    } catch (Exception $e) {
        error_log("Error al buscar recetas externas: " . $e->getMessage());
    }
} else {
    // Si no hay búsqueda, obtener todas las recetas locales
    $localRecetas = $recetaController->obtenerTodas();

    // Mostrar recetas predeterminadas de Edamam (al menos algunos resultados)
    try {
        $externalRecetas = $edamamController->obtenerRecetasIniciales($desde, $hasta)['hits'];
    } catch (Exception $e) {
        error_log("Error al buscar recetas externas: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Recetas</title>
    <link rel="stylesheet" href="public/css/styles.css">
    <link rel="icon" href="src/drawable/logo.png" type="image/x-icon"></link>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
<header>
    <div class="header-content">
        <div class="logo-container">
            <a href="">
                <img src="src/drawable/logo.png" alt="Recipe Search Logo">
            </a>
            <h1 class="site-title">Recipe Search</h1>
        </div>
            <?php if (isset($_SESSION['user'])): ?>
                <a href="public/php/logout.php" onclick="return confirm('¿Cerrar Sesion?')">
                    <button class="google-signin-button">
                        <svg viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                        <span><?= htmlspecialchars($_SESSION['user']['nombre']) ?></span>
                    </button>
                </a>
            <?php else: ?>
                <a href="<?= loginUrl($client) ?>">
                    <button class="google-signin-button">
                        <svg viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                        <span>Sign in with Google</span>
                    </button>
                </a>
            <?php endif; ?>
    </div>
</header>

<main>
    <form id="searchForm" method="GET">
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

    <?php if ($query): ?>
        <div class="seccion-recetas">
            <h3>Recetas Locales</h3>
            <?php if (isset($_SESSION['user'])): ?>
                <a href="public/php/crear_receta.php" class="btn-crear-receta">
                    <button>Añadir Receta</button>
                </a>
            <?php endif; ?>
            <?php if (empty($localRecetas)): ?>
                <p class="no-resultados">No se encontraron recetas locales.</p>
            <?php else: ?>
                <div class="grid-recetas">
                    <?php foreach ($localRecetas as $receta): ?>
                        <div class="receta">
                            <div class="receta-imagen">
                                <?php if ($receta['imagen']): ?>
                                    <img src="../src/uploads/<?= htmlspecialchars($receta['imagen']) ?>"
                                         alt="<?= htmlspecialchars($receta['titulo']) ?>">
                                <?php endif; ?>
                            </div>
                            <div class="receta-contenido">
                                <h2><?= htmlspecialchars($receta['titulo']) ?></h2>
                                <p class="receta-autor">Por: <?= htmlspecialchars($receta['autor']) ?></p>
                                <p><?= htmlspecialchars(substr($receta['descripcion'], 0, 150)) ?>...</p>
                                <a href="public/php/detalle_receta.php?id=<?= $receta['id'] ?>">Ver Detalle</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="seccion-recetas">
            <h3>Recetas Externas</h3>
            <?php if (empty($externalRecetas)): ?>
                <p class="no-resultados">No se encontraron recetas externas.</p>
            <?php else: ?>
                <div class="grid-recetas">
                    <?php foreach ($externalRecetas as $receta): ?>
                        <div class="receta resultado-externo">
                            <div class="receta-imagen">
                                <img src="<?= htmlspecialchars($receta['recipe']['image']) ?>"
                                     alt="<?= htmlspecialchars($receta['recipe']['label']) ?>">
                            </div>
                            <div class="receta-contenido">
                                <h2><?= htmlspecialchars($receta['recipe']['label']) ?></h2>
                                <p class="receta-calorias">Calorías: <?= round($receta['recipe']['calories']) ?></p>
                                <a href="public/php/detalle_receta_externa.php?id=<?= urlencode($receta['recipe']['uri']) ?>">Ver
                                    Detalles</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="seccion-recetas">
            <h3>Recetas Locales</h3>
            <?php if (isset($_SESSION['user'])): ?>
                <a href="public/php/crear_receta.php" class="btn-crear-receta">
                    <button>Añadir Receta</button>
                </a>
            <?php endif; ?>
            <?php if (empty($localRecetas)): ?>
                <p class="no-resultados">No se encontraron recetas locales.</p>
            <?php else: ?>
                <div class="grid-recetas">
                    <?php foreach ($localRecetas as $receta): ?>
                        <div class="receta">
                            <div class="receta-imagen">
                                <?php if ($receta['imagen']): ?>
                                    <img src="../src/uploads/<?= htmlspecialchars($receta['imagen']) ?>"
                                         alt="<?= htmlspecialchars($receta['titulo']) ?>">
                                <?php endif; ?>
                            </div>
                            <div class="receta-contenido">
                                <h2><?= htmlspecialchars($receta['titulo']) ?></h2>
                                <p class="receta-autor">Por: <?= htmlspecialchars($receta['autor']) ?></p>
                                <p><?= htmlspecialchars(substr($receta['descripcion'], 0, 150)) ?>...</p>
                                <a href="public/php/detalle_receta.php?id=<?= $receta['id'] ?>">Ver Detalle</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="seccion-recetas">
            <h3>Recetas Externas</h3>
            <?php if (empty($externalRecetas)): ?>
                <p class="no-resultados">No se encontraron recetas externas.</p>
            <?php else: ?>
                <div class="grid-recetas">
                    <?php foreach ($externalRecetas as $receta): ?>
                        <div class="receta resultado-externo">
                            <div class="receta-imagen">
                                <img src="<?= htmlspecialchars($receta['recipe']['image']) ?>"
                                     alt="<?= htmlspecialchars($receta['recipe']['label']) ?>">
                            </div>
                            <div class="receta-contenido">
                                <h2><?= htmlspecialchars($receta['recipe']['label']) ?></h2>
                                <p class="receta-calorias">Calorías: <?= round($receta['recipe']['calories']) ?></p>
                                <a href="public/php/detalle_receta_externa.php?id=<?= urlencode($receta['recipe']['uri']) ?>">Ver
                                    Detalles</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    <!-- Paginación -->
    <div class="paginacion">
        <?php if ($paginaActual > 1): ?>
            <a href="?q=<?= urlencode($query) ?>&page=<?= $paginaActual - 1 ?>">Anterior</a>
        <?php endif; ?>
        <span>Página <?= $paginaActual ?></span>
        <?php if (!empty($externalRecetas)): ?>
            <a href="?q=<?= urlencode($query) ?>&page=<?= $paginaActual + 1 ?>">Siguiente</a>
        <?php endif; ?>
    </div>
</main>
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

<script src="public/js/liveSearch.js"></script>

</body>
</html>