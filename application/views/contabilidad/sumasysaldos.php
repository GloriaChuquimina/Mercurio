<script src="<?php echo  base_url() ?>scriptjs/jquery.js"></script>
<script src="<?php echo  base_url() ?>scriptjs/contabilidad/sumasysaldos.js"></script>
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
                        <!-- <span class="badge badge-warning">XXX cuentas disponibles</span> -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- FILTROS Y OPCIONES -->
            <div class="card card-primary card-outline" id ="filtrosConsulta" style="display: none">
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

									<div class="row align-items-end">
										<!-- Radio "Al" -->
										<div class="col-auto">
											<div class="form-group mb-0">
												<div class="custom-control custom-radio d-flex align-items-center">
													<input class="custom-control-input custom-control-input-danger" 
																type="radio" id="radioAl" name="customRadio2" checked>
													<label for="radioAl" class="custom-control-label mr-2">Al:</label>
													<input id="fechaAl" name="fechaAl" type="date" 
																class="form-control form-control-sm" style="max-width: 150px;">
												</div>
											</div>
										</div>

										<!-- Moneda -->
										<div class="col-auto">
											<div class="form-group mb-0">
												<label for="tipo_moneda"><strong>Moneda:</strong></label>
												<select class="form-control form-control-sm" id="tipo_moneda" name="tipo_moneda" style="max-width: 120px;"></select>    
											</div>                                            
										</div>

										<!-- Nivel -->
										<div class="col-auto">
											<div class="form-group mb-0">
												<label for="nivel">Nivel:</label>
												<input type="number" class="form-control form-control-sm is-warning" 
															name="nivel" id="nivel" placeholder="Ej: 1" style="max-width: 80px;">
											</div>
										</div> 

										<!-- Cuentas -->
										<div class="col-md-4">
											<div class="form-group mb-0">
												<label><strong>Cuentas:</strong></label>
												<div class="input-group input-group-sm">
													<input type="text" class="form-control" placeholder="Buscar cuenta..." 
																list="listaCuentas" id="txtCuenta" name="txtCuenta"/>
													<datalist id='listaCuentas'></datalist>
													<div class="input-group-append">
														<button type="button" class="btn btn-success btn-sm" id="btnAddCuenta" onclick="añadirCuenta();">
															➕
														</button>
														<button type="button" class="btn btn-warning btn-sm" id="btnlistaCuentasBusqueda" onclick="listaCuentasBusqueda();">
															🔍
														</button>
													</div>
												</div>
											</div>
										</div>

										<!-- Opciones -->
										<div class="col-auto">
											<div class="form-check mt-4">
												<input class="form-check-input" type="checkbox" id="soloConMovimientos"/>
												<label class="form-check-label" for="soloConMovimientos">
													Solo cuentas con movimientos
												</label>
											</div>
										</div>
									</div>

									<hr>

									<div class="row align-items-end">
										<!-- Radio "Entre el" -->
										<div class="col-md-6">
											<div class="custom-control custom-radio d-flex align-items-center">
												<input class="custom-control-input custom-control-input-danger" 
															type="radio" id="radioEntre" name="customRadio2">
												<label for="radioEntre" class="custom-control-label mr-2">Entre el:</label>
												<input id="fechaDesde" name="fechaDesde" type="date" 
															class="form-control form-control-sm mr-2" style="max-width: 150px;">
												<input id="fechaHasta" name="fechaHasta" type="date" 
															class="form-control form-control-sm" style="max-width: 150px;">
											</div>
										</div>

										<!-- Botones -->
										<div class="col-md-6 text-right">
											<button class="btn btn-primary btn-sm mr-2" onClick="cargarDatosSumasySaldos()">
												🔍 Generar Balance
											</button>
											<button class="btn btn-success btn-sm mr-2" onClick="ReporteSumasySaldosPDF()">
												📄 Exportar PDF
											</button>
										</div>
									</div>
								</div>


              
            </div>
            <!-- CUENTA SELECCIONADA -->
            <div class="card" style="background-color: #f0f8ff; border-left: 4px solid #007bff" id="cuentaSeleccionada" style="display: none">
                <div class="card-body p-3">
                    <div class="row align-items-center">
                        <div class="col-md-12">
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
                                    <h7 class="mb-0">CUENTA(s):</h7>
                                    <h8 class="mb-0" style="color: #007bff; font-weight: bold;" id="cuentas" name="cuentas">
                                        
                                    </h8>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ESTADO DEL BALANCE -->
            <!-- <div class="card" style="background-color:#D9F6F5;  border-left: 4px solid #10707f;" id="entidadSeleccionada" style="display: none">
                <div class="">
                    <div class="d-flex align-items-center">
                        <div style="font-size: 24px; margin-right: 15px">✅⚠️</div>
                        <div>
                        <h5 class="alert-heading mb-1">Balance Correcto: Balance Desbalanceado</h5>
                        <p class="mb-0">
                            El balance de sumas y saldos está correctamente balanceado.
                            Existe una diferencia de Bs. 000000 que debe ser revisada.
                        </p>
                        </div>
                    </div>
                </div>
            </div> -->
           <!-- TABLA DE BALANCE DE SUMAS Y SALDOS -->
            <div class="card" id="cardSumasySaldos" style="display: none">
                <div class="card-header bg-gradient-secondary">
                  <h3 class="card-title text-white">
                    <i class="mr-2">⚖️</i>
                    BALANCE DE SUMAS Y SALDOS
                  </h3>
                  <div class="card-tools">
                    <span class="badge badge-light">
                      Período: {dateFrom} al {dateTo}
                    </span>
                  </div>
                </div>
                <div class="card-body p-0">
                  <div class="table-responsive">
                    <table id="tablaSumasySaldos" class="table table-striped table-hover" style="width: 100%;">
                      <thead class="bg-dark text-white">
                          <tr>
                            <th rowspan="2" style="width: 150px;">CÓDIGO</th>
                            <th rowspan="2" style="width: 350px;">DESCRIPCIÓN</th>
                            <th colspan="2" style="text-align: center;">SUMAS</th>
                            <th colspan="2" style="text-align: center;">SALDOS</th>
                          </tr>
                          <tr>
                            <th style="width: 120px; text-align: center;">DEBE</th>
                            <th style="width: 120px; text-align: center;">HABER</th>
                            <th style="width: 120px; text-align: center;">DEUDOR</th>
                            <th style="width: 120px; text-align: center;">ACREEDOR</th>
                          </tr>
                      </thead>
                      <!-- <tfoot>
                        <tr class="bg-primary">
                          <td colSpan="4" style="color: white; font-weight:bold">
                            TOTALES
                          </td>
                          <td style=" text-align:right; color: white; font-weight:bold">
                          </td>
                          <td style=" text-align:right; color: white; font-weight:bold">
                          </td>
                          <td style=" text-align:right; color: white; font-weight:bold">
                          </td>
                          <td style=" text-align:right; color: white; font-weight:bold">
                          </td>
                          <td style=" text-align:right; color: white; font-weight:bold">
                          </td>
                        </tr>
                        <tr class="bg-danger">
                          <td colSpan="7" style="color: black; font-weight: bold">
                            VERIFICACIÓN DE BALANCE
                          </td>
                          <td style="text-align: right; color:black; font-weight: bold">
                            DIFERENCIA:
                          </td>
                          <td style="text-align: right; color: black; font-weight: bold">
                            BALANCEADO ✅ : DESBALANCEADO ⚠️
                          </td>
                        </tr>
                      </tfoot> -->
                      <tfoot>
                          <tr class="bg-primary">
                              <td colSpan="2" style="color: white; font-weight: bold;">
                              TOTALES:
                              </td>
                                  <!-- <td style="text-align: right; color: white; font-weight: bold;" id="txtTotalImporteDebe">0.00</td> -->
                                  <td style="text-align: right; color: white; font-weight: bold;" class="txtTotalImporteDebe">0.00</td>
                                  <td style="text-align: right; color: white; font-weight: bold;" class="txtTotalImporteHaber">0.00</td>
                                  <td style="text-align: right; color: white; font-weight: bold;" class="txtTotalImporteDeudor">0.00</td>
                                  <td style="text-align: right; color: white; font-weight: bold;" class="txtTotalImporteAcreedor">0.00</td>
                              </td>
                          </tr>
                      </tfoot>
                    </table>
                  </div>
                </div>
                <div class="card-footer">
                  <div class="row">
                    <div class="col-md-6">
                      <!-- <p class="text-muted">
                        Empresa: • Período:  al  • 
                        cuentas mostradas
                      </p> -->
                    </div>
                    <div class="col-md-6 text-right">
                      <!-- <small class="text-muted">
                        Estado: Balanceado : Desbalanceado • Generado:
                      </small> -->
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
                          <!-- <th style="color: white; text-align: center;">OPCIONES</th> -->
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
      cargarCuentasLista();
    });
</script> 
