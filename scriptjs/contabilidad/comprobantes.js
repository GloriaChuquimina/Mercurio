var base_url;
var id_entidad;
var nombre_entidad;
var accion;
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
        }
    });

    var enlace = base_url + "Comunes/Comunes/cargarTipoComprobante";
    $.ajax({
        type: "GET",
        url: enlace,
        success: function(data) {
            $('#txtTipo').html(data);
        }
    });
    var enlace = base_url + "Comunes/Comunes/cargarTipoMovimiento";
    $.ajax({
        type: "GET",
        url: enlace,
        success: function(data) {
            $('#txtTipoMovimiento').html(data);
        }
    });

}
function cargarCuentas(){

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
$(function (){

    $('#txtCuenta' ).on({
                'change': function(event) {
                    $('#idCuenta').val('');
                    var target = event.target.value;
                    var datalist = document.getElementById('listaCuentas').childNodes;
                    for (var i = 0; i < datalist.length; i++) 
                    {                 
                        if (datalist[i].value === target) {
                            $('#id_cuenta').val( datalist[i].dataset.value) ;

                            break;
                        }
                    }
                },
                'blur':  function(event) {
                    $('#idCuenta').val('');
                    var target = event.target.value;
                    var datalist = document.getElementById('listaCuentas').childNodes;
                    for (var i = 0; i < datalist.length; i++) 
                    {     
                        if (datalist[i].value  == target) {
                            $('#id_cuenta').val( datalist[i].dataset.value) ;
                            // $('#txtCodigo').val( datalist[i].dataset.value) ;
                            break;
                        }
                    }
                }

        });

        $('#entidades').change(function(){
            id_entidad = $(this).val();
            // alert(id_entidad);
            nombre_entidad = $('#entidades option:selected').text();
            $('#nombre_entidad').text(nombre_entidad);
            cargarTablaComprobantesEntidades(id_entidad);
        });
        /* Valida  numeros en los textos */ 
        $("#txtImporte").keypress(function (e) {
            var keyCode = e.keyCode || e.which;
            if (event.which && (event.which < 46 || event.which > 57 || event.which == 47) && event.keyCode != 8) {
                    event.preventDefault();
            }
        });

});

function busquedaIDCuenta(id_cuenta,cuenta)
{

     $('#id_cuenta').val( id_cuenta) ;
     $('#txtCuenta').val( cuenta) ;
     $('#modalListaCuentas').modal('hide');  
}

function cargarTablaComprobantesEntidades(id_entidad)
{
    var enlace = base_url + "Contabilidad/Comprobante/cargarComprobantesByEntidad";
    $('#tablaComprobantesEntidades').DataTable({
        destroy: true,
        "aLengthMenu": [[10, 20, 50, -1], [10, 20, 50, "Todos"]],
        "iDisplayLength": 40,
        "font-size":8,
        "ajax": {
            type: "POST",
            url: enlace,
            data: { id_entidad: id_entidad }
        },
    });
}
function cargarTablaComprobantes()
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
function agregarComprobante()
{
    var entidad =$('#entidades').val();
    window.location.href = base_url + "Contabilidad/Comprobante/registroComprobante/"+entidad;
}
function agregarRegistroComprobante()
{
    eliminaMensajeError();
    eliminaMensajeErrorCombos();
    limpiarModalRegistro();
    $('#txtAccionMovimiento').val('nuevo');
    // alert (id_entidad);
    $('#id_entidad_registro').val(id_entidad);
    $('#nombreEntidad').text(nombre_entidad);
    var tipo_cambio=$('#txtTipoCambio').val();
    $('#tipoCambio').text(tipo_cambio);
    $('#tipo_cambio_movimiento').text(tipo_cambio);
    $('#modalRegistroMovimiento').modal({backdrop: 'static', keyboard: false})
    $('#modalRegistroMovimiento').modal('show');  
}
function guardarRegistroCuenta()
{
    var accion                    = $('#txtAccionMovimiento').val();
    var id_entidad                = $('#id_entidad_registro').val();
    var id_cuenta                 = $('#id_cuenta').val();
    var cuenta                    = $('#txtCuenta').val();
    var tipo_movimiento           = $('#txtTipoMovimiento').val();
    var tipo_movimiento_literal   = $('#txtTipoMovimiento option:selected').text();
    var importe                   = $('#txtImporte').val();
    var importeFormato            = importe.split(",").join("");
    var tipo_cambio               = $('#txtTipoCambio').val();
    var glosa_cuenta              = $('#txtGlosaCuenta').val();

    var cadRegistroCuenta         =  $('#registroCuentaT').val();


    var enlace = base_url + "Contabilidad/Comprobante/validarDatosRegistroCuenta";
    var datos = $('#formularioRegistroCuenta').serialize();
    $.ajax({
                type:"POST",
                url:enlace,
                data:datos,
                success:function(data)
                {
                    // alert(data);
                    var result =JSON.parse(data);
                    $.each(result,function(i,datos){
                        if(datos.resultado == 0)
                        {
                            visualizarValidaciones(datos.mensaje);
                            swal({title:"ALERTA",text:"Existen Observaciones.",icon:"warning",button:"OK",dangerMode:true});
                        }
						else
                        {
                            
                            /****************************************************** */
                            // quitarComaNumeroDecimal1();

                            if(accion == 'nuevo')
                            {
                                var cadRegistroCuentaT = cadRegistroCuenta+"*"+id_cuenta+"*"+cuenta+"*"+tipo_movimiento+"*"+tipo_movimiento_literal+"*"+importeFormato+"*"+tipo_cambio+"*"+glosa_cuenta+"|";
                                $('#registroCuentaT').val(cadRegistroCuentaT);
                                var enlace = base_url + "Contabilidad/Comprobante/cargarTablaRegistroCuenta";
                                $('#tablaRegistroCuenta').DataTable({
                                    destroy: true,
                                    searching: true,
                                    fixedHeader: true,
                                    scrollY: '300px',   // Altura del contenedor visible
                                    scrollCollapse: true,
                                    paging: false,
                                    "aLengthMenu": [[5,10, 15,  -1], [7,10, 15,  "Todos"]],
                                    "iDisplayLength": 5,
                                    "ajax": {
                                        type: "POST",
                                        url: enlace,
                                        data: { accion: accion, 
                                                cuenta: cadRegistroCuentaT, 
                                                id_entidad: id_entidad
                                            },

                                        dataSrc: function(json) {

                                                // $('#txtTotalImporteDebe').text(parseFloat(json.totalimporteDebe).toFixed(2));
                                                $('.txtTotalImporteDebe').text(json.totalimporteDebe);
                                                $('.txtTotalImporteHaber').text(json.totalimporteHaber);
                                                $('.txtTotalImporteDebeUs').text(json.totalimporteDebeUs);
                                                $('.txtTotalImporteHaberUs').text(json.totalimporteHaberUs);
                                                $('#cant_cuentas').val(json.nro_registros);
                                                return json.data; // Data para el cuerpo de la tabla
                                            }
                                    }
                                    
                                });
                                swal("!Excelente!","SE REGISTRO CORRECTAMENTE","success");
                                registro();
                            }
                            /****************************************************** */

                            
                        }
                    });
                }
            });
     
}
function registro()
{
     swal({
        title: 'ATENCIÓN',
        text: "¿Desea seguir añadiendo registros?",
        icon: 'warning',
        dangerMode: true,
        buttons: {
            cancel: "NO",
            verificar: {
                text: "SI",
                value: "si",
            }
        },
    })
    .then(respuesta => {
        if (respuesta)
        {
           //Limpiar cajas de texto
           limpiarModalRegistro();
           eliminaMensajeError();
           eliminaMensajeErrorCombos();
        }
        else
        {
            $('#modalRegistroMovimiento').modal('hide');  
        }
    });
}
function limpiarModalRegistro()
{
    $('#txtCuenta').val('');
    var enlace = base_url + "Comunes/Comunes/cargarTipoMovimiento";
    $.ajax({
        type: "GET",
        url: enlace,
        success: function(data) {
            $('#txtTipoMovimiento').html(data);
        }
    });
    $('#txtImporte').val('');
    $('#txtGlosaCuenta').val('');
    $('#id_cuenta').val('');
}
function  guardarRegistroCuentaTemporal()
{

    /****************************************************** */
    // quitarComaNumeroDecimal1();
    if(accion == 'nuevo')
    {
        var cadRegistroCuentaT = cadRegistroCuenta+"*"+id_cuenta+"*"+cuenta+"*"+tipo_movimiento+"*"+tipo_movimiento_literal+"*"+importe+"*"+tipo_cambio+"*"+glosa_cuenta+"|";
        $('#registroCuentaT').val(cadRegistroCuentaT);
        var enlace = base_url + "Contabilidad/Comprobante/cargarTablaRegistroCuenta";
        $('#tablaRegistroCuenta').DataTable({
            destroy: true,
            searching: true,
            fixedHeader: true,
            scrollY: '300px',   // Altura del contenedor visible
            scrollCollapse: true,
            paging: false,
            "aLengthMenu": [[5,10, 15,  -1], [7,10, 15,  "Todos"]],
            "iDisplayLength": 5,
            "ajax": {
                type: "POST",
                url: enlace,
                data: { accion: accion, 
                        cuenta: cadRegistroCuentaT, 
                        id_entidad: id_entidad
                    },

                dataSrc: function(json) {listaCuentas

                        // $('#txtTotalImporteDebe').text(parseFloat(json.totalimporteDebe).toFixed(2));
                        $('.txtTotalImporteDebe').text(parseFloat(json.totalimporteDebe).toFixed(2));
                        $('.txtTotalImporteHaber').text(parseFloat(json.totalimporteHaber).toFixed(2));
                        $('.txtTotalImporteDebeUs').text(parseFloat(json.totalimporteDebeUs).toFixed(2));
                        $('.txtTotalImporteHaberUs').text(parseFloat(json.totalimporteHaberUs).toFixed(2));

                        return json.data; // Data para el cuerpo de la tabla
                    }
            }
            
        });
    }
    /****************************************************** */

}
function cargarCuentasComprobanteT()
{
    var accion                   = $('#txtAccionMovimiento').val();
    var id_entidad               = $('#id_entidad_registro').val();
    var cadRegistroCuenta        = $('#registroCuentaT').val();

    var enlace = base_url + "Contabilidad/Comprobante/cargarTablaRegistroCuenta";
    $('#tablaRegistroCuenta').DataTable({
        destroy: true,
        searching: false,
        paging: false,
        "aLengthMenu": [[5,10, 15,  -1], [7,10, 15,  "Todos"]],
        "iDisplayLength": 5,
         "ajax": {
            type: "POST",
            url: enlace,
            data: { accion: accion, 
                    cuenta: cadRegistroCuenta, 
                id_entidad: id_entidad
                },

            dataSrc: function(json) {

                    // $('#txtTotalImporteDebe').text(parseFloat(json.totalimporteDebe).toFixed(2));
                    $('.txtTotalImporteDebe').text(parseFloat(json.totalimporteDebe).toFixed(2));
                    $('.txtTotalImporteHaber').text(parseFloat(json.totalimporteHaber).toFixed(2));
                    $('.txtTotalImporteDebeUs').text(parseFloat(json.totalimporteDebeUs).toFixed(2));
                    $('.txtTotalImporteHaberUs').text(parseFloat(json.totalimporteHaberUs).toFixed(2));

                    return json.data; // Data para el cuerpo de la tabla
                }
        },
    });
}
function cargarCuentasLista()
{
    $("#listaCuentas").load(base_url +  "Contabilidad/PlanDeCuentas/listCuentas" );
}
function listaCuentasBusqueda()
{
    cargarCuentas();
    $('#modalListaCuentas').modal({backdrop: 'static', keyboard: false})
    $('#modalListaCuentas').modal('show');  
}
function guardarDatosComprobanteMasDetalle()
{

    var nro_cuentas_comprobante = $('#cant_cuentas').val();
    var mensaje="";
    var icon="";
    if(nro_cuentas_comprobante>0)
    {
        mensaje="¿Está seguro de registrar el comprobante?";
        icon="warning";
    }
    else
    {
        mensaje="Está a punto de registrar un comprobante sin cuentas contables asociadas.<strong>¿Desea continuar?</strong>";
        icon="error";


    }
    swal({
        title: 'ATENCIÓN',
        // text: mensaje,
        content: {
        element: "div",
        attributes: {
            innerHTML:mensaje
            }
        },


        icon: icon,
        dangerMode: true,
        buttons: {
            cancel: "Cancelar",
            verificar: {
                text: "Registrar",
                value: "registrar",
            }
        },
    })
    .then(respuesta => {
        if (respuesta)
        {

            $('#txtAccionComprobante').val(accion);
            var detalleComprobante = $('#registroCuentaT').val();
            var enlace = base_url + "Contabilidad/Comprobante/guardarComprobante";
            var datos = $('#formregistrocontable').serialize();
            //alert (datos);
            $.ajax({
                type: "POST",
                url: enlace,
                data: {datos:datos,
                    detalleComprobante:detalleComprobante
                },
                success: function(data)
                {
                    var result = JSON.parse(data);
                    $.each(result, function(i, datos)
                    {
                        if(datos.resultado == 1)
                        {
                            swal({title: "OK",text: datos.mensaje,icon: "success",button: "OK",});
                            cargarComprobantesPrincipal();
                        }
                        else
                        {
                            visualizarValidaciones(datos.mensaje);
                            swal({title:"ALERTA",text:"Existen Observaciones.",icon:"warning",button:"OK",dangerMode:true});
                            // swal({title: "ERROR",text: datos.mensaje,icon: "error",button: "Error",});
                        }
                    });
                }
            });
        }
    });

    
}
function cargarComprobantesPrincipal()
{
    // var entidad =$('#entidades').val();
    window.location.href = base_url + "Contabilidad/Comprobante/";
}
function eliminarRegistroCuentaTemporal(id_cuenta,codigoCuenta,tipo_movimiento,tipo_movimiento_literal,importe,tipo_cambio,glosa_cuenta,cadRegistroCuenta)
{
    swal({
        title: 'ATENCIÓN',
        text: "¿Está seguro de eliminar el registro de cuenta del comprobante?",
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
            var accion  = $('#txtAccionMovimiento').val();
            var enlace = base_url + "Contabilidad/Comprobante/eliminarRegistroCuenta";
            $.ajax({
                type: "POST",
                url: enlace,
                data: { accion: accion,
                     id_cuenta: id_cuenta,
                  codigoCuenta: codigoCuenta,
               tipo_movimiento: tipo_movimiento,
       tipo_movimiento_literal: tipo_movimiento_literal,
                       importe: importe,
                   tipo_cambio: tipo_cambio,
                  glosa_cuenta: glosa_cuenta,
             cadRegistroCuenta: cadRegistroCuenta
                      },
                dataType: 'JSON',
                success: function(data) {
                            $('#registroCuentaT').val(data.cuentas);
                            cargarCuentasComprobanteT();   
                }
            });
        }
    });
}