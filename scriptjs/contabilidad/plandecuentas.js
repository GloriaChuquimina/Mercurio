var base_url;

function baseurl(enlace) {
   base_url = enlace;
}

function cargarTablaPlanDeCuentas()
{
    var enlace = base_url + "Contabilidad/PlanDeCuentas/listarPlanDeCuentas";
    $('#tablaPlanDeCuentas').DataTable({
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
function agregarCuentas()
{
    // eliminaMensajeError();
    // eliminaMensajeErrorCombos();
    $('#txtAccion').val('nuevo');
    $('#nivel').val(1);
    $('#modalPlanDeCuentas').modal({backdrop: 'static', keyboard: false})
    $('#modalPlanDeCuentas').modal('show');  
}

 function guardarPlanDeCuentas()
{

    var accion  = $('#txtAccion').val();
    var mensaje ="";
    var boton ="";

    if(accion == 'nuevo'){
        mensaje = "¿Está seguro de registrar el Plan de Cuenta?";
        boton   = "Guardar";
    }
    else{
        mensaje = "¿Está seguro de modificar el Plan de Cuenta?"
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

            var enlace = base_url + "Contabilidad/PlanDeCuentas/guardarPlanDeCuentas";
            var datos = $('#formularioPlanDeCuentas').serialize();
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
                            cargarTablaPlanDeCuentas();
                            $("#modalPlanDeCuentas").modal('hide'); 
                        }
                    });
                }
            });
        }
    });



 
}
function agregarSubCuentas(id_cuenta,codigo,nombreCuenta,nivel,padre,ruta)
{
    // eliminaMensajeError();
    // eliminaMensajeErrorCombos();
    $('#txtAccionSubCuenta').val('nuevo');
    var Cuenta=" "+codigo+"   "+nombreCuenta
    $('#nombreCuenta').text(Cuenta);
    $('#id_cuenta').val(id_cuenta);
    $('#nivel_padre').val(nivel);
    $('#id_padre').val(padre);
    $('#ruta').val(ruta);
    $('#modalPlanDeSubCuentas').modal({backdrop: 'static', keyboard: false})
    $('#modalPlanDeSubCuentas').modal('show');  
    cargarTablaPlanDeSubCuentas(id_cuenta)
}

function guardarPlanDeSubCuentas()
{

 swal({
        title: 'ATENCIÓN',
        text: "¿Está seguro de guardar la cuenta?",
        icon: 'warning',
        dangerMode: true,
        buttons: {
            cancel: "Cancelar",
            verificar: {
                text: "GUARDAR",
                value: "verificar",
            }
        },
    })
    .then(respuesta => {
        if (respuesta)
        {
            var id_cuenta = $('#id_cuenta').val();
            var enlace = base_url + "Contabilidad/PlanDeCuentas/guardarPlanDeSubCuentas";
            var datos = $('#formularioPlanDeSubCuentas').serialize();
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
                            // swal("!Excelente!","SE REGISTRO CORRECTAMENTE,"success");
                            cargarTablaPlanDeSubCuentas(id_cuenta);
                            $("#modalPlanDeCuentas").modal('hide'); 
                        }
                    });
                }
            });


        }
    });
}
function cargarTablaPlanDeSubCuentas(id_cuenta)
{
    var enlace = base_url + "Contabilidad/PlanDeCuentas/listarPlanDeSubCuentas";
    $('#tablaPlanDeSubCuentas').DataTable({
        destroy: true,
        "aLengthMenu": [[10, 20, 50, -1], [10, 20, 50, "Todos"]],
        "iDisplayLength": 10,
        "font-size":8,
        "ajax": {
            type: "POST",
            url: enlace,
            data:{id_cuenta:id_cuenta}
        },
    });
}
function editarCuentas(id_cuenta,codigo,sigla,descripcion)
{
    // eliminaMensajeError();
    // eliminaMensajeErrorCombos();
    // $('#nivel').val();
    $('#txtAccion').val('editar');
    $('#idCuenta').val(id_cuenta);
    $('#txtCodigoCuenta').val(codigo);
    $('#txtSiglaCuenta').val(sigla);
    $('#txtDescripcionCuenta').val(descripcion);
    $('#modalPlanDeCuentas').modal({backdrop: 'static', keyboard: false})
    $('#modalPlanDeCuentas').modal('show');  
}