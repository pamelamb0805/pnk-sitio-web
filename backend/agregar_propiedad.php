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
    <title>Agregar Nueva Propiedad</title>
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
            document.getElementById("accion").value = valor;
            document.getElementById("frm_prop").submit();
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
    <span class="navbar-brand">➕ Agregar Nueva Propiedad</span>
    <a href="../dashboards/admin.php" class="btn btn-secondary btn-sm">Volver al Dashboard</a>
  </div>
</nav>

<div class="container-fluid">
  <div class="row">
    <!-- Sidebar -->
    <div class="col-md-2 bg-dark text-white p-3 vh-100">
      <h5 class="mb-4">Menú</h5>
      <ul class="nav flex-column">
        <li class="nav-item"><a class="nav-link text-white" href="../dashboards/admin.php">Inicio</a></li>
        <?php if ($esAdministrador): ?>
        <li class="nav-item"><a class="nav-link text-white" href="frm_usuarios.php">Usuarios</a></li>
        <?php endif; ?>
        <li class="nav-item"><a class="nav-link text-white" href="frm_propiedades.php">Mis Propiedades</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="agregar_propiedad.php">+ Nueva Propiedad</a></li>
      </ul>
    </div>

    <!-- Contenido -->
    <div class="col-md-10 p-4">
      <div class="card shadow mb-4">
        <div class="card-header bg-dark text-white">Formulario Nueva Propiedad</div>
        <div class="card-body">
          <form action="crud_propiedades.php" method="post" name="frm_prop" id="frm_prop" enctype="multipart/form-data">

            <!-- TÍTULO -->
            <div class="row mb-3">
              <div class="col-sm-3">Título de la Propiedad:</div>
              <div class="col-sm-9">
                <input type="text" class="form-control" id="frm_titulo" name="frm_titulo"
                       placeholder="Ej: Casa familiar con jardín en La Serena" maxlength="150">
              </div>
            </div>

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
                  <option value="activa" selected>Activa</option>
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
              <div class="col-sm-3"><input type="number" min="0" class="form-control" id="frm_banos" name="frm_banos" value="0"></div>
              <div class="col-sm-3">Cantidad de Dormitorios:</div>
              <div class="col-sm-3"><input type="number" min="0" class="form-control" id="frm_dormitorios" name="frm_dormitorios" value="0"></div>
            </div>

            <div class="row mb-3">
              <div class="col-sm-3">Área Total Terreno (m²):</div>
              <div class="col-sm-3"><input type="number" min="0" step="0.01" class="form-control" id="frm_area_terreno" name="frm_area_terreno" value="0"></div>
              <div class="col-sm-3">Área Construida (m²):</div>
              <div class="col-sm-3"><input type="number" min="0" step="0.01" class="form-control" id="frm_area_construida" name="frm_area_construida" value="0"></div>
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
              <div class="col-sm-3"><input type="date" class="form-control" id="frm_fecha_publicacion" name="frm_fecha_publicacion" value="<?php echo date('Y-m-d'); ?>"></div>
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
                <small class="text-muted">Formatos permitidos: JPG, PNG, WEBP. Máximo 10 fotos.</small>
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-sm-3">La propiedad cuenta con:</div>
              <div class="col-sm-9 checks-row">
                <div class="form-check"><input class="form-check-input" type="checkbox" id="frm_bodega" name="frm_bodega" value="1"><label class="form-check-label" for="frm_bodega">Bodega</label></div>
                <div class="form-check"><input class="form-check-input" type="checkbox" id="frm_estacionamiento" name="frm_estacionamiento" value="1"><label class="form-check-label" for="frm_estacionamiento">Estacionamiento</label></div>
                <div class="form-check"><input class="form-check-input" type="checkbox" id="frm_logia" name="frm_logia" value="1"><label class="form-check-label" for="frm_logia">Logia</label></div>
                <div class="form-check"><input class="form-check-input" type="checkbox" id="frm_cocina_amoblada" name="frm_cocina_amoblada" value="1"><label class="form-check-label" for="frm_cocina_amoblada">Cocina amoblada</label></div>
                <div class="form-check"><input class="form-check-input" type="checkbox" id="frm_antejardin" name="frm_antejardin" value="1"><label class="form-check-label" for="frm_antejardin">Antejardin</label></div>
                <div class="form-check"><input class="form-check-input" type="checkbox" id="frm_patio_trasero" name="frm_patio_trasero" value="1"><label class="form-check-label" for="frm_patio_trasero">Patio trasero</label></div>
                <div class="form-check"><input class="form-check-input" type="checkbox" id="frm_piscina" name="frm_piscina" value="1"><label class="form-check-label" for="frm_piscina">Piscina</label></div>
              </div>
            </div>

            <hr>
            <div class="text-center">
              <button type="button" class="btn btn-primary" onclick="validarform(this.value)" value="guardar">Guardar</button>
              <button type="button" class="btn btn-secondary" onclick="validarform(this.value)" value="cancelar">Cancelar</button>
            </div>
            <input type="hidden" id="accion" name="accion">
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
const CHILE = {
  "Región de Arica y Parinacota":{"Arica":["Arica","Camarones"],"Parinacota":["Putre","General Lagos"]},
  "Región de Tarapacá":{"Iquique":["Iquique","Alto Hospicio"],"Tamarugal":["Pozo Almonte","Colchane","Huara","Camiña","Pica"]},
  "Región de Antofagasta":{"Antofagasta":["Antofagasta","Mejillones","Sierra Gorda","Taltal"],"El Loa":["Calama","Ollagüe","San Pedro de Atacama"],"Tocopilla":["Tocopilla","María Elena"]},
  "Región de Atacama":{"Copiapó":["Copiapó","Caldera","Tierra Amarilla"],"Chañaral":["Chañaral","Diego de Almagro"],"Huasco":["Vallenar","Alto del Carmen","Freirina","Huasco"]},
  "Región de Coquimbo":{"Elqui":["La Serena","Coquimbo","Andacollo","La Higuera","Paihuano","Vicuña"],"Limarí":["Ovalle","Combarbalá","Monte Patria","Punitaqui","Río Hurtado"],"Choapa":["Illapel","Canela","Los Vilos","Salamanca"]},
  "Región de Valparaíso":{"Valparaíso":["Valparaíso","Casablanca","Juan Fernández","Viña del Mar","Concón","Puchuncaví","Quintero"],"Isla de Pascua":["Isla de Pascua"],"Los Andes":["Los Andes","Calle Larga","Rinconada","San Esteban"],"Petorca":["La Ligua","Cabildo","Papudo","Petorca","Zapallar"],"Quillota":["Quillota","Calera","Hijuelas","La Cruz","Nogales"],"San Antonio":["San Antonio","Algarrobo","Cartagena","El Quisco","El Tabo","Santo Domingo"],"San Felipe de Aconcagua":["San Felipe","Catemu","Llaillay","Panquehue","Putaendo","Santa María"],"Marga Marga":["Quilpué","Limache","Olmué","Villa Alemana"]},
  "Región Metropolitana":{"Santiago":["Santiago","Cerrillos","Cerro Navia","Conchalí","El Bosque","Estación Central","Huechuraba","Independencia","La Cisterna","La Florida","La Granja","La Pintana","La Reina","Las Condes","Lo Barnechea","Lo Espejo","Lo Prado","Macul","Maipú","Ñuñoa","Pedro Aguirre Cerda","Peñalolén","Providencia","Pudahuel","Quilicura","Quinta Normal","Recoleta","Renca","San Joaquín","San Miguel","San Ramón","Vitacura"],"Cordillera":["Puente Alto","Pirque","San José de Maipo"],"Chacabuco":["Colina","Lampa","Tiltil"],"Maipo":["San Bernardo","Buin","Calera de Tango","Paine"],"Melipilla":["Melipilla","Alhué","Curacaví","María Pinto","San Pedro"],"Talagante":["Talagante","El Monte","Isla de Maipo","Padre Hurtado","Peñaflor"]},
  "Región del Libertador Gral. Bernardo O'Higgins":{"Cachapoal":["Rancagua","Codegua","Coinco","Coltauco","Doñihue","Graneros","Las Cabras","Machalí","Malloa","Mostazal","Olivar","Peumo","Pichidegua","Quinta de Tilcoco","Rengo","Requínoa","San Vicente"],"Cardenal Caro":["Pichilemu","La Estrella","Litueche","Marchihue","Navidad","Paredones"],"Colchagua":["San Fernando","Chépica","Chimbarongo","Lolol","Nancagua","Palmilla","Peralillo","Placilla","Pumanque","Santa Cruz"]},
  "Región del Maule":{"Curicó":["Curicó","Hualañé","Licantén","Molina","Rauco","Romeral","Sagrada Familia","Teno","Vichuquén"],"Linares":["Linares","Colbún","Longaví","Parral","Retiro","San Javier","Villa Alegre","Yerbas Buenas"],"Talca":["Talca","Constitución","Curepto","Empedrado","Maule","Pelarco","Pencahue","Río Claro","San Clemente","San Rafael"],"Cauquenes":["Cauquenes","Chanco","Pelluhue"]},
  "Región de Ñuble":{"Ñuble":["Chillán","Bulnes","Cobquecura","Coelemu","Coihueco","Chillán Viejo","El Carmen","Ninhue","Ñiquén","Pemuco","Pinto","Portezuelo","Quillón","Quirihue","Ránquil","San Carlos","San Fabián","San Ignacio","San Nicolás","Treguaco","Yungay"]},
  "Región del Biobío":{"Biobío":["Los Ángeles","Alto Biobío","Antuco","Cabrero","Laja","Mulchén","Nacimiento","Negrete","Quilaco","Quilleco","San Rosendo","Santa Bárbara","Tucapel","Yumbel"],"Concepción":["Concepción","Coronel","Chiguayante","Florida","Hualpén","Hualqui","Lota","Penco","San Pedro de la Paz","Santa Juana","Talcahuano","Tomé","Trebulquén"],"Arauco":["Lebu","Arauco","Cañete","Contulmo","Curanilahue","Los Álamos","Tirúa"]},
  "Región de La Araucanía":{"Cautín":["Temuco","Carahue","Cunco","Curarrehue","Freire","Galvarino","Gorbea","Lautaro","Loncoche","Melipeuco","Nueva Imperial","Padre Las Casas","Perquenco","Pitrufquén","Pucón","Saavedra","Teodoro Schmidt","Toltén","Vilcún","Villarrica","Cholchol"],"Malleco":["Angol","Collipulli","Curacautín","Ercilla","Lonquimay","Los Sauces","Lumaco","Purén","Renaico","Traiguén","Victoria"]},
  "Región de Los Ríos":{"Valdivia":["Valdivia","Corral","Futrono","La Unión","Lago Ranco","Lanco","Los Lagos","Máfil","Mariquina","Paillaco","Panguipulli","Río Bueno"]},
  "Región de Los Lagos":{"Llanquihue":["Puerto Montt","Calbuco","Cochamó","Fresia","Frutillar","Los Muermos","Llanquihue","Maullín","Puerto Varas"],"Chiloé":["Castro","Ancud","Chonchi","Curaco de Vélez","Dalcahue","Puqueldón","Queilén","Quellón","Quemchi","Quinchao"],"Osorno":["Osorno","Puerto Octay","Purranque","Puyehue","Río Negro","San Juan de la Costa","San Pablo"],"Palena":["Chaitén","Futaleufú","Hualaihué","Palena"]},
  "Región de Aysén del Gral. Carlos Ibáñez del Campo":{"Aysén":["Aysén","Cisnes","Guaitecas"],"Capitán Prat":["Cochrane","O'Higgins","Tortel"],"Coyhaique":["Coyhaique","Lago Verde"],"General Carrera":["Chile Chico","Río Ibáñez"]},
  "Región de Magallanes y de la Antártica Chilena":{"Antártica Chilena":["Cabo de Hornos","Antártica"],"Magallanes":["Punta Arenas","Laguna Blanca","Río Verde","San Gregorio"],"Tierra del Fuego":["Porvenir","Primavera","Timaukel"],"Última Esperanza":["Natales","Torres del Paine"]}
};

