<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - PNK Inmobiliaria</title>
    <link rel="stylesheet" href="css/mystyle.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>

<div class="modal fade" id="errorModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title">Error de inicio de sesión</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <?php if (isset($_GET['error'])): ?>
            <?php if ($_GET['error'] == 1): ?>
                Debes completar todos los campos.
            <?php elseif ($_GET['error'] == 2): ?>
                Ingrese un correo registrado.
            <?php elseif ($_GET['error'] == 3): ?>
                Contraseña incorrecta.
            <?php elseif ($_GET['error'] == 4): ?>
                Tu cuenta está inactiva.
            <?php endif; ?>
        <?php endif; ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

  <header>
    <ul>
      <li class="logo">
        <a href="index.html"><img src="img/logo.png" alt="Logo PNK"></a>
      </li>
    </ul>
    <ul>
        <li><a href="registro.php">Registrate</a></li>
        <li><a href="iniciosesion.php">Inicio Sesión</a></li>
        <li><a href="contacto.php">Contacto</a></li>
    </ul>
  </header>

  <?php if (isset($_GET['error'])): ?>
      <?php if ($_GET['error'] == 1): ?>
          <p style="color:red; text-align:center;">Debes completar todos los campos</p>
      <?php elseif ($_GET['error'] == 2): ?>
          <p style="color:red; text-align:center;">El usuario no existe</p>
      <?php elseif ($_GET['error'] == 3): ?>
          <p style="color:red; text-align:center;">Contraseña incorrecta</p>
      <?php elseif ($_GET['error'] == 4): ?>
          <p style="color:red; text-align:center;">Tu cuenta está inactiva</p>
      <?php endif; ?>
  <?php endif; ?>

    <form action=" backend/iniciar_sesion.php" method="POST">

        <h1>Iniciar Sesión</h1>
        <hr>

        <label for="email"><b>Correo electrónico</b></label>
        <input type="email" placeholder="ejemplo@correo.com" name="email" id="email" required>

        <label for="password"><b>Contraseña</b></label>
        <input type="password" placeholder="Ingresa tu contraseña" name="password" id="password" required>

        <label>
            <input type="checkbox" checked="checked" name="remember"> Recordar sesión
        </label>

        <div class="clearfix">
            <button type="submit" class="signupbtn">Iniciar Sesión</button>
        </div>

        <div style="text-align: center; margin-top: 20px; font-size: 0.9rem;">
            ¿No tienes cuenta? <a href="registro.php" style="color: #b0a78f; text-decoration: none;">Regístrate aquí</a><br>
            <a href="#" style="color: #b0a78f; text-decoration: none;">¿Olvidaste tu contraseña?</a>
        </div>
    </form>

    <!-- Bootstrap JS (opcional, solo para el dropdown si lo necesitas) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  <?php if (isset($_GET['error'])): ?>
    var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
    errorModal.show();
  <?php endif; ?>
</script>

</body>

</html>
