var base_url;

function baseurl(enlace) {
   base_url = enlace;
}

function cargarTablaPlanDeCuentas()
{
    var enlace = base_url + "Contabilidad/PlanDeCuentas/listarPlanDeCuentas";
    $('#tablaPlanDeCuentas').DataTable({
        destroy: true,
        responsive: true,
        "aLengthMenu": [[10, 20, 50, -1], [10, 20, 50, "Todos"]],
        "iDisplayLength": 40,
        "font-size":8,
        "ajax": {
            type: "POST",
            url: enlace
        },
        "columnDefs": [
        {
            targets: 0, 
            orderable: false, // <-- DESACTIVA ordenamiento
            width: "180px", 
            className: "text-center" 
        }
        
    ],
    "order": [] // <-- Desactiva orden inicial automática
    });

     cargarTipoMoneda();
}
function buscarTablaPlanDeCuentas()
{
    var enlace = base_url + "Contabilidad/PlanDeCuentas/buscarCuentas";
    $('#tablaPlanDeCuentas').DataTable({
        destroy: true,
        responsive: true,
        "aLengthMenu": [[10, 20, 50, -1], [10, 20, 50, "Todos"]],
        "iDisplayLength": 40,
        "font-size":8,
        "ajax": {
            type: "POST",
            url: enlace,
            data: function(d) {
                // Añadir los parámetros POST desde los filtros del formulario
                d.filtroNivel = $('#filtroNivel').val();
                d.filtroMayores = $('#filtroMayores').val();
                d.filtroSubCuentas = $('#filtroSubCuentas').val();
                d.filtroOtrasSubCuentas = $('#filtroOtrasSubCuentas').val();
                // campo de búsqueda opcional (ajusta el id si es otro)
                d.filtroBusqueda = $('#filtroBusqueda').val() || '';
            }
        },
        "columnDefs": [
        {
            targets: 0, 
            orderable: false, // <-- DESACTIVA ordenamiento
            width: "180px", 
            className: "text-center" 
        }
        
    ],
    "order": [] // <-- Desactiva orden inicial automática
    });

     cargarTipoMoneda();
}
function cargarFiltrosPlanDeCuentas()
{
    cargarNiveles();
    cargarMayores();
    // cargarSubCuentas();
}
$(function() {
    $('#filtroMayores').change(function(){
        var id_mayores   =  $(this).val();
        cargarSubCuentas(id_mayores);

    });
    $('#filtroSubCuentas').change(function(){
        var id_mayores   =  $('#filtroMayores').val();
        var id_subcuenta   =  $(this).val();
        
        cargarOtrasSubCuentas(id_mayores, id_subcuenta);

    });

});
function agregarCuentas()
{
    // eliminaMensajeError();
    // eliminaMensajeErrorCombos();
    $('#txtCodigoCuenta').val('');
    $('#txtSiglaCuenta').val('');
    $('#txtDescripcionCuenta').val('');
    $('#txtAccion').val('nuevo');
    $('#nivel').val(1);
    cargarTipoMoneda();
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
    $('#txtSigla').val('');
    $('#txtDescripcion').val('');
    $('#txtAccionSubCuenta').val('nuevo');
    var Cuenta=" "+codigo+"   "+nombreCuenta
    $('#nombreCuenta').text(Cuenta);
    $('#id_cuenta').val(id_cuenta);
    $('#nivel_padre').val(nivel);
    $('#id_padre').val(padre);
    $('#ruta').val(ruta);
    $('#tituloSubcuentas').text(Cuenta);
    $('#codigo_cuenta_padre').val(codigo+".");
    $('#modalPlanDeSubCuentas').modal({backdrop: 'static', keyboard: false})
    $('#modalPlanDeSubCuentas').modal('show');  
    cargarTipoMoneda();
    cargarTablaPlanDeSubCuentas(id_cuenta);
    // Espera a que se muestre el input antes de asignar prefijo
    var aux="";
    setTimeout(() => {
        cargarPrefijo(codigo+".",aux);
    }, 300); // ajusta si tu modal tarda más en mostrarse
}

