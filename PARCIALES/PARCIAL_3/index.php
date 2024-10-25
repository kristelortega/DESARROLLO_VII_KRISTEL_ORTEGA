<?php
session_start();

// Lista de usuarios predefinida
$usuarios = [
    "usuario1" => "1234",
    "usuario2" => "abcd"
];

// Validar el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (isset($usuarios[$username]) && $usuarios[$username] == $password) {
        // Credenciales correctas
        $_SESSION['username'] = $username;
        header("Location: dashboard.php");
        exit();
    } else {
        // Credenciales incorrectas
        $error = "Nombre de usuario o contraseña incorrectos.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Iniciar Sesión</title>
</head>
<body>
    <h2>Iniciar Sesión</h2>
    <form method="POST" action="">
        <label for="username">Usuario:</label>
        <input type="text" id="username" name="username" required><br>
        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" required><br>
        <input type="submit" value="Iniciar Sesión">
    </form>

    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
</body>
</html>
