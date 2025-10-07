<script src="<?php echo base_url(); ?>scriptjs/jquery.js"></script>
<script src="<?php echo base_url(); ?>scriptjs/validacion.js"></script>
<script src="<?php echo base_url(); ?>scriptjs/contabilidad/comprobantes.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>resources/css/global.css">

<div class="wrapper">
    <div class="content-wrapper" style="margin-left: 0;">
        <form id="formregistrocontablePrincipal">
            <section class="content">
                <div class="container-fluid">
                    <div class="card card-primary">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div class="col-md-8">
                                <h3 class="card-title mb-0">
                                    ENTIDAD: <b><?= $nombre_entidad ?></b>
                                </h3>
                                <input type="hidden" id="nombre_entidad" name="nombre_entidad" value="<?= $nombre_entidad ?>"/>
                            </div>
                            <div class="col-md-4 text-right">
                                <button id="btnGuardar" name="btnGuardar" type="button" class="btn btn-success mr-2" onclick="guardarDatosComprobanteMasDetalle()">
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
                <div class="container-fluid">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-file-invoice mr-2"></i>
                                Información del Comprobante
                            </h3>
                        </div>
                        <div class="card-body">
                            <input type="hidden" id="txtAccionComprobante" name="txtAccionComprobante" />
                            <input type="hidden" id="id_comprobanteP" name="id_comprobanteP" />
                            <input type="hidden" id="id_entidad" name="id_entidad" />
                            <input type="hidden" id="cant_cuentas" name="cant_cuentas" />
                            <input type="hidden" id="tipo_cambio_comprobante" name="tipo_cambio_comprobante" />
                            <input type="hidden" id="total_debe" name="total_debe" />
                            <input type="hidden" id="total_haber" name="total_haber" />
                            <input type="hidden" id="total_debe_us" name="total_debe_us" />
                            <input type="hidden" id="total_haber_us" name="total_haber_us" />
                            
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="txtTipo">
                                            <i class="text-danger">*</i>
                                            <strong> TIPO:</strong>
                                        </label>
                                        <select class="form-control" id="txtTipo" name="txtTipo"></select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="txtFecha">
                                            <i class="text-danger">*</i>
                                            <strong> FECHA:</strong>
                                        </label>
                                        <input type="date" class="form-control" id="txtFecha" name="txtFecha" />
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="txtTipoCambio">
                                            <i class="text-danger">*</i>
                                            <strong> TIPO DE CAMBIO:</strong>
                                        </label>
                                        <div class="input-group">
                                            <input
                                                type="text"
                                                class="form-control"
                                                id="txtTipoCambio"
                                                name="txtTipoCambio"
                                                style="background-color: #f3d6d6; text-align: right;"
                                                placeholder="0.00"
                                                readonly
                                            />   
                                            <div class="input-group-append">
                                                <button
                                                    type="button"
                                                    class="btn btn-danger"
                                                    id="btnRecalcularTipoCambio"
                                                    name="btnRecalcularTipoCambio"
                                                    title="Actualizar detalle del comprobante al Tipo de Cambio"
                                                    onclick="recalcularCuentasDelComprobante();"
                                                    style="display: none"
                                                >
                                                    <i>🔃</i>
                                                </button>
                                            </div>  
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>
                                            <strong>ESTADO:</strong>
                                        </label>
                                        <div class="mt-2">
                                            <span class="badge badge-warning" id="estado_comprobante" name="estado_comprobante">Borrador</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-2" style="display:none;" id="info_numero" name="info_numero">
                                    <div class="info-box bg-info" style="min-height: 60px;">                                                    
                                        <div class="info-box-content p-1" style="line-height: 1.1;">
                                            <span class="info-box-text">#  Comprobante</span>
                                            <span class="info-box-number" style="font-size: 24px; font-weight: bold;" id="nro_comprobante" name="nro_comprobante">...</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="txtReferencia">
                                            <i class="text-danger">*</i>
                                            <strong> REFERENCIA:</strong>
                                        </label>
                                        <textarea
                                            class="form-control"
                                            id="txtReferencia"
                                            name="txtReferencia"
                                            placeholder="Descripción de la referencia del comprobante..."
                                        ></textarea>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="txtGlosaGeneral">
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
                    </div>
                </div>
                
                <div class="container-fluid">
                    <div class="card card-secondary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="mr-2">📋</i>
                                DETALLE DEL COMPROBANTE
                                
                                
                                <!-- <span class='badge badge-danger' id="estado_span_comprobante" type="hidden"><b> <i class="mr-2">⚠️</i> <label id= "estado_comprobante">Desbalanceado</label></b></span> -->
                                 <span id="alertaDiferencia" style="display:none; color:red; font-weight:bold;">
                                        ⚠️ Desbalanceado (Existe una diferencia a ser revisada en el comprobante)
                                </span>
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
                                    <thead class="bg-dark">
                                        <tr>
                                            <th style="color: white">Código</th>
                                            <th style="color: white">DESCRIPCIÓN</th>
                                            <th style="color: white; text-align: right">DEBE Bs.</th>
                                            <th style="color: white; text-align: right">HABER Bs.</th>
                                            <th style="color: white; text-align: right">DIFERENCIA</th>
                                            <th style="color: white; text-align: right">DEBE Us.</th>
                                            <th style="color: white; text-align: right">HABER Us.</th>
                                            <th style="color: white; text-align: right">DIFERENCIA Us.</th>
                                            <th style="color: white; text-align: center">OPCIONES</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr class="bg-primary">
                                            <td colspan="2" style="color: white; font-weight: bold;">
                                                TOTALES
                                            </td>
                                            <td style="text-align: right; color: white; font-weight: bold;" class="txtTotalImporteDebe">0.00</td>
                                            <td style="text-align: right; color: white; font-weight: bold;" class="txtTotalImporteHaber">0.00</td>
                                            <td style="text-align: right; color: black; font-weight: bold;" class="txtTotalDiferencia">0.00</td>
                                            <td style="text-align: right; color: white; font-weight: bold;" class="txtTotalImporteDebeUs">0.00</td>
                                            <td style="text-align: right; color: white; font-weight: bold;" class="txtTotalImporteHaberUs">0.00</td>
                                            <td style="text-align: right; color: black; font-weight: bold;" class="txtTotalDiferenciaUs">0.00</td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </form>
    </div>
