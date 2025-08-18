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

function procedimientoCierreCuentasDeResultados()
{
    // eliminaMensajeError();
    // eliminaMensajeErrorCombos();
    // limpiarModalRegistro();
    // if($('#txtAccionComprobante').val()=== 'editar')
    // {
    //     $('#txtAccionComprobanteCuenta').val('editar');
    //     $('#txtAccionMovimiento').val('nuevo');
    //     id_comprobante = $('#id_comprobanteP').val();
    //     $('#id_comprobante').val(id_comprobante);
    // }
    // else
    // {
    //     $('#txtAccionComprobanteCuenta').val('nuevo');
    //     $('#txtAccionMovimiento').val('nuevo');
    // }
    // // alert (id_entidad);
    // $('#id_entidad_registro').val(id_entidad);
    // $('#nombreEntidad').text(nombre_entidad);
    // var tipo_cambio=$('#txtTipoCambio').val();
    // $('#tipoCambio').text(tipo_cambio);
    // $('#tipo_cambio_movimiento').val(tipo_cambio);
    cargarEstadoDeResultados();
    $('#modalRegistroCierreDeResultados').modal({backdrop: 'static', keyboard: false})
    $('#modalRegistroCierreDeResultados').modal('show');  
}
function cargarEstadoDeResultados()
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
        //     data: { id_entidad: id_entidad,
        //           fecha_inicio: fecha_inicio,
        //              fecha_fin: fecha_fin,
        //                 moneda: moneda,
        //                  nivel: nivel,
        //              saldoCero: saldoCero,
        //   cuentasSeleccionadas: cuentasSeleccionadas
        //           },

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
            // data: { id_entidad: id_entidad,
            //         fecha_inicio: fecha_inicio,
            //             fecha_fin: fecha_fin,
            //             moneda: moneda,
            //                 nivel: nivel,
            //             saldoCero: saldoCero,
            // cuentasSeleccionadas: cuentasSeleccionadas 
            //     },

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
    alert("Steph");  

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
                        }
                    });
                }
            });
        }
    });
}
