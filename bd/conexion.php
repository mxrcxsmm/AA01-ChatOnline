<?php
$host = "localhost";
$dbname = "db_chatonline";
$username = "root";
$password = "";

$conn = mysqli_connect($host, $username, $password, $dbname);
if (!$conn) {
    die("Error en la conexión: " . mysqli_connect_error());
}
