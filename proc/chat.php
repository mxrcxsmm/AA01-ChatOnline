<?php
session_start();
include '../bd/conexion.php';

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header('Location: ../view/login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Consulta para obtener la lista de amigos
$query = "SELECT u.id_usuario, u.usuario 
          FROM amistad a 
          JOIN usuario u ON (a.id_usuario1 = u.id_usuario OR a.id_usuario2 = u.id_usuario) 
          WHERE (a.id_usuario1 = '$user_id' OR a.id_usuario2 = '$user_id') 
          AND u.id_usuario != '$user_id'";

$result = mysqli_query($conn, $query);

// Verifica si se ha enviado el ID del amigo
$friend_id = isset($_GET['friend_id']) ? mysqli_real_escape_string($conn, $_GET['friend_id']) : null;

// Si hay un amigo seleccionado, obtenemos los mensajes
$messages = [];
if ($friend_id) {
    // Verifica que haya una amistad entre los dos usuarios
    $query = "SELECT * FROM amistad WHERE (id_usuario1 = '$user_id' AND id_usuario2 = '$friend_id') OR (id_usuario1 = '$friend_id' AND id_usuario2 = '$user_id')";
    $result_friendship = mysqli_query($conn, $query);

    if (mysqli_num_rows($result_friendship) > 0) {
        // Obtener mensajes entre el usuario y su amigo, ordenados del más nuevo al más viejo
        $query = "
            SELECT m.contenido, m.fecha, u.usuario AS remitente, m.id_usuario_remitente 
            FROM mensaje m
            JOIN amistad a ON m.id_amistad = a.id_amistad
            JOIN usuario u ON m.id_usuario_remitente = u.id_usuario
            WHERE (a.id_usuario1 = '$user_id' AND a.id_usuario2 = '$friend_id') 
               OR (a.id_usuario1 = '$friend_id' AND a.id_usuario2 = '$user_id')
            ORDER BY m.fecha DESC
        ";
        $messages = mysqli_query($conn, $query);
    } else {
        echo "No eres amigo de este usuario.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Chat</title>
    <link rel="stylesheet" href="../css/chat.css">
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
        <div id="amigos-lista">
            <h2>Lista de Amigos</h2>
            <ul>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <li>
                            <a href="chat.php?friend_id=<?php echo $row['id_usuario']; ?>">
                                <?php echo htmlspecialchars($row['usuario']); ?>
                            </a>
                        </li>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No tienes amigos para chatear.</p>
                <?php endif; ?>
            </ul>
            <a href="../index.php" class="button">Volver al Inicio</a> <!-- Botón para volver al inicio -->
        </div>

        <div id="chat-area">
            <?php if ($friend_id): ?>
                <?php
                // Obtener nombre del amigo para mostrar
                $friend_query = "SELECT usuario FROM usuario WHERE id_usuario = '$friend_id'";
                $friend_result = mysqli_query($conn, $friend_query);
                $friend_name = mysqli_fetch_assoc($friend_result)['usuario'];
                ?>
                <h2>Chat con <?php echo htmlspecialchars($friend_name); ?></h2>
                <div id="chat-messages">
                    <?php if (mysqli_num_rows($messages) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($messages)): ?>
                            <div class="message <?php echo ($row['id_usuario_remitente'] == $user_id) ? 'user-message' : ''; ?>">
                                <strong><?php echo htmlspecialchars($row['remitente']); ?>:</strong>
                                <?php echo htmlspecialchars($row['contenido']); ?>
                                <em>(<?php echo $row['fecha']; ?>)</em>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p>No hay mensajes en esta conversación.</p>
                    <?php endif; ?>
                </div>

                <form method="POST" action="send_message.php">
                    <input type="hidden" name="friend_id" value="<?php echo htmlspecialchars($friend_id); ?>">
                    <textarea name="message" placeholder="Escribe tu mensaje..." required></textarea>
                    <button type="submit">Enviar</button>
                    <button type="button" onclick="location.reload();">Actualizar</button> <!-- Botón para actualizar la página -->
                </form>
            <?php else: ?>
                <p>Selecciona un amigo para comenzar a chatear.</p>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>