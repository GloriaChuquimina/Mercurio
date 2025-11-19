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
    var enlace = base_url + "Comunes/Comunes/cargarTipoMoneda";
    $.ajax({
        type: "GET",
        url: enlace,
        success: function(data) {
            $('#tipo_moneda').html(data);
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

		// Obtener la fecha actual en formato YYYY-MM-DD
        const hoy = new Date();
        const yyyy = hoy.getFullYear();

		// Primer día del año (01-01-YYYY)
		const primerDia = `${yyyy}-01-01`;

        const mm = String(hoy.getMonth() + 1).padStart(2, '0'); // Meses van de 0 a 11
        const dd = String(hoy.getDate()).padStart(2, '0');
        const fechaActual = `${yyyy}-${mm}-${dd}`;
		$('#fechaDesde').val(primerDia);
        $('#fechaHasta').val(fechaActual);

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

function cargarCuentas(marcar){
    var cuentasSeleccionadas = $('#id_cuenta_seleccionadas').val();
    var enlace = base_url + "Contabilidad/LibroMayor/listarPlanDeCuentasBusqueda";
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
function listaCuentasBusqueda()
{
    var cuentasSeleccionadas=$('#id_cuenta_seleccionadas').text();
    $('#opcionSeleccionar').checked = false;
    cargarCuentas(0,cuentasSeleccionadas);
    $('#modalListaCuentas').modal({backdrop: 'static', keyboard: false})
    $('#modalListaCuentas').modal('show');  
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

$(function (){

    $('#entidades').change(function(){
                // id_entidad = $(this).val();
                var id_entidad = $('#entidades').val();
                nombre_entidad = $('#entidades option:selected').text();
                $('#nombre_entidad').text(nombre_entidad);
                $('#id_entidad').val(id_entidad);
                cargarCuentasEntidad();
                valoresIniciales();
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
                    // $('#cuentaSeleccionada').show();
                    $('#tablaLibroMayor').show();
                    $("#mensajeSeleccion").hide();
                }
                // nombre_entidad = $('#entidades option:selected').text();
                // $('#nombre_entidad').text(nombre_entidad);
                // cargarCuentasEntidad();
                // valoresIniciales();
            });
     $('#opcionSeleccionar').click (function ()
    {
        if( $('#opcionSeleccionar').prop('checked') ) 
        {
            marcar = 1;
        }
        else
        {
           marcar = 0;
           $("#cantidadSolicitudes").html('0');
        }
        // var id_entidad = $('#cbEntidades').val();
        cargarCuentas(marcar);
    });
     
    $('#modalListaCuentas').on('hidden.bs.modal', function (e) {
        // alert('El modal se ha cerrado');
        // $('#cuentaSeleccionada').show();
        seleccionDeCuentas();
    });

});
function añadirCuenta()
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
        // $('#cuentaSeleccionada').show();
         
    }
    // $('#cuentaSeleccionada').show();
}
function seleccionDeCuentas()
{

    // Obtener todos los checkboxes seleccionados del DataTable, no solo los visibles
    var table = $('#tbl_CuentasContables').DataTable();

    // Array para guardar los inputs seleccionados manualmente
    var inputs = [];

    // Recorremos todas las filas (incluso las que no están en el DOM)
    table.$('input[type="checkbox"]:checked').each(function () {
        var name = $(this).attr('name');
        var value = $(this).val();

        // Asegurarse de que tiene un name (para serializar)
        if (name) {
            inputs.push($('<input>').attr('type', 'hidden').attr('name', name).val(value));
        }
    });

    // Clonamos el formulario para no modificar el original
    var form = $('#formListaCuentas').clone();

    // Añadimos los inputs ocultos
    $.each(inputs, function (i, input) {
        form.append(input);
    });

    var enlace = base_url + "Contabilidad/LibroMayor/seleccionDeCuentas";
    // var datos  = $('#formListaCuentas').serialize();
    $.ajax({
        type: "POST",
        url: enlace,
        // data: datos,
        data:form.serialize(),
        dataType:'JSON',
        success: function(data)
        {
            // var result = JSON.parse(data);
            if(data.totalCuentas > 1)
            {
                // swal({title: "ALERTA",text: data.mensaje ,icon: "warning",button: "OK",dangerMode:true,});
                $('#id_cuenta_seleccionadas').val(data.cuentas);
                $('#cuentas').text(data.cuentasLiteral);
                $('#txtCuenta').val("");
                $('#txtCuenta').prop('readonly', true);
            }
            else
            {
                if(data.totalCuentas == 1)
                {
                    // swal({title: "EXITO",text: data.mensaje ,icon: "success",button: "OK",dangerMode:true,});
                    $('#id_cuenta').val(data.cuentas);
                    $('#txtCuenta').val(data.cuentasLiteral);
                    $('#id_cuenta_seleccionadas').val(data.cuentas);
                    $('#cuentas').text(data.cuentasLiteral);
                    $('#txtCuenta').prop('readonly', false);
                }
                else
                {
                    swal({title: "ERROR",text: "No se encontraron cuentas seleccionadas",icon: "error",button: "OK",dangerMode:true,});
                    $('#id_cuenta_seleccionadas').val("");
                    $('#cuentas').text("");
                    $('#txtCuenta').prop('readonly', false);
                }
            }
        }
    });
}
function consultar()
{

    var entidad =$('#entidades').val();
	if(entidad == -1)
	{
		swal({title:"ALERTA",text:"Por favor, seleccione una entidad valida para continuar con el registro.",icon:"warning",button:"OK",dangerMode:true});
	}
	else
	{

		// $('#cuentaSeleccionada').show();
		$('#tablaLibroMayor').show();
		$("#mensajeSeleccion").hide();
		var id_entidad =$('#id_entidad').val();
		var cuentas =$('#id_cuenta_seleccionadas').val();
		var fecha_inicio = $('#fechaDesde').val();
		var fecha_fin = $('#fechaHasta').val();
        var valorCheckSinMovimiento    = $('input[name="sinMovimientos"]').is(':checked');
        var moneda         = $('#tipo_moneda').val();
		/*CARGAR TABLA BUSQUEDA LIBRO MAYOR */
		var enlace = base_url + "Contabilidad/LibroMayor/listarBusquedaLibroMayor";
		$.ajax({
			url: enlace,
			method: "POST",
			data: { id_entidad : id_entidad,
					cuentas:cuentas,
					fecha_inicio: fecha_inicio,
					fecha_fin: fecha_fin,
                    valorCheckSinMovimiento:valorCheckSinMovimiento,
                    moneda:moneda
				}, 
			dataType:'JSON',
			success: function (data) 
			{
				if(data.resultado == '1')
				{   

					// console.log(data); 
					if ($.fn.DataTable.isDataTable('#tablaDatosLibroMayor')) {
					$('#tablaDatosLibroMayor').DataTable().clear().destroy();
					}

					$("#tbodyLibroMayor").html(data.tabla);

					// Actualizar totales
					$('.txtTotalImporteDebe').text(data.totalimporteDebe ?? '0.00');
					$('.txtTotalImporteHaber').text(data.totalimporteHaber ?? '0.00');
					$('.txtTotalImporteDeudor').text(data.totalimporteDeudor ?? '0.00');
					$('.txtTotalImporteAcreedor').text(data.totalimporteAcreedor ?? '0.00');

					$('#tablaDatosLibroMayor').DataTable({
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
					
				}
				else
				{
					swal({
						title: "ERROR",
						text: "No se encontraron datos.",
						icon: "error",
						button: "OK",
						dangerMode: true,
					});
				}
			}
		});
    }
}
function generarReporteLibroMayor()
{
    var id_entidad   = $('#id_entidad').val();
    var cuentas =$('#id_cuenta_seleccionadas').val();
    if (cuentas == null || cuentas.length === 0) {
		cuentas = '0'; // o algún valor por defecto
	} 
    var fecha_inicio = $('#fechaDesde').val();
    var fecha_fin    = $('#fechaHasta').val();
    var valorCheckSinMovimiento    = $('input[name="sinMovimientos"]').is(':checked');
    var moneda         = $('#tipo_moneda').val();
    if(fecha_inicio!='' && fecha_fin !='')
    {
        $('#divPDF').html('');
        var iframe = document.createElement("iframe");
            iframe.width = '100%';
            iframe.height = '700px';
            iframe.src = base_url+'Contabilidad/LibroMayor/ReporteLibroMayorPDF/'+id_entidad+"/"+cuentas+"/"+fecha_inicio+"/"+fecha_fin+"/"+valorCheckSinMovimiento+"/"+moneda; 
            $('#divPDF').append(iframe);
        $('#divCapa').addClass('overlay');    
        $('#pdfModal > .modal-dialog ').parent().css('z-index', 1999);
        $('#pdfModal > .modal-dialog ').css("max-width","75%"); 
        $('#pdfModal').show();   
        
    }
    else
    {
         swal({title: "ERROR",text: "SELECCIONE UN RANGO DE FECHA VÁLIDA, POR FAVOR.",icon: "error",button: "OK",dangerMode:true,});
    }
}
