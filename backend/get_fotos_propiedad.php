<?php
include("../config/setup.php");
session_start();
if (!isset($_SESSION['usuario_sesion'])) {
    echo json_encode([]);
    exit;
}

$id = intval($_GET['id'] ?? 0);
if (!$id) { echo json_encode([]); exit; }

$db = conectar();
$res = mysqli_query($db, "SELECT id, nombre_archivo, es_principal FROM fotos_propiedades WHERE id_propiedad=$id AND estado=1 ORDER BY es_principal DESC, id ASC");

$fotos = [];
while ($f = mysqli_fetch_assoc($res)) {
    $fotos[] = $f;
}

header('Content-Type: application/json');
echo json_encode($fotos);