function poblarSelect(sel, opciones, seleccionado) {
    sel.innerHTML = '<option value="">-- Seleccione --</option>';
    opciones.forEach(o => { var opt=document.createElement('option'); opt.value=o; opt.textContent=o; if(o===seleccionado) opt.selected=true; sel.appendChild(opt); });
}
function cargarRegiones() {
    var sel=document.getElementById('sel_region');
    sel.innerHTML='<option value="">-- Seleccione Región --</option>';
    Object.keys(CHILE).forEach(r => { var opt=document.createElement('option'); opt.value=r; opt.textContent=r; sel.appendChild(opt); });
}
function cargarProvincias() {
    var region=document.getElementById('sel_region').value;
    var sel=document.getElementById('sel_provincia');
    sel.innerHTML='<option value="">-- Seleccione Provincia --</option>';
    document.getElementById('sel_comuna').innerHTML='<option value="">-- Seleccione Comuna --</option>';
    if(!region||!CHILE[region]) return;
    poblarSelect(sel, Object.keys(CHILE[region]), '');
}
function cargarComunas() {
    var region=document.getElementById('sel_region').value;
    var provincia=document.getElementById('sel_provincia').value;
    var sel=document.getElementById('sel_comuna');
    sel.innerHTML='<option value="">-- Seleccione Comuna --</option>';
    if(!region||!provincia||!CHILE[region][provincia]) return;
    poblarSelect(sel, CHILE[region][provincia], '');
}
document.addEventListener('DOMContentLoaded', cargarRegiones);
</script>
</body>
</html>
