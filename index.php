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
        <link rel="stylesheet" href="./css/index.css"> <!-- Asegúrate de que la ruta es correcta -->
    </head>

    <body>
        <div class="container">
            <h1>Bienvenido, <?php echo htmlspecialchars($user['nombre']); ?>!</h1>
            <p>Qué quieres hacer?</p>

        <!-- Opciones de interacción -->
        <a href="proc/search_users.php">Buscar usuarios</a> |
        <a href="proc/send_request.php">Solicitudes de amistad</a> |
        <a href="proc/friendship.php">Amistades</a> |
        <a href="proc/chat.php">Chat</a> |
        <a href="proc/logout.php">Cerrar sesión</a>

    </body>

    </html>

<?php
} else {
    // Usuario no autenticado - Redirigir al inicio de sesión
    header("Location: view/login.php");
    exit; // Siempre es buena práctica usar exit después de redirigir
}
?>