<?php
$config = array(
	
		'validar_categoria'=> array
		(
		array ('field' => 'txtcodigo',
			   'label' => 'CÓDIGO',
		  	   'rules' => 'trim|required|min_length[3]|max_length[30]'),

		array ('field' => 'txtdescripcion',
			   'label' => 'DESCRIPCIÓN',
		  	   'rules' => 'trim|required|min_length[3]|max_length[30]')
		
		),

		
		'validar_categoria_editar'=> array
		(
		array ('field' => 'txtcodigo',
			   'label' => 'CÓDIGO',
		  	   'rules' => 'trim|required|min_length[3]|max_length[30]'),

		array ('field' => 'txtdescripcion',
			   'label' => 'DESCRIPCIÓN',
		  	   'rules' => 'trim|required|min_length[3]|max_length[30]')
		
		),

		'validar_linea'=> array
		(
		
		array ('field' => 'txtdescripcion',
			   'label' => 'DESCRIPCIÓN',
		  	   'rules' => 'trim|required|min_length[3]|max_length[30]')
		
		),
		
		'validar_medida'=> array
		(
		
		array ('field' => 'txtdescripcion',
			   'label' => 'DESCRIPCIÓN',
		  	   'rules' => 'trim|required|min_length[3]|max_length[30]'),
		array ('field' => 'txtabreviatura',
			   'label' => 'ABREVIATURA',
		  	   'rules' => 'trim|required|min_length[3]|max_length[30]'),

		
		),
		'validar_medida_editar'=> array
		(
		array ('field' => 'txtdescripcion',
			   'label' => 'DESCRIPCIÓN',
		  	   'rules' => 'trim|required|min_length[3]|max_length[30]'),
		array ('field' => 'txtabreviatura',
			   'label' => 'ABREVIATURA',
		  	   'rules' => 'trim|required|min_length[3]|max_length[30]'),
		
		),

		'validar_proveedor'=> array
		(
		
		array ('field' => 'txtnombre',
			   'label' => 'NOMBRE PROVEEDOR',
		  	   'rules' => 'trim|required|min_length[3]|max_length[30]'),
		array ('field' => 'txtRepresentanteLegal',
			   'label' => 'REPRESENTANTE LEGAL',
		  	   'rules' => 'trim|required|min_length[3]|max_length[30]'),
		array ('field' => 'txtNit',
			   'label' => 'NIT',
		  	   'rules' => 'trim|required|min_length[3]|max_length[30]'),
		array ('field' => 'txtCelular',
			   'label' => 'CELULAR',
		  	   'rules' => 'trim|min_length[3]|max_length[15]|numeric'),
		array ('field' => 'txtDireccion',
			   'label' => 'DIRECCIÓN',
		  	   'rules' => 'trim|min_length[3]|max_length[30]'),

		
		),

		

		'validar_ingreso'=> array
		(
		array ('field' => 'txtOrden',
			   'label' => 'Número de Orden de Compra',
		  	   'rules' => 'trim|min_length[1]|max_length[50]|callback_verificarcadena_check'),

		array ('field' => 'txtNotaRemision',
			   'label' => 'Nota de Remisión',
		  	   'rules' => 'trim|min_length[1]|max_length[50]|callback_verificarcadena_check'),

		array ('field' => 'txtfecha_nota_remision',
			   'label' => 'Nota de Remisión',
		  	   'rules'=>'trim|callback_verificarfecha_check'),

		array ('field' => 'txtNotaRemision',
			   'label' => 'Nota de Remisión',
		  	   'rules' => 'trim|min_length[1]|max_length[50]|callback_verificarcadena_check'),
		
		array ('field' => 'txtNumeroFactura',
			   'label' => 'Nro. Factura(s)',
		  	   'rules' => 'trim|min_length[1]|max_length[50]|callback_verificarcadena_check'),

		array ('field' => 'slProveedor',
			   'label' => 'Proveedor',
		  	   'rules' => 'callback_verificarValorCombo'),

		array ('field' => 'txtDescripcion',
			   'label' => 'Descripción Ingreso',
		  	   'rules' => 'trim|min_length[3]|max_length[250]|callback_verificarcadena_check'),
		),

		'validar_precio'=> array
		(
		array ('field' => 'txtmotivoprecio',
			   'label' => 'MOTIVO DE ACTUALIZACIÓN',
		  	   'rules' => 'trim|required|min_length[3]|max_length[50]|callback_verificarcadena_check'),

		array ('field' => 'txtprecioprod',
			   'label' => 'NUEVO PRECIO UNITARIO DE PRODUCTO',
		  	   'rules' => 'trim|required|min_length[1]|max_length[10]|callback_verificarprecio_check'),
		
		),


		'validar_producto'=> array
		(
		/*array ('field' => 'txtActividad',
			   'label' => 'ACTIVIDAD',
		  	   'rules' => 'callback_verificarValorCombo'),
		
		array ('field' => 'txtcodigosin',
			   'label' => 'CODIGO SIN',
		  	   'rules' => 'callback_verificarValorCombo'),*/

		array ('field' => 'txtcategoria',
			   'label' => 'CATEGORÍA',
		  	   'rules' => 'callback_verificarValorCombo'),
		
		array ('field' => 'txtcomercial',
			   'label' => 'NOMBRE GENÉRICO',
		  	   'rules' => 'trim|required|min_length[3]|max_length[100]|callback_verificarcadena_check'),		
		
		array ('field' => 'txtgenerico',
			   'label' => 'NOMBRE COMERCIAL',
		  	   'rules' => 'trim|required|min_length[3]|max_length[100]|callback_verificarcadena_check'),
		
		array ('field' => 'txtcomposicion',
			   'label' => 'COMPOSICIÓN',
		  	   'rules' => 'trim|min_length[3]|max_length[100]'),

		array ('field' => 'txtcantidadminima',
			   'label' => 'CANTIDAD MÍNIMA PARA CONTROL INVENTARIO',
		  	   'rules' => 'trim|required|min_length[1]|max_length[10]|callback_verificarprecio_check'),
		
		array ('field' => 'txtpresentacion',
			   'label' => 'PRESENTACIÓN',
		  	   'rules' => 'trim|min_length[3]|max_length[100]'),

		array ('field' => 'txtlinea',
			   'label' => 'LÍNEA PRODUCTO',
		  	   'rules' => 'callback_verificarValorCombo'),

		array ('field' => 'txtunidad',
			   'label' => 'UNIDAD DE MEDIDA',
		  	   'rules' => 'callback_verificarValorCombo'),

		array ('field' => 'txtvencimiento',
			   'label' => 'FECHA DE VENCIMIENTO PRODUCTO',
		  	   'rules' => 'callback_verificarValorCombo'),

		array ('field' => 'txtprecio',
			   'label' => 'PRECIO UNITARIO',
		  	   'rules' => 'trim|required|min_length[1]|max_length[10]'),
		
		),

		'validar_producto_frasco'=> array
		(
		/*array ('field' => 'txtActividad',
			   'label' => 'ACTIVIDAD',
		  	   'rules' => 'callback_verificarValorCombo'),
		
		array ('field' => 'txtcodigosin',
			   'label' => 'CODIGO SIN',
		  	   'rules' => 'callback_verificarValorCombo'),*/

		array ('field' => 'txtcategoria',
			   'label' => 'CATEGORÍA',
		  	   'rules' => 'callback_verificarValorCombo'),
		
		array ('field' => 'txtcomercial',
			   'label' => 'NOMBRE GENÉRICO',
		  	   'rules' => 'trim|required|min_length[3]|max_length[100]|callback_verificarcadena_check'),		
		
		array ('field' => 'txtgenerico',
			   'label' => 'NOMBRE COMERCIAL',
		  	   'rules' => 'trim|required|min_length[3]|max_length[100]|callback_verificarcadena_check'),
		
		array ('field' => 'txtcomposicion',
			   'label' => 'COMPOSICIÓN',
		  	   'rules' => 'trim|min_length[1]|max_length[10]|callback_verificarprecio_check'),


		array ('field' => 'txtcantidadminima',
			   'label' => 'CANTIDAD MÍNIMA PARA CONTROL INVENTARIO',
		  	   'rules' => 'trim|required|min_length[1]|max_length[10]|callback_verificarprecio_check'),

		
		array ('field' => 'txtpresentacion',
			   'label' => 'PRESENTACIÓN',
		  	   'rules' => 'trim|min_length[3]|max_length[100]'),

		array ('field' => 'txtlinea',
			   'label' => 'LÍNEA PRODUCTO',
		  	   'rules' => 'callback_verificarValorCombo'),

		array ('field' => 'txtunidad',
			   'label' => 'UNIDAD DE MEDIDA',
		  	   'rules' => 'callback_verificarValorCombo'),

		array ('field' => 'txtvencimiento',
			   'label' => 'FECHA DE VENCIMIENTO PRODUCTO',
		  	   'rules' => 'callback_verificarValorCombo'),

		array ('field' => 'txtprecio',
			   'label' => 'PRECIO UNITARIO',
		  	   'rules' => 'trim|required|min_length[1]|max_length[10]'),
		
		),


		'validar_producto_codigo'=> array
		(
		/*array ('field' => 'txtActividad',
			   'label' => 'ACTIVIDAD',
		  	   'rules' => 'callback_verificarValorCombo'),
		
		array ('field' => 'txtcodigosin',
			   'label' => 'CODIGO SIN',
		  	   'rules' => 'callback_verificarValorCombo'),*/

		array ('field' => 'txtcategoria',
			   'label' => 'CATEGORÍA',
		  	   'rules' => 'callback_verificarValorCombo'),
		
		array ('field' => 'txtcomercial',
			   'label' => 'NOMBRE GENÉRICO',
		  	   'rules' => 'trim|required|min_length[3]|max_length[100]|callback_verificarcadena_check'),

		array ('field' => 'txtCodigoProductoManual',
			   'label' => 'CÓDIGO PRODUCTO EMPRESA',
		  	  'rules' => 'trim|required|min_length[1]|max_length[10]'),	
		
		array ('field' => 'txtgenerico',
			   'label' => 'NOMBRE PRODUCTO O SERVICIO',
		  	   'rules' => 'trim|required|min_length[3]|max_length[100]|callback_verificarcadena_check'),
		
		array ('field' => 'txtcomposicion',
			   'label' => 'COMPOSICIÓN',
		  	   'rules' => 'trim|min_length[3]|max_length[100]'),

		array ('field' => 'txtcantidadminima',
			   'label' => 'CANTIDAD MÍNIMA PARA CONTROL INVENTARIO',
		  	   'rules' => 'trim|required|min_length[1]|max_length[10]|callback_verificarprecio_check'),
		
		array ('field' => 'txtpresentacion',
			   'label' => 'PRESENTACIÓN',
		  	   'rules' => 'trim|min_length[3]|max_length[100]'),

		array ('field' => 'txtlinea',
			   'label' => 'LÍNEA PRODUCTO',
		  	   'rules' => 'callback_verificarValorCombo'),

		array ('field' => 'txtunidad',
			   'label' => 'UNIDAD DE MEDIDA',
		  	   'rules' => 'callback_verificarValorCombo'),

		array ('field' => 'txtvencimiento',
			   'label' => 'FECHA DE VENCIMIENTO PRODUCTO',
		  	   'rules' => 'callback_verificarValorCombo'),

		array ('field' => 'txtprecio',
			   'label' => 'PRECIO UNITARIO',
		  	   'rules' => 'trim|required|min_length[1]|max_length[10]'),
		
		),

		

		'validar_producto_codigo_frasco'=> array
		(
		/*array ('field' => 'txtActividad',
			   'label' => 'ACTIVIDAD',
		  	   'rules' => 'callback_verificarValorCombo'),
		
		array ('field' => 'txtcodigosin',
			   'label' => 'CODIGO SIN',
		  	   'rules' => 'callback_verificarValorCombo'),*/

		array ('field' => 'txtcategoria',
			   'label' => 'CATEGORÍA',
		  	   'rules' => 'callback_verificarValorCombo'),
		
		array ('field' => 'txtcomercial',
			   'label' => 'NOMBRE GENÉRICO',
		  	   'rules' => 'trim|required|min_length[3]|max_length[100]|callback_verificarcadena_check'),

		array ('field' => 'txtCodigoProductoManual',
			   'label' => 'CÓDIGO PRODUCTO EMPRESA',
		  	   'rules' => 'trim|required|min_length[1]|max_length[10]'),	

		array ('field' => 'txtgenerico',
			   'label' => 'NOMBRE PRODUCTO O SERVICIO',
		  	   'rules' => 'trim|required|min_length[3]|max_length[100]|callback_verificarcadena_check'),
		
		array ('field' => 'txtcomposicion',
			   'label' => 'COMPOSICIÓN',
		  	   'rules' => 'trim|min_length[1]|max_length[10]|callback_verificarprecio_check'),

		array ('field' => 'txtcantidadminima',
			   'label' => 'CANTIDAD MÍNIMA PARA CONTROL INVENTARIO',
		  	   'rules' => 'trim|required|min_length[1]|max_length[10]|callback_verificarprecio_check'),
		
		array ('field' => 'txtpresentacion',
			   'label' => 'PRESENTACIÓN',
		  	   'rules' => 'trim|min_length[3]|max_length[100]'),

		array ('field' => 'txtlinea',
			   'label' => 'LÍNEA PRODUCTO',
		  	   'rules' => 'callback_verificarValorCombo'),

		array ('field' => 'txtunidad',
			   'label' => 'UNIDAD DE MEDIDA',
		  	   'rules' => 'callback_verificarValorCombo'),

		array ('field' => 'txtvencimiento',
			   'label' => 'FECHA DE VENCIMIENTO PRODUCTO',
		  	   'rules' => 'callback_verificarValorCombo'),

		array ('field' => 'txtprecio',
			   'label' => 'PRECIO UNITARIO',
		  	   'rules' => 'trim|required|min_length[1]|max_length[10]'),
		
		),


		'validar_pv'=> array
		(
		array ('field' => 'txtnombrePunto',
			   'label' => 'Nombre Punto Venta',
		  	   'rules' => 'trim|required|min_length[1]|max_length[30]')
		),


		'validar_sucursal'=> array
		(
		array ('field' => 'nroSucursal',
			   'label' => 'Nro. Sucursal:',
		  	   'rules' => 'trim|required|min_length[1]|numeric'),

		array ('field' => 'txtcodigosucursal',
			   'label' => 'Código Sucursal',
		  	   'rules' => 'trim|required|min_length[1]|max_length[30]'),

		array ('field' => 'txtsucursal',
			   'label' => 'Sucursal',
		  	   'rules' => 'trim|required|min_length[3]|max_length[30]'),

		

		array ('field' => 'txtresponsable',
			   'label' => 'Responsable',
		  	   'rules' => 'trim|required|min_length[3]|max_length[30]'),

		array ('field' => 'txtdireccion',
			   'label' => 'Dirección',
		  	   'rules' => 'trim|required|min_length[3]|max_length[30]'),


		array ('field' => 'txttelefono',
			   'label' => 'Teléfono',
		  	   'rules' => 'trim|required|min_length[3]|max_length[10]'),

		array ('field' => 'txtcelular',
			   'label' => 'Celular',
		  	   'rules' => 'trim|required|min_length[3]|max_length[10]'),
		),



		'validar_venta_numeros'=> array
		(
		array ('field' => 'nitcit',
			   'label' => 'NIT/CI',
		  	   'rules' => 'trim|required|min_length[1]|max_length[15]|numeric'),
		array ('field' => 'razonsocial',
			   'label' => 'Nombres - Razon social',
		  	   'rules' => 'trim|required|min_length[1]|max_length[25]|callback_verificarcadena_check'),
		array ('field' => 'correoelectronico',
			   'label' => 'Correo',
		  	   'rules' => 'trim|valid_email'),
		array ('field' => 'selectPuntoVenta',
			   'label' => 'Punto de Venta',
		  	   'rules' => 'trim|required')
		
		),


		'validar_venta_letras'=> array
		(
		array ('field' => 'nitcit',
			   'label' => 'NIT/CI',
		  	   'rules' => 'trim|required|min_length[1]|max_length[20]|callback_verificarcadenadocumento_check'),
		array ('field' => 'razonsocial',
			   'label' => 'Nombres - Razon social',
		  	   'rules' => 'trim|required|min_length[1]|max_length[25]|callback_verificarcadena_check'),
		array ('field' => 'correoelectronico',
			   'label' => 'Correo',
		  	   'rules' => 'trim|valid_email'),
		array ('field' => 'selectPuntoVenta',
			   'label' => 'Punto de Venta',
		  	   'rules' => 'trim|required')
		
		),

		'validar_venta_otro'=> array
		(
		array ('field' => 'nitcit',
			   'label' => 'NIT/CI',
		  	   'rules' => 'trim|required|min_length[1]|max_length[20]|callback_verificarcadenadocumento_check'),
		array ('field' => 'razonsocial',
			   'label' => 'Nombres - Razon social',
		  	   'rules' => 'trim|required|min_length[1]|max_length[25]|callback_verificarcadena_check'),
		array ('field' => 'correoelectronico',
			   'label' => 'Correo',
		  	   'rules' => 'trim|valid_email'),
		array ('field' => 'selectPuntoVenta',
			   'label' => 'Punto de Venta',
		  	   'rules' => 'trim|required')
		
		),




	


		'validar_ventacafc_numeros'=> array
		(
		array ('field' => 'nitcit',
			   'label' => 'NIT/CI',
		  	   'rules' => 'trim|required|min_length[1]|max_length[15]|numeric'),
		array ('field' => 'razonsocial',
			   'label' => 'Nombres - Razon social',
		  	   'rules' => 'trim|required|min_length[1]|max_length[25]|callback_verificarcadena_check'),
		array ('field' => 'correoelectronico',
			   'label' => 'Correo',
		  	   'rules' => 'trim|valid_email'),
		array ('field' => 'selectPuntoVenta',
			   'label' => 'Punto de Venta',
		  	   'rules' => 'trim|required'),

		array ('field' => 'numerofacturacafc',
			   'label' => 'Nro Factura (cafc)',
		  	   'rules' => 'trim|required|numeric'),
		array ('field' => 'fechacafc',
			   'label' => 'Fecha (cafc)',
		  	   'rules' => 'trim|required'),
		array ('field' => 'horacafc',
			   'label' => 'Hora (cafc)',
		  	   'rules' => 'trim|required')
		),


		'validar_ventacafc_letras'=> array
		(
		array ('field' => 'nitcit',
			   'label' => 'NIT/CI',
		  	   'rules' => 'trim|required|min_length[1]|max_length[15]|callback_verificarcadenadocumento_check'),
		array ('field' => 'razonsocial',
			   'label' => 'Nombres - Razon social',
		  	   'rules' => 'trim|required|min_length[1]|max_length[25]|callback_verificarcadena_check'),
		array ('field' => 'correoelectronico',
			   'label' => 'Correo',
		  	   'rules' => 'trim|valid_email'),
		array ('field' => 'selectPuntoVenta',
			   'label' => 'Punto de Venta',
		  	   'rules' => 'trim|required'),

		array ('field' => 'numerofacturacafc',
			   'label' => 'Nro Factura (cafc)',
		  	   'rules' => 'trim|required|numeric'),
		array ('field' => 'fechacafc',
			   'label' => 'Fecha (cafc)',
		  	   'rules' => 'trim|required'),
		array ('field' => 'horacafc',
			   'label' => 'Hora (cafc)',
		  	   'rules' => 'trim|required')
		),


		'validar_ventacafc_otro'=> array
		(
		array ('field' => 'nitcit',
			   'label' => 'NIT/CI',
		  	   'rules' => 'trim|required|min_length[1]|max_length[15]|callback_verificarcadenadocumento_check'),
		array ('field' => 'razonsocial',
			   'label' => 'Nombres - Razon social',
		  	   'rules' => 'trim|required|min_length[1]|max_length[25]|callback_verificarcadena_check'),
		array ('field' => 'correoelectronico',
			   'label' => 'Correo',
		  	   'rules' => 'trim|valid_email'),
		array ('field' => 'selectPuntoVenta',
			   'label' => 'Punto de Venta',
		  	   'rules' => 'trim|required'),

		array ('field' => 'numerofacturacafc',
			   'label' => 'Nro Factura (cafc)',
		  	   'rules' => 'trim|required|numeric'),
		array ('field' => 'fechacafc',
			   'label' => 'Fecha (cafc)',
		  	   'rules' => 'trim|required'),
		array ('field' => 'horacafc',
			   'label' => 'Hora (cafc)',
		  	   'rules' => 'trim|required')
		),


		'validar_ingreso_cantidad'=> array
		(
		array ('field' => 'txtCantidad',
				   'label' => 'CANTIDAD DE INGRESO',
			  	   'rules' => 'trim|required|min_length[1]|max_length[10]|callback_verificarlmayorcero_check'),
		array ('field' => 'txtPrecio',
				   'label' => 'PRECIO UNITARIO DE COMPRA',
			  	   'rules' => 'trim|required|min_length[1]|max_length[10]|callback_verificarlmayorcero_check'),
		array ('field' => 'txtPrecioVenta',
				   'label' => 'PRECIO UNITARIO DE VENTA',
			  	   'rules' => 'trim|required|min_length[1]|max_length[10]|callback_verificarlmayorcero_check'),		
		
		),


		'validar_ingreso_cantidad_vencimiento'=> array
		(
		array ('field' => 'txtCantidad',
				   'label' => 'CANTIDAD DE INGRESO',
			  	   'rules' => 'trim|required|min_length[1]|max_length[10]|callback_verificarlmayorcero_check'),
		array ('field' => 'txtPrecio',
				   'label' => 'PRECIO UNITARIO DE COMPRA',
			  	   'rules' => 'trim|required|min_length[1]|max_length[10]|callback_verificarlmayorcero_check'),
		array ('field' => 'txtPrecioVenta',
				   'label' => 'PRECIO UNITARIO DE VENTA',
			  	   'rules' => 'trim|required|min_length[1]|max_length[10]|callback_verificarlmayorcero_check'),

		array ('field' => 'txtFechaVencimiento',
			   'label' => 'FECHA VENCIMIENTO',
			    'rules'=>'trim|required|callback_verificarfecha_check'),	
		
		),

		

		'validar_evento_simple'=> array
		(
		array ('field' => 'selectEvento',
			   'label' => 'Tipo de Evento Significativo',
			   'rules' => 'callback_verificarValorCombo'),
		
		),

		'validar_evento_compuesto'=> array
		(
		array ('field' => 'selectEvento',
			   'label' => 'Tipo de Evento Significativo',
			   'rules' => 'callback_verificarValorCombo'),

		array ('field' => 'selectCufd',
			   'label' => 'CUFD',
			   'rules' => 'callback_verificarValorCombo'),

		
		array ('field' => 'evefini',
			   'label' => 'Fecha Inicio',
			   'rules' => 'trim|required|min_length[10]|max_length[15]'),

		array ('field' => 'evehini',
			   'label' => 'Hora Inicio',
			   'rules' => 'trim|required|min_length[8]|max_length[15]'),

		array ('field' => 'eveffin',
			   'label' => 'Fecha Fin',
			   'rules' => 'trim|required|min_length[10]|max_length[15]'),

		array ('field' => 'evehfin',
			   'label' => 'Hora Fin',
			   'rules' => 'trim|required|min_length[8]|max_length[15]'),
		),


		'validar_configuracion'=> array
		(
		array ('field' => 'txtNombreSistema',
			   'label' => 'Nombre Sistema',
			   'rules' => 'trim|required|min_length[1]|max_length[20]'),
		
		),


		'validar_roles_usuarios'=> array
		(
		array ('field' => 'tipousuario',
			   'label' => 'TIPO USUARIO',
		  	   'rules' => 'callback_verificarValorCombo'),
		array ('field' => 'selectSucursal',
			   'label' => 'Sucursal',
		  	   'rules' => 'callback_verificarValorCombo'),
		array ('field' => 'selectPuntoVenta',
			   'label' => 'Punto de Venta',
		  	   'rules' => 'callback_verificarValorCombo'),
		
		),

		'validar_personas'=> array
		(
		array ('field' => 'txtnombres',
			   'label' => 'NOMBRES',
			   'rules' => 'trim|required|min_length[2]|max_length[100]'),
		array ('field' => 'txtprimerapellido',
			   'label' => 'PRIMER APELLIDO',
			   'rules' => 'trim|required|min_length[2]|max_length[100]'),
		array ('field' => 'txtsegundoapellido',
			   'label' => 'SEGUNDO APELLIDO',
			   'rules' => 'trim|min_length[2]|max_length[100]'),
		array ('field' => 'txtcedula',
			   'label' => 'CÉDULA DE IDENTIDAD',
			   'rules' => 'trim|required|min_length[2]|max_length[100]'),
		array ('field' => 'txtfechanacimiento',
			   'label' => 'FECHA DE NACIMIENTO',
			   'rules' => 'trim|required|min_length[2]'),

		array ('field' => 'txtpuesto',
			   'label' => 'NOMBRE PUESTO EN LA EMPRESA',
			   'rules' => 'trim|min_length[2]|max_length[100]'),
		array ('field' => 'txttelefono',
			   'label' => 'TELÉFONO',
			   'rules' => 'trim|min_length[2]|max_length[100]'),
		array ('field' => 'txtdireccion',
			   'label' => 'DIRECCIÓN',
			   'rules' => 'trim|min_length[2]|max_length[100]'),
		array ('field' => 'txtcorreo',
			   'label' => 'CORREO',
			   'rules' => 'trim|min_length[2]|max_length[100]'),
		
		
		),

		'validar_personas_update'=> array
		(
		
		array ('field' => 'txtpuesto',
			   'label' => 'NOMBRE PUESTO EN LA EMPRESA',
			   'rules' => 'trim|min_length[2]|max_length[100]'),
		array ('field' => 'txttelefono',
			   'label' => 'TELÉFONO',
			   'rules' => 'trim|min_length[6]|numeric|max_length[10]'),
		array ('field' => 'txtdireccion',
			   'label' => 'DIRECCIÓN',
			   'rules' => 'trim|min_length[2]|max_length[100]'),
		array ('field' => 'txtcorreo',
			   'label' => 'CORREO',
			   'rules' => 'trim|min_length[2]|max_length[100]'),
		),
		
		'validar_empresa'=> array
		(
		array ('field' => 'txtNombreEmpresa',
			   'label' => 'Nombre Empresa',
		  	    'rules' => 'trim|required|min_length[2]|max_length[100]'),
		array ('field' => 'txtSigla',
			   'label' => 'Sigla',
		  	  'rules' => 'trim|required|min_length[2]|max_length[100]'),
		array ('field' => 'txtPropietario',
			   'label' => 'Propietario / Representante Legal',
		  	  'rules' => 'trim|required|min_length[2]|max_length[100]'),
		array ('field' => 'txtTelefono',
			   'label' => 'Teléfono',
		  	  'rules' => 'trim|required|numeric|min_length[2]|max_length[100]'),
		array ('field' => 'txtDireccion',
			   'label' => 'Dirección',
		  	  'rules' => 'trim|required|min_length[2]|max_length[100]'),
		array ('field' => 'txtCorreo',
			   'label' => 'Correo Empresa',
		  	   'rules' => 'trim|required|valid_email|min_length[2]|max_length[100]'),


		array ('field' => 'txtDescripcion',
			   'label' => 'Descripción Empresa',
		  	   'rules' => 'trim|min_length[10]|max_length[250]'),

		array ('field' => 'txtObjetivo',
			   'label' => 'Objetivo de Empresa',
		  	   'rules' => 'trim|min_length[10]|max_length[250]'),

		array ('field' => 'txtMision',
			   'label' => 'Misión de Empresa',
		  	   'rules' => 'trim|min_length[10]|max_length[250]'),

		array ('field' => 'txtfacebook',
			   'label' => 'Link Facebook',
		  	   'rules' => 'trim|min_length[5]|valid_url|max_length[100]'),

		array ('field' => 'txtinstagram',
			   'label' => 'Link Instagram',
		  	   'rules' => 'trim|min_length[5]|valid_url|max_length[100]'),

		array ('field' => 'txtWhatsapp',
			   'label' => 'Link Whatsapp',
		  	   'rules' => 'trim|min_length[5]|valid_url|max_length[100]'),
		
		),

		'validar_registrar_venta_numeros'=> array
		(
		array ('field' => 'nitcit',
			   'label' => 'NIT/CI',
		  	   'rules' => 'trim|required|min_length[1]|max_length[15]|numeric'),
		array ('field' => 'razonsocial',
			   'label' => 'Nombres - Razon social',
		  	   'rules' => 'trim|required|min_length[1]|max_length[25]|callback_verificarcadena_check'),
		array ('field' => 'correoelectronico',
			   'label' => 'Correo',
		  	   'rules' => 'trim|valid_email'),
		array ('field' => 'tipoOperacion',
			   'label' => 'Tipo Operación',
		  	   'rules' => 'callback_verificarValorCombo')
		
		),


		'validar_registrar_venta_numeros_venta'=> array
		(
		array ('field' => 'nitcit',
			   'label' => 'NIT/CI',
		  	   'rules' => 'trim|required|min_length[1]|max_length[15]|numeric'),
		array ('field' => 'razonsocial',
			   'label' => 'Nombres Cliente :',
		  	   'rules' => 'trim|required|min_length[1]|max_length[25]|callback_verificarcadena_check'),
		array ('field' => 'correoelectronico',
			   'label' => 'Correo',
		  	   'rules' => 'trim|valid_email'),
		array ('field' => 'tipoOperacion',
			   'label' => 'Tipo Operación',
		  	   'rules' => 'callback_verificarValorCombo')
		
		),


		'validar_registrar_venta_letras'=> array
		(
		array ('field' => 'nitcit',
			   'label' => 'NIT/CI',
		  	   'rules' => 'trim|required|min_length[1]|max_length[20]|callback_verificarcadenadocumento_check'),
		array ('field' => 'razonsocial',
			   'label' => 'Nombres - Razon social',
		  	   'rules' => 'trim|required|min_length[1]|max_length[25]|callback_verificarcadena_check'),
		array ('field' => 'correoelectronico',
			   'label' => 'Correo',
		  	   'rules' => 'trim|valid_email'),
		array ('field' => 'tipoOperacion',
			   'label' => 'Tipo Operación',
		  	   'rules' => 'callback_verificarValorCombo')
		
		),

		'validar_registrar_venta_otro'=> array
		(
		array ('field' => 'nitcit',
			   'label' => 'NIT/CI',
		  	   'rules' => 'trim|required|min_length[1]|max_length[20]|callback_verificarcadenadocumento_check'),
		array ('field' => 'razonsocial',
			   'label' => 'Nombres - Razon social',
		  	   'rules' => 'trim|required|min_length[1]|max_length[25]|callback_verificarcadena_check'),
		array ('field' => 'correoelectronico',
			   'label' => 'Correo',
		  	   'rules' => 'trim|valid_email'),
		array ('field' => 'tipoOperacion',
			   'label' => 'Tipo Operación',
		  	   'rules' => 'callback_verificarValorCombo')
		
		),

		'validar_kit'=> array
		(
		array ('field' => 'txtcodigokits',
			   'label' => 'CÓDIGO',
		  	   'rules' => 'trim|required|min_length[3]|max_length[30]'),

		array ('field' => 'txtprecio',
			   'label' => 'PRECIO KITS',
		  	   'rules' => 'trim|required|min_length[1]|max_length[10]|callback_verificarprecio_check'),
		
		),
		

		
	);
?>