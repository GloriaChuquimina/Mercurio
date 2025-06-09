<script src="<?php echo  base_url() ?>scriptjs/jquery.js"></script>
<script src="<?php echo  base_url() ?>scriptjs/contabilidad/comprobantes.js"></script>

<link rel="stylesheet" href="<?php echo base_url();?>resources/css/global.css">

<div>
<h1 class="page-title"></h1>
    <div class="card">
        <div class="card-body">
            <div class="container-fluid">
                <hr>
                <div class="row" >
                    <div class="col-9 form-group">
                       <div class="col-lg-12 mi-div" style="padding: 15px;">                                
                            <label><small><b> <i class="mdi mdi-asterisk"></i>ENTIDADES: </b></small></label>                                
                            <select class="form-control" id="entidades" name="entidades"></select>
                        </div>
                    </div>
                    <div class="col-3 form-group">
                        <br><br>
                        <div class="d-flex justify-content-center">
                            <button id="botonNuevaCuenta" class="btn btn-success btn-m" onclick="agregarComprobante()">
                                <i class="fa fa-plus-circle"></i> Registro de Comprobante
                            </button>
                        </div>
                    </div>
                </div>
                <hr>
            </div>
        </div>    
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <table id="tablaComprobantesEntidades" class="table table-striped" cellspacing="0" width="100%">
                        <thead>
                            <tr class="bg-dark text-white">
                                <th>Opciones</th>
                                <th>Nro</th>
                                <th>Nombre</th>
                                <th>Sigla</th>
                                <th>Observaciones</th>
                                <th>Fecha de Registro</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- <div class="modal fade show" id="modalRegistroComprobante" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-lg" style="max-width: 1200px;" role="document">
        <div class="modal-content" style="border-radius: 10px;" >
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel" style="color: black !important;">COMPROBANTE<span style="color:black;"></span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">              
                <form id="formularioComprobante">
                    <input type="hidden" class="form-control" id="txtAccionComprobante" name="txtAccionComprobante">
                    <input type="hidden" class="form-control" id="id_comprobante" name="id_comprobante">
                    <fieldset style='margin-left: 5px;'>
                        <legend id= "titulo_registro">FORMULARIO DE REGISTRO DE COMPROBANTE</legend>
                                                  
                         <div class="form-group row mb-3">
                            <div class="col-sm-1">
                                <label class="form-label small"><b><i class="mdi mdi-asterisk"></i> TIPO:</b></label>
                            </div>
                            <div class="col-sm-2">
                                <select class="form-control" id="txtTipo" name="txtTipo"></select>
                            </div>
                            <div class="col-sm-1">
                            </div>
                            <div class="col-sm-2">
                            </div>
                            <div class="col-sm-1">
                                <label class="form-label small"><b><i class="mdi mdi-asterisk"></i> FECHA:</b></label>
                            </div>
                            <div class="col-sm-2">
                                <input class="form-control" type="date" id="txtFecha" name="txtFecha">
                            </div>
                            <div class="col-sm-1">
                                <label class="form-label small"><b><i class="mdi mdi-asterisk"></i> TIPO DE CAMBIO:</b></label>
                            </div>
                            <div class="col-sm-2">
                                <input class="form-control" type="text" id="txtTipoCambio" name="txtTipoCambio">
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <div class="col-sm-12">
                                <label class="form-label small"><b><i class="mdi mdi-asterisk"></i> ENTIDAD:</b></label>
                                <select class="form-control" type="text" id="txtEntidad" name="txtEntidad"></select>
                            </div>
                        </div> 
                        <div class="form-group row mb-3">
                            <div class="col-sm-12">
                                <label class="form-label small"><b><i class="mdi mdi-asterisk"></i> GLOSA GENERAL:</b></label>
                                <textarea class="form-control" type="text" id="txtDescripcion" name="txtDescripcion"></textarea>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset style='margin-left: 5px;'>
                        <legend id= "titulo_registro">REGISTRO DE CUENTA </legend>
                       
                        <div class="form-group row mb-3">
                            <div class="col-sm-2">
                                <label class="form-label small"><b><i class="mdi mdi-asterisk"></i> CÓDIGO:</b></label>
                                <input class="form-control" type="text" id="txtCodigo" name="txtCodigo">
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label small"><b><i class="mdi mdi-asterisk"></i> DESCRIPCIÓN CUENTA:</b></label>
                                <input class="form-control" type="text" id="txtDescripcion" name="txtDescripcion">
                            </div>
                            <div class="col-sm-2">
                                <label class="form-label small"><b><i class="mdi mdi-asterisk"></i> TIPO MOVIMIENTO:</b></label>
                                <select class="form-control" type="text" id="txtTipoMovimiento" name="txtTipoMovimiento"></select>
                            </div>
                            <div class="col-sm-2">
                                <label class="form-label small"><b><i class="mdi mdi-asterisk"></i> IMPORTE:</b></label>
                                <input class="form-control" type="text" id="txtImporte" name="txtImporte">
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <div class="col-sm-9">
                                <label class="form-label small"><b><i class="mdi mdi-asterisk"></i> GLOSA:</b></label>
                                <textarea class="form-control" type="text" id="txtGlosa" name="txtGlosa"></textarea>
                            </div>
                            <div class="col-sm-3">
                                <br>
                                <br>
                                <button type="button" name="btnAdiconarSubcuenta" class="btn btn-info btn-sm" onclick="guardarPlanDeSubCuentas()">+Agregar Registro</button>            
                            </div>
                        </div>
                    </fieldset>
                    <fieldset style='margin-left: 5px;'>
                        <legend id= "titulo_registro">DETALLE DEL COMPROBANTE</legend>
                        <div class="row">
                            <div class="col-12">
                                <table id="tablaPlanDeSubCuentas" class="table table-striped table-responsive" cellspacing="0" width="100%">
                                    <thead class="bg-dark text-white">
                                        <tr>
                                            <th>Código</th>
                                            <th>DESCRIPCIÓN</th>
                                            <th>DEBE Bs.</th>
                                            <th>HABER Bs.</th>
                                            <th>DEBE  Us.</th>
                                            <th>HABER Us.</th>
                                            <th>OPCIONES</th>
                                        </tr>
                                    </thead>
                                </table>
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
</div> -->
<script type="text/javascript">
    $(document).ready(function(){
      var enlace  = "<?php echo base_url();?>";    
      baseurl(enlace);
      cargarCombos();
      cargarTablaComprobantesEntidades();
    });
</script>  