<script src="<?php echo  base_url() ?>scriptjs/jquery.js"></script>
<script src="<?php echo  base_url() ?>scriptjs/contabilidad/comprobantes.js"></script>
<link rel="stylesheet" href="<?php echo base_url();?>resources/css/global.css">


<div class="wapper">
    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary card-outline">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="mr-2">🏢</i>
                  Selección de Entidad
                </h3>
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
                  <div class="col-md-8">
                    <div class="d-flex align-items-center">
                      <div>
                        <h5 class="mb-0">ENTIDAD:</h5>
                        <h4 id="nombre_entidad" class="mb-0" style="color: #3c8dbc; font-weight: bold;">...
                        </h4>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4 text-right">
                    <button class="btn btn-success" onclick="agregarComprobante()">
                      <i class="fa fa-plus-circle"></i> Registro de Comprobante
                    </button>
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
                  <button type="button" class="btn btn-tool" data-card-widget="collapse" >
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
                        <th style="color: white; width: 50px;">NRO</th>
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
        </div>
    </section>
</div>
<div class="row">
            <div class="col-md-3 col-sm-6 col-12">
              <div
                class="info-box"
                style="
                  background-color: #fff;
                  box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
                  border-radius: 0.25rem;
                "
              >
                  <span class="info-box-icon bg-info">
                      <i class="far fa-file-alt"></i>
                  </span>
                  <div class="info-box-content" style="padding: 15px;">
                    <span class="info-box-text">Total Comprobantes</span>
                    <span class="info-box-number" style="font-size: 24px; font-weight: bold;">
                    </span>
                  </div>

                  <div
                    style="
                      width: 50px,
                      background-color: #17a2b8,
                      display: flex,
                      align-items: center,
                      justify-content: center,
                      color: white,
                      font-size: 24px;
                    "
                  >
                    📊
                  </div>
              </div>
            </div>
            <div class="col-md-3 col-sm-6 col-12">
              <div
                class="info-box"
                style="
                  background-color: #fff;
                  box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
                  border-radius: 0.25rem;
                "
              >
              <span class="info-box-icon bg-success">
                  <i class="far fa-file-alt"></i>
              </span>
                <div class="info-box-content" style="padding: 15px;">
                  <span class="info-box-text">Aprobados</span>
                  <span
                    class="info-box-number"
                    style="font-size: 24px; font-weight: bold; color: #28a745;"
                  >
                  </span>
                </div>
                <div
                  style="
                    width: 50px,
                    background-color: #28a745,
                    display: flex,
                    align-items: center,
                    justify-content: center,
                    color: white,
                    font-size: 24px;
                  "
                >
                  ✓
                </div>
              </div>
            </div>
              <div class="col-md-3 col-sm-6 col-12">
                <div
                  class="info-box"
                  style="
                    background-color: #fff,
                    box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2),
                    border-radius: 0.25rem;
                  "
                >
                <span class="info-box-icon bg-warning">
                    <i class="far fa-file-alt"></i>
                </span>
                    <div class="info-box-content" style=" padding:15px;">
                      <span class="info-box-text">Pendientes</span>
                      <span
                        class="info-box-number"
                        style="font-size:24px; font-weight: bold; color: #ffc107;" 
                      >   
                      </span>
                    </div>
                    <div
                      style="
                        width: 50px,
                        background-color: #ffc107,
                        display: flex,
                        align-items: center,
                        justify-content: center,
                        color: white,
                        font-size: 24px;"
                    >
                      ⏳
                    </div>
                </div>
              </div>
              <div class="col-md-3 col-sm-6 col-12">
                <div
                  class="info-box"
                  style="
                    background-color: #fff,
                    box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2),
                    border-radius: 0.25rem;"
                >
                <span class="info-box-icon bg-danger">
                    <!-- <i class="far-solid fa-xmark"> ❌</i> -->
                    <i class="far fa-file-alt"></i>
                </span>
                  <div class="info-box-content" style=" padding:15px">
                    <span class="info-box-text">Anulados</span>
                    <span
                      class="info-box-number"
                      style="font-size: 24px; font-weight: bold; color: #dc3545;"
                    >
                    </span>
                  </div>
                  <div
                    style="
                      width: 50px,
                      background-color: #dc3545,
                      display: flex,
                      align-items: center,
                      justify-content: center,
                      color: white,
                      font-size: 24px;"
                  >
                    ❌
                  </div>
                </div>
              </div>
</div>


<script type="text/javascript">
    $(document).ready(function(){
      var enlace  = "<?php echo base_url();?>";    
      baseurl(enlace);
      id_entidad = <?= json_encode($entidad) ?>;
      cargarComboPrincipal();
    //   cargarTablaComprobantesEntidades();
    });
</script>  