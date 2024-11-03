<?php
session_start();
include 'bd/conexion.php';

// Verificamos si el usuario ya está autenticado
if (isset($_SESSION['user_id'])) {
    // Usuario autenticado - Mostrar la página de bienvenida
    $user_id = $_SESSION['user_id'];

    // Consulta para obtener el nombre del usuario autenticado
    $query = "SELECT nombre FROM usuario WHERE id_usuario = '$user_id'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);
?>

    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Bienvenida</title>
        <link rel="stylesheet" href="">
    </head>

    <body>
        <h1>Bienvenido, <?php echo htmlspecialchars($user['nombre']); ?>!</h1>
        <p>Has iniciado sesión exitosamente.</p>

        <!-- Opciones de interacción -->
        <a href="proc/search_users.php">Buscar usuarios</a> |
        <a href="proc/manage_request.php">Solicitudes de amistad</a> |
        <a href="proc/friendship.php">Amistades</a> |
        <a href="proc/chat.php">Chat</a> |
        <a href="proc/logout.php">Cerrar sesión</a>

    </body>

    </html>

<?php
} else {
    // Usuario no autenticado - Mostrar el formulario de inicio de sesión
    header("Location: view/login.php")
?>

    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Iniciar sesión</title>
        <link rel="stylesheet" href="css/styles.css">
    </head>

    <body>
        <h1>Iniciar sesión</h1>
        <?php if (isset($error)) echo "<p style='color: red;'>$error</p>"; ?>

        <form method="POST" action="index.php">
            <label for="usuario">Usuario:</label>
            <input type="text" id="usuario" name="usuario" required>
            <br>
            <label for="passwd">Contraseña:</label>
            <input type="password" id="passwd" name="passwd" required>
            <br>
            <button type="submit">Iniciar sesión</button>
        </form>

        <p>¿No tienes una cuenta? <a href="view/register.php">Regístrate aquí</a></p>

    </body>

    </html>

<?php
}
?>