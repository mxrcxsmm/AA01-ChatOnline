<?php
session_start();
include '../bd/conexion.php';

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Verifica si se ha enviado el ID del amigo
if (!isset($_POST['friend_id'])) {
    echo "No se ha especificado el amigo con el que chatear.";
    exit();
}

$friend_id = mysqli_real_escape_string($conn, $_POST['friend_id']);

// Aquí iría el código para mostrar el chat con el amigo seleccionado.
// Puedes buscar los mensajes entre el usuario y el amigo.

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Chat con Amigo</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <h2>Chat con <?php echo htmlspecialchars($friend_id); ?></h2>
    <!-- Aquí puedes incluir el formulario de envío de mensajes y la visualización del chat -->
</body>
</html>
