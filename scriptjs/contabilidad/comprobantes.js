var base_url;
var id_entidad;
var nombre_entidad;
var accion;
var id_comprobante;
var tipo_comprobante;
function baseurl(enlace) {
   base_url = enlace;
}
function cargarComboPrincipal()
{
     var enlace = base_url + "Comunes/Comunes/cargarEntidad";
    $.ajax({
        type: "GET",
        url: enlace,
        success: function(data) {
            $('#entidades').html(data);
            if(id_entidad > -1){
                $('#entidades option[value="'+id_entidad+'"]').prop('selected','selected');
                nombre_entidad = $('#entidades option:selected').text();
                $('#nombre_entidad').text(nombre_entidad);
                
                cargarTablaComprobantesEntidades(id_entidad,tipo_comprobante); 
            }
        }
    });
}
function cargarCombos()
{
   
    var enlace = base_url + "Comunes/Comunes/cargarTipoComprobante";
    $.ajax({
        type: "GET",
        url: enlace,
        success: function(data) {
            $('#txtTipo').html(data);
        }
    });
    var enlace = base_url + "Comunes/Comunes/cargarTipoComprobanteBusqueda";
    $.ajax({
        type: "GET",
        url: enlace,
        success: function(data) {
            $('#tipo_comprobante').html(data);
            $('#tipo_comprobante option[value="'+tipo_comprobante+'"]').prop('selected','selected');
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
                            $('#id_cuenta_auxiliar').val('-');
                            $('#txtAuxiliarCuenta').val('-');
                            $('#id_cuenta_auxiliar').text('-');
                            $('#txtAuxiliarCuenta').text('-');
                            cargarCuentasAuxiliaresLista(datalist[i].dataset.value);
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
                            $('#id_cuenta_auxiliar').val('-');
                            $('#txtAuxiliarCuenta').val('-');
                            $('#id_cuenta_auxiliar').text('-');
                            $('#txtAuxiliarCuenta').text('-');
                            cargarCuentasAuxiliaresLista(datalist[i].dataset.value);
                            break;
                        }
                    }
                }

        });

        $('#entidades').change(function(){
            $('#cardEntidad').find('[data-card-widget="collapse"]').click();
            id_entidad = $(this).val();
            // alert(id_entidad);
            nombre_entidad = $('#entidades option:selected').text();
            $('#nombre_entidad').text(nombre_entidad);
            cargarTablaComprobantesEntidades(id_entidad,-1);

        });
         $('#tipo_comprobante').change(function(){
            id_entidad = $('#entidades').val();
            var id_tipo_comprobante =  $(this).val();
            cargarTablaComprobantesEntidades(id_entidad,id_tipo_comprobante);

        });
        /* Valida  numeros en los textos */ 
        $("#txtImporte").keypress(function (e) {
            var keyCode = e.keyCode || e.which;
            if (event.which && (event.which < 46 || event.which > 57 || event.which == 47) && event.keyCode != 8) {
                    event.preventDefault();
            }
        });
        $('#txtFecha').change(function(){
            fecha = $(this).val();
            var enlace =  base_url + 'Contabilidad/Comprobante/getTipoCambio';
            $.ajax({
                     url: enlace,
                    type: 'POST',
                    data: { fecha: fecha },
                    success: function(response) {
                        var resultado = JSON.parse(response);
                        // alert(resultado.tipo_cambio_fecha); 
                        var tipo_cambio = resultado.tipo_cambio_fecha; 
                        $('#txtTipoCambio').val(tipo_cambio);
                        var nuevo_tipocambio = tipo_cambio;
                        var comprobante_tipocambio = $('#tipo_cambio_comprobante').val();
                        var id_comprobante = $('#id_comprobanteP').val();
                        if(accion == "editar")
                        {
                            if(nuevo_tipocambio !== comprobante_tipocambio)
                            {
                                var mensaje="Atención: El tipo de cambio ha sido modificado. Para garantizar la exactitud de los datos, actualizar los montos en moneda extranjera según el tipo de cambio introducido.";
                               swal({title:"ALERTA",text:mensaje,icon:"warning",button:"OK",dangerMode:true});
                               $('#btnRecalcularTipoCambio').show();
                            }
                            else
                            {
                                $('#btnRecalcularTipoCambio').hide();
                            }
                        }
                    }
                });



        });

});



