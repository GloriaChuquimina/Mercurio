<script src="<?php echo  base_url() ?>scriptjs/jquery.js"></script>
<script src="<?php echo  base_url() ?>scriptjs/contabilidad/plandecuentas.js"></script>


<div class="wapper">
    <section class="content">
        <div class="container-fluid" >
            <!-- Entidades -->
            <div class="card card-primary card-outline" id="cardEntidad">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="mr-2">🏢</i>
                  Selección de Entidad
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
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
                        <strong> ENTIDADES:</strong>
                      </label>
                      <select
                        class="form-control"
                        id="entidades"
                        name="entidades"
                        value="selectedEntity"
                      >
                      </select>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- Entidades -->
            <div class="card card-primary">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <!-- Entidad Seleccionada -->
                    <div class="col-md-8">
                      <div class="d-flex align-items-center">
                        <div>
                          <h5 class="mb-0">ENTIDAD:</h5>
                          <h5 id="nombre_entidad" class="mb-0" style="color: #ffffff; font-weight: bold;">...
                          </h5>
                        </div>
                      </div>
                    </div>
                    <!-- Entidad Seleccionada -->
                    <div class="col-md-4 text-right">
                        <button type="button" class="btn btn-success mr-2" onclick="agregarCuentas()">
                            <i class="fas fa-save mr-1"></i> Registro de Cuentas Mayores
                        </button>
                    </div>
                </div>
            </div> 
            <div class="card">
              <div class="card-header bg-gradient-secondary">
                <h3 class="card-title text-white">
                  <i class="mr-2">📒</i>
                  PLAN DE CUENTAS
                </h3>
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse" >
                    <i>🔍</i>
                  </button>
                </div>
              </div>

              <!-- Filter Section -->
              <div class="card-header bg-gradient-light">
                  <div class="row">
                      <div class="col-md-12">
                          <h5 class="mb-3">
                              <i class="fas fa-filter mr-2"></i>
                              Filtros de Búsqueda
                          </h5>
                      </div>
                  </div>
                  
                  <div class="row">
                      <!-- Filtro por Nivel -->
                      <div class="col-md-2">
                          <div class="form-group mb-2">
                              <label class="mb-1"><strong>Nivel:</strong></label>
                              <select class="form-control form-control-sm" id="filtroNivel" name ="filtroNivel">
                              </select>
                          </div>
                      </div>

                      <!-- Filtro por Grupo Principal -->
                      <div class="col-md-3">
                          <div class="form-group mb-2">
                              <label class="mb-1"><strong>Cuentas Mayores:</strong></label>
                              <select class="form-control form-control-sm" id="filtroMayores" name ="filtroMayores">
                              </select>
                          </div>
                      </div>

                      <!-- Filtro por Subgrupo -->
                      <div class="col-md-3">
                          <div class="form-group mb-2">
                              <label class="mb-1"><strong>SubCuentas:</strong></label>
                              <select class="form-control form-control-sm" id="filtroSubCuentas" name="filtroSubCuentas">
  
                              </select>
                          </div>
                      </div>

                      <!-- Filtro por Tipo -->
                      <div class="col-md-2">
                          <div class="form-group mb-2">
                              <label class="mb-1"><strong>Cuentas:</strong></label>
                              <select class="form-control form-control-sm" id="filtroOtrasSubCuentas" name="filtroOtrasSubCuentas">
                                  
                              </select>
                          </div>
                      </div>

                      <!-- Búsqueda por texto -->
                      <div class="col-md-2">
                          <div class="form-group mb-2">
                              <label class="mb-1"><strong>Buscar:</strong></label>
                              <input type="text" class="form-control form-control-sm" id="filtroBusqueda" 
                                    placeholder="Código o descripción...">
                          </div>
                      </div>
                  </div>

                  <div class="row">
                      <div class="col-md-12 text-right">
                          <button type="button" class="btn btn-info btn-sm" onclick="buscarTablaPlanDeCuentas()">
                              <i class="fas fa-search mr-1"></i> Buscar
                          </button>
                          <button type="button" class="btn btn-secondary btn-sm" onclick="limpiarFiltros()">
                              <i class="fas fa-eraser mr-1"></i> Limpiar
                          </button>
                      </div>
                  </div>
              </div>

              <div class="card-body p-1">
                <div class="table-responsive">
                  <table id="tablaPlanDeCuentas" class="table table-striped table-hover" style="width: 100%;">
                    <thead class="bg-dark">
                      <tr>
                        <th style="color: white; width: 100px;">OPCIONES</th>
                        <!-- <th style="color: white; width: 50px;">NRO</th> -->
                        <th style="color: white;">CÓDIGO</th>
                        <th style="color: white;">DESCRIPCIÓN</th>
                        <th style="color: white;">TIPO</th>
                        <th style="color: white;">NIVEL</th>
                        <!-- <th style="color: white;">SIGLA</th> -->
                        <th style="color: white;">MONEDA</th>
                        <th style="color: white;">ESTADO</th>
                        <!-- <th style="color: white;">CODIGO_CUENTA</th> -->
                      </tr>
                    </thead>
                  </table>
                </div>
              </div>
              <div class="card-footer">
                <div class="row">
                  <div class="col-md-6">
                    <!-- <p class="text-muted">Mostrando comprobantes</p> -->
                  </div>
                  <div class="col-md-6 text-right">
                    <!-- <div class="btn-group">
                      <button class="btn btn-default btn-sm">
                        <i class="mr-1">⬅️</i> Anterior
                      </button>
                      <button class="btn btn-default btn-sm">
                        Siguiente <i class="ml-1">➡️</i>
                      </button>
                    </div> -->
                  </div>
                </div>
              </div>
            </div>                       
        </div>
    </section>
