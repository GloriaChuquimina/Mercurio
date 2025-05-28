function visualizarValidaciones(validaciones)
{
    eliminaMensajeError();
    eliminaMensajeErrorCombos();
    var campo = "";
    var array2 = "";
    var campo2 = "";
    var array3 = "";
    var nombrecampo = "";
    var mensaje     = "";
    var dataarray = validaciones.split(",");
    for (var i=0; i < dataarray.length; i++) 
    {
        campo = dataarray[i];
        array2 = campo.split("<>");
        if(array2.length == 1)
        {
            array3 = array2[0];
            campo2 = array3.split(":");
            nombrecampo = campo2[0];
            mensaje     = campo2[1];
            activarCampoTexto(nombrecampo,mensaje);
        }
        else
        {
            array3 = array2[0];
            campo2 = array3.split(":");            
            nombrecampo = campo2[0];
            mensaje     = campo2[1];
            activarCampoCombos(nombrecampo,mensaje);
        }        
    }
    return true;
}
function eliminaMensajeError()
{
    $(".msje_error").each(function(){
        $(this).parent().children('input[type=text] ,select ').removeClass('form-control-danger');
        $(this).parent().removeClass('has-danger');
        $(this).remove();
    });
}
function eliminaMensajeErrorCombos()
{
    $(".msje_error").each(function(){
        $(this).parent().children('select').removeClass('form-control-danger');
        $(this).parent().removeClass('has-danger');
        $(this).remove();
    });
}

function activarCampoTexto(idTexto, mensaje)
{
    
    $('#'+idTexto).parent().addClass('form-group has-danger')
    $('#'+idTexto).addClass('form-control-danger')
    $('#'+idTexto).parent().append('<div class="msje_error"><small><label class="error text-danger">'+mensaje+'</label></small></div> </div>')
    resp = false;        
    return resp;
}

function activarCampoCombos(idTexto, mensaje)
{
    $('#'+idTexto).parent().addClass('form-group has-danger')
    $('#'+idTexto).addClass('form-control-danger')
    $('#'+idTexto).parent().append('<div class="msje_error"><small><label class="error text-danger">'+mensaje+'</label></small></div> </div>')
    resp = false;       
    return resp;
}


function mensajeValidacionesAlert(tipo, mensaje)
{
   
    if(tipo == 1)
    {
       $(document).Toasts('create', {
        class: 'bg-success',
        title: 'Correcto',
        subtitle: 'Cerrar',
        showConfirmButton: false,
         delay: 750,
        body: mensaje
      })
    }
    if(tipo == 2)
    {
        $(document).Toasts('create', {
        class: 'bg-info',
        title: 'Información',
        subtitle: 'Cerrar',
        showConfirmButton: false,
         delay: 750,
        body: mensaje
      })
    }
    if(tipo == 3)
    {
       $(document).Toasts('create', {
        class: 'bg-danger',
        title: 'Error',
        subtitle: 'Cerrar',
        showConfirmButton: false,
        delay: 750,
        body: mensaje
      })
    }
    if(tipo == 4)
    {
         $(document).Toasts('create', {
        class: 'bg-warning',
        title: 'Observaciones',
        subtitle: 'Cerrar',
        showConfirmButton: false,
         delay: 750,
        body: mensaje
      })
    }    
}

function verFacturaSiat(idfactura)
{
    var enlace = base_url + "Facturacion/Facturar/getVerFacturaImpuestos";
    $.ajax({
        url: enlace,
        method: 'POST',
        data:{factura:idfactura},
        success: function (data) 
        {
            var result = JSON.parse(data);
            $.each(result, function(i, datos)
            {
                if(datos.resultado == 1)
                {                    
                    var facturaImpuestos = datos.enlace;                   
                    window.open(facturaImpuestos, '_blank');
                }
                else
                {
                    swal({title: "ALERTA",text: datos.mensaje ,icon: "warning",button: "OK",dangerMode:true,});
                }
            });                  
        }
    });
}

function verFacturaRollo(idfactura)
{       
    var enlace = base_url + "Facturacion/Facturar/getVerFacturaRollo";
    $.ajax({
        url: enlace,
        method: 'POST',
        data:{factura:idfactura},
        success: function (data) 
        {
            var result = JSON.parse(data);
            $.each(result, function(i, datos)
            {
                if(datos.resultado == 1)
                {                    
                    verFacturaPdf(datos.mensaje);
                }
                else
                {
                    swal({title: "ALERTA",text: datos.mensaje ,icon: "warning",button: "OK",dangerMode:true,});
                }
            });                  
        }
    });
}

