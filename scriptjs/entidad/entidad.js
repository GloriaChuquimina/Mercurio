var base_url;

function baseurl(enlace) {
   base_url = enlace;
}
function cargarTablaEntidades()
{
    var enlace = base_url + "Entidades/Entidades/cargarEntidades";
    $('#tablaEntidades').DataTable({
        destroy: true,
        "aLengthMenu": [[10, 20, 50, -1], [10, 20, 50, "Todos"]],
        "iDisplayLength": 40,
        "font-size":8,
        "ajax": {
            type: "POST",
            url: enlace
        },
    });
}
function agregarEntidad()
{
    // eliminaMensajeError();
    // eliminaMensajeErrorCombos();
    $('#txtAccion').val('nuevo');
    $('#modalEntidad').modal({backdrop: 'static', keyboard: false})
    $('#modalEntidad').modal('show');  
}
function editarEntidad(id_entidad)
{
    // eliminaMensajeError();
    // eliminaMensajeErrorCombos();

    var enlace = base_url + "Entidades/Entidades/datosEntidad";        
        $.ajax({
            type: "POST",
            url: enlace,
            data: {id_entidad:id_entidad},
            success: function(data)
            {
                var result = JSON.parse(data);
                $.each(result, function(i, datos)
                {
                    if(datos.resultado == 1)
                    { 
                        $('#txtAccion').val('editar');
                        $('#id_entidad').val(id_entidad);
                        $('#txtSigla').val(datos.sigla);
                        $('#txtNombre').val(datos.nombre);
                        $('#txtObservaciones').val(datos.observaciones);
                        $('#modalEntidad').modal({backdrop: 'static', keyboard: false})
                        $('#modalEntidad').modal('show'); 
                    }
                    else
                    {
                        swal({title: "ERROR",text: datos.mensaje,icon: "error",button: "Error"});
                    }
                 });
            }
    });
     
}
 function guardarEntidad()
{

    var accion  = $('#txtAccion').val();
    var mensaje ="";
    var boton ="";

    if(accion == 'nuevo'){
        mensaje = "¿Desea registrar la entidad?";
        boton   = "Guardar";
    }
    else{
        mensaje = "¿Desea guardar los cambios realizados en la entidad?"
        boton   = "Modificar";
    }

    swal({
        title: 'ATENCIÓN',
        text: mensaje,
        icon: 'warning',
        dangerMode: true,
        buttons: {
            cancel: "Cancelar",
            verificar: {
                text: boton,
                value: "verificar",
            }
        },
    })
    .then(respuesta => {
        if (respuesta)
        {

            var enlace = base_url + "Entidades/Entidades/guardarEntidad";
            var datos = $('#formularioEntidad').serialize();
            $.ajax({
                type: "POST",
                url: enlace,
                data: datos,
                success: function(data) {
                    var result =JSON.parse(data);
                    $.each(result,function(i,datos){
                        if(datos.resultado == 0)
                        {
                            // visualizarValidaciones(datos.mensaje);
                            swal({title:"ALERTA",text:datos.mensaje,icon:"warning",button:"OK",dangerMode:true});
                        }else{
                            swal({title:"!Excelente¡",text:datos.mensaje,icon:"success",button:"OK"});
                            cargarTablaEntidades();
                            $("#modalEntidad").modal('hide'); 
                        }
                    });
                }
            });
        }
    });
 
}