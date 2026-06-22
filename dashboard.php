<?php
include("config/setup.php");
session_start();

if(isset($_SESSION['usuario_sesion']))
{
    $esAdministrador = ($_SESSION['nombre_perfil'] === 'Administrador');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/mystyle.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">🏠 Mi Panel</a>
        <div class="d-flex">
            <a href="backend/logout.php" class="btn btn-danger btn-sm">Cerrar sesión</a>
        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">

        <!-- Sidebar -->
        <div class="col-md-2 bg-dark text-white p-3 vh-100">
            <h5 class="mb-4">Menú</h5>
            <ul class="nav flex-column">
                <li class="nav-item"><a class="nav-link text-white" href="#">Inicio</a></li>
                <?php if ($esAdministrador): ?>
                <li class="nav-item"><a class="nav-link text-white" href="backend/frm_usuarios.php">Usuarios</a></li>
                <?php endif; ?>
                <li class="nav-item"><a class="nav-link text-white" href="backend/frm_propiedades.php">Mis Propiedades</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="backend/agregar_propiedad.php">+ Nueva Propiedad</a></li>
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
                        <h4 class="mb-0">Bienvenido, <?php echo $_SESSION['usuario_sesion'];?></h4>
                        <small class="text-muted"><?php echo $_SESSION['nombre_perfil'];?></small>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <img src="img/<?php echo $_SESSION['foto_sesion'];?>" alt="Usuario" class="rounded-circle" width="50" height="50">
                        <?php if ($esAdministrador): ?>
                        <a href="backend/frm_usuarios.php" class="btn btn-primary">Gestionar usuarios</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Tabla: SOLO visible para Administrador -->
            <?php if ($esAdministrador): ?>
            <div class="card shadow">
                <div class="card-header bg-dark text-white">Últimos registros</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Correo</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $sql="SELECT * FROM usuarios";
                            $result=mysqli_query(conectar(),$sql);
                            while($datos=mysqli_fetch_array($result))
                            {
                            ?>
                            <tr>
                                <td><?php echo $datos['id'];?></td>
                                <td><?php echo $datos['nombre'];?></td>
                                <td><?php echo $datos['email'];?></td>
                                <td>
                                    <?php if($datos['estado']=='1'){ ?>
                                    <span class="badge bg-success">Activo</span>
                                    <?php } else { ?>
                                    <span class="badge bg-danger">Inactivo</span>
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            <!-- FIN bloque Administrador -->

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
}else{
    header("Location:backend/error.html");
}
?>
