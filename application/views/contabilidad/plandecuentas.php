<script src="<?php echo  base_url() ?>scriptjs/jquery.js"></script>
<script src="<?php echo  base_url() ?>scriptjs/contabilidad/plandecuentas.js"></script>


<div class="wapper">
    <section class="content">
        <div class="container-fluid" >
            <div class="card card-primary">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="col-md-8">
                        <h3 class="card-title mb-0">
                        </h3>
                    </div>
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
              <div class="card-body p-1">
                <div class="table-responsive">
                  <table id="tablaPlanDeCuentas" class="table table-striped table-hover" style="width: 100%;">
                    <thead class="bg-dark">
                      <tr>
                        <th style="color: white; width: 100px;">OPCIONES</th>
                        <th style="color: white; width: 50px;">NRO</th>
                        <th style="color: white;">CÓDIGO</th>
                        <th style="color: white;">DESCRIPCIÓN</th>
                        <th style="color: white;">TIPO</th>
                        <th style="color: white;">NIVEL</th>
                        <th style="color: white;">SIGLA</th>
                        <th style="color: white;">ESTADO</th>
                        <th style="color: white;">CODIGO_CUENTA</th>
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
                

                  <div class="card card-outline card-success">
                    <div class="card-header">
                      <h3 class="card-title">
                        <i class="mr-2">📊</i>
                        Datos de la Cuenta Mayor
                      </h3>
                    </div>
                    <div class="card-body">
                      <div class="row">
                        <div class="col-md-6">
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
                        <div class="col-md-6">
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
                              maxlength="3"
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
                              <strong> DESCRIPCIÓN:</strong>
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
                  <div class="card card-outline card-info mb-4">
                    <div class="card-header">
                      <h3 class="card-title">
                        <i class="mr-2">📝</i>
                        Agregar Subcuenta
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
                              id="txtCodigo"
                              name="txtCodigo"
                              placeholder="Ej: 1100"
                            />
                          </div>
                        </div>
                        <div class="col-md-3">
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
                              maxlength="3"
                            />
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label>
                              <i class="text-danger">*</i>
                              <strong> DESCRIPCIÓN:</strong>
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
      cargarTablaPlanDeCuentas();
    //   cargarNiveles();
    });
</script>  
