<!-- <script src="<?php echo  base_url() ?>scriptjs/jquery.js"></script> -->
<script src="<?php echo  base_url() ?>scriptjs/contabilidad/plandecuentas.js"></script>
<!-- <script src="<?php echo  base_url() ?>scriptjs/inicio/inicio.js"></script>
<script src="<?php echo  base_url() ?>scriptjs/validacion.js"></script> -->

<!-- jQuery -->
<script src="<?php echo base_url();?>resources/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="<?php echo base_url();?>resources/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url();?>resources/dist/js/adminlte.min.js"></script>



<h1 class="page-title"></h1>
    <div class="card">
        <div class="card-body">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-3 form-group">
                        <button id="botonNuevaCuenta" class="btn btn-block btn-success btn-xm" onclick='agregarCuentas()'><i class="mdi mdi-plus"></i>Alta de Cuentas</button>
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
                                <th>Nivel</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- <div class="modal fade show" id="modal-secondary" style="display: block;" aria-modal="true" role="dialog">
        <div class="modal-dialog">
          <div class="modal-content bg-secondary">
            <div class="modal-header">
              <h4 class="modal-title">Secondary Modal</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
              </button>
            </div>
            <div class="modal-body">
              <p>One fine body…</p>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-outline-light">Save changes</button>
            </div>
          </div>
</div> -->






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
                    <input type="hidden" class="form-control" id="id_aplicacion" name="id_aplicacion">
                    <input type="hidden" class="form-control" id="id_modulo" name="id_modulo">
                    <input type="hidden" class="form-control" id="id_opcion" name="id_opcion">
                    <input type="hidden" class="form-control" id="id_funcionario" name="id_funcionario">
                    <input type="hidden" class="form-control" id="id_usuario" name="id_usuario">

                    <fieldset style="margin-left: 5px;">
                        <!-- <legend id="titulo_registro">FORMULARIO DE REGISTRO PLAN DE CUENTAS</legend> -->

                        <!-- Fila 1: Aplicación y Módulo -->
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

                        <!-- Fila 2: Nivel y Orden -->
                        <div class="form-group row mb-3">
                            <div class="col-sm-1">
                                <label class="form-label small"><i class="mdi mdi-asterisk"></i><b> NIVEL:</b></label>
                            </div>
                            <div class="col-sm-2">
                                <select class="form-control" id="opcionNivel" name="opcionNivel"></select>
                            </div>
                            <div class="col-sm-1">
                                <label class="form-label small"><i class="mdi mdi-asterisk"></i><b> CUENTA SUPERIOR:</b></label>
                            </div>
                            <div class="col-sm-5">
                                <select class="form-control" id="opcionPadre" name="opcionPadre"></select>
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

<script type="text/javascript">
    $(document).ready(function(){
      var enlace  = "<?php echo base_url();?>";    
      baseurl(enlace);
      cargarTablaPlanDeCuentas();
      cargarNiveles();
    });
</script>  