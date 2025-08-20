var base_url;
var id_entidad;
function baseurl(enlace) {
   base_url = enlace;
}
function cargarCombos()
{
    var enlace = base_url + "Comunes/Comunes/cargarEntidad";
    $.ajax({
        type: "GET",
        url: enlace,
        success: function(data) {
            $('#entidades').html(data);
            // valoresIniciales();
        }
    }); 

    var enlace = base_url + "Comunes/Comunes/cargarTipoMoneda";
    $.ajax({
        type: "GET",
        url: enlace,
        success: function(data) {
            $('#tipo_moneda').html(data);
        }
    });
}
$(function (){

    $('#entidades').change(function(){
                id_entidad = $(this).val();
                nombre_entidad = $('#entidades option:selected').text();
                $('#nombre_entidad').text(nombre_entidad);
                $('#id_entidad').val(id_entidad);
                $('#cardEntidad').find('[data-card-widget="collapse"]').click();
            });
    $('#modalListaCuentas').on('hidden.bs.modal', function (e) {
        alert('El modal se ha cerrado');
        $('#cuentaSeleccionada').show();
        seleccionDeCuentas();
        // Aquí puedes ejecutar cualquier función adicional
    });
    $('#opcionSeleccionar').click (function ()
    {
        if( $('#opcionSeleccionar').prop('checked') ) 
        {
            marcar = 1;
        }
        else
        {
           marcar = 0;
           $("#cantidadSolicitudes").html('0');
        }
        // var id_entidad = $('#cbEntidades').val();
        cargarCuentas(marcar);
    });
    // Al seleccionar "Al"
    $('#fechaAl').prop('disabled', false);
    $('#fechaDesde, #fechaHasta').prop('disabled', true);
    $('#radioAl').on('change', function () {
        if ($(this).is(':checked')) {
            $('#fechaAl').prop('disabled', false);
            $('#fechaDesde, #fechaHasta').prop('disabled', true);
        }
    });

    // Al seleccionar "Entre el"
    $('#radioEntre').on('change', function () {
        if ($(this).is(':checked')) {
            $('#fechaAl').prop('disabled', true);
            $('#fechaDesde, #fechaHasta').prop('disabled', false);
        }
    });

    $('#nivel').on('input', function () {
        if (this.value < 0) {
            this.value = 0; // Si es menor que 0, lo ajusta a 0
        }
    });
   
});

