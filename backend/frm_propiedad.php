<?php
include ("../config/setup.php");
session_start();
if (!isset($_SESSION['usuario_sesion'])) {
    header("Location:error.html");
    exit;
}
$esAdministrador = ($_SESSION['nombre_perfil'] === 'Administrador');
$id_sesion = $_SESSION['id_sesion'];
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
    </style>
    <script>
        function validarform(valor) {
            if (valor === "guardar" || valor === "cancelar") {
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
            document.getElementById("frm_sector").value = "";
            document.getElementById("frm_solicitar_visita").checked = false;
            cargarRegiones();
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
            document.getElementById("frm_sector").value = datos.sector;
            document.getElementById("frm_solicitar_visita").checked = (datos.solicitar_visita == 1);

            cargarRegiones(datos.region);
            cargarProvincias(datos.provincia);
            cargarComunas(datos.comuna);

            ['bodega','estacionamiento','logia','cocina_amoblada','antejardin','patio_trasero','piscina'].forEach(function(k) {
                document.getElementById("frm_" + k).checked = (datos[k] == 1);
            });
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
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
              <div class="col-sm-3">Región:</div>
              <div class="col-sm-3">
                <select class="form-select" id="sel_region" name="frm_region" onchange="cargarProvincias()">
                  <option value="">-- Seleccione Región --</option>
                </select>
              </div>
              <div class="col-sm-3">Provincia:</div>
              <div class="col-sm-3">
                <select class="form-select" id="sel_provincia" name="frm_provincia" onchange="cargarComunas()">
                  <option value="">-- Seleccione Provincia --</option>
                </select>
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-sm-3">Comuna:</div>
              <div class="col-sm-3">
                <select class="form-select" id="sel_comuna" name="frm_comuna">
                  <option value="">-- Seleccione Comuna --</option>
                </select>
              </div>
              <div class="col-sm-3">Sector / Dirección:</div>
              <div class="col-sm-3"><input type="text" class="form-control" id="frm_sector" name="frm_sector" placeholder="Ej: Av. del Mar 1234"></div>
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

            <div class="row mb-3">
              <div class="col-sm-3">Fotografías (1 a 10):</div>
              <div class="col-sm-9">
                <input type="file" class="form-control" id="frm_fotos" name="frm_fotos[]" accept=".jpg,.jpeg,.png,.webp" multiple>
                <small class="text-muted">Formatos: JPG, PNG, WEBP. Si subes fotos nuevas al modificar, se agregan a las existentes.</small>
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
                  <th>Foto</th>
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
                if ($esAdministrador) {
                    $sql = "SELECT * FROM propiedades ORDER BY id DESC";
                } else {
                    $sql = "SELECT * FROM propiedades WHERE id_usuario='$id_sesion' ORDER BY id DESC";
                }
                $result = mysqli_query(conectar(), $sql);
                while ($datos = mysqli_fetch_assoc($result)) {
                    $foto_principal = mysqli_fetch_assoc(mysqli_query(conectar(), "SELECT nombre_archivo FROM fotos_propiedades WHERE id_propiedad='{$datos['id']}' AND es_principal=1 LIMIT 1"));
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
                    <button type="button" class="btn btn-sm btn-warning" onclick='cargarPropiedad(<?php echo $datosJson; ?>)'>Editar</button>

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
<script>
/* ---- Datos de Regiones, Provincias y Comunas de Chile ---- */
const CHILE = {
  "Región de Arica y Parinacota": {
    "Arica": ["Arica","Camarones"],
    "Parinacota": ["Putre","General Lagos"]
  },
  "Región de Tarapacá": {
    "Iquique": ["Iquique","Alto Hospicio"],
    "Tamarugal": ["Pozo Almonte","Colchane","Huara","Camiña","Pica"]
  },
  "Región de Antofagasta": {
    "Antofagasta": ["Antofagasta","Mejillones","Sierra Gorda","Taltal"],
    "El Loa": ["Calama","Ollagüe","San Pedro de Atacama"],
    "Tocopilla": ["Tocopilla","María Elena"]
  },
  "Región de Atacama": {
    "Copiapó": ["Copiapó","Caldera","Tierra Amarilla"],
    "Chañaral": ["Chañaral","Diego de Almagro"],
    "Huasco": ["Vallenar","Alto del Carmen","Freirina","Huasco"]
  },
  "Región de Coquimbo": {
    "Elqui": ["La Serena","Coquimbo","Andacollo","La Higuera","Paihuano","Vicuña"],
    "Limarí": ["Ovalle","Combarbalá","Monte Patria","Punitaqui","Río Hurtado"],
    "Choapa": ["Illapel","Canela","Los Vilos","Salamanca"]
  },
  "Región de Valparaíso": {
    "Valparaíso": ["Valparaíso","Casablanca","Juan Fernández","Viña del Mar","Concón","Puchuncaví","Quintero"],
    "Isla de Pascua": ["Isla de Pascua"],
    "Los Andes": ["Los Andes","Calle Larga","Rinconada","San Esteban"],
    "Petorca": ["La Ligua","Cabildo","Papudo","Petorca","Zapallar"],
    "Quillota": ["Quillota","Calera","Hijuelas","La Cruz","Nogales"],
    "San Antonio": ["San Antonio","Algarrobo","Cartagena","El Quisco","El Tabo","Santo Domingo"],
    "San Felipe de Aconcagua": ["San Felipe","Catemu","Llaillay","Panquehue","Putaendo","Santa María"],
    "Marga Marga": ["Quilpué","Limache","Olmué","Villa Alemana"]
  },
  "Región Metropolitana": {
    "Santiago": ["Santiago","Cerrillos","Cerro Navia","Conchalí","El Bosque","Estación Central","Huechuraba","Independencia","La Cisterna","La Florida","La Granja","La Pintana","La Reina","Las Condes","Lo Barnechea","Lo Espejo","Lo Prado","Macul","Maipú","Ñuñoa","Pedro Aguirre Cerda","Peñalolén","Providencia","Pudahuel","Quilicura","Quinta Normal","Recoleta","Renca","San Joaquín","San Miguel","San Ramón","Vitacura"],
    "Cordillera": ["Puente Alto","Pirque","San José de Maipo"],
    "Chacabuco": ["Colina","Lampa","Tiltil"],
    "Maipo": ["San Bernardo","Buin","Calera de Tango","Paine"],
    "Melipilla": ["Melipilla","Alhué","Curacaví","María Pinto","San Pedro"],
    "Talagante": ["Talagante","El Monte","Isla de Maipo","Padre Hurtado","Peñaflor"]
  },
  "Región del Libertador Gral. Bernardo O'Higgins": {
    "Cachapoal": ["Rancagua","Codegua","Coinco","Coltauco","Doñihue","Graneros","Las Cabras","Machalí","Malloa","Mostazal","Olivar","Peumo","Pichidegua","Quinta de Tilcoco","Rengo","Requínoa","San Vicente"],
    "Cardenal Caro": ["Pichilemu","La Estrella","Litueche","Marchihue","Navidad","Paredones"],
    "Colchagua": ["San Fernando","Chépica","Chimbarongo","Lolol","Nancagua","Palmilla","Peralillo","Placilla","Pumanque","Santa Cruz"]
  },
  "Región del Maule": {
    "Curicó": ["Curicó","Hualañé","Licantén","Molina","Rauco","Romeral","Sagrada Familia","Teno","Vichuquén"],
    "Linares": ["Linares","Colbún","Longaví","Parral","Retiro","San Javier","Villa Alegre","Yerbas Buenas"],
    "Talca": ["Talca","Constitución","Curepto","Empedrado","Maule","Pelarco","Pencahue","Río Claro","San Clemente","San Rafael"],
    "Cauquenes": ["Cauquenes","Chanco","Pelluhue"]
  },
  "Región de Ñuble": {
    "Ñuble": ["Chillán","Bulnes","Cobquecura","Coelemu","Coihueco","Chillán Viejo","El Carmen","Ninhue","Ñiquén","Pemuco","Pinto","Portezuelo","Quillón","Quirihue","Ránquil","San Carlos","San Fabián","San Ignacio","San Nicolás","Treguaco","Yungay"]
  },
  "Región del Biobío": {
    "Biobío": ["Los Ángeles","Alto Biobío","Antuco","Cabrero","Laja","Mulchén","Nacimiento","Negrete","Quilaco","Quilleco","San Rosendo","Santa Bárbara","Tucapel","Yumbel"],
    "Concepción": ["Concepción","Coronel","Chiguayante","Florida","Hualpén","Hualqui","Lota","Penco","San Pedro de la Paz","Santa Juana","Talcahuano","Tomé","Trebulquén"],
    "Arauco": ["Lebu","Arauco","Cañete","Contulmo","Curanilahue","Los Álamos","Tirúa"]
  },
  "Región de La Araucanía": {
    "Cautín": ["Temuco","Carahue","Cunco","Curarrehue","Freire","Galvarino","Gorbea","Lautaro","Loncoche","Melipeuco","Nueva Imperial","Padre Las Casas","Perquenco","Pitrufquén","Pucón","Saavedra","Teodoro Schmidt","Toltén","Vilcún","Villarrica","Cholchol"],
    "Malleco": ["Angol","Collipulli","Curacautín","Ercilla","Lonquimay","Los Sauces","Lumaco","Purén","Renaico","Traiguén","Victoria"]
  },
  "Región de Los Ríos": {
    "Valdivia": ["Valdivia","Corral","Futrono","La Unión","Lago Ranco","Lanco","Los Lagos","Máfil","Mariquina","Paillaco","Panguipulli","Río Bueno"]
  },
  "Región de Los Lagos": {
    "Llanquihue": ["Puerto Montt","Calbuco","Cochamó","Fresia","Frutillar","Los Muermos","Llanquihue","Maullín","Puerto Varas"],
    "Chiloé": ["Castro","Ancud","Chonchi","Curaco de Vélez","Dalcahue","Puqueldón","Queilén","Quellón","Quemchi","Quinchao"],
    "Osorno": ["Osorno","Puerto Octay","Purranque","Puyehue","Río Negro","San Juan de la Costa","San Pablo"],
    "Palena": ["Chaitén","Futaleufú","Hualaihué","Palena"]
  },
  "Región de Aysén del Gral. Carlos Ibáñez del Campo": {
    "Aysén": ["Aysén","Cisnes","Guaitecas"],
    "Capitán Prat": ["Cochrane","O'Higgins","Tortel"],
    "Coyhaique": ["Coyhaique","Lago Verde"],
    "General Carrera": ["Chile Chico","Río Ibáñez"]
  },
  "Región de Magallanes y de la Antártica Chilena": {
    "Antártica Chilena": ["Cabo de Hornos","Antártica"],
    "Magallanes": ["Punta Arenas","Laguna Blanca","Río Verde","San Gregorio"],
    "Tierra del Fuego": ["Porvenir","Primavera","Timaukel"],
    "Última Esperanza": ["Natales","Torres del Paine"]
  }
};

function poblarSelect(sel, opciones, seleccionado) {
    sel.innerHTML = '<option value="">-- Seleccione --</option>';
    opciones.forEach(function(o) {
        var opt = document.createElement('option');
        opt.value = o; opt.textContent = o;
        if (o === seleccionado) opt.selected = true;
        sel.appendChild(opt);
    });
}

function cargarRegiones(preRegion) {
    var sel = document.getElementById('sel_region');
    sel.innerHTML = '<option value="">-- Seleccione Región --</option>';
    Object.keys(CHILE).forEach(function(r) {
        var opt = document.createElement('option');
        opt.value = r; opt.textContent = r;
        if (r === preRegion) opt.selected = true;
        sel.appendChild(opt);
    });
}

function cargarProvincias(preProvincia) {
    var region = document.getElementById('sel_region').value;
    var sel = document.getElementById('sel_provincia');
    sel.innerHTML = '<option value="">-- Seleccione Provincia --</option>';
    document.getElementById('sel_comuna').innerHTML = '<option value="">-- Seleccione Comuna --</option>';
    if (!region || !CHILE[region]) return;
    poblarSelect(sel, Object.keys(CHILE[region]), preProvincia);
}

function cargarComunas(preComuna) {
    var region    = document.getElementById('sel_region').value;
    var provincia = document.getElementById('sel_provincia').value;
    var sel = document.getElementById('sel_comuna');
    sel.innerHTML = '<option value="">-- Seleccione Comuna --</option>';
    if (!region || !provincia || !CHILE[region][provincia]) return;
    poblarSelect(sel, CHILE[region][provincia], preComuna);
}

document.addEventListener('DOMContentLoaded', function() {
    cargarRegiones();
});
</script>
</body>
</html>
