<script src="<?php echo  base_url() ?>scriptjs/jquery.js"></script>
<script src="<?php echo  base_url() ?>scriptjs/inicio/inicio.js"></script>
<script src="<?php echo  base_url() ?>scriptjs/validacion.js"></script>
<section class="content">
    <div class="container-fluid">
        <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3 id="cantidadproductos">0</h3>

                <p>Cantidad de Productos</p>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
             
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3 id="cantidadventas">0</h3>

                <p>Cantidad de Ventas del Día</p>
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>
              
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3 id="cantidadvencidos">0</h3>

                <p>Cantidad de Productos por Vencer</p>
              </div>
              <div class="icon">
                <i class="ion ion-person-add"></i>
              </div>              
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3 id="cantidadsaldo">0</h3>

                <p>Productos con Saldo Mínimo en Almacen</p>
              </div>
              <div class="icon">
                <i class="ion ion-pie-graph"></i>
              </div>              
            </div>
          </div>
          <!-- ./col -->
        </div> 
    </div>



</section>
<script type="text/javascript">
    $(document).ready(function(){
      var enlace  = "<?php echo base_url();?>";      
     
      baseurlInicio(enlace);
    });
</script>  