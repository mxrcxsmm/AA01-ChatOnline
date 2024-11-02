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
$query = "SELECT friend_requests.id, users.username, users.real_name 
          FROM friend_requests 
          JOIN users ON friend_requests.sender_id = users.id 
          WHERE friend_requests.receiver_id = '$user_id' AND friend_requests.status = 'pending'";
$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_assoc($result)) {
    $requests[] = $row;
}

// Procesa la aceptación o rechazo de solicitudes
if (isset($_POST['accept_request']) && isset($_POST['request_id'])) {
    $request_id = mysqli_real_escape_string($conn, $_POST['request_id']);

    // Acepta la solicitud de amistad y actualiza el estado
    $query = "UPDATE friend_requests SET status = 'accepted' WHERE id = '$request_id'";
    if (mysqli_query($conn, $query)) {
        echo "Solicitud de amistad aceptada.";
    } else {
        echo "Error al aceptar la solicitud: " . mysqli_error($conn);
    }
} elseif (isset($_POST['reject_request']) && isset($_POST['request_id'])) {
    $request_id = mysqli_real_escape_string($conn, $_POST['request_id']);

    // Rechaza la solicitud de amistad y actualiza el estado
    $query = "UPDATE friend_requests SET status = 'rejected' WHERE id = '$request_id'";
    if (mysqli_query($conn, $query)) {
        echo "Solicitud de amistad rechazada.";
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
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
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
