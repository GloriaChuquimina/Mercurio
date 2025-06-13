<script src="<?php echo  base_url() ?>scriptjs/jquery.js"></script>
<script src="<?php echo  base_url() ?>scriptjs/validacion.js"></script>
<!-- <script src="<?php echo  base_url() ?>scriptjs/entidades/entidades.js"></script> -->
<script src="<?php echo  base_url() ?>scriptjs/contabilidad/comprobantes.js"></script>
<link rel="stylesheet" href="<?php echo base_url();?>resources/css/global.css">

<div class="wrapper">
    <div class="content-wrapper" style="margin-left: 0;">
        <form id="formregistrocontable">
            <div>
                <div>
                    <section class="content">
                        <div class="container-fluid" >
                            <div class="card card-primary">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <div class="col-md-8">
                                        <h3 class="card-title mb-0">
                                            ENTIDAD: <b><?= $nombre_entidad ?></b>
                                        </h3>
                                    </div>
                                    <div class="col-md-4 text-right">
                                        <button type="button" class="btn btn-success mr-2" onclick="guardarDatosComprobanteMasDetalle()">
                                            <i class="fas fa-save mr-1"></i> Guardar Comprobante
                                        </button>
                                        <button type="button" class="btn btn-warning mr-2" onclick="generarPDFComprobante()">
                                            <i class="fas fa-print mr-1"></i> PDF
                                        </button>
                                        <button type="button" class="btn btn-info" onclick="cargarComprobantesPrincipal()">
                                            <i class="fas fa-info-circle mr-1"></i> Principal
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- DATOS DEL COMPROBANTE -->
                        <div class="container-fluid" >
                            <div class="card card-primary card-outline">
                                <div class="card-header">
                                    <h3 class="card-title">
                                    <i class="fas fa-file-invoice mr-2"></i>
                                    Información del Comprobante
                                    </h3>
                                </div>

                                <form id="formregistrocontable">
                                    <div class="card-body">
                                    <input type="hidden" id="txtAccionComprobante" name="txtAccionComprobante" />
                                    <input type="hidden" id="id_comprobante" name="id_comprobante" />
                                    <input type="hidden" id="id_entidad" name="id_entidad" />
                                    <div class="row">
                                        <div class="col-md-3">
                                        <div class="form-group">
                                            <label htmlFor="txtTipo">
                                            <i class="text-danger">*</i>
                                            <strong> TIPO:</strong>
                                            </label>
                                            <select class="form-control" id="txtTipo" name="txtTipo"></select>
                                        </div>
                                        </div>

                                        <div class="col-md-3">
                                        <div class="form-group">
                                            <label htmlFor="txtFecha">
                                            <i class="text-danger">*</i>
                                            <strong> FECHA:</strong>
                                            </label>
                                            <input type="date" class="form-control" id="txtFecha" name="txtFecha" />
                                        </div>
                                        </div>

                                        <div class="col-md-3">
                                        <div class="form-group">
                                            <label htmlFor="txtTipoCambio">
                                            <i class="text-danger">*</i>
                                            <strong> TIPO DE CAMBIO:</strong>
                                            </label>
                                            <input
                                            type="text"
                                            class="form-control"
                                            id="txtTipoCambio"
                                            name="txtTipoCambio"
                                            style="background-color: #f3d6d6;"
                                            placeholder="0.00"
                                            value="6.96"
                                            />
                                        </div>
                                        </div>

                                        <div class="col-md-3">
                                        <div class="form-group">
                                            <label>
                                            <strong>ESTADO:</strong>
                                            </label>
                                            <div class="mt-2">
                                            <span class="badge badge-warning">Borrador</span>
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                        <div class="form-group">
                                            <label htmlFor="txtGlosaGeneral">
                                            <i class="text-danger">*</i>
                                            <strong> GLOSA GENERAL:</strong>
                                            </label>
                                            <textarea
                                            class="form-control"
                                            id="txtGlosaGeneral"
                                            name="txtGlosaGeneral"
                                            placeholder="Descripción de glosa general del comprobante..."
                                            ></textarea>
                                        </div>
                                        </div>
                                    </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="container-fluid" >
                            <div class="card card-secondary card-outline">
                                <div class="card-header">
                                    <h3 class="card-title">
                                    <i class="mr-2">📋</i>
                                    DETALLE DEL COMPROBANTE
                                    </h3>
                                    <div class="card-tools">
                                    <button type="button" class="btn btn-info btn-sm" onClick="agregarRegistroComprobante()">
                                        <i class="mr-1">+</i>
                                        Agregar Registro
                                    </button>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                    <table class="table table-striped table-hover" id="tablaRegistroCuenta" style="width: 100%;">
                                    <!-- <table class="table table-head-fixed text-nowrap" id="tablaRegistroCuenta" style="width: 100%;"> -->
                                        <thead class="bg-dark">
                                        <tr>
                                            <th style="color: white">Código</th>
                                            <th style="color: white" >DESCRIPCIÓN</th>
                                            <th style="color: white; text-align: right">DEBE Bs.</th>
                                            <th style="color: white; text-align: right">HABER Bs.</th>
                                            <th style="color: white; text-align: right">DEBE Us.</th>
                                            <th style="color: white; text-align: right">HABER Us.</th>
                                            <th style="color: white; text-align: center">OPCIONES</th>
                                        </tr>
                                        </thead>
                                        <tfoot>
                                            <tr class="bg-primary">
                                                <td colSpan="2" style="color: white; font-weight: bold;">
                                                TOTALES
                                                </td>
                                                    <!-- <td style="text-align: right; color: white; font-weight: bold;" id="txtTotalImporteDebe">0.00</td> -->
                                                    <td style="text-align: right; color: white; font-weight: bold;" class="txtTotalImporteDebe">0.00</td>
                                                    <td style="text-align: right; color: white; font-weight: bold;" class="txtTotalImporteHaber">0.00</td>
                                                    <td style="text-align: right; color: white; font-weight: bold;" class="txtTotalImporteDebeUs">0.00</td>
                                                    <td style="text-align: right; color: white; font-weight: bold;" class="txtTotalImporteHaberUs">0.00</td>
                                                    <td></td>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- /*MODAL REGISTRO DE MOVIMIENTO */ -->
