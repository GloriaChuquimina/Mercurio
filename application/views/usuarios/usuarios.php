<script src="<?php echo  base_url() ?>scriptjs/jquery.js"></script>
<script src="<?php echo  base_url() ?>scriptjs/usuarios/asignacion.js"></script>
<script src="<?php echo  base_url() ?>scriptjs/validacion.js"></script>
<link rel="stylesheet" href="<?php echo base_url();?>scriptjs/stylecss/styles.css">

<style>
/*ABD sept 2025 */
 /*#listadoPermisos > div > div  > div  ,*/
 #listadoPermisos > div {
    padding-left: 40px;
 }
 #listadoPermisos > div > div  > div > label > strong > h3
 {
    font-size: 1.15rem !important; 
    font-weight: bolder;
    /* div#listadoPermisos div.col-12.row div.col-lg-12.grid-margin.grid-margin-lg-0 div.form-check label.form-check-label*/

 }
</style>

<div class="wapper">
    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary">
                <div class="card-header d-flex justify-content-between align-items-center"  style="background-color: #f0f0ff; border-left: 4px solid #3c8dbc;">
                    <div class="col-md-8">
                        <p class="mb-0 opacity-50 text-info"><i class="fas fa-users"></i> Gestión de usuarios y alta de nuevos registros</p>
                    </div>
                    <div class="col-md-4 text-right">
                       <button id="btnAgregar" type="button" class="btn btn-success" onclick="agregarUsuario();"><i class="fas fa-plus-circle"></i> Agregar Usuarios</button>
                        </button>
                    </div>
                </div>
            </div> 
            <div class="card">
            <!--  <div class="card-header bg-gradient-secondary">
                <h3 class="card-title text-white">
                  <i class="mr-2">🏢</i>
                  Entidades Registradas
                </h3>
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse" >
                    <i>🔍</i>
                  </button>
                </div>
              </div>-->
              <div class="card-body p-1">
                <div class="table-responsive">
                  <table id="tbl_usuarios" class="table table-striped table-hover" style="width: 100%;">
                    <thead class="bg-dark">
                        <tr class="bg-dark text-white">
                           <th>Opciones</th>
                           <th>Nro</th>
                           <th>Nombre Completo</th>
                           <th>Cargo</th>

                        </tr>
                    </thead>
                  </table>
                </div>
              </div>
              <div class="card-footer">
                <div class="row">
                  <div class="col-md-6">
                    <!-- <p class="text-muted">Mostrando comprobantes</p> -->
                  </div>
                  <div class="col-md-6 text-right">
                    <!-- <div class="btn-group">
                      <button class="btn btn-default btn-sm">
                        <i class="mr-1">⬅️</i> Anterior
                      </button>
                      <button class="btn btn-default btn-sm">
                        Siguiente <i class="ml-1">➡️</i>
                      </button>
                    </div> -->
                  </div>
                </div>
              </div>
            </div> 
        </div>
    </section>
</div>






<!--MODAL ROLES DE USUARIOS-->
<div class="modal fade" id="editarRoles" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-lg" style="max-width: 800px;" role="document">
        <div class="modal-content">
            <div class="modal-header navbar-inverse bg-secondary">
                <h4 class="modal-title" id="exampleModalLabel">EDITAR PERMISOS DE USUARIOS</h4>
            </div>
            <div class="modal-body">              
                <form id="formularioRolesUsuario">
                    <div class="row">
                        <div class="col-lg-12">
                            <input type="hidden" class="form-control" id="txtDato" name="txtDato">
                        </div>
                        <div class="col-lg-12">
                            <h4 class="modal-title" id="exampleModalLabel"><strong> ROLES DE USUARIOS </strong></h4>
                            <div class="form-group">                                                               
                                <div id="listadoPermisos"> </div>  
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Cerrar</button>
                <button id="rechazar" type="button" class="btn btn-primary" onclick="guardarDatos();"><span class="glyphicon glyphicon-floppy-disk"></span> Guardar Cambios</button>
            </div>
        </div>
    </div>
</div>

<!-- VENTANA MODAL PARA REGISTRAR USUARIOS DRP -->
<div class="modal" id="modalAgregarUsuariosDRP" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog" style="max-width: 1000px;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLabel"><b>AÑADIR USUARIOS DE LA DIRECCIÓN DE REGISTRO Y PROMOCIÓN </b></h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formUsuario">
                    <div class="col-12 row">                        
                        <table id="tbl_usuariosDRP" class="table table-responsive" cellspacing="0" width="100%">
                            <thead>
                                <tr class="bg-dark text-white">
                                    <th>Opciones</th>
                                    <th>Nro</th>
                                    <th>Nombre Completo</th>
                                    <th>Cargo</th>
                                    <th>Subdependencia</th>
                                </tr>
                            </thead>
                            <tbody>                                         
                            </tbody>
                        </table>
               
                    </div>
                </form>
            </div>          
        </div>
    </diV>   
</div>
<!---->

<!-- VENTANA MODAL PARA REGISTRAR USUARIOS OTROS -->
<div class="modal" id="modalAgregarUsuarios" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog" style="max-width: 1000px;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLabel"><b>AÑADIR USUARIOS </b></h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formUsuario">
                    <div class="col-12 row">                        
                        <table id="tbl_usuario" class="table table-responsive" cellspacing="0" width="100%">
                            <thead>
                                <tr class="bg-dark text-white">
                                    <th>Opciones</th>
                                    <th>Nro</th>
                                    <th>Nombre Completo</th>
                                    <th>Cargo</th>
                                    <th>Dependencia</th>
                                    <th>Subdependencia</th>
                                </tr>
                            </thead>
                            <tbody>                                         
                            </tbody>
                        </table>
               
                    </div>
                </form>
            </div>          
        </div>
    </diV>   
</div>
<!---->



<script type="text/javascript">
    $(document).ready(function(){
      var enlace  = "<?php echo base_url();?>";      
      baseurl(enlace); 
      cargarFuncionesUsuarios();       
    });
</script>