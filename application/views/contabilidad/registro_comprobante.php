<script src="<?php echo  base_url() ?>scriptjs/jquery.js"></script>
<!-- <script src="<?php echo  base_url() ?>scriptjs/entidades/entidades.js"></script> -->
<script src="<?php echo  base_url() ?>scriptjs/contabilidad/comprobantes.js"></script>
<link rel="stylesheet" href="<?php echo base_url();?>resources/css/global.css">


<!-- <h1 class="page-title">ENTIDAD: <?= $nombre_entidad ?></h1> -->
<div class="card">
    <div class="col-12">
        <form id="formregistrocontable">
            <div>
                <div data-pws-tab="anynameyouwant1" data-pws-tab-name="REGISTRO COMPROBANTE CONTABLE">
                    <br>
                    <div class="row">
                        <div class="col-md-6">
                        </div>
                        <div class="col-md-6 d-flex justify-content-end gap-3">
                                <button type="button" class="btn btn-success m-1" onclick="guardarDatosComprobanteMasDetalle()">
                                    <i class="mdi mdi-plus"></i> Guardar Comprobante
                                </button>
                                <button type="button" class="btn btn-warning m-1" onclick="reporteEntidadInmueblesPDF(<?= $entidad ?>)">
                                    <i class="mdi mdi-printer"></i> PDF
                                </button>
                                <button type="button" class="btn btn-primary m-1" onclick="cargarComprobantesPrincipal()">
                                    <i class="mdi mdi-information"></i> Comprobantes Principal
                                </button>
                        </div>
                    </div>
                    <div class="container-fluid" style="background-color:#D8CEF6;">
                        <hr>
                        <div class="row" >
                            <div class="col-md-6">
                                <h5 class="page-title">ENTIDAD:<b><?= $nombre_entidad?></b></h5>
                            </div>
                        </div>
                        <hr>
                    </div>
                    <div class="col-12 col-lg-12 ml-lg-auto">              
                        <!-- <form id="formularioComprobante"> -->
                            <!-- Inputs ocultos -->
                            <input type="hidden" class="form-control" id="txtAccionComprobante" name="txtAccionComprobante">
                            <input type="hidden" class="form-control" id="id_comprobante" name="id_comprobante">                                                        
                            <input type="hidden" class="form-control" id="id_entidad" name="id_entidad">                                                                                                               
                                <div class="form-group row mb-6">
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
                                        <input class="form-control" style="background-color:#f3d6d6;" type="text" id="txtTipoCambio" name="txtTipoCambio">
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <div class="col-sm-12">
                                        <label class="form-label small"><b><i class="mdi mdi-asterisk"></i> GLOSA GENERAL:</b></label>
                                        <textarea class="form-control" type="text" id="txtGlosaGeneral" name="txtGlosaGeneral"></textarea>
                                    </div>
                                </div>                            
                        <!-- </form> -->
                        <hr>   
                        <div class="card">
                            <div class="card-body">
                                    <div class="row align-items-center mb-3">
                                        <div class="col-md-9">
                                            <h7 id="titulo_registro">DETALLE DEL COMPROBANTE</h7>
                                        </div>
                                        <div class="col-md-3 d-flex justify-content-center">
                                            <button type="button" name="btnAdiconarRegistro" class="btn btn-info" onclick="agregarRegistroComprobante()">
                                                + Agregar Registro
                                            </button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <!-- <table id="tablaRegistroCuenta" class="table" cellspacing="0" width="100%"> -->
                                            <table id="tablaRegistroCuenta" class="table table-striped" cellspacing="0" width="100%">
                                                <thead class="bg-dark text-white">
                                                    <!-- <tr class="bg-dark text-white"> -->
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
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade show" id="modalRegistroMovimiento" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-lg" style="max-width: 1300px;" role="document">
        <div class="modal-content" style="border-radius: 10px;" >
            <div class="modal-header">
                <!-- <h4 class="modal-title" id="exampleModalLabel" style="color: black !important;">COMPROBANTE<span style="color:black;"><label id="nombreEntidad">...</label></span></h4> -->
                <b><h7 class="modal-title" id="exampleModalLabel">REGISTRO DE MOVIMIENTO:</h7></b><span style="color:purple;"><label id="nombreEntidad">...</label></span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">              
                <form id="formularioComprobante">
                     <!-- Inputs ocultos -->
                    <input type="hidden" class="form-control" id="txtAccionComprobante" name="txtAccionComprobante">
                    <input type="hidden" class="form-control" id="id_comprobante" name="id_comprobante">
                    <input class="form-control" id="txtAccionMovimiento" name="txtAccionMovimiento">
                    <input class="form-control" id="id_entidad_registro" name="id_entidad_registro">
                    <input class="form-control" id="id_cuenta" name="id_cuenta">
                    <input class="form-control" id="registroCuentaT" name="registroCuentaT">
    
                    <fieldset style='margin-left: 5px;'>
                        <legend id= "titulo_registro">REGISTRO DE CUENTA </legend>
                       
                        <!-- <div class="form-group row mb-3">
                            <div class="col-sm-2">
                                <label class="form-label small">
                                    <b><i class="mdi mdi-asterisk"></i> CÓDIGO:</b>
                                </label>
                                <div class="input-group">
                                    <button id="guardar" type="button" class="btn btn-warning btn-sm"  onclick="listaCuentasBusqueda();">
                                        <i class="fas fa-search-plus"></i>
                                    </button>
                                    <input class="form-control" type="text" style="background-color:#E7D9FC;" id="txtCodigo" name="txtCodigo" readonly>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <label class="form-label small"><b><i class="mdi mdi-asterisk"></i> DESCRIPCIÓN CUENTA:</b></label>
                                <input type="text" list="listaCuentas" class="form-control" id="txtCuenta" name="txtCuenta" >
                                <datalist id='listaCuentas'></datalist>
                                <input type='hidden' name='idCuenta' id='idCuenta' >
                            </div>
                            <div class="col-sm-2">
                                <label class="form-label small"><b><i class="mdi mdi-asterisk"></i> TIPO MOVIMIENTO:</b></label>
                                <select class="form-control" type="text" id="txtTipoMovimiento" name="txtTipoMovimiento"></select>
                            </div>
                            <div class="col-sm-2">
                                <label class="form-label small"><b><i class="mdi mdi-asterisk"></i> IMPORTE:</b></label>
                                <input class="form-control" type="text" id="txtImporte" name="txtImporte">
                            </div>
                        </div> -->
                        <div class="form-group row mb-3">
                            <!-- <div class="col-sm-2">
                                <label class="form-label small">
                                    <b><i class="mdi mdi-asterisk"></i> CÓDIGO:</b>
                                </label>
                                <div class="input-group">
                                    <button id="guardar" type="button" class="btn btn-warning btn-sm"  onclick="listaCuentasBusqueda();">
                                        <i class="fas fa-search-plus"></i>
                                    </button>
                                    <input class="form-control" type="text" style="background-color:#E7D9FC;" id="txtCodigo" name="txtCodigo" readonly>
                                </div>
                            </div> -->

                            <div class="col-sm-8">
                                <label class="form-label small"><b><i class="mdi mdi-asterisk"></i> DESCRIPCIÓN CUENTA:</b></label>
                                <input type="text" list="listaCuentas" class="form-control" id="txtCuenta" name="txtCuenta" >
                                <datalist id='listaCuentas'></datalist>
                                <input type='hidden' name='idCuenta' id='idCuenta' >
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
                            <div class="col-sm-12">
                                <label class="form-label small"><b><i class="mdi mdi-asterisk"></i> GLOSA:</b></label>
                                <textarea class="form-control" type="text" id="txtGlosaCuenta" name="txtGlosaCuenta"></textarea>
                            </div>
                        </div>
                    </fieldset>                    
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Cerrar</button>
                <button id="guardar" type="button" class="btn btn-info" onclick="guardarRegistroCuenta();"><span class="glyphicon glyphicon-floppy-disk"></span> Guardar Registro</button>
            </div>
        </div>
    </div>