function seleccionDeCuentas()
{

    // Obtener todos los checkboxes seleccionados del DataTable, no solo los visibles
    var table = $('#tbl_CuentasContables').DataTable();

    // Array para guardar los inputs seleccionados manualmente
    var inputs = [];

    // Recorremos todas las filas (incluso las que no están en el DOM)
    table.$('input[type="checkbox"]:checked').each(function () {
        var name = $(this).attr('name');
        var value = $(this).val();

        // Asegurarse de que tiene un name (para serializar)
        if (name) {
            inputs.push($('<input>').attr('type', 'hidden').attr('name', name).val(value));
        }
    });

    // Clonamos el formulario para no modificar el original
    var form = $('#formListaCuentas').clone();

    // Añadimos los inputs ocultos
    $.each(inputs, function (i, input) {
        form.append(input);
    });

    var enlace = base_url + "Contabilidad/LibroMayor/seleccionDeCuentas";
    // var datos  = $('#formListaCuentas').serialize();
    $.ajax({
        type: "POST",
        url: enlace,
        // data: datos,
        data:form.serialize(),
        dataType:'JSON',
        success: function(data)
        {
            // var result = JSON.parse(data);
            if(data.totalCuentas > 1)
            {
                swal({title: "ALERTA",text: data.mensaje ,icon: "warning",button: "OK",dangerMode:true,});
                $('#id_cuenta_seleccionadas').val(data.cuentas);
                $('#cuentas').text(data.cuentasLiteral);
            }
            else
            {
                if(data.totalCuentas == 1)
                {
                    swal({title: "EXITO",text: data.mensaje ,icon: "success",button: "OK",dangerMode:true,});
                    $('#id_cuenta').val(data.id_cuenta);
                }
                else
                {
                    swal({title: "ERROR",text: "No se encontraron cuentas seleccionadas",icon: "error",button: "OK",dangerMode:true,});
                    $('#id_cuenta_seleccionadas').val("");
                    $('#cuentas').text("");
                }
            }
        }
    });
}
function listaCuentasBusqueda()
{
    alert("STEPH");
    var cuentasSeleccionadas=$('#id_cuenta_seleccionadas').text();
    $('#opcionSeleccionar').checked = false;
    cargarCuentas(0,cuentasSeleccionadas);
    $('#modalListaCuentas').modal({backdrop: 'static', keyboard: false})
    $('#modalListaCuentas').modal('show');  
}
function cargarCuentas(marcar){
    var cuentasSeleccionadas = $('#id_cuenta_seleccionadas').val();
    var enlace = base_url + "Contabilidad/LibroMayor/listarPlanDeCuentasBusqueda";
    $('#tbl_CuentasContables').DataTable({
        destroy: true,
        "aLengthMenu": [[10, 20, 50, -1], [10, 20, 50, "Todos"]],
        "iDisplayLength": 10,
        "font-size":5,
        "ajax": {
            type: "POST",
            url: enlace,
            data:{          
                marcareg:marcar,
                cuentasSeleccionadas:cuentasSeleccionadas
            }
        },
    });
}
function cargarDatosBalanceGeneral(){
    // alert("STEPH");
    var id_entidad = $('#id_entidad').val();
    var cuentasSeleccionadas = $('#id_cuenta_seleccionadas').val();
    var fecha_desde = $('#fechaDesde').val();
    var fecha_hasta = $('#fechaHasta').val();
    var fecha_al     = $('#fechaAl').val();  
    var idSeleccionado = $('input[name="customRadio2"]:checked').attr('id');
    var valorCheckCero    = $('input[name="saldoCero"]').is(':checked');
    var moneda         = $('#tipo_moneda').val();
    var nivel          = $('#nivel').val();
    if(nivel == null || nivel.length === 0 || nivel <= 0)
    {
        nivel= 0;
    }
    var enlace = base_url + "Contabilidad/BalanceGeneral/cargarDatosBalanceGeneral";
    $('#tablaBalanceGeneral').DataTable({
        destroy: true,
        "aLengthMenu": [[10, 20, 50, -1], [10, 20, 50, "Todos"]],
        "iDisplayLength": 50,
        "font-size":5,
        "ajax": {
            type: "POST",
            url: enlace,
            data:{          
                id_entidad:id_entidad,
                cuentasSeleccionadas:cuentasSeleccionadas,
                fecha_desde:fecha_desde,
                fecha_hasta:fecha_hasta,
                fecha_al:fecha_al,
                idSeleccionado:idSeleccionado,
                valorCheckCero:valorCheckCero,
                moneda:moneda,
                nivel:nivel
            },
            dataSrc: function(json) {
                $('.txtTotalImporteActivo').text(json.totalimporteActivo);
                $('.txtTotalImportePasivoPatrimonio').text(json.totalimportePasivoPatrimonio);
                $('.txtTotalImporteCuentasOrdenDeudoras').text(json.totalimporteCuentasOrdenDeudoras);
                $('.txtTotalImporteCuentasOrdenAcreedoras').text(json.totalimporteCuentasOrdenAcreedoras);
                $('#cant_cuentas').val(json.recordsTotal);
                return json.data;
            }
        },
         columnDefs: [
            { 
                targets: [2, 3, 4], 
                className: 'text-end' 
            } // columnas 2, 3 y 4 alineadas a la derecha
         ]
    });
}
// function ReporteBalanceGeneralPDF1()
// {
//     var id_entidad   = $('#id_entidad').val();
//     var cuentas      = $('#id_cuenta_seleccionadas').val();
//     if (cuentas == null || cuentas.length === 0) {
// 		cuentas = '0'; // o algún valor por defecto
// 	} 
//     var fecha_al     = $('#fechaAl').val();  
//     var fecha_inicio = $('#fechaDesde').val();
//     var fecha_fin    = $('#fechaHasta').val();

