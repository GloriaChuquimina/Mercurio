<script src="<?php echo  base_url() ?>scriptjs/jquery.js"></script>
<script src="<?php echo  base_url() ?>scriptjs/contabilidad/plandecuentas.js"></script>
<!-- <script src="<?php echo  base_url() ?>scriptjs/inicio/inicio.js"></script>
<script src="<?php echo  base_url() ?>scriptjs/validacion.js"></script> -->

<!-- jQuery -->
<!-- <script src="<?php echo base_url();?>resources/plugins/jquery/jquery.min.js"></script> -->
<!-- Bootstrap 4 -->
<!-- <script src="<?php echo base_url();?>resources/plugins/bootstrap/js/bootstrap.bundle.min.js"></script> -->
<!-- AdminLTE App -->
<!-- <script src="<?php echo base_url();?>resources/dist/js/adminlte.min.js"></script> -->


<div>
<h1 class="page-title"></h1>
    <div class="card">
        <div class="card-body">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-3 form-group">
                        <button id="botonNuevaCuenta" class="btn btn-block btn-success btn-xm" onclick='agregarCuentas()'><i class="mdi mdi-plus"></i>Registro de Cuentas Mayores</button>
                    </div>
                </div>
                <hr>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <table id="tablaPlanDeCuentas" class="table table-striped" cellspacing="0" width="100%">
                        <thead>
                            <tr class="bg-dark text-white">
                                <th>Opciones</th>
                                <th>Nro</th>
                                <th>Código</th>
                                <th>Descripción</th>
                                <th>Tipo</th>
                                <th>Nivel</th>
                                <th>Sigla</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade show" id="modalPlanDeCuentas" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-lg" style="max-width: 1200px;" role="document">
        <div class="modal-content" style="border-radius: 10px;" >
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel" style="color: black !important;">REGISTRO DE PLAN DE CUENTAS<span style="color:black;" id="nombreUsuario"></span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">              
                <form id="formularioPlanDeCuentas">
                     <!-- Inputs ocultos -->
                    <input type="hidden" class="form-control" id="txtAccion" name="txtAccion">
                    <input type="hidden" class="form-control" id="idCuenta" name="idCuenta">
                    <input type="hidden" class="form-control" id="nivel" name="nivel">

                    <fieldset style="margin-left: 5px;">
                        <div class="form-group row mb-3">
                            <div class="col-sm-1">
                                <label class="form-label small"><b><i class="mdi mdi-asterisk"></i> CÓDIGO:</b></label>
                            </div>
                            <div class="col-sm-2">
                                <input class="form-control" type="text" id="txtCodigoCuenta" name="txtCodigoCuenta">
                            </div>
                            <div class="col-sm-1">
                                <label class="form-label small"><b><i class="mdi mdi-asterisk"></i> SIGLA:</b></label>
                            </div>
                            <div class="col-sm-2">
                                <input class="form-control" type="text" id="txtSiglaCuenta" name="txtSiglaCuenta">
                            </div>
                            <div class="col-sm-1">
                                <label class="form-label small"><b><i class="mdi mdi-asterisk"></i> DESCRIPCIÓN:</b></label>
                            </div>
                            <div class="col-sm-5">
                                <input class="form-control" type="text" id="txtDescripcionCuenta" name="txtDescripcionCuenta">
                            </div>
                        </div>
                    </fieldset>
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Cerrar</button>
                <button id="guardar" type="button" class="btn btn-primary" onclick="guardarPlanDeCuentas();"><span class="glyphicon glyphicon-floppy-disk"></span> Guardar Cambios</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade show" id="modalPlanDeSubCuentas" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-lg" style="max-width: 1200px;" role="document">
        <div class="modal-content" style="border-radius: 10px;" >
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel" style="color: black !important;">REGISTRO DE PLAN DE CUENTAS:<span style="color:black;"><label id="nombreCuenta">...</label></span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">              
                <form id="formularioPlanDeSubCuentas">
                     <!-- Inputs ocultos -->
                    <input type="hidden" class="form-control" id="txtAccionSubCuenta" name="txtAccionSubCuenta">
                    <input type="hidden" class="form-control" id="id_cuenta" name="id_cuenta">
                    <input type="hidden" class="form-control" id="nivel_padre" name="nivel_padre">
                    <input type="hidden" class="form-control" id="id_padre" name="id_padre">
                    <input type="hidden" class="form-control" id="ruta" name="ruta">
                    <fieldset style="margin-left: 5px;">
                        <!-- <legend id="titulo_registro">FORMULARIO DE REGISTRO PLAN DE CUENTAS</legend> -->
                        <!-- Fila 1 -->
                        <div class="form-group row mb-3">
                            <div class="col-sm-1">
                                <label class="form-label small"><b><i class="mdi mdi-asterisk"></i> CÓDIGO:</b></label>
                            </div>
                            <div class="col-sm-2">
                                <input class="form-control" type="text" id="txtCodigo" name="txtCodigo">
                            </div>
                            <div class="col-sm-1">
                                <label class="form-label small"><b><i class="mdi mdi-asterisk"></i> SIGLA:</b></label>
                            </div>
                            <div class="col-sm-2">
                                <input class="form-control" type="text" id="txtSigla" name="txtSigla">
                            </div>
                            <div class="col-sm-1">
                                <label class="form-label small"><b><i class="mdi mdi-asterisk"></i> DESCRIPCIÓN:</b></label>
                            </div>
                            <div class="col-sm-5">
                                <input class="form-control" type="text" id="txtDescripcion" name="txtDescripcion">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-10"></div>
                            <div class="col-2 d-flex justify-content-end">                
                                <button type="button" name="btnAdiconarSubcuenta" class="btn btn-block btn-info btn-sm" onclick="guardarPlanDeSubCuentas()">+Agregar Cuenta</button>            
                                <!-- <button type="button" class="btn btn-success" onclick="guardarPlanDeSubCuentas()">Guardar</button> -->
                            </div>
                        </div>
                        <hr>
                        <!-- Fila 2-->
                        <div class="row">
                            <div class="col-12">
                                <table id="tablaPlanDeSubCuentas" class="table" cellspacing="0" width="100%">
                                    <thead class="table-light">
                                        <!-- <tr class="bg-dark text-white"> -->
                                        <tr>
                                            <th>Nro</th>
                                            <th>Código</th>
                                            <th>Descripción</th>
                                            <th>Nivel</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>

                    </fieldset>
                </form>

            </div>
            <!-- <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Cerrar</button>
                <button id="guardar" type="button" class="btn btn-primary" onclick="guardarPlanDeCuentas();"><span class="glyphicon glyphicon-floppy-disk"></span> Guardar Cambios</button>
            </div> -->
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
      var enlace  = "<?php echo base_url();?>";    
      baseurl(enlace);
      cargarTablaPlanDeCuentas();
    //   cargarNiveles();
    });
</script>  