<?php
require_once 'classes/GestorBiblioteca.php';

$gestor = new GestorBiblioteca();
$gestor->cargarRecursosDesdeJSON('biblioteca.json');

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'agregar':
            if ($_GET['tipo'] == 'libro') {
                $nuevoRecurso = new Libro($_GET['id'], $_GET['titulo'], $_GET['estado'], $_GET['isbn']);
            } elseif ($_GET['tipo'] == 'revista') {
                $nuevoRecurso = new Revista($_GET['id'], $_GET['titulo'], $_GET['estado'], $_GET['numeroEdicion']);
            } elseif ($_GET['tipo'] == 'dvd') {
                $nuevoRecurso = new DVD($_GET['id'], $_GET['titulo'], $_GET['estado'], $_GET['duracion']);
            }
            $gestor->agregarRecurso($nuevoRecurso);
            file_put_contents('biblioteca.json', json_encode($gestor->listarRecursos()));
            break;
        case 'eliminar':
            $gestor->eliminarRecurso($_GET['id']);
            file_put_contents('biblioteca.json', json_encode($gestor->listarRecursos()));
            break;
        case 'actualizar':
            // Handle updating logic
            break;
        case 'cambiar_estado':
            $gestor->actualizarEstadoRecurso($_GET['id'], $_GET['estado']);
            file_put_contents('biblioteca.json', json_encode($gestor->listarRecursos()));
            break;
    }
}

$recursos = $gestor->listarRecursos();
?>

<!-- HTML to display resources -->
<table>
    <tr>
        <th>ID</th>
        <th>Título</th>
        <th>Estado</th>
        <th>Detalles Préstamo</th>
    </tr>
    <?php foreach ($recursos as $recurso): ?>
    <tr>
        <td><?php echo $recurso->id; ?></td>
        <td><?php echo $recurso->titulo; ?></td>
        <td><?php echo $recurso->estado; ?></td>
        <td><?php echo $recurso->obtenerDetallesPrestamo(); ?></td>
    </tr>
    <?php endforeach; ?>
</table>