<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>MERCURIO</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url();?>resources/plugins/fontawesome-free/css/all.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="<?php echo base_url();?>resources/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">


  <link rel="stylesheet" href="<?php echo base_url();?>resources/plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="<?php echo base_url();?>resources/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  
  <link rel="stylesheet" href="<?php echo base_url();?>resources/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="<?php echo base_url();?>resources/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url();?>resources/dist/css/adminlte.min.css">

  
   <script src="<?php echo  base_url() ?>resources/js/sweetalert.min.js"></script>

</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
       <a class="dropdown-item" href="<?php echo base_url()?>Inicio">
                                <i class="mdi mdi-close text-danger"></i>
                                <span class="notification-text">Inicio</span>
                            </a>
      </li>      
    </ul>
    
  </nav>



  <div class="modal " id="pdfModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 1300px;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalCenter"><b></b></h3>
                <button type="button" class="close cerrarPDF" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body ">
                <div id="divPDF" align="center"></div>
            </div>
            <!-- <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Cerrar</button>
            </div> -->
        </div>
    </div>
</div>


<div class="modal " id="procesoCargadoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 500px;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" ><b>Registrando datos para la facturación...</b></h3>
                
            </div>
            <div class="modal-body">
                <div class="col-12">                
                  <strong>Enviando datos al SIAT para la facturación, espere por favor no salga de esta pantalla...</strong>
                  <div align="center">
                    <img src="<?php echo  base_url() ?>resources/images/cargado.gif">
                  </div>                  
            </div>
            </div>            
        </div>
    </div>
</div>


<div class="modal " id="procesoDatosModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 500px;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" ><b>Procensando información...</b></h3>
                
            </div>
            <div class="modal-body">
                <div class="col-12">                
                  <strong>Procesando la información, espere por favor no salga de esta pantalla...</strong>
                  <div align="center">
                    <img src="<?php echo  base_url() ?>resources/images/cargado.gif">
                  </div>                  
            </div>
            </div>            
        </div>
    </div>
</div>

