<?php
include ("../config/setup.php"); // setup.php está en la raíz

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email    = $_POST['email'];
    $password = $_POST['password'];

    // Validación básica: campos vacíos
    if (empty($email) || empty($password)) {
        header("Location: ../iniciosesion.php?error=1");
        exit();
    }

    $conexion = conectar();

    // Buscar usuario por email
    $sql = "SELECT usuarios.*, perfiles.nombre AS nombre_perfil 
            FROM usuarios 
            INNER JOIN perfiles ON usuarios.idperfil = perfiles.idperfil 
            WHERE usuarios.email='$email'";
    $result = mysqli_query($conexion, $sql);

    if (mysqli_num_rows($result) == 0) {
        // Email no registrado
        header("Location: ../iniciosesion.php?error=2");
        exit();
    }

    $datos = mysqli_fetch_array($result);

    // Validar contraseña
    if ($datos['clave'] !== $password) {
        header("Location: ../iniciosesion.php?error=3");
        exit();
    }

    // Validar estado (normalizado)
    if (trim(strtolower($datos['estado'])) !== 'activo') {
        header("Location: ../iniciosesion.php?error=4");
        exit();
    }

    // Si todo está OK → iniciar sesión
    session_start();
    $_SESSION['usuario_sesion'] = $datos['nombre'];   // campo correcto en tu BD
    $_SESSION['foto_sesion']    = $datos['foto'];
    $_SESSION['nombre_perfil']  = $datos['nombre_perfil'];

    header("Location: ../dashboard.php"); // dashboard en raíz
    exit();

    mysqli_close($conexion);
}
?>
