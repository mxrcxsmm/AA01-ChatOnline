<?php
session_start();
include '../bd/conexion.php';

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Procesa el envío de la solicitud de amistad
if (isset($_POST['send_request']) && isset($_POST['friend_id'])) {
    $sender_id = $_SESSION['user_id'];
    $receiver_id = mysqli_real_escape_string($conn, $_POST['friend_id']);
    
    // Inserta la solicitud de amistad en la base de datos
    $query = "INSERT INTO solicitud_amistad (id_usuario_enviado, id_usuario_recibido, status) 
              VALUES ('$sender_id', '$receiver_id', 'pending')";
    
    if (mysqli_query($conn, $query)) {
        // Redirige a la página de búsqueda de usuarios después de enviar la solicitud
        header('Location: search_users.php?request_sent=1');
        exit();
    } else {
        echo "Error al enviar la solicitud: " . mysqli_error($conn);
    }
}
?>