<div class="modal fade show" id="modalRegistroMovimiento" style="background-color: rgba(0,0,0,0.4)" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-lg" style="max-width: 1000px">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <div class="d-flex justify-content-between align-items-center w-100">
                    <div class="col-md-8">
                        <h7 class="modal-title text-white">
                            <i class="mr-2">📋</i>
                            REGISTRO DE MOVIMIENTO:
                            <span style="color:white;"><label id="nombreEntidad">...</label></span>
                        </h7>
                    </div>
                    <div class="col-md-4 text-right">
                        <span class="badge badge-warning">Tipo Cambio:<label id="tipoCambio">...</label></span>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                        <span>&times;</span>
                        </button>
                    </div>
                </div>
             </div>
            <div class="modal-body">
                <form id="formularioRegistroCuenta">
                <!-- CAMPOS OCULTOS -->
                <input type="hidden" class="form-control" id="txtAccionComprobante" name="txtAccionComprobante">
                <input type="hidden" class="form-control" id="id_comprobante" name="id_comprobante">
                <input class="form-control" id="txtAccionMovimiento" name="txtAccionMovimiento">
                <input class="form-control" id="id_entidad_registro" name="id_entidad_registro">
                <input class="form-control" id="id_cuenta" name="id_cuenta">
                <input class="form-control" id="registroCuentaT" name="registroCuentaT">
                <input class="form-control" id="tipo_cambio_movimiento" name="tipo_cambio_movimiento">
                <div class="card card-outline card-info">
                    <div class="card-header">
                    <h7 class="card-title">
                        <i class="mr-2">✏️</i>
                        REGISTRO DE CUENTA
                    </h7>
                    </div>
                    <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>
                                <i class="text-danger">*</i>
                                <strong> DESCRIPCIÓN CUENTA:</strong>
                                </label>
                                <div class="input-group">
                                    <input
                                        type="text"
                                        class="form-control"
                                        placeholder="Buscar cuenta..."
                                        list="listaCuentas"
                                        id="txtCuenta" 
                                        name="txtCuenta"
                                    />
                                    <datalist id='listaCuentas'></datalist>
                                    <input type='hidden' name='idCuenta' id='idCuenta' >
                                    <div class="input-group-append">
                                        <button
                                        type="button"
                                        class="btn btn-warning"
                                        onclick="listaCuentasBusqueda();"
                                        >
                                        <i>🔍</i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>
                                <i class="text-danger">*</i>
                                <strong> TIPO:</strong>
                                </label>
                                <select class="form-control" id="txtTipoMovimiento" name="txtTipoMovimiento">
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                        <div class="form-group">
                            <label>
                            <i class="text-danger">*</i>
                            <strong> IMPORTE:</strong>
                            </label>
                            <input type="number" id="txtImporte" name="txtImporte" class="form-control text-right" placeholder="0.00" step="0.01" />
                        </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                        <div class="form-group">
                            <label>
                            <i class="text-danger">*</i>
                            <strong> GLOSA:</strong>
                            </label>
                            <textarea 
                            id="txtGlosaCuenta" 
                            name="txtGlosaCuenta"
                            class="form-control"
                            rows="3"
                            placeholder="Descripción del movimiento..."
                            ></textarea>
                        </div>
                        </div>
                    </div>
                    </div>
                </div>
                </form>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal" >
                <i class="mr-1">❌</i>
                Cerrar
            </button>
            <button type="button" class="btn btn-info" onclick="guardarRegistroCuenta();">
                <i class="mr-1">💾</i>
                Guardar Registro
            </button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade show" id="modalListaCuentas" style="backgroundColor: rgba(0,0,0,0.4)" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="max-width: 700px">
        <div class="modal-content">
            <div class="modal-header bg-secondary">
                <h7 class="modal-title text-white">
                    <i class="mr-2">🔍</i>
                    BÚSQUEDA DE CUENTAS CONTABLES
                </h7>
                <button type="button" class="close text-white" data-dismiss="modal" >
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                  <table class="table table-striped table-hover" id="tbl_CuentasContables" style="width: 100%;">
                    <thead class="bg-dark">
                      <tr>
                        <th style="color: white; text-align: center;">OPCIONES</th>
                        <th style="color: white;">CÓDIGO</th>
                        <th style="color: white;">DESCRIPCIÓN</th>
                      </tr>
                    </thead>
                  </table>
                </div>
              </div>
        </div>
    </div>
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