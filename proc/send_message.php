<?php
session_start();
include '../bd/conexion.php';

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header('Location: ../view/login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtiene el ID del amigo y el mensaje
    $friend_id = mysqli_real_escape_string($conn, $_POST['friend_id']);
    $message_content = mysqli_real_escape_string($conn, $_POST['message']);

    // Verifica que haya una amistad
    $query = "SELECT * FROM amistad WHERE (id_usuario1 = '$user_id' AND id_usuario2 = '$friend_id') OR (id_usuario1 = '$friend_id' AND id_usuario2 = '$user_id')";
    $result_friendship = mysqli_query($conn, $query);

    if (mysqli_num_rows($result_friendship) > 0) {
        // Inserta el mensaje
        $query = "INSERT INTO mensaje (contenido, id_amistad, id_usuario_remitente) 
                  VALUES ('$message_content', (SELECT id_amistad FROM amistad WHERE (id_usuario1 = '$user_id' AND id_usuario2 = '$friend_id') OR (id_usuario1 = '$friend_id' AND id_usuario2 = '$user_id')), '$user_id')";
        mysqli_query($conn, $query);
    }
    // Redirige de vuelta al chat con el amigo
    header("Location: chat.php?friend_id=" . $friend_id);
    exit();
}
