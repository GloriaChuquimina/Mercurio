<script src="<?php echo  base_url() ?>scriptjs/jquery.js"></script>
<script src="<?php echo  base_url() ?>scriptjs/contabilidad/libromayor.js"></script>

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
            <!-- SECCION TOTALES -->
            <div class="row" id="totales" style="display: none">
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
                                <span class="info-box-text">Total Debe</span>
                                <span class="info-box-number" style="font-size: 24px; font-weight: bold;">
                                </span>
                            </div>

                            <div
                                style="
                                width: 50px,
                                background-color: #17a2b8,
                                display: flex,
                                alignItems: center,
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
                                background-color: #fff,
                                box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
                                border-radius: 0.25rem;
                            "
                            >
                            <span class="info-box-icon bg-success">
                                <i class="far fa-file-alt"></i>
                            </span>
                            <div class="info-box-content" style="padding: 15px;">
                                <span class="info-box-text">Total Haber</span>
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
                                <span class="info-box-text">Saldo Final</span>
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
                                fontSize: 24px:"
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
                                <span class="info-box-text">Movimientos Cuentas</span>
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
                    <div class="row">
                      <div class="col-md-6">
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
                    </div>
                    <div class="row">
                      <div class="col-md-12 text-right">
                        <button class = "btn btn-primary mr-2"
                              onClick = "consultar()">
                          <i class="mr-1">🔍</i> Consultar
                        </button>
                        <button class="btn btn-success mr-2">
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
                                    <h7 class="mb-0">CUENTA:|EMPRESA:</h7>
                                    <h4 class="mb-0" style="color: #007bff; font-weight: bold;">
                                        ....
                                    </h4>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- TABLA LIBRO MAYOR -->
            <div class="card" id="tablaLibroMayor" style="display: none">
                <div class="card-header bg-gradient-secondary">
                  <h3 class="card-title text-white">
                    <i class="mr-2">📖</i>
                    LIBRO MAYOR - DETALLE DE MOVIMIENTOS
                  </h3>
                  <div class="card-tools">
                    <!-- <span class="badge badge-light">
                      NROMOVIMIENTOS
                    </span> -->
                  </div>
                </div>
                <div class="card-body p-0">
                  <div class="table-responsive">
                    <table class="table table-striped table-hover" id="tbl_libroMayor" name ="tbl_libroMayor">
                      <!-- <thead class="bg-dark">
                        <tr>
                          <th style="color: white; width:100px ">FECHA</th>
                          <th style="color: white; width:120px ">COMPROBANTE</th>
                          <th style="color: white; width:100px ">TIPO</th>
                          <th style="color: white;">GLOSA</th>                      
                          <th style="color: white; width:120px;text-align: right" >DEBE</th>
                          <th style="color: white; width:120px;text-align: right">HABER</th>
                          <th style="color: white; width:120px;text-align: right">SALDO</th>
                          <th style="color: white; width:80px">ACCIONES</th>
                        </tr>
                      </thead>
                      <tr class="bg-primary"> -->
                          <td colSpan="4" style="color: white; fontWeight: bold">
                            TOTALES
                          </td>
                          <td style="text-align:right;color: white;fontWeight:bold" >
                            0000
                          </td>
                          <td style="text-align: right; color: white; fontWeight: bold">
                            0000
                          </td>
                          <td style="text-align: right; color: white; fontWeight: bold" >
                            0000
                          </td>
                          <td></td>
                        </tr>
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
            <!-- MENSAJE CUANDO NO HAY SELECCIONES-->
             <div class="card" id="mensajeSeleccion" style="display: none;">
                <div class="card-body text-center p-5">
                  <div style="font-size: 64px; margin-bottom: 20px">📖</div>
                  <h4 class="text-muted">Libro Mayor</h4>
                  <p class="text-muted">
                    <!-- {!selectedEntity
                      ? "Seleccione una empresa para comenzar"
                      : "Seleccione una cuenta contable para ver los movimientos"} -->
                  </p>
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
            <div class="modal-body">
                <div class="table-responsive">
                  <table class="table table-striped table-hover" id="tbl_CuentasContables" style="width: 100%;">
                    <thead class="bg-dark">
                      <tr>
                        <th style="color: white; text-align: center;">OPCIONES</th>
                        <th style="color: white;">CÓDIGO</th>
                        <th style="color: white;">DESCRIPCIÓN</th>
                      </tr>
                    </thead>
                  </table>
                </div>
              </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
      var enlace  = "<?php echo base_url();?>";    
      baseurl(enlace);
      cargarCombos();
      cargarCuentasLista();
    //   cargarTablaComprobantesEntidades();
    });
</script>  