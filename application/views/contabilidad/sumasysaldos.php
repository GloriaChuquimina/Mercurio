<script src="<?php echo  base_url() ?>scriptjs/jquery.js"></script>
<script src="<?php echo  base_url() ?>scriptjs/contabilidad/sumasysaldos.js"></script>
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
            <!-- SECCION DE TOTALES -->
             <!-- <div class="row" id="totales" style="display: none"> -->
             <div class="row" id="totales" >
                        <div class="col-md-2 col-sm-6 col-12">
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
                                <span class="info-box-text">Total Debe</span>
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
                                  📥
                            </div>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-6 col-12">
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
                                <span class="info-box-text">Total Haber</span>
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
                                📤
                            </div>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-6 col-12">
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
                                <span class="info-box-text">Saldo Deudor</span>
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
                                 ⬆️
                            </div>
                            </div>
                        </div>                        
                        <div class="col-md-2 col-sm-6 col-12">
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
                                <span class="info-box-text">Saldo Acreedor</span>
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
                                ⬇️
                            </div>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-6 col-12">
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
                                <span class="info-box-text">Diferencia</span>
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
                                ✅: ⚠️
                            </div>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-6 col-12">
                            <div
                            class="info-box"
                            style="
                                background-color: #fff,
                                box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2),
                                border-radius: 0.25rem;"
                            >
                            <span class="info-box-icon bg-danger">
                                <i class="far fa-file-alt"></i>
                            </span>
                            <div class="info-box-content" style=" padding:15px">
                                <span class="info-box-text">Estado:Balanceado-Desbalanceado</span>
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
                                ⚖️
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
                    <div class="row">
                      <div class="col-md-3">
                        <div class="form-group">
                          <label>
                            <strong>FECHA DESDE:</strong>
                          </label>
                          <input
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
                          <select
                            class="form-control"
                          >
                          </select>
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
                        <button class="btn btn-primary mr-2">
                          <i class="mr-1">🔍</i> Generar Balance
                        </button>
                        <button class="btn btn-success mr-2">
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
            <!-- ESTADO DEL BALANCE -->
            <div class="card" style="background-color:#D9F6F5;  border-left: 4px solid #10707f;" id="entidadSeleccionada" style="display: none">
                <div class="">
                    <div class="d-flex align-items-center">
                        <div style="font-size: 24px; margin-right: 15px">✅⚠️</div>
                        <div>
                        <h5 class="alert-heading mb-1">Balance Correcto: Balance Desbalanceado</h5>
                        <p class="mb-0">
                            El balance de sumas y saldos está correctamente balanceado.
                            Existe una diferencia de Bs. 000000 que debe ser revisada.
                        </p>
                        </div>
                    </div>
                </div>
            </div>
           <!-- TABLA DE BALANCE DE SUMAS Y SALDOS -->
            <div class="card">
                <div class="card-header bg-gradient-secondary">
                  <h3 class="card-title text-white">
                    <i class="mr-2">⚖️</i>
                    BALANCE DE SUMAS Y SALDOS
                  </h3>
                  <div class="card-tools">
                    <span class="badge badge-light">
                      Período: {dateFrom} al {dateTo}
                    </span>
                  </div>
                </div>
                <div class="card-body p-0">
                  <div class="table-responsive">
                    <table class="table table-striped table-hover">
                      <thead class="bg-dark">
                        <tr>
                          <th style="color: white; width: 100px" >CÓDIGO</th>
                          <th style="color: white;">DESCRIPCIÓN</th>
                          <th style="color: white; width: 80px" >NIVEL</th>
                          <th style="color: white; width: 100px" >TIPO</th>
                          <th style="color: white; width: 120px; text-align: right;">SALDO ANTERIOR</th>
                          <th style="color: white; width: 120px; text-align: right;">DEBE</th>
                          <th style="color: white; width: 120px; text-align: right;">HABER</th>
                          <th style="color: white; width: 120px; text-align: right;">SALDO DEUDOR</th>
                          <th style="color: white; width: 120px; text-align: right;">SALDO ACREEDOR</th>
                        </tr>
                      </thead>
                      <tfoot>
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
                        <!-- <tr class={balanceado ? "bg-success" : "bg-danger"}> -->
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
                      </tfoot>
                    </table>
                  </div>
                </div>
                <div class="card-footer">
                  <div class="row">
                    <div class="col-md-6">
                      <p class="text-muted">
                        Empresa: • Período:  al  • 
                        cuentas mostradas
                      </p>
                    </div>
                    <div class="col-md-6 text-right">
                      <small class="text-muted">
                        Estado: Balanceado : Desbalanceado • Generado:
                      </small>
                    </div>
                  </div>
                </div>
            </div>
        </div>
    </section>
</div>
