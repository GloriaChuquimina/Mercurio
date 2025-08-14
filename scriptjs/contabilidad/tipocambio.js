var base_url;
var id_entidad;
// var nombre_entidad;
// var accion;
function baseurl(enlace) {
   base_url = enlace;
}
function cargarCombos()
{
    // var enlace = base_url + "Comunes/Comunes/cargarEntidad";
    // $.ajax({
    //     type: "GET",
    //     url: enlace,
    //     success: function(data) {
    //         $('#entidades').html(data);
    //         valoresIniciales();
    //     }
    // }); 
    var enlace = base_url + "Comunes/Comunes/cargarMeses";
    $.ajax({
        type: "GET",
        url: enlace,
        success: function(data) {
            $('#mesTipoCambio').html(data);
        }
    }); 
    var enlace = base_url + "Comunes/Comunes/cargarGestionTipoCambio";
    $.ajax({
        type: "GET",
        url: enlace,
        success: function(data) {
            $('#anioTipoCambio').html(data);
        }
    }); 
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
function cargarTipoCambio(){


    var enlace = base_url + "Contabilidad/TipoCambio/cargarTipoCambio";
     $('#tablaTipoCambio').DataTable({
        destroy: true,
        "aLengthMenu": [[10, 20, 50, -1], [10, 20, 50, "Todos"]],
        "iDisplayLength": 15,
        "font-size":6,
        "ajax": {
            type: "POST",
            url: enlace
        },
    });



}
$(function (){

    $('#entidades').change(function(){
                id_entidad = $(this).val();
                nombre_entidad = $('#entidades option:selected').text();
                $('#nombre_entidad').text(nombre_entidad);
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