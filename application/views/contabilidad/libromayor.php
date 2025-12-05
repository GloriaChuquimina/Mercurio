<script src="<?php echo  base_url() ?>scriptjs/jquery.js"></script>
<script src="<?php echo  base_url() ?>scriptjs/contabilidad/libromayor.js"></script>



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- DataTables JS y CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<!-- Responsive y Scroll si necesitas -->
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/scroller/2.2.0/js/dataTables.scroller.min.js"></script>


<div class="wapper">
    <section class="content">
        <div class="container-fluid">
             <!-- SECCION ENTIDAD -->
            <div class="card card-warning card-outline" id="cardEntidad">
                <div class="card-header">
                    <h3 class="card-title">
<<<<<<< HEAD
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
=======
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
>>>>>>> 86e86a998fd13133562672039854ed148e2211bf
                    </div>
                </div>
            </div>
            <!-- SECCION ENTIDAD SELECCIONADA -->
<<<<<<< HEAD
            <div class="card" style="background-color: #fff3cd; border-left: 4px solid #ffc107; display: none;" id="entidadSeleccionada">
=======
            <div class="card" style="background-color: #fff3cd; border-left: 4px solid #ffc107;" id="entidadSeleccionada" style="display: none">
>>>>>>> 86e86a998fd13133562672039854ed148e2211bf
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
<<<<<<< HEAD
                          <!-- <span class="badge badge-warning">XXX cuentas disponibles</span> -->
=======
                        <!-- <span class="badge badge-warning">XXX cuentas disponibles</span> -->
>>>>>>> 86e86a998fd13133562672039854ed148e2211bf
                        </div>
                    </div>
                </div>
            </div>
            <!-- FILTROS DE BUSQUEDA -->
            <div class="card card-primary card-outline" id ="filtrosConsulta" style="display: none">
                <div class="card-header">
                  <h3 class="card-title">
                    <i class="mr-2">🔍</i>
                    Filtros de Consulta
                  </h3>
                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" >
                      <i>🔼🔽</i>
                    </button>
                  </div>
                </div>
<<<<<<< HEAD
=======


>>>>>>> 86e86a998fd13133562672039854ed148e2211bf
                <div class="card-body">
									<input type="hidden" class="form-control" id="id_entidad" name="id_entidad">
									<input type="hidden" class="form-control" id="id_cuenta" name="id_cuenta">
									<input type="hidden" class="form-control" id="id_cuenta_seleccionadas" name="id_cuenta_seleccionadas">
									
									<!-- FILTROS -->
									<div class="row">                      
										<div class="col-md-2">
											<div class="form-group">
												<label><strong>FECHA DESDE:</strong></label>
												<input id="fechaDesde" name="fechaDesde" type="date" class="form-control"/>
											</div>
										</div>
										<div class="col-md-2">
											<div class="form-group">
												<label><strong>FECHA HASTA:</strong></label>
												<input id="fechaHasta" name="fechaHasta" type="date" class="form-control"/>
											</div>
										</div>
										<div class="col-md-2">
											<div class="form-group">
												<label for="tipo_moneda"><strong>MONEDA:</strong></label>
												<select class="form-control" id="tipo_moneda" name="tipo_moneda"></select>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label><i class="text-danger">*</i><strong> CUENTA CONTABLE:</strong></label>
												<div class="input-group">
													<input type="text" class="form-control" placeholder="Buscar cuenta..." list="listaCuentas" id="txtCuenta" name="txtCuenta"/>
													<datalist id='listaCuentas'></datalist>
													<div class="input-group-append">
														<button type="button" class="btn btn-success" onclick="añadirCuenta();">➕</button>
														<button type="button" class="btn btn-warning" onclick="listaCuentasBusqueda();">🔍</button>
													</div>
												</div>
											</div>
										</div>
										<div class="col-md-2">
											<div class="form-check m-0">
												<label><strong>OPCIONES:</strong></label>
												<div class="form-check">
													<input class="form-check-input" type="checkbox" id="sinMovimientos" name="sinMovimientos"/>
													<label class="form-check-label" for="sinMovimientos">
														Incluir cuentas sin movimiento
													</label>
												</div>
											</div>
										</div> 
									</div>

									<!-- OPCIONES Y CUENTA -->
									<!-- <div class="row">
									</div> -->

									<!-- BOTONES EN FILA SEPARADA -->
									<div class="row mt-3">
										<div class="col-md-12 text-right">
											<button class="btn btn-primary mr-2" onClick="consultar()">
												🔍 Consultar
											</button>
											<button class="btn btn-success mr-2" onClick="generarReporteLibroMayor()">
												📄 Exportar PDF
											</button>
										</div>
									</div>
								</div>

								
								

            </div>
            <!-- CUENTA SELECCIONADA -->
