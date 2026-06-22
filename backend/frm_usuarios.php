<?php
include ("../config/setup.php");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Formulario Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/mystyle.css" rel="stylesheet">
    <script src="../js/bootstrap.bundle.min.js"></script>
    <script src="../js/login.js"></script>
    <script src="../js/sweetalert2@11"></script>
    <script>
        function validarform(valor) {
            if (valor === "guardar" || valor === "cancelar") {
                document.getElementById("id").value = "";
                document.getElementById("preview_foto").src = "../img/default.png";
            }
            document.getElementById("accion").value = valor;
            document.getElementById("frm_usu").submit();
        }

        function cargarUsuario(id, rut, nombre, apellido, email, estado, idperfil, foto, fecha_nac) {
            document.getElementById("id").value = id;
            document.getElementById("frm_rut").value = rut;
            document.getElementById("frm_nombre").value = nombre;
            document.getElementById("frm_apellido").value = apellido;
            document.getElementById("frmusuario").value = email;
            document.getElementById("frm_estado").value = estado;
            document.getElementById("frm_idperfil").value = idperfil;
            document.getElementById("frm_fecha_nacimiento").value = fecha_nac || '';
            document.getElementById("preview_foto").src = "../img/" + foto;
            document.getElementById("frm_foto").value = "";
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function previsualizarFoto(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById("preview_foto").src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</head>
<body class="bg-light">

<!-- Navbar -->
<nav class="navbar navbar-dark bg-dark shadow">
  <div class="container-fluid">
    <span class="navbar-brand">👤 Gestión de Usuarios</span>
    <a href="../dashboard.php" class="btn btn-secondary btn-sm">Volver al Dashboard</a>
  </div>
</nav>

<div class="container-fluid">
  <div class="row">
    <!-- Sidebar -->
    <div class="col-md-2 bg-dark text-white p-3 vh-100">
      <h5 class="mb-4">Menú</h5>
      <ul class="nav flex-column">
        <li class="nav-item"><a class="nav-link text-white" href="../dashboard.php">Inicio</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="#">Usuarios</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="#">Reportes</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="#">Configuración</a></li>
      </ul>
    </div>

    <!-- Contenido -->
    <div class="col-md-10 p-4">
      
      <!-- Card formulario -->
      <div class="card shadow mb-4">
        <div class="card-header bg-dark text-white">Formulario Usuario</div>
        <div class="card-body">
          <form action="crud_usuarios.php" method="post" name="frm_usu" id="frm_usu" enctype="multipart/form-data">
            <div class="row mb-3">
              <div class="col-sm-3">R.U.T:</div>
              <div class="col-sm-3"><input type="text" class="form-control" id="frm_rut" name="frm_rut"></div>
              <div class="col-sm-3">Nombres:</div>
              <div class="col-sm-3"><input type="text" class="form-control" id="frm_nombre" name="frm_nombre"></div>
            </div>
            <div class="row mb-3">
              <div class="col-sm-3">Apellidos:</div>
              <div class="col-sm-3"><input type="text" class="form-control" id="frm_apellido" name="frm_apellido"></div>
              <div class="col-sm-3">Fecha Nacimiento:</div>
              <div class="col-sm-3"><input type="date" class="form-control" id="frm_fecha_nacimiento" name="frm_fecha_nacimiento"></div>
            </div>
            <div class="row mb-3">
              <div class="col-sm-3">Estado:</div>
              <div class="col-sm-3">
                <select class="form-select" id="frm_estado" name="frm_estado">
                  <option value="">Seleccionar</option>
                  <option value="1">Activo</option>
                  <option value="0">Inactivo</option>
                </select>
              </div>
              <div class="col-sm-3">Perfil:</div>
              <div class="col-sm-3">
                <select class="form-select" id="frm_idperfil" name="frm_idperfil">
                  <option value="">Seleccionar</option>
                  <option value="1">Administrador</option>
                  <option value="2">Propietario</option>
                  <option value="3">Gestor Inmobiliario</option>
                </select>
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-sm-3">Correo (Usuario):</div>
              <div class="col-sm-3"><input type="text" class="form-control" id="frmusuario" name="frmusuario"></div>
              <div class="col-sm-3"></div>
              <div class="col-sm-3"></div>
            </div>
            <div class="row mb-3 align-items-center">
              <div class="col-sm-3">Foto de Perfil:</div>
              <div class="col-sm-3">
                <input type="file" class="form-control" id="frm_foto" name="frm_foto" accept=".jpg,.jpeg,.png,.webp" onchange="previsualizarFoto(this)">
                <small class="text-muted">Formatos: JPG, PNG, WEBP</small>
              </div>
              <div class="col-sm-3">Vista previa:</div>
              <div class="col-sm-3">
                <img id="preview_foto" src="../img/default.png" alt="Foto de perfil" class="rounded-circle" width="60" height="60" style="object-fit:cover;border:2px solid #b0a78f">
              </div>
            </div>
            <hr>
            <div class="text-center">
              <button type="button" class="btn btn-primary" onclick="validarform(this.value)" value="guardar">Guardar</button>
              <button type="button" class="btn btn-success" onclick="validarform(this.value)" value="modificar">Modificar</button>
              <button type="button" class="btn btn-danger" onclick="validarform(this.value)" value="eliminar">Eliminar</button>
              <button type="button" class="btn btn-secondary" onclick="validarform(this.value)" value="cancelar">Cancelar</button>
            </div>
            <input type="hidden" id="accion" name="accion">
            <input type="hidden" name="id" id="id" value="">
          </form>
        </div>
      </div>

      <!-- Card grilla -->
      <div class="card shadow">
        <div class="card-header bg-dark text-white">Usuarios Registrados</div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover align-middle">
              <thead class="table-dark">
                <tr>
                  <th>Foto</th>
                  <th>ID</th>
                  <th>RUT</th>
                  <th>Nombre</th>
                  <th>Correo</th>
                  <th>Estado</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
              <?php
                $sql="SELECT * FROM usuarios";
                $result=mysqli_query(conectar(),$sql);
                while($datos=mysqli_fetch_array($result)) {
                    $foto_actual = !empty($datos['foto']) ? $datos['foto'] : 'default.png';
              ?>
                <tr>
                  <td><img src="../img/<?php echo htmlspecialchars($foto_actual); ?>" alt="Foto" class="rounded-circle" width="40" height="40" style="object-fit:cover"></td>
                  <td><?php echo $datos['id'];?></td>
                  <td><?php echo $datos['rut'];?></td>
                  <td><?php echo $datos['nombre'] . " " . $datos['apellido'];?></td>
                  <td><?php echo $datos['email'];?></td>
                  <td>
                    <?php if((int)$datos['estado'] === 1){ ?>
                      <span class="badge bg-success">Activo</span>
                    <?php } else { ?>
                      <span class="badge bg-danger">Inactivo</span>
                    <?php } ?>
                  </td>
                  <td>
                    <button type="button" class="btn btn-sm btn-warning"
                      onclick="cargarUsuario(
                        '<?php echo $datos['id']; ?>',
                        '<?php echo addslashes($datos['rut']); ?>',
                        '<?php echo addslashes($datos['nombre']); ?>',
                        '<?php echo addslashes($datos['apellido']); ?>',
                        '<?php echo addslashes($datos['email']); ?>',
                        '<?php echo $datos['estado']; ?>',
                        '<?php echo $datos['idperfil']; ?>',
                        '<?php echo addslashes($foto_actual); ?>',
                        '<?php echo $datos['fecha_nacimiento']; ?>'
                      )">Editar</button>

                    <form action="crud_usuarios.php" method="post" style="display:inline;"
                          onsubmit="return confirm('¿Estás seguro de eliminar este usuario?');">
                      <input type="hidden" name="id" value="<?php echo $datos['id']; ?>">
                      <button type="submit" name="accion" value="eliminar" class="btn btn-sm btn-danger">Eliminar</button>
                    </form>
                  </td>
                </tr>
              <?php } ?>  
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
