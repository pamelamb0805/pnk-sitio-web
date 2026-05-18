<?php
include ("../config/setup.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario Usuarios</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/mistyle.css" rel="stylesheet">
    <script src="../js/bootstrap.bundle.min.js"></script>
    <script src="../js/login.js"></script>
    <script src="../js/sweetalert2@11"></script>
    <script>
        function validarform(valor) {
            document.getElementById("accion").value = valor;
            document.getElementById("frm_usu").submit();
        }
    </script>
</head>
<body>
    <div id="frm_usuario">
        <div class="card">
            <div class="card-header">Formulario Usuario</div>
            <div class="card-body">
                <form action="crud_usuarios.php" method="post" name="frm_usu" id="frm_usu">
                   
                    <div class="row separacion">
                        <div class="col-sm-3">R.U.T:</div>
                        <div class="col-sm-3"><input type="text" class="form-control" id="frm_rut" name="frm_rut"></div>
                        <div class="col-sm-3">Nombres:</div>
                        <div class="col-sm-3"><input type="text" class="form-control" id="frm_nombre" name="frm_nombre"></div>
                    </div>
                    <div class="row separacion">
                        <div class="col-sm-3">Apellidos:</div>
                        <div class="col-sm-3"><input type="text" class="form-control" id="frm_apellido" name="frm_apellido"></div>
                        <div class="col-sm-3">Estado:</div>
                        <div class="col-sm-3">
                            <select class="form-select form-select-sm mt-3" id="frm_estado" name="frm_estado">
                                <option value="">Seleccionar</option>
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>
                    </div>
                    <div class="row separacion">
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
                    <div class="row separacion">
                        <div class="col-sm-3">Correo (Usuario):</div>
                        <div class="col-sm-3"><input type="text" class="form-control" id="frmusuario" name="frmusuario"></div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-12 alinear">
                            <button type="button" class="btn btn-primary" onclick="validarform(this.value)" value="guardar">Guardar</button>&nbsp;
                            <button type="button" class="btn btn-success" onclick="validarform(this.value)" value="modificar">Modificar</button>&nbsp;
                            <button type="button" class="btn btn-danger" onclick="validarform(this.value)" value="eliminar">Eliminar</button>&nbsp;
                            <button type="button" class="btn btn-secondary" onclick="validarform(this.value)" value="cancelar">Cancelar</button>
                        </div>
                        <input type="hidden" class="form-control" id="accion" name="accion">
                        <input type="hidden" name="id" id="id" value="">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <br>
    <div id="grilla_usuario">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
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
                ?>
                    <tr>
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
                            <form action="crud_usuarios.php" method="post" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo $datos['id']; ?>">
                                <button type="submit" name="accion" value="modificar" class="btn btn-sm btn-warning">Editar</button>
                            </form>
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
</body>
</html>