function cargarTipoMoneda()
{
    var enlace = base_url + "Comunes/Comunes/cargarTipoMoneda";
    $.ajax({
        type: "GET",
        url: enlace,
        success: function(data) {
            $('#txtTipoMoneda').html(data);
            $('#txtTipoMoneda_editar').html(data);
        }
    });
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
                            var codigo_cuenta = $('#codigo_cuenta_padre').val();
                             $('#txtCodigo').val(codigo_cuenta);
                             $('#txtSigla').val('');
                             $('#txtDescripcion').val('');
                            cargarTablaPlanDeCuentas();
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
function editarCuentas(id_cuenta,codigo,sigla,descripcion,tipo_moneda_cuenta)
{
    // eliminaMensajeError();
    // eliminaMensajeErrorCombos();
    // $('#nivel').val();
    $('#txtAccion').val('editar');
    $('#idCuenta').val(id_cuenta);
    $('#txtCodigoCuenta').val(codigo);
    $('#txtSiglaCuenta').val(sigla);
   
    $('#txtTipoMoneda_editar option[value="'+tipo_moneda_cuenta+'"]').prop('selected','selected'); 
    $('#txtDescripcionCuenta').val(descripcion);
    $('#modalPlanDeCuentas').modal({backdrop: 'static', keyboard: false})
    $('#modalPlanDeCuentas').modal('show');  
    

}
function eliminarCuenta(id_cuenta)
{
    swal({
        title: 'ATENCIÓN',
        text: "¿Está seguro de Eliminar la cuenta ?",
        icon: 'warning',
        dangerMode: true,
        buttons: {
            cancel: "Cancelar",
            verificar: {
                text: "ELIMINAR",
                value: "verificar",
            }
        },
    })
    .then(respuesta => {
        if (respuesta)
        {
            let idHR= $("#idHojaruta").val();
            $.ajax({
                type: 'POST',
                url: base_url + "Externa/correspondenciaExterna/borraAnexo",
                data: {   id_anexo: id , id_hojaruta: idHR },
                dataType:'JSON', 
                success: function(data) {
                    if(data.resultado==1) 
                    {
                        swal({title: "",text: data.mensaje ,icon: "success",button: "OK",dangerMode:true });
                        eliminarFilaAnexo(obj);
                        $("#idAnexos").val(data.idAnexos);
                    }
                    else
                    {
                        swal({title: "Advertencia",text: data.mensaje ,icon: "warning",button: "OK" });
                    }
                }
            });
        }
    });
}
// cuentas auxiliares
let prefijoActual = "";

function cargarPrefijo(codigo,aux) {
    prefijoActual = codigo;

    // Detecta el input según aux o usa directamente el id
    let idInput = aux === "aux" ? "txtCodigoAux" : "txtCodigo";
    let input = document.getElementById(idInput);

    if (!input) {
        console.warn(`No se encontró el input ${idInput}`);
        return;
    }

    // if (!input) {
    //     console.warn("No se encontró el input txtCodigo");
    //     return;
    // }
    // else
    // {
    //     console.log("ENTRAAAA");
    // }

    // Coloca el prefijo y pone el cursor al final
    input.value = codigo;
    // input.textContent = codigo;
    setTimeout(() => {
        input.setSelectionRange(codigo.length, codigo.length);
        input.focus();
        // $('#txtCodigo').text('ddddd');
    }, 0);

    // Evita borrar el prefijo con teclas
    input.onkeydown = function(e) {
        const pos = input.selectionStart;

        // Bloquear retroceso o suprimir dentro del prefijo
        if ((e.key === "Backspace" || e.key === "Delete") && pos <= codigo.length) {
            e.preventDefault();
        }

        // Bloquear escritura dentro del prefijo
        if (pos < codigo.length && !['ArrowLeft', 'ArrowRight', 'Tab'].includes(e.key)) {
            e.preventDefault();
        }
    };

    // Evita pegar o modificar el prefijo
    input.oninput = function() {
        if (!input.value.startsWith(prefijoActual)) {
            input.value = prefijoActual;
        }
    };
}

function agregarCuentasAuxiliares(id_cuenta,codigo,sigla,descripcion)
{
    // eliminaMensajeError();
    // eliminaMensajeErrorCombos();
    // cargarPrefijo(codigo);
    $('#txtAccionAux').val('nuevo');
    var Cuenta=" "+codigo+"   "+descripcion
    $('#nombreCuentaAux').text(Cuenta);
    $('#tituloAuxiliares').text(Cuenta);
    $('#id_cuentaAux').val(id_cuenta);
    $('#txtCodigoCuenta').val(codigo);
    $('#txtSiglaCuenta').val(sigla);
    $('#txtDescripcionCuenta').val(descripcion);
    cargarTablaAuxiliaresPlanDeCuentas(id_cuenta);
    $('#modalPlanCuentasAuxiliares').modal({backdrop: 'static', keyboard: false})
    $('#modalPlanCuentasAuxiliares').modal('show');  

    var aux="aux";
    // Espera a que se muestre el input antes de asignar prefijo
    setTimeout(() => {
        cargarPrefijo(codigo+".",aux);
    }, 300); // ajusta si tu modal tarda más en mostrarse


}
function guardarAuxiliarPlanDeCuentas()
{

 swal({
        title: 'ATENCIÓN',
        text: "¿Está seguro de guardar el auxiliar de la cuenta?",
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
            var id_cuenta = $('#id_cuentaAux').val();
            var enlace = base_url + "Contabilidad/PlanDeCuentas/guardarAuxiliarPlanCuentas";
            var datos = $('#formularioPlanCuentasAuxiliar').serialize();
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
                            cargarTablaAuxiliaresPlanDeCuentas(id_cuenta);
                            // $("#modalPlanCuentasAuxiliares").modal('hide'); 
                        }
                    });
                }
            });


        }
    });
}
function cargarTablaAuxiliaresPlanDeCuentas(id_cuenta)
{
    var enlace = base_url + "Contabilidad/PlanDeCuentas/listarAuxiliaresPlanDeCuentas";
    $('#tablaAuxiliaresPlanDeCuentas').DataTable({
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
function eliminarAuxiliarCuenta(id_auxiliar_cuenta,id_cuenta)
{
    // var id_cuenta = $('#id_cuentaAux').val();
    swal({
        title: 'ATENCIÓN',
        text: "¿Está seguro de eliminar el auxiliar de la cuenta contable ?",
        icon: 'warning',
        dangerMode: true,
        buttons: {
            cancel: "Cancelar",
            verificar: {
                text: "ELIMINAR",
                value: "verificar",
            }
        },
    })
    .then(respuesta => {
        if (respuesta)
        {
            $.ajax({
                type: 'POST',
                url: base_url + "Contabilidad/PlanDeCuentas/eliminarAuxiliarCuenta",
                data: {   id_auxiliar_cuenta: id_auxiliar_cuenta },
                // dataType:'JSON', 
                success: function(data) {
                    var result =JSON.parse(data);
                    $.each(result,function(i,datos){
                        if(datos.resultado == 1)
                        {
                            swal({title: "",text: datos.mensaje ,icon: "success",button: "OK",dangerMode:true });
                            cargarTablaAuxiliaresPlanDeCuentas(id_cuenta);
                        }else{
                            swal({title: "Advertencia",text: datos.mensaje ,icon: "warning",button: "OK" });
                        }
                    });
                }
            });
        }
    });
}
/*FUNCIONES FILTROS DE BUSQUEDA PLAN DE CUENTAS */
function cargarNiveles()
{
    var enlace = base_url + "Comunes/Comunes/cargarNivelesPlanDeCuentas";
    $.ajax({
        type: "GET",
        url: enlace,
        success: function(data) {
            // alert (data);
            $('#filtroNivel').html(data);
        }
    });
}
function cargarMayores()
{
    var enlace = base_url + "Comunes/Comunes/cargarMayoresPlanDeCuentas";
    $.ajax({
        type: "GET",
        url: enlace,
        success: function(data) {
            // alert (data);
            $('#filtroMayores').html(data);
        }
    });
}
function cargarSubCuentas()
{
    var id_mayor = $('#filtroMayores').val();
    if(id_mayor=="" || id_mayor==null || id_mayor== -1){
        swal("ALERTA","Seleccione una cuenta mayor","warning");
        return;}
    else{
         var enlace = base_url + "Comunes/Comunes/cargarSubCuentasPlanDeCuentas";
        $.ajax({
            type: "POST",
            url: enlace,
            data: { id_mayor: id_mayor },
            success: function(data) {
                // alert (data);
                $('#filtroSubCuentas').html(data);
            }
        });
    }
   
}
function cargarOtrasSubCuentas()
{
    var id_mayor = $('#filtroMayores').val();
    var id_cuenta = $('#filtroSubCuentas').val();
    if(id_mayor=="" || id_mayor==null || id_mayor== -1){
        swal("ALERTA","Seleccione los filtros de las cuentas","warning");
        return;}
    else{
         var enlace = base_url + "Comunes/Comunes/cargarOtrasSubCuentasPlanDeCuentas";
        $.ajax({
            type: "POST",
            url: enlace,
            data: { id_mayor: id_mayor, id_cuenta: id_cuenta },
            success: function(data) {
                // alert (data);
                $('#filtroOtrasSubCuentas').html(data);
            }
        });
    }
}
function limpiarFiltros() {
    $('#filtroNivel').val('');
    $('#filtroMayores').val('');
    $('#filtroSubCuentas').val('');
    $('#filtroOtrasSubCuentas').val('');
    $('#filtroBusqueda').val('');
    swal({
        title: "¡Listo!",
        text: "Filtros limpiados",
        icon: "success",
        button: "OK"
    });
    cargarTablaPlanDeCuentas();
}

