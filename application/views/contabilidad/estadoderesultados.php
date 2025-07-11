<script src="<?php echo  base_url() ?>scriptjs/jquery.js"></script>
<script src="<?php echo  base_url() ?>scriptjs/contabilidad/estadoderesultados.js"></script>
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
                    <input class="form-control" id="id_entidad" name="id_entidad">
                    <input class="form-control" id="id_cuenta" name="id_cuenta">
                    <input class="form-control" id="id_cuenta_seleccionadas" name="id_cuenta_seleccionadas">
                    <div class="row">
                      <div class="col-md-3">
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
                      <div class="col-md-3">
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
                      <div class="col-md-3">
                        <div class="form-group">
                          <label>
                            <strong>CUENTAS:</strong>
                          </label>
                          <!-- <select
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
                    </div>
                    <div class="row">
                      <div class="col-md-12 text-right">
                        <button class="btn btn-primary mr-2"
                             onClick = "cargarDatosSumasySaldos()">
                          <i class="mr-1">🔍</i> Generar Balance
                        </button>
                        <button class="btn btn-success mr-2"
                              onClick="ReporteSumasySaldosPDF()">
                          <i class="mr-1">📄</i> Exportar PDF
                        </button>
                        <button class="btn btn-info mr-2">
                          <i class="mr-1">📊</i> Exportar Excel
                        </button>
                        <button class="btn btn-warning">
                          <i class="mr-1">🖨️</i> Imprimir
                        </button>
                      </div>
                    </div>
                  </div>              
            </div>
        </div>
    </section>
</div>
<script type="text/javascript">
    $(document).ready(function(){
      var enlace  = "<?php echo base_url();?>";    
      baseurl(enlace);
      cargarCombos();
    });
</script> 