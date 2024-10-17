
<?php
$paginaActual = 'contacto';
require_once 'plantillas/funciones.php';
$tituloPagina = obtenerTituloPagina($paginaActual);
include 'plantillas/encabezado.php';
?>

<h2>Contenido de la Página de Conctato</h2>
<p>Este es el contenido específico de la página de contacto.</p>

<?php
include 'plantillas/pie_pagina.php';
?>
   