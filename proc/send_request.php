<?php
session_start();
include '../bd/conexion.php';

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Procesa el envío de la solicitud de amistad
if (isset($_POST['send_request'])) {
    $friend_id = mysqli_real_escape_string($conn, $_POST['friend_id']);
    $user_id = $_SESSION['user_id'];

    // Inserta la solicitud de amistad en la base de datos
    $insert_query = "INSERT INTO solicitud_amistad (id_usuario_enviado, id_usuario_recibido, status) 
                     VALUES ('$user_id', '$friend_id', 'pending')";

    if (mysqli_query($conn, $insert_query)) {
        echo "Solicitud de amistad enviada.";
    } else {
        echo "Error al enviar la solicitud: " . mysqli_error($conn);
    }
}

// Redirige de vuelta a la página de búsqueda
header('Location: search_users.php');
exit();
?>
