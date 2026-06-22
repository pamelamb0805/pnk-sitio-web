<?php
include("../config/setup.php");
session_start();

if(!isset($_SESSION['usuario_sesion'])) {
    header("Location:../backend/error.html");
    exit;
}
$esAdministrador = ($_SESSION['nombre_perfil'] === 'Administrador');
if (!$esAdministrador) {
    header("Location:../backend/error.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Administrador - PNK Inmobiliaria</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/mystyle.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">🏠 Panel Administrador</a>
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
        <li class="nav-item"><a class="nav-link text-white" href="admin.php">Inicio</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="../backend/frm_usuarios.php">Usuarios</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="../backend/frm_propiedades.php">Todas las Propiedades</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="../backend/agregar_propiedad.php">+ Nueva Propiedad</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="#">Reportes</a></li>
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
            <a href="../backend/frm_usuarios.php" class="btn btn-primary">Gestionar usuarios</a>
          </div>
        </div>
      </div>

      <!-- Cards de resumen -->
      <div class="row mb-4">
        <?php
        $db = conectar();
        $total_usuarios = mysqli_fetch_row(mysqli_query($db, "SELECT COUNT(*) FROM usuarios"))[0];
        $total_propiedades = mysqli_fetch_row(mysqli_query($db, "SELECT COUNT(*) FROM propiedades"))[0];
        $propiedades_activas = mysqli_fetch_row(mysqli_query($db, "SELECT COUNT(*) FROM propiedades WHERE estado='activa'"))[0];
        $propiedades_vendidas = mysqli_fetch_row(mysqli_query($db, "SELECT COUNT(*) FROM propiedades WHERE estado='vendida'"))[0];
        ?>
        <div class="col-md-3 mb-3">
          <div class="card bg-primary text-white shadow">
            <div class="card-body text-center">
              <h3><?php echo $total_usuarios; ?></h3>
              <p class="mb-0">Usuarios Registrados</p>
            </div>
          </div>
        </div>
        <div class="col-md-3 mb-3">
          <div class="card bg-success text-white shadow">
            <div class="card-body text-center">
              <h3><?php echo $total_propiedades; ?></h3>
              <p class="mb-0">Total Propiedades</p>
            </div>
          </div>
        </div>
        <div class="col-md-3 mb-3">
          <div class="card bg-info text-white shadow">
            <div class="card-body text-center">
              <h3><?php echo $propiedades_activas; ?></h3>
              <p class="mb-0">Propiedades Activas</p>
            </div>
          </div>
        </div>
        <div class="col-md-3 mb-3">
          <div class="card bg-warning text-white shadow">
            <div class="card-body text-center">
              <h3><?php echo $propiedades_vendidas; ?></h3>
              <p class="mb-0">Propiedades Vendidas</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Tabla de usuarios -->
      <div class="card shadow">
        <div class="card-header bg-dark text-white">Últimos usuarios registrados</div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover align-middle">
              <thead class="table-dark">
                <tr>
                  <th>Foto</th>
                  <th>ID</th>
                  <th>Nombre</th>
                  <th>Correo</th>
                  <th>Perfil</th>
                  <th>Estado</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  $sql = "SELECT u.*, p.nombre AS nombre_perfil FROM usuarios u INNER JOIN perfiles p ON u.idperfil = p.idperfil ORDER BY u.id DESC LIMIT 10";
                  $result = mysqli_query($db, $sql);
                  while($datos = mysqli_fetch_array($result)):
                    $foto_usuario = !empty($datos['foto']) ? $datos['foto'] : 'default.png';
                    $estado_activo = (trim(strtolower($datos['estado'])) === 'activo' || $datos['estado'] === '1');
                ?>
                <tr>
                  <td>
                    <img src="../img/<?php echo htmlspecialchars($foto_usuario); ?>"
                         alt="Foto"
                         class="rounded-circle"
                         width="40" height="40"
                         style="object-fit:cover">
                  </td>
                  <td><?php echo $datos['id']; ?></td>
                  <td><?php echo htmlspecialchars($datos['nombre'] . ' ' . $datos['apellido']); ?></td>
                  <td><?php echo htmlspecialchars($datos['email']); ?></td>
                  <td><span class="badge bg-secondary"><?php echo htmlspecialchars($datos['nombre_perfil']); ?></span></td>
                  <td>
                    <?php if($estado_activo): ?>
                      <span class="badge bg-success">Activo</span>
                    <?php else: ?>
                      <span class="badge bg-danger">Inactivo</span>
                    <?php endif; ?>
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
