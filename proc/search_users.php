<?php
session_start();
include '../bd/conexion.php';

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Procesa el formulario de búsqueda
$search_results = [];
if (isset($_POST['search'])) {
    $search = mysqli_real_escape_string($conn, $_POST['search']);
    $query = "SELECT id_usuario AS id, usuario, nombre AS real_name FROM usuario 
              WHERE usuario LIKE '%$search%' OR nombre LIKE '%$search%'";
    $result = mysqli_query($conn, $query);

    while ($row = mysqli_fetch_assoc($result)) {
        $search_results[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Búsqueda de Usuarios</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <h2>Búsqueda de Usuarios</h2>
    <form method="post">
        <input type="text" name="search" placeholder="Buscar por nombre de usuario o nombre real" required>
        <button type="submit">Buscar</button>
    </form>

    <?php if (!empty($search_results)): ?>
        <ul>
            <?php foreach ($search_results as $user): ?>
                <li>
                    <?php echo htmlspecialchars($user['usuario']); ?> (<?php echo htmlspecialchars($user['real_name']); ?>)
                    <form method="post" action="send_request.php" style="display: inline;">
                        <input type="hidden" name="friend_id" value="<?php echo $user['id']; ?>">
                        <button type="submit" name="send_request">Enviar solicitud de amistad</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php elseif (isset($_POST['search'])): ?>
        <p>No se encontraron usuarios.</p>
    <?php endif; ?>
    <a href="../index.php">Volver a la pantalla de inicio</a>
</body>
</html>
