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


    var enlace = base_url + "Contabilidad/PlanDeCuentas/listarPlanDeCuentasBusqueda";
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

})
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
    // eliminaMensajeError();
    // eliminaMensajeErrorCombos();
    $('#txtAccionMovimiento').val('nuevo');
    alert (id_entidad);
    $('#id_entidad_registro').val(id_entidad);
    $('#nombreEntidad').text(nombre_entidad);
    $('#modalRegistroMovimiento').modal({backdrop: 'static', keyboard: false})
    $('#modalRegistroMovimiento').modal('show');  
}
function guardarRegistroCuenta()
{
    // var entidadT= $('#txtidregistro').val();
     
    //  var cadDocumentosA  =  $('#documentosA').val();
    //  var documento  = $('#documentoAdm').val();
    //  var nrodocumento  = $('#txtnrodocumentoAdm').val();
    //  var fecha  = $('#txtfechaAdm').val();
    //  if(fecha =="" && documento ==-1 && nrodocumento == ""  )
    //  {
    //     alert("Debe ingresar algun Documento,Nro. documento y Fecha");
    //     return false;
    //  }
    //  if(fecha =="" || documento ==-1 )
    //  { 
    //     alert("Debe ingresar algun Documento y Fecha");
    //     return false;
    //  }
    var accion                    = $('#txtAccionMovimiento').val();
    var id_entidad                = $('#id_entidad_registro').val();
    var id_cuenta                 = $('#id_cuenta').val();
    var cuenta                    = $('#txtCuenta').val();
    var tipo_movimiento           = $('#txtTipoMovimiento').val();
    var tipo_movimiento_literal   = $('#txtTipoMovimiento option:selected').text();
    var importe                   = $('#txtImporte').val();
    var tipo_cambio               = 6.96;
    var glosa_cuenta              = $('#txtGlosaCuenta').val();

    var cadRegistroCuenta         =  $('#registroCuentaT').val();


     if(accion == 'nuevo')
     {
         var cadRegistroCuentaT = cadRegistroCuenta+"*"+id_cuenta+"*"+cuenta+"*"+tipo_movimiento+"*"+tipo_movimiento_literal+"*"+importe+"*"+tipo_cambio+"*"+glosa_cuenta+"|";
         $('#registroCuentaT').val(cadRegistroCuentaT);
        //  inicializarDatos();
        //  cargarEntidadDocumentoT('tablaNormativaTransferencia','TRA');
        //  cargarEntidadDocumentoT('tablaNormativaAdministracion','ADM');
        // var entidadT= $('#txtidregistro').val();
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
                        cuenta: cadRegistroCuentaT, 
                        id_entidad: id_entidad
                      },
            },
        });
     }
     else {
        // var enlace = base_url + "Entidades/Entidades/guardarDocumento";
        // $.ajax({
        //     type: "POST",
        //     url: enlace,
        //     data: { accion: accion, doc: documento, nrodoc: nrodocumento, fechadoc:fecha, tipodoc:'ADM', entidad:entidadT },
        //     dataType: 'JSON',
        //     success: function(data) {
        //             inicializarDatos();
        //            swal({title: "OK",text: data.mensaje,icon: "success",button: "OK",});
        //            cargarEntidadDocumentoT('tablaNormativaAdministracion','ADM');   
        //     }
        // });
     }
     
}
function cargarEntidadDocumentoT(tabla, tipo)
{
    var accionT  = $('#txtaccion').val();
    if(tipo == 'TRA')
    {
        var cadDocumentosT  =  $('#documentosT').val();    
    }
    else{
        var cadDocumentosT  =  $('#documentosA').val();       
    }

    
    var entidadT= $('#txtidregistro').val();
    var enlace = base_url + "Entidades/entidades/cargarTablaDocumentoT";
    $('#'+tabla).DataTable({
        destroy: true,
        searching: false,
        paging: false,
        "aLengthMenu": [[5,10, 15,  -1], [7,10, 15,  "Todos"]],
        "iDisplayLength": 5,
         "ajax": {
            type: "POST",
            url: enlace,
             data: { accion: accionT, documentos: cadDocumentosT, entidad: entidadT, tipo: tipo},
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
                    // cargarTablaEntidades();
                    // $('#modalEntidad').modal('hide');
                    cargarComprobantesPrincipal();
                }
                else
                {
                    swal({title: "ERROR",text: datos.mensaje,icon: "error",button: "Error",});
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