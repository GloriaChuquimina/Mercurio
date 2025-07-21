<script src="<?php echo  base_url() ?>scriptjs/jquery.js"></script>
<script src="<?php echo  base_url() ?>scriptjs/contabilidad/librodiario.js"></script>
<div class="wapper">
    <section class="content">
        <div class="container-fluid">
             <!-- SECCION ENTIDAD -->
            <div class="card card-warning card-outline">
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
                <div class="card-body">
                    <input class="form-control" id="id_entidad" name="id_entidad">
                    <input class="form-control" id="id_cuenta" name="id_cuenta">
                    <input class="form-control" id="id_cuenta_seleccionadas" name="id_cuenta_seleccionadas">
                    <div class="row">
                      <div class="col-md-5">
                        <div class="form-group">
                          <label>
                            <i class="text-danger">*</i>
                            <strong> CUENTA CONTABLE:</strong>
                          </label>
                          <!-- <select
                            id="cuentaContable"
                            name="cuentaContable"
                            class="form-control"
                          >
                          </select> -->
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
                              <!-- <input type='hidden' name='idCuenta' id='idCuenta' > -->
                              <div class="input-group-append">
                                  <button
                                  type="button"
                                  class="btn btn-success"
                                  onclick="agregarCuenta();"
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
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label>
                            <strong>OPCIONES:</strong>
                          </label>
                          <div class="form-check">
                            <input
                              class="form-check-input"
                              type="checkbox"
                              id="soloConMovimientos"                              
                            />
                            <label class="form-check-label" htmlFor="soloConMovimientos">
                              Solo cuentas con movimientos
                            </label>
                          </div>
                        </div>
                      </div>
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
                    </div>
                    <div class="row">
                      <div class="col-md-12 text-right">
                        <button class = "btn btn-primary mr-2"
                              onClick = "consultar()">
                          <i class="mr-1">🔍</i> Consultar
                        </button>
                        <button class="btn btn-success mr-2"
                                onClick="generarReporteLibroDiario()">
                          <i class="mr-1">📄</i> Exportar PDF
                        </button>
                        <button class="btn btn-info">
                          <i class="mr-1">📊</i> Exportar Excel
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
            <!-- TABLA LIBRO DIARIO-->
            <div class="card" id="tablaLibroDiario" style="display: none">
                <div class="card-header bg-gradient-secondary">
                  <h3 class="card-title text-white">
                    <i class="mr-2">📖</i>
                    LIBRO DIARIO - DETALLE DE MOVIMIENTOS
                  </h3>
                  <div class="card-tools">
                    <!-- <span class="badge badge-light">
                      NROMOVIMIENTOS
                    </span> -->
                  </div>
                </div>
                <div class="card-body p-0">
                  <div class="table-responsive">
                    <table id="tablaLibroDiario" class="table table-striped table-hover" style="width: 100%;">
                      <thead class="bg-dark text-white">
                          <tr>
                            <th rowspan="2" style="width: 150px;">FECHA<br>CÓDIGO</th>
                            <th rowspan="2" style="width: 350px;">DETALLE</th>
                            <th colspan="2" style="text-align: center;">BOLIVIANOS</th>
                          </tr>
                          <tr>
                            <th style="width: 120px; text-align: center;">DEBE</th>
                            <th style="width: 120px; text-align: center;">HABER</th>
                          </tr>
                      </thead>
                      <tbody id="tbodyLibroDiario">
                     </tbody>
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
                    </table>
                  </div>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-6">
                        <p class="text-muted">
                            Empresa: • Período:• Movimientos:
                        </p>
                        </div>
                        <div class="col-md-6 text-right">
                        <small class="text-muted">Última actualización:</small>
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
    //   cargarTablaComprobantesEntidades();
    });
</script>  