</div>

<!-- MODAL REGISTRO DE MOVIMIENTO -->
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
                    <input class="form-control" id="txtAccionComprobanteCuenta" name="txtAccionComprobanteCuenta">
                    <input class="form-control" id="id_comprobante" name="id_comprobante">
                    <input class="form-control" id="txtAccionMovimiento" name="txtAccionMovimiento">
                    <input class="form-control" id="id_entidad_registro" name="id_entidad_registro">
                    <input class="form-control" id="id_cuenta" name="id_cuenta">
                    <input class="form-control" id="registroCuentaT" name="registroCuentaT">
                    <input class="form-control" id="tipo_cambio_movimiento" name="tipo_cambio_movimiento">
                    <input class="form-control" id="id_registroCuentaComprobante" name="id_registroCuentaComprobante">
                    <input class="form-control" id="mensaje" name="mensaje">
                    <input class="form-control" id="id_cuenta_auxiliar" name="id_cuenta_auxiliar">
                    <input class="form-control" id="registroMovimientoCuentaT" name="registroMovimientoCuentaT">
                    
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
                                            <datalist id="listaCuentas"></datalist>
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
                                        <input type="text" id="txtImporte" name="txtImporte" class="form-control text-right" placeholder="0.00" />
                                    </div>
                                </div>
                            </div>
                            
                            <!-- CONVERSION -->
                            <div class="card" style="background-color: #f0f0ff; border-left: 4px solid #3c8dbc;">
                                <div class="card-body p-3">
                                    <div class="row align-items-center">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="d-flex align-items-end">
                                                    <div style="flex-grow: 1; margin-right: 10px;">
                                                        <label for="txtMontoUSD">
                                                            <strong>Monto en Dólares(USD):</strong>
                                                        </label>
                                                        <div class="input-group">
                                                            <div class="input-group-append">
                                                                <button
                                                                    type="button"
                                                                    class="btn btn-primary"
                                                                >
                                                                    <i>💵 USD</i>
                                                                </button>
                                                            </div>
                                                            <input
                                                                type="text"
                                                                class="form-control text-right"
                                                                placeholder="0.00"
                                                                id="txtMontoUSD" 
                                                                name="txtMontoUSD"
                                                            />
                                                        </div>
                                                        <label>
                                                            Introducir monto en dólares estadounidenses
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="d-flex align-items-end">
                                                    <div style="flex-grow: 1; margin-right: 10px;">
                                                        <label for="txtMontoBOB">
                                                            <strong>Equivalente en Moneda Local:</strong>
                                                        </label>
                                                        <div class="input-group">
                                                            <div class="input-group-append">
                                                                <button
                                                                    type="button"
                                                                    class="btn btn-secondary"
                                                                >
                                                                    <i>🪙</i> Bs.
                                                                </button>
                                                            </div>
                                                            <input
                                                                type="text"
                                                                class="form-control text-right"
                                                                placeholder="0.00"
                                                                id="txtMontoBOB" 
                                                                name="txtMontoBOB"
                                                                readonly
                                                            />
                                                        </div>
                                                        <label>
                                                            Calculado automáticamente al tipo de cambio seleccionado
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- FIN CONVERSION -->
                            
                            <div class="row" id="auxiliares_cuenta">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>
                                            <i class="text-danger">*</i>
                                            <strong> AUXILIARES DE LA CUENTA:</strong>
                                        </label>
                                        <div class="input-group">
                                            <input
                                                type="text"
                                                class="form-control"
                                                placeholder="Buscar auxiliar de la cuenta..."
                                                list="listaAuxiliaresDeCuenta"
                                                id="txtAuxiliarCuenta" 
                                                name="txtAuxiliarCuenta"
                                            />
                                            <datalist id="listaAuxiliaresDeCuenta"></datalist>
                                            <div class="input-group-append">
                                                <button
                                                    type="button"
                                                    class="btn btn-info"
                                                    onclick="listaCuentasAuxiliaresBusqueda();"
                                                >
                                                    <i>🕵️</i>
                                                </button>
                                            </div>
                                        </div>
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
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <i class="mr-1">❌</i>
                    Cerrar
                </button>
                <button type="button" class="btn btn-info" onclick="guardarRegistroCuenta();" id="btnGuardarCuenta">
                    <i class="mr-1">💾</i>
                    Guardar Registro
                </button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL LISTA CUENTAS -->
