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
                            <button type="button" class="btn btn-primary mr-2" onclick="guardarDatosComprobanteMasDetalle()">
                                <i class="fas fa-save mr-1"></i> Cargar
                            </button>
                            <button type="button" class="btn btn-info mr-2" onclick="generarPDFComprobante()">
                                <i class="fas fa-print mr-1"></i> Buscar
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

<script type="text/javascript">
    $(document).ready(function(){
      var enlace  = "<?php echo base_url();?>";    
      baseurl(enlace);
      cargarTipoCambio();
    //   cargarTablaComprobantesEntidades();
    });
</script>  