function verFacturaMediaPlana(idfactura)
{       
    var enlace = base_url + "Facturacion/Facturar/getVerFacturaMediaPlana";
    $.ajax({
        url: enlace,
        method: 'POST',
        data:{factura:idfactura},
        success: function (data) 
        {
            var result = JSON.parse(data);
            $.each(result, function(i, datos)
            {
                if(datos.resultado == 1)
                {                    
                    verFacturaPdf(datos.mensaje);
                }
                else
                {
                    swal({title: "ALERTA",text: datos.mensaje ,icon: "warning",button: "OK",dangerMode:true,});
                }
            });                  
        }
    });
}


function verVentaPdf(idVenta,tipoOperacion)
{
    var enlace = base_url + "Ventas/Vender/getImpresionTipo";
    $.ajax({
        url: enlace,
        method: 'POST',
        data:{venta:idVenta, tipo:tipoOperacion},
        success: function (data) 
        {
            var result = JSON.parse(data);
            $.each(result, function(i, datos)
            {
                if(datos.resultado == 1)
                {                    
                    verFacturaPdf(datos.mensaje);
                }
                else
                {
                    swal({title: "ALERTA",text: datos.mensaje ,icon: "warning",button: "OK",dangerMode:true,});
                }
            });                  
        }
    });
}

function verFacturaMediaPlanaReporte(idfactura)
{       
    var enlace = base_url + "Facturacion/Facturar/getVerFacturaMediaPlanaReporte";
    $.ajax({
        url: enlace,
        method: 'POST',
        data:{factura:idfactura},
        success: function (data) 
        {
            var result = JSON.parse(data);
            $.each(result, function(i, datos)
            {
                if(datos.resultado == 1)
                {                    
                    verFacturaPdf(datos.mensaje);
                }
                else
                {
                    swal({title: "ALERTA",text: datos.mensaje ,icon: "warning",button: "OK",dangerMode:true,});
                }
            });                  
        }
    });
}

function verReenviarFactura(idfactura)
{       
    var enlace = base_url + "Facturacion/Facturar/getReenviarFactura";
    $('#procesoDatosModal').modal({backdrop: 'static', keyboard: false})
    $('#procesoDatosModal').modal('show'); 
    $.ajax({
        url: enlace,
        method: 'POST',
        data:{factura:idfactura},
        success: function (data) 
        {
            $('#procesoDatosModal').modal('hide');
            var result = JSON.parse(data);
            $.each(result, function(i, datos)
            {
                if(datos.resultado == 1)
                {                    
                    swal("¡Excelente!", datos.mensaje, "success");
                }
                else
                {
                    swal({title: "ALERTA",text: datos.mensaje ,icon: "warning",button: "OK",dangerMode:true,});
                }
            });                  
        }
    });
}

function verFacturaPdf(rutaNombreArchivo)
{

    $('#divPDF').html('');
    var iframe = document.createElement("iframe");
        iframe.width = '100%';
        iframe.height = '800px';
        iframe.src = rutaNombreArchivo; //Aqui iría el src de tu archivo .PDF
        $('#divPDF').append(iframe);
    $('#divCapa').addClass('overlay');    
    $('#pdfModal > .modal-dialog ').parent().css('z-index', 2000);    
    $('#pdfModal').show();     
}



function verFacturaXml(idfactura)
{
    var enlace = base_url + "Facturacion/Facturar/getVerFacturaXml";
    $.ajax({
        url: enlace,
        method: 'POST',
        data:{factura:idfactura},
        success: function (data) 
        {
            var result = JSON.parse(data);
            $.each(result, function(i, datos)
            {
                if(datos.resultado == 1)
                {                    
                    var facturaImpuestos = datos.enlace;                   
                    window.open(facturaImpuestos, '_blank');
                }
                else
                {
                    swal({title: "ALERTA",text: datos.mensaje ,icon: "warning",button: "OK",dangerMode:true,});
                }
            });                  
        }
    });
}



