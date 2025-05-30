var base_url;

function baseurl(enlace) {
   base_url = enlace;
}
function cargarNiveles(){

    var enlace = base_url + "Comunes/Comunes/nivel";
    $.ajax({
        type: "POST",
        url: enlace,
        success: function(data) {
            $('#opcionNivel').html(data);
            
        }
    });
}
function cargarCuentaSuperior(nivel)
{
    var enlace = base_url + "Contabilidad/PlanDeCuentas/cargarCuentaSuperior";
    $.ajax({
        type: "POST",
        url: enlace,
        data: {nivel:nivel},
        success: function(data) {
            $('#opcionPadre').html(data);
            
        }
    });
}
$(function (){
    $('#opcionNivel').change(function () {
        var nivel = $("#opcionNivel").val();
        if(nivel > 1){
            cargarCuentaSuperior(nivel);        
        }
    });  
})
function cargarTablaPlanDeCuentas()
{
    var enlace = base_url + "Contabilidad/PlanDeCuentas/listarPlanDeCuentas";
    $('#tablaPlanDeCuentas').DataTable({
        destroy: true,
        "aLengthMenu": [[10, 20, 50, -1], [10, 20, 50, "Todos"]],
        "iDisplayLength": 10,
        "font-size":8,
        "ajax": {
            type: "POST",
            url: enlace
        },
    });
}
function agregarCuentas()
{
    // eliminaMensajeError();
    // eliminaMensajeErrorCombos();
    $('#txtAccion').val('nuevo');
    $('#modalPlanDeCuentas').modal({backdrop: 'static', keyboard: false})
    $('#modalPlanDeCuentas').modal('show');  
}
 function guardarPlanDeCuentas()
{
    var enlace = base_url + "Contabilidad/PlanDeCuentas/guardarPlanDeCuentas";
    var datos = $('#formularioPlanDeCuentas').serialize();
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
                    swal({title:"ALERTA",text:"Existen Observaciones.",icon:"warning",button:"OK",dangerMode:true});
                }else{
                    swal("!Excelente!","SE REGISTRO CORRECTAMENTE","success");
                    $("#modalPlanDeCuentas").modal('hide'); 
                    cargarTablaPlanDeCuentas();
                }
            });
        }
    });
}