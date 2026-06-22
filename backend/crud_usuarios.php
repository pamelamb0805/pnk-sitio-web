<?php
include ("../config/setup.php");

if (!isset($_POST['accion'])) {
    header("Location: frm_usuarios.php");
    exit;
}

switch($_POST['accion']){
    case "guardar": insertar(); break;
    case "modificar": modificar(); break;
    case "eliminar": eliminar(); break;
    case "cancelar": cancelar(); break;
}

/**
 * Procesa la subida de foto si viene un archivo válido.
 * Devuelve el nombre del archivo guardado, o false si no se subió nada.
 */
function procesarFoto() {
    if (!isset($_FILES['frm_foto']) || $_FILES['frm_foto']['error'] !== UPLOAD_ERR_OK) {
        return false; // no se subió ningún archivo nuevo
    }

    $validos = ['image/jpeg', 'image/png', 'image/webp'];
    if (!in_array($_FILES['frm_foto']['type'], $validos)) {
        return false; // formato no permitido, se ignora
    }

    $dir = "../img/";
    if (!is_dir($dir)) mkdir($dir, 0755, true);

    $ext    = pathinfo($_FILES['frm_foto']['name'], PATHINFO_EXTENSION);
    $nombre = 'user_' . uniqid() . '.' . strtolower($ext);
    $destino = $dir . $nombre;

    if (move_uploaded_file($_FILES['frm_foto']['tmp_name'], $destino)) {
        return $nombre;
    }
    return false;
}

function insertar() {
    $conexion = conectar();

    // Recibir datos del formulario
    $rut       = $_POST['frm_rut'];
    $nombre    = $_POST['frm_nombre'];
    $apellido  = $_POST['frm_apellido'];
    $usuario   = $_POST['frmusuario'];
    $estado    = $_POST['frm_estado'];
    $idperfil  = $_POST['frm_idperfil'];

    // Foto: si se subió una válida, se usa; si no, queda default.png
    $foto = procesarFoto();
    if ($foto === false) {
        $foto = 'default.png';
    }
    $foto = mysqli_real_escape_string($conexion, $foto);

    // Query de inserción
    $sql = "INSERT INTO usuarios (rut, nombre, apellido, email, estado, fecha_hora, foto, idperfil) 
            VALUES ('$rut', '$nombre', '$apellido', '$usuario', '$estado', NOW(), '$foto', '$idperfil')";

    mysqli_query($conexion, $sql) or die("Error en inserción: " . mysqli_error($conexion));
    header("Location: frm_usuarios.php");
}

function modificar() {
    $conexion = conectar();

    $id = $_POST['id'] ?? '';
    if (empty($id)) {
        header("Location: frm_usuarios.php");
        exit;
    }

    // Recibir datos del formulario
    $rut      = $_POST['frm_rut'];
    $nombre   = $_POST['frm_nombre'];
    $apellido = $_POST['frm_apellido'];
    $usuario  = $_POST['frmusuario'];
    $estado   = $_POST['frm_estado'];
    $idperfil = $_POST['frm_idperfil'];

    // Foto: solo se reemplaza si el admin subió una nueva
    $foto_nueva = procesarFoto();

    if ($foto_nueva !== false) {
        // Borrar la foto anterior del servidor (si no es la default)
        $row_actual = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT foto FROM usuarios WHERE id='$id'"));
        if ($row_actual && !empty($row_actual['foto']) && $row_actual['foto'] !== 'default.png') {
            $ruta_anterior = "../img/" . $row_actual['foto'];
            if (file_exists($ruta_anterior)) {
                @unlink($ruta_anterior);
            }
        }

        $foto_nueva = mysqli_real_escape_string($conexion, $foto_nueva);
        $sql = "UPDATE usuarios 
                SET rut='$rut', 
                    nombre='$nombre', 
                    apellido='$apellido', 
                    email='$usuario', 
                    estado='$estado', 
                    idperfil='$idperfil',
                    foto='$foto_nueva'
                WHERE id='$id'";
    } else {
        // No se subió foto nueva: se mantiene la que ya tenía
        $sql = "UPDATE usuarios 
                SET rut='$rut', 
                    nombre='$nombre', 
                    apellido='$apellido', 
                    email='$usuario', 
                    estado='$estado', 
                    idperfil='$idperfil'
                WHERE id='$id'";
    }

    mysqli_query($conexion, $sql) or die("Error en modificación: " . mysqli_error($conexion));
    header("Location: frm_usuarios.php");
}

function eliminar() {
    $conexion = conectar();

    $id = $_POST['id'] ?? '';
    if (empty($id)) {
        header("Location: frm_usuarios.php");
        exit;
    }

    // Borrar la foto física del usuario (si no es la default)
    $row_actual = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT foto FROM usuarios WHERE id='$id'"));
    if ($row_actual && !empty($row_actual['foto']) && $row_actual['foto'] !== 'default.png') {
        $ruta = "../img/" . $row_actual['foto'];
        if (file_exists($ruta)) {
            @unlink($ruta);
        }
    }

    $sql = "DELETE FROM usuarios WHERE id='$id'";
    mysqli_query($conexion, $sql) or die("Error en eliminación: " . mysqli_error($conexion));
    header("Location: frm_usuarios.php");
}

function cancelar() {   
    header("Location: frm_usuarios.php");
}
?>
