  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?php echo base_url()?>Inicio" class="brand-link">      
      <span class="brand-text font-weight-light"><?= $nombre_sistema?></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <!-- <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image"> -->
        <div class="info">
          <a href="<?php echo site_url("Usuarios/Datos");?>" class="d-block"><?= $nombre_usuario?></a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          
          
       
          <li class="nav-header">MENÚ DE OPCIONES</li>                  

           <?php foreach($rolescero as $rol):?>              
              <li class="nav-item">
              <a href="<?php echo site_url($rol->link);?>" class="nav-link"><i class="<?php echo $rol->icono; ?>"></i><p><?=$rol->opcion?></p></a>
            </li>
          <?php endforeach?>

          <?php $nivelanterior = 0; $con = 0;?>
          <?php foreach($roles as $rol): ?>
              <?php if($rol->nivel== 1){ ?>
                  <?php if ($nivelanterior == 2){ ?>
                          </ul>                            
                  </li>
                  <?php }?>                      

                  <li class="nav-item">
                    <a href="#" class="nav-link"><i class="<?php echo $rol->icono; ?>"></i><p><?php echo $rol->opcion; ?><i class="fas fa-angle-left right"></i></p></a>
                    <ul class="nav nav-treeview">
              <?php } ?>

          <?php if ($rol->nivel == 2){?>                  

              <li class="nav-item">
                <a  href="<?php echo site_url($rol->link);?>" class="nav-link"><i class="far fa-circle nav-icon"></i><p><?=$rol->opcion?></p></a>

                

              </li>  
          <?php }?>
          <?php $nivelanterior = $rol->nivel;  ?>
          <?php endforeach?>
          <?php if ($nivelanterior == 2){?>
                      </ul>
                 
              </li>
         <?php }?>
          
         
         
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1><?= $titulo?></h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"> <a class="dropdown-item" href="<?php echo base_url()?>Login/salir">
                                <i class="mdi mdi-close text-danger"></i>
                                <span class="notification-text">Cerrar Sesión</span>
                            </a></li>
              
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>