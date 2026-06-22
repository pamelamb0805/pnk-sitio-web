<?php
/* editar_propiedad.php - carga formulario con datos existentes */
include("config/setup.php");
session_start();
if (!isset($_SESSION['usuario_sesion'])) { header("Location:backend/error.html"); exit; }

$id_prop = intval($_GET['id'] ?? 0);
$db = conectar();

/* Seguridad: propietario solo edita sus propias propiedades */
if ($_SESSION['nombre_perfil'] === 'Propietario') {
    $prop = mysqli_fetch_assoc(mysqli_query($db,
        "SELECT * FROM propiedades WHERE id=$id_prop AND id_usuario={$_SESSION['id_sesion']}"));
} else {
    $prop = mysqli_fetch_assoc(mysqli_query($db, "SELECT * FROM propiedades WHERE id=$id_prop"));
}

if (!$prop) { header("Location:dashboard.php?msg=Propiedad+no+encontrada&tipo=error"); exit; }

$esAdministrador = ($_SESSION['nombre_perfil'] === 'Administrador');
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Editar Propiedad - PNK Inmobiliaria</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="css/mystyle.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-dark shadow px-3">
  <a class="navbar-brand" href="dashboard.php">← Volver al Dashboard</a>
</nav>
<div class="container py-4">
  <?php include('backend/frm_propiedad.php'); ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