<<<<<<< HEAD
            <div class="card" style="background-color: #f0f8ff; border-left: 4px solid #007bff; display: none;" id="cuentaSeleccionada">
=======
            <div class="card" style="background-color: #f0f8ff; border-left: 4px solid #007bff" id="cuentaSeleccionada" style="display: none">
>>>>>>> 86e86a998fd13133562672039854ed148e2211bf
                <div class="card-body p-3">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="d-flex align-items-center">
                                    <div
                                    style="
<<<<<<< HEAD
                                        width: 48px;
                                        height: 48px;
                                        border-radius: 50%;
                                        background-color: #ffc107;
                                        display: flex;
                                        align-items: center;
                                        justify-content: center;
                                        color: white;
                                        font-weight: bold;
                                        font-size: 18px;
=======
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
>>>>>>> 86e86a998fd13133562672039854ed148e2211bf
                                    "
                                    >
                                    </div>
                                    <div class="ml-3">
<<<<<<< HEAD
                                      <h7 class="mb-0">CUENTA(s):</h7>
                                      <h8 class="mb-0" style="color: #007bff; font-weight: bold;" id="cuentas" name="cuentas">   
                                     </h8>
=======
                                    <h7 class="mb-0">CUENTA(s):</h7>
                                    <h8 class="mb-0" style="color: #007bff; font-weight: bold;" id="cuentas" name="cuentas">
                                        
                                    </h8>
>>>>>>> 86e86a998fd13133562672039854ed148e2211bf
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- TABLA LIBRO MAYOR -->
            <div class="card" id="tablaLibroMayor" style="display: none">
                <div class="card-header bg-gradient-secondary">
                  <h3 class="card-title text-white">
                    <i class="mr-2">📖</i>
                    LIBRO MAYOR - DETALLE DE MOVIMIENTOS
                  </h3>
                  <div class="card-tools">
                    <!-- <span class="badge badge-light">
                      NROMOVIMIENTOS
                    </span> -->
                  </div>
                </div>
                <div class="card-body p-0">
                  <!-- <div class="table-responsive"> -->
                    <!-- <table class="table table-striped table-hover" id="tbl_libroMayor" name ="tbl_libroMayor"> -->
                      <!--<tr class="bg-primary"> 
                          <td colSpan="4" style="color: white; fontWeight: bold">
                            TOTALES
                          </td>
                          <td style="text-align:right;color: white;fontWeight:bold" >
                            0000
                          </td>
                          <td style="text-align: right; color: white; fontWeight: bold">
                            0000
                          </td>
                          <td style="text-align: right; color: white; fontWeight: bold" >
                            0000
                          </td>
                          <td></td>
                        </tr> -->
                    <!-- </table> -->
                     <!-- <div id="contenedor_libroMayor" class="table-responsive" style="height: 320px;">
                     </div> -->
                  <!-- </div> -->
                  <!-- INICIO-->
<<<<<<< HEAD
                  <div class="table-responsive" style="overflow-x: auto; max-width: 100%;">
                    <table id="tablaDatosLibroMayor" class="table table-striped table-hover" style="width: 100%; min-width: 1200px;">
                      <thead class="bg-dark text-white">
                          <tr>
                            <th rowspan="2" style="min-width: 120px;">FECHA</th>
                            <th rowspan="2" style="min-width: 120px;">COMPROBANTE</th>
                            <th rowspan="2" style="min-width: 200px;">TIPO</th>
                            <th rowspan="2" style="min-width: 300px;">DESCRIPCIÓN(GLOSA)</th>
