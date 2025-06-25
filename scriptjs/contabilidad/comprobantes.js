var base_url;
var id_entidad;
var nombre_entidad;
var accion;
var id_comprobante;
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
                cargarTablaComprobantesEntidades(id_entidad); 
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

    var cadRegistroCuenta         =  $('#registroCuentaT').val();


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
                                var cadRegistroCuentaT = cadRegistroCuenta+"*"+id_cuenta+"*"+cuenta+"*"+tipo_movimiento+"*"+tipo_movimiento_literal+"*"+importeFormato+"*"+tipo_cambio+"*"+glosa_cuenta+"|";
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
                                        data: { datos_cuenta:datos_cuenta },
                                        // data: datos,

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
                            // }

                            
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
                        $('.txtTotalImporteDebe').text(json.totalimporteDebe);
                        $('.txtTotalImporteHaber').text(json.totalimporteHaber);
                        $('.txtTotalImporteDebeUs').text(json.totalimporteDebeUs);
                        $('.txtTotalImporteHaberUs').text(json.totalimporteHaberUs);
                        $('#cant_cuentas').val(json.nro_registros);
                        return json.data;
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
                            // cargarComprobantesPrincipal();
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
    var entidad =$('#id_entidad').val();
    window.location.href = base_url + "Contabilidad/Comprobante/principalComprobante/"+entidad;
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
function generarReportePComprobanteRegistrado(id_comprobante)
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
    $('#txtAccionComprobante').val(accion);
    // var datos = $('#formregistrocontable').serializeArray();
    var datos = $('#formregistrocontable').serialize();
    var detalleComprobante = $('#registroCuentaT').val();

    let form = document.createElement("form");
    form.setAttribute("target", "iframePDF");
    form.setAttribute("method", "POST");
    form.setAttribute("action", base_url + "Contabilidad/Comprobante/ReporteComprobantePDF");
    form.style.display = "none";

    // Input para los datos generales
    let inputDatos = document.createElement("input");
    inputDatos.setAttribute("type", "hidden");
    inputDatos.setAttribute("name", "datos");
    inputDatos.setAttribute("value", JSON.stringify(datos));
    form.appendChild(inputDatos);

    // Input para el detalle contable
    let inputDetalle = document.createElement("input");
    inputDetalle.setAttribute("type", "hidden");
    inputDetalle.setAttribute("name", "detalleComprobante");
    inputDetalle.setAttribute("value", JSON.stringify(detalleComprobante));
    form.appendChild(inputDetalle);

    // Crear iframe si no existe
    let iframe = document.getElementById("iframePDF");
    if (!iframe) {
        iframe = document.createElement("iframe");
        iframe.setAttribute("name", "iframePDF");
        iframe.setAttribute("id", "iframePDF");
        iframe.style.width = "100%";
        iframe.style.height = "700px";
        $('#divPDF').html(iframe);
    }
    // Agregar el form al DOM y enviarlo
    document.body.appendChild(form);
    form.submit();

    // Mostrar modal
    $('#divCapa').addClass('overlay');
    $('#pdfModal > .modal-dialog ').parent().css('z-index', 1999);
    $('#pdfModal > .modal-dialog ').css("max-width", "85%");
    $('#pdfModal').show();

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
                $('#txtTipo option[value="'+data.tipo_comprobante+'"]').prop('selected','selected'); 
                $('#txtTipo').prop('disabled', true);
                $('#txtFecha').val(data.fecha_comprobante);
                $('#txtTipoCambio').val(data.tipo_cambio);
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