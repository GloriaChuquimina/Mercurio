<script src="<?php echo  base_url() ?>scriptjs/jquery.js"></script>
<script src="<?php echo  base_url() ?>scriptjs/contabilidad/balancegeneral.js"></script>
<div class="wapper">
    <section class="content">
        <div class="container-fluid">
             <!-- SECCION ENTIDAD -->
            <div class="card card-warning card-outline" id="cardEntidad">
                <div class="card-header">
                    <h3 class="card-title">
                    <i class="mr-2">🏢</i>
                    Selección de Entidad
                    </h3>
                    <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" >
                      <i>🔼🔽</i>
                    </button>
                  </div>
                </div>
                <div class="card-body">
                    <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                        <label>
                            <i class="text-danger">*</i>
                            <strong> ENTIDAD:</strong>
                        </label>
                        <select
                            class="form-control"
                            id="entidades"
                            name="entidades"
                        >
                        </select>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
            <!-- SECCION ENTIDAD SELECCIONADA -->
            <div class="card" style="background-color: #fff3cd; border-left: 4px solid #ffc107;" id="entidadSeleccionada" style="display: none">
                <div class="card-body p-3">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center">
                                    <div
                                    style="
                                        width: 48px,
                                        height: 48px,
                                        border-radius: 50%,
                                        background-color: #ffc107,
                                        display: flex,
                                        align-items: center,
                                        justify-content: center,
                                        color: white,
                                        font-weight: bold,
                                        font-size: 18px,
                                    "
                                    >
                                    </div>
                                    <div class="ml-3">
                                    <h5 class="mb-0">ENTIDAD SELECCIONADA:</h5>
                                    <h4 class="mb-0" style="color: #856404; font-weight: bold;" id ="nombre_entidad" name="nombre_entidad">
                                        ....
                                    </h4>
                                    </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-right">
                        <span class="badge badge-warning">XXX cuentas disponibles</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- FILTROS Y OPCIONES -->
            <div class="card card-primary card-outline">
                <div class="card-header">
                  <h3 class="card-title">
                    <i class="mr-2">🔍</i>
                    Filtros y Opciones
                  </h3>
                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" >
                      <i>🔼🔽</i>
                    </button>
                  </div>
                </div>

                <div class="card-body">
                  <input type="hidden" class="form-control" id="id_entidad" name="id_entidad">
                  <input type="hidden" class="form-control" id="id_cuenta" name="id_cuenta">
                  <input type="hidden" class="form-control" id="id_cuenta_seleccionadas" name="id_cuenta_seleccionadas">

                  <div class="row align-items-center">
                    <!-- Radio "Al" -->
                    <div class="col-sm-6 col-md-auto">
                      <div class="custom-control custom-radio d-flex align-items-center">
                        <input class="custom-control-input custom-control-input-danger" 
                              type="radio" id="radioAl" name="customRadio2" checked>
                        <label for="radioAl" class="custom-control-label mb-0 mr-1">Al</label>
                        <input id="fechaAl" name="fechaAl" type="date" 
                              class="form-control" style="max-width: 140px;">
                      </div>
                    </div>

                    <!-- Radio "Entre el" -->
                    <div class="col-sm-6 col-md-auto">
                      <div class="custom-control custom-radio d-flex align-items-center">
                        <input class="custom-control-input custom-control-input-danger" 
                              type="radio" id="radioEntre" name="customRadio2">
                        <label for="radioEntre" class="custom-control-label mb-0 mr-1">Entre el:</label>
                        <input id="fechaDesde" name="fechaDesde" type="date" 
                              class="form-control mr-1" style="max-width: 140px;">
                        <input id="fechaHasta" name="fechaHasta" type="date" 
                              class="form-control" style="max-width: 140px;">
                      </div>
                    </div>

                    <!-- Moneda -->
                    <div class="col-sm-6 col-md-auto">
                      <div class="d-flex align-items-center">
                        <label for="tipo_moneda" class="mr-1"><strong>Moneda:</strong></label>
                        <select class="form-control" id="tipo_moneda" name="tipo_moneda" style="max-width: 100px;"></select>    
                      </div>                                            
                    </div>

                    <!-- Nivel -->
                    <div class="col-sm-6 col-md-auto">
                      <div class="d-flex align-items-center">
                        <label for="nivel" class="mr-1">Nivel:</label>
                        <input type="number" class="form-control is-warning" name="nivel" id="nivel" 
                              placeholder="Ej: 1" style="max-width: 80px;">
                      </div>
                    </div>

                    <!-- Opción -->
                    <div class="col-sm-12 col-md-auto">
                      <div class="form-check m-0">
                        <input class="form-check-input" type="checkbox" id="saldoCero">
                        <label class="form-check-label" for="saldoCero">
                          Incluir cuentas con saldo cero
                        </label>
                      </div>
                    </div>
                  </div>

                  <!-- Botones -->
                  <div class="row mt-2">
                    <div class="col-12 text-center text-md-right">
                      <button class="btn btn-primary btn-sm mr-1" onClick="cargarDatosBalanceGeneral()">
                        🔍 Generar Balance
                      </button>
                      <button class="btn btn-success btn-sm mr-1" onClick="ReporteBalanceGeneralPDF1()">
                        📄 Reporte 1
                      </button>
                      <button class="btn btn-warning btn-sm" onClick="ReporteBalanceGeneralPDF2()">
                        📄 Reporte 2
                      </button>
                    </div>
                  </div>
                </div>                
            </div>
            <div class="card">
                <div class="card-header bg-gradient-secondary">
                  <h3 class="card-title text-white">
                    <i class="mr-2">⚖️</i>
                    BALANCE GENERAL
                  </h3>
                  <div class="card-tools">
                    <span class="badge badge-light">
                      Período: {dateFrom} al {dateTo}
                    </span>
                  </div>
                </div>
                <div class="card-body p-0">
                  <div class="table-responsive">
                    <table id="tablaBalanceGeneral" class="table table-striped table-hover" style="width: 100%;">
                        <thead class="bg-dark">
                        <tr>
                            <th style="color: white;">CÓDIGO</th>
                            <th style="color: white;">DESCRIPCIÓN</th>
                            <th style="color: white; text-align: right;">-</th>
                            <th style="color: white; text-align: right;">-</th>
                            <th style="color: white; text-align: right;">IMPORTE</th>
                        </tr>
                        </thead>
                    </table>
                  </div>
                </div>
                <div class="card-footer">
                  <div class="row">
                    <div class="col-md-6">
                      <p class="text-muted">
                        Empresa: • Período:  al  • 
                        cuentas mostradas
                      </p>
                    </div>
                    <div class="col-md-6 text-right">
                      <small class="text-muted">
                        Estado: Balanceado : Desbalanceado • Generado:
                      </small>
                    </div>
                  </div>
                </div>
            </div>
        </div>
    </section>
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
            <form id="formListaCuentas" name="formListaCuentas">
              <div class="modal-body">
                  <div class="table-responsive">
                    <table class="table table-striped table-hover" id="tbl_CuentasContables" style="width: 100%;">
                      <thead class="bg-dark">
                        <tr>
                          <th><input type='checkbox' value='0' name = 'opcionSeleccionar' id='opcionSeleccionar'> &nbsp;</th>
                          <!-- <th style="color: white; text-align: center;">SELECCIONAR</th> -->
                          <th style="color: white; text-align: center;">OPCIONES</th>
                          <th style="color: white;">CÓDIGO</th>
                          <th style="color: white;">DESCRIPCIÓN</th>
                          <th style="color: white;">NIVEL</th>
                        </tr>
                      </thead>
                    </table>
                  </div>
              </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
      var enlace  = "<?php echo base_url();?>";    
      baseurl(enlace);
      cargarCombos();
    //   cargarCuentasLista();
    });
</script> 
