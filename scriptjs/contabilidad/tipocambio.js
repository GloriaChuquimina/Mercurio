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
	const hoy = new Date();
	const yyyy = hoy.getFullYear();
	const mm   = hoy.getMonth() + 1; // Meses van de 0 a 11
	const dd = String(hoy.getDate()).padStart(2, '0');

    var enlace = base_url + "Comunes/Comunes/cargarMeses";
    $.ajax({
        type: "GET",
        url: enlace,
        success: function(data) {
            $('#mesTipoCambio').html(data);
			$('#mesTipoCambio option[value="'+mm+'"]').prop('selected','selected'); 
        }
    }); 
    var enlace = base_url + "Comunes/Comunes/cargarGestionTipoCambio";
    $.ajax({
        type: "GET",
        url: enlace,
        success: function(data) {
            $('#anioTipoCambio').html(data);
			$('#anioTipoCambio option[value="'+yyyy+'"]').prop('selected','selected'); 
<<<<<<< HEAD
            cargarTipoCambio(yyyy,mm);
        }
    }); 
=======
        }
    }); 
	cargarTipoCambio(yyyy,mm);
>>>>>>> 86e86a998fd13133562672039854ed148e2211bf
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
function cargarTipoCambio(gestion,mes){

<<<<<<< HEAD
	// alert("GESTION: "+gestion+" MES: "+mes);
=======
	
>>>>>>> 86e86a998fd13133562672039854ed148e2211bf

    var enlace = base_url + "Contabilidad/TipoCambio/cargarTipoCambio";
     $('#tablaTipoCambio').DataTable({
        destroy: true,
        "aLengthMenu": [[10, 20, 50, -1], [10, 20, 50, "Todos"]],
        "iDisplayLength": 15,
        "font-size":6,
        "ajax": {
            type: "POST",
            url: enlace,
			data:{
					gestion:gestion,
					mes:mes
				 }
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
<<<<<<< HEAD
	 $('#mesTipoCambio').change(function(){
				var gestion = $('#anioTipoCambio').val();
				if(gestion != -1)
				{
					// cargarTipoCambio();
                    buscar();
				}
				else
				{
					var mensaje="Seleccione una gestión, por favor."
					 swal({title:"ALERTA",text:mensaje,icon:"warning",button:"OK",dangerMode:true});
				}			
               
            });
=======
	//  $('#mesTipoCambio').change(function(){
	// 			var gestion = $('#anioTipoCambio').val();
	// 			if(gestion != -1)
	// 			{
	// 				cargarTipoCambio();
	// 			}
	// 			else
	// 			{
	// 				var mensaje="Seleccione una gestión, por favor."
	// 				 swal({title:"ALERTA",text:mensaje,icon:"warning",button:"OK",dangerMode:true});
	// 			}			
               
    //         });
>>>>>>> 86e86a998fd13133562672039854ed148e2211bf
	// cargarTipoCambio();
	   $("#tipo_cambio").keypress(function (e) {
            var keyCode = e.keyCode || e.which;
            if (event.which && (event.which < 46 || event.which > 57 || event.which == 47) && event.keyCode != 8) {
                    event.preventDefault();
            }
        });

});
function buscar()
{
	// gestion = $('#anioTipoCambio').val();
	var gestion=$('#anioTipoCambio').val();
	var mes=$('#mesTipoCambio').val();
	if(gestion != -1  &&  mes!= -1 )
	{
		cargarTipoCambio(gestion,mes);
	}
	else
	{
		if(gestion != -1  && mes== -1 )
		{
			cargarTipoCambio(gestion,mes);
		}
		else
		{
			var mensaje="Seleccione una gestión, por favor."
		    swal({title:"ALERTA",text:mensaje,icon:"warning",button:"OK",dangerMode:true});
		}
		
	}		
}
function agregarTipoCambio()
{
    // eliminaMensajeError();
    // eliminaMensajeErrorCombos();
    $('#txtAccion').val('nuevo');
    $('#modalTipoCambio').modal({backdrop: 'static', keyboard: false})
    $('#modalTipoCambio').modal('show');  
}
 function guardarTipoCambio()
{

	// alert("sTEPH");
	var gestion=$('#anioTipoCambio').val();
	var mes=$('#mesTipoCambio').val();
    var accion  = $('#txtAccion').val();
    var mensaje ="";
    var boton ="";

    if(accion == 'nuevo'){
        mensaje = "¿Desea registrar en tipo de cambio?";
        boton   = "Guardar";
    }
    else{
        mensaje = "¿Desea guardar los cambios realizados en el tipo de cambio?"
        boton   = "Modificar";
    }

    swal({
        title: 'ATENCIÓN',
        text: mensaje,
        icon: 'warning',
        dangerMode: true,
        buttons: {
            cancel: "Cancelar",
            verificar: {
                text: boton,
                value: "verificar",
            }
        },
    })
    .then(respuesta => {
        if (respuesta)
        {

            var enlace = base_url + "Contabilidad/TipoCambio/guardarTipoCambio";
            var datos = $('#formularioTipoCambio').serialize();
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
                            swal({title:"ALERTA",text:datos.mensaje,icon:"warning",button:"OK",dangerMode:true});
                        }else{
                            swal({title:"!Excelente¡",text:datos.mensaje,icon:"success",button:"OK"});
                            cargarTipoCambio(gestion,mes)
                            $("#modalTipoCambio").modal('hide'); 
                        }
                    });
                }
            });
        }
    });
 
}

