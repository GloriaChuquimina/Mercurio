var base_url;
var identidad;
function baseurlUsuarios(venlace,videntidad)
{
    base_url  = venlace;  
    identidad = videntidad;    
    cargarDatos();
}

function cargarDatos()
{
    cargartablaUsuarios();
    //cargartablaUsuariosAnteriores();
    //cargartablaUsuariosHistoricos();

    $('#opcionSeleccionar').click (function ()
    {
        if( $('#opcionSeleccionar').prop('checked') ) 
        {
            marcar = 1;
        }
        else
        {
           marcar = 2;
        }           

        var idusuario = $('#txtDato').val();
        cargarRoles(idusuario,marcar);            
    });
}

function cargartablaUsuarios()
{
 
    var enlace = base_url + "Usuarios/Usuarios/cargarTablaUsuarios";
    $('#tbl_usuarios').DataTable({
        destroy: true,
        "aLengthMenu": [[5, 10, 15, -1], [5, 10, 15, "Todos"]],
        "iDisplayLength": 10,
        "ajax": {
            type: "GET",
            url: enlace
        },
    });
}s

function cargartablaUsuariosAnteriores()
{
    var enlace = base_url + "Usuarios/Usuarios/cargarTablaUsuariosAnteriores";
    $('#tbl_usuarios_anteriores').DataTable({
        destroy: true,
        "aLengthMenu": [[5, 10, 15, -1], [5, 10, 15, "Todos"]],
        "iDisplayLength": 10,
        "ajax": {
            type: "GET",
            url: enlace
        },
    });
}

function cargartablaUsuariosHistoricos()
{
    var enlace = base_url + "Usuarios/Usuarios/cargarTablaUsuariosHistoricos";
    $('#tbl_usuarios_historicos').DataTable({
        destroy: true,
        "aLengthMenu": [[5, 10, 15, -1], [5, 10, 15, "Todos"]],
        "iDisplayLength": 10,
        "ajax": {
            type: "GET",
            url: enlace
        },
    });
}

function cargartablaNuevasPersonas()
{
    var enlace = base_url + "Usuarios/Usuarios/cargarTablaNuevasPersonas";
    $('#tbl_nuevas_personas').DataTable({
        destroy: true,
        "aLengthMenu": [[5, 10, 15, -1], [5, 10, 15, "Todos"]],
        "iDisplayLength": 10,
        "ajax": {
            type: "GET",
            url: enlace
        },
    });
}


function nuevasPersonas()
{
    cargartablaNuevasPersonas();
    $('#nuevasPersonas').modal({backdrop: 'static', keyboard: false})
    $('#nuevasPersonas').modal('show');   
}
function habilitarUsuario(id_funcionario)
{
    if(confirm('¿Estás seguro de habilitar al usuario seleccionado?'))
    {
        var enlace = base_url + "Usuarios/Usuarios/habilitarUsuarios";        
        $.ajax({
            url: enlace,
            method: 'POST',
            data: {id_func: id_funcionario},
            success: function (data) {
                var result = JSON.parse(data);
                $.each(result, function(i, datos)
                {
                    if(datos.resultado == 0)
                    {
                        alert(datos.mensaje);                                          
                    }
                    else
                    {
                        alert(datos.mensaje); 
                        $('#nuevasPersonas').modal('hide');    
                        cargarDatos();
                    }
                });   
            }
        });    
    }
    else
    {
      return false;
    }
}

function opcionesUsuario(idusuario,marca)
{
    cargarDatosUsuario(idusuario);
    $("#opcionSeleccionar").removeAttr('checked');
    $('#editarRoles').modal({backdrop: 'static', keyboard: false})
    $('#editarRoles').modal('show');
    cargarRoles(idusuario,marca);
    $('#generarClave option[value="NO"]').prop('selected','selected');
}

function cargarRoles(idusuario,marca)
{    
    $('#txtDato').val(idusuario);
                  
    var enlace = base_url + "Usuarios/Usuarios/permisosUsuarios";
    $.ajax({
        type: "POST",
        url: enlace,
        data: {idUser: idusuario,marcar:marca},
        success: function(data)
        {   
            if(data == 0)
            {
                alert('Ocurrio un error al cargar la información de documentos, Inicie sessión con el usuario Administrador');
                window.setTimeout('location.reload()', 500); 
            }
            else
            {           
                $("#listadoPermisosSistemas" ).load(base_url +  "Usuarios/Usuarios/permisosUsuariosSistemas" ,{idUser: idusuario,marcar:marca} );                       
                $('#listadoPermisos').html(data);                                                   
            }               
        }
    });
}

