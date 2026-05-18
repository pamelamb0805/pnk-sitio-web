<?php
include __DIR__ . "/../config/setup.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rut             = $_POST['rut'];
    $nombre          = $_POST['nombre'];
    $apellido        = $_POST['apellido'];
    $fecha_nacimiento= $_POST['fecha_nacimiento'];
    $genero          = $_POST['genero'];
    $telefono        = $_POST['telefono'];
    $email           = $_POST['email'];
    $clave           = $_POST['pswd'];

    // Conexión ya está en $conn
    $sql = "INSERT INTO usuarios (rut, nombre, apellido, fecha_nacimiento, genero, telefono, email, clave) 
            VALUES ('$rut', '$nombre', '$apellido', '$fecha_nacimiento', '$genero', '$telefono', '$email', '$clave')";

    if ($conn->query($sql) === TRUE) {
        header("Location: iniciosesion.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }

    $conn->close();
}
?>
