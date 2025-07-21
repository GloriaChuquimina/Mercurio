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
function cargarCuentasLista()
{
    $("#listaCuentas").load(base_url +  "Contabilidad/PlanDeCuentas/listCuentas" );
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

        // Obtener la fecha actual en formato YYYY-MM-DD
        const hoy = new Date();
        const yyyy = hoy.getFullYear();
        const mm = String(hoy.getMonth() + 1).padStart(2, '0'); // Meses van de 0 a 11
        const dd = String(hoy.getDate()).padStart(2, '0');
        const fechaActual = `${yyyy}-${mm}-${dd}`;
        $('#fechaDesde').val(fechaActual);
        $('#fechaHasta').val(fechaActual);
        consultar();
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
$(function (){

    $('#entidades').change(function(){
                id_entidad = $(this).val();
                nombre_entidad = $('#entidades option:selected').text();
                $('#nombre_entidad').text(nombre_entidad);
                $('#id_entidad').val(id_entidad);
                cargarCuentasEntidad();
                valoresIniciales();
                // $('.card [data-card-widget="collapse"]').click();
                $('#cardEntidad').find('[data-card-widget="collapse"]').click();
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

function listaCuentasBusqueda()
{
    var cuentasSeleccionadas=$('#id_cuenta_seleccionadas').text();
    $('#opcionSeleccionar').checked = false;
    cargarCuentas(0,cuentasSeleccionadas);
    $('#modalListaCuentas').modal({backdrop: 'static', keyboard: false})
    $('#modalListaCuentas').modal('show');  
}
function cargarCuentas(marcar){
    var cuentasSeleccionadas = $('#id_cuenta_seleccionadas').val();
    var enlace = base_url + "Contabilidad/LibroDiario/listarPlanDeCuentasBusqueda";
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
function agregarCuenta()
{
    var cuenta = $('#txtCuenta').val();
    var id_cuenta = $('#id_cuenta').val();
    $('#idCuenta').val('');
    // var target = event.target.value;
    var datalist = document.getElementById('listaCuentas').childNodes;
    for (var i = 0; i < datalist.length; i++) 
    {                 
        if (datalist[i].value === cuenta) {
            $('#id_cuenta').val( datalist[i].dataset.value) ;
            id_cuenta= datalist[i].dataset.value;
            break;
        }
    }
    if(cuenta != '' && id_cuenta != '')
    {
        var cuenta = $('#id_cuenta').val()+"-";
        var cuentas = $('#id_cuenta_seleccionadas').val()+ cuenta;
        $('#id_cuenta_seleccionadas').val(cuentas);
        var cuentaLiteral =$('#txtCuenta').val()+"|";
        var cuentasLiteral = $('#cuentas').text()+ cuentaLiteral;
        $('#cuentas').text(cuentasLiteral);
        $('#cuentaSeleccionada').show();
         
    }
    $('#cuentaSeleccionada').show();
}
function cargarDatosLibroDiario(){
    var id_entidad = $('#id_entidad').val();
    var cuentasSeleccionadas = $('#id_cuenta_seleccionadas').val();
    var fecha_desde = $('#fechaDesde').val();
    var fecha_hasta = $('#fechaHasta').val();
    var enlace = base_url + "Contabilidad/LibroDiario/cargarDatosLibroDiario";
    $('#tablaSumasySaldos').DataTable({
        destroy: true,
        "aLengthMenu": [[10, 20, 50, -1], [10, 20, 50, "Todos"]],
        "iDisplayLength": 10,
        "font-size":5,
        "ajax": {
            type: "POST",
            url: enlace,
            data:{          
                id_entidad:id_entidad,
                cuentasSeleccionadas:cuentasSeleccionadas,
                fecha_desde:fecha_desde,
                fecha_hasta:fecha_hasta
            }
        },
    });
}
function consultar()
{
    $('#tablaLibroDiario').show();
    $("#mensajeSeleccion").hide();
    var id_entidad =$('#id_entidad').val();
    var fecha_inicio = $('#fechaDesde').val();
    var fecha_fin = $('#fechaHasta').val();
    var enlace = base_url + "Contabilidad/LibroDiario/cargarDatosLibroDiario";
    $.ajax({
        url: enlace,
        method: "POST",
        data: { id_entidad : id_entidad,
                fecha_inicio: fecha_inicio,
                fecha_fin: fecha_fin
               }, 
        dataType:'JSON',
        success: function (data) 
        {
            if(data.resultado == '1')
            {   
                 $('#tbodyLibroDiario').html(data.tabla);
            }
            else
            {
                swal({title: "ERROR",text: "Error",icon: "error",button: "OK",dangerMode:true,});
            }
        }
    });
}
function generarReporteLibroDiario()
{
    var id_entidad   = $('#id_entidad').val();
    var fecha_inicio = $('#fechaDesde').val();
    var fecha_fin    = $('#fechaHasta').val();
    if(fecha_inicio!='' && fecha_fin !='')
    {
        $('#divPDF').html('');
        var iframe = document.createElement("iframe");
            iframe.width = '100%';
            iframe.height = '700px';
            iframe.src = base_url+'Contabilidad/LibroDiario/ReporteLibroDiarioPDF/'+id_entidad+"/"+fecha_inicio+"/"+fecha_fin; 
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