function busquedaIDCuenta(id_cuenta,cuenta)
{

    $('#id_cuenta').val( id_cuenta) ;
    $('#txtCuenta').val( cuenta) ;
    $('#modalListaCuentas').modal('hide'); 
    $('#id_cuenta_auxiliar').val('-');
    $('#txtAuxiliarCuenta').val('-');
    $('#id_cuenta_auxiliar').text('-');
    $('#txtAuxiliarCuenta').text('-');
     cargarCuentasAuxiliaresLista(id_cuenta); 
}

function cargarTablaComprobantesEntidades(id_entidad,id_tipo_comprobante)
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
            data: { id_entidad: id_entidad ,
                    id_tipo_comprobante:id_tipo_comprobante
            },
            
        
        },
        "columnDefs": [
        {
            targets: 0, 
            orderable: false, // <-- DESACTIVA ordenamiento
            width: "180px", 
            className: "text-center" 
        }],
        
        "order": [] // <-- Desactiva orden inicial automática
   
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
    if(entidad == -1)
    {
         swal({title:"ALERTA",text:"Por favor, seleccione una entidad para continuar con el registro.",icon:"warning",button:"OK",dangerMode:true});
    }
    else
    {
        window.location.href = base_url + "Contabilidad/Comprobante/registroComprobante/"+entidad;
    }
}
function agregarRegistroComprobante()
{
    eliminaMensajeError();
    eliminaMensajeErrorCombos();
    limpiarModalRegistro();
    if($('#txtAccionComprobante').val()=== 'editar')
    {
        $('#txtAccionComprobanteCuenta').val('editar');
        $('#txtAccionMovimiento').val('nuevo');
        id_comprobante = $('#id_comprobanteP').val();
        $('#id_comprobante').val(id_comprobante);
    }
    else
    {
        $('#txtAccionComprobanteCuenta').val('nuevo');
        $('#txtAccionMovimiento').val('nuevo');
    }
    // alert (id_entidad);
    $('#id_entidad_registro').val(id_entidad);
    $('#nombreEntidad').text(nombre_entidad);
    var tipo_cambio=$('#txtTipoCambio').val();
    $('#tipoCambio').text(tipo_cambio);
    $('#tipo_cambio_movimiento').val(tipo_cambio);
    $('#modalRegistroMovimiento').modal({backdrop: 'static', keyboard: false})
    $('#modalRegistroMovimiento').modal('show');  
}
function editarRegistroCuentaComprobante(id_registro_cuenta)
{
    eliminaMensajeError();
    eliminaMensajeErrorCombos();
    limpiarModalRegistro();
    $('#mensaje').val("");
    $('#registroCuentaT').val("");
    if($('#txtAccionComprobante').val()=== 'editar')
    {
        id_comprobante = $('#id_comprobanteP').val();
        var tipo_cambio=$('#txtTipoCambio').val();
        $('#txtAccionComprobanteCuenta').val('editar');
        $('#txtAccionMovimiento').val('editar');
        $('#id_comprobante').val(id_comprobante);
        $('#id_entidad_registro').val(id_entidad);
        $('#nombreEntidad').text(nombre_entidad);
        $('#tipoCambio').text(tipo_cambio);
        $('#tipo_cambio_movimiento').val(tipo_cambio);
        $('#id_registroCuentaComprobante').val(id_registro_cuenta);
        // cargarDatosCuentaComprobante(id_registro_cuenta);
        // $('#modalRegistroMovimiento').modal({backdrop: 'static', keyboard: false})
        // $('#modalRegistroMovimiento').modal('show');  

        cargarDatosCuentaComprobante(id_registro_cuenta).done(function() {
            $('#modalRegistroMovimiento').modal({
                backdrop: 'static',
                keyboard: false
            });
            $('#modalRegistroMovimiento').modal('show');
        });





    }

}
function guardarRegistroCuenta()
{
    var accion_comprobante        = $('#txtAccionComprobanteCuenta').val();
    var accion_cuenta             = $('#txtAccionMovimiento').val();
    var id_entidad                = $('#id_entidad_registro').val();
    var id_comprobante            = $('#id_comprobante').val();
    var id_cuenta                 = $('#id_cuenta').val();
    var cuenta                    = $('#txtCuenta').val();
    var tipo_movimiento           = $('#txtTipoMovimiento').val();
    var tipo_movimiento_literal   = $('#txtTipoMovimiento option:selected').text();
    var importe                   = $('#txtImporte').val();
    var importeFormato            = importe.split(",").join("");
    var tipo_cambio               = $('#txtTipoCambio').val();
    var glosa_cuenta              = $('#txtGlosaCuenta').val();
	var id_cuenta_auxiliar		  = $('#id_cuenta_auxiliar').val();
	var cuenta_auxiliar		      = $('#txtAuxiliarCuenta').val();
    var cadRegistroCuenta         = $('#registroCuentaT').val();


    var enlace = base_url + "Contabilidad/Comprobante/validarDatosRegistroCuenta";
    var datos = $('#formularioRegistroCuenta').serialize();
    var datos_cuenta = $('#formularioRegistroCuenta').serialize();
    // var datos_cuenta = datos;
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

                            // if(accion_comprobante == 'nuevo' && accion_cuenta == 'nuevo')
                            // {
                                var cadRegistroCuentaT = cadRegistroCuenta+"*"+id_cuenta+"*"+cuenta+"*"+tipo_movimiento+"*"+tipo_movimiento_literal+"*"+importeFormato+"*"+tipo_cambio+"*"+glosa_cuenta+"*"+id_cuenta_auxiliar+"*"+cuenta_auxiliar+"|";
                                $('#registroCuentaT').val(cadRegistroCuentaT);
                                var enlace = base_url + "Contabilidad/Comprobante/cargarTablaRegistroCuenta";
                                // var datos_cuenta = $('#formularioRegistroCuenta').serialize();
                                // console.log(datos_cuenta);
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
                                        // data: { 
                                        //         accion_comprobante: accion_comprobante, 
                                        //         accion_cuenta: accion_cuenta, 
                                        //         cuenta: cadRegistroCuentaT, 
                                        //         id_entidad: id_entidad
                                        //     },
                                        data: { datos_cuenta:datos_cuenta,
                                                     cuentas:cadRegistroCuentaT
                                              },
                                        // data: datos,

                                        dataSrc: function(json) {

                                                // $('#txtTotalImporteDebe').text(parseFloat(json.totalimporteDebe).toFixed(2));
                                                $('.txtTotalImporteDebe').text(json.totalimporteDebe);
                                                $('.txtTotalImporteHaber').text(json.totalimporteHaber);
                                                $('.txtTotalImporteDebeUs').text(json.totalimporteDebeUs);
                                                $('.txtTotalImporteHaberUs').text(json.totalimporteHaberUs);
                                                $('#cant_cuentas').val(json.nro_registros);
                                                $('#mensaje').val(json.mensaje);
                                                return json.data; // Data para el cuerpo de la tabla
                                            }
                                    }
                                    
                                });
                                // swal("!Excelente!", text:json.mensaje,"success");
                                var mensaje= $('#mensaje').val();
                                swal({title:"!Excelente¡",text:mensaje,icon:"success",button:"OK"});
                                if(accion_comprobante == 'nuevo' || accion_cuenta == 'nuevo')
                                {
                                    registro();
                                }
                                else
                                {
                                    $('#modalRegistroMovimiento').modal('hide');  
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
// function  guardarRegistroCuentaTemporal()
// {
//     if(accion == 'nuevo')
//     {
//         var cadRegistroCuentaT = cadRegistroCuenta+"*"+id_cuenta+"*"+cuenta+"*"+tipo_movimiento+"*"+tipo_movimiento_literal+"*"+importe+"*"+tipo_cambio+"*"+glosa_cuenta+"|";
//         $('#registroCuentaT').val(cadRegistroCuentaT);
//         var enlace = base_url + "Contabilidad/Comprobante/cargarTablaRegistroCuenta";
//         $('#tablaRegistroCuenta').DataTable({
//             destroy: true,
//             searching: true,
//             fixedHeader: true,
//             scrollY: '300px',   // Altura del contenedor visible
//             scrollCollapse: true,
//             paging: false,
//             "aLengthMenu": [[5,10, 15,  -1], [7,10, 15,  "Todos"]],
//             "iDisplayLength": 5,
//             "ajax": {
//                 type: "POST",
//                 url: enlace,
//                 data: { accion: accion, 
//                         cuenta: cadRegistroCuentaT, 
//                         id_entidad: id_entidad
//                     },

//                 dataSrc: function(json) {listaCuentas
//                         $('.txtTotalImporteDebe').text(json.totalimporteDebe);
//                         $('.txtTotalImporteHaber').text(json.totalimporteHaber);
//                         $('.txtTotalImporteDebeUs').text(json.totalimporteDebeUs);
//                         $('.txtTotalImporteHaberUs').text(json.totalimporteHaberUs);
//                         $('#cant_cuentas').val(json.nro_registros);
//                         return json.data;
//                     }
//             }
            
//         });
//     }
// }
function cargarCuentasComprobanteT()
{
    var accion                   = $('#txtAccionMovimiento').val();
    var id_entidad               = $('#id_entidad_registro').val();
    var cadRegistroCuenta        = $('#registroCuentaT').val();
    var datos_cuenta             = $('#formularioRegistroCuenta').serialize();
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
            // data: { accion: accion, 
            //         cuenta: cadRegistroCuenta, 
            //     id_entidad: id_entidad
            //     },
            data: { datos_cuenta:datos_cuenta,
                         cuentas:cadRegistroCuenta
                },
            

            dataSrc: function(json) {
                    $('.txtTotalImporteDebe').text(json.totalimporteDebe);
                    $('.txtTotalImporteHaber').text(json.totalimporteHaber);
                    $('.txtTotalImporteDebeUs').text(json.totalimporteDebeUs);
                    $('.txtTotalImporteHaberUs').text(json.totalimporteHaberUs);
                    $('#cant_cuentas').val(json.nro_registros);
                    return json.data;
                }
        },
    });
}
function cargarCuentasComprobanteRegistrado(id_comprobante)
{
    /*DETALLE COMPROBANTE */
    var enlace = base_url + "Contabilidad/Comprobante/cargarDetalleComprobanteByIdComprobanteEntidad";
    $('#tablaRegistroCuenta').DataTable({
        destroy: true,
        searching: false,
        paging: false,
        "aLengthMenu": [[5,10, 15,  -1], [7,10, 15,  "Todos"]],
        "iDisplayLength": 5,
        "ajax": {
            type: "POST",
            url: enlace,
            // data: { accion: accion, 
            //         cuenta: cadRegistroCuenta, 
            //     id_entidad: id_entidad
            //     },
            data: { id_comprobante: id_comprobante},

            dataSrc: function(json) {

                    
                        $('.txtTotalImporteDebe').text(json.totalimporteDebe);
                        $('.txtTotalImporteHaber').text(json.totalimporteHaber);
                        $('.txtTotalImporteDebeUs').text(json.totalimporteDebeUs);
                        $('.txtTotalImporteHaberUs').text(json.totalimporteHaberUs);
                        $('#cant_cuentas').val(json.nro_registros);
                        return json.data;
                    
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
    var accion = $('#txtAccionComprobante').val();
    var mensaje="";
    var icon="";
    if(accion == "editar")
    {
        // editarCabeceraComprobante();
        var nro_cuentas_comprobante = $('#cant_cuentas').val();        
        if(nro_cuentas_comprobante>0)
        {
            mensaje="¿Está seguro de actualizar el comprobante?";
            icon="warning";
        }
        else
        {
            mensaje="Está actualizar el comprobante sin cuentas contables asociadas.<strong>¿Desea continuar?</strong>";
            icon="error";   
        }
    }
    else
    {
        var nro_cuentas_comprobante = $('#cant_cuentas').val();        
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
        
    }
    /*REGISTRO*/
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
            var datos = $('#formregistrocontablePrincipal').serialize();
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
                            // generarPDFComprobante();
                            // cargarComprobantesPrincipal();
                            accion='editar';
                            $('#txtAccionComprobante').val(accion);
                            $('#estado_comprobante')
                            .removeClass('badge-warning') // Quita cualquier clase previa
                            .addClass('badge-success') // Agrega la nueva
                            .text('Registrado');                             
                            generarPDFComprobante().then(() => {
                                cargarComprobantesPrincipal();
                            });
                        }
                        else
                        {
                            visualizarValidaciones(datos.mensaje);
                            swal({title:"ALERTA",text:"Existen Observaciones.",icon:"warning",button:"OK",dangerMode:true});
                            
                        }
                    });
                }
            });
        }
    });
    
}
function editarCabeceraComprobante()
{
    // var nro_cuentas_comprobante = $('#cant_cuentas').val();
    var nro_cuentas_comprobante = 2;
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
            var datos = $('#formregistrocontablePrincipal').serialize();
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
                            
                        }
                        else
                        {
                            visualizarValidaciones(datos.mensaje);
                            swal({title:"ALERTA",text:"Existen Observaciones.",icon:"warning",button:"OK",dangerMode:true});
                            
                        }
                    });
                }
            });
        }
    });
}
function cargarComprobantesPrincipal()
{
    var entidad =$('#id_entidad').val();
    var tipo_comprobante = $('#txtTipo').val();
    window.location.href = base_url + "Contabilidad/Comprobante/principalComprobante/"+entidad+"/"+tipo_comprobante;
}
function eliminarRegistroCuentaComprobante(id_registro_CuentaComprobante)
{
    var id_comprobante = $('#id_comprobanteP').val();
    var cant_cuentas   = $('#cant_cuentas').val();

    swal({
        title: 'ATENCIÓN',
        text: "¿Está seguro de que desea eliminar este registro de cuenta del comprobante?",
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
            var enlace = base_url + "Contabilidad/Comprobante/eliminarRegistroCuentaComprobante";
            $.ajax({
                type: "POST",
                url: enlace,
                data: { id_registro_cuenta: id_registro_CuentaComprobante,
                               cant_cuenta: cant_cuentas
                },
                // dataType: 'JSON',
                success: function(data) {
                            // $('#registroCuentaT').val(data.cuentas);

                            var result = JSON.parse(data);
                            $.each(result, function(i, datos)
                            {
                                // alert(datos.resultado);
                                // alert(datos.cant_cuentas);
                                if(datos.resultado == 1)
                                {
                                    swal({title: "OK",text: datos.mensaje,icon: "success",button: "OK",});
                                    cargarCuentasComprobanteRegistrado(id_comprobante);
                                }
                                else
                                {
                                    swal({title:"ALERTA",text:datos.mensaje,icon:"warning",button:"OK",dangerMode:true});
                                }
                            });                            
                }
            });
        }
    });
}
function eliminarRegistroCuentaTemporal(id_cuenta,codigoCuenta,tipo_movimiento,tipo_movimiento_literal,importe,tipo_cambio,glosa_cuenta,id_cuenta_auxiliar,cuenta_auxiliar,cadRegistroCuenta)
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
            var enlace = base_url + "Contabilidad/Comprobante/eliminarRegistroCuentaTemporal";
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
            id_cuenta_auxiliar: id_cuenta_auxiliar,
               cuenta_auxiliar: cuenta_auxiliar,
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
function generarReporteComprobanteRegistrado(id_comprobante)
{
    // $('#txtAccionComprobante').val('editar');
    // var accion= $('#txt
    $('#divPDF').html('');   
    var accion= 'editar';
    var iframe = document.createElement("iframe");
                iframe.width = '100%';
                iframe.height = '700px';
                iframe.src = base_url+'Contabilidad/Comprobante/ReporteComprobanteRegistradoPDF/'+id_comprobante+'/'+accion; 
                $('#divPDF').append(iframe);
                $('#divCapa').addClass('overlay');    
                $('#pdfModal > .modal-dialog ').parent().css('z-index', 1999);
                $('#pdfModal > .modal-dialog ').css("max-width","85%"); 
                $('#pdfModal').show();   
    
}
function generarPDFComprobante()
{

     return new Promise((resolve) => {
         if(accion=='editar')
        {
            var id_comprobante = $('#id_comprobanteP').val();
            generarReporteComprobanteRegistrado(id_comprobante);
            resolve();
        }
        else
        {

            $('#txtAccionComprobante').val(accion);

            // Obtener datos del formulario principal
            var datos = $('#formregistrocontablePrincipal').serialize();
            var detalleComprobante = $('#registroCuentaT').val();

            // Crear el formulario oculto
            let form = document.createElement("form");
            form.setAttribute("target", "iframePDF");
            form.setAttribute("method", "POST");
            form.setAttribute("action", base_url + "Contabilidad/Comprobante/ReporteComprobanteTemporalPDF");
            form.style.display = "none";

            // Input para los datos generales
            let inputDatos = document.createElement("input");
            inputDatos.setAttribute("type", "hidden");
            inputDatos.setAttribute("name", "datos");
            inputDatos.setAttribute("value", datos);
            form.appendChild(inputDatos);

            // Input para el detalle contable
            let inputDetalle = document.createElement("input");
            inputDetalle.setAttribute("type", "hidden");
            inputDetalle.setAttribute("name", "detalleComprobante");
            inputDetalle.setAttribute("value", detalleComprobante);
            form.appendChild(inputDetalle);

            // Crear iframe si no existe
            let iframe = document.getElementById("iframePDF");
            if (!iframe) {
                iframe = document.createElement("iframe");
                iframe.setAttribute("name", "iframePDF");
                iframe.setAttribute("id", "iframePDF");
                iframe.style.width = "100%";
                iframe.style.height = "700px";
                document.getElementById('divPDF').appendChild(iframe);
            }

            // Agregar el form al DOM, enviarlo y limpiar
            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form); // opcional, limpia el DOM

            // Mostrar el modal
            $('#divCapa').addClass('overlay');
            $('#pdfModal > .modal-dialog').parent().css('z-index', 1999);
            $('#pdfModal > .modal-dialog').css("max-width", "85%");
            $('#pdfModal').show();

            $('#pdfModal').on('hidden.bs.modal', function (e) {
                console.log("Modal PDF cerrado. La promesa de generarPDFComprobante se resuelve.");
                resolve();
                // Opcional: Remover el event listener para evitar múltiples llamadas si el modal se abre y cierra varias veces.
                $(this).off('hidden.bs.modal');
            });

        }
        // setTimeout(() => {
        //     console.log("PDF listo");
        //     resolve();
        // }, 5000); // Simula proceso
        
    });
}
function editarComprobante(id_comprobante)
{
    $('#txtAccionComprobante').val('editar');
    var entidad =$('#entidades').val();
    var accion ='editar';
    window.location.href = base_url + "Contabilidad/Comprobante/registroComprobante/"+entidad+'/'+accion+'/'+id_comprobante;
}
function cargarDatosComprobante(id_comprobante,entidad)
{

    var enlace = base_url + "Contabilidad/Comprobante/cargarComprobanteByIdComprobanteEntidad";
    $.ajax({
        url: enlace,
        method: 'POST',
        data:{ id_comprobanteP:id_comprobante,
                    id_entidad:entidad
             },
        dataType:'JSON',
        success: function (data)
        {
            if(data.resultado == 1)
            {
                $('#info_numero').show();
                $('#nro_comprobante').text(data.correlativo);
                $('#txtTipo option[value="'+data.tipo_comprobante+'"]').prop('selected','selected'); 
                $('#txtTipo').prop('disabled', true);
                $('#txtFecha').val(data.fecha_comprobante);
                $('#txtTipoCambio').val(data.tipo_cambio);
                $('#tipo_cambio_comprobante').val(data.tipo_cambio);
                $('#txtReferencia').val(data.referencia_comprobante);
                $('#txtGlosaGeneral').val(data.glosa_comprobante);
                $('#txtFecha').val(data.fecha_comprobante);
                $('#btnGuardar').html('<i class="fas fa-save mr-1"></i> Editar Comprobante');
                if(data.estado=='ACT')
                {
                    $('#estado_comprobante')
                    .removeClass('badge-warning') // Quita cualquier clase previa
                    .addClass('badge-success') // Agrega la nueva
                    .text('Registrado');
                }
                
                /*DETALLE COMPROBANTE */
                var enlace = base_url + "Contabilidad/Comprobante/cargarDetalleComprobanteByIdComprobanteEntidad";
                $('#tablaRegistroCuenta').DataTable({
                    destroy: true,
                    searching: false,
                    paging: false,
                    "aLengthMenu": [[5,10, 15,  -1], [7,10, 15,  "Todos"]],
                    "iDisplayLength": 5,
                    "ajax": {
                        type: "POST",
                        url: enlace,
                        // data: { accion: accion, 
                        //         cuenta: cadRegistroCuenta, 
                        //     id_entidad: id_entidad
                        //     },
                        data: { id_comprobante: id_comprobante},

                        dataSrc: function(json) {

                                $('.txtTotalImporteDebe').text(json.totalimporteDebe);
                                $('.txtTotalImporteHaber').text(json.totalimporteHaber);
                                $('.txtTotalImporteDebeUs').text(json.totalimporteDebeUs);
                                $('.txtTotalImporteHaberUs').text(json.totalimporteHaberUs);
                                $('#cant_cuentas').val(json.nro_registros);
                                return json.data;
                            }
                    },
                });
                
            }
        }
    });
}
function cargarDatosCuentaComprobante(id_registro_cuenta)
{
    var enlace = base_url + "Contabilidad/Comprobante/cargarComprobanteCuentaByIdRegistro";
    // $.ajax({
    //     url: enlace,
    //     method: 'POST',
    //     data:{ id_registro_cuenta:id_registro_cuenta },
    //     dataType:'JSON',
    //     success: function (data)
    //     {
    //         if(data.resultado == 1)
    //         {
    //             $('#id_cuenta').val(data.id_cuenta);
    //             $('#txtCuenta').val(data.codigo_descripcion);
    //             $('#txtTipoMovimiento option[value="'+data.tipo_movimiento+'"]').prop('selected','selected'); 
    //             $('#txtImporte').val(data.importe_moneda_nacional);
    //             $('#txtGlosaCuenta').val(data.glosa_cuenta);          
    //         }
    //     }
    // });
     return $.ajax({
        url: enlace,
        method: 'POST',
        data: { id_registro_cuenta:id_registro_cuenta  },
        dataType: 'json'
    }).done(function(data) {
        if(data.resultado == 1)
            {
                $('#id_cuenta').val(data.id_cuenta);
                $('#txtCuenta').val(data.codigo_descripcion);
                $('#txtTipoMovimiento option[value="'+data.tipo_movimiento+'"]').prop('selected','selected'); 
                $('#txtImporte').val(data.importe_moneda_nacional);
                $('#txtGlosaCuenta').val(data.glosa_cuenta);          
            }
    });
}
// function recalcularCuentasDelComprobante(id_comprobante, tipo_cambio,fecha_tipocambio)
function recalcularCuentasDelComprobante()
{
    

    var id_comprobante    = $('#id_comprobanteP').val();
    var tipo_cambio       = $('#txtTipoCambio').val();
    var fecha_tipocambio  = $('#txtFecha').val();


    swal({
        title: 'ATENCIÓN',
        text: "¿Desea actualizar los montos en moneda extranjera de las cuentas del comprobante con el nuevo tipo de cambio?",
        icon: 'warning',
        dangerMode: true,
        buttons: {
            cancel: "Cancelar",
            verificar: {
                text: "ACTUALIZAR",
                value: "actualizar",
            }
        },
    })
    .then(respuesta => {
        if (respuesta)
        {
            guardarDatosComprobanteMasDetalle();
            var enlace = base_url + "Contabilidad/Comprobante/recalcularCuentasDelComprobante";
            $.ajax({
                type: "POST",
                url: enlace,
                data: { id_comprobante: id_comprobante,
                           tipo_cambio: tipo_cambio,
                      fecha_tipocambio: fecha_tipocambio
                      },
                dataType: 'JSON',
                success: function(data) {
                    if(data.resultado==1){
                        swal({title: "OK",text: data.mensaje,icon: "success",button: "OK",});
                        cargarCuentasComprobanteRegistrado(id_comprobante);
                        $('#btnRecalcularTipoCambio').hide();
                    }
                    else
                    {
                        swal({title:"ALERTA",text:data.mensaje,icon:"warning",button:"OK",dangerMode:true});
                    }
                }
            });
        }
    });
}
function eliminarComprobante(id_comprobante,tipo_comprobante,correlativo,entidad)
{
    var mensaje = "¿Está seguro de eliminar el comprobante :"+correlativo+"-"+tipo_comprobante+" de "+entidad+"?";
    swal({
        title: 'ATENCIÓN',
        text: mensaje,
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
            var enlace = base_url + "Contabilidad/Comprobante/eliminarComprobante";
            $.ajax({
                type: "POST",
                url: enlace,
                data: { id_comprobante: id_comprobante
                },
                // dataType: 'JSON',
                success: function(data) {
                            var result = JSON.parse(data);
                            $.each(result, function(i, datos)
                            {
                                if(datos.resultado == 1)
                                {
                                    swal({title: "OK",text: datos.mensaje,icon: "success",button: "OK",});
                                    cargarTablaComprobantesEntidades(id_entidad);
                                }
                                else
                                {
                                    swal({title:"ALERTA",text:datos.mensaje,icon:"warning",button:"OK",dangerMode:true});
                                }
                            });                            
                }
            });
        }
    });
}
/*CUENTAS AUXILIARES */
function cargarCuentasAuxiliaresLista(id_cuenta)
{
    var enlace = base_url + "Contabilidad/Comprobante/buscaCuentasAuxiliares";
    $.ajax({
        type: "POST",
        url: enlace,
        data: { id_cuenta: id_cuenta
        },
        // dataType: 'JSON',
        success: function(data) {
                    var result = JSON.parse(data);
                    $.each(result, function(i, datos)
                    {
                        if(datos.resultado == 1)
                        {
                            swal({title: "OK",text: datos.mensaje,icon: "info",button: "OK",});
                            $("#auxiliares_cuenta").show();
                            $("#listaAuxiliaresDeCuenta").load(base_url +  "Contabilidad/Comprobante/listCuentasAuxiliares/"+id_cuenta );
                        }
                        else
                        {
                            $("#auxiliares_cuenta").hide();
                        }
                        
                    });                            
        }
    });
}
function cargarCuentasAuxiliares(){

    var idCuenta =$('#id_cuenta').val();
    alert("idCuenta===>"+idCuenta);
    var enlace = base_url + "Contabilidad/Comprobante/listarTablaCuentasAuxiliares";
    $('#tbl_CuentasAuxiliares').DataTable({
        destroy: true,
        "aLengthMenu": [[10, 20, 50, -1], [10, 20, 50, "Todos"]],
        "iDisplayLength": 10,
        "font-size":5,
        "ajax": {
            type: "POST",
            url: enlace,
            data:{id_cuenta:idCuenta}
        },
    });
}
function listaCuentasAuxiliaresBusqueda()
{

    cargarCuentasAuxiliares();
    $('#modalListaCuentasAuxiliares').modal({backdrop: 'static', keyboard: false})
    $('#modalListaCuentasAuxiliares').modal('show');  
}
function busquedaIDCuentaAuxliar(id_cuenta_auxiliar,cuenta_auxiliar)
{

     $('#id_cuenta_auxiliar').val( id_cuenta_auxiliar) ;
     $('#txtAuxiliarCuenta').val( cuenta_auxiliar) ;
     $('#modalListaCuentasAuxiliares').modal('hide');  
}
