<?php
$config = array(
	
		'validar_registro_movimiento_cuenta'=> array
		(
		
		array('field' => 'txtCuenta',
			  'label' => 'Cuenta',
			  'rules' => 'trim|required|callback_verificarValorCuentaBusqueda'),
		// array(
		// 	  'field' => 'id_cuenta',
		// 	  'label' => 'ID de la Cuenta',
		// 	  'rules' => 'trim|required'
		// 	  ),
		array ('field' => 'txtTipoMovimiento',
			   'label' => 'txtTipoMovimiento',
			   'rules' => 'callback_verificarValorCombo'),
		array ('field' => 'txtImporte',
			   'label' => 'IMPORTE',
			   'rules' => 'trim|required'),
		array ('field' => 'txtGlosaCuenta',
			   'label' => 'GLOSA CUENTA',
		  	   'rules' => 'trim|required|min_length[3]|max_length[250]'),
		
		),

		
		'validar_registro_comprobante'=> array
		(

		array ('field' => 'nombre_entidad',
			   'label' => 'ENTIDAD',
		  	   'rules' => 'trim|required'),

		array ('field' => 'txtTipo',
			   'label' => 'TIPO DE COMPROBANTE',
			   'rules' => 'callback_verificarValorCombo'),
		array(
			  'field' => 'txtFecha',
			  'label' => 'FECHA DE COMPROBANTE',
			  'rules' => 'trim|required|callback_fecha_valida'),

		array ('field' => 'txtTipoCambio',
			   'label' => 'IMPORTE',
			   'rules' => 'trim|required'),

		array ('field' => 'txtGlosaGeneral',
			   'label' => 'GLOSA GENERAL',
		  	   'rules' => 'trim|required|min_length[3]|max_length[500]')
		
		),

		
		
	);
?>