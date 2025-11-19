<script src="<?php echo  base_url() ?>scriptjs/jquery.js"></script>
<script src="<?php echo  base_url() ?>scriptjs/contabilidad/cierredebalance.js"></script>
<div class="wapper">
    <section class="content">
        <div class="container-fluid">
             <!-- SECCION ENTIDAD -->
            <div class="card card-success card-outline" id="cardEntidad">
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
            <div class="card" style="background-color: #c2f1bcff; border-left: 4px solid #139c26ff;" id="entidadSeleccionada" style="display: none">
                <div class="card-body p-3">
									 <input type ="hidden" class="form-control" id="id_entidad" name="id_entidad">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center">
                                    <div
                                    style="
                                        width: 48px,
                                        height: 48px,
                                        border-radius: 50%,
                                        background-color: #09972dff,
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
                                    <h4 class="mb-0" style="color: #000000; font-weight: bold;" id ="nombre_entidad" name="nombre_entidad">
                                        ....
                                    </h4>
                                    </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-right">
                        <!-- <span class="badge badge-warning">XXX cuentas disponibles</span> -->
													<button class = "btn btn-success mr-1"
																onClick = "procedimientoCierreCuentasDeBalance()">
														<i class="mr-1">⏳</i> Procesar Cierre 
													</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- FILTROS Y OPCIONES -->
            <!-- <div class="card card-success card-outline">
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
                  <input  class="form-control" id="id_entidad" name="id_entidad">
                  <input type="hidden" class="form-control" id="id_cuenta" name="id_cuenta">
                  <input type="hidden" class="form-control" id="id_cuenta_seleccionadas" name="id_cuenta_seleccionadas">

                  <div class="row align-items-center">

                    <div class="col-sm-6 col-md-auto">
                      <div class="custom-control custom-radio d-flex align-items-center">
                        <input class="custom-control-input custom-control-input-danger" 
                              type="radio" id="radioAl" name="customRadio2" checked>
                        <label for="radioAl" class="custom-control-label mb-0 mr-1">Al</label>
                        <input id="fechaAl" name="fechaAl" type="date" 
                              class="form-control" style="max-width: 140px;">
                      </div>
                    </div>


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


                    <div class="col-sm-6 col-md-auto">
                      <div class="d-flex align-items-center">
                        <label for="tipo_moneda" class="mr-1"><strong>Moneda:</strong></label>
                        <select class="form-control" id="tipo_moneda" name="tipo_moneda" style="max-width: 100px;"></select>    
                      </div>                                            
                    </div>


                    <div class="col-sm-6 col-md-auto">
                      <div class="d-flex align-items-center">
                        <label for="nivel" class="mr-1">Nivel:</label>
                        <input type="number" class="form-control is-warning" name="nivel" id="nivel" 
                              placeholder="Ej: 1" style="max-width: 80px;">
                      </div>
                    </div>

                    <div class="col-sm-12 col-md-auto">
                      <div class="form-check m-0">
                        <input class="form-check-input" type="checkbox" id="saldoCero">
                        <label class="form-check-label" for="saldoCero">
                          Incluir cuentas con saldo cero
                        </label>
                      </div>
                    </div>

                    <div class="col-sm-4 col-md-auto text-md-right">
                      <div class="form-check m-0">
                        <button class = "btn btn-success mr-1"
                              onClick = "procedimientoCierreCuentasDeBalance()">
                          <i class="mr-1">⏳</i> Procesar Cierre 
                        </button>
                      </div>
                    </div>
                  </div>


                  <div class="row mt-2">
                    <div class="col-12 text-center text-md-right">
                     
                    </div>
                  </div>
                </div>                
            </div> -->
            <div class="card">
                <div class="card-header bg-gradient-success">
                  <h3 class="card-title text-white">
                    <i class="mr-2">📘🧾</i>
                    CIERRES DE CUENTAS DE BALANCE
                  </h3>
                  <div class="card-tools">
                    <!-- <span class="badge badge-light">
                      Período: {dateFrom} al {dateTo}
                    </span> -->
                  </div>
                </div>
                <div class="card-body p-0">
                  <div class="table-responsive">
                    <table id="tablaCierresDeBalance" class="table table-striped table-hover" style="width: 100%;">
                        <thead class="bg-dark">
                        <tr>
                            <th style="color: white;">NÚMERO</th>
                            <th style="color: white;">ENTIDAD</th>
                            <th style="color: white;">FECHA CIERRE</th>
                            <th style="color: white;">DESCRIPCION</th>
                            <th style="color: white;">USUARIO</th>
                            <th style="color: white;">ESTADO</th>
                        </tr>
                        </thead>
                    </table>
                  </div>
                </div>
                <div class="card-footer">
                  <div class="row">
                    <div class="col-md-6">
                      <!-- <p class="text-muted">
                        Empresa •   • 
                        Cierres
                      </p> -->
                    </div>
                    <div class="col-md-6 text-right">
                      <!-- <small class="text-muted">
                        Balance:  Generado:
                      </small> -->
                    </div>
                  </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- MODAL PARA EL REGISTRO DE CIERRE DE BALANCE GENERAL  -->

