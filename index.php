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
        <link rel="stylesheet" href="css/styles.css">
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
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $usuario = $_POST['usuario'];
        $passwd = $_POST['passwd'];

        // Verificar las credenciales
        $query = "SELECT id_usuario, passwd FROM usuario WHERE usuario = '$usuario'";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);

            // Verificar la contraseña
            if (password_verify($passwd, $row['passwd'])) {
                $_SESSION['user_id'] = $row['id_usuario']; // Guardar el ID en la sesión

                // Redirigir al usuario a la misma página (index.php) para que vea la bienvenida
                header("Location: index.php");
                exit();
            } else {
                $error = "Contraseña incorrecta.";
            }
        } else {
            $error = "Usuario no encontrado.";
        }
    }
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
