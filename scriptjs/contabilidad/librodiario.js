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
    var enlace = base_url + "Comunes/Comunes/cargarTipoComprobanteBusqueda";
    $.ajax({
        type: "GET",
        url: enlace,
        success: function(data) {
            $('#tipo_comprobante').html(data);
            $('#tipo_comprobante option[value="'+tipo_comprobante+'"]').prop('selected','selected');
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

            });
    $('#tipo_comprobante').change(function(){
                tipo_cuenta = $(this).val();
                if(tipo_cuenta == -1){
                    $("#numero_inicio").prop("disabled", true);
                    $("#numero_fin").prop("disabled", true);
                    $("#numero_inicio").val('');
                    $("#numero_fin").val('');
                }
                else{
                    $("#numero_inicio").prop("disabled", false);
                    $("#numero_fin").prop("disabled", false);
                    $("#numero_inicio").val('');
                    $("#numero_fin").val('');
                }

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
function consultar() {
  $('#tablaLibroDiario').show();
  $("#mensajeSeleccion").hide();

  var id_entidad          = $('#id_entidad').val();
  var fecha_inicio        = $('#fechaDesde').val();
  var fecha_fin           = $('#fechaHasta').val();
  var tipo_comprobante    = $('#tipo_comprobante').val();
  var numero_inicio       = $('#numero_inicio').val();
  var numero_fin          = $('#numero_fin').val();
  var enlace = base_url + "Contabilidad/LibroDiario/cargarDatosLibroDiario";

  $.ajax({
    url: enlace,
    method: "POST",
    data: {
      id_entidad: id_entidad,
      fecha_inicio: fecha_inicio,
      fecha_fin: fecha_fin,
      tipo_comprobante:tipo_comprobante,
      numero_inicio:numero_inicio,
      numero_fin:numero_fin
    },
    dataType: 'JSON',
    success: function (data) {
      if (data.resultado === 1) {


        if ($.fn.DataTable.isDataTable('#tablaDatosLibroDiario')) {
          $('#tablaDatosLibroDiario').DataTable().clear().destroy();
        }


        $('#tbodyLibroDiario').html(data.tabla);

        // Actualizar totales
        $('.txtTotalImporteDebe').text(data.totalimporteDebe);
        $('.txtTotalImporteHaber').text(data.totalimporteHaber);
        $('.txtTotalImporteDebe').text(data.totalimporteDeudor);
        $('.txtTotalImporteHaber').text(data.totalimporteAcreedor);

        $('#tablaDatosLibroDiario').DataTable({
        //   scrollY: true,
          scrollY: '600px',   // Altura del contenedor visible
          scrollCollapse: true,
          responsive: true,
          paging: true,
          searching: true,
          ordering: false,
          "aLengthMenu": [[10,30, 50,  -1], [10,30, 50,  "Todos"]],
          "iDisplayLength": 10,
        });

      } else {
        swal({
          title: "ERROR",
          text: "No se encontraron datos.",
          icon: "error",
          button: "OK",
          dangerMode: true,
        });
      }
    },
    error: function () {
      swal({
        title: "Error",
        text: "No se pudo procesar la solicitud.",
        icon: "error",
        dangerMode: true,
      });
    }
  });
}


function generarReporteLibroDiario()
{
    consultar();
    var id_entidad          = $('#id_entidad').val();
    var fecha_inicio        = $('#fechaDesde').val();
    var fecha_fin           = $('#fechaHasta').val();
    var tipo_comprobante    = $('#tipo_comprobante').val();
    var numero_inicio       = $('#numero_inicio').val();
    var numero_fin          = $('#numero_fin').val();

    if(numero_inicio == '')
    {
        numero_inicio  = 0;
    }
    if(numero_fin == '')
    {
        var numero_fin = 0;
    }
    if(fecha_inicio!='' && fecha_fin !='')
    {
        $('#divPDF').html('');
        var iframe = document.createElement("iframe");
            iframe.width = '100%';
            iframe.height = '700px';
            iframe.src = base_url+'Contabilidad/LibroDiario/ReporteLibroDiarioPDF/'+id_entidad+"/"+fecha_inicio+"/"+fecha_fin+"/"+tipo_comprobante+"/"+numero_inicio+"/"+numero_fin; 
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