//     // var alactivo     = $('input[name="radioAl"]:checked').val();
//     // var rangoactivo  = $('input[name="radioEntre"]:checked').val();

//     var idSeleccionado = $('input[name="customRadio2"]:checked').attr('id');
//     // var valorCheckCero = $('input[name="saldoCero"]:checked').val();
//     var valorCheckCero    = $('input[name="saldoCero"]').is(':checked');
//     var moneda         = $('#tipo_moneda').val();
//     var nivel          = $('#nivel').val();
    
//     var mensaje      ="";
//     var sw=0;
//     // alert(id_entidad);

//     if(nivel == null || nivel.length === 0 || nivel <= 0)
//     {
//         nivel= 0;
//     }
    
//     if(id_entidad == null || id_entidad.length === 0){
//         // alert("SELECCIONE UNA ENTIDAD POR FAVOR");
//         var mensaje ="SELECCIONE UNA ENTIDAD POR FAVOR";
//         swal({title: "ERROR",text: mensaje,icon: "error",button: "OK",dangerMode:true,});
//         return;
//     }
//     else
//     {
//         sw=0;
//         if(idSeleccionado == 'radioAl')
//         {
//             if(fecha_al == null || fecha_al.length === 0){
//                fecha_inicio='01/01/1900';
//                 fecha_fin='01/01/1900';
//                 mensaje = "SELECCIONE UNA FECHA VÁLIDA POR FAVOR";
//                 sw=1;
//             }
//             else
//             {
//                 fecha_inicio=fecha_al;
//                 fecha_fin=fecha_al;
//             }
//         }
//         else if(idSeleccionado == 'radioEntre')
//         {
//             if((fecha_inicio=='' && fecha_fin =='')|| (fecha_inicio.length === 0 && fecha_fin.length === 0)){
                
//                 mensaje = "SELECCIONE UN RANGO DE FECHA VÁLIDO POR FAVOR";
//                 sw=1;
//             }
//             else
//             {
//                 fecha_al=fecha_inicio;
//             }
//         }
//         if(sw==0)
//         {
//             $('#divPDF').html('');
//             var iframe = document.createElement("iframe");
//                 iframe.width = '100%';
//                 iframe.height = '700px';
//                 iframe.src = base_url+'Contabilidad/BalanceGeneral/ReporteBalanceGeneralPDF_1/'+id_entidad+"/"+cuentas+"/"+fecha_inicio+"/"+fecha_fin+"/"+valorCheckCero+"/"+idSeleccionado+"/"+fecha_al+"/"+nivel+"/"+moneda;

//                 $('#divPDF').append(iframe);
//             $('#divCapa').addClass('overlay');    
//             $('#pdfModal > .modal-dialog ').parent().css('z-index', 1999);
//             $('#pdfModal > .modal-dialog ').css("max-width","75%"); 
//             $('#pdfModal').show();   
            
//         }
//         else
//         {
//             // alert("SELECCIONE UN RANGO DE FECHA VÁLIDA POR FAVOR");
//             // var mensaje ="SELECCIONE UN RANGO DE FECHA VÁLIDA POR FAVOR";
//             swal({title: "ERROR",text: mensaje,icon: "error",button: "OK",dangerMode:true,});
//             return;
//         }
//     }
    
// }
// function ReporteBalanceGeneralPDF2()
// {
//     var id_entidad   = $('#id_entidad').val();
//     var cuentas      = $('#id_cuenta_seleccionadas').val();
//     if (cuentas == null || cuentas.length === 0) {
// 		cuentas = '0'; // o algún valor por defecto
// 	} 
//     var fecha_al     = $('#fechaAl').val();  
//     var fecha_inicio = $('#fechaDesde').val();
//     var fecha_fin    = $('#fechaHasta').val();

//     // var alactivo     = $('input[name="radioAl"]:checked').val();
//     // var rangoactivo  = $('input[name="radioEntre"]:checked').val();