</div>
<!-- VENTANA LISTA DE CUENTAS BUSQUEDA -->
 <!-- <style>
{
   .modal-dialog {
    max-width: 700px;
    margin: 1.75rem auto;
    }

   .modal-content {
        max-height: calc(100vh - 100px); /* adapta al alto de la ventana */
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }

   .modal-body {
        max-height: calc(100vh - 110px);
        overflow-y: auto;
        flex: 1 1 auto;
    }
}
</style> -->
<div class="modal fade" id="modalListaCuentas" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 700px;">
        <div class="modal-content" >
            <div class="modal-header">
                <!-- <h3 class="modal-title" id="exampleModalLabel"><b>AÑADIR TIPO BIEN </b></h3> -->
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formCuentas">
                    <div class="row">                        
                        <div class="col-12">                        
                            <table id="tbl_CuentasContables" class="table table-striped cellspacing="0" width="100%">
                                <thead>
                                    <tr class="bg-dark text-white">
                                        <th>Opciones</th>
                                        <th>Código</th>
                                        <th>Descripcion</th>
                                    </tr>
                                </thead>
                                <tbody>                                         
                                </tbody>
                            </table>
                        </div>
                    </div>
                </form>
            </div>          
        </div>
    </diV>   
</div>
<script type="text/javascript">
    $(document).ready(function(){
      var enlace  = "<?php echo base_url();?>";    
      baseurl(enlace);
      cargarCombos();
       id_entidad = <?= json_encode($entidad) ?>;
       nombre_entidad = <?= json_encode($nombre_entidad) ?>;
       accion = <?= json_encode($accion) ?>;
       $('#id_entidad').val(id_entidad);
       cargarCuentasLista();
    //   cargarPerfilesUsuarios(tipoPerfil); 
    //   cargarTablaComprobantes();
    });
</script>  