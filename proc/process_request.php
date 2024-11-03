<?php
session_start();
include '../bd/conexion.php';

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Maneja la aceptación o rechazo de solicitudes
if (isset($_POST['accept_request']) || isset($_POST['reject_request'])) {
    $request_id = mysqli_real_escape_string($conn, $_POST['request_id']);
    
    if (isset($_POST['accept_request'])) {
        // Aceptar la solicitud
        $query = "UPDATE solicitud_amistad SET status = 'accepted' WHERE id_solicitudAmistad = '$request_id'";
        if (mysqli_query($conn, $query)) {
            // Aquí puedes crear una entrada en la tabla amistad si es necesario
            $_SESSION['message'] = "Solicitud de amistad aceptada. Ahora puedes hablar con tu nuevo amigo.";
            // Redirigir a la página de chat o donde desees
            header('Location: chat.php'); // Cambia esto según tu estructura
            exit();
        } else {
            $_SESSION['error'] = "Error al aceptar la solicitud: " . mysqli_error($conn);
        }
    } elseif (isset($_POST['reject_request'])) {
        // Rechazar la solicitud
        $query = "UPDATE solicitud_amistad SET status = 'rejected' WHERE id_solicitudAmistad = '$request_id'";
        if (mysqli_query($conn, $query)) {
            $_SESSION['message'] = "Solicitud de amistad rechazada.";
            // Redirigir de vuelta a la página de solicitudes
            header('Location: send_request.php'); // Cambia esto según tu estructura
            exit();
        } else {
            $_SESSION['error'] = "Error al rechazar la solicitud: " . mysqli_error($conn);
        }
    }

    // Redirigir a la página de solicitudes para mostrar el mensaje
    header('Location: send_request.php'); // Cambia esto según tu estructura
    exit();
}

// Después de redirigir, puedes mostrar mensajes en send_request.php