=======
                  <div class="table-responsive" style="overflow-x:auto;">
                    <table id="tablaDatosLibroMayor" class="table table-striped table-hover " style="width: 100%;">
                      <thead class="bg-dark text-white">
                          <tr>
                            <th rowspan="2" style="width: 150px;">FECHA</th>
                            <th rowspan="2" style="width: 150px;">COMPROBANTE</th>
                            <th rowspan="2" style="width: 350px;">TIPO</th>
                            <th rowspan="2" style="width: 350px;">DESCRIPCIÓN(GLOSA)</th>
>>>>>>> 86e86a998fd13133562672039854ed148e2211bf
                            <th colspan="2" style="text-align: center;">MOVIMIENTOS</th>
                            <th colspan="2" style="text-align: center;">SALDOS</th>
                          </tr>
                          <tr>
<<<<<<< HEAD
                            <th style="min-width: 100px; text-align: center;">DEBE</th>
                            <th style="min-width: 100px; text-align: center;">HABER</th>
                            <th style="min-width: 100px; text-align: center;">DEUDOR</th>
                            <th style="min-width: 100px; text-align: center;">ACREEDOR</th>
=======
                            <th style="width: 120px; text-align: center;">DEBE</th>
                            <th style="width: 120px; text-align: center;">HABER</th>
                            <th style="width: 120px; text-align: center;">DEUDOR</th>
                            <th style="width: 120px; text-align: center;">ACREEDOR</th>
>>>>>>> 86e86a998fd13133562672039854ed148e2211bf
                          </tr>
                      </thead>
                      <tbody id="tbodyLibroMayor">
                      </tbody>
                      <tfoot>
                          <tr class="bg-primary">
<<<<<<< HEAD
                              <td colspan="4" style="color: white; font-weight: bold;">
=======
                              <td colSpan="4" style="color: white; font-weight: bold;">
>>>>>>> 86e86a998fd13133562672039854ed148e2211bf
                              TOTALES LIBRO MAYOR:
                              </td>
                                  <!-- <td style="text-align: right; color: white; font-weight: bold;" id="txtTotalImporteDebe">0.00</td> -->
                                  <td style="text-align: right; color: white; font-weight: bold;" class="txtTotalImporteDebe">0.00</td>
                                  <td style="text-align: right; color: white; font-weight: bold;" class="txtTotalImporteHaber">0.00</td>
                                  <td style="text-align: right; color: white; font-weight: bold;" class="txtTotalImporteDeudor">0.00</td>
                                  <td style="text-align: right; color: white; font-weight: bold;" class="txtTotalImporteAcreedor">0.00</td>
<<<<<<< HEAD
=======
                              </td>
>>>>>>> 86e86a998fd13133562672039854ed148e2211bf
                          </tr>
                      </tfoot>
                    </table>
                  </div>

                  <!--FIN -->

                  
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-6">
                        <!-- <p class="text-muted">
                            Empresa: • Período:• Movimientos:
                        </p> -->
                        </div>
                        <div class="col-md-6 text-right">
<<<<<<< HEAD
                          <small class="text-muted">Última actualización:</small>
=======
                        <small class="text-muted">Última actualización:</small>
>>>>>>> 86e86a998fd13133562672039854ed148e2211bf
                        </div>
                    </div>
                </div>
            </div>
            <!-- MENSAJE CUANDO NO HAY SELECCIONES-->
<<<<<<< HEAD
            <div class="card" id="mensajeSeleccion" style="display: none;">
=======
             <div class="card" id="mensajeSeleccion" style="display: none;">
>>>>>>> 86e86a998fd13133562672039854ed148e2211bf
                <div class="card-body text-center p-5">
                  <div style="font-size: 64px; margin-bottom: 20px">📖</div>
                  <h4 class="text-muted">Libro Mayor</h4>
                  <p class="text-muted">
                    <!-- {!selectedEntity
                      ? "Seleccione una empresa para comenzar"
                      : "Seleccione una cuenta contable para ver los movimientos"} -->
                  </p>
                </div>
<<<<<<< HEAD
            </div> 
=======
              </div> 
>>>>>>> 86e86a998fd13133562672039854ed148e2211bf
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
<<<<<<< HEAD
      // cargarCuentasLista();
=======
      cargarCuentasLista();
>>>>>>> 86e86a998fd13133562672039854ed148e2211bf
    //   cargarTablaComprobantesEntidades();
    });
</script>  
