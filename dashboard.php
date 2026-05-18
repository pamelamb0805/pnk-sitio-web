<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PNK Inmobiliaria - Dashboard Propietario</title>
  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  
<!-- Banner -->
<header style="background-color:#b0a78f; color:white;" class="p-3">
  <div class="container d-flex justify-content-between align-items-center">
    <div>
      <h3>Bienvenido, Propietario: Juan Pérez</h3>
      <p class="mb-0">Correo: juanperez@ejemplo.com</p>
    </div>
    <img src="img/avatar.png" alt="Avatar" class="rounded-circle" width="60">
  </div>
</header>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg shadow-sm" style="background-color:#b0a78f;">
  <div class="container">
    <a class="navbar-brand fw-bold text-white" href="#">PNK Inmobiliaria</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuPrincipal">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="menuPrincipal">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link text-white" href="#">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="#">Mis Propiedades</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="#">Agregar Propiedad</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="#">Solicitudes de Visita</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="#">Perfil</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="iniciosesion.php">Cerrar Sesión</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- Contenido principal -->
<main class="container my-4">
  <div class="row g-4">
    <!-- Panel Mis Propiedades -->
    <div class="col-md-6">
      <div class="card shadow-sm">
        <div class="card-body">
          <h5 class="card-title">Mis Propiedades</h5>
          <ul class="list-group">
            <li class="list-group-item">Casa en El Milagro - Estado: Publicada 
              <button class="btn btn-sm btn-primary">Editar</button>
              <button class="btn btn-sm btn-danger">Eliminar</button>
            </li>
            <li class="list-group-item">Departamento en Centro - Estado: Pendiente 
              <button class="btn btn-sm btn-primary">Editar</button>
              <button class="btn btn-sm btn-danger">Eliminar</button>
            </li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Panel Agregar Propiedad -->
    <div class="col-md-6">
      <div class="card shadow-sm">
        <div class="card-body">
          <h5 class="card-title">Agregar Nueva Propiedad</h5>
          <p>Formulario para ingresar datos completos de la vivienda:</p>
          <button class="btn btn-success">Crear Propiedad</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Sección adicional -->
  <div class="row mt-4">
    <!-- Solicitudes de Visita -->
    <div class="col-md-6">
      <div class="card shadow-sm">
        <div class="card-body">
          <h5 class="card-title">Solicitudes de Visita</h5>
          <p>No hay solicitudes pendientes.</p>
        </div>
      </div>
    </div>

    <!-- Notificaciones -->
    <div class="col-md-6">
      <div class="card shadow-sm">
        <div class="card-body">
          <h5 class="card-title">Notificaciones</h5>
          <p>Tu cuenta ha sido activada correctamente.</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Perfil -->
  <div class="row mt-4">
    <div class="col-md-12">
      <div class="card shadow-sm">
        <div class="card-body">
          <h5 class="card-title">Perfil de Usuario</h5>
          <p>Nombre: Juan Pérez</p>
          <p>Correo: juanperez@ejemplo.com</p>
          <button class="btn btn-primary">Editar Perfil</button>
          <button class="btn btn-warning">Cambiar Contraseña</button>
        </div>
      </div>
    </div>
  </div>
</main>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
