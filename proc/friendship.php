<?php
session_start();
include '../bd/conexion.php';

// Verifica si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header('Location: ../view/login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$friends = [];
// Elimina la amistad si se ha enviado una solicitud de eliminación
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_friend_id'])) {
    $friend_id = $_POST['delete_friend_id'];

    // Elimina la amistad de la base de datos
    $delete_query = "DELETE FROM amistad 
                     WHERE (id_usuario1 = '$user_id' AND id_usuario2 = '$friend_id') 
                     OR (id_usuario1 = '$friend_id' AND id_usuario2 = '$user_id')";
    mysqli_query($conn, $delete_query);
}
// Obtiene los amigos del usuario actual
$query = "SELECT usuario.id_usuario, usuario.usuario AS username, usuario.nombre AS real_name 
          FROM amistad
          JOIN usuario ON (amistad.id_usuario1 = usuario.id_usuario OR amistad.id_usuario2 = usuario.id_usuario)
          WHERE (amistad.id_usuario1 = '$user_id' OR amistad.id_usuario2 = '$user_id')
          AND usuario.id_usuario != '$user_id'";

$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_assoc($result)) {
    $friends[] = $row;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Mis Amigos</title>
    <link rel="stylesheet" href="../css/lista.css">
    <link rel="stylesheet" href="../css/navbar.css">
</head>

<body>
    <nav class="navbar">
        <div class="navbar-content">
            <a href="../index.php" class="navbar-brand">
                <img src="../img/logo.png" alt="Logo" style="height: 40px;"> <!-- Ajusta la ruta y la altura según tus necesidades -->
            </a>
            <div class="navbar-links">
                <a href="../proc/search_users.php">Buscar Usuarios</a>
                <a href="../proc/manage_request.php">Ver Solicitudes</a>
                <a href="../proc/friendship.php">Amistades</a>
                <a href="../proc/chat.php">Chat</a>
                <a href="../proc/logout.php">Cerrar Sesión</a>
            </div>
        </div>
    </nav>
    <div class="container">
        <div class="card">
            <h2>Lista de Amigos</h2>
            <ul id="amigos-lista-ul">
                <?php if (!empty($friends)): ?>
                    <?php foreach ($friends as $friend): ?>
                        <li>
                            <a href="chat.php?friend_id=<?php echo htmlspecialchars($friend['id_usuario']); ?>">
                                <?php echo htmlspecialchars($friend['username']); ?> (<?php echo htmlspecialchars($friend['real_name']); ?>)
                            </a>
                            <form method="post" action="friendship.php" style="display: inline;">
                                <input type="hidden" name="delete_friend_id" value="<?php echo htmlspecialchars($friend['id_usuario']); ?>">
                                <button type="submit" class="button" onclick="return confirm('¿Estás seguro de que deseas eliminar a este amigo?');">Eliminar</button>
                            </form>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No tienes amigos en tu lista actualmente.</p>
                <?php endif; ?>
            </ul>
            <a href="../index.php" class="button">Volver al Inicio</a> <!-- Botón para volver al inicio -->
        </div>
    </div>
</body>

</html>