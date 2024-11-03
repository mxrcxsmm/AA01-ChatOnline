<?php
session_start();
require '../bd/conexion.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
</head>

<body>
    <form action="../Validaciones/Login-Register/validacionLog.php" method="POST">
        <div>
            <label for="usuario">Usuario:</label>
            <input type="text" id="usuario" name="usuario" value="<?php echo isset($_SESSION['usuario']) ? htmlspecialchars($_SESSION['usuario']) : ''; ?>">
            <?php if (isset($_SESSION['loginUsuarioError'])) : ?>
                <p style="color: red;"><?php echo $_SESSION['loginUsuarioError']; ?></p>
            <?php endif; ?>
        </div>

        <div>
            <label for="psswrd">Contraseña:</label>
            <input type="password" id="psswrd" name="psswrd">
            <?php if (isset($_SESSION['loginPsswrdError'])) : ?>
                <p style="color: red;"><?php echo $_SESSION['loginPsswrdError']; ?></p>
            <?php endif; ?>
        </div>

        <input type="submit" value="Iniciar Sesión">
    </form>
    <p>¿No tienes cuenta? <a href="register.php">Regístrate aquí</a>.</p>

    <!-- Mostrar el mensaje de error genérico -->
    <?php if (isset($_SESSION['loginError'])) : ?>
        <p style="color: red;"><?php echo $_SESSION['loginError']; ?></p>
    <?php endif; ?>
</body>

</html>

<?php
// Limpiar las variables de sesión después de mostrar los errores
unset($_SESSION['loginUsuarioError'], $_SESSION['loginPsswrdError'], $_SESSION['loginError']);
?>