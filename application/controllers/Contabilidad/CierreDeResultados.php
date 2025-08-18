<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . "libraries/fpdf/easyTable.php";
require_once APPPATH . "libraries/fpdf/fpdfde.php";
require_once APPPATH . "libraries/fpdf/exfpdfCartaContable.php";

class CierreDeResultados extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->_is_logued_in();
		$this->load->library('form_validation');
        $this->load->model('Comprobantes_model');
        $this->load->model('PlanDeCuentas_model');
        $this->load->model('EstadoDeResultado_model');
		$this->load->helper('configuraciones_helper');
		$this->load->helper('funcionarios_helper');
		$this->load->helper('correlativos_helper');
	}
    function _is_logued_in()
	{
		$is_logued_in = $this->session->userdata('is_logued_in');
		$id_apliacion = $this->session->userdata('id_apliacion');
		$aplicacion =   $this->config->item('IDAPLICACION');
		if($is_logued_in != TRUE || $id_apliacion != $aplicacion)
		{
			redirect('Login');
		}
	}
	public function index()
	{

		$dato['nombre_usuario']  = $this->session->userdata('nombre_usuario');		
		$dato['nombre_sistema']  = "MERCURIO";
		$dato['tipo_sistema']  = "Sistema Contable";
		
		
		$id_usuario = $this->session->userdata('id_usuario');
		$dato['rolescero'] = $this->session->userdata('rolescero');
		$dato['roles']  = $this->session->userdata('roles');
		$dato['nombre_usuario']  = $this->session->userdata('nombre_completo');

		$titulo = "Cierre de Cuentas de Resultados";		
		$dato['titulo'] = $titulo;

		$this->load->view('inicio/cabecera',$dato);
		$this->load->view('inicio/menu',$dato);
		$this->load->view('contabilidad/cierrederesultados',$dato);
		$this->load->view('inicio/pie');
	}
	private function ordenarJerarquicamenteCuentasOrdenEstadoDeResultados(array $cuentas, int $padreId = 0, int $indentacion = 0,bool $excluirDesdeNivelDos=false,int $nivelMaximo = null)
	{
		$ordenadas     = [];
		$totalImporte  = 0;
		$totalImporteUSD  = 0;

		foreach ($cuentas as &$cuenta) {
			if ($cuenta['padre'] == $padreId) {

				// --- ¿Tiene hijos? -------------------------------------------------
				$tieneHijos = false;
				foreach ($cuentas as $posibleHijo) {
					if (
						$posibleHijo['padre'] == $cuenta['id'] ||
						$posibleHijo['padre'] == $cuenta['ruta']   // para cuentas mayores
					) {
						$tieneHijos = true;
						break;
					}
				}

				// --- Procesar hijos recursivamente --------------------------------
				// [$hijosOrdenados, $sumaHijos] =$this->ordenarJerarquicamenteCuentasOrden($cuentas, $cuenta['id'], $indentacion + 1,$excluirDesdeNivelDos);
				[$hijosOrdenados, $sumaHijos,$sumaHijosUSD] =
					$this->ordenarJerarquicamenteCuentasOrdenEstadoDeResultados(
						$cuentas, 
						$cuenta['id'], 
						$indentacion + 1,
						$excluirDesdeNivelDos,
						$nivelMaximo
					);

				// --- Sumar importe propio + importe hijos -------------------------
				$importePropio              = isset($cuenta['saldo'])
												? (float) $cuenta['saldo']
												: 0;
				$importePropioUSD              = isset($cuenta['saldousd'])
												? (float) $cuenta['saldousd']
												: 0;
				// Si el nivel máximo está definido y la cuenta está en ese nivel máximo, sumamos saldo hijos
				// if ($nivelMaximo !== null && isset($cuenta['nivel']) && $cuenta['nivel'] == $nivelMaximo) {
				
				// if ( isset($cuenta['nivel']) && $cuenta['nivel'] >= $nivelMaximo) {
				// 	$saldoConHijos 		= $importePropio + $sumaHijos;
				// 	$saldoConHijosUSD   = $importePropioUSD + $sumaHijosUSD;
				// } else {
				// 	// No sumamos hijos, solo saldo propio
				// 	$saldoConHijos = $importePropio;
				// 	$saldoConHijosUSD = $importePropioUSD;
				// }

				$saldoConHijos 		= $importePropio + $sumaHijos;
				$saldoConHijosUSD   = $importePropioUSD + $sumaHijosUSD;

				$cuenta['saldo'] 			 = $saldoConHijos;
				$cuenta['importe_total'] 	 = $importePropio + $sumaHijos;
				$cuenta['saldousd'] 		 = $saldoConHijosUSD;
				$cuenta['importe_total_USD'] = $importePropioUSD + $sumaHijosUSD;

				// $cuenta['importe_total']    = $importePropio + $sumaHijos;			
				// --- Campos extra --------------------------------------------------
				$cuenta['indentacion']      = $indentacion;
				$cuenta['es_padre']         = $tieneHijos;

				// --- Añadir al resultado ------------------------------------------

				$agregarCuenta =true;
				// Si hay límite de nivel y esta cuenta está por debajo, no se muestra
				if ( isset($cuenta['nivel']) && $cuenta['nivel'] > $nivelMaximo) {
					$agregarCuenta = false;
				}

				// if($excluirEnCero && $cuenta['importe_total'] == 0)
				//EXCLUYE CUENTAS EN 0 DESDE EL NIVEL DOS
				// if(
				// 	$excluirDesdeNivelDos &&
				// 	isset($cuenta['nivel']) &&
				// 	$cuenta['nivel'] >=2 &&
				// 	$importePropio == 0) 
				if(
					$excluirDesdeNivelDos &&
					isset($cuenta['nivel']) &&
					$cuenta['nivel'] >=2 &&
					$saldoConHijos == 0) 
				{
					$agregarCuenta = false;
				}
				
				if($agregarCuenta)
				{
					$ordenadas[] = $cuenta;
				}

				// $ordenadas   = array_merge($ordenadas, $hijosOrdenados);


				// Agregar hijos solo si no hay límite de nivel o si el hijo está permitido
				if ($nivelMaximo === null || (isset($cuenta['nivel']) && $cuenta['nivel'] <= $nivelMaximo)) {
					$ordenadas = array_merge($ordenadas, $hijosOrdenados);
				}

				$totalImporte += $cuenta['importe_total'];
				$totalImporteUSD += $cuenta['importe_total_USD'];
			}
		}
		unset($cuenta);   // rompe la referencia del foreach

		/*───────────────────────────────────────────────────────────────
		Invertir la indentación EN ESTE BLOQUE devuelto, sea raíz
		o subárbol: buscamos la profundidad máxima dentro de $ordenadas
		───────────────────────────────────────────────────────────────*/

		if ($ordenadas) {
			$profMax = max(array_column($ordenadas, 'indentacion'));

			foreach ($ordenadas as &$c) {
				$c['indentacion_invertida'] = $profMax - $c['indentacion'];
			}
			unset($c);
		}

		return [$ordenadas, $totalImporte,$totalImporteUSD];
	}

	public function cargarDatosEstadoDeResultadosIngreso()
	{
		$id_usuario  = $this->session->userdata('id_usuario');
			
		$draw    = intval($this->input->get("draw"));
		$start   = intval($this->input->get("start"));
		$length  = intval($this->input->get("length"));	
		$data    = array();
		$num     = 1;

		// $id_entidad            = $this->input->post('id_entidad');
		// $fecha_inicio  		   = $this->input->post('fecha_inicio');
		// $fecha_fin             = $this->input->post('fecha_fin');
		// // $id_cuenta             = $this->input->post('id_cuenta');
		// $moneda                  = $this->input->post('moneda');
		// $nivel				     = $this->input->post('nivel');
		// $saldoCero  			 = $this->input->post('saldoCero');
		// $cuentasSeleccionadas    = $this->input->post('cuentasSeleccionadas');

		$codigo_cuenta_ingreso     = 4;
		$id_cuenta_ingreso   	   = getIdCuenta($codigo_cuenta_ingreso);
		$codigo_cuenta_egreso      = 5;
		$id_cuenta_egreso   	   = getIdCuenta($codigo_cuenta_egreso);

		$id_entidad	  = 1;
		$nivel		  = 0;
		$moneda		  = 'BOB';
		$saldoCero	  = true;
		$fecha_inicio = '2025-01-01';
		$fecha_fin    = '2025-08-18';	

		if($nivel== 0)
		{
			$nivel=getNivelMaximo();
			// $nivel=4;
		}

		if($saldoCero == "true")
		{
			$excluirCuentasEnCero      = false;
		}
		else
		{
			$excluirCuentasEnCero      = true;
		}
		// echo("Fecha Inicio".$fecha_inicio."<br>");
		// echo("Fecha Fin".$fecha_fin."<br>");
		
		$estadoResultadoIngreso    = $this->EstadoDeResultado_model->getEstadoDeResultadosIngreso($id_entidad,$fecha_inicio,$fecha_fin,$id_cuenta_ingreso,$codigo_cuenta_ingreso);
		$cuentasIngreso  	       = json_decode(json_encode($estadoResultadoIngreso), true);		
		$ordenadas_cuentas_ingreso = $this->ordenarJerarquicamenteCuentasOrdenEstadoDeResultados($cuentasIngreso ,0,0,$excluirCuentasEnCero,$nivel);
		$cuentasOrdenadasIngreso   = $ordenadas_cuentas_ingreso[0];

		$resultado    = $this->EstadoDeResultado_model->getMontoResultado($id_entidad,$fecha_inicio,$fecha_fin,$id_cuenta_ingreso,$codigo_cuenta_ingreso,$id_cuenta_egreso,$codigo_cuenta_egreso);
		if($moneda === 'BOB'){
				$sumaTotalGlobalIngreso    = $ordenadas_cuentas_ingreso[1];
				if (!empty($resultado) && isset($resultado[0]->total_estado_resultado) ) {
					$total_resultado = $resultado[0]->total_estado_resultado;
				} else {
					$total_resultado = 0; 
				}
		}
		elseif ($moneda === 'USD') {
				$sumaTotalGlobalIngreso    = $ordenadas_cuentas_ingreso[2];
				if (!empty($resultado) && isset($resultado[0]->total_estado_resultadousd) ) {
						$total_resultado = $resultado[0]->total_estado_resultadousd;
					} else {
						$total_resultado = 0; 
					}
		} 
		
		
		$totalSaldoAcreedor =0;
		foreach ($cuentasOrdenadasIngreso as $cuenta)
		{   
			$codigo 		 	   = $cuenta['codigo'];
			$nivel 		 	 	   = $cuenta['nivel'];
			$descripcion     	   = $cuenta['descripcion'];
			// $indentacion_invertida = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $cuenta['indentacion_invertida']);
			$indentacion           = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $cuenta['indentacion']);
			// $padding_px 		   = $cuenta['nivel'] * 20; // 20px por nivel
			if($moneda === 'BOB'){
				$saldoAcreedor	 = $cuenta['saldo'];
			}
			elseif ($moneda === 'USD') {
				$saldoAcreedor	 = $cuenta['saldousd'];
			} 

			$data[] = array(
				"<span class='badge badge-secondary'>".$codigo."</span>",
				"<span style='text-align: left; '>".$nivel."</span>",	
				"<span style='text-align: left; '>".$indentacion.$descripcion."</span>",	
				"<div style='text-align: right; color: #28a745; font-weight: bold; white-space: pre;'>"
				. number_format($saldoAcreedor, 2, '.', ',') .$indentacion.
				"</div>"		
				
			);
			// $totalSaldoAcreedor+=$saldoAcreedor;
							
		}		
		$output =( array(
			             "     resultado" => 1, 
		                  "nro_registros" => count($cuentasOrdenadasIngreso) , 
					 "totalSaldoAcreedor" => number_format($sumaTotalGlobalIngreso,2,'.',','),
					     "totalResultado" => number_format($total_resultado,2,'.',','),
						           "data" => $data ) );

		echo json_encode($output);
		exit();

	}
	public function cargarDatosEstadoDeResultadosEgreso()
	{
		$id_usuario  = $this->session->userdata('id_usuario');
			
		$draw    = intval($this->input->get("draw"));
		$start   = intval($this->input->get("start"));
		$length  = intval($this->input->get("length"));	
		$data    = array();
		$num     = 1;

		// $id_entidad            = $this->input->post('id_entidad');
		// $fecha_inicio  		   = $this->input->post('fecha_inicio');
		// $fecha_fin             = $this->input->post('fecha_fin');
		// // // $id_cuenta             = $this->input->post('id_cuenta');
		// $moneda                = $this->input->post('moneda');
		// $nivel				   = $this->input->post('nivel');
		// $saldoCero  			 = $this->input->post('saldoCero');
		// $cuentasSeleccionadas    = $this->input->post('cuentasSeleccionadas');
		$codigo_cuenta_ingreso     = 4;
		$id_cuenta_ingreso   	   = getIdCuenta($codigo_cuenta_ingreso);
		$codigo_cuenta_egreso      = 5;
		$id_cuenta_egreso   	   = getIdCuenta($codigo_cuenta_egreso);


		$id_entidad	  = 1;
		$nivel		  = 0;
		$moneda		  = 'BOB';
		$saldoCero	  = true;
		$fecha_inicio = '2025-01-01';
		$fecha_fin    = '2025-08-18';


		if($nivel== 0)
		{
			$nivel=getNivelMaximo();
		}


		if($saldoCero == "true")
		{
			$excluirCuentasEnCero      = false;
		}
		else
		{
			$excluirCuentasEnCero      = true;
		}

		$estadoResultadoEgreso     = $this->EstadoDeResultado_model->getEstadoDeResultadosEgreso($id_entidad,$fecha_inicio,$fecha_fin,$id_cuenta_egreso,$codigo_cuenta_egreso );
		$cuentasDeEgreso		   = json_decode(json_encode($estadoResultadoEgreso), true);		
		$ordenadas_cuentas_egreso  = $this->ordenarJerarquicamenteCuentasOrdenEstadoDeResultados($cuentasDeEgreso ,0,0,$excluirCuentasEnCero,$nivel);
		$cuentasOrdenadasEgreso    = $ordenadas_cuentas_egreso[0];
		
		
		$resultado    = $this->EstadoDeResultado_model->getMontoResultado($id_entidad,$fecha_inicio,$fecha_fin,$id_cuenta_ingreso,$codigo_cuenta_ingreso,$id_cuenta_egreso,$codigo_cuenta_egreso);

		if($moneda === 'BOB'){
			$sumaTotalGlobalEgreso     = $ordenadas_cuentas_egreso[1];
			if (!empty($resultado) && (isset($resultado[0]->total_estado_resultado)||isset($resultado[0]->total_estado_resultado))) {
				$total_resultado = $resultado[0]->total_estado_resultado;
			} else {
				$total_resultado = 0; 
			}
		}
		elseif ($moneda === 'USD') {
			$sumaTotalGlobalEgreso  = $ordenadas_cuentas_egreso[2];
			if (!empty($resultado) && (isset($resultado[0]->total_estado_resultado)||isset($resultado[0]->total_estado_resultadousd))) {
				$total_resultado = $resultado[0]->total_estado_resultadousd;
			} else {
				$total_resultado = 0; 
			}
		} 

	
		$totalSaldoDeudor =0;

		foreach ($cuentasOrdenadasEgreso as $cuenta)
		{   
			$codigo 		 = $cuenta['codigo'];
			$descripcion     = $cuenta['descripcion'];
			$nivel     		 = $cuenta['nivel'];
			$indentacion           = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $cuenta['indentacion']);
			if($moneda === 'BOB'){
				$totalDeudor	 = $cuenta['saldo'];
			}
			elseif ($moneda === 'USD') {
				$totalDeudor	 = $cuenta['saldousd'];
			} 

			$data[] = array(
				"<span class='badge badge-secondary'>".$codigo."</span>",
				"<span style='text-align: left; '>".$nivel."</span>",	
				"<span style='text-align: left; '>".$indentacion.$descripcion."</span>",	
				"<div style='text-align: right; color: #dc3545; font-weight: bold;'>
				".number_format($totalDeudor,2,'.',',').$indentacion."</div>",				
			);
			// $totalSaldoDeudor+=$totalDeudor;
							
		}		
		$output =( array(
			             "     resultado" => 1, 
		                  "nro_registros" => count($cuentasOrdenadasEgreso) , 
					   "totalSaldoDeudor" => number_format($sumaTotalGlobalEgreso,2,'.',','),
					     "totalResultado" => number_format($total_resultado,2,'.',','),
						           "data" => $data ) );

		echo json_encode($output);
		exit();

	}
	public function cerrarCuentaDeResultados()
	{
		$this->db->trans_start();
		$id_usuario  = $this->session->userdata('id_usuario');	
		$codigo_cuenta_ingreso     = 4;
		$id_cuenta_ingreso   	   = getIdCuenta($codigo_cuenta_ingreso);
		$codigo_cuenta_egreso      = 5;
		$id_cuenta_egreso   	   = getIdCuenta($codigo_cuenta_egreso);


		$id_entidad	  = 1;
		$nivel		  = 0;
		$moneda		  = 'BOB';
		$saldoCero	  = true;
		$fecha_inicio = '2025-01-01';
		$fecha_fin    = '2025-08-18';
		$fecha_actual = getFechaHoraActual();

		if($nivel== 0)
		{
			$nivel=getNivelMaximo();
		}
		if($saldoCero == "true")
		{
			$excluirCuentasEnCero      = false;
		}
		else
		{
			$excluirCuentasEnCero      = true;
		}

		$estadoResultadoIngreso    = $this->EstadoDeResultado_model->getEstadoDeResultadosIngreso($id_entidad,$fecha_inicio,$fecha_fin,$id_cuenta_ingreso,$codigo_cuenta_ingreso);
		$cuentasIngreso  	       = json_decode(json_encode($estadoResultadoIngreso), true);		
		$ordenadas_cuentas_ingreso = $this->ordenarJerarquicamenteCuentasOrdenEstadoDeResultados($cuentasIngreso ,0,0,$excluirCuentasEnCero,$nivel);
		$cuentasOrdenadasIngreso   = $ordenadas_cuentas_ingreso[0];

		$estadoResultadoEgreso     = $this->EstadoDeResultado_model->getEstadoDeResultadosEgreso($id_entidad,$fecha_inicio,$fecha_fin,$id_cuenta_egreso,$codigo_cuenta_egreso );
		$cuentasDeEgreso		   = json_decode(json_encode($estadoResultadoEgreso), true);		
		$ordenadas_cuentas_egreso  = $this->ordenarJerarquicamenteCuentasOrdenEstadoDeResultados($cuentasDeEgreso ,0,0,$excluirCuentasEnCero,$nivel);
		$cuentasOrdenadasEgreso    = $ordenadas_cuentas_egreso[0];
		
		// $sumaTotalGlobalIngreso    = $ordenadas_cuentas_ingreso[1];

		$resultado    = $this->EstadoDeResultado_model->getMontoResultado($id_entidad,$fecha_inicio,$fecha_fin,$id_cuenta_ingreso,$codigo_cuenta_ingreso,$id_cuenta_egreso,$codigo_cuenta_egreso);
		if($moneda === 'BOB'){
				$sumaTotalGlobalIngreso    = $ordenadas_cuentas_ingreso[1];
				$sumaTotalGlobalEgreso     = $ordenadas_cuentas_egreso[1];
				if (!empty($resultado) && isset($resultado[0]->total_estado_resultado) ) {
					$total_resultado = $resultado[0]->total_estado_resultado;
				} else {
					$total_resultado = 0; 
				}
		}
		elseif ($moneda === 'USD') {
				$sumaTotalGlobalIngresoUSD    = $ordenadas_cuentas_ingreso[2];
				$sumaTotalGlobalEgreso  	  = $ordenadas_cuentas_egreso[2];
				if (!empty($resultado) && isset($resultado[0]->total_estado_resultadousd) ) {
						$total_resultadousd = $resultado[0]->total_estado_resultadousd;
					} else {
						$total_resultadousd = 0; 
					}
		} 



		// echo("<pre>");
		// print_r($estadoResultadoIngreso);
		// echo("</pre>");
		// die();

		// CUENTAS DE RESULTADOS ACUMULADOS -INGRESO
		$idCuentaCierre 	  = 54; ///ojo
		$idCuentaSaldoCierre  = 54; ///ojo
		$tipo_comprobante     = 'TR';
		$id_dependencia       = $this->session->userdata('id_dependencia_principal');
		$fecha_comprobante    = '2025-01-01';
		$periodo        	  = (int)date('m', strtotime($fecha_comprobante));
		$gestion        	  = date("Y", strtotime($fecha_comprobante));
		$referencia_general   = "*****";
		$glosa_general        = "*****";
		$tipo_cambio	      = "6.96";
		$tipo_cierre          = "CIR";
		// $tipo_movimiento	  = "DB";
		$contador=3;
		$estado_resultado     = 'CNS';//CONSOLIDADO
		$comprobantes ='';
		$id_comprobante=0;
		for($i=1 ;$i<=$contador;$i++)
		{

			$tipoCorrelativo  	  = $tipo_comprobante ;
			$datosCorrelativo     = json_decode(obtenerCorrelativoComprobanteGestionEntidad($tipoCorrelativo,$id_entidad,$id_dependencia, $gestion));
			$idcorrelativoentidadgestion    = $datosCorrelativo[0]->idcorrelativoentidadgestion;
			$correlativo      		        = $datosCorrelativo[0]->correlativo;			
			$datosComprobante = array(
				'id_entidad'              => $id_entidad,
				'tipo_comprobante'        => $tipo_comprobante,
				'correlativo'             => $correlativo,
				'periodo'                 => $periodo,
				'gestion'                 => $gestion,
				'referencia_comprobante'  => $referencia_general,
				'glosa_comprobante' 	  => $glosa_general,
				'fecha_comprobante'       => $fecha_comprobante,
				'tipo_cambio'             => $tipo_cambio,
				'id_usuario_registro'     => $id_usuario,
				"tipo_cierre"			  => $tipo_cierre
			);
			

			$saveComprobante = $this->Comprobantes_model->guardarComprobante($datosComprobante);
			$comprobantes = $comprobantes."-".$saveComprobante;
			$updateCorrelativoEntidadGestion = array(
				'correlativo' => $correlativo,
				'fecha_modificacion' => $fecha_actual
			);	

			$data = $this->Correlativos_model->updateCorrelativoEntidadGestion($idcorrelativoentidadgestion,$updateCorrelativoEntidadGestion);

			if($saveComprobante)
			{
				if($i==3)
				{
					$id_comprobante = $saveComprobante;
				}

				/*DETALLE DE CUENTA DE INGRESO*/
				if($i==1)//primero se registrara el comprobante de ingreso	
				{
							
					foreach($estadoResultadoIngreso as $cuenta)
					{
						$id_cuenta = $cuenta->id;
						$saldo     = $cuenta->saldo;
						$saldoUSD  = $cuenta->saldousd;
						if($saldo > 0)
						{
							$tipo_movimiento='HB';
						}
						else
						{
							$tipo_movimiento='DB';
						}
						$tipo_cambio  = 6.96;
						$glosa_cuenta = '';

						/*REGISTRO DE CUENTAS DETALLE COMPROBANTE*/

						$datosComprobanteDetalle = array(
							'id_entidad'	            => $id_entidad,
							'id_comprobante'            => $saveComprobante,
							'id_cuenta'                 => $id_cuenta,
							'tipo_movimiento'           => $tipo_movimiento,
							'tipo_cambio'               => $tipo_cambio,
							'importe_moneda_nacional'   => $saldo,
							'importe_moneda_extranjera' => $saldoUSD,
							'glosa_cuenta'              => $glosa_cuenta,
							'id_usuario_registro'       => $id_usuario,
							'estado_resultado'          => $estado_resultado									
						);
						$detalle_comprobante = $this->Comprobantes_model->guardarDetalleComprobante($datosComprobanteDetalle);
						if($detalle_comprobante)
						{
							
							$resul = 1;
							$mensaje = "SE REGISTRO CORRECTAMENTE";
						}
						else {
							$resul = 0;
							$mensaje = "ERROR EN EL REGISTRO!!!";
						}

					}
					/*ASIENTO CONTABLE*/
					$tipo_movimiento	  = "HB";
					$glosa_cuenta		  = "";
					$datosComprobanteDetalle = array(
						'id_entidad'	            => $id_entidad,
						'id_comprobante'            => $saveComprobante,
						'id_cuenta'                 => $idCuentaCierre,
						'tipo_movimiento'           => $tipo_movimiento,
						'tipo_cambio'               => $tipo_cambio,
						'importe_moneda_nacional'   => $sumaTotalGlobalIngreso,
						'importe_moneda_extranjera' => $sumaTotalGlobalIngresoUSD,
						'glosa_cuenta'              => $glosa_cuenta,
						'id_usuario_registro'       => $id_usuario,
						'estado_resultado'          => $estado_resultado				
					);
					$detalle_comprobante = $this->Comprobantes_model->guardarDetalleComprobante($datosComprobanteDetalle);	


					// $updateCorrelativoEntidadGestion = array(
					// 	'correlativo' => $correlativo,
					// 	'fecha_modificacion' => $fechaActual
					// );	

					// $data = $this->Correlativos_model->updateCorrelativoEntidadGestion($idcorrelativoentidadgestion,$updateCorrelativoEntidadGestion);

				}
				elseif($i==2)
				{
					/*DETALLE DE CUENTA DE GASTO O EGRESO*/
					$tipo_movimiento	  = "DB";
					$glosa_cuenta		  = "";
					$datosComprobanteDetalle = array(
						'id_entidad'	            => $id_entidad,
						'id_comprobante'            => $saveComprobante,
						'id_cuenta'                 => $idCuentaCierre,
						'tipo_movimiento'           => $tipo_movimiento,
						'tipo_cambio'               => $tipo_cambio,
						'importe_moneda_nacional'   => $sumaTotalGlobalIngreso,
						'importe_moneda_extranjera' => $sumaTotalGlobalIngresoUSD,
						'glosa_cuenta'              => $glosa_cuenta,
						'id_usuario_registro'       => $id_usuario,
						'estado_resultado'          => $estado_resultado					
					);
					$detalle_comprobante = $this->Comprobantes_model->guardarDetalleComprobante($datosComprobanteDetalle);			
					foreach($estadoResultadoEgreso as $cuenta)
					{
						$id_cuenta = $cuenta->id;
						$saldo     = $cuenta->saldo;
						$saldoUSD  = $cuenta->saldousd;
						if($saldo > 0)
						{
							$tipo_movimiento='DB';
						}
						else
						{
							$tipo_movimiento='HB';
						}
						$tipo_cambio  = '';
						$glosa_cuenta = '';

						/*REGISTRO DE CUENTAS DETALLE COMPROBANTE*/

						$datosComprobanteDetalle = array(
							'id_entidad'	            => $id_entidad,
							'id_comprobante'            => $saveComprobante,
							'id_cuenta'                 => $id_cuenta,
							'tipo_movimiento'           => $tipo_movimiento,
							'tipo_cambio'               => $tipo_cambio,
							'importe_moneda_nacional'   => $saldo,
							'importe_moneda_extranjera' => $saldoUSD,
							'glosa_cuenta'              => $glosa_cuenta,
							'id_usuario_registro'       => $id_usuario,
							'estado_resultado'          => $estado_resultado									
						);
						$detalle_comprobante = $this->Comprobantes_model->guardarDetalleComprobante($datosComprobanteDetalle);
						if($detalle_comprobante)
						{
							// $updateCorrelativoEntidadGestion = array(
							// 	'correlativo' => $correlativo,
							// 	'fecha_modificacion' => $fechaActual
							// );	

							// $data = $this->Correlativos_model->updateCorrelativoEntidadGestion($idcorrelativoentidadgestion,$updateCorrelativoEntidadGestion);

							$resul = 1;
							$mensaje = "SE REGISTRO CORRECTAMENTE";
						}
						else {
							$resul = 0;
							$mensaje = "ERROR EN EL REGISTRO!!!";
						}

					}

				}else
				{
					/*REGISTRO DE COMPROBANTE DE CIERRE CONTABLE */
					if($total_resultado < 0)
					{
						$tipo_movimiento    = 'HB';
						$total_resultado    = $total_resultado*-1;
						$total_resultadousd = $total_resultadousd*-1;
					}	
					else {
						$tipo_movimiento = 'DB';
					}				
					// $tipo_movimiento	  = "DB";
					$glosa_cuenta		  = "";
					$datosComprobanteDetalleA = array(
						'id_entidad'	            => $id_entidad,
						'id_comprobante'            => $saveComprobante,
						'id_cuenta'                 => $idCuentaCierre,
						'tipo_movimiento'           => $tipo_movimiento,
						'tipo_cambio'               => $tipo_cambio,
						'importe_moneda_nacional'   => $total_resultado,
						'importe_moneda_extranjera' => $total_resultadousd,
						'glosa_cuenta'              => $glosa_cuenta,
						'id_usuario_registro'       => $id_usuario,
						'estado_resultado'          => $estado_resultado					
					);
					$detalle_comprobante = $this->Comprobantes_model->guardarDetalleComprobante($datosComprobanteDetalleA);		

					if($total_resultado < 0){
						$tipo_movimiento = 'DB';
						$total_resultado    = $total_resultado*-1;
						$total_resultadousd = $total_resultadousd*-1;
					}
					else {
						$tipo_movimiento = 'HB';
					}
					
					// $tipo_movimiento	  = "DB";
					$glosa_cuenta		  = "";
					$datosComprobanteDetalleB = array(
						'id_entidad'	            => $id_entidad,
						'id_comprobante'            => $saveComprobante,
						'id_cuenta'                 => $idCuentaSaldoCierre,
						'tipo_movimiento'           => $tipo_movimiento,
						'tipo_cambio'               => $tipo_cambio,
						'importe_moneda_nacional'   => $total_resultado,
						'importe_moneda_extranjera' => $total_resultadousd,
						'glosa_cuenta'              => $glosa_cuenta,
						'id_usuario_registro'       => $id_usuario,
						'estado_resultado'          => $estado_resultado					
					);
					$detalle_comprobante = $this->Comprobantes_model->guardarDetalleComprobante($datosComprobanteDetalleB);		

				}	
				
				
			}
			else
			{
				$resul = 0;
				$mensaje = "ERROR EN EL REGISTRO!!!";
			}


		}

		/*CONSOLIDAR CUENTAS DEL CIERRE DE RESULTADOS*/
		$cuentasIngreso    = $this->EstadoDeResultado_model->getCuentasConMovimientoByIdMayor($id_entidad,$fecha_inicio,$fecha_fin,$id_cuenta_ingreso,$codigo_cuenta_ingreso);
		$cuentasEgreso     = $this->EstadoDeResultado_model->getEstadoDeResultadosIngreso($id_entidad,$fecha_inicio,$fecha_fin,$id_cuenta_egreso,$codigo_cuenta_egreso);
		$cuentasConsolidadas =  array_merge($cuentasIngreso,$cuentasEgreso);
		foreach($cuentasConsolidadas as $cuenta)
		{
			$id_cuenta        = $cuenta->id_detalle_cuenta;
			$estado_resultado = 'CNS';//CONSOLIDADO
			$fecha_actual     = fecha_actual();
			$datosDetalleComprobante = array(
				'estado_resultado'        => $estado_resultado,
				'fecha_modificacion'      => $fecha_actual,
				'id_usuario_update'       => $id_usuario					
			);
			$detalle_comprobante = $this->Comprobantes_model->updateDetalleComprobante($id_cuenta,$datosDetalleComprobante);		
		}
		/*GUARDAR CIERRE */
		$tipo_cierre = "CIR";
		$descripcion = "";
		$comprobantes = preg_replace('/^-/', '', $comprobantes, 1);
		$datosCierre = array(
			'id_entidad'	            => $id_entidad,
			'gestion'		            => $gestion,
			'mes'	                    => $mes,
			'tipo_cierre'               => $tipo_cierre,
			'fecha_cierre'              => $fecha_actual,
			'id_comprobante'            => $id_comprobante,
			'descripcion'               => $descripcion,
			'saldo_resultado'           => $total_resultado,
			'id_usuario_registro'       => $id_usuario,
			'comprobante'               => $comprobantes					
		);
		$cierre = $this->Comprobantes_model->guardarCierre($datosCierre);				
		if($cierre)
		{
			$resul = 1;
			$mensaje = "SE REGISTRO EL CIERRE DE RESULTADOS CORRECTAMENTE.";
		}
		else
		{
			$resul = 0;
			$mensaje = "ERROR EN EL PROCESO!!!";
		}



		/******TRANSACT*****/
		if ($this->db->trans_status() === FALSE && $resul == 1) { 
			$this->db->trans_rollback(); // Deshacer los cambios si hay un error
			// $resultado = 0;
		} else {
			$this->db->trans_commit(); // Confirmar los cambios si todo está bien
			// $resultado = 1;
		}
		echo '[{
				"resultado":"'.$resul.'",
					"mensaje":"'.$mensaje.'"}]';

		// $resultado ='[{
		// 				"resultado":"'.$resul.'",
		// 				"mensaje":"'.$mensaje.'"
		// 			 }]';

		// echo $resultado;


	}

	// public function guardarComprobante()
	// {
	// 	$id_usuario          = $this->session->userdata('id_usuario');
	// 	$id_funcionario      = $this->session->userdata('id_funcionario');
	// 	$id_dependencia      = $this->session->userdata('id_dependencia_principal');
	// 	$fechaActual         = getFechaHoraActual();
	// 	parse_str($this->input->post('datos'), $data);
	// 	$detalleComprobante  = $this->input->post('detalleComprobante');
	
	// 	$validacomprobante   = json_decode($this->validarDatos($data));	
	// 	$resultado   = $validacomprobante[0]->resultado;
	// 	$mensaje     = $validacomprobante[0]->mensaje;
    //     $idComprobante=0;
	// 	$this->db->trans_start();
    //     if($resultado == 1)
	// 	{
			
    //         $accion      		  = $data['txtAccionComprobante'];
	// 		// $idComprobante   	  = $data['id_comprobante'];
	// 		$id_entidad       	  = $data['id_entidad'];
	// 		$fecha_comprobante    = $data['txtFecha'];
	// 		$tipo_cambio	      = $data['txtTipoCambio'];
	// 		$referencia_general   = $data['txtReferencia'];
	// 		$glosa_general	      = $data['txtGlosaGeneral'];
	// 		$correlativo		  = 0;
	// 		// $periodo        	  = date("m", strtotime($fecha_comprobante));
	// 		$periodo        	  = (int)date('m', strtotime($fecha_comprobante));
	// 		$gestion        	  = date("Y", strtotime($fecha_comprobante));



	// 		$totalImporteDebe	  = $data['total_debe'];
	// 		$totalImporteHaber    = $data['total_haber'];
	// 		$totalImporteDebeUs	  = $data['total_debe_us'];
	// 		$totalImporteHaberUs  = $data['total_haber_us'];

	// 		if(($totalImporteDebe === $totalImporteHaber) && ($totalImporteDebeUs === $totalImporteHaberUs))
	// 		{
	// 			if($accion === 'nuevo')
	// 			{
	// 			// $idComprobante   	  = $data['id_comprobante'];
	// 				$tipo_comprobante     = $data['txtTipo'];

	// 				$tipoCorrelativo  = $tipo_comprobante ;
	// 				$datosCorrelativo = json_decode(obtenerCorrelativoComprobanteGestionEntidad($tipoCorrelativo,$id_entidad,$id_dependencia, $gestion));
	// 				$idcorrelativoentidadgestion    = $datosCorrelativo[0]->idcorrelativoentidadgestion;
	// 				$correlativo      		        = $datosCorrelativo[0]->correlativo;

					
	// 				$datosComprobante = array(
	// 					'id_entidad'              => $id_entidad,
	// 					'tipo_comprobante'        => $tipo_comprobante,
	// 					'correlativo'             => $correlativo,
	// 					'periodo'                 => $periodo,
	// 					'gestion'                 => $gestion,
	// 					'referencia_comprobante'  => $referencia_general,
	// 					'glosa_comprobante' 	  => $glosa_general,
	// 					'fecha_comprobante'       => $fecha_comprobante,
	// 					'tipo_cambio'             => $tipo_cambio,
	// 					'id_usuario_registro'     => $id_usuario
	// 				);

	// 				$saveComprobante = $this->Comprobantes_model->guardarComprobante($datosComprobante);
	// 				if($saveComprobante)
	// 				{
	// 					/*REGISTRO DE CUENTAS DEL COMPROBANTE*/
	// 					$idComprobante = $saveComprobante;
	// 					$filas = explode("|", $detalleComprobante);
	// 					if(!empty($filas))
	// 					{
	// 						foreach($filas as $fila)
	// 						{
	// 							if(!empty($fila) && $fila != "undefined" && $fila != "null")
	// 							{
	// 								$row = explode("*", $fila);
	// 								if(!isset($row[0]) || empty($row[0]))
	// 								{
	// 									list($inicio,$id_cuenta, $cuenta,$tipo_movimiento,$tipo_movimiento_literal, $importe, $tipo_cambio,$glosa_cuenta,$id_cuenta_auxiliar,$cuenta_auxiliar) = $row;
	// 									// $importe   = number_format($importe,2,'.',',');
	// 									// $importeUs = number_format(($importe/$tipo_cambio),2,'.',',');
	// 									$importeUsBase = round($importe/$tipo_cambio,2);
	// 									$importeBase = floatval(str_replace(',', '', $importe));
	// 									//$importeUsBase = floatval(str_replace(',', '', $importeOriginal));
	// 									if($id_cuenta_auxiliar === '-')
	// 									{
	// 										$datosComprobanteDetalle = array(
	// 										'id_entidad'	            => $id_entidad,
	// 										'id_comprobante'            => $saveComprobante,
	// 										'id_cuenta'                 => $id_cuenta,
	// 										'tipo_movimiento'           => $tipo_movimiento,
	// 										'tipo_cambio'               => $tipo_cambio,
	// 										'importe_moneda_nacional'   => $importeBase,
	// 										'importe_moneda_extranjera' => $importeUsBase,
	// 										'glosa_cuenta'              => $glosa_cuenta,
	// 										'id_usuario_registro'       => $id_usuario									
	// 										);
	// 									}
	// 									else
	// 									{
	// 										$datosComprobanteDetalle = array(
	// 										'id_entidad'	            => $id_entidad,
	// 										'id_comprobante'            => $saveComprobante,
	// 										'id_cuenta'                 => $id_cuenta,
	// 										'tipo_movimiento'           => $tipo_movimiento,
	// 										'tipo_cambio'               => $tipo_cambio,
	// 										'importe_moneda_nacional'   => $importe,
	// 										'importe_moneda_extranjera' => $importeUs,
	// 										'glosa_cuenta'              => $glosa_cuenta,
	// 										'id_usuario_registro'       => $id_usuario,					
	// 										'id_cuenta_auxiliar'        => $id_cuenta_auxiliar										
	// 										);
	// 									}
										
	// 									// $datosComprobanteDetalle = array(
	// 									// 	'id_entidad'	            => $id_entidad,
	// 									// 	'id_comprobante'            => $saveComprobante,
	// 									// 	'id_cuenta'                 => $id_cuenta,
	// 									// 	'tipo_movimiento'           => $tipo_movimiento,
	// 									// 	'tipo_cambio'               => $tipo_cambio,
	// 									// 	'importe_moneda_nacional'   => $importe,
	// 									// 	'importe_moneda_extranjera' => $importeUs,
	// 									// 	'glosa_cuenta'              => $glosa_cuenta,
	// 									// 	'id_usuario_registro'       => $id_usuario,					
	// 									// 	'id_cuenta_auxiliar'        => $id_cuenta_auxiliar										
	// 									// );
	// 									$detalle_comprobante = $this->Comprobantes_model->guardarDetalleComprobante($datosComprobanteDetalle);
	// 									if($detalle_comprobante)
	// 									{
	// 										$updateCorrelativoEntidadGestion = array(
	// 											'correlativo' => $correlativo,
	// 											'fecha_modificacion' => $fechaActual
	// 										);	

	// 										$data = $this->Correlativos_model->updateCorrelativoEntidadGestion($idcorrelativoentidadgestion,$updateCorrelativoEntidadGestion);

	// 										$resul = 1;
	// 										$mensaje = "SE REGISTRO CORRECTAMENTE";
	// 									}
	// 									else
	// 									{
	// 										$resul = 0;
	// 										$mensaje = "ERROR EN EL REGISTRO DETALLE COMPROBANTE!!!";
	// 									}
	// 								}
	// 								else
	// 								{
	// 									// echo("FALSOOOOO");
	// 									$resul = 0;
	// 									$mensaje = "ERROR EN EL REGISTRO DETALLE COMPROBANTE....!!!";
	// 								}

	// 							}
															
	// 						}
	// 					}
	// 				}
	// 				else
	// 				{
	// 					$resul = 0;
	// 					$mensaje = "ERROR EN EL REGISTRO!!!";
	// 				}
	// 			}
	// 			else
	// 			{
	// 				$idComprobante   	  = $data['id_comprobanteP'];
	// 				$updateComprobante = array(
	// 					'periodo'                 => $periodo,
	// 					'gestion'                 => $gestion,
	// 					'glosa_comprobante' 	  => $glosa_general,
	// 					'referencia_comprobante'  => $referencia_general,
	// 					'fecha_comprobante'       => $fecha_comprobante,
	// 					'tipo_cambio'             => $tipo_cambio,
	// 					'fecha_modificacion'      => $fechaActual,
	// 					'id_funcionario_update'   => $id_funcionario
	// 				);
	// 				$saveComprobante = $this->Comprobantes_model->updateComprobante($idComprobante,$updateComprobante);
	// 				if($saveComprobante)
	// 				{
	// 					$resul = 1;
	// 					$mensaje = "SE ACTUALIZÓ CORRECTAMENTE EL COMPROBANTE";
	// 				}
	// 				else
	// 				{
	// 					$resul = 0;
	// 					$mensaje = "ERROR EN EL REGISTRO!!!";
	// 				}
	// 			}
	// 		}
	// 		else
	// 		{
	// 			$resultado = 2;
	// 			$mensaje = "Comprobante Contable Descuadrado:El comprobante presenta una diferencia contable.Verifique que el total del debe y del haber sean iguales para cumplir con la partida doble.";

	// 		}		
						
	// 	}
	// 	/******TRANSACT*****/
	// 	if ($this->db->trans_status() === FALSE && $resul == 1) { 
	// 		$this->db->trans_rollback(); // Deshacer los cambios si hay un error
	// 		// $resultado = 0;
	// 	} else {
	// 		$this->db->trans_commit(); // Confirmar los cambios si todo está bien
	// 		// $resultado = 1;
	// 	}
	// 	echo '[{"idComprobante":"'.$idComprobante.'",
	// 			    "resultado":"'.$resultado.'",
	// 	              "mensaje":"'.$mensaje.'"}]';

	// }
	
}