</div>
<div class="modal fade show" id="modalPlanDeCuentas"  style="backgroundColor: rgba(0,0,0,0.4);" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-lg" style="max-width: 700px !important;">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h7 class="modal-title text-white">
                    <i class="mr-2">📝</i>
                    REGISTRO DE PLAN DE CUENTAS
                </h7>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formularioPlanDeCuentas">
                  <input type="hidden" class="form-control" id="txtAccion" name="txtAccion" />
                  <input type="hidden" class="form-control" id="idCuenta" name="idCuenta" />
                  <input type="hidden" class="form-control" id="nivel" name="nivel" />               
                  <input class="form-control" id="id_entidad" name="id_entidad" />               
                  <div class="card card-outline card-success">
                    <div class="card-header">
                      <h3 class="card-title">
                        <i class="mr-2">📊</i>
                        Datos de la Cuenta Mayor
                      </h3>
                    </div>
                    <div class="card-body">
                      <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>
                                <i class="text-danger">*</i>
                                <strong> CÓDIGO:</strong>
                                </label>
                                <input
                                class="form-control"
                                type="text"
                                id="txtCodigoCuenta"
                                name="txtCodigoCuenta"
                                placeholder="Ej: 1000"
                                />
                            </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>
                              <i class="text-danger">*</i>
                              <strong> TIPO MONEDA:</strong>
                            </label>
                            <select class="form-control" id="txtTipoMoneda_editar" name="txtTipoMoneda_editar">
                            </select>
                          </div>
                        </div> 
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>
                              <i class="text-danger">*</i>
                              <strong> SIGLA:</strong>
                            </label>
                            <input
                              class="form-control"
                              type="text"
                              id="txtSiglaCuenta"
                              name="txtSiglaCuenta"
                              placeholder="Ej: ACT"
                            />
                          </div>
                        </div>
                        <!-- <div class="col-md-4">
                          <div class="form-group">
                            <label>
                              <i class="text-danger">*</i>
                              <strong> TIPO:</strong>
                            </label>
                            <select class="form-control" id="txtTipoCuenta" name="txtTipoCuenta">
                            </select>
                          </div>
                        </div> -->
                      </div>
                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group">
                            <label>
                              <i class="text-danger">*</i>
                              <strong> NOMBRE DE LA CUENTA:</strong>
                            </label>
                            <textarea
                              class="form-control"
                              id="txtDescripcionCuenta"
                              name="txtDescripcionCuenta"
                              placeholder="Nombre de la cuenta"
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
                  <i class="mr-1"></i><span class="glyphicon glyphicon-remove"></span>  Cerrar
                </button>
                <button type="button" class="btn btn-success" onclick="guardarPlanDeCuentas();">
                  <i class="mr-1">💾</i> Guardar Cambios
                </button>
              </div>
        </div>
    </div>
