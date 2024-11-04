<?php
session_start();
include '../bd/conexion.php';

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$requests = [];

// Obtiene las solicitudes de amistad pendientes
$query = "SELECT solicitud_amistad.id_solicitudAmistad AS id, 
                 usuario.usuario AS username, 
                 usuario.nombre AS real_name 
          FROM solicitud_amistad 
          JOIN usuario ON solicitud_amistad.id_usuario_enviado = usuario.id_usuario 
          WHERE solicitud_amistad.id_usuario_recibido = ? AND solicitud_amistad.status = 'pending'";

// Prepara la consulta para evitar inyecciones SQL
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $user_id); // 'i' indica que el parámetro es un entero
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Verifica si hay errores en la consulta
if (!$result) {
    echo "Error en la consulta: " . mysqli_error($conn);
    exit();
}

// Recolecta las solicitudes
while ($row = mysqli_fetch_assoc($result)) {
    $requests[] = $row;
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
                    <form method="post" action="send_request.php" style="display: inline;">
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

    <a href="../index.php">Volver al inicio</a>
</body>
</html>
