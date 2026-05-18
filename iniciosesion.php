<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - PNK Inmobiliaria</title>
    <!-- Tu CSS principal (ya corregido) -->
    <link rel="stylesheet" href="css/mystyle.css">
    <!-- Bootstrap solo para el header y posibles íconos -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>

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

<?php if (isset($_GET['error']) && $_GET['error'] == 1): ?>
    <p style="color:red; text-align:center;">Correo o contraseña incorrecta</p>
<?php endif; ?>

    <form action="iniciar_sesion.php" method="post">
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
            <button type="button" class="cancelbtn" onclick="window.location.href='index.html'">Cancelar</button>
            <button type="submit" class="signupbtn">Iniciar Sesión</button>
        </div>

        <div style="text-align: center; margin-top: 20px; font-size: 0.9rem;">
            ¿No tienes cuenta? <a href="registro.php" style="color: #b0a78f; text-decoration: none;">Regístrate aquí</a><br>
            <a href="#" style="color: #b0a78f; text-decoration: none;">¿Olvidaste tu contraseña?</a>
        </div>
    </form>

    <!-- Bootstrap JS (opcional, solo para el dropdown si lo necesitas) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>