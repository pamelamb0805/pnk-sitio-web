<?php
include ("../config/setup.php");
session_start();
if (!isset($_SESSION['usuario_sesion'])) {
    header("Location:error.html");
    exit;
}

if (!isset($_POST['accion'])) {
    header("Location: frm_propiedades.php");
    exit;
}

switch($_POST['accion']){
    case "guardar": insertar(); break;
    case "modificar": modificar(); break;
    case "eliminar": eliminar(); break;
    case "cancelar": cancelar(); break;
}

/**
 * Recoge los checkboxes de características como 0/1.
 */
function obtenerChecks() {
    $campos = ['bodega','estacionamiento','logia','cocina_amoblada','antejardin','patio_trasero','piscina','solicitar_visita'];
    $valores = [];
    foreach ($campos as $c) {
        $valores[$c] = isset($_POST['frm_' . $c]) ? 1 : 0;
    }
    return $valores;
}

/**
 * Sube hasta 10 fotos válidas (jpg, png, webp) asociadas a una propiedad.
 * La primera foto subida en una propiedad nueva queda marcada como principal.
 */
function procesarFotos($id_propiedad, $hayFotosPrevias) {
    if (empty($_FILES['frm_fotos']['name'][0])) {
        return; // no se subió ninguna foto
    }

    $conexion = conectar();
    $dir = "../img/propiedades/";
    if (!is_dir($dir)) mkdir($dir, 0755, true);

    $validos = ['image/jpeg', 'image/png', 'image/webp'];
    $fotos = $_FILES['frm_fotos'];
    $total = count($fotos['name']);

    if ($total > 10) {
        $total = 10; // límite de seguridad
    }

    $primera = !$hayFotosPrevias; // si no hay fotos previas, la primera subida será principal

    for ($i = 0; $i < $total; $i++) {
        if ($fotos['error'][$i] !== UPLOAD_ERR_OK) continue;
        if (!in_array($fotos['type'][$i], $validos)) continue;

        $ext = pathinfo($fotos['name'][$i], PATHINFO_EXTENSION);
        $nombre = 'prop_' . $id_propiedad . '_' . uniqid() . '.' . strtolower($ext);
        $destino = $dir . $nombre;

        if (move_uploaded_file($fotos['tmp_name'][$i], $destino)) {
            $es_principal = $primera ? 1 : 0;
            $primera = false;
            $nombreEsc = mysqli_real_escape_string($conexion, $nombre);
            mysqli_query($conexion, "INSERT INTO fotos_propiedades (id_propiedad, nombre_archivo, es_principal) VALUES ('$id_propiedad','$nombreEsc',$es_principal)");
        }
    }
}

function insertar() {
    $conexion = conectar();

    $tipo        = $_POST['frm_tipo'];
    $descripcion = $_POST['frm_descripcion'];
    $banos       = $_POST['frm_banos'];
    $dormitorios = $_POST['frm_dormitorios'];
    $area_terreno    = $_POST['frm_area_terreno'];
    $area_construida = $_POST['frm_area_construida'];
    $precio_pesos = $_POST['frm_precio_pesos'];
    $precio_uf    = $_POST['frm_precio_uf'];
    $fecha_pub    = $_POST['frm_fecha_publicacion'];
    $estado       = $_POST['frm_estado'];
    $id_usuario   = $_SESSION['id_sesion'];

    $region    = mysqli_real_escape_string($conexion, $_POST['frm_region']);
    $provincia = mysqli_real_escape_string($conexion, $_POST['frm_provincia']);
    $comuna    = mysqli_real_escape_string($conexion, $_POST['frm_comuna']);
    $sector    = mysqli_real_escape_string($conexion, $_POST['frm_sector'] ?? '');

    $checks = obtenerChecks();

    $sql = "INSERT INTO propiedades 
            (id_usuario, tipo_propiedad, descripcion, banos, dormitorios, area_terreno, area_construida,
             precio_pesos, precio_uf, region, provincia, comuna, sector, fecha_publicacion, estado,
             bodega, estacionamiento, logia, cocina_amoblada, antejardin, patio_trasero, piscina, solicitar_visita)
            VALUES
            ('$id_usuario','$tipo','$descripcion','$banos','$dormitorios','$area_terreno','$area_construida',
             '$precio_pesos','$precio_uf','$region','$provincia','$comuna','$sector','$fecha_pub','$estado',
             {$checks['bodega']},{$checks['estacionamiento']},{$checks['logia']},{$checks['cocina_amoblada']},
             {$checks['antejardin']},{$checks['patio_trasero']},{$checks['piscina']},{$checks['solicitar_visita']})";

    mysqli_query($conexion, $sql) or die("Error en inserción: " . mysqli_error($conexion));

    $id_propiedad = mysqli_insert_id($conexion);
    procesarFotos($id_propiedad, false);

    header("Location: frm_propiedades.php");
}

