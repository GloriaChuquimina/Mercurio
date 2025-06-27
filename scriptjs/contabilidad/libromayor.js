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