<div class="modal fade show" id="modalRegistroCierreDeBalance" style="backgroundColor: rgba(0,0,0,0.4)" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" style="max-width: 1000px">
    <div class="modal-content">
            <div class="modal-header bg-success">
                <div class="d-flex justify-content-between align-items-center w-100">
                    <div class="col-md-8">
                        <h7 class="modal-title text-white">
                            <i class="mr-2">📋</i>
                            CIERRE DE CUENTAS DE BALANCE:
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
							<form id="formularioCierreDeBalance">
									<!-- CAMPOS OCULTOS -->
									<input type="hidden" class="form-control" id="id_entidad_registro" name="id_entidad_registro">
                  
									<div class="card card-outline card-success">
										<div class="card-header">
											<h6 class="card-title">
												📘 CIERRE DE CUENTAS DE BALANCE
											</h6>
										</div>

										<div class="card-body">
											<!-- FECHA DE CIERRE -->
											<div class="row">
												<div class="col-12 col-md-6">
													<div class="form-group">
														<label>
															<i class="text-danger">*</i>
															<strong>Fecha de cierre de gestión:</strong>
														</label>
														<input
															id="fechaCierreBalance"
															name="fechaCierreBalance"
															type="date"
															class="form-control"
														/>
													</div>
												</div>
												<div class="col-12 col-md-6 text-right">
													<br>
													<!-- <br> -->
													<button
														type="button"
														class="btn btn-success"
														onclick="cerrarCuentaDeBalance();"
													>
														⏳ Procesar Cierre
													</button>
												</div>
											</div>

											<!-- AUXILIARES -->
											<!-- <div class="row" id="auxiliares_cuenta" style="display:none;">
												<div class="col-12">
													<div class="form-group">
														<label>
															<i class="text-danger">*</i>
															<strong>AUXILIARES DE LA CUENTA:</strong>
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
																	🕵️
																</button>
															</div>
														</div>
													</div>
												</div>
											</div> -->

											<!-- ASIENTOS -->
											<div class="row">
												<div class="col-12 col-md-6">
													<label><strong>ASIENTO DE CIERRE</strong></label>
													<div class="form-group">
														<label>
															<i class="text-danger">*</i>
															<strong>Referencia:</strong>
														</label>
														<textarea 
															class="form-control"
															rows="3"
															placeholder="Por cierre transitorio de Cuentas de Balance."
                              id="referenciaCierre"
                              name="referenciaCierre"
														></textarea>
													</div>
												</div>

												<div class="col-12 col-md-6">
													<label><strong>ASIENTO DE REAPERTURA</strong></label>
													<div class="form-group">
														<label>
															<i class="text-danger">*</i>
															<strong>Referencia:</strong>
														</label>
														<textarea 
															class="form-control"
															rows="3"
															placeholder="Por reapertura de Cuentas de Balance."
                              id="referenciaApertura"
                              name="referenciaApertura"
														></textarea>
													</div>
												</div>
											</div>

											<!-- GLOSAS -->
											<div class="row">
												<div class="col-12 col-md-6">
													<div class="form-group">
														<label>
															<i class="text-danger">*</i>
															<strong>Glosa:</strong>
														</label>
														<textarea 
															class="form-control"
															rows="3"
															placeholder="Para cerrar transitoriamente cuentas de balance..."
                              id="glosaCierre"
                              name="glosaCierre"
														></textarea>
													</div>
												</div>

												<div class="col-12 col-md-6">
													<div class="form-group">
														<label>
															<i class="text-danger">*</i>
															<strong>Glosa:</strong>
														</label>
														<textarea 
															class="form-control"
															rows="3"
															placeholder="Por reapertura de las Cuentas de Balance."
                              id="glosaApertura"
                              name="glosaApertura"
														></textarea>
													</div>
												</div>
											</div>

											<!-- BOTÓN -->
											<!-- <div class="row">
												<div class="col-12 text-right">
													<button
														type="button"
														class="btn btn-success"
														onclick="cerrarCuentaDeBalance();"
													>
														⏳ Procesar Cierre
													</button>
												</div>
											</div> -->
										</div>
									</div>
                  <!-- TABLA BALANCE GENERAL-->

                <div class="card">
                  <div class="card-header bg-gradient-secondary">
                    <h3 class="card-title text-white">
                      <i class="mr-2">⚖️</i>
                      BALANCE GENERAL
                    </h3>
                    <div class="card-tools">
                      <!-- <span class="badge badge-light">
                        Período: {dateFrom} al {dateTo}
                      </span> -->
                    </div>
                  </div>
                  <div class="card-body p-0">
                    <div class="table-responsive">
                      <table id="tablaBalanceGeneralCierre" class="table table-striped table-hover" style="width: 100%;">
                          <thead class="bg-dark">
                          <tr>
                              <th style="color: white;">CÓDIGO</th>
                              <th style="color: white;">DESCRIPCIÓN</th>
                              <th style="color: white; text-align: right;">-</th>
                              <th style="color: white; text-align: right;">-</th>
                              <th style="color: white; text-align: right;">IMPORTE</th>
                          </tr>
                          </thead>
                          <tfoot>
                                <tr class="bg-secondary">
                                    <td colSpan="4" style="color: white; font-weight: bold;">
                                    TOTAL ACTIVO :
                                    </td>
                                    <td style="text-align: right; color: white; font-weight: bold;" class="txtTotalImporteActivo" >0.00</td>
                                    </td>
                                </tr>
                                <tr class="bg-secondary">
                                    <td colSpan="4" style="color: white; font-weight: bold;">
                                    TOTAL PASIVO Y PATRIMONIO  :
                                    </td>
                                    <td style="text-align: right; color: white; font-weight: bold;" class="txtTotalImportePasivoPatrimonio" >0.00</td>
                                    </td>
                                </tr>
                                <tr class="bg-dark">
                                    <td colSpan="4" style="color: white; font-weight: bold;">
                                    TOTAL CUENTAS DE ORDEN DEUDORAS :
                                    </td>
                                    <td style="text-align: right; color: white; font-weight: bold;" class="txtTotalImporteCuentasOrdenDeudoras" >0.00</td>
                                    </td>
                                </tr>
                                <tr class="bg-dark">
                                    <td colSpan="4" style="color: white; font-weight: bold;">
                                    TOTAL CUENTAS DE ORDEN ACREEDORAS :
                                    </td>
                                    <td style="text-align: right; color: white; font-weight: bold;" class="txtTotalImporteCuentasOrdenAcreedoras" >0.00</td>
                                    </td>
                                </tr>
                          </tfoot>
                      </table>
                    </div>
                  </div>
                  <!-- <div class="card-footer">
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
                  </div> -->
                </div>


                </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal" >
                  <i class="mr-1">❌</i>
                  Cerrar
              </button>
              <!-- <button type="button" class="btn btn-info" onclick="guardarRegistroCuenta();">
                  <i class="mr-1">💾</i>
                  Guardar Registro
              </button> -->
            </div>
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
