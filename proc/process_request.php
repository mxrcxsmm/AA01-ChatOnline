<?php
session_start();
include '../bd/conexion.php';

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

if (isset($_POST['accept_request']) || isset($_POST['reject_request'])) {
    $request_id = mysqli_real_escape_string($conn, $_POST['request_id']);
    
    if (isset($_POST['accept_request'])) {
        // Aceptar la solicitud
        $query = "UPDATE solicitud_amistad SET status = 'accepted' WHERE id_solicitudAmistad = '$request_id'";
        if (mysqli_query($conn, $query)) {
            // Aquí puedes crear una entrada en la tabla amistad si es necesario
            echo "Solicitud de amistad aceptada. Ahora puedes hablar con tu nuevo amigo.";
            // Redirigir a la página de chat o donde desees
            header('Location: chat.php'); // Cambia esto según tu estructura
            exit();
        } else {
            echo "Error al aceptar la solicitud: " . mysqli_error($conn);
        }
    } elseif (isset($_POST['reject_request'])) {
        // Rechazar la solicitud
        $query = "UPDATE solicitud_amistad SET status = 'rejected' WHERE id_solicitudAmistad = '$request_id'";
        if (mysqli_query($conn, $query)) {
            echo "Solicitud de amistad rechazada.";
            // Redirigir de vuelta a la página de solicitudes
            header('Location: friendship.php'); // Cambia esto según tu estructura
            exit();
        } else {
            echo "Error al rechazar la solicitud: " . mysqli_error($conn);
        }
    }
}
?>
