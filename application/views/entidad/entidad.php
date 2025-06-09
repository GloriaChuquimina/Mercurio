<script src="<?php echo  base_url() ?>scriptjs/jquery.js"></script>
<script src="<?php echo  base_url() ?>scriptjs/entidad/entidad.js"></script>

<link rel="stylesheet" href="<?php echo base_url();?>resources/css/global.css">

<div>
    <div class="card">
        <div class="card-body">
            <!-- <h1 class="page-title">Lista de Entidades Registradas</h1> -->
            <div class="container-fluid">
                <div class="row">
                    <div class="col-3 form-group">
                        <button id="botonNuevaEntidad" class="btn btn-block btn-success btn-xm" onclick='agregarEntidad()'><i class="mdi mdi-plus"></i>+ Añadir Entidad</button>
                    </div>
                </div>
                <hr>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <table id="tablaEntidades" class="table table-striped" cellspacing="0" width="100%">
                        <thead>
                            <tr class="bg-dark text-white">
                                <th>Opciones</th>
                                <th>Nro</th>
                                <th>Nombre</th>
                                <th>Sigla</th>
                                <th>Observaciones</th>
                                <th>Fecha de Registro</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEntidad" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-lg" style="max-width: 1000;" role="document" >
        <div class="modal-content" style="border-radius: 10px;">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel" style="color: black !important;">REGISTRO DE ENTIDAD<span style="color:black;" id="nombreUsuario"></span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">              
                <form id="formularioEntidad">
                    <input type="hidden" class= "form-control" id="txtAccion" name="txtAccion">
                    <input type="hidden" class= "form-control" id="id_entidad" name="id_entidad">
                    <fieldset style='margin-left: 5px;'>
                        <legend id= "titulo_registro">FORMULARIO DE REGISTRO</legend>
                        <div class="form-group row mb-3"> 
                            <div class="col-sm-1">
                                <label class="form-label small"><i  class="mdi mdi-asterisk"></i><b>Sigla:</b></label>
                            </div>
                            <div class="col-sm-2">
                                <input  class="form-control" style="margin-bottom: 15px;" type="text" id="txtSigla" name="txtSigla" rows="3">
                            </div>
                            <div class="col-sm-2">
                                <label class="form-label small"><i  class="mdi mdi-asterisk"></i><b>Nombre:</b></label>
                            </div>
                            <div class="col-sm-7">
                                <input  class="form-control" style="margin-bottom: 15px;" type="text" id="txtNombre" name="txtNombre" rows="3">
                            </div>
                        </div>                               
                        <div class="form-group row mb-3"> 
                            <div class="col-sm-2">
                                <label class="form-label small"><i  class="mdi mdi-asterisk"></i><b>Observaciones:</b></label>
                            </div>
                            <div class="col-sm-10">
                                <textarea  class="form-control" style="margin-bottom: 15px;" type="text" id="txtObservaciones" name="txtObservaciones" rows="3"></textarea>
                            </div>
                           
                        </div>                               
                       
                    </fieldset>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Cerrar</button>
                <button id="guardar" type="button" class="btn btn-info btn-sm" onclick="guardarEntidad();"><span class="glyphicon glyphicon-floppy-disk"></span> Guardar Cambios</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
      var enlace  = "<?php echo base_url();?>";    
      baseurl(enlace);
      cargarTablaEntidades();
    });
</script>  