function editarTipoCambio(id_tipocambio)
{
    // eliminaMensajeError();
    // eliminaMensajeErrorCombos();

    var enlace = base_url + "Contabilidad/TipoCambio/datosTipoCambio";        
        $.ajax({
            type: "POST",
            url: enlace,
            data: {id_tipocambio:id_tipocambio},
            success: function(data)
            {
                var result = JSON.parse(data);
                $.each(result, function(i, datos)
                {
                    if(datos.resultado == 1)
                    { 
                        $('#txtAccion').val('editar');
                        $('#id_tipocambio').val(id_tipocambio);
                        $('#txtFecha').val(datos.fecha);
                        $('#tipo_cambio').val(datos.valor);
                        $('#modalTipoCambio').modal({backdrop: 'static', keyboard: false})
                        $('#modalTipoCambio').modal('show'); 
                    }
                    else
                    {
                        swal({title: "ERROR",text: datos.mensaje,icon: "error",button: "Error"});
                    }
                 });
            }
    });
     
}
function bajaTipoCambio(id_tipocambio)
{

	var gestion=$('#anioTipoCambio').val();
	var mes=$('#mesTipoCambio').val();
    swal({
        title:'ATENCIÓN',
        text:"¿Está seguro de eliminar el tipo de cambio?",
        icon:'warning',
        dangerMode:true,
        buttons:{
            cancel:"Cancelar",
            verificar:{
                text:"GUARDAR",
                value:"verificar",
            }
        }
    })
    .then(respuesta=>{
        if(respuesta)
        {
            var enlace = base_url + "Contabilidad/TipoCambio/bajaTipoCambio";
            $.ajax({
                type:"POST",
                url:enlace,
                data:{ id_tipocambio: id_tipocambio},
                success:function(data)
                {
                    var result =JSON.parse(data);
                    $.each(result,function(i,datos){
                        if(datos.resultado == 0)
                        {
                            swal({title:"ALERTA",text:"Existen Observaciones.",icon:"warning",button:"OK",dangerMode:true});
                        }
                        else
                        {
                            swal("!Excelente!","SE REGISTRO CORRECTAMENTE LA BAJA.","success");
                           cargarTipoCambio(gestion,mes)
                            $("#modalTipoCambio").modal('hide'); 
                        }
                    });
                }
            });
        }
    });
}
