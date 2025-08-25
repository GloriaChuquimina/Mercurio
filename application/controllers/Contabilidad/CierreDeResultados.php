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
        $this->load->model('CierresContables_model');
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
	public function cargarCierreDeResultados()
	{
		$id_usuario  = $this->session->userdata('id_usuario');
		$tipo_cierre = "CIR";
		$id_entidad  = $this->input->post('id_entidad');
		$filas       = $this->CierresContables_model->getCierres($tipo_cierre,$id_entidad);
		
		$draw    = intval($this->input->get("draw"));
		$start   = intval($this->input->get("start"));
		$length  = intval($this->input->get("length"));	
		$data    = array();
		$num     = 1;

		foreach ($filas as $fila)
		{   
			// $boton   = "
            //             <span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Editar'>
            //                 <button type='button' class='btn btn-block btn-warning btn-sm' onclick=\"editarEntidad(". $fila->id . ",'".$fila->sigla."','".$fila->nombre."')\"><i class='fas fa-edit'></i></button>     
            //             </span>	
            //             <span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Eliminar'>
            //                 <button type='button' class='btn btn-block btn-danger btn-sm' onclick='bajaEntidad(". $fila->id . ")'><i class='fas fa-trash-alt'></i></button>     
            //             </span>	
            //             ";	
			$entidad      = descripcion_nombre_entidad($fila->id_entidad);	
			$gestion      = $fila->gestion;
			$fecha_cierre = formato_fecha($fila->fecha_cierre);
			$descripcion  = $fila->descripcion;
			$usuario      = datos_persona_nombre2($fila->id_usuario_registro);
			$estado       = getValor2Configuraciones("ESTADO REGISTRO", $fila->estado);


			$data[] = array(
				// $boton,
				$num++,
				$entidad,
				$fecha_cierre,
				$descripcion,
				$usuario,
				$estado
			);
		}
		$output = array(
			"draw" => $draw,
			"recordsTotal" => count($filas),
			"recordsFiltered" => count($filas),
			"data" => $data
		);
		echo json_encode($output);
		exit();
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
		$id_entidad			   	   = $this->input->post('id_entidad');
		// echo("ID_ ENTIDAD==>".$id_entidad."<br>");
		// die();
		$codigo_cuenta_ingreso     = 4;
		$codigo_cuenta_egreso      = 5;
		$id_cuenta_ingreso   	   = getIdCuenta($codigo_cuenta_ingreso);
		$id_cuenta_egreso   	   = getIdCuenta($codigo_cuenta_egreso);
		$nivel		  			   = 0;
		$moneda		  			   = 'BOB';
		$saldoCero    			   = true;
		$fecha_fin    			   = $this->input->post('fecha_cierre');
		$fecha_inicio 			   = primerDiaDelAnio($fecha_fin);	

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
		$id_entidad			   	   = $this->input->post('id_entidad');
		$codigo_cuenta_ingreso     = 4;
		$codigo_cuenta_egreso      = 5;
		$id_cuenta_ingreso   	   = getIdCuenta($codigo_cuenta_ingreso);
		$id_cuenta_egreso   	   = getIdCuenta($codigo_cuenta_egreso);
		$nivel		  			   = 0;
		$moneda		  			   = 'BOB';
		$saldoCero    			   = true;
		$fecha_fin    			   = $this->input->post('fecha_cierre');
		$fecha_inicio 			   = primerDiaDelAnio($fecha_fin);	


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
	
	private function ordenarCuentas($cuentas, $excluirCuentasEnCero, $nivel)
	{
		$cuentasArray = json_decode(json_encode($cuentas), true);
		return $this->ordenarJerarquicamenteCuentasOrdenEstadoDeResultados(
			$cuentasArray,
			0,
			0,
			$excluirCuentasEnCero,
			$nivel
		);
	}
	public function cerrarCuentaDeResultados()
	{
		// 1. Manejo de transacciones
		$this->db->trans_begin();

		try {


			// 2. Definición de constantes y datos de la sesión
			$id_usuario      = $this->session->userdata('id_usuario');
			$id_funcionario  = $this->session->userdata('id_funcionario');
			$id_dependencia  = $this->session->userdata('id_dependencia_principal');
			

			// 3. Preparación de datos de cierre
			$id_entidad      		= $this->input->post('id_entidad_registro');
			$fecha_actual        	= getFechaHoraActual();
			// $tipo_cambio         	= 6.96;
			$tipo_cierre         	= 'CIR';
			$estado_resultado    	= 'CNS';
			$tipo_comprobante    	= 'TR';
			$idCuentaCierre      	= 54;//PÉRDIDAS Y GANANCIAS(CUENTA CONTRA LA QUE SE EJECUTA EL ACIENTO DE CIERRE DE RESULTADOS)
			$idCuentaSaldoCierre 	= $this->input->post('id_cuenta');//CUENTA DEL CIERRE DEL RESULTADO A LA QUE SE APROPIA EL SALDO DE LA GANANCIA O PERDIDA
			$fecha_comprobante   	= $this->input->post('fechaCierreResultado');
			$tipo_cambio         	=  number_format(getTipoCambio(formato_fecha_slash_invertido2($fecha_comprobante)),2,'.',',');
			$gestion            	= date('Y', strtotime($fecha_comprobante));
			$periodo             	= (int)date('m', strtotime($fecha_comprobante));
			$referencia_ingreso  	= 'Cuentas de ingreso de la gestión';
			$glosa_ingreso       	= 'Para registrar el cierre de las cuentas de ingreso apropiadas hasta el cierre del presente periodo.';
			$referencia_gasto    	= 'Cuentas de gasto de la gestión';
			$glosa_gasto         	= 'Para registrar el cierre de cuentas de costo y gasto apropiadas hasta el cierre del presente periodo.';
			$referencia_cierre    	= 'Asiento del cierre de resultados';
			$glosa_cierre         	= 'Para registro de perdida o ganancia del periodo';
			$contador            	= 3; // NÚMERO DE COMPROBANTES A GENERAR

			// 4. Parámetros para la consulta de resultados
			$codigo_cuenta_ingreso     = 4;
			$codigo_cuenta_egreso      = 5;
			$codigo_cuenta_resultado   = 9;//OTRAS CUENTAS DE RESULTADOS
			$id_cuenta_ingreso      = getIdCuenta($codigo_cuenta_ingreso);
			$id_cuenta_egreso       = getIdCuenta($codigo_cuenta_egreso);
			$id_cuenta_resultado    = getIdCuenta($codigo_cuenta_resultado);
			$nivel        			= getNivelMaximo();
			$saldoCero    			= true;
			$fecha_fin    			= $this->input->post('fechaCierreResultado');
			$fecha_inicio 			= primerDiaDelAnio($fecha_fin);
			$moneda       			= 'BOB';

			// 5. Obtención de datos de ingresos y egresos
			$ingresosData = $this->EstadoDeResultado_model->getEstadoDeResultadosIngreso(
				$id_entidad,
				$fecha_inicio,
				$fecha_fin,
				$id_cuenta_ingreso,
				$codigo_cuenta_ingreso
			);
			$egresosData = $this->EstadoDeResultado_model->getEstadoDeResultadosEgreso(
				$id_entidad,
				$fecha_inicio,
				$fecha_fin,
				$id_cuenta_egreso,
				$codigo_cuenta_egreso
			);

			// 6. Ordenar y calcular totales
			list($cuentasIngresoOrdenadas, $sumaIngreso, $sumaIngresoUSD) = $this->ordenarCuentas(
				$ingresosData,
				$saldoCero,
				$nivel
			);

			
			list($cuentasEgresoOrdenadas, $sumaEgreso, $sumaEgresoUSD) = $this->ordenarCuentas(
				$egresosData,
				$saldoCero,
				$nivel
			);

			$resultado = $this->EstadoDeResultado_model->getMontoResultado(
				$id_entidad,
				$fecha_inicio,
				$fecha_fin,
				$id_cuenta_ingreso,
				$codigo_cuenta_ingreso,
				$id_cuenta_egreso,
				$codigo_cuenta_egreso
			);

			$total_resultado    = $resultado[0]->total_estado_resultado ?? 0;
			$total_resultadousd = $resultado[0]->total_estado_resultadousd ?? 0;

			// 7. Generación de los comprobantes
			$comprobantes = [];
			$id_comprobante_cierre = 0;

			for ($i = 1; $i <= $contador; $i++) {
				$datosCorrelativo = json_decode(obtenerCorrelativoComprobanteGestionEntidad(
					$tipo_comprobante,
					$id_entidad,
					$id_dependencia,
					$gestion
				));

				// $correlativo = $datosCorrelativo[0]->correlativo;
				// $idcorrelativoentidadgestion = $datosCorrelativo[0]->idcorrelativoentidadgestion;

				// $datosComprobante = [
				// 	'id_entidad'             => $id_entidad,
				// 	'tipo_comprobante'       => $tipo_comprobante,
				// 	'correlativo'            => $correlativo,
				// 	'periodo'                => $periodo,
				// 	'gestion'                => $gestion,
				// 	'referencia_comprobante' => $referencia_general,
				// 	'glosa_comprobante'      => $glosa_general,
				// 	'fecha_comprobante'      => $fecha_comprobante,
				// 	'tipo_cambio'            => $tipo_cambio,
				// 	'id_usuario_registro'    => $id_usuario,
				// 	'tipo_cierre'            => $tipo_cierre
				// ];

				// $saveComprobante = $this->Comprobantes_model->guardarComprobante($datosComprobante);			
				// // Actualizar correlativo
				// $this->Correlativos_model->updateCorrelativoEntidadGestion(
				// 	$idcorrelativoentidadgestion,
				// 	['correlativo' => $correlativo, 'fecha_modificacion' => $fecha_actual]
				// );

				// $comprobantes[] = $saveComprobante;
				// $comprobantes = [];
				$id_comprobante_cierre = 0;
				// 8. Registro de detalles del comprobante
				switch ($i) {
					case 1:
						//registro de comprobante de resultado de ingreso
						$saveComprobante = $this->registrarComprobante(
						    $id_entidad,
							$tipo_comprobante,
							$periodo,
							$gestion,
							$referencia_ingreso,
							$glosa_ingreso,
							$fecha_comprobante,
							$tipo_cambio,
							$id_usuario,
							$tipo_cierre,
							$id_dependencia,
							$fecha_actual
						);
						$comprobantes[] = $saveComprobante;
						$this->registrarDetalle(
							$saveComprobante,
							$ingresosData,
							$id_entidad,
							$id_usuario,
							$estado_resultado,
							'DB',
							'HB',
							$idCuentaCierre,
							$sumaIngreso,
							$sumaIngresoUSD,
							$tipo_cambio
						);
						break;
					case 2:
						//registro de comprobante de resultado de egreso
						$saveComprobante = $this->registrarComprobante(
						    $id_entidad,
							$tipo_comprobante,
							$periodo,
							$gestion,
							$referencia_gasto,
							$glosa_gasto,
							$fecha_comprobante,
							$tipo_cambio,
							$id_usuario,
							$tipo_cierre,
							$id_dependencia,
							$fecha_actual
						);
						$comprobantes[] = $saveComprobante;
						$this->registrarDetalle(
							$saveComprobante,
							$egresosData,
							$id_entidad,
							$id_usuario,
							$estado_resultado,
							'HB',
							'DB',
							$idCuentaCierre,
							$sumaEgreso,
							$sumaEgresoUSD,
							$tipo_cambio
						);
						break;
					case 3:
						//registro de comprobante de resultado de ganancia o perdida
						$saveComprobante = $this->registrarComprobante(
						    $id_entidad,
							$tipo_comprobante,
							$periodo,
							$gestion,
							$referencia_cierre,
							$glosa_cierre,
							$fecha_comprobante,
							$tipo_cambio,
							$id_usuario,
							$tipo_cierre,
							$id_dependencia,
							$fecha_actual
						);
						$comprobantes[] = $saveComprobante;
						$id_comprobante_cierre = $saveComprobante;
						$this->registrarAsientoCierre(
							$saveComprobante,
							$id_entidad,
							$id_usuario,
							$estado_resultado,
							$idCuentaCierre,
							$idCuentaSaldoCierre,
							$total_resultado,
							$total_resultadousd,
							$tipo_cambio
						);
						break;
				}
			}

			// 9. Consolidar cuentas del cierre

			$cuentasMayor = [

				[$id_cuenta_ingreso, $codigo_cuenta_ingreso],
				[$id_cuenta_egreso, $codigo_cuenta_egreso],
				[$id_cuenta_resultado, $codigo_cuenta_resultado],
			];

			// ===============================
			// 1) Procesar  COMPROBANTES
			// ===============================
			$cuentasConsolidadasComprobantes = [];
			foreach ($cuentasMayor as [$id, $codigo]) {
				$cuentasConsolidadasComprobantes = array_merge(
					$cuentasConsolidadasComprobantes,
					$this->CierresContables_model->getCuentasConMovimientoComprobanteByIdMayor(
						$id_entidad,
						$fecha_inicio,
						$fecha_fin,
						$id,
						$codigo
					)
				);
			}

			foreach ($cuentasConsolidadasComprobantes as $cuenta) {
				$this->Comprobantes_model->updateComprobante(
					$cuenta->id_comprobante,
					[
						// 'estado'                => $estado_resultado,
						'tipo_cierre'           => $tipo_cierre,
						'fecha_modificacion'    => $fecha_actual,
						'id_funcionario_update' => $id_usuario
					]
				);
			}


			// ===============================
			// 2) Procesar DETALLE CUENTA
			// ===============================


			$cuentasConsolidadas = $this->CierresContables_model->getCuentasConMovimientoByIdMayor(
				$id_entidad,
				$fecha_inicio,
				$fecha_fin,
				$id_cuenta_ingreso,
				$codigo_cuenta_ingreso
			);
			$cuentasConsolidadas = array_merge(
				$cuentasConsolidadas,
				$this->CierresContables_model->getCuentasConMovimientoByIdMayor(
					$id_entidad,
					$fecha_inicio,
					$fecha_fin,
					$id_cuenta_egreso,
					$codigo_cuenta_egreso
				)
			);

			foreach ($cuentasConsolidadas as $cuenta) {
				$this->Comprobantes_model->updateDetalleComprobante(
					$cuenta->id_detalle_cuenta,
					[
						
						'estado_resultado'      => $estado_resultado,
						'fecha_modificacion'    => $fecha_actual,
						'id_funcionario_update' => $id_usuario
					]
				);
			}

			// 10. Guardar el cierre contable
			$datosCierre = [
				'id_entidad'          => $id_entidad,
				'gestion'             => $gestion,
				'mes'                 => $periodo,
				'tipo_cierre'         => $tipo_cierre,
				'fecha_cierre'        => $fecha_actual,
				'id_comprobante'      => $id_comprobante_cierre,
				'descripcion'         => 'Cierre de cuentas de resultado',
				'saldo_resultado'     => $total_resultado,
				'id_usuario_registro' => $id_usuario,
				'comprobante'         => implode('-', $comprobantes)
			];
			$this->CierresContables_model->guardarCierre($datosCierre);

			$this->db->trans_commit();
			$response = ['resultado' => 1, 
			             'mensaje' => 'SE REGISTRÓ EL CIERRE DE RESULTADOS CORRECTAMENTE.',
			             'id_entidad' => $id_entidad
						];
		} catch (\Exception $e) {
			$this->db->trans_rollback();
			$response = ['resultado' => 0, 
			               'mensaje' => 'ERROR EN EL PROCESO: ' . $e->getMessage(),
						   'id_entidad' => $id_entidad
						];
		}

		echo json_encode([$response]);
	}

	/**
	 * Registra un comprobante para de cierre de balance, cierre de cuentas de orden y apertura de gestion
	 */
	private function registrarComprobante(
	$id_entidad, 
	$tipo_comprobante, 
	$periodo, 
	$gestion, 
	$referencia_general, 
	$glosa_general, 
	$fecha_comprobante, 
	$tipo_cambio, 
	$id_usuario, 
	$tipo_cierre,
	$id_dependencia,
	$fecha_actual){


		$datosCorrelativo = json_decode(obtenerCorrelativoComprobanteGestionEntidad(
			$tipo_comprobante,
			$id_entidad,
			$id_dependencia,
			$gestion
		));

		$correlativo = $datosCorrelativo[0]->correlativo;
		$idcorrelativoentidadgestion = $datosCorrelativo[0]->idcorrelativoentidadgestion;

		$datosComprobante = [
			'id_entidad'             => $id_entidad,
			'tipo_comprobante'       => $tipo_comprobante,
			'correlativo'            => $correlativo,
			'periodo'                => $periodo,
			'gestion'                => $gestion,
			'referencia_comprobante' => $referencia_general,
			'glosa_comprobante'      => $glosa_general,
			'fecha_comprobante'      => $fecha_comprobante,
			'tipo_cambio'            => $tipo_cambio,
			'id_usuario_registro'    => $id_usuario,
			'tipo_cierre'            => $tipo_cierre
		];	

		$saveComprobante = $this->Comprobantes_model->guardarComprobante($datosComprobante);
		$comprobantes[] = $saveComprobante;

		// Actualizar correlativo
		$this->Correlativos_model->updateCorrelativoEntidadGestion(
			$idcorrelativoentidadgestion,
			['correlativo' => $correlativo, 'fecha_modificacion' => $fecha_actual]
		);
		return $saveComprobante;

	}
	/**
	 * Registra los detalles de un comprobante para cuentas de ingresos o egresos.
	 */
	private function registrarDetalle($comprobanteId, $cuentas, $entidadId, $usuarioId, $estado, $tipoMovimientoPositivo, $tipoMovimientoNegativo, $idCuentaCierre, $sumaTotal, $sumaTotalUSD,$tipo_cambio)
	{
		foreach ($cuentas as $cuenta) {
			$saldo     = $cuenta->saldo;
			$saldoUSD  = $cuenta->saldousd;
			//SOLO SE REGISTRAN CUENTAS CON SALDO DIFERENTE DE CERO
			if ($saldo != 0) {
				$tipo_movimiento = ($saldo > 0) ? $tipoMovimientoPositivo : $tipoMovimientoNegativo;
				$saldo           = ($saldo > 0) ? $saldo : $saldo * -1;
				// $saldoUSD        = ($saldoUSD > 0) ? $saldoUSD : $saldoUSD * -1;
				$saldoUSD       = round($saldo/$tipo_cambio,2);
				$datosDetalle = [
					'id_entidad'                => $entidadId,
					'id_comprobante'            => $comprobanteId,
					'id_cuenta'                 => $cuenta->id,
					'tipo_movimiento'           => $tipo_movimiento,
					'tipo_cambio'               => $tipo_cambio,
					'importe_moneda_nacional' 	=> $saldo,
					'importe_moneda_extranjera' => $saldoUSD,
					'glosa_cuenta'              => '',
					'id_usuario_registro'       => $usuarioId,
					'estado_resultado'          => $estado
				];
				$this->Comprobantes_model->guardarDetalleComprobante($datosDetalle);
			}
		}

		// Registro del asiento contable de cierre
		$sumaTotalUSD       = round($sumaTotal/$tipo_cambio,2);
		$datosDetalleCierre = [
			'id_entidad'                => $entidadId,
			'id_comprobante'            => $comprobanteId,
			'id_cuenta'                 => $idCuentaCierre,
			'tipo_movimiento'           => $tipoMovimientoNegativo,
			// 'tipo_cambio'             => 6.96,
			'tipo_cambio'               => $tipo_cambio,
			'importe_moneda_nacional' 	=> $sumaTotal,
			'importe_moneda_extranjera' => $sumaTotalUSD,
			'glosa_cuenta'              => '',
			'id_usuario_registro'       => $usuarioId,
			'estado_resultado'          => $estado
		];
		$this->Comprobantes_model->guardarDetalleComprobante($datosDetalleCierre);
	}
	/**
	 * Registra el asiento de cierre final.
	 */
	private function registrarAsientoCierre($comprobanteId, $entidadId, $usuarioId, $estado, $idCuentaCierre, $idCuentaSaldoCierre, $total_resultado, $total_resultadousd,$tipo_cambio)
	{
		$importe    = abs($total_resultado);
		// $importeUSD = abs($total_resultadousd);

		// Movimiento para la cuenta de cierre
		$tipo_movimiento_cierre = ($total_resultado < 0) ? 'HB' : 'DB';

		$importeUSD       = round($importe/$tipo_cambio,2);
		
		$this->Comprobantes_model->guardarDetalleComprobante([
			'id_entidad'                => $entidadId,
			'id_comprobante'            => $comprobanteId,
			'id_cuenta'                 => $idCuentaCierre,
			'tipo_movimiento'           => $tipo_movimiento_cierre,
			// 'tipo_cambio'             => 6.96,
			'tipo_cambio'               => $tipo_cambio,
			'importe_moneda_nacional'   => $importe,
			'importe_moneda_extranjera' => $importeUSD,
			'glosa_cuenta'              => '',
			'id_usuario_registro'       => $usuarioId,
			'estado_resultado'          => $estado
		]);

		// Movimiento para la cuenta de saldo
		$tipo_movimiento_saldo = ($total_resultado < 0) ? 'DB' : 'HB';
		$this->Comprobantes_model->guardarDetalleComprobante([
			'id_entidad'                => $entidadId,
			'id_comprobante'            => $comprobanteId,
			'id_cuenta'                 => $idCuentaSaldoCierre,
			'tipo_movimiento'           => $tipo_movimiento_saldo,
			'tipo_cambio'               => $tipo_cambio,
			'importe_moneda_nacional'   => $importe,
			'importe_moneda_extranjera' => $importeUSD,
			'glosa_cuenta'              => '',
			'id_usuario_registro'       => $usuarioId,
			'estado_resultado'          => $estado
		]);
	}

	
}