function modificar() {
    $conexion = conectar();

    $id = $_POST['id'] ?? '';
    if (empty($id)) {
        header("Location: frm_propiedades.php");
        exit;
    }

    $tipo        = $_POST['frm_tipo'];
    $descripcion = $_POST['frm_descripcion'];
    $banos       = $_POST['frm_banos'];
    $dormitorios = $_POST['frm_dormitorios'];
    $area_terreno    = $_POST['frm_area_terreno'];
    $area_construida = $_POST['frm_area_construida'];
    $precio_pesos = $_POST['frm_precio_pesos'];
    $precio_uf    = $_POST['frm_precio_uf'];
    $fecha_pub    = $_POST['frm_fecha_publicacion'];
    $estado       = $_POST['frm_estado'];

    $region    = mysqli_real_escape_string($conexion, $_POST['frm_region']);
    $provincia = mysqli_real_escape_string($conexion, $_POST['frm_provincia']);
    $comuna    = mysqli_real_escape_string($conexion, $_POST['frm_comuna']);
    $sector    = mysqli_real_escape_string($conexion, $_POST['frm_sector'] ?? '');

    $checks = obtenerChecks();

    $sql = "UPDATE propiedades 
            SET tipo_propiedad='$tipo',
                descripcion='$descripcion',
                banos='$banos',
                dormitorios='$dormitorios',
                area_terreno='$area_terreno',
                area_construida='$area_construida',
                precio_pesos='$precio_pesos',
                precio_uf='$precio_uf',
                region='$region',
                provincia='$provincia',
                comuna='$comuna',
                sector='$sector',
                fecha_publicacion='$fecha_pub',
                estado='$estado',
                bodega={$checks['bodega']},
                estacionamiento={$checks['estacionamiento']},
                logia={$checks['logia']},
                cocina_amoblada={$checks['cocina_amoblada']},
                antejardin={$checks['antejardin']},
                patio_trasero={$checks['patio_trasero']},
                piscina={$checks['piscina']},
                solicitar_visita={$checks['solicitar_visita']}
            WHERE id='$id'";

    mysqli_query($conexion, $sql) or die("Error en modificación: " . mysqli_error($conexion));

    // ¿Ya tenía fotos? para no marcar una segunda principal por error
    $check_fotos = mysqli_query($conexion, "SELECT id FROM fotos_propiedades WHERE id_propiedad='$id' LIMIT 1");
    $hayFotosPrevias = $check_fotos && mysqli_num_rows($check_fotos) > 0;

    procesarFotos($id, $hayFotosPrevias);

    header("Location: frm_propiedades.php");
}

function eliminar() {
    $conexion = conectar();

    $id = $_POST['id'] ?? '';
    if (empty($id)) {
        header("Location: frm_propiedades.php");
        exit;
    }

    // Borrar archivos físicos de las fotos asociadas
    $fotos = mysqli_query($conexion, "SELECT nombre_archivo FROM fotos_propiedades WHERE id_propiedad='$id'");
    while ($f = mysqli_fetch_assoc($fotos)) {
        $ruta = "../img/propiedades/" . $f['nombre_archivo'];
        if (file_exists($ruta)) {
            @unlink($ruta);
        }
    }

    // Las fotos se eliminan en cascada por la FK (ON DELETE CASCADE)
    $sql = "DELETE FROM propiedades WHERE id='$id'";
    mysqli_query($conexion, $sql) or die("Error en eliminación: " . mysqli_error($conexion));

    header("Location: frm_propiedades.php");
}

function cancelar() {
    header("Location: frm_propiedades.php");
}
?>
