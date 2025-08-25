<script src="<?php echo  base_url() ?>scriptjs/jquery.js"></script>
<script src="<?php echo  base_url() ?>scriptjs/inicio/inicio.js"></script>
<script src="<?php echo  base_url() ?>scriptjs/validacion.js"></script>
<link rel="stylesheet" href="<?php echo base_url();?>resources/css/loginnew.css">
<section class="content">
    <div class="container-fluid">     
        <!-- SECCION DE TOTALES -->
        <div class="row">
          <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
              <div class="inner">
                <h3 id="cantidadproductos">0</h3>
                <p>Debe</p>
              </div>
              <div class="icon">
                <i class="fas fa-chart-area"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
              <div class="inner">
                <h3 id="cantidadproductos">0</h3>
                <p>Haber</p>
              </div>
              <div class="icon">
                <i class="fas fa-chart-line"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
              
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
              <div class="inner">
                <h3 id="cantidadproductos">0</h3>
                <p>Saldo Deudor</p>
              </div>
              <div class="icon">
                <i class="fas fa-calculator"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>            
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
              <div class="inner">
                <h3 id="cantidadproductos">0</h3>
                <p>Saldo Acreedor</p>
              </div>
              <div class="icon">
                <i class="fas fa-file-invoice"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>            
            </div>
          </div>
         
          <!-- ./col -->
        </div> 
        <div class="row">
         <!-- calendario -->
          <div class="col-md-6">
            <!-- <div class="card">
              <div class="card-header">
                <h3 class="card-title">Calendario de actividades</h3>
              </div>
              <div class="card-body">
                <div id="calendar"></div>
              </div>
            </div> -->

          </div>
          <div class="col-md-6">
            <!-- <div class="card">
              <div class="card-header">
                <h3 class="card-title">Area Chart</h3>
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <div class="chart"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                  <canvas id="areaChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 50%; display: block; width: 369px;" width="369" height="250" class="chartjs-render-monitor"></canvas>
                </div>
              </div>
            </div> -->

          </div>
         

        </div>
    </div>
</section>
<script type="text/javascript">
    $(document).ready(function(){
      var enlace  = "<?php echo base_url();?>";      
     
      baseurlInicio(enlace);
    });
</script>  