<?php
include("../config/setup.php");
session_start();
if (!isset($_SESSION['usuario_sesion'])) { header("Location:../backend/error.html"); exit; }

$db = conectar();
$id_prop = intval($_POST['id_propiedad']);

/* Verificar propiedad pertenece al usuario (excepto admin) */
if ($_SESSION['nombre_perfil'] === 'Propietario') {
    $check = mysqli_fetch_row(mysqli_query($db, "SELECT id FROM propiedades WHERE id=$id_prop AND id_usuario={$_SESSION['id_sesion']}"));
    if (!$check) { header("Location:../dashboard.php?msg=Acceso+denegado&tipo=error"); exit; }
}

$tipo        = mysqli_real_escape_string($db, $_POST['tipo_propiedad']);
$descripcion = mysqli_real_escape_string($db, $_POST['descripcion']);
$banos       = intval($_POST['banos'] ?? 0);
$dormitorios = intval($_POST['dormitorios'] ?? 0);
$area_terreno   = floatval($_POST['area_terreno'] ?? $_POST['area_terreno_solo'] ?? 0);
$area_construida= floatval($_POST['area_construida'] ?? 0);
$precio_pesos= intval($_POST['precio_pesos']);
$precio_uf   = floatval($_POST['precio_uf']);
$fecha_pub   = mysqli_real_escape_string($db, $_POST['fecha_publicacion']);
$region      = mysqli_real_escape_string($db, $_POST['region']);
$provincia   = mysqli_real_escape_string($db, $_POST['provincia']);
$comuna      = mysqli_real_escape_string($db, $_POST['comuna']);
$sector      = mysqli_real_escape_string($db, $_POST['sector'] ?? '');

$bodega           = isset($_POST['bodega']) ? 1 : 0;
$estacionamiento  = isset($_POST['estacionamiento']) ? 1 : 0;
$logia            = isset($_POST['logia']) ? 1 : 0;
$cocina_amoblada  = isset($_POST['cocina_amoblada']) ? 1 : 0;
$antejardin       = isset($_POST['antejardin']) ? 1 : 0;
$patio_trasero    = isset($_POST['patio_trasero']) ? 1 : 0;
$piscina          = isset($_POST['piscina']) ? 1 : 0;
$solicitar_visita = isset($_POST['solicitar_visita']) ? 1 : 0;

$sql = "UPDATE propiedades SET
        tipo_propiedad='$tipo', descripcion='$descripcion', banos=$banos, dormitorios=$dormitorios,
        area_terreno=$area_terreno, area_construida=$area_construida, precio_pesos=$precio_pesos,
        precio_uf=$precio_uf, fecha_publicacion='$fecha_pub', region='$region', provincia='$provincia',
        comuna='$comuna', sector='$sector', bodega=$bodega, estacionamiento=$estacionamiento,
        logia=$logia, cocina_amoblada=$cocina_amoblada, antejardin=$antejardin,
        patio_trasero=$patio_trasero, piscina=$piscina, solicitar_visita=$solicitar_visita
        WHERE id=$id_prop";

if (!mysqli_query($db, $sql)) {
    header("Location:../editar_propiedad.php?id=$id_prop&msg=Error+al+actualizar&tipo=error"); exit;
}

/* Foto principal */
if (!empty($_POST['foto_principal'])) {
    $fp = intval($_POST['foto_principal']);
    mysqli_query($db, "UPDATE fotos_propiedades SET es_principal=0 WHERE id_propiedad=$id_prop");
    mysqli_query($db, "UPDATE fotos_propiedades SET es_principal=1 WHERE id=$fp AND id_propiedad=$id_prop");
}

/* Eliminar fotos seleccionadas */
if (!empty($_POST['eliminar_foto'])) {
    foreach ($_POST['eliminar_foto'] as $fid) {
        $fid = intval($fid);
        $row = mysqli_fetch_assoc(mysqli_query($db, "SELECT nombre_archivo FROM fotos_propiedades WHERE id=$fid"));
        if ($row) {
            @unlink("../img/propiedades/".$row['nombre_archivo']);
            mysqli_query($db, "DELETE FROM fotos_propiedades WHERE id=$fid");
        }
    }
}

/* Subir fotos nuevas */
if (!empty($_FILES['fotos']['name'][0])) {
    $dir    = "../img/propiedades/";
    $validos= ['image/jpeg','image/png','image/webp'];
    $fotos  = $_FILES['fotos'];
    $total  = count($fotos['name']);

    for ($i = 0; $i < min($total, 10); $i++) {
        if ($fotos['error'][$i] !== UPLOAD_ERR_OK) continue;
        if (!in_array($fotos['type'][$i], $validos)) continue;
        $ext    = pathinfo($fotos['name'][$i], PATHINFO_EXTENSION);
        $nombre = 'prop_'.$id_prop.'_'.uniqid().'.'.strtolower($ext);
        if (move_uploaded_file($fotos['tmp_name'][$i], $dir.$nombre)) {
            $nombreEsc = mysqli_real_escape_string($db, $nombre);
            mysqli_query($db, "INSERT INTO fotos_propiedades (id_propiedad,nombre_archivo,es_principal) VALUES ($id_prop,'$nombreEsc',0)");
        }
    }
}

header("Location:../dashboard.php?msg=Propiedad+actualizada+correctamente&tipo=success");
exit;