<div class="modal fade show" id="modalListaCuentas" style="background-color: rgba(0,0,0,0.4)" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="max-width: 700px">
        <div class="modal-content">
            <div class="modal-header bg-secondary">
                <h7 class="modal-title text-white">
                    <i class="mr-2">🔍</i>
                    BÚSQUEDA DE CUENTAS CONTABLES
                </h7>
                <button type="button" class="close text-white" data-dismiss="modal">
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
                                <th style="color: white;">NIVEL</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <i class="mr-1">❌</i>
                    Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL LISTA CUENTAS AUXILIARES -->
<div class="modal fade show" id="modalListaCuentasAuxiliares" style="background-color: rgba(0,0,0,0.4)" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="max-width: 700px">
        <div class="modal-content">
            <div class="modal-header bg-secondary">
                <h7 class="modal-title text-white">
                    <i class="mr-2">🔍</i>
                    BÚSQUEDA AUXILIARES DE LA CUENTA:
                </h7>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="tbl_CuentasAuxiliares" style="width: 100%;">
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
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <i class="mr-1">❌</i>
                    Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        var enlace = "<?php echo base_url(); ?>";    
        baseurl(enlace);
        id_entidad = <?= json_encode($entidad) ?>;
        nombre_entidad = <?= json_encode($nombre_entidad) ?>;
        accion = <?= json_encode($accion) ?>;
        id_comprobante = <?= json_encode($id_comprobante) ?>;
        gestion = <?= json_encode($gestion) ?>;
        $('#id_entidad').val(id_entidad);
        $('#id_comprobanteP').val(id_comprobante);
        $('#txtAccionComprobante').val(accion);
        cargarCuentasLista();
        cargarCombos();
        if(id_comprobante > 0) {
            cargarDatosComprobante(id_comprobante, id_entidad);
        }
    });
</script>

<script src="<?php echo base_url(); ?>scriptjs/cleave.min.js"></script>
<script type="text/javascript">   
    if($("#txtImporte").length > 0) {     
        new Cleave('#txtImporte', {
            numeral: true,
            numeralThousandsGroupStyle: 'thousand',
            numeralDecimalMark: '.',
            delimiter: ',',
            numeralIntegerScale: 20
        });
    }    
    if($("#txtMontoUSD").length > 0) {     
        new Cleave('#txtMontoUSD', {
            numeral: true,
            numeralThousandsGroupStyle: 'thousand',
            numeralDecimalMark: '.',
            delimiter: ',',
            numeralIntegerScale: 20
        });
    }    
    if($("#txtMontoBOB").length > 0) {     
        new Cleave('#txtMontoBOB', {
            numeral: true,
            numeralThousandsGroupStyle: 'thousand',
            numeralDecimalMark: '.',
            delimiter: ',',
            numeralIntegerScale: 20
        });
    }    
</script>