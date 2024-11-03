
<?php
session_start();
require '../bd/conexion.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
</head>

<body>
    <form action="../Validaciones/Login-Register/validacion.php" method="POST">
        <div>
            <label>Usuario:</label>
            <input type="text" name="usuario" value="<?php echo isset($_SESSION['usuario']) ? htmlspecialchars($_SESSION['usuario']) : ''; ?>">
            <p style="color: red;"><?php echo isset($_SESSION['usuarioError']) ? $_SESSION['usuarioError'] : ''; ?></p>
            <p style="color: red;"><?php echo isset($_GET['usuarioVacio']) ? "El usuario no puede estar vacío." : ""; ?></p>
            <p style="color: red;"><?php echo isset($_GET['usuarioMal']) ? "El usuario solo puede contener letras y números." : ""; ?></p>
        </div>
        <div>
            <label>Nombre:</label>
            <input type="text" name="nombre" value="<?php echo isset($_SESSION['nombre']) ? htmlspecialchars($_SESSION['nombre']) : ''; ?>">
            <p style="color: red;"><?php echo isset($_SESSION['nombreError']) ? $_SESSION['nombreError'] : ''; ?></p>
            <p style="color: red;"><?php echo isset($_GET['nombreVacio']) ? "El nombre no puede estar vacío." : ""; ?></p>
        </div>
        <div>
            <label>Contraseña:</label>
            <input type="password" name="psswrd">
            <p style="color: red;"><?php echo isset($_SESSION['psswrdError']) ? $_SESSION['psswrdError'] : ''; ?></p>
            <p style="color: red;"><?php echo isset($_GET['psswrdVacio']) ? "La contraseña no puede estar vacía." : ""; ?></p>
        </div>
        <input type="submit" value="Registrarse">
        <p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a>.</p>
    </form>

    <?php
    // Limpiar los mensajes de error después de mostrarlos
    unset($_SESSION['usuarioError']);
    unset($_SESSION['nombreError']);
    unset($_SESSION['psswrdError']);
    ?>
</body>
</html>
