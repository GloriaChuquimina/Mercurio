<script src="<?php echo base_url(); ?>scriptjs/jquery.js"></script>
<script src="<?php echo base_url(); ?>scriptjs/contabilidad/comprobantes.js"></script>
<script src="<?php echo base_url(); ?>scriptjs/validacion.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>resources/css/global.css">

<div class="wrapper">
    <section class="content">
        <div class="container-fluid">
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
          
            <div class="card" style="background-color: #f0f0ff; border-left: 4px solid #3c8dbc;">
              <div class="card-body p-3">
                <div class="row align-items-center">
                  <div class="col-md-6">
                    <div class="d-flex align-items-center">
                      <div>
                        <h5 class="mb-0">ENTIDAD:</h5>
                        <h5 id="nombre_entidad" class="mb-0" style="color: #3c8dbc; font-weight: bold;">...
                        </h5>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <div class="d-flex align-items-end">
                              <div style="flex-grow: 1; margin-right: 10px;">
                                  <label for="gestion_comprobante">
                                      <strong>GESTIÓN:</strong>
                                  </label>
                                  <select
                                      class="form-control"
                                      id="gestion_comprobante"
                                      name="gestion_comprobante"
                                      value="selectedEntity"
                                  >
                                  </select>
                              </div>
                              <div style="flex-grow: 1; margin-right: 10px;">
                                  <label for="tipo_comprobante">
                                      <strong>TIPO COMPROBANTE:</strong>
                                  </label>
                                  <select
                                      class="form-control"
                                      id="tipo_comprobante"
                                      name="tipo_comprobante"
                                      value="selectedEntity"
                                  >
                                  </select>
                              </div>
                              <div>
                                  <button class="btn btn-success" onclick="agregarComprobante()">
                                      <i class="fa fa-plus-circle"></i> Registro de Comprobante
                                  </button>
                              </div>
                          </div>
                      </div>
                  </div>
                </div>
              </div>
            </div>
          
            <div class="card">
              <div class="card-header bg-gradient-secondary">
                <h3 class="card-title text-black">
                  <i class="mr-2">📋</i>
                  COMPROBANTES
                </h3>
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i>🔍</i>
                  </button>
                </div>
              </div>
              <div class="card-body p-1">
                <div class="table-responsive">
                  <table id="tablaComprobantesEntidades" class="table table-striped table-hover" style="width: 100%;">
                    <thead class="bg-dark">
                      <tr>
                        <th style="color: white; width: 100px;">OPCIONES</th>
                        <!-- <th style="color: white; width: 50px;">NRO</th> -->
                        <th style="color: white;">TIPO</th>
                        <th style="color: white;">CORRELATIVO</th>
                        <th style="color: white;">FECHA</th>
                        <th style="color: white;">GLOSA</th>
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
        </div> <!-- cierra container-fluid -->
    </section> <!-- cierra content -->
</div> <!-- cierra wrapper -->
<script type="text/javascript">
    $(document).ready(function(){
      var enlace  = "<?php echo base_url();?>";    
      baseurl(enlace);
      id_entidad = <?= json_encode($entidad) ?>;
      tipo_comprobante = <?= json_encode($tipo_comprobante) ?>;
      gestion = <?= json_encode($gestion) ?>;
      cargarComboPrincipal();
      cargarCombos();
    //   cargarTablaComprobantesEntidades();
    });
</script>  
