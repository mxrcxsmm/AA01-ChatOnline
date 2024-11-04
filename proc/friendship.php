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

// Obtiene los amigos del usuario actual
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
    <title>Mis Amigos</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <h2>Mis Amigos</h2>

    <?php if (!empty($friends)): ?>
        <ul>
            <?php foreach ($friends as $friend): ?>
                <li>
                    <?php echo htmlspecialchars($friend['username']); ?> (<?php echo htmlspecialchars($friend['real_name']); ?>)
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No tienes amigos en tu lista actualmente.</p>
    <?php endif; ?>

    <a href="../index.php">Volver al inicio</a>
</body>
</html>
