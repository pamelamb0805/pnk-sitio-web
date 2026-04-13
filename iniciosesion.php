<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PNK Inmobiliaria</title>
  <link rel="stylesheet" href="css/mystyle.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

</head>

<body>

  <header>
<ul>
  <li class="logo">
    <a href="index.html" class="logo-link">
      <img src="img/logo.png" alt="Logo">
    </a>
  </li>
</ul>

<ul>
 <li class="dropdown">
      <a href="#">Registrate</a>
      <div class="dropdown-content">
        <a href="registropropietario.php">Registrar a un Propietario</a>
        <a href="registrogestor.php">Registrar a un Gestor Inmobiliario</a>
      </div>
    </li>
  <li><a href="iniciosesion.php">Inicio Sesión</a></li>
  <li><a href="contacto.php
    ">Contacto</a></li>
</ul>
</header>


<div class="container text-center mt-5">
  <h2>Selecciona tu tipo de inicio de sesión</h2>
  <div class="mt-4">
    <button class="gestorbtn me-3" onclick="mostrarLogin('propietario')">
      Inicio Sesión como Propietario
    </button>
    <button class="propietariobtn" onclick="mostrarLogin('gestor')">
      Inicio Sesión como Gestor Inmobiliario
    </button>
  </div>
</div>

<!-- Formulario oculto -->
<div id="loginForm" class="container mt-5" style="display:none;">
  <form id="formLogin" method="post">
    <div class="mb-3">
      <label for="correo" class="form-label"><b>Correo</b></label>
      <input type="email" class="form-control" name="correo" required>
    </div>
    <div class="mb-3">
      <label for="psw" class="form-label"><b>Contraseña</b></label>
      <input type="password" class="form-control" name="psw" required>
    </div>
    <div class="form-check mb-3">
      <input type="checkbox" class="form-check-input" name="remember" checked>
      <label class="form-check-label">Recuérdame</label>
    </div>
    <button type="submit" class="btn btn-dark">Iniciar Sesión</button>
  </form>
</div>

<script>
  function mostrarLogin(tipo) {
    document.getElementById('loginForm').style.display = 'block';
    const form = document.getElementById('formLogin');

    // Cambiar el destino según el tipo
    if (tipo === 'gestor') {
      form.action = 'dashboardgestor.php';
    } else {
      form.action = 'dashboardpropietario.php';
    }
  }
</script>

<!--????-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
