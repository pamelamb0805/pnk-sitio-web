<?php
include("../config/setup.php");
session_start();
if (!isset($_SESSION['usuario_sesion'])) {
    header("Location:../backend/error.html");
    exit;
}

$db = conectar();

/* === Datos del formulario === */
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

/* Características */
$bodega           = isset($_POST['bodega']) ? 1 : 0;
$estacionamiento  = isset($_POST['estacionamiento']) ? 1 : 0;
$logia            = isset($_POST['logia']) ? 1 : 0;
$cocina_amoblada  = isset($_POST['cocina_amoblada']) ? 1 : 0;
$antejardin       = isset($_POST['antejardin']) ? 1 : 0;
$patio_trasero    = isset($_POST['patio_trasero']) ? 1 : 0;
$piscina          = isset($_POST['piscina']) ? 1 : 0;
$solicitar_visita = isset($_POST['solicitar_visita']) ? 1 : 0;

/* Propietario: Admin puede elegir, otros usan su propio ID */
if ($_SESSION['nombre_perfil'] === 'Administrador' && !empty($_POST['id_usuario'])) {
    $id_usuario = intval($_POST['id_usuario']);
} else {
    $id_usuario = intval($_SESSION['id_sesion']);
}

/* Validaciones básicas */
if (!$tipo || !$descripcion || !$precio_pesos || !$precio_uf || !$region || !$comuna) {
    header("Location:../dashboard.php?msg=Faltan+campos+obligatorios&tipo=error");
    exit;
}

/* Insertar propiedad */
$sql = "INSERT INTO propiedades
        (id_usuario, tipo_propiedad, descripcion, banos, dormitorios, area_terreno, area_construida,
         precio_pesos, precio_uf, fecha_publicacion, region, provincia, comuna, sector,
         bodega, estacionamiento, logia, cocina_amoblada, antejardin, patio_trasero, piscina, solicitar_visita)
        VALUES
        ($id_usuario,'$tipo','$descripcion',$banos,$dormitorios,$area_terreno,$area_construida,
         $precio_pesos,$precio_uf,'$fecha_pub','$region','$provincia','$comuna','$sector',
         $bodega,$estacionamiento,$logia,$cocina_amoblada,$antejardin,$patio_trasero,$piscina,$solicitar_visita)";

if (!mysqli_query($db, $sql)) {
    header("Location:../dashboard.php?msg=Error+al+guardar+propiedad&tipo=error");
    exit;
}

$id_propiedad = mysqli_insert_id($db);

/* === Subida de fotos === */
$dir = "../img/propiedades/";
if (!is_dir($dir)) mkdir($dir, 0755, true);

$fotos  = $_FILES['fotos'];
$total  = count($fotos['name']);
$validos= ['image/jpeg','image/png','image/webp'];
$primera= true;

if ($total > 10) {
    header("Location:../dashboard.php?msg=Máximo+10+fotos+permitidas&tipo=warning");
    exit;
}

for ($i = 0; $i < $total; $i++) {
    if ($fotos['error'][$i] !== UPLOAD_ERR_OK) continue;
    if (!in_array($fotos['type'][$i], $validos)) continue;

    $ext       = pathinfo($fotos['name'][$i], PATHINFO_EXTENSION);
    $nombre    = 'prop_' . $id_propiedad . '_' . uniqid() . '.' . strtolower($ext);
    $destino   = $dir . $nombre;

    if (move_uploaded_file($fotos['tmp_name'][$i], $destino)) {
        $es_principal = $primera ? 1 : 0;
        $primera      = false;
        $nombreEsc    = mysqli_real_escape_string($db, $nombre);
        mysqli_query($db, "INSERT INTO fotos_propiedades (id_propiedad, nombre_archivo, es_principal) VALUES ($id_propiedad,'$nombreEsc',$es_principal)");
    }
}

header("Location:../dashboard.php?msg=Propiedad+publicada+exitosamente&tipo=success");
exit;
