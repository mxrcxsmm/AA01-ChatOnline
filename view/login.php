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
    <link rel="stylesheet" href="../css/login.css">
</head>

<body>
    <div class="container">
        <div class="left-section">
            <img src="../img/logo.png" alt="Logo" class="logo">
        </div>

        <div class="right-section">
            <form action="../Validaciones/Login-Register/validacionLog.php" method="POST">
                <div>
                    <label for="usuario">Usuario:</label>
                    <input type="text" id="usuario" name="usuario" value="<?php echo isset($_SESSION['usuario']) ? htmlspecialchars($_SESSION['usuario']) : ''; ?>">
                    <?php if (isset($_SESSION['loginUsuarioError'])) : ?>
                        <p class="error-message"><?php echo $_SESSION['loginUsuarioError']; ?></p>
                    <?php endif; ?>
                </div>

                <div>
                    <label for="psswrd">Contraseña:</label>
                    <input type="password" id="psswrd" name="psswrd">
                    <?php if (isset($_SESSION['loginPsswrdError'])) : ?>
                        <p class="error-message"><?php echo $_SESSION['loginPsswrdError']; ?></p>
                    <?php endif; ?>
                </div>

                <?php if (isset($_SESSION['loginError'])) : ?>
                    <p class="error-message"><?php echo $_SESSION['loginError']; ?></p>
                <?php endif; ?>

                <input type="submit" value="Iniciar Sesión">
                <p>¿No tienes cuenta? <a href="register.php">Regístrate aquí</a>.</p>
            </form>
        </div>
    </div>
</body>

</html>

<?php
unset($_SESSION['loginUsuarioError'], $_SESSION['loginPsswrdError'], $_SESSION['loginError']);
?>