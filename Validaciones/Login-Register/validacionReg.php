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
    $nombre = $_POST['nombre'];
    $psswrd = $_POST['psswrd'];

    // Guardar los valores en la sesión
    $_SESSION['usuario'] = $usuario;
    $_SESSION['nombre'] = $nombre;

    // Validación de campos
    if (ValidaCampoVacio($usuario)) {
        $_SESSION['usuarioError'] = "El usuario no puede estar vacío.";
        $errores = true;
    } elseif (!preg_match("/^[a-zA-Z0-9]*$/", $usuario)) {
        $_SESSION['usuarioError'] = "El usuario solo puede contener letras y números.";
        $errores = true;
    } elseif (strlen($usuario) < 3) {
        $_SESSION['usuarioError'] = "El nombre debe tener al menos 3 caracteres.";
        $errores = true;
    }

    if (ValidaCampoVacio($nombre)) {
        $_SESSION['nombreError'] = "El nombre no puede estar vacío.";
        $errores = true;
    } elseif (strlen($nombre) < 2) {
        $_SESSION['nombreError'] = "El nombre debe tener al menos 2 caracteres.";
        $errores = true;
    } elseif (!preg_match("/^[a-zA-Z]*$/", $nombre)) {
        $_SESSION['nombreError'] = "El nombre no puede contener números.";
        $errores = true;
    }

    if (ValidaCampoVacio($psswrd)) {
        $_SESSION['psswrdError'] = "La contraseña no puede estar vacía.";
        $errores = true;
    } elseif (strlen($psswrd) < 6) {
        $_SESSION['psswrdError'] = "La contraseña debe tener al menos 6 caracteres.";
        $errores = true;
    }

    // Si no hay errores, proceder a guardar el usuario
    if (!$errores) {
        $usuario = mysqli_real_escape_string($conn, $usuario);
        $nombre = mysqli_real_escape_string($conn, $nombre);
        $psswrd = password_hash($psswrd, PASSWORD_BCRYPT);

        $sql = "INSERT INTO usuario (usuario, nombre, passwd) VALUES ('$usuario', '$nombre', '$psswrd')";

        if (mysqli_query($conn, $sql)) {
            $_SESSION['mensaje'] = "Registro exitoso. Puedes iniciar sesión.";
            unset($_SESSION['usuario']); // Limpiar la sesión
            unset($_SESSION['nombre']); // Limpiar la sesión
            header("Location: ../../index.php");
            exit();
        } else {
            $_SESSION['error'] = "Error: " . mysqli_error($conn);
            header("Location: ../../view/register.php");
            exit();
        }
    } else {
        // Redirigir al formulario con mensajes de error
        header("Location: ../../view/register.php");
        exit();
    }
} else {
    // Redirigir si se accede a este archivo sin enviar el formulario
    header("Location: ../../view/register.php");
    exit();
}
