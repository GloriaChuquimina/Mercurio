<script src="<?php echo  base_url() ?>scriptjs/jquery.js"></script>
<script src="<?php echo  base_url() ?>scriptjs/contabilidad/tipocambio.js"></script>
<div class="wapper">
    <section class="content">
        <div class="container-fluid">
            <!-- SECCION ENTIDAD SELECCIONADA -->
            <div class="card" style="background-color: #f0f0ff; border-left: 4px solid #3c8dbc;">
                <div class="card-body p-3">
                    <div class="row align-items-center">
                        <div class="col-md-1">
                            <div class="form-group ">
                                <label>
                                <strong> Año:</strong>
                                </label>
                                <select
                                class="form-control"
                                id="anioTipoCambio"
                                name="anioTipoCambio"
                                value="selectedEntity"
                                >
                                </select>
                            </div>                                                          
                        </div>
                        <div class="col-md-2">
                            <div class="form-group w-55">
                                <label>
                                <strong> Mes:</strong>
                                </label>
                                <select
                                class="form-control"
                                id="mesTipoCambio"
                                name="mesTipoCambio"
                                value="selectedEntity"
                                >
                                </select>
                            </div>  
                        </div>
                        <div class="col-md-6">
                        </div>
                        <div class="col-md-3 text-right">
                            <!-- <br> -->
                            <button type="button" class="btn btn-primary mr-2" onclick="agregarTipoCambio()">
                                <i class="fas fa-plus mr-1"></i> Registrar
                            </button>
                            <button type="button" class="btn btn-info mr-2" onclick="buscar()">
                                <i class="fa fa-search mr-1"></i> Buscar
                            </button>

                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
              <div class="card-header bg-gradient-primary">
                <h3 class="card-title text-black">
                  <i class="mr-2">📋</i>
                        COTIZACIONES DEL BOLIVIANO CON RELACIÓN AL DÓLAR ESTADOUNIDENSE
                </h3>
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse" >
                    <i>🔍</i>
                  </button>
                </div>

              </div>
              <div class="card-body p-1">            
                <div class="table-responsive">
                  <table id="tablaTipoCambio" class="table table-striped table-hover" style="width: 100%;">
                    <thead class="bg-gray-50">
                      <tr>
                          <!-- <th style="color: black; width: 50px;">NRO</th> -->
                          <th style="color: black;">FECHA</th>
                          <th style="color: black;">MONTO CAMBIO</th>
                          <th style="color: black;">ESTADO</th>
                          <th style="color: black; width: 100px;">OPCIONES</th>
                      </tr>
                    </thead>
                  </table>
                </div>
              </div>
              <div class="card-footer">
              </div>
            </div>
        </div>
    </section>
</div>
<div id="modalTipoCambio" class="modal fade show" style="display:backgroundColor: rgba(0,0,0,0.4)"  tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="max-width: 500px !important;">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h7 class="modal-title text-white">
                  <!-- <i class="mr-2">{isEditing ? "✏️" : "➕"}</i> -->
                  <i class="mr-2">Registro de Entidad</i>
                </h7>
                <button type="button" class="close text-white" data-dismiss="modal">
                  <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formularioTipoCambio">
                  <input type="hidden" class="form-control" id="txtAccion" name="txtAccion" />
                  <input type="hidden" class="form-control" id="id_tipocambio" name="id_tipocambio" />

                  <div class="card card-outline card-primary">
                    <div class="card-header">
                      <h3 class="card-title">
                        <i class="mr-2">📝</i>
                        FORMULARIO DE REGISTRO TIPO CAMBIO
                      </h3>
                    </div>
                    <div class="card-body">
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label>
                              <i class="text-danger">*</i>
                              <strong> FECHA:</strong>
                            </label>
                             <input type="date" class="form-control" id="txtFecha" name="txtFecha" />
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label>
                              <i class="text-danger">*</i>
                              <strong> TIPO CAMBIO:</strong>
                            </label>
                            <input
                              class="form-control"
                              type="text"
                              id="tipo_cambio"
                              name="tipo_cambio"
                              placeholder="Monto cambio..."
                            />
                          </div>
                        </div>
                      </div>                  
                    </div>
                  </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                  <i class="mr-1">❌</i> Cerrar
                </button>
                <button type="button" class="btn btn-primary" onclick="guardarTipoCambio();">
                  <i class="mr-1">💾</i> Guardar
                </button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
      var enlace  = "<?php echo base_url();?>";    
      baseurl(enlace);
      cargarCombos();
      // cargarTipoCambio()
    //   cargarTablaComprobantesEntidades();
    });
</script>  
