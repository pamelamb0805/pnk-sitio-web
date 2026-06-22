<?php include("config/setup.php"); ?>
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
      <a href="index.php" class="logo-link"><img src="img/logo.png" alt="Logo"></a>
    </li>
  </ul>
  <ul>
    <li class="dropdown">
      <a href="#">Registrate</a>
      <div class="dropdown-content">
        <a href="registro.php">Registrar a un Propietario</a>
        <a href="registro.php">Registrar a un Gestor Inmobiliario</a>
      </div>
    </li>
    <li><a href="iniciosesion.php">Inicio Sesión</a></li>
    <li><a href="contacto.php">Contacto</a></li>
  </ul>
</header>

<!-- ===== FILTROS ===== -->
<section class="filtros_propiedad">
  <form action="catalogo.php" method="GET">
    <label for="tipo_propiedad">Propiedad:</label>
    <select id="tipo_propiedad" name="tipo_propiedad">
      <option value="">Todos</option>
      <option value="Casa">Casa</option>
      <option value="Departamento">Departamento</option>
      <option value="Terreno">Terreno</option>
    </select>

    <label for="sel_region">Región:</label>
    <select id="sel_region" name="region" onchange="cargarProvincias()">
      <option value="">Todas las Regiones</option>
    </select>

    <label for="sel_provincia">Provincia:</label>
    <select id="sel_provincia" name="provincia" onchange="cargarComunas()">
      <option value="">Todas las Provincias</option>
    </select>

    <label for="sel_comuna">Comuna:</label>
    <select id="sel_comuna" name="comuna">
      <option value="">Todas las Comunas</option>
    </select>

    <label for="sector">Sector:</label>
    <input type="text" id="sector" name="sector" placeholder="Ej: Av. del Mar" style="padding:6px;border-radius:5px;border:1px solid #ccc;">

    <button type="submit">Buscar</button>
  </form>
</section>

