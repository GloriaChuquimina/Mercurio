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
                cargarTablaCierresDeResultados(id_entidad);
            });
    $('#modalListaCuentas').on('hidden.bs.modal', function (e) {
        // alert('El modal se ha cerrado');
        $('#cuentaSeleccionada').show();
        // Aquí puedes ejecutar cualquier función adicional
        // seleccionDeCuentas();
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
    // $('#fechaCierreResultado').change(function(){
    //     fecha = $(this).val();
    //     id_entidad=$('#id_entidad_registro').val();
    //     cargarEstadoDeResultados(id_entidad,fecha);
    //     var enlace =  base_url + 'Contabilidad/Comprobante/getTipoCambio';
    //     $.ajax({
    //                 url: enlace,
    //                 type: 'POST',
    //                 data: { fecha: fecha },
    //                 success: function(response) {
    //                     var resultado = JSON.parse(response);
    //                     // alert(resultado.tipo_cambio_fecha); 
    //                     // var tipo_cambio = resultado.tipo_cambio_fecha; 

    //                     var tipo_cambio = parseFloat(resultado.tipo_cambio_fecha) || 0;

    //                     if (tipo_cambio <= 0) {
    //                         // Si no existe tipo de cambio
    //                         swal({
    //                             title: "Atención",
    //                             text: "No existe tipo de cambio registrado para la fecha seleccionada.",
    //                             icon: "warning",
    //                             button: "OK",
    //                             dangerMode: true,
    //                         });
    //                         $('#tipoCambio').val(tipo_cambio); // Limpia el campo
    //                         return; // Sale para no seguir validando
    //                     }                      
    //                 }
    //             });
    // });
    $('#fechaCierreResultado').change(function(){
            fecha = $(this).val();
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
            cargarEstadoDeResultados(id_entidad,fecha);
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
    // alert("STEPH");
    var cuentasSeleccionadas=$('#id_cuenta_seleccionadas').text();
    $('#opcionSeleccionar').checked = false;
    cargarCuentas(0,cuentasSeleccionadas);
    $('#modalListaCuentas').modal({backdrop: 'static', keyboard: false})
    $('#modalListaCuentas').modal('show');  
}
function cargarCuentas(){
    var cuentasSeleccionadas = $('#id_cuenta_seleccionadas').val();
    var enlace = base_url + "Contabilidad/Comprobante/listarPlanDeCuentasBusquedaComprobante";
    $('#tbl_CuentasContables').DataTable({
        destroy: true,
        "aLengthMenu": [[10, 20, 50, -1], [10, 20, 50, "Todos"]],
        "iDisplayLength": 10,
        "font-size":5,
        "ajax": {
            type: "POST",
            url: enlace
        },
    });
}

function procedimientoCierreCuentasDeResultados()
{
    
    $('#nombreEntidad').text(nombre_entidad);
	var id_entidad = $('#id_entidad').val();
    $('#id_entidad_registro').val(id_entidad);
	const hoy = new Date();
	const yyyy = hoy.getFullYear();
	const mm = String(hoy.getMonth() + 1).padStart(2, '0'); // Meses van de 0 a 11
	const dd = String(hoy.getDate()).padStart(2, '0');
	const fechaActual = `${yyyy}-${mm}-${dd}`;
	$('#fechaCierreResultado').val(fechaActual);

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

    cargarEstadoDeResultados(id_entidad,fechaActual);
    $('#modalRegistroCierreDeResultados').modal({backdrop: 'static', keyboard: false})
    $('#modalRegistroCierreDeResultados').modal('show');  
}
function cargarEstadoDeResultados(id_entidad,fecha)
{
    var enlace = base_url + "Contabilidad/CierreDeResultados/cargarDatosEstadoDeResultadosIngreso";
  $('#tablaDatosCuentasIngreso').DataTable({
        destroy: true,
        searching: false,
        paging: false,
        "aLengthMenu": [[5,10, 15,  -1], [7,10, 15,  "Todos"]],
        "iDisplayLength": 5,
        "ajax": {
            type: "POST",
            url: enlace,
            data: { id_entidad: id_entidad,
                  fecha_cierre: fecha
                  },

            dataSrc: function(json) {

                    
                        $('.txtTotalImporteIngreso').text(json.totalSaldoAcreedor);
                        $('.txtTotalImporteResultado1').text(json.totalResultado);
                        $('#cant_cuentas').val(json.nro_registros);
                        return json.data;
                    
                }
        },
    });
    var enlace = base_url + "Contabilidad/CierreDeResultados/cargarDatosEstadoDeResultadosEgreso";
    $('#tablaDatosCuentasEgreso').DataTable({
        destroy: true,
        searching: false,
        paging: false,
        "aLengthMenu": [[5,10, 15,  -1], [7,10, 15,  "Todos"]],
        "iDisplayLength": 5,
        "ajax": {
            type: "POST",
            url: enlace,
             data: { id_entidad: id_entidad,
                  fecha_cierre: fecha
                  },

            dataSrc: function(json) {

                    
                        $('.txtTotalImporteEgreso').text(json.totalSaldoDeudor);
                        $('.txtTotalImporteResultado2').text(json.totalResultado);
                        $('#cant_cuentas').val(json.nro_registros);
                        return json.data;
                    
                }
        },
    });
}
function cerrarCuentaDeResultados()
{
    // alert("Steph");  

    mensaje = "¿Confirma que desea registrar el cierre de cuentas de resultados? Tenga en cuenta que, una vez realizado, este proceso no podrá revertirse.";
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
            var enlace = base_url + "Contabilidad/CierreDeResultados/cerrarCuentaDeResultados";
            var datos = $('#formularioCierreResultados').serialize();
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
                            $("#modalRegistroCierreDeResultados").modal('hide'); 
							cargarTablaCierresDeResultados(datos.id_entidad);
                        }
                    });
                }
            });
        }
    });
}
function cargarTablaCierresDeResultados(id_entidad)
{
    var enlace = base_url + "Contabilidad/CierreDeResultados/cargarCierreDeResultados";
    $('#tablaCierreResultados').DataTable({
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
function busquedaIDCuenta(id_cuenta,cuenta)
{

    $('#id_cuenta').val( id_cuenta) ;
    $('#txtCuenta').val( cuenta) ;
    $('#modalListaCuentas').modal('hide'); 
    $('#id_cuenta_auxiliar').val('-');
    $('#txtAuxiliarCuenta').val('-');
    $('#id_cuenta_auxiliar').text('-');
    $('#txtAuxiliarCuenta').text('-');
    //  cargarCuentasAuxiliaresLista(id_cuenta); 
}
