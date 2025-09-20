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
                cargarTablaCierresDeBalance(id_entidad);
            });
    $('#modalListaCuentas').on('hidden.bs.modal', function (e) {
        // alert('El modal se ha cerrado');
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


    /*CIERRE*/
    // $('#fechaCierreBalance').change(function(){
    //     fecha = $(this).val();
    //     id_entidad=$('#id_entidad_registro').val();
    //     cargarDatosBalanceGeneral(id_entidad,fecha);
    // });

    // //  $('#fechaCierreBalance').change(function(){
    // //         fecha = $(this).val();
    // //         id_entidad=$('#id_entidad_registro').val();            
    // //         var enlace =  base_url + 'Contabilidad/Comprobante/getTipoCambio';
    // //         $.ajax({
    // //                  url: enlace,
    // //                 type: 'POST',
    // //                 data: { fecha: fecha },
    // //                 success: function(response) {
    // //                     var resultado = JSON.parse(response);
    // //                     // alert(resultado.tipo_cambio_fecha); 
    // //                     // var tipo_cambio = resultado.tipo_cambio_fecha; 

    // //                     var tipo_cambio = parseFloat(resultado.tipo_cambio_fecha) || 0;

    // //                     if (tipo_cambio <= 0) {
    // //                         // Si no existe tipo de cambio
    // //                         swal({
    // //                             title: "Atención",
    // //                             text: "No existe tipo de cambio registrado para la fecha seleccionada.",
    // //                             icon: "warning",
    // //                             button: "OK",
    // //                             dangerMode: true,
    // //                         });
    // //                         $('#tipoCambio').text(''); // Limpia el campo
    // //                         $('#btnRecalcularTipoCambio').hide();
    // //                         return; // Sale para no seguir validando
    // //                     }
    // //                     else
    // //                     {
    // //                         // swal({
    // //                         //     title: "Atención",
    // //                         //     text: "tipo cambio",
    // //                         //     icon: "success",
    // //                         //     button: "OK",
    // //                         //     dangerMode: true,
    // //                         // });
    // //                          $('#tipoCambio').text(tipo_cambio);
    // //                     }
    // //                 }
    // //         });
    // //         cargarDatosBalanceGeneral(id_entidad,fecha);
    // // });
    console.log('fechaCierreBalance existe?', $('#fechaCierreBalance').length);
    // $('#fechaCierreResultado').on('blur', function(){
// //    $(document).on('focusout', '#fechaCierreResultado', function(){
// //         alert("STEPH");
// //         validarFecha($(this).val());
// //     });

    // Captura cuando el usuario sale del campo #fechaCierreResultado
    $(document).on('focusout', '#fechaCierreBalance', function () {
        let fecha = $(this).val();

        if (!fecha) {
            Swal.fire({
                icon: 'warning',
                title: 'Atención',
                text: 'Debe ingresar una fecha antes de continuar',
            });
        } else {
            validarFecha(fecha);
        }
    });


   
});
function validarFecha(fecha){
    id_entidad=$('#id_entidad_registro').val();            
    var enlace =  base_url + 'Contabilidad/Comprobante/getTipoCambio';
    $.ajax({
                url: enlace,
            type: 'POST',
            data: { fecha: fecha },
            success: function(response) {
                var resultado = JSON.parse(response);
                // alert(resultado.tipo_cambio_fecha); 
                // var tipo_cambio = resultado.tipo_cambio_fecha; 

                var tipo_cambio = parseFloat(resultado.tipo_cambio_fecha) || 0;

                if (tipo_cambio <= 0) {
                    // Si no existe tipo de cambio
                    swal({
                        title: "Atención",
                        text: "No existe tipo de cambio registrado para la fecha seleccionada.",
                        icon: "warning",
                        button: "OK",
                        dangerMode: true,
                    });
                    $('#tipoCambio').text(''); // Limpia el campo
                    $('#btnRecalcularTipoCambio').hide();
                    return; // Sale para no seguir validando
                }
                else
                {
                    // swal({
                    //     title: "Atención",
                    //     text: "tipo cambio",
                    //     icon: "success",
                    //     button: "OK",
                    //     dangerMode: true,
                    // });
                        $('#tipoCambio').text(tipo_cambio);
                }
            }
    });
    cargarDatosBalanceGeneral(id_entidad,fecha);
}

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
    // alert("STEPH");
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
function cargarDatosBalanceGeneral(id_entidad,fecha){
    // alert("STEPH");  
    var cierre =true;
    var enlace = base_url + "Contabilidad/CierreDeBalance/cargarDatosBalanceGeneral";
    $('#tablaBalanceGeneralCierre').DataTable({
        destroy: true,
        "aLengthMenu": [[10, 20, 50, -1], [10, 20, 50, "Todos"]],
        "iDisplayLength": 10,
        "font-size":5,
        "ajax": {
            type: "POST",
            url: enlace,
            data:{          
                id_entidad:id_entidad,
                fecha_cierre:fecha,
                cierre:cierre
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

// FUNCIONES PARA EL CIERRE DE BALANCE
function procedimientoCierreCuentasDeBalance()
{
    $('#nombreEntidad').text(nombre_entidad);
	var id_entidad = $('#id_entidad').val();
    $('#id_entidad_registro').val(id_entidad);
	const hoy = new Date();
	const yyyy = hoy.getFullYear();
	const mm = String(hoy.getMonth() + 1).padStart(2, '0'); // Meses van de 0 a 11
	const dd = String(hoy.getDate()).padStart(2, '0');
	const fechaActual = `${yyyy}-${mm}-${dd}`;
	$('#fechaCierreBalance').val(fechaActual);

    /*TIPO DE CAMBIO */

    var enlace =  base_url + 'Contabilidad/Comprobante/getTipoCambio';
    $.ajax({
            url: enlace,
            type: 'POST',
            data: { fecha: fechaActual },
            success: function(response) {
                var resultado = JSON.parse(response);
                // alert(resultado.tipo_cambio_fecha); 
                // var tipo_cambio = resultado.tipo_cambio_fecha; 

                var tipo_cambio = parseFloat(resultado.tipo_cambio_fecha) || 0;

                if (tipo_cambio <= 0) {
                    // Si no existe tipo de cambio
                    swal({
                        title: "Atención",
                        text: "No existe tipo de cambio registrado para la fecha seleccionada.",
                        icon: "warning",
                        button: "OK",
                        dangerMode: true,
                    });
                    $('#tipoCambio').text(''); // Limpia el campo
                    $('#btnRecalcularTipoCambio').hide();
                    return; // Sale para no seguir validando
                }
                else
                {
                    // swal({
                    //     title: "Atención",
                    //     text: "tipo cambio",
                    //     icon: "success",
                    //     button: "OK",
                    //     dangerMode: true,
                    // });
                        $('#tipoCambio').text(tipo_cambio);
                }
            }
    });
    /*TIPO DE CAMBIO */


    cargarDatosBalanceGeneral(id_entidad,fechaActual);
    $('#modalRegistroCierreDeBalance').modal({backdrop: 'static', keyboard: false})
    $('#modalRegistroCierreDeBalance').modal('show');  
}
function cerrarCuentaDeBalance()
{
    // alert("Steph");  

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
                            cargarDatosBalanceGeneral(datos.id_entidad,datos.fecha);
                        }
                    });
                }
            });
        }
    });
}

function cargarTablaCierresDeBalance(id_entidad)
{
    var enlace = base_url + "Contabilidad/CierreDeBalance/cargarCierres";
    $('#tablaCierresDeBalance').DataTable({
        destroy: true,
        "aLengthMenu": [[10, 20, 50, -1], [10, 20, 50, "Todos"]],
        "iDisplayLength": 40,
        "font-size":8,
        "ajax": {
            type: "POST",
            url: enlace,
            data: { id_entidad: id_entidad 
            },         
        },   
    });
}