</div>
<div class="modal fade show" id="modalPlanDeSubCuentas"  style="display: backgroundColor: rgba(0,0,0,0.4)">
    <div class="modal-dialog modal-xl" style="max-width: 1200px;">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h7 class="modal-title text-white">
                  <i class="mr-2">📋</i>
                  REGISTRO DE PLAN DE CUENTAS:
                  <span class="ml-2 font-weight-bold"><label id="nombreCuenta">...</label></span>
                </h7>
                <button type="button" class="close text-white" data-dismiss="modal">
                  <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formularioPlanDeSubCuentas">
                  <input type="hidden" class="form-control" id="txtAccionSubCuenta" name="txtAccionSubCuenta" />
                  <input type="hidden" class="form-control" id="id_cuenta" name="id_cuenta" />
                  <input type="hidden" class="form-control" id="nivel_padre" name="nivel_padre" />
                  <input type="hidden" class="form-control" id="id_padre" name="id_padre" />
                  <input type="hidden" class="form-control" id="ruta" name="ruta" />
                  <input type="hidden" class="form-control" id="codigo_cuenta_padre" name="codigo_cuenta_padre" />
                  <input class="form-control" id="id_entidad_sub" name="id_entidad_sub" />  
                  <div class="card card-outline card-info mb-4">
                    <div class="card-header">
                      <h3 class="card-title">
                        <i class="mr-2">📝</i>
                        Agregar Subcuenta
                      </h3>
                    </div>
                    <div class="card-body">
                      <div class="row">
                        <div class="col-md-2">
                          <div class="form-group">
                            <label>
                              <i class="text-danger">*</i>
                              <strong> CÓDIGO:</strong>
                            </label>
                            <input
                              class="form-control"
                              type="text"
                              id="txtCodigo"
                              name="txtCodigo"
                              placeholder="Ej: 1100"
                            />
                          </div>
                        </div>
                        <div class="col-md-2">
                          <div class="form-group">
                            <label>
                              <i class="text-danger">*</i>
                              <strong> TIPO MONEDA:</strong>
                            </label>
                            <select class="form-control" id="txtTipoMoneda" name="txtTipoMoneda">
                            </select>
                          </div>
                        </div> 
                        <div class="col-md-2">
                          <div class="form-group">
                            <label>
                              <i class="text-danger">*</i>
                              <strong> SIGLA:</strong>
                            </label>
                            <input
                              class="form-control"
                              type="text"
                              id="txtSigla"
                              name="txtSigla"
                              placeholder="Ej: ACT-CTE"
                            />
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label>
                              <i class="text-danger">*</i>
                              <strong> NOMBRE DE LA CUENTA:</strong>
                            </label>
                            <div class="input-group">
                              <input
                                class="form-control"
                                type="text"
                                id="txtDescripcion"
                                name="txtDescripcion"
                                placeholder="Nombre de la subcuenta"
                              />
                              <div class="input-group-append">
                                <button type="button" class="btn btn-info" onclick="guardarPlanDeSubCuentas()">
                                  <i class='fas fa-plus-circle'></i>Agregar Cuenta
                                </button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="card card-outline card-secondary">
                    <div class="card-header">
                        <i class="mr-2">📋 Cuenta:</i>
                        <h7 class="card-title0 ml-2 font-weight-bold" id="tituloSubcuentas">
                            Subcuentas de ...
                        </h7>
                    </div>
                    <div class="card-body p-1">
                      <div class="table-responsive">
                        <table id="tablaPlanDeSubCuentas"  class="table table-striped table-hover" Width="100%">
                          <thead class="bg-light">
                            <tr>
                              <th>NRO</th>
                              <th>CÓDIGO</th>
                              <th>DESCRIPCIÓN</th>
                              <th>NIVEL</th>
                              <th>ESTADO</th>
                            </tr>
                          </thead>
                        </table>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
        </div>
    </div>
