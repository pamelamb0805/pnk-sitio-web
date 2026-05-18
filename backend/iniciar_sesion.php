<?php
include ("../config/setup.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email    = $_POST['email'];
    $password = $_POST['password'];

    $conexion = conectar();

    // Consulta: busca usuario por email y clave
    $sql = "SELECT usuarios.*, perfiles.nombre AS nombre_perfil 
            FROM usuarios 
            INNER JOIN perfiles ON usuarios.idperfil = perfiles.idperfil 
            WHERE usuarios.email='$email' AND usuarios.clave='$password'";

    $result = mysqli_query($conexion, $sql);
    $contar = mysqli_num_rows($result);
    $datos  = mysqli_fetch_array($result);

    if ($contar != 0) {
        if ($datos['estado'] == 'activo') {
            session_start();
            // Guardamos datos relevantes en la sesión
            $_SESSION['usuario_sesion'] = $datos['nombre'] . " " . $datos['apellido'];
            $_SESSION['rut_sesion']     = $datos['rut'];
            $_SESSION['email_sesion']   = $datos['email'];
            $_SESSION['telefono_sesion']= $datos['telefono'];
            $_SESSION['nombre_perfil']  = $datos['nombre_perfil'];
            header("Location: dashboard.php");
        } else {
            // Usuario existe pero está inactivo
            header("Location: backend/error.html");
        }
    } else {
        // Credenciales incorrectas
        header("Location: iniciosesion.php?error=1");
    }

    mysqli_close($conexion);
}
?>

