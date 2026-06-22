<?php
include ("../config/setup.php");
session_start();
if (!isset($_SESSION['usuario_sesion'])) {
    header("Location:error.html");
    exit;
}
$esAdministrador = ($_SESSION['nombre_perfil'] === 'Administrador');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Propiedades</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/mystyle.css" rel="stylesheet">
    <script src="../js/bootstrap.bundle.min.js"></script>
    <script src="../js/login.js"></script>
    <script src="../js/sweetalert2@11"></script>
    <style>
        .checks-row .form-check { width: 50%; float: left; }
        #galeria_fotos_edicion { display: none; }
        .foto-card {
            position: relative;
            display: inline-block;
            margin: 5px;
        }
        .foto-card img {
            width: 100px;
            height: 80px;
            object-fit: cover;
            border-radius: 6px;
            border: 2px solid #dee2e6;
        }
        .foto-card img.principal {
            border: 3px solid #198754;
        }
        .foto-card .badge-principal {
            position: absolute;
            top: 3px;
            left: 3px;
            font-size: 0.6rem;
        }
        .foto-card .controles {
            font-size: 0.75rem;
            text-align: center;
            margin-top: 3px;
        }
        #preview_nuevas_fotos img {
            width: 80px;
            height: 65px;
            object-fit: cover;
            border-radius: 5px;
            border: 2px solid #b0a78f;
            margin: 3px;
        }
    </style>
    <script>
        function validarform(valor) {
            if (valor === "cancelar") {
                document.getElementById("id").value = "";
                limpiarFormulario();
            }
            document.getElementById("accion").value = valor;
            document.getElementById("frm_prop").submit();
        }

        function limpiarFormulario() {
            document.getElementById("frm_tipo").value = "";
            document.getElementById("frm_descripcion").value = "";
            document.getElementById("frm_banos").value = "";
            document.getElementById("frm_dormitorios").value = "";
            document.getElementById("frm_area_terreno").value = "";
            document.getElementById("frm_area_construida").value = "";
            document.getElementById("frm_precio_pesos").value = "";
            document.getElementById("frm_precio_uf").value = "";
            document.getElementById("frm_fecha_publicacion").value = "";
            document.getElementById("frm_estado").value = "";
            document.getElementById("frm_solicitar_visita").checked = false;
            document.getElementById("frm_fotos").value = "";
            document.getElementById("preview_nuevas_fotos").innerHTML = "";
            document.getElementById("galeria_fotos_edicion").style.display = "none";
            document.getElementById("contenedor_fotos_existentes").innerHTML = "";
            ['bodega','estacionamiento','logia','cocina_amoblada','antejardin','patio_trasero','piscina'].forEach(function(k) {
                document.getElementById("frm_" + k).checked = false;
            });
        }

        function cargarPropiedad(datos) {
            document.getElementById("id").value = datos.id;
            document.getElementById("frm_tipo").value = datos.tipo_propiedad;
            document.getElementById("frm_descripcion").value = datos.descripcion;
            document.getElementById("frm_banos").value = datos.banos;
            document.getElementById("frm_dormitorios").value = datos.dormitorios;
            document.getElementById("frm_area_terreno").value = datos.area_terreno;
            document.getElementById("frm_area_construida").value = datos.area_construida;
            document.getElementById("frm_precio_pesos").value = datos.precio_pesos;
            document.getElementById("frm_precio_uf").value = datos.precio_uf;
            document.getElementById("frm_fecha_publicacion").value = datos.fecha_publicacion;
            document.getElementById("frm_estado").value = datos.estado;
            document.getElementById("frm_solicitar_visita").checked = (datos.solicitar_visita == 1);
            document.getElementById("frm_fotos").value = "";
            document.getElementById("preview_nuevas_fotos").innerHTML = "";
            ['bodega','estacionamiento','logia','cocina_amoblada','antejardin','patio_trasero','piscina'].forEach(function(k) {
                document.getElementById("frm_" + k).checked = (datos[k] == 1);
            });

            // Cargar fotos existentes via fetch
            cargarFotosExistentes(datos.id);

            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function cargarFotosExistentes(id_propiedad) {
            var contenedor = document.getElementById("contenedor_fotos_existentes");
            var galeria    = document.getElementById("galeria_fotos_edicion");
            contenedor.innerHTML = '<p class="text-muted">Cargando fotos...</p>';
            galeria.style.display = "block";

            fetch('get_fotos_propiedad.php?id=' + id_propiedad)
                .then(r => r.json())
                .then(fotos => {
                    if (!fotos.length) {
                        contenedor.innerHTML = '<p class="text-muted">Sin fotos cargadas.</p>';
                        return;
                    }
                    var html = '';
                    fotos.forEach(function(f) {
                        html += '<div class="foto-card">';
                        html += '<img src="../img/propiedades/' + f.nombre_archivo + '" ' +
                                (f.es_principal == 1 ? 'class="principal"' : '') + '>';
                        if (f.es_principal == 1) {
                            html += '<span class="badge bg-success badge-principal">Principal</span>';
                        }
                        html += '<div class="controles">';
                        // Radio para marcar como principal
                        html += '<label><input type="radio" name="foto_principal" value="' + f.id + '" ' +
                                (f.es_principal == 1 ? 'checked' : '') + ' form="frm_prop"> Principal</label><br>';
                        // Checkbox para eliminar
                        html += '<label><input type="checkbox" name="eliminar_foto[]" value="' + f.id + '" form="frm_prop"> Eliminar</label>';
                        html += '</div>';
                        html += '</div>';
                    });
                    contenedor.innerHTML = html;
                })
                .catch(() => {
                    contenedor.innerHTML = '<p class="text-danger">Error al cargar fotos.</p>';
                });
        }

        // Preview de fotos nuevas antes de subir
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('frm_fotos').addEventListener('change', function() {
                var preview = document.getElementById('preview_nuevas_fotos');
                preview.innerHTML = '';
                var files = Array.from(this.files);
                if (files.length > 10) {
                    alert('Máximo 10 fotografías permitidas.');
                    this.value = '';
                    return;
                }
                files.forEach(function(f) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        var img = document.createElement('img');
                        img.src = e.target.result;
                        preview.appendChild(img);
                    };
                    reader.readAsDataURL(f);
                });
            });
        });
    </script>
