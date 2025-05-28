<script src="<?php echo  base_url() ?>scriptjs/jquery.js"></script>
<script src="<?php echo  base_url() ?>scriptjs/clave/clave.js"></script>
<script src="<?php echo  base_url() ?>scriptjs/validacion.js"></script>
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-3">
        <div class="form-group">
          <label for="exampleInputEmail1">NOMBRES</label>
          <input type="text" class="form-control" id="txt_nombres" name="txt_nombres" placeholder="Ingrese el Complemento">
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <label for="exampleInputEmail1">PRIMER APELLIDO :</label>
          <input type="text" class="form-control" id="txt_primer_apellido" name="txt_primer_apellido"  placeholder="Ingrese la Razón Social">
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <label for="exampleInputEmail1">SEGUNDO APELLIDO :</label>
          <input type="email" class="form-control" id="txt_segundo_apellido" name="txt_segundo_apellido" placeholder="Ingrese el email">
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <label for="exampleInputEmail1">NOMBRE DE USUARIO :</label>
          <input type="email" class="form-control" id="txt_usuario" name="txt_usuario" >
        </div>
      </div>
    </div>
    <form id="formularioActualizar">
      <div class="row">
        <div class="col-md-3">
          <div class="form-group">
            <label for="exampleInputEmail1">CONTRASEÑA ACTUAL</label>
            <input type="password" class="form-control" id="clave_actual" name="clave_actual" placeholder="Contraseña Actual">
          </div>
        </div>          
      </div>
      <div class="row">
        <div class="col-md-3">
          <div class="form-group">
            <label for="exampleInputEmail1">NUEVA CONTRASEÑA</label>
            <input type="password" class="form-control" id="nueva_clave" name="nueva_clave" placeholder="Nueva Contraseña">
          </div>
        </div>          
        <div class="col-md-3">
          <div class="form-group">
            <label for="exampleInputEmail1">REPETIR CONTRASEÑA</label>
            <input type="password" class="form-control" id="repetir_clave" name="repetir_clave" placeholder="Repetir Contraseña">
          </div>
        </div>          
      </div>
    </form>
    <div class="row">
      <div class="form-group col-3">
        <button class="btn btn-success" onclick="cambiarClave()">CAMBIAR CONTRASEÑA</button>
      </div>
    </div>
  </div>
</section>
<script type="text/javascript">
    $(document).ready(function(){
      var enlace  = "<?php echo base_url();?>";
      baseurlclave(enlace);
      cargarDatosGenerales();
    });
</script>  