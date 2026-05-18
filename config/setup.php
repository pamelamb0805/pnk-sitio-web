<?php
$servername = "localhost";
$username = "root"; // usuario por defecto en WAMP
$password = "";     // contraseña vacía por defecto
$dbname = "pnks";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>