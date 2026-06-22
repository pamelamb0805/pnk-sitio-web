<?php
include("../config/setup.php");
session_start();

if(!isset($_SESSION['usuario_sesion'])) {
    header("Location:../backend/error.html");
    exit;
}
$esPropietario = ($_SESSION['nombre_perfil'] === 'Propietario');
if (!$esPropietario) {
    header("Location:../backend/error.html");
    exit;
}
// Obtener ID del usuario logueado desde la BD por su nombre
$db = conectar();
$nombre_esc = mysqli_real_escape_string($db, $_SESSION['usuario_sesion']);
$usr = mysqli_fetch_assoc(mysqli_query($db, "SELECT id FROM usuarios WHERE nombre='$nombre_esc' LIMIT 1"));
$id_usuario_sesion = $usr ? $usr['id'] : 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Propietario - PNK Inmobiliaria</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/mystyle.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">🏠 Panel Propietario</a>
    <div class="d-flex">
      <a href="../backend/logout.php" class="btn btn-danger btn-sm">Cerrar sesión</a>
    </div>
  </div>
</nav>

<div class="container-fluid">
  <div class="row">
    <!-- Sidebar -->
    <div class="col-md-2 bg-dark text-white p-3 vh-100">
      <h5 class="mb-4">Menú</h5>
      <ul class="nav flex-column">
        <li class="nav-item"><a class="nav-link text-white" href="propietario.php">Inicio</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="../backend/frm_propiedades.php">Mis Propiedades</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="#">Estado de Publicaciones</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="#">Solicitudes de Visita</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="#">Configuración</a></li>
      </ul>
    </div>

    <!-- Contenido -->
    <div class="col-md-10 p-4">

      <!-- Encabezado usuario -->
      <div class="card shadow mb-4">
        <div class="card-body d-flex justify-content-between align-items-center">
          <div>
            <h4 class="mb-0">Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario_sesion']);?></h4>
            <small class="text-muted"><?php echo htmlspecialchars($_SESSION['nombre_perfil']);?></small>
          </div>
          <div class="d-flex align-items-center gap-3">
            <img src="../img/<?php echo htmlspecialchars($_SESSION['foto_sesion']);?>" alt="Usuario" class="rounded-circle" width="50" height="50" style="object-fit:cover">
          </div>
        </div>
      </div>

      <!-- Cards de resumen -->
      <div class="row mb-4">
        <?php
        $total_mis_prop = mysqli_fetch_row(mysqli_query($db, "SELECT COUNT(*) FROM propiedades WHERE id_usuario='$id_usuario_sesion'"))[0];
        $activas = mysqli_fetch_row(mysqli_query($db, "SELECT COUNT(*) FROM propiedades WHERE id_usuario='$id_usuario_sesion' AND estado='activa'"))[0];
        $vendidas = mysqli_fetch_row(mysqli_query($db, "SELECT COUNT(*) FROM propiedades WHERE id_usuario='$id_usuario_sesion' AND estado='vendida'"))[0];
        ?>
        <div class="col-md-4 mb-3">
          <div class="card bg-primary text-white shadow">
            <div class="card-body text-center">
              <h3><?php echo $total_mis_prop; ?></h3>
              <p class="mb-0">Mis Propiedades</p>
            </div>
          </div>
        </div>
        <div class="col-md-4 mb-3">
          <div class="card bg-success text-white shadow">
            <div class="card-body text-center">
              <h3><?php echo $activas; ?></h3>
              <p class="mb-0">Activas</p>
            </div>
          </div>
        </div>
        <div class="col-md-4 mb-3">
          <div class="card bg-warning text-white shadow">
            <div class="card-body text-center">
              <h3><?php echo $vendidas; ?></h3>
              <p class="mb-0">Vendidas</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Tabla de mis propiedades -->
      <div class="card shadow">
        <div class="card-header bg-dark text-white">Mis Propiedades</div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover align-middle">
              <thead class="table-dark">
                <tr>
                  <th>ID</th>
                  <th>Tipo</th>
                  <th>Descripción</th>
                  <th>Precio $</th>
                  <th>Estado</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  $sql = "SELECT * FROM propiedades WHERE id_usuario='$id_usuario_sesion' ORDER BY id DESC";
                  $result = mysqli_query($db, $sql);
                  while($datos = mysqli_fetch_assoc($result)):
                    $badge = ['activa'=>'success','inactiva'=>'secondary','vendida'=>'danger'];
                    $b = $badge[$datos['estado']] ?? 'secondary';
                ?>
                <tr>
                  <td><?php echo $datos['id'];?></td>
                  <td><?php echo htmlspecialchars($datos['tipo_propiedad']);?></td>
                  <td><?php echo htmlspecialchars(mb_strimwidth($datos['descripcion'], 0, 40, '...'));?></td>
                  <td>$<?php echo number_format($datos['precio_pesos'],0,',','.');?></td>
                  <td><span class="badge bg-<?php echo $b; ?>"><?php echo ucfirst($datos['estado']);?></span></td>
                  <td>
                    <a href="../backend/frm_propiedades.php" class="btn btn-sm btn-warning">Editar</a>
                  </td>
                </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>