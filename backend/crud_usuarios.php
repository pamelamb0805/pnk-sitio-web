<?php
include ("../config/setup.php");

switch($_POST['accion']){
    case "guardar": insertar(); break;
    case "modificar": modificar(); break;
    case "eliminar": eliminar(); break;
    case "cancelar": cancelar(); break;
}

function insertar() {
    $conexion = conectar();

    // Recibir datos del formulario
    $rut       = $_POST['frm_rut'];
    $nombre    = $_POST['frm_nombre'];    // singular
    $apellido  = $_POST['frm_apellido'];  // singular
    $usuario   = $_POST['frmusuario'];    // correo electrónico
    $estado    = $_POST['frm_estado'];
    $idperfil  = $_POST['frm_idperfil'];

    // Query de inserción
    $sql = "INSERT INTO usuarios (rut, nombre, apellido, email, estado, fecha_hora, idperfil) 
            VALUES ('$rut', '$nombre', '$apellido', '$usuario', '$estado', NOW(), '$idperfil')";

    mysqli_query($conexion, $sql) or die("Error en inserción: " . mysqli_error($conexion));
    header("Location: frm_usuarios.php");
}

function modificar() {
    $conexion = conectar();

    // Recibir datos del formulario
    $id       = $_POST['id'];             // campo hidden con el ID del usuario
    $rut      = $_POST['frm_rut'];
    $nombre   = $_POST['frm_nombre'];     // singular
    $apellido = $_POST['frm_apellido'];   // singular
    $usuario  = $_POST['frmusuario'];
    $estado   = $_POST['frm_estado'];
    $idperfil = $_POST['frm_idperfil'];

    // Query de actualización
    $sql = "UPDATE usuarios 
            SET rut='$rut', 
                nombre='$nombre', 
                apellido='$apellido', 
                email='$usuario', 
                estado='$estado', 
                idperfil='$idperfil'
            WHERE id='$id'";

    mysqli_query($conexion, $sql) or die("Error en modificación: " . mysqli_error($conexion));
    header("Location: frm_usuarios.php");
}

function eliminar() {
    $conexion = conectar();
    $id = $_POST['id'];

    $sql = "DELETE FROM usuarios WHERE id='$id'";
    mysqli_query($conexion, $sql) or die("Error en eliminación: " . mysqli_error($conexion));
    header("Location: frm_usuarios.php");
}

function cancelar() {   
    header("Location: frm_usuarios.php");
}
?>