//     var idSeleccionado = $('input[name="customRadio2"]:checked').attr('id');
//     // var valorCheckCero = $('input[name="saldoCero"]:checked').val();
//     var valorCheckCero    = $('input[name="saldoCero"]').is(':checked');
//     var moneda         = $('#tipo_moneda').val();
//     var nivel          = $('#nivel').val();
    
//     var mensaje      ="";
//     var sw=0;
//     // alert(id_entidad);

//     if(nivel == null || nivel.length === 0 || nivel <= 0)
//     {
//         nivel= 0;
//     }
    
//     if(id_entidad == null || id_entidad.length === 0){
//         // alert("SELECCIONE UNA ENTIDAD POR FAVOR");
//         var mensaje ="SELECCIONE UNA ENTIDAD POR FAVOR";
//         swal({title: "ERROR",text: mensaje,icon: "error",button: "OK",dangerMode:true,});
//         return;
//     }
//     else
//     {
//         sw=0;
//         if(idSeleccionado == 'radioAl')
//         {
//             if(fecha_al == null || fecha_al.length === 0){
//                fecha_inicio='01/01/1900';
//                 fecha_fin='01/01/1900';
//                 mensaje = "SELECCIONE UNA FECHA VÁLIDA POR FAVOR";
//                 sw=1;
//             }
//             else
//             {
//                 fecha_inicio=fecha_al;
//                 fecha_fin=fecha_al;
//             }
//         }
//         else if(idSeleccionado == 'radioEntre')
//         {
//             if((fecha_inicio=='' && fecha_fin =='')|| (fecha_inicio.length === 0 && fecha_fin.length === 0)){
                
//                 mensaje = "SELECCIONE UN RANGO DE FECHA VÁLIDO POR FAVOR";
//                 sw=1;
//             }
//             else
//             {
//                 fecha_al=fecha_inicio;
//             }
//         }
//         if(sw==0)
//         {
//             $('#divPDF').html('');
//             var iframe = document.createElement("iframe");
//                 iframe.width = '100%';
//                 iframe.height = '700px';
//                 iframe.src = base_url+'Contabilidad/BalanceGeneral/ReporteBalanceGeneralPDF_2/'+id_entidad+"/"+cuentas+"/"+fecha_inicio+"/"+fecha_fin+"/"+valorCheckCero+"/"+idSeleccionado+"/"+fecha_al+"/"+nivel+"/"+moneda;

//                 $('#divPDF').append(iframe);
//             $('#divCapa').addClass('overlay');    
//             $('#pdfModal > .modal-dialog ').parent().css('z-index', 1999);
//             $('#pdfModal > .modal-dialog ').css("max-width","75%"); 
//             $('#pdfModal').show();   
            
//         }
//         else
//         {
//             // alert("SELECCIONE UN RANGO DE FECHA VÁLIDA POR FAVOR");
//             // var mensaje ="SELECCIONE UN RANGO DE FECHA VÁLIDA POR FAVOR";
//             swal({title: "ERROR",text: mensaje,icon: "error",button: "OK",dangerMode:true,});
//             return;
//         }
//     }
    
// }

// FUNCIONES PARA EL CIERRE DE BALANCE
function procedimientoCierreCuentasDeBalance()
{
    
    // cargarDatosBalanceGeneral();
    $('#modalRegistroCierreDeBalance').modal({backdrop: 'static', keyboard: false})
    $('#modalRegistroCierreDeBalance').modal('show');  
}
function cerrarCuentaDeBalance()
{
    alert("Steph");  

    mensaje = "¿Confirma que desea registrar el cierre de cuentas de balance? Tenga en cuenta que, una vez realizado, este proceso no podrá revertirse.";
    boton   = "Guardar";

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

            // var enlace = base_url + "Entidades/Entidades/guardarEntidad";
            var enlace = base_url + "Contabilidad/CierreDeBalance/cerrarCuentasDeBalance";
            var datos = $('#formularioCierreDeBalance').serialize();
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
                            // cargarTablaEntidades();
                            $("#modalRegistroCierreDeBalance").modal('hide'); 
                        }
                    });
                }
            });
        }
    });
}



