<?php
session_start();
include 'datos.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validación de usuario y contraseña
    if (isset($users[$username]) && $users[$username]['password'] === $password) {
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $users[$username]['rol'];
        header('Location: dashboard.php');
        exit;
    } else {
        $error = "Usuario o contraseña incorrectos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
</head>
<body>
    <h2>Iniciar Sesión</h2>
    <form method="POST" action="">
        <label>Nombre de usuario:</label>
        <input type="text" name="username" required minlength="3" pattern="[A-Za-z0-9]+"><br>
        <label>Contraseña:</label>
        <input type="password" name="password" required minlength="5"><br>
        <button type="submit">Ingresar</button>
    </form>
    <p style="color:red;"><?php echo $error; ?></p>
</body>
</html>