function eliminarUsuario(idUsuario)
{
    swal({
        title: 'ATENCIÓN',
        text: "¿Está seguro de eliminar al Usuario?",
        icon: 'warning',
        dangerMode: true,
        buttons: {
            cancel: "Cancelar",
            verificar: {
                text: "ELIMINAR",
                value: "eliminar",
            }
        },
    })
    .then(respuesta => {
        if (respuesta)
        {
            var enlace = base_url + "Usuarios/Usuarios/eliminarUsuario";
            $.ajax({
                type: "POST",
                url: enlace,
                data: {idUsuario:idUsuario },
                success: function (data) {
                    var result = JSON.parse(data);
                    $.each(result, function(i, datos)
                    {
                        if(datos.resultado == 0)
                        {
                            alert(datos.mensaje);
                        }
                        else
                        {
                            swal({title: "OK",text: datos.mensaje,icon: "success",button: "OK",});
                            cargartablaUsuarios();
                            cargartablaUsuariosAnteriores();
                            cargartablaUsuariosHistoricos();
                        }
                    });
                    
                }
            });
        }
    });
}


function restablecerUsuario(idUsuario)
{
    swal({
        title: 'ATENCIÓN',
        text: "¿Está seguro de RESTABLECER al Usuario Seleccionado?",
        icon: 'warning',
        dangerMode: true,
        buttons: {
            cancel: "Cancelar",
            verificar: {
                text: "Restablecer Usuario",
                value: "eliminar",
            }
        },
    })
    .then(respuesta => {
        if (respuesta)
        {
            var enlace = base_url + "Usuarios/Usuarios/restablecerUsuario";
            $.ajax({
                type: "POST",
                url: enlace,
                data: {idUsuario:idUsuario },
                success: function (data) {
                    var result = JSON.parse(data);
                    $.each(result, function(i, datos)
                    {
                        if(datos.resultado == 0)
                        {
                            alert(datos.mensaje);
                        }
                        else
                        {
                            swal({title: "OK",text: datos.mensaje,icon: "success",button: "OK",});
                            cargartablaUsuarios();
                            cargartablaUsuariosAnteriores();
                            cargartablaUsuariosHistoricos();
                        }
                    });
                    
                }
            });
        }
    });
}
function guardarDatos()
{
    if(confirm('¿Estas seguro de guardar los cambios realizados?')){  
        var enlace = base_url + "Usuarios/Usuarios/guardarPermisosUsuarios";                  
        var datos  = $('#formularioPermisos').serialize();
        var idPersona = $('#txtDato').val();
        $.ajax({
            type: "POST",
            url: enlace,
            data: datos,
            success: function(data)
            {                                    
                var result = JSON.parse(data);
                $.each(result, function(i, datos)
                {
                    if(datos.resultado == 0)
                    {   
                        swal({title: "ALERTA",text: datos.mensaje ,icon: "warning",button: "OK",dangerMode:true,});
                    }
                    else
                    {   
                        swal("¡Excelente!", datos.mensaje, "success");
                        $('#editarRoles').modal('hide');
                        generarReporte(idPersona);
                    }
                });
            }
        });
    }
}
function generarReporte(idRegistro)
{    

    $('#divPDF').html('');
    var iframe = document.createElement("iframe");
        iframe.width = '100%';
        iframe.height = '700px';
        iframe.src = base_url+'Usuarios/Usuarios/resumenUsuarios/'+idRegistro; 
        $('#divPDF').append(iframe);
    $('#divCapa').addClass('overlay');    
    $('#pdfModal > .modal-dialog ').parent().css('z-index', 1999);
    $('#pdfModal > .modal-dialog ').css("max-width","75%"); 
    $('#pdfModal').show();   
}

function cargarDatosUsuario(idusuario)
{

     $("#datosUsuario").load(base_url + "Usuarios/Usuarios/datosUsuarioHTML/"+idusuario );
}
