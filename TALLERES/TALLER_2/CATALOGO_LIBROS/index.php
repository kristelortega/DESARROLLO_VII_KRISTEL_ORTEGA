<?php
require_once 'includes/funciones.php';
include 'includes/header.php';

// Lista de libros
$libros = obtenerLibros();
?>

<main>
    <h2>Lista de libros disponibles</h2>
    <?php

    // Detalles de cada libro
    foreach ($libros as $libro) {
        echo mostrarDetallesLibro($libro);
    }
    ?>
</main>

<?php
include 'includes/footer.php';
?>
