<?php
session_start();
require '../../bd/conexion.php';

// Función para validar campos vacíos
function ValidaCampoVacio($campo)
{
    return empty(trim($campo)); // Devuelve true si el campo está vacío
}

// Solo proceder si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errores = false;
    $usuario = $_POST['usuario'];
    $psswrd = $_POST['psswrd'];

    // Validar los datos del login
    if (ValidaCampoVacio($usuario)) {
        $_SESSION['loginUsuarioError'] = "El usuario no puede estar vacío.";
        $errores = true;
    }

    if (ValidaCampoVacio($psswrd)) {
        $_SESSION['loginPsswrdError'] = "La contraseña no puede estar vacía.";
        $errores = true;
    }

    if (!$errores) {
        $usuario = mysqli_real_escape_string($conn, $usuario);

        // Consulta a la base de datos para verificar el usuario
        $sql = "SELECT * FROM usuario WHERE usuario = '$usuario'";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            if (password_verify($psswrd, $row['passwd'])) {
                $_SESSION['user_id'] = $row['id_usuario'];
                $_SESSION['usuario'] = $row['usuario'];
                $_SESSION['mensaje'] = "Bienvenido, " . $row['nombre'] . "!";
                header("Location: ../../index.php");
                exit();
            } else {
                // Contraseña incorrecta
                $_SESSION['loginError'] = "Usuario o contraseña incorrectos.";
            }
        } else {
            // Usuario no encontrado
            $_SESSION['loginError'] = "Usuario o contraseña incorrectos.";
        }
    } else {
        // Si hay un error, almacenar el usuario en la sesión para conservarlo
        $_SESSION['usuario'] = $usuario;
    }

    // Redirigir al formulario de login con mensajes de error
    header("Location: ../../view/login.php");
    exit();
} else {
    // Redirigir si se accede a este archivo sin enviar el formulario
    header("Location: ../../view/login.php");
    exit();
}