<!-- ===== CARRUSEL: últimas 3 propiedades de la BD ===== -->
<section class="carrusel">
  <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel" data-bs-interval="4000">
    <div class="carousel-inner">
    <?php
    $db = conectar();
    $ultimas = mysqli_query($db,
        "SELECT p.*, f.nombre_archivo AS foto_principal
         FROM propiedades p
         LEFT JOIN fotos_propiedades f ON f.id_propiedad = p.id AND f.es_principal = 1
         WHERE p.estado = 'activa'
         ORDER BY p.id DESC LIMIT 3");

    $first = true;
    if ($ultimas && mysqli_num_rows($ultimas) > 0):
        while ($p = mysqli_fetch_assoc($ultimas)):
            $foto = !empty($p['foto_principal']) ? 'img/propiedades/' . $p['foto_principal'] : 'img/default_propiedad.png';
            $activeClass = $first ? 'active' : '';
            $first = false;
    ?>
      <div class="carousel-item <?= $activeClass ?>">
        <img src="<?= htmlspecialchars($foto) ?>" class="d-block w-100" alt="<?= htmlspecialchars($p['tipo_propiedad']) ?>" style="height:500px;object-fit:cover">
        <div class="carousel-content-overlay">
          <div class="carousel-logo"><img src="img/logocarrusel.png" alt="Logo PNK Inmobiliaria" class="logo-img"></div>
          <div class="carousel-text-main">
            <h1><?= strtoupper(htmlspecialchars($p['tipo_propiedad'])) ?></h1>
            <h2><?= strtoupper(htmlspecialchars(mb_strimwidth($p['descripcion'], 0, 60, '...'))) ?></h2>
            <h3 class="destacado">$<?= number_format($p['precio_pesos'],0,',','.') ?> - <?= number_format($p['precio_uf'],2,',','.') ?> UF</h3>
          </div>
          <div class="carousel-location">
            <span class="icono-ubicacion">📍</span>
            <p><?= strtoupper(htmlspecialchars($p['sector'] ? $p['sector'].', ' : '')) . strtoupper(htmlspecialchars($p['comuna'])) ?></p>
          </div>
        </div>
      </div>
    <?php endwhile; else: ?>
      <div class="carousel-item active">
        <div style="height:500px;background:#dedbc1;display:flex;align-items:center;justify-content:center;">
          <h2 style="color:#b0a78f">Pronto publicaremos propiedades aquí</h2>
        </div>
      </div>
    <?php endif; ?>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Anterior</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Siguiente</span>
    </button>
  </div>
</section>

<!-- ===== TARJETAS: todas las propiedades activas ===== -->
<section class="zona-tarjetas">
<?php
$todas = mysqli_query($db,
    "SELECT p.*, f.nombre_archivo AS foto_principal
     FROM propiedades p
     LEFT JOIN fotos_propiedades f ON f.id_propiedad = p.id AND f.es_principal = 1
     WHERE p.estado = 'activa'
     ORDER BY p.id DESC");

if ($todas && mysqli_num_rows($todas) > 0):
    while ($prop = mysqli_fetch_assoc($todas)):
        $fotoT = !empty($prop['foto_principal']) ? 'img/propiedades/' . $prop['foto_principal'] : 'img/default_propiedad.png';
?>
  <article class="tarjeta">
    <div class="caja-foto">
      <img src="<?= htmlspecialchars($fotoT) ?>" alt="<?= htmlspecialchars($prop['tipo_propiedad']) ?>">
    </div>
    <div class="texto-propiedad">
      <h3><?= strtoupper(htmlspecialchars($prop['tipo_propiedad'])) ?></h3>
      <p class="ubicacion">📍 <?= htmlspecialchars(($prop['sector'] ? $prop['sector'].', ' : '') . $prop['comuna'].', '.$prop['region']) ?></p>
      <p class="precio-destacado">$<?= number_format($prop['precio_pesos'],0,',','.') ?> - <?= number_format($prop['precio_uf'],2,',','.') ?> UF</p>
    </div>
    <a href="catalogo.php?id=<?= $prop['id'] ?>"><button>Más info</button></a>
  </article>
<?php endwhile; else: ?>
  <p style="text-align:center;padding:40px;color:#999">No hay propiedades publicadas aún.</p>
<?php endif; ?>
</section>

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
    opciones.forEach(o => {
        var opt = document.createElement('option');
        opt.value = o; opt.textContent = o;
        if (o === seleccionado) opt.selected = true;
        sel.appendChild(opt);
    });
}

function cargarRegiones() {
    var sel = document.getElementById('sel_region');
    var preRegion = "<?= htmlspecialchars($_GET['region'] ?? '') ?>";
    sel.innerHTML = '<option value="">Todas las Regiones</option>';
    Object.keys(CHILE).forEach(r => {
        var opt = document.createElement('option');
        opt.value = r; opt.textContent = r;
        if (r === preRegion) opt.selected = true;
        sel.appendChild(opt);
    });
    if (preRegion) cargarProvincias();
}

function cargarProvincias() {
    var region = document.getElementById('sel_region').value;
    var preProv = "<?= htmlspecialchars($_GET['provincia'] ?? '') ?>";
    var sel = document.getElementById('sel_provincia');
    sel.innerHTML = '<option value="">Todas las Provincias</option>';
    document.getElementById('sel_comuna').innerHTML = '<option value="">Todas las Comunas</option>';
    if (!region || !CHILE[region]) return;
    poblarSelect(sel, Object.keys(CHILE[region]), preProv);
    if (preProv) cargarComunas();
}

function cargarComunas() {
    var region   = document.getElementById('sel_region').value;
    var provincia= document.getElementById('sel_provincia').value;
    var preComuna= "<?= htmlspecialchars($_GET['comuna'] ?? '') ?>";
    var sel = document.getElementById('sel_comuna');
    sel.innerHTML = '<option value="">Todas las Comunas</option>';
    if (!region || !provincia || !CHILE[region][provincia]) return;
    poblarSelect(sel, CHILE[region][provincia], preComuna);
}

document.addEventListener('DOMContentLoaded', cargarRegiones);
</script>
</body>
</html>
