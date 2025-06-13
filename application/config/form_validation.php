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

		
		'validar_categoria_editar'=> array
		(
		array ('field' => 'txtcodigo',
			   'label' => 'CÓDIGO',
		  	   'rules' => 'trim|required|min_length[3]|max_length[30]'),

		array ('field' => 'txtdescripcion',
			   'label' => 'DESCRIPCIÓN',
		  	   'rules' => 'trim|required|min_length[3]|max_length[30]')
		
		),

		
		
	);
?>