<script src="<?php echo  base_url() ?>scriptjs/jquery.js"></script>
<script src="<?php echo  base_url() ?>scriptjs/contabilidad/cierrederesultados.js"></script>
<div class="wapper">
    <section class="content">
        <div class="container-fluid">
             <!-- SECCION ENTIDAD -->
            <div class="card card-primary card-outline" id="cardEntidad">
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
            <div class="card" style="background-color: #aecaf0ff; border-left: 4px solid #0d56c4ff;" id="entidadSeleccionada" style="display: none">
                <input type ="hidden" class="form-control" id="id_entidad" name="id_entidad">
                <div class="card-body p-3">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center">
                                    <div
                                    style="
                                        width: 48px,
                                        height: 48px,
                                        border-radius: 50%,
                                        background-color: #0d56c4ff,
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
                        <!-- <span class="badge badge-primary">XXX cuentas disponibles</span> -->
                          <button class = "btn btn-primary mr-2"
                                onClick = "procedimientoCierreCuentasDeResultados()">
                            <i class="mr-1">⏳</i> Procesar Cierre de Resultados
                          </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- FILTROS Y OPCIONES -->
            <!-- <div class="card card-primary card-outline">
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
                    <div class="col-sm-3 col-md-auto">
                        <div class="form-group">
                          <label>
                            <i class="text-danger">*</i>
                            <strong>Fecha hasta la que se considera la gestión:</strong>
                          </label>
                          <input
                            id="fechaCierreResultado"
                            name="fechaCierreResultado"
                            placeHolder="Fecha Cierre"
                            type="date"
                            class="form-control"
                          />
                        </div> 
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                          <label>
                            <i class="text-danger">*</i>
                            <strong> Cuenta contable que se considera para resgistrar los Resultados de la Gestión:</strong>
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
                        </div>
                    </div>  

                    <div class="col-md-3 text-right">
                        <br>
                        <button class = "btn btn-primary mr-2"
                              onClick = "procedimientoCierreCuentasDeResultados()">
                          <i class="mr-1">⏳</i> Procesar Cierre de Resultados
                        </button>
                    </div>  

                  </div>
                </div>                
            </div> -->
            <!-- TABLA CIERRE DE RESULTADOS -->
            <div class="card">
                <div class="card-header bg-gradient-secondary">
                  <h3 class="card-title text-white">
                    <i class="mr-2">📉 - 📈</i>
                    CIERRES DE RESULTADOS
                  </h3>
                  <div class="card-tools">
                    <!-- <span class="badge badge-light">
                      Período: {dateFrom} al {dateTo}
                    </span> -->
                  </div>
                </div>
                <div class="card-body p-0">
                  <div class="table-responsive">
                    <table id="tablaCierreResultados" class="table table-striped table-hover" style="width: 100%;">
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
                      <p class="text-muted">
                        Empresa •  • 
                        cuentas de Ingreso y Gasto
                      </p>
                    </div>
                    <div class="col-md-6 text-right">
                      <small class="text-muted">
                        Estado Generado
                      </small>
                    </div>
                  </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal fade show" id="modalRegistroCierreDeResultados" style="backgroundColor: rgba(0,0,0,0.4)" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" style="max-width: 1000px">
    <div class="modal-content">
            <div class="modal-header bg-primary">
                <div class="d-flex justify-content-between align-items-center w-100">
                    <div class="col-md-8">
                        <h7 class="modal-title text-white">
                            <i class="mr-2">📋</i>
                            CIERRE DE RESULTADOS
                            <span style="color:white;"><label id="nombreEntidad">...</label></span>
                        </h7>
                    </div>
                    <div class="col-md-4 text-right">
                        <!-- <span class="badge badge-warning">Tipo Cambio:<label id="tipoCambio">...</label></span> -->
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                        <span>&times;</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-body">
              <form id="formularioCierreResultados">
                  <input  type="hidden" class="form-control" id="id_entidad_registro" name="id_entidad_registro">
                  <input  type="hidden" class="form-control" id="id_cuenta" name="id_cuenta">
                  <div class="card card-outline card-primary">
                    <div class="card-header">
                      <h7 class="card-title">
                          <i class="mr-2">✏️</i>
                          CIERRE DE CUENTA DE RESULTADOS
                      </h7>
                    </div>
                    <div class="card-body">
                      <div class="row align-items-end">
                        <!-- Fecha -->
                        <div class="col-md-3 col-sm-6">
                          <div class="form-group mb-0">
                            <label>
                              <i class="text-danger">*</i>
                              <strong>Fecha hasta la que se considera la gestión:</strong>
                            </label>
                            <input
                              id="fechaCierreResultado"
                              name="fechaCierreResultado"
                              placeHolder="Fecha Cierre"
                              type="date"
                              class="form-control"
                            />
                          </div>
                        </div>

                        <!-- Cuenta contable -->
                        <div class="col-md-6 col-sm-12">
                          <div class="form-group mb-0">
                            <label>
                              <i class="text-danger">*</i>
                              <strong>Cuenta contable que se considera para registrar los Resultados de la Gestión:</strong>
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
                              <input type="hidden" name="idCuenta" id="idCuenta" />
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

                        <!-- Botón -->
                        <div class="col-md-3 col-sm-6 text-center">
                          <button
                            type="button"
                            class="btn btn-warning mt-4"
                            onclick="cerrarCuentaDeResultados();"
                          >
                            <i>⏳</i> Procesar Cierre
                          </button>
                        </div>
                      </div>

                      <div class="row" id="auxiliares_cuenta" style="display:none;">
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
                                      <datalist id='listaAuxiliaresDeCuenta'></datalist>
                                      <!-- <input type='hidden' name='idCuenta' id='idCuenta' > -->
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
                      <!-- <div class="row">
                          <div class="col-md-9">
                              <div class="form-group">
                                  <label>
                                  <i class="text-danger">*</i>
                                  <strong> DESCRIPCIÓN:</strong>
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
                          <div class="col-md-3">
                            <br>
                            <br>
                            <br>
                              <div class="input-group-append">
                                  <button
                                  type="button"
                                  class="btn btn-warning"
                                  onclick="cerrarCuentaDeResultados();"
                                  >
                                  <i>⏳</i>Procesar Cierre
                                  </button>
                              </div>
                          </div>                          
                      </div> -->
                    </div>  
                  </div>
                  <!-- TABLA ESTADO DE CUENTA-->
                  <div class="card" id="tablaEstadoDeResultados" >
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
                          <!-- <th><input type='checkbox' value='0' name = 'opcionSeleccionar' id='opcionSeleccionar'> &nbsp;</th> -->
                          <th style="color: white; text-align: center;">SELECCIONAR</th>
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
