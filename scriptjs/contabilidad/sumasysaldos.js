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
}
function valoresIniciales(){
    var entidad = $('#entidades').val();    
    if(entidad == -1){
        $("#mensajeSeleccion").show();
        $("#entidadSeleccionada").hide();
        $("#totales").hide();
        $("#filtrosConsulta").hide();
        $("#cuentaSeleccionada").hide();
        $("#cardSumasySaldos").hide();
    }
    else{
        $('#entidadSeleccionada').show();
        $('#totales').show();
        $('#filtrosConsulta').show();
        $("#mensajeSeleccion").show();
        // $('#cuentaSeleccionada').show();
        $('#cardSumasySaldos').show();


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
        $('#fechaAl').val(fechaActual);

    }
}
function cargarCuentasLista()
{
<<<<<<< HEAD
    // $("#listaCuentas").load(base_url +  "Contabilidad/PlanDeCuentas/listCuentas" );
     $("#listaCuentas").load(base_url +  "Contabilidad/PlanDeCuentas/listCuentas", { id_entidad: id_entidad });
=======
    $("#listaCuentas").load(base_url +  "Contabilidad/PlanDeCuentas/listCuentas" );
>>>>>>> 86e86a998fd13133562672039854ed148e2211bf
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
				$('#cardEntidad').find('[data-card-widget="collapse"]').click();
<<<<<<< HEAD
                cargarCuentasLista(id_entidad);
=======
>>>>>>> 86e86a998fd13133562672039854ed148e2211bf
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
    $('#modalListaCuentas').on('hidden.bs.modal', function (e) {
<<<<<<< HEAD
=======
        // alert('El modal se ha cerrado');
>>>>>>> 86e86a998fd13133562672039854ed148e2211bf
        // $('#cuentaSeleccionada').show();
        seleccionDeCuentas();
        // Aquí puedes ejecutar cualquier función adicional
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
	$('#soloConMovimientos').click (function ()
    {
        if( $('#soloConMovimientos').prop('checked') ) 
        {
            marcar = 1;
			$('#txtCuenta').prop('readonly', true);
			$('#btnAddCuenta').prop('disabled', true);
			$('#btnlistaCuentasBusqueda').prop('disabled', true);
            // $('#cuentaSeleccionada').hide();
        }
        else
        {
			$("#cantidadSolicitudes").html('0');
           marcar = 0;
		   $('#txtCuenta').prop('readonly', false);
		   $('#btnAddCuenta').prop('disabled', false);
		   $('#btnlistaCuentasBusqueda').prop('disabled', false);
        }

		
    });
     $('#nivel').on('input', function () {
        if (this.value < 0) {
            this.value = 0; // Si es menor que 0, lo ajusta a 0
        }
    });

     // Al seleccionar "Al"
    $('#fechaAl').prop('disabled', false);
    $('#fechaDesde, #fechaHasta').prop('disabled', true);
    $('#radioAl').on('change', function () {
        if ($(this).is(':checked')) {
            $('#fechaAl').prop('disabled', false);
            $('#fechaDesde, #fechaHasta').prop('disabled', true);
        }
    });

    // Al seleccionar "Entre el"
    $('#radioEntre').on('change', function () {
        if ($(this).is(':checked')) {
            $('#fechaAl').prop('disabled', true);
            $('#fechaDesde, #fechaHasta').prop('disabled', false);
        }
    });

});
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
<<<<<<< HEAD
                cuentasSeleccionadas:cuentasSeleccionadas,
                id_entidad: id_entidad
=======
                cuentasSeleccionadas:cuentasSeleccionadas
>>>>>>> 86e86a998fd13133562672039854ed148e2211bf
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
<<<<<<< HEAD
    form.append($('<input>').attr('type', 'hidden').attr('name', 'id_entidad').val(id_entidad));
=======
>>>>>>> 86e86a998fd13133562672039854ed148e2211bf
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
                // $('#id_cuenta_seleccionadas').val(data.cuentas);
                // $('#cuentas').text(data.cuentasLiteral);
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
                    // $('#id_cuenta').val(data.id_cuenta);
					$('#id_cuenta').val(data.cuentas);
                    $('#txtCuenta').val(data.cuentasLiteral);
                    $('#id_cuenta_seleccionadas').val(data.cuentas);
                    $('#cuentas').text(data.cuentasLiteral);
                    $('#txtCuenta').prop('readonly', false);
                }
                else
                {
                    // swal({title: "ERROR",text: "No se encontraron cuentas seleccionadas",icon: "error",button: "OK",dangerMode:true,});
                    // $('#id_cuenta_seleccionadas').val("");
                    // $('#cuentas').text("");
					swal({title: "ERROR",text: "No se encontraron cuentas seleccionadas",icon: "error",button: "OK",dangerMode:true,});
                    $('#id_cuenta_seleccionadas').val("");
                    $('#cuentas').text("");
                    $('#txtCuenta').prop('readonly', false);
                }
            }
        }
    });
}
function cargarDatosSumasySaldos(){

    
    var id_entidad = $('#id_entidad').val();
    var cuentasSeleccionadas = $('#id_cuenta_seleccionadas').val();
    var fecha_desde = $('#fechaDesde').val();
    var fecha_hasta = $('#fechaHasta').val();
	var cuentas_con_movimiento = $('#soloConMovimientos').prop('checked');

    var fecha_al       = $('#fechaAl').val();  
    // var idSeleccionado = $('input[name="customRadio2"]:checked').attr('id');
    var moneda         = $('#tipo_moneda').val();
    var nivel          = $('#nivel').val();
	var radio		   = $('input[name="customRadio2"]:checked').attr('id');
	var mensaje      ="";
    var sw=0;
	

	if(id_entidad == null || id_entidad.length === 0){
<<<<<<< HEAD

=======
        // alert("SELECCIONE UNA ENTIDAD POR FAVOR");
>>>>>>> 86e86a998fd13133562672039854ed148e2211bf
        var mensaje ="SELECCIONE UNA ENTIDAD POR FAVOR";
        swal({title: "ERROR",text: mensaje,icon: "error",button: "OK",dangerMode:true,});
        return;
    }
    else
    {
		sw=0;
		if(radio == 'radioAl')
		{
			if(fecha_al == null || fecha_al.length === 0){

				mensaje = "SELECCIONE UNA FECHA VÁLIDA POR FAVOR";
				sw=1;
			}		
		}
		else if(radio == 'radioEntre')
		{
			if((fecha_desde=='' && fecha_fin =='')|| (fecha_desde.length === 0 && fecha_hasta.length === 0)){
				
				mensaje = "SELECCIONE UN RANGO DE FECHA VÁLIDO POR FAVOR";
				sw=1;
			}

		}
		if(moneda == -1)
		{
			mensaje = "SELECCIONE UNA MONEDA POR FAVOR";
			sw=1;
		}

		if(cuentas_con_movimiento == false)
		{
			if(cuentasSeleccionadas == "")
			{
				mensaje = "SELECCIONE UNA O MAS CUENTAS POR FAVOR";
			    sw=1;
			}

		}


		if(sw==0)
		{
			var enlace = base_url + "Contabilidad/SumasYSaldos/cargarDatosSumasySaldos";
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
						fecha_hasta:fecha_hasta,
						cuentas_con_movimiento:cuentas_con_movimiento,
						fecha_al:fecha_al,
						idSeleccionado:radio,
						moneda:moneda,
						nivel:nivel
					},
					dataSrc: function(json) {listaCuentas
							$('.txtTotalImporteDebe').text(json.totalimporteDebe);
							$('.txtTotalImporteHaber').text(json.totalimporteHaber);
							$('.txtTotalImporteDeudor').text(json.totalimporteDeudor);
							$('.txtTotalImporteAcreedor').text(json.totalimporteAcreedor);
							$('#cant_cuentas').val(json.nro_registros);
							return json.data;
						}
				},
			});
		}
		else
        {
            swal({title: "ERROR",text: mensaje,icon: "error",button: "OK",dangerMode:true,});
            return;
        }
	}
	

    
}
function ReporteSumasySaldosPDF()
{
    var id_entidad   = $('#id_entidad').val();
    var cuentas      = $('#id_cuenta_seleccionadas').val();
	if (cuentas == null || cuentas.length === 0) {
		cuentas = '0'; // o algún valor por defecto
	} 
    // else if (Array.isArray(cuentas)) {
	// 	cuentas = cuentas.join('-'); // convierte a cadena separada por guiones u otro separador
	// }
    var fecha_inicio = $('#fechaDesde').val();
    var fecha_fin    = $('#fechaHasta').val();
	var cuentas_con_movimiento = $('#soloConMovimientos').prop('checked');
<<<<<<< HEAD

=======
    // alert(id_entidad);
>>>>>>> 86e86a998fd13133562672039854ed148e2211bf

    var fecha_al     = $('#fechaAl').val();  
    var idSeleccionado = $('input[name="customRadio2"]:checked').attr('id');
    var moneda         = $('#tipo_moneda').val();
    var nivel          = $('#nivel').val();

    if(nivel == null || nivel.length === 0 || nivel <= 0)
    {
        nivel= 0;
    }
    var sw=0;
    if(id_entidad == null || id_entidad.length === 0){
<<<<<<< HEAD

=======
        // alert("SELECCIONE UNA ENTIDAD POR FAVOR");
>>>>>>> 86e86a998fd13133562672039854ed148e2211bf
        var mensaje ="SELECCIONE UNA ENTIDAD POR FAVOR";
        swal({title: "ERROR",text: mensaje,icon: "error",button: "OK",dangerMode:true,});
        return;
    }
    else{

         sw=0;
        if(idSeleccionado == 'radioAl')
        {
            if(fecha_al == null || fecha_al.length === 0){
               fecha_inicio='01/01/1900';
                fecha_fin='01/01/1900';
                mensaje = "SELECCIONE UNA FECHA VÁLIDA POR FAVOR";
                sw=1;
            }
            else
            {
                fecha_inicio=fecha_al;
                fecha_fin=fecha_al;
            }
        }
        else if(idSeleccionado == 'radioEntre')
        {
            if((fecha_inicio=='' && fecha_fin =='')|| (fecha_inicio.length === 0 && fecha_fin.length === 0)){
                
                mensaje = "SELECCIONE UN RANGO DE FECHA VÁLIDO POR FAVOR";
                sw=1;
            }
            else
            {
                fecha_al=fecha_inicio;
            }
        }
        if(sw==0)
        {
        
            $('#divPDF').html('');
            var iframe = document.createElement("iframe");
                iframe.width = '100%';
                iframe.height = '700px';
                iframe.src = base_url+'Contabilidad/SumasYSaldos/ReporteSumasySaldosPDF/'+id_entidad+"/"+cuentas+"/"+fecha_inicio+"/"+fecha_fin+"/"+cuentas_con_movimiento+"/"+idSeleccionado+"/"+fecha_al+"/"+nivel+"/"+moneda; 
                $('#divPDF').append(iframe);
            $('#divCapa').addClass('overlay');    
            $('#pdfModal > .modal-dialog ').parent().css('z-index', 1999);
            $('#pdfModal > .modal-dialog ').css("max-width","75%"); 
            $('#pdfModal').show();   
        }
        else
        {
            // alert("SELECCIONE UN RANGO DE FECHA VÁLIDA POR FAVOR");
            // var mensaje ="SELECCIONE UN RANGO DE FECHA VÁLIDA POR FAVOR";
            swal({title: "ERROR",text: mensaje,icon: "error",button: "OK",dangerMode:true,});
            return;
        }

    }

}
