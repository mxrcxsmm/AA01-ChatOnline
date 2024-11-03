<?php
session_start();
include '../bd/conexion.php';

// Verifica si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header('Location: ../view/login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Procesa el envío de solicitud de amistad
if (isset($_POST['send_request']) && isset($_POST['receiver_id'])) {
    $receiver_id = mysqli_real_escape_string($conn, $_POST['receiver_id']);

    // Verifica que el usuario no esté enviándose una solicitud a sí mismo
    if ($receiver_id == $user_id) {
        echo "<script>alert('No puedes enviarte una solicitud de amistad a ti mismo.'); window.location.href = '../index.php';</script>";
        exit();
    }

    // Verifica si ya existe una solicitud pendiente o amistad entre los usuarios
    $query = "SELECT * FROM solicitud_amistad 
              WHERE (id_usuario_enviado = '$user_id' AND id_usuario_recibido = '$receiver_id') 
              OR (id_usuario_enviado = '$receiver_id' AND id_usuario_recibido = '$user_id')";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('Ya tienes una solicitud de amistad pendiente o eres amigo de este usuario.'); window.location.href = '../index.php';</script>";
    } else {
        // Inserta la solicitud de amistad
        $query = "INSERT INTO solicitud_amistad (id_usuario_enviado, id_usuario_recibido, status) 
                  VALUES ('$user_id', '$receiver_id', 'pending')";
        
        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Solicitud de amistad enviada exitosamente.'); window.location.href = '../index.php';</script>";
        } else {
            echo "Error al enviar la solicitud: " . mysqli_error($conn);
        }
    }
}
?>