function revertirAnulacionFactura(idfactura)
{
    var titulo ="¿Está seguro de revertir la anulación de la factura?";
    var mensaje = "";
    var btnconfirmacion= "Revertir";
    swal({
        title: titulo,
        text : mensaje,
        icon : 'warning',
        dangerMode: true,
        buttons: {
            cancel: "Cancelar",
            verificar: {
                text: btnconfirmacion,
                value: "OK",
            }
        },
    })
    .then(respuesta => {
        if (respuesta)
        {
            $('#procesoCargadoModal').modal({backdrop: 'static', keyboard: false})
            $('#procesoCargadoModal').modal('show'); 
            var enlace = base_url + "Facturacion/Facturar/revertirAnularFacturaSiat";
            $.ajax({
                url: enlace,
                method: 'POST',
                data:{factura:idfactura},
                success: function (data) 
                {  
                    $('#procesoCargadoModal').modal('hide');
                    var result = JSON.parse(data);
                    $.each(result, function(i, datos)
                    {
                        if(datos.resultado == 1)
                        {                    
                            generarReporte();
                            swal("¡Excelente!", datos.mensaje, "success");
                        }
                        else
                        {
                            swal({title: "ALERTA",text: datos.mensaje ,icon: "warning",button: "OK",dangerMode:true,});
                        }
                    });                  
                }
            });
        }
    });
}

function anularFacturaSiat()
{
    var motivo      = $('#txtmotivo').val();
    var idfactura   = $('#txtDatoFac').val();
    var detallemotivo = $("#txtmotivo option:selected").text();

    $('#btnAnular').prop('disabled', true);
    if(motivo > 0)
    {
        var enlace = base_url + "Facturacion/Facturar/anularFacturaSiat";
        $('#procesoCargadoModal').modal({backdrop: 'static', keyboard: false})
        $('#procesoCargadoModal').modal('show');
        $.ajax({
            url: enlace,
            method: 'POST',
            data:{idfac:idfactura, moti:motivo, detalle:detallemotivo},
            success: function (data) 
            {
                $('#procesoCargadoModal').modal('hide');
                var result = JSON.parse(data);
                $.each(result, function(i, datos)
                {                                    
                    if(datos.resultado == 0)
                    {                            
                        
                        var mensaje = datos.mensaje+'\n'+datos.descripAnulacion;
                        swal({title: "ALERTA",text: mensaje ,icon: "warning",button: "OK",dangerMode:true,});                       
                        
                    }
                    else
                    {   
                        generarReporte();
                        $('#modalAnulacion').modal('hide');                                                                      
                       
                        swal("¡Excelente!", datos.mensaje, "success");
                    }
                });                  
            }
        });
    }
    else
    {
        var mensaje = "DEBE SELECCIONAR UN MOTIVO DE ANULACIÓN"
        swal({title: "ALERTA",text: mensaje ,icon: "warning",button: "OK",dangerMode:true,});
    }
}






function fechaActual()
{
    var fechaactual;
    let date = new Date();

    let day = date.getDate()
    let month = date.getMonth() + 1
    let year = date.getFullYear()

    if(month < 10)
    {
      if (day < 10)
      {
        fechaactual = `${year}-0${month}-0${day}`;  
      }
      else 
      {
        fechaactual = `${year}-0${month}-${day}`;
      } 
    }
    else
    {
        if (day < 10)
        {
            fechaactual = `${year}-${month}-0${day}`;  
        }
        else 
        {
            fechaactual = `${year}-${month}-${day}`;
        }      
    }

    return fechaactual
}


function validarCampoTexto(idTexto, mensaje, tipo='text')
{
    var resp = true;    
    if(tipo == 'text')
    {
        if($('#'+idTexto).val().trim() ==='')
        {
            $('#'+idTexto).parent().addClass('form-group has-danger')
            $('#'+idTexto).addClass('form-control-danger')
            $('#'+idTexto).parent().append('<div class="msje_error"><small><label class="error text-danger">'+mensaje+'</label></small></div> </div>')
            resp = false;
        }    
    }
    else
    {
        if($('#'+idTexto).val() =='-1' || $('#'+idTexto).val() =='')
        {
            $('#'+idTexto).parent().addClass('form-group has-danger')
            $('#'+idTexto).addClass('form-control-danger')
            $('#'+idTexto).parent().append('<div class="msje_error"><small><label class="error text-danger">'+mensaje+'</label></small></div> </div>')
            resp = false;
        }  
    }
    return resp;
}