</div>

<!-- MODAL REGISTRO DE CUENTAS AUXILIARES -->
 <div class="modal fade show" id="modalPlanCuentasAuxiliares"  style="display: backgroundColor: rgba(0,0,0,0.4)">
    <div class="modal-dialog modal-xl" style="max-width: 1200px;">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h7 class="modal-title text-white">
                  <i class="mr-2">📋</i>
                  REGISTRO DE AXULIARES DE CUENTA:
                  <span class="ml-2 font-weight-bold"><label id="nombreCuentaAux">...</label></span>
                </h7>
                <button type="button" class="close text-white" data-dismiss="modal">
                  <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formularioPlanCuentasAuxiliar">
                  <input type="hidden" class="form-control" id="txtAccionAux" name="txtAccionSubCuenta" />
                  <input type="hidden" class="form-control" id="id_cuentaAux" name="id_cuenta" />
                  <input class="form-control" id="id_entidad_aux" name="id_entidad_aux" />

                  <div class="card card-outline card-success mb-4">
                    <div class="card-header">
                      <h3 class="card-title">
                        <i class="mr-2">📝</i>
                        Agregar Cuenta Auxiliar
                      </h3>
                    </div>
                    <div class="card-body">
                      <div class="row">
                        <div class="col-md-3">
                          <div class="form-group">
                            <label>
                              <i class="text-danger">*</i>
                              <strong> CÓDIGO:</strong>
                            </label>
                            <input
                              class="form-control"
                              type="text"
                              id="txtCodigoAux"
                              name="txtCodigoAux"
                              placeholder="Ej: 1100"
                            />
                          </div>
                        </div>
                        <div class="col-md-9">
                          <div class="form-group">
                            <label>
                              <i class="text-danger">*</i>
                              <strong> DESCRIPCIÓN:</strong>
                            </label>
                            <div class="input-group">
                              <input
                                class="form-control"
                                type="text"
                                id="txtDescripcionAux"
                                name="txtDescripcionAux"
                                placeholder="Nombre de la cuenta auxiliar"
                              />
                              <div class="input-group-append">
                                <button type="button" class="btn btn-success" onclick="guardarAuxiliarPlanDeCuentas()">
                                  <i class='fas fa-plus-circle'></i>Agregar Auxiliar de Cuenta
                                </button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="card card-outline card-secondary">
                    <div class="card-header">
                        <i class="mr-2">📋 Auxiliares:</i>
                        <h7 class="card-title0 ml-2 font-weight-bold" id="tituloAuxiliares">
                            Auxiliares de...
                        </h7>
                    </div>
                    <div class="card-body p-1">
                      <div class="table-responsive">
                        <table id="tablaAuxiliaresPlanDeCuentas"  class="table table-striped table-hover" Width="100%">
                          <thead class="bg-light">
                            <tr>
                              <th>NRO</th>
                              <th>CÓDIGO</th>
                              <th>DESCRIPCIÓN</th>
                              <th>ESTADO</th>
                              <th>OPCIONES</th>
                            </tr>
                          </thead>
                        </table>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function(){
      var enlace  = "<?php echo base_url();?>";    
      baseurl(enlace);
      cargarComboEntidades();
      
    //   cargarNiveles();
    });
</script>  
