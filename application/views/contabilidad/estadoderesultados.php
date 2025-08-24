<script src="<?php echo  base_url() ?>scriptjs/jquery.js"></script>
<script src="<?php echo  base_url() ?>scriptjs/contabilidad/estadoderesultados.js"></script>
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
            <div class="card card-primary card-outline " id ="filtrosConsulta" style="display: none">
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
                    <div class="row">
                      <div class="col-md-2">
                        <div class="form-group">
                          <label>
                            <strong>FECHA DESDE:</strong>
                          </label>
                          <input
                            id="fechaDesde"
                            name="fechaDesde"
                            placeHolder="Fecha Desde"
                            type="date"
                            class="form-control"
                          />
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-group">
                          <label>
                            <strong>FECHA HASTA:</strong>
                          </label>
                          <input
                            id="fechaHasta"
                            name="fechaHasta"
                            placeHolder="Fecha Hasta"
                            type="date"
                            class="form-control"
                          />
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-group">
                            <div class="d-flex align-items-end">
                                <div style="flex-grow: 1; margin-right: 10px;">
                                    <label for="tipo_moneda">
                                        <strong>MONEDA:</strong>
                                    </label>
                                    <select
                                        class="form-control"
                                        id="tipo_moneda"
                                        name="tipo_moneda"
                                        value="selectedEntity"
                                    >
                                    </select>
                                </div>
                            </div>
                        </div>                        
                      </div>
                      <div class="col-md-1">
                          <div class="form-group">
                              <label>NIVEL:</label>
                              <input type="number"  class="form-control is-warning" name="nivel" id="nivel" class="form-control" placeholder="Ej: 1">
                          </div>
                      </div>

                      <div class="col-md-2">
                        <div class="form-group">
                          <label>
                            <strong>OPCIONES:</strong>
                          </label>
                          <br>
                          <!-- <br> -->
                          <div class="form-check">
                            <input
                              class="form-check-input"
                              type="checkbox"
                              id="saldoCero"                              
                            />
                            <label class="form-check-label" htmlFor="saldoCero">
                              Incluir cuentas con saldo cero
                            </label>
                          </div>
                        </div>
                      </div> 

                      <div class="col-md-3">
                        <!-- <div class="form-group">
                          <label>
                            <strong>CUENTAS:</strong>
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
                              <div class="input-group-append">
                                  <button
                                  type="button"
                                  class="btn btn-success"
                                  onclick="añadirCuenta();"
                                  >
                                  <i>➕</i>
                                  </button>
                                  <button
                                  type="button"
                                  class="btn btn-warning"
                                  onclick="listaCuentasBusqueda();"
                                  >
                                  <i>🔍</i>
                                  </button>
                              </div>
                          </div>
                        </div> -->
                      </div> 
                      
                      
                      <div class="col-md-12">
                        <br>
                         <div class="col-md-12 text-right">
                          <button class="btn btn-primary mr-2"
                              onClick = "consultar()">
                            <i class="mr-1">🔍</i> Consultar 
                          </button>
                          <button class="btn btn-success mr-2"
                                onClick="generarReporteEstadoDeResultados()">
                            <i class="mr-1">📄</i> Exportar PDF
                          </button>
                        </div>
                      </div>
                    </div>
                    <!-- <div class="row">
                      <div class="col-md-12 text-right">
                        <button class="btn btn-primary mr-2"
                             onClick = "consultar()">
                          <i class="mr-1">🔍</i> Consultar 
                        </button>
                        <button class="btn btn-success mr-2"
                              onClick="generarReporteEstadoDeResultados()">
                          <i class="mr-1">📄</i> Exportar PDF
                        </button>
                      </div>
                    </div> -->
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
             <!-- TABLA ESTADO DE CUENTA-->
						<div class="card" id="tablaEstadoDeResultados" style="display: none">
                <div class="card-header bg-gradient-secondary">
                  <h3 class="card-title text-white">
                    <i class="mr-2">📖</i>
                    ESTADO DE RESULTADOS
                  </h3>
                  <div class="card-tools">
                    <!-- <span class="badge badge-light">
                      NROMOVIMIENTOS
                    </span> -->
                  </div>
                </div>
                <div class="card-body p-0">
                  <!-- tabla cuentas de ingreso  -->
                  <div class="table-responsive" style="overflow-x:auto;">
                    <table id="tablaDatosCuentasIngreso" class="table table-striped table-hover " style="width: 100%;">
                      <thead class="bg-dark text-white">
                          <tr>
                            <th colspan ="4" style="text-align: center;">CUENTAS DE INGRESO</th>
                          </tr>
                          <tr>
                            <th style="text-align: center;">CÓDIGO</th>
                            <th style="text-align: center;">NIVEL</th>
                            <th style="text-align: center;">NOMBRE</th>
                            <th style="text-align: center;">BOLIVIANOS</th>
                          </tr>
                          
                      </thead>
                      <!-- <tbody id="tbodyEstadoCuenta">
                      </tbody> -->
                      <tfoot>
                          <tr class="bg-primary">
                              <td colSpan="3" style="color: white; font-weight: bold;">
                              TOTAL CUENTAS DE INGRESO:
                              </td>
                                  <td style="text-align: right; color: white; font-weight: bold;" class="txtTotalImporteIngreso">0.00</td>
                              </td>
                          </tr>
                          <tr class="bg-secondary">
                              <td colSpan="3" style="color: white; font-weight: bold;">
                              RESULTADO DEL EJERCICIO:
                              </td>
                                  <td style="text-align: right; color: white; font-weight: bold;" class="txtTotalImporteResultado1">0.00</td>
                              </td>
                          </tr>
                      </tfoot>
                    </table>
                  </div>
                  <!-- tabla cuentas de egreso -->
                  <div class="table-responsive" style="overflow-x:auto;">
                    <table id="tablaDatosCuentasEgreso" class="table table-striped table-hover " style="width: 100%;">
                      <thead class="bg-dark text-white">
                          <tr>
                            <th colspan ="4" style="text-align: center;">CUENTAS DE EGRESO</th>
                          </tr>
                          <tr>
                            <th style="text-align: center;">CÓDIGO</th>
                            <th style="text-align: center;">NIVEL</th>
                            <th style="text-align: center;">NOMBRE</th>
                            <th style="text-align: center;">BOLIVIANOS</th>
                          </tr>
                          
                      </thead>
                      <!-- <tbody id="tbodyEstadoCuenta">
                      </tbody> -->
                      <tfoot>
                          <tr class="bg-primary">
                              <td colSpan="3" style="color: white; font-weight: bold;">
                              TOTAL CUENTAS DE EGRESO:
                              </td>
                                  <td style="text-align: right; color: white; font-weight: bold;" class="txtTotalImporteEgreso">0.00</td>
                              </td>
                          </tr>
                          <tr class="bg-secondary">
                              <td colSpan="3" style="color: white; font-weight: bold;">
                              RESULTADO DEL EJERCICIO:
                              </td>
                                  <td style="text-align: right; color: white; font-weight: bold;" class="txtTotalImporteResultado2">0.00</td>
                              </td>
                          </tr>
                      </tfoot>
                    </table>
                  </div>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-6">
                        <p class="text-muted">
                            <!-- Empresa: • Período:• Movimientos: -->
                        </p>
                        </div>
                        <div class="col-md-6 text-right">
                        <small class="text-muted">Última actualización:</small>
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
    });
</script> 
