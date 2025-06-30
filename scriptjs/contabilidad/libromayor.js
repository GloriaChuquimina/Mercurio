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
                // $('#formularioSeguimientoModal').modal('hide') ;
                $("#tbl_libroMayor").html(data.tabla);
                $('#tbl_libroMayor').DataTable({
                    destroy: true,
                    order: [[0, 'asc']],
                    "columnDefs": [
                                    { "width": "7%", "targets": 0 }
                                    ],
                    "aLengthMenu": [[ 30,50,100, -1], [  30, 50, 100,"Todos"]],
                    "iDisplayLength": 30,
                    "searching": true
                });
                // setTimeout( function () {
                // $('#formularioSeguimientoModal > .modal-dialog ').css("max-width","95%"); 
                // $('#formularioSeguimientoModal').modal({backdrop: 'static', keyboard: false})
                // $('#formularioSeguimientoModal').modal('show');   
                // } ,350);
            }
            else
            {
                swal({title: "ERROR",text: "Error",icon: "error",button: "OK",dangerMode:true,});
            }
        }
    });



}