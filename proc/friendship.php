<?php
session_start();
include '../bd/conexion.php';

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Verifica si se ha enviado la solicitud para aceptar una amistad
if (isset($_POST['accept_request'])) {
    $request_id = mysqli_real_escape_string($conn, $_POST['request_id']);
    $user_id = $_SESSION['user_id'];

    // Obtiene los IDs de los usuarios implicados en la solicitud
    $query = "SELECT id_usuario_enviado, id_usuario_recibido FROM solicitud_amistad WHERE id_solicitudAmistad = '$request_id'";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $friend_id = $row['id_usuario_enviado'] == $user_id ? $row['id_usuario_recibido'] : $row['id_usuario_enviado'];

        // Actualiza el estado de la solicitud de amistad
        $update_query = "UPDATE solicitud_amistad SET status = 'accepted' WHERE id_solicitudAmistad = '$request_id'";
        mysqli_query($conn, $update_query);

        // Inserta la nueva amistad
        $insert_query = "INSERT INTO amistad (id_usuario1, id_usuario2) VALUES ('$user_id', '$friend_id')";
        if (mysqli_query($conn, $insert_query)) {
            echo "Amistad aceptada.";
        } else {
            echo "Error al crear la amistad: " . mysqli_error($conn);
        }
    } else {
        echo "Solicitud no válida.";
    }
}

// Obtiene las amistades del usuario
$user_id = $_SESSION['user_id'];
$friends = [];

// Aquí se obtiene la lista de amigos del usuario
$query = "SELECT usuario.usuario AS username, usuario.nombre AS real_name 
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
    <title>Amistades</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <h2>Mis Amigos</h2>

    <?php if (!empty($friends)): ?>
        <ul>
            <?php foreach ($friends as $friend): ?>
                <li>
                    <?php echo htmlspecialchars($friend['username']); ?> (<?php echo htmlspecialchars($friend['real_name']); ?>)
                    <form method="post" action="chat.php" style="display: inline;">
                        <input type="hidden" name="friend_id" value="<?php echo $friend['id_usuario']; ?>">
                        <button type="submit">Iniciar Chat</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No tienes amigos aún.</p>
    <?php endif; ?>
</body>
</html>