</head>
<body class="bg-light">

<?php if (isset($_GET['error'])): ?>
<script>
window.addEventListener('DOMContentLoaded', function() {
    if (typeof Swal !== 'undefined') {
        Swal.fire({ icon: 'error', title: 'No se pudo guardar', text: '<?php echo htmlspecialchars($_GET['error'], ENT_QUOTES); ?>', confirmButtonColor: '#b0a78f' });
    } else {
        alert('<?php echo htmlspecialchars($_GET['error'], ENT_QUOTES); ?>');
    }
});
</script>
<?php endif; ?>
<?php if (isset($_GET['ok'])): ?>
<script>
window.addEventListener('DOMContentLoaded', function() {
    Swal.fire({ icon: 'success', title: '<?php echo htmlspecialchars($_GET['ok'], ENT_QUOTES); ?>', confirmButtonColor: '#b0a78f' });
});
</script>
<?php endif; ?>

<!-- Navbar -->
<nav class="navbar navbar-dark bg-dark shadow">
  <div class="container-fluid">
    <span class="navbar-brand">🏡 Gestión de Propiedades</span>
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
        <?php if ($esAdministrador): ?>
        <li class="nav-item"><a class="nav-link text-white" href="frm_usuarios.php">Usuarios</a></li>
        <?php endif; ?>
        <li class="nav-item"><a class="nav-link text-white" href="frm_propiedades.php">Mis Propiedades</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="agregar_propiedad.php">+ Nueva Propiedad</a></li>
      </ul>
    </div>

    <!-- Contenido -->
    <div class="col-md-10 p-4">

      <!-- Card formulario -->
      <div class="card shadow mb-4">
        <div class="card-header bg-dark text-white">Formulario Propiedad</div>
        <div class="card-body">
          <form action="crud_propiedades.php" method="post" name="frm_prop" id="frm_prop" enctype="multipart/form-data">

            <div class="row mb-3">
              <div class="col-sm-3">Tipo de Propiedad:</div>
              <div class="col-sm-3">
                <select class="form-select" id="frm_tipo" name="frm_tipo">
                  <option value="">Seleccionar</option>
                  <option value="Casa">Casa</option>
                  <option value="Departamento">Departamento</option>
                  <option value="Terreno">Terreno</option>
                </select>
              </div>
              <div class="col-sm-3">Estado:</div>
              <div class="col-sm-3">
                <select class="form-select" id="frm_estado" name="frm_estado">
                  <option value="">Seleccionar</option>
                  <option value="activa">Activa</option>
                  <option value="inactiva">Inactiva</option>
                  <option value="vendida">Vendida</option>
                </select>
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-sm-3">Descripción:</div>
              <div class="col-sm-9"><textarea class="form-control" id="frm_descripcion" name="frm_descripcion" rows="2"></textarea></div>
            </div>

            <div class="row mb-3">
              <div class="col-sm-3">Cantidad de Baños:</div>
              <div class="col-sm-3"><input type="number" min="0" class="form-control" id="frm_banos" name="frm_banos"></div>
              <div class="col-sm-3">Cantidad de Dormitorios:</div>
              <div class="col-sm-3"><input type="number" min="0" class="form-control" id="frm_dormitorios" name="frm_dormitorios"></div>
            </div>

            <div class="row mb-3">
              <div class="col-sm-3">Área Total Terreno (m²):</div>
              <div class="col-sm-3"><input type="number" min="0" step="0.01" class="form-control" id="frm_area_terreno" name="frm_area_terreno"></div>
              <div class="col-sm-3">Área Construida (m²):</div>
              <div class="col-sm-3"><input type="number" min="0" step="0.01" class="form-control" id="frm_area_construida" name="frm_area_construida"></div>
            </div>

            <div class="row mb-3">
              <div class="col-sm-3">Precio en $:</div>
              <div class="col-sm-3"><input type="number" min="0" class="form-control" id="frm_precio_pesos" name="frm_precio_pesos"></div>
              <div class="col-sm-3">Precio en UF:</div>
              <div class="col-sm-3"><input type="number" min="0" step="0.01" class="form-control" id="frm_precio_uf" name="frm_precio_uf"></div>
            </div>

            <div class="row mb-3">
              <div class="col-sm-3">Fecha de Publicación:</div>
              <div class="col-sm-3"><input type="date" class="form-control" id="frm_fecha_publicacion" name="frm_fecha_publicacion"></div>
              <div class="col-sm-3">Solicitar Visita:</div>
              <div class="col-sm-3 d-flex align-items-center">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="frm_solicitar_visita" name="frm_solicitar_visita" value="1">
                  <label class="form-check-label" for="frm_solicitar_visita">Permitir solicitud de visita</label>
                </div>
              </div>
            </div>

            <!-- Galería de fotos existentes (aparece al hacer Editar) -->
            <div id="galeria_fotos_edicion" class="mb-3">
              <label class="form-label fw-bold">Fotos actuales:</label>
              <div id="contenedor_fotos_existentes" class="d-flex flex-wrap"></div>
              <small class="text-muted">Marca "Principal" para cambiar la foto destacada. Marca "Eliminar" para quitar fotos.</small>
            </div>

            <!-- Subir fotos nuevas -->
            <div class="row mb-3">
              <div class="col-sm-3">Agregar Fotografías:</div>
              <div class="col-sm-9">
                <input type="file" class="form-control" id="frm_fotos" name="frm_fotos[]" accept=".jpg,.jpeg,.png,.webp" multiple>
                <small class="text-muted">Formatos: JPG, PNG, WEBP. Máx. 10 fotos en total por propiedad.</small>
                <div id="preview_nuevas_fotos" class="mt-2"></div>
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-sm-3">La propiedad cuenta con:</div>
              <div class="col-sm-9 checks-row">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="frm_bodega" name="frm_bodega" value="1">
                  <label class="form-check-label" for="frm_bodega">Bodega</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="frm_estacionamiento" name="frm_estacionamiento" value="1">
                  <label class="form-check-label" for="frm_estacionamiento">Estacionamiento</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="frm_logia" name="frm_logia" value="1">
                  <label class="form-check-label" for="frm_logia">Logia</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="frm_cocina_amoblada" name="frm_cocina_amoblada" value="1">
                  <label class="form-check-label" for="frm_cocina_amoblada">Cocina amoblada</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="frm_antejardin" name="frm_antejardin" value="1">
                  <label class="form-check-label" for="frm_antejardin">Antejardin</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="frm_patio_trasero" name="frm_patio_trasero" value="1">
                  <label class="form-check-label" for="frm_patio_trasero">Patio trasero</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="frm_piscina" name="frm_piscina" value="1">
                  <label class="form-check-label" for="frm_piscina">Piscina</label>
                </div>
              </div>
            </div>

            <hr>
            <div class="text-center">
              <button type="button" class="btn btn-success" onclick="validarform(this.value)" value="modificar">Modificar</button>
              <button type="button" class="btn btn-danger" onclick="validarform(this.value)" value="eliminar">Eliminar</button>
              <button type="button" class="btn btn-secondary" onclick="validarform(this.value)" value="cancelar">Cancelar</button>
              <a href="agregar_propiedad.php" class="btn btn-primary">+ Agregar Nueva Propiedad</a>
            </div>

            <input type="hidden" id="accion" name="accion">
            <input type="hidden" name="id" id="id" value="">
          </form>
        </div>
      </div>

      <!-- Card grilla -->
      <div class="card shadow">
        <div class="card-header bg-dark text-white">
          <?php echo $esAdministrador ? 'Todas las Propiedades Registradas' : 'Mis Propiedades Registradas'; ?>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover align-middle">
              <thead class="table-dark">
                <tr>
                  <th>Foto</th><th>ID</th><th>Tipo</th><th>Descripción</th><th>Precio $</th><th>Estado</th><th>Acciones</th>
                </tr>
              </thead>
              <tbody>
              <?php
                if ($esAdministrador) {
                    $sql = "SELECT * FROM propiedades ORDER BY id DESC";
                } else {
                    $sql = "SELECT * FROM propiedades WHERE id_usuario='$id_sesion' ORDER BY id DESC";
                }
                $result = mysqli_query(conectar(), $sql);
                while ($datos = mysqli_fetch_assoc($result)) {
                    $foto_principal = mysqli_fetch_assoc(mysqli_query(conectar(),
                        "SELECT nombre_archivo FROM fotos_propiedades WHERE id_propiedad='{$datos['id']}' AND es_principal=1 LIMIT 1"));
                    $img = $foto_principal ? $foto_principal['nombre_archivo'] : 'default_propiedad.png';
                    $badge = ['activa'=>'success','inactiva'=>'secondary','vendida'=>'danger'];
                    $b = $badge[$datos['estado']] ?? 'secondary';
                    $datosJson = htmlspecialchars(json_encode($datos), ENT_QUOTES, 'UTF-8');
              ?>
                <tr>
                  <td><img src="../img/propiedades/<?php echo htmlspecialchars($img); ?>" alt="Foto" width="50" height="40" style="object-fit:cover;border-radius:4px"></td>
                  <td><?php echo $datos['id'];?></td>
                  <td><?php echo htmlspecialchars($datos['tipo_propiedad']);?></td>
                  <td><?php echo htmlspecialchars(mb_strimwidth($datos['descripcion'], 0, 40, '...'));?></td>
                  <td>$<?php echo number_format($datos['precio_pesos'],0,',','.');?></td>
                  <td><span class="badge bg-<?php echo $b; ?>"><?php echo ucfirst($datos['estado']);?></span></td>
                  <td>
                    <button type="button" class="btn btn-sm btn-warning"
                      onclick='cargarPropiedad(<?php echo $datosJson; ?>)'>Editar</button>
                    <form action="crud_propiedades.php" method="post" style="display:inline;"
                          onsubmit="return confirm('¿Estás seguro de eliminar esta propiedad?');">
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
