<script src="<?php echo  base_url() ?>scriptjs/jquery.js"></script>
<script src="<?php echo  base_url() ?>scriptjs/entidad/entidad.js"></script>

<div class="wapper">
    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="col-md-8">
                        <h3 class="card-title mb-0">
                        </h3>
                    </div>
                    <div class="col-md-4 text-right">
                        <button type="button" class="btn btn-success mr-2" onclick="agregarEntidad()">
                            <i class="fas fa-save mr-1"></i> Añadir Entidad
                        </button>
                    </div>
                </div>
            </div> 
            <div class="card">
              <div class="card-header bg-gradient-secondary">
                <h3 class="card-title text-white">
                  <i class="mr-2">🏢</i>
                  Entidades Registradas
                </h3>
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse" >
                    <i>🔍</i>
                  </button>
                </div>
              </div>
              <div class="card-body p-1">
                <div class="table-responsive">
                  <table id="tablaEntidades" class="table table-striped table-hover" style="width: 100%;">
                    <thead class="bg-dark">
                      <tr>
                        <th style="color: white; width: 100px;">OPCIONES</th>
                        <th style="color: white; width: 50px;">NRO</th>
                        <th style="color: white;">NOMBRE</th>
                        <th style="color: white;">SIGLA</th>
                        <th style="color: white;">OBSERVACIONES</th>
                        <th style="color: white;">FECHA DE REGISTRO</th>
                        <th style="color: white;">ESTADO</th>
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

<div id="modalEntidad" class="modal fade show" style="display:backgroundColor: rgba(0,0,0,0.4)"  tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="max-width: 1000px;">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h7 class="modal-title text-white">
                  <!-- <i class="mr-2">{isEditing ? "✏️" : "➕"}</i> -->
                  <i class="mr-2">Registro de Entidad</i>
                </h7>
                <button type="button" class="close text-white" data-dismiss="modal">
                  <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formularioEntidad">
                  <input type="hidden" class="form-control" id="txtAccion" name="txtAccion" />
                  <input type="hidden" class="form-control" id="id_entidad" name="id_entidad" />

                  <div class="card card-outline card-success">
                    <div class="card-header">
                      <h3 class="card-title">
                        <i class="mr-2">📝</i>
                        FORMULARIO DE REGISTRO
                      </h3>
                    </div>
                    <div class="card-body">
                      <div class="row">
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
                              placeholder="Ej: EDEMO"
                            />
                          </div>
                        </div>
                        <div class="col-md-9">
                          <div class="form-group">
                            <label>
                              <i class="text-danger">*</i>
                              <strong> NOMBRE:</strong>
                            </label>
                            <input
                              class="form-control"
                              type="text"
                              id="txtNombre"
                              name="txtNombre"
                              placeholder="Nombre completo de la entidad"
                            />
                          </div>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group">
                            <label>
                              <i class="text-danger">*</i>
                              <strong> OBSERVACIONES:</strong>
                            </label>
                            <textarea
                              class="form-control"
                              id="txtObservaciones"
                              name="txtObservaciones"
                              rows="4"
                              placeholder="Descripción y observaciones sobre la entidad..."
                            ></textarea>
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
                <button type="button" class="btn btn-success" onclick="guardarEntidad();">
                  <i class="mr-1">💾</i> Guardar Cambios
                </button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
      var enlace  = "<?php echo base_url();?>";    
      baseurl(enlace);
      cargarTablaEntidades();
    });
</script>  
