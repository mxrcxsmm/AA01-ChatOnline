<?php
session_start();
require '../bd/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verifica si los campos están definidos
    if (isset($_POST['usuario'], $_POST['nombre'], $_POST['psswrd'])) {
        $usuario = mysqli_real_escape_string($conn, $_POST['usuario']); // Cambiado de 'user' a 'usuario'
        $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
        $psswrd = password_hash($_POST['psswrd'], PASSWORD_BCRYPT); // Cambiado de 'password' a 'psswrd'

        $sql = "INSERT INTO usuario (usuario, nombre, passwd) VALUES ('$usuario', '$nombre', '$psswrd')";

        if (mysqli_query($conn, $sql)) {
            $_SESSION['mensaje'] = "Registro exitoso. Puedes iniciar sesión.";
            header("Location: login.php");
            exit();
        } else {
            $_SESSION['error'] = "Error: " . mysqli_error($conn);
            header("Location: register.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "Por favor completa todos los campos.";
        header("Location: register.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
</head>

<body>
    <form action="register.php" method="POST">
        <label>Usuario:</label>
        <input type="text" name="usuario">
        <label>Nombre:</label>
        <input type="text" name="nombre">
        <label>Contraseña:</label>
        <input type="password" name="psswrd">
        <input type="submit" value="Registrarse">
        <p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a>.</p>
    </form>
</body>

</html>