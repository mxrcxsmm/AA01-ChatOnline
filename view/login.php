<?php
session_start();
require '../bd/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verifica si los campos están definidos
    if (isset($_POST['usuario'], $_POST['psswrd'])) {
        $usuario = mysqli_real_escape_string($conn, $_POST['usuario']);
        $psswrd = $_POST['psswrd'];

        // Consulta para obtener el usuario y la contraseña
        $sql = "SELECT * FROM usuario WHERE usuario = '$usuario'";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            // Verifica si la contraseña es correcta
            if (password_verify($psswrd, $row['passwd'])) {
                // Almacena información del usuario en la sesión
                $_SESSION['user_id'] = $row['id_usuario'];
                $_SESSION['usuario'] = $row['usuario'];
                $_SESSION['mensaje'] = "Bienvenido, " . $row['nombre'] . "!";
                header("Location: index.php"); // Redirigir a una página de bienvenida
                exit();
            } else {
                $_SESSION['error'] = "Contraseña incorrecta.";
            }
        } else {
            $_SESSION['error'] = "Usuario no encontrado.";
        }
    } else {
        $_SESSION['error'] = "Rellena todos los campos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
</head>

<body>
    <form action="login.php" method="POST">
        <label for="usuario">Usuario:</label>
        <input type="text" id="usuario" name="usuario" required>
        <label for="psswrd">Contraseña:</label>
        <input type="password" id="psswrd" name="psswrd" required>
        <input type="submit" value="Iniciar Sesión">
    </form>
    <p>¿No tienes cuenta? <a href="register.php">Regístrate aquí</a>.</p>

    <?php
    // Mostrar mensajes de error o de éxito
    if (isset($_SESSION['error'])) {
        echo "<p style='color: red;'>" . $_SESSION['error'] . "</p>";
        unset($_SESSION['error']);
    }
    ?>
</body>

</html>
