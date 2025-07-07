var base_url;
var id_entidad;
// var nombre_entidad;
// var accion;
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
            valoresIniciales();
        }
    }); 
    cargarCuentasLista();
}
function valoresIniciales(){
    var entidad = $('#entidades').val();    
    if(entidad == -1){
        $("#mensajeSeleccion").show();
        $("#entidadSeleccionada").hide();
        $("#totales").hide();
        $("#filtrosConsulta").hide();
        $("#cuentaSeleccionada").hide();
        $("#tablaLibroMayor").hide();
    }
    else{
        $('#entidadSeleccionada').show();
        $('#totales').show();
        $('#filtrosConsulta').show();
        $("#mensajeSeleccion").show();
        // $('#cuentaSeleccionada').show();
        // $('#tablaLibroMayor').show();
    }
}
function cargarCuentasEntidad(){


    var enlace = base_url + "Comunes/Comunes/cargarCuentaContableEntidad";
    $.ajax({
        type: "GET",
        url: enlace,
        success: function(data) {
            $('#cuentaContable').html(data);
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
function busquedaIDCuenta(id_cuenta,cuenta)
{

     $('#id_cuenta').val( id_cuenta) ;
     $('#txtCuenta').val( cuenta) ;
     $('#modalListaCuentas').modal('hide');  
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
$(function (){

    $('#entidades').change(function(){
                // id_entidad = $(this).val();
                var id_entidad = $('#entidades').val();
                alert(id_entidad);
                nombre_entidad = $('#entidades option:selected').text();
                $('#nombre_entidad').text(nombre_entidad);
                $('#id_entidad').val(id_entidad);
                cargarCuentasEntidad();
                valoresIniciales();
            });
    $('#cuentaContable').change(function(){
                id_cuenta = $(this).val();
                if(id_cuenta == -1){
                    $("#mensajeSeleccion").show();
                    $("#cuentaSeleccionada").hide();
                    $("#tablaLibroMayor").hide();
                }
                else{
                    $('#cuentaSeleccionada').show();
                    $('#tablaLibroMayor').show();
                    $("#mensajeSeleccion").hide();
                }
                // nombre_entidad = $('#entidades option:selected').text();
                // $('#nombre_entidad').text(nombre_entidad);
                // cargarCuentasEntidad();
                // valoresIniciales();
            });

});
function consultar()
{
     $('#cuentaSeleccionada').show();
     $('#tablaLibroMayor').show();
     $("#mensajeSeleccion").hide();
    var id_entidad =$('#id_entidad').val();
    alert (id_entidad);
    /*CARGAR TABLA BUSQUEDA LIBRO MAYOR */
     var enlace = base_url + "Contabilidad/LibroMayor/listarBusquedaLibroMayor";
    $.ajax({
        url: enlace,
        method: "POST",
        data: { id_entidad : id_entidad}, 
        dataType:'JSON',
        success: function (data) 
        {
            if(data.resultado == '1')
            {   
                $("#contenedor_libroMayor").html(data.tabla);
                // setTimeout(() => {
                //         if ($.fn.DataTable.isDataTable("#tbl_libroMayor")) {
                //             $("#tbl_libroMayor").DataTable().destroy();
                //         }

                //         $("#tbl_libroMayor").DataTable({
                //             scrollY: '300px',
                //             scrollCollapse: true,
                //             paging: false,
                //             searching: true,
                //             fixedHeader: true,
                //             dom: 'ftip',
                //             language: {
                //                 search: "Buscar:",
                //                 zeroRecords: "No se encontraron resultados"
                //             }
                //         });
                //     }, 150);
            }
            else
            {
                swal({title: "ERROR",text: "Error",icon: "error",button: "OK",dangerMode:true,});
            }
        }
    });
}
function generarReporteLibroMayor()
{

    var fecha_inicio=$('#fechaDesde').val();
    var fecha_fin=$('#fechaHasta').val();
    if(fecha_inicio!='' && fecha_fin !='')
    {
        $('#divPDF').html('');
        var iframe = document.createElement("iframe");
            iframe.width = '100%';
            iframe.height = '700px';
            iframe.src = base_url+'Contabilidad/LibroMayor/ReporteLibroMayorPDF/'+fecha_inicio+"/"+fecha_fin; 
            $('#divPDF').append(iframe);
        $('#divCapa').addClass('overlay');    
        $('#pdfModal > .modal-dialog ').parent().css('z-index', 1999);
        $('#pdfModal > .modal-dialog ').css("max-width","75%"); 
        $('#pdfModal').show();   
        
    }
    else
    {
        alert("SELECCIONE UN RANGO DE FECHA VÁLIDA POR FAVOR");
    }
}