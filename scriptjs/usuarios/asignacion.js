var base_url;
function baseurl(enlace)
{
    base_url = enlace;
}
function cargarFuncionesUsuarios()
{
    cargarTablaUsuarios();
}
function cargarTablaUsuarios()
{
    var enlace = base_url + "Usuarios/Usuarios/cargarTablaUsuarios";
    $('#tbl_usuarios').DataTable({
        destroy: true,
        "aLengthMenu": [[5, 10, 50, -1], [5, 10, 50, "Todos"]],
        "iDisplayLength": 50,
        "ajax": {
            type: "POST",
            url: enlace,           
        },
    });
}
function opcionesUsuario(idusuario)
{
    $('#txtDato').val(idusuario);
    var enlace = base_url + "Usuarios/Usuarios/permisosUsuarios";
    $.ajax({
        type: "POST",
        url: enlace,
        data: {idUser: idusuario},
        success: function(data)
        {   
            if(data == 0)
            {
                alert('Ocurrio un error al cargar la información de documentos, Inicie sessión con el usuario Administrador');
                window.setTimeout('location.reload()', 500); 
            }
            else
            {           
                // $("#listadoPermisosSistemas" ).load(base_url +  "Usuarios/Usuarios/permisosUsuariosSistemas" ,{idUser: idusuario} );                       
                // cargarDatosUsuario(idusuario);
                 $('#listadoPermisos').html(data);                             
                $('#editarRoles').modal({backdrop: 'static', keyboard: false})
                $('#editarRoles').modal('show');                     
            }
               
        }
    });
}
function guardarDatos()
{
    if(confirm('¿Estas seguro de guardar los cambios realizados?')){  
        var enlace = base_url + "Usuarios/Usuarios/guardarPermisosUsuarios";                  
        var datos  = $('#formularioRolesUsuario').serialize();
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
                        // generarReporte(idPersona);
                    }
                });
            }
        });
    }
}
function agregarUsuariosDRP()
{
    cargarTablaUsuariosDRP();
    $('#modalAgregarUsuariosDRP').modal({backdrop: 'static', keyboard: false})
    $('#modalAgregarUsuariosDRP').modal('show');  
}

function agregarUsuario()
{
    cargarTablaUsuario();
    $('#modalAgregarUsuarios').modal({backdrop: 'static', keyboard: false})
    $('#modalAgregarUsuarios').modal('show');  
}
function cargarTablaUsuariosDRP()
{
    var enlace = base_url + "Usuarios/Usuarios/cargarTablaUsuariosDRP";
    $('#tbl_usuariosDRP').DataTable({
        destroy: true,
        "aLengthMenu": [[5, 10, 50, -1], [5, 10, 50, "Todos"]],
        "iDisplayLength": 50,
        "ajax": {
            type: "POST",
            url: enlace,           
        },
    });
}
function guardarUsuarioAdmin(id_usuario)
{
    if(confirm('¿Estas seguro de registrar al usuario?'))
    {  
        var enlace = base_url + "Usuarios/Usuarios/guardarUsuarioSistema";                 
        // var datos  = $('#formDocumentos').serialize();            
        $.ajax({
            type: "POST",
            url: enlace,
            data: {id_fun:id_usuario},
            success: function(data)
            {            
                var result = JSON.parse(data);
                $.each(result, function(i, datos)
                {
                    if(datos.resultado == 0)
                    {   
                        swal({title: "ALERTA",text: datos.mensaje ,icon: "warning",button: "OK",dangerMode:true,});
                       // $('#modalAgregarDocumento').modal('hide');
                    }
                    else
                    {   
                        cargarTablaUsuarios();
                        swal("¡Excelente!", 'SE REGISTRO AL USUARIO CORRECTAMENTE', "success");                            
                        $('#modalAgregarUsuariosDRP').modal('hide');
                        $('#modalAgregarUsuarios').modal('hide');
                    }
                });
            }
        });
    }
}
function cargarTablaUsuario()
{
    var enlace = base_url + "Usuarios/Usuarios/cargarTablaUsuario";
    $('#tbl_usuario').DataTable({
        destroy: true,
        "aLengthMenu": [[5, 10, 50, -1], [5, 10, 50, "Todos"]],
        "iDisplayLength": 50,
        "ajax": {
            type: "POST",
            url: enlace,           
        },
    });
}
function eliminarUsuario(id)
{    
    // var rubro = $('#cbRubro').val();   
    if(confirm('¿Estas seguro de eliminar al usuario?'))
    {
        var enlace  = base_url + "Usuarios/Usuarios/eliminarUsuario";
        $.ajax({
            url: enlace,
            method: 'POST',
            data: {
                   id:id
                  },
            success: function (data) 
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
                        swal("¡Excelente!", 'SE REALIZÓ LA ELIMINACION CORRECTAMENTE', "success");                            
                        cargarTablaUsuarios();
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