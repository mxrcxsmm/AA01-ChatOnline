<?php
session_start();
include '../bd/conexion.php';

// Verifica si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header('Location: ../view/login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$requests = [];

// Obtiene las solicitudes de amistad pendientes
$query = "SELECT solicitud_amistad.id_solicitudAmistad AS id, usuario.usuario AS username, usuario.nombre AS real_name 
          FROM solicitud_amistad 
          JOIN usuario ON solicitud_amistad.id_usuario_enviado = usuario.id_usuario 
          WHERE solicitud_amistad.id_usuario_recibido = '$user_id' AND solicitud_amistad.status = 'pending'";
$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_assoc($result)) {
    $requests[] = $row;
}

// Procesa la aceptación o rechazo de solicitudes
if (isset($_POST['accept_request']) && isset($_POST['request_id'])) {
    $request_id = mysqli_real_escape_string($conn, $_POST['request_id']);
    
    // Acepta la solicitud de amistad y actualiza el estado
    $query = "UPDATE solicitud_amistad SET status = 'accepted' WHERE id_solicitudAmistad = '$request_id'";
    if (mysqli_query($conn, $query)) {
        echo "Solicitud de amistad aceptada.";
    } else {
        echo "Error al aceptar la solicitud: " . mysqli_error($conn);
    }
} elseif (isset($_POST['reject_request']) && isset($_POST['request_id'])) {
    $request_id = mysqli_real_escape_string($conn, $_POST['request_id']);
    
    // Rechaza la solicitud de amistad y actualiza el estado
    $query = "UPDATE solicitud_amistad SET status = 'rejected' WHERE id_solicitudAmistad = '$request_id'";
    if (mysqli_query($conn, $query)) {
        // Redirecciona a la página principal
        header('Location: ../index.php');
        exit();
    } else {
        echo "Error al rechazar la solicitud: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Solicitudes de Amistad</title>
    <link rel="stylesheet" href="../css/soliAmi.css"> 
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
            <h2>Solicitudes de Amistad Recibidas</h2>

    <?php if (!empty($requests)): ?>
        <ul>
            <?php foreach ($requests as $request): ?>
                <li>
                    <?php echo htmlspecialchars($request['username']); ?> (<?php echo htmlspecialchars($request['real_name']); ?>)
                    <form method="post" action="manage_requests.php" style="display: inline;">
                        <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
                        <button type="submit" name="accept_request">Aceptar</button>
                        <button type="submit" name="reject_request">Rechazar</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No tienes solicitudes de amistad pendientes.</p>
    <?php endif; ?>

    <a href="../view/index.php">Volver al inicio</a>
</body>

</html>