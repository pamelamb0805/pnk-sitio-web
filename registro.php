<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Registro - PNK Inmobiliaria</title>
<link rel="stylesheet" href="css/mystyle.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
.tab-registro {
    max-width: 700px;
    margin: 30px auto;
    padding: 0 20px;
}
.tab-buttons {
    display: flex;
    justify-content: center;
    gap: 0;
    margin-bottom: 0;
}
.tab-buttons button {
    padding: 14px 40px;
    border: none;
    background-color: #dedbc1;
    color: #3c3c3c;
    font-weight: bold;
    cursor: pointer;
    transition: background-color 0.3s ease, color 0.3s ease;
    font-size: 1rem;
    letter-spacing: 0.5px;
    flex: 1;
    max-width: 250px;
}
.tab-buttons button:first-child { border-radius: 10px 0 0 0; }
.tab-buttons button:last-child  { border-radius: 0 10px 0 0; }
.tab-buttons button.active { background-color: #b0a78f; color: white; }
.tab-buttons button:hover:not(.active) { background-color: #c9c1a7; color: #3c3c3c; }
.tab-panel { display: none; }
.tab-panel.active { display: block; }
.tab-panel form { border-radius: 0 0 15px 15px; margin-top: 0; }
.error-msg { color: red; font-size: 0.85em; display: block; margin-bottom: 6px; }
</style>
</head>
<body>
<header>
  <ul>
    <li class="logo"><a href="index.php"><img src="img/logo.png" alt="Logo PNK"></a></li>
  </ul>
  <ul>
    <li><a href="registro.php">Registrate</a></li>
    <li><a href="iniciosesion.php">Inicio Sesión</a></li>
    <li><a href="contacto.php">Contacto</a></li>
  </ul>
</header>

<div class="tab-registro">
  <div class="tab-buttons">
    <button id="tabPropietario" class="active" onclick="mostrarPestana('propietario')">Propietario</button>
    <button id="tabGestor" onclick="mostrarPestana('gestor')">Gestor Inmobiliario</button>
  </div>

  <!-- PROPIETARIO -->
  <div id="panelPropietario" class="tab-panel active">
    <form id="formPropietario" action="backend/registrar_usuario.php" method="post" novalidate>
      <div class="container">
        <h1>Registrarse</h1>
        <p>Rellene el formulario</p>
        <hr>

        <label><b>Rut</b></label>
        <input type="text" placeholder="Ej: 12.345.678-9" name="rut" id="rut_p" maxlength="12">
        <span class="error-msg" id="err_rut_p"></span>

        <label><b>Nombre</b></label>
        <input type="text" placeholder="Ingrese su nombre" name="nombre" id="nombre_p">
        <span class="error-msg" id="err_nombre_p"></span>

        <label><b>Apellidos</b></label>
        <input type="text" placeholder="Ingrese sus Apellidos" name="apellido" id="apellido_p">
        <span class="error-msg" id="err_apellido_p"></span>

        <label><b>Fecha nacimiento</b></label>
        <input type="date" name="fecha_nacimiento" id="fecha_p">
        <span class="error-msg" id="err_fecha_p"></span>

        <label>Sexo:</label>
        <select name="genero" id="genero_p">
          <option value="">-- Seleccione --</option>
          <option value="Masculino">Masculino</option>
          <option value="Femenino">Femenino</option>
        </select>
        <span class="error-msg" id="err_genero_p"></span>

        <label><b>Teléfono</b></label>
        <input type="text" placeholder="+56912345678" name="telefono" id="telefono_p">
        <span class="error-msg" id="err_telefono_p"></span>

        <label><b>Email</b></label>
        <input type="text" placeholder="ejemplo@correo.com" name="email" id="email_p">
        <span class="error-msg" id="err_email_p"></span>

        <label><b>Contraseña</b></label>
        <input type="password" placeholder="Mínimo 8 caracteres" name="pswd" id="pswd_p">
        <span class="error-msg" id="err_pswd_p"></span>

        <label><b>Repita su contraseña</b></label>
        <input type="password" placeholder="Repita su contraseña" name="pswd-repeat" id="pswd2_p">
        <span class="error-msg" id="err_pswd2_p"></span>

        <label style="margin-bottom:15px">
          <input type="checkbox" name="Recordar"> Recuérdame
        </label>
        <p>
          <label>
            <input type="checkbox" name="terminos" id="terminos_p">
            Acepto los <a href="#" style="color:dodgerblue">Términos y condiciones</a>.
          </label>
          <span class="error-msg" id="err_terminos_p"></span>
        </p>

        <div class="clearfix">
          <button type="button" class="cancelbtn" onclick="window.location.href='index.php'">Cancelar</button>
          <input type="hidden" name="idperfil" value="2">
          <button type="submit" name="registrar_propietario">Registrar</button>
        </div>
      </div>
    </form>
  </div>

  <!-- GESTOR -->
  <div id="panelGestor" class="tab-panel">
    <form id="formGestor" action="backend/registrar_usuario.php" method="post" enctype="multipart/form-data" novalidate>
      <div class="container">
        <h1>Registrarse</h1>
        <p>Rellene el formulario</p>
        <hr>

        <label><b>Rut</b></label>
        <input type="text" placeholder="Ej: 12.345.678-9" name="rut" id="rut_g" maxlength="12">
        <span class="error-msg" id="err_rut_g"></span>

        <label><b>Nombre</b></label>
        <input type="text" placeholder="Ingrese su nombre" name="nombre" id="nombre_g">
        <span class="error-msg" id="err_nombre_g"></span>

        <label><b>Apellidos</b></label>
        <input type="text" placeholder="Ingrese sus Apellidos" name="apellido" id="apellido_g">
        <span class="error-msg" id="err_apellido_g"></span>

        <label><b>Fecha nacimiento</b></label>
        <input type="date" name="fecha_nacimiento" id="fecha_g">
        <span class="error-msg" id="err_fecha_g"></span>

        <label>Sexo:</label>
        <select name="genero" id="genero_g">
          <option value="">-- Seleccione --</option>
          <option value="Masculino">Masculino</option>
          <option value="Femenino">Femenino</option>
        </select>
        <span class="error-msg" id="err_genero_g"></span>

        <label><b>Teléfono</b></label>
        <input type="text" placeholder="+56912345678" name="telefono" id="telefono_g">
        <span class="error-msg" id="err_telefono_g"></span>

        <label><b>Email</b></label>
        <input type="text" placeholder="ejemplo@correo.com" name="email" id="email_g">
        <span class="error-msg" id="err_email_g"></span>

        <label><b>Contraseña</b></label>
        <input type="password" placeholder="Mínimo 8 caracteres" name="pswd" id="pswd_g">
        <span class="error-msg" id="err_pswd_g"></span>

        <label><b>Repita su contraseña</b></label>
        <input type="password" placeholder="Repita su contraseña" name="pswd-repeat" id="pswd2_g">
        <span class="error-msg" id="err_pswd2_g"></span>

        <label><b>Certificado de Antecedentes</b></label>
        <input type="file" name="certificado" id="cert_g" accept=".pdf,.jpg,.jpeg,.png">
        <span class="error-msg" id="err_cert_g"></span>

        <label style="margin-bottom:15px">
          <input type="checkbox" name="Recordar"> Recuérdame
        </label>
        <p>
          <label>
            <input type="checkbox" name="terminos" id="terminos_g">
            Acepto los <a href="#" style="color:dodgerblue">Términos y condiciones</a>.
          </label>
          <span class="error-msg" id="err_terminos_g"></span>
        </p>

        <div class="clearfix">
          <button type="button" class="cancelbtn" onclick="window.location.href='index.php'">Cancelar</button>
          <input type="hidden" name="idperfil" value="3">
          <button type="submit" name="btnGestor">Registrar</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Mostrar mensajes de éxito/error desde backend -->
<?php if (isset($_GET['registro']) && $_GET['registro'] == 'ok'): ?>
<script>
window.addEventListener('DOMContentLoaded', function() {
  Swal.fire({ icon: 'success', title: '¡Registro exitoso!', text: 'Tu cuenta ha sido creada correctamente.', confirmButtonColor: '#b0a78f' })
  .then(() => { window.location.href = 'iniciosesion.php'; });
});
</script>
<?php elseif (isset($_GET['error'])): ?>
<script>
window.addEventListener('DOMContentLoaded', function() {
  Swal.fire({ icon: 'error', title: 'Error en el registro', text: '<?php echo htmlspecialchars($_GET["error"]); ?>', confirmButtonColor: '#b0a78f' });
});
</script>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
/* ============================================================
   VALIDACIÓN DE RUT CHILENO (dígito verificador real)
   ============================================================ */
function limpiarRut(rut) {
    return rut.replace(/[^0-9kK]/g, '').toUpperCase();
}

function formatearRut(rut) {
    let limpio = limpiarRut(rut);
    if (limpio.length < 2) return rut;
    let cuerpo = limpio.slice(0, -1);
    let dv = limpio.slice(-1);
    cuerpo = cuerpo.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    return cuerpo + '-' + dv;
}

function validarRutChileno(rut) {
    let limpio = limpiarRut(rut);
    if (limpio.length < 2) return false;
    let cuerpo = limpio.slice(0, -1);
    let dv    = limpio.slice(-1);
    if (cuerpo.length < 7 || cuerpo.length > 8) return false;

    let suma = 0, multiplo = 2;
    for (let i = cuerpo.length - 1; i >= 0; i--) {
        suma += parseInt(cuerpo[i]) * multiplo;
        multiplo = multiplo === 7 ? 2 : multiplo + 1;
    }
    let dvEsperado = 11 - (suma % 11);
    if (dvEsperado === 11) dvEsperado = '0';
    else if (dvEsperado === 10) dvEsperado = 'K';
    else dvEsperado = String(dvEsperado);

    return dv === dvEsperado;
}

/* Autoformateo de RUT al escribir */
['rut_p', 'rut_g'].forEach(function(id) {
    var campo = document.getElementById(id);
    if (!campo) return;
    campo.addEventListener('input', function() {
        var pos = this.selectionStart;
        this.value = formatearRut(this.value);
    });
});

/* ============================================================
   VALIDACIÓN GENERAL DE FORMULARIO
   ============================================================ */
function validarForm(sufijo) {
    var valido = true;

    function mostrarError(id, msg) {
        var el = document.getElementById('err_' + id + '_' + sufijo);
        if (el) { el.textContent = msg; }
        valido = false;
    }
    function limpiar(id) {
        var el = document.getElementById('err_' + id + '_' + sufijo);
        if (el) el.textContent = '';
    }

    // Limpiar todos
    ['rut','nombre','apellido','fecha','genero','telefono','email','pswd','pswd2','terminos','cert'].forEach(limpiar);

    // RUT
    var rut = document.getElementById('rut_' + sufijo).value.trim();
    if (!rut) { mostrarError('rut', 'El RUT es obligatorio.'); }
    else if (!validarRutChileno(rut)) { mostrarError('rut', 'RUT inválido: dígito verificador incorrecto.'); }

    // Nombre
    var nombre = document.getElementById('nombre_' + sufijo).value.trim();
    if (!nombre) { mostrarError('nombre', 'El nombre es obligatorio.'); }
    else if (nombre.length < 2) { mostrarError('nombre', 'Mínimo 2 caracteres.'); }

    // Apellido
    var apellido = document.getElementById('apellido_' + sufijo).value.trim();
    if (!apellido) { mostrarError('apellido', 'El apellido es obligatorio.'); }
    else if (apellido.length < 2) { mostrarError('apellido', 'Mínimo 2 caracteres.'); }

    // Fecha + mayor de edad
    var fecha = document.getElementById('fecha_' + sufijo).value;
    if (!fecha) { mostrarError('fecha', 'La fecha de nacimiento es obligatoria.'); }
    else {
        var nac = new Date(fecha), hoy = new Date();
        var edad = hoy.getFullYear() - nac.getFullYear();
        var m = hoy.getMonth() - nac.getMonth();
        if (m < 0 || (m === 0 && hoy.getDate() < nac.getDate())) edad--;
        if (edad < 18) { mostrarError('fecha', 'Debe ser mayor de 18 años.'); }
    }

    // Género
    var genero = document.getElementById('genero_' + sufijo).value;
    if (!genero) { mostrarError('genero', 'Seleccione un sexo.'); }

    // Teléfono
    var tel = document.getElementById('telefono_' + sufijo).value.trim();
    if (!tel) { mostrarError('telefono', 'El teléfono es obligatorio.'); }
    else if (!/^\+569\d{8}$/.test(tel)) { mostrarError('telefono', 'Formato: +56912345678'); }

    // Email
    var email = document.getElementById('email_' + sufijo).value.trim();
    if (!email) { mostrarError('email', 'El correo es obligatorio.'); }
    else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) { mostrarError('email', 'Correo electrónico inválido.'); }

    // Contraseña
    var pswd  = document.getElementById('pswd_' + sufijo).value;
    var pswd2 = document.getElementById('pswd2_' + sufijo).value;
    if (!pswd) { mostrarError('pswd', 'La contraseña es obligatoria.'); }
    else if (pswd.length < 8) { mostrarError('pswd', 'Mínimo 8 caracteres.'); }
    if (!pswd2) { mostrarError('pswd2', 'Repita su contraseña.'); }
    else if (pswd !== pswd2) { mostrarError('pswd2', 'Las contraseñas no coinciden.'); }

    // Certificado (solo gestor)
    if (sufijo === 'g') {
        var cert = document.getElementById('cert_g');
        if (!cert.value) { mostrarError('cert', 'El certificado de antecedentes es obligatorio.'); }
    }

    // Términos
    var terminos = document.getElementById('terminos_' + sufijo);
    if (!terminos.checked) { mostrarError('terminos', 'Debe aceptar los términos y condiciones.'); }

    return valido;
}

/* Interceptar submit de ambos formularios */
document.getElementById('formPropietario').addEventListener('submit', function(e) {
    if (!validarForm('p')) {
        e.preventDefault();
        Swal.fire({ icon: 'warning', title: 'Formulario incompleto', text: 'Por favor corrige los errores antes de continuar.', confirmButtonColor: '#b0a78f' });
    }
});

document.getElementById('formGestor').addEventListener('submit', function(e) {
    if (!validarForm('g')) {
        e.preventDefault();
        Swal.fire({ icon: 'warning', title: 'Formulario incompleto', text: 'Por favor corrige los errores antes de continuar.', confirmButtonColor: '#b0a78f' });
    }
});

/* Tabs */
function mostrarPestana(tipo) {
    document.getElementById('panelPropietario').classList.remove('active');
    document.getElementById('panelGestor').classList.remove('active');
    document.getElementById('tabPropietario').classList.remove('active');
    document.getElementById('tabGestor').classList.remove('active');
    if (tipo === 'propietario') {
        document.getElementById('panelPropietario').classList.add('active');
        document.getElementById('tabPropietario').classList.add('active');
    } else {
        document.getElementById('panelGestor').classList.add('active');
        document.getElementById('tabGestor').classList.add('active');
    }
}
</script>
</body>
</html>
