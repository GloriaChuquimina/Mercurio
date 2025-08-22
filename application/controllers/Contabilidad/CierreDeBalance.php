<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . "libraries/fpdf/easyTable.php";
require_once APPPATH . "libraries/fpdf/fpdfde.php";
require_once APPPATH . "libraries/fpdf/exfpdfCartaContable.php";

class CierreDeBalance extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->_is_logued_in();
		$this->load->library('form_validation');
        $this->load->model('Comprobantes_model');
        $this->load->model('PlanDeCuentas_model');
        $this->load->model('BalanceGeneral_model');
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

		$titulo = "Cierre de Cuentas de Balance";		
		$dato['titulo'] = $titulo;

		$this->load->view('inicio/cabecera',$dato);
		$this->load->view('inicio/menu',$dato);
		$this->load->view('contabilidad/cierredebalance',$dato);
		$this->load->view('inicio/pie');
	}
	public function principalComprobante($entidad=-1,$tipo_comprobante=-1)
	{

		$dato['nombre_usuario']  = $this->session->userdata('nombre_usuario');		
		$dato['nombre_sistema']  = "MERCURIO";
		$dato['tipo_sistema']  = "Sistema Contable";
		
		
		$id_usuario = $this->session->userdata('id_usuario');
		$dato['rolescero'] = $this->session->userdata('rolescero');
		$dato['roles']  = $this->session->userdata('roles');
		$dato['nombre_usuario']  = $this->session->userdata('nombre_completo');

		$titulo = "Gestión de Comprobantes";		
		$dato['titulo'] = $titulo;
		$dato['entidad'] = $entidad;
		$dato['tipo_comprobante'] =$tipo_comprobante;

		$this->load->view('inicio/cabecera',$dato);
		$this->load->view('inicio/menu',$dato);
		$this->load->view('contabilidad/cierredebalance',$dato);
		$this->load->view('inicio/pie');
	}
	public function registroComprobante($entidad,$accion='nuevo',$id_comprobante=0)
	{
		$dato['nombre_usuario']  = $this->session->userdata('nombre_usuario');		
		$dato['nombre_sistema']  = "MERCURIO";
		$dato['tipo_sistema']  = "Sistema Contable";
		$id_usuario = $this->session->userdata('id_usuario');
		$dato['rolescero'] = $this->session->userdata('rolescero');
		$dato['roles']  = $this->session->userdata('roles');
		$dato['nombre_usuario']  = $this->session->userdata('nombre_completo');
		$dato['nombre_entidad']  = descripcion_nombre_entidad($entidad);
		$dato['entidad']  = $entidad;
		$dato['accion']  = $accion;
		$dato['id_comprobante']  = $id_comprobante;

		$titulo = "Comprobante Contable";		
		$dato['titulo'] = $titulo;

		$this->load->view('inicio/cabecera',$dato);
		$this->load->view('inicio/menu',$dato);		
		$this->load->view('contabilidad/registro_comprobante',$dato); //cuerpo
		$this->load->view('inicio/pie');
	}
	/*FUNCIONES PARA EL CIERRE DE BALANCE */
	private function ordenarJerarquicamente(
    array $cuentas, 
    int $padreId = 0, 
    int $indentacion = 0, 
    bool $excluirDesdeNivelTres = false,
    int $nivelMaximo = null // nuevo parámetro
	) {
		$ordenadas     = [];
		$totalImporte  = 0;
		$totalImporteUSD   = 0;

		foreach ($cuentas as &$cuenta) {
			if ($cuenta['padre'] == $padreId) {

				// --- ¿Tiene hijos? ---
				$tieneHijos = false;
				foreach ($cuentas as $posibleHijo) {
					if (
						$posibleHijo['padre'] == $cuenta['id'] ||
						$posibleHijo['padre'] == $cuenta['ruta'] // para cuentas mayores
					) {
						$tieneHijos = true;
						break;
					}
				}

				// --- Procesar hijos recursivamente ---
				[$hijosOrdenados, $sumaHijos,$sumaHijosUSD] =
					$this->ordenarJerarquicamente(
						$cuentas, 
						$cuenta['id'], 
						$indentacion + 1,
						$excluirDesdeNivelTres,
						$nivelMaximo
					);

				// --- Sumar importe propio + importe hijos ---
				// $importePropio              = isset($cuenta['saldo_cuenta'])
				// 								? (float) $cuenta['saldo_cuenta']
				// 								: 0;

				$importePropio    = isset($cuenta['saldo_cuenta']) ? (float)$cuenta['saldo_cuenta'] : 0;
				$importePropioUSD = isset($cuenta['saldo_cuenta_usd']) ? (float)$cuenta['saldo_cuenta_usd'] : 0;

				// Si el nivel máximo está definido y la cuenta está en ese nivel máximo, sumamos saldo hijos
				if ($nivelMaximo !== null && isset($cuenta['nivel']) && $cuenta['nivel'] == $nivelMaximo) {
					$saldoConHijos = $importePropio + $sumaHijos;
					$saldoConHijosUSD = $importePropioUSD + $sumaHijosUSD;
				} else {
					// No sumamos hijos, solo saldo propio
					$saldoConHijos = $importePropio;
					$saldoConHijosUSD = $importePropioUSD;
				}

				$cuenta['saldo_cuenta']      = $saldoConHijos;
				$cuenta['importe_total']     = $importePropio + $sumaHijos;
				$cuenta['saldo_cuenta_USD']  = $saldoConHijosUSD;
				$cuenta['importe_total_USD'] = $importePropioUSD + $sumaHijosUSD;
			
				// $cuenta['importe_total']    = $importePropio + $sumaHijos;

				// $sumaTotal 					= $importePropio + $sumaHijos;
				// $cuenta['saldo_cuenta'] 	= $sumaTotal;
				// --- Campos extra ---
				$cuenta['indentacion']      = $indentacion;
				$cuenta['es_padre']         = $tieneHijos;

				// --- Verificar si debe mostrarse ---
				$agregarCuenta = true;

				// Si hay límite de nivel y esta cuenta está por debajo, no se muestra
				if ($nivelMaximo !== null && isset($cuenta['nivel']) && $cuenta['nivel'] > $nivelMaximo) {
					$agregarCuenta = false;
				}

				// Tu lógica anterior de exclusión desde nivel 3 para que si o si muestre las cuentas hasta el nivel dos aunque este en cero 
				if (
					$excluirDesdeNivelTres &&
					isset($cuenta['nivel']) &&
					$cuenta['nivel'] >= 3 &&
					$importePropio == 0
				) {
					$agregarCuenta = false;
				}

				// Agregar al resultado si aplica
				if ($agregarCuenta) {
					$ordenadas[] = $cuenta;
				}

				// Agregar hijos solo si no hay límite de nivel o si el hijo está permitido
				if ($nivelMaximo === null || (isset($cuenta['nivel']) && $cuenta['nivel'] < $nivelMaximo)) {
					$ordenadas = array_merge($ordenadas, $hijosOrdenados);
				}

				$totalImporte += $cuenta['importe_total'];
				$totalImporteUSD += $cuenta['importe_total_USD'];
			}
		}
		unset($cuenta);

		// Calcular indentación invertida
		if ($ordenadas) {
			$profMax = max(array_column($ordenadas, 'indentacion'));
			foreach ($ordenadas as &$c) {
				$c['indentacion_invertida'] = $profMax - $c['indentacion'];
			}
			unset($c);
		}

		return [$ordenadas, $totalImporte,$totalImporteUSD];
	}
	private function ordenarJerarquicamenteCuentasOrden(array $cuentas, int $padreId = 0, int $indentacion = 0,bool $excluirDesdeNivelDos=false,int $nivelMaximo = null)
	{
		$ordenadas     = [];
		$totalImporte  = 0;
		$totalImporteUSD   = 0;

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
					$this->ordenarJerarquicamenteCuentasOrden(
						$cuentas, 
						$cuenta['id'], 
						$indentacion + 1,
						$excluirDesdeNivelDos,
						$nivelMaximo
					);

				// --- Sumar importe propio + importe hijos -------------------------
				$importePropio              = isset($cuenta['saldo_cuenta'])
												? (float) $cuenta['saldo_cuenta']
												: 0;
				$importePropioUSD              = isset($cuenta['saldo_cuenta_usd'])
												? (float) $cuenta['saldo_cuenta_usd']
												: 0;



				// Si el nivel máximo está definido y la cuenta está en ese nivel máximo, sumamos saldo hijos
				// if ($nivelMaximo !== null && isset($cuenta['nivel']) && $cuenta['nivel'] == $nivelMaximo) {
				// if ($nivelMaximo !== null && isset($cuenta['nivel']) && $cuenta['nivel']>= $nivelMaximo) {
				// 	$saldoConHijos    = $importePropio + $sumaHijos;
				// 	$saldoConHijosUSD = $importePropioUSD + $sumaHijosUSD; 
				// } else {
				// 	// No sumamos hijos, solo saldo propio
				// 	$saldoConHijos 	  = $importePropio;
				// 	$saldoConHijosUSD = $importePropioUSD;
				// }

				$saldoConHijos    = $importePropio + $sumaHijos;
				$saldoConHijosUSD = $importePropioUSD + $sumaHijosUSD; 

				$cuenta['saldo_cuenta']      = $saldoConHijos;
				$cuenta['importe_total']     = $importePropio+$saldoConHijos;
				$cuenta['saldo_cuenta_USD']  = $saldoConHijosUSD;
				$cuenta['importe_total_USD'] = $importePropioUSD + $sumaHijosUSD;
				// $cuenta['importe_total']    = $importePropio + $sumaHijos;			
				// --- Campos extra --------------------------------------------------
				$cuenta['indentacion']      = $indentacion;
				$cuenta['es_padre']         = $tieneHijos;

				// --- Añadir al resultado ------------------------------------------

				$agregarCuenta =true;
				// Si hay límite de nivel y esta cuenta está por debajo, no se muestra
				if ($nivelMaximo !== null && isset($cuenta['nivel']) && $cuenta['nivel'] > $nivelMaximo) {
					$agregarCuenta = false;
				}

				// if($excluirEnCero && $cuenta['importe_total'] == 0)
				//EXCLUYE CUENTAS EN 0 DESDE EL NIVEL DOS
				if(
					$excluirDesdeNivelDos &&
					isset($cuenta['nivel']) &&
					$cuenta['nivel'] >=2 &&
					$importePropio == 0) {
					$agregarCuenta = false;
				}
				
				if($agregarCuenta)
				{
					$ordenadas[] = $cuenta;
				}

				// $ordenadas   = array_merge($ordenadas, $hijosOrdenados);


				// Agregar hijos solo si no hay límite de nivel o si el hijo está permitido
				if ($nivelMaximo === null || (isset($cuenta['nivel']) && $cuenta['nivel'] < $nivelMaximo)) {
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

	private function ordenarCuentas($cuentas, $excluirCuentasEnCero, $nivel,$tipo)
	{
		$cuentasArray = json_decode(json_encode($cuentas), true);
		if($tipo=='balance'){
			return $this->ordenarJerarquicamente(
				$cuentasArray,
				0,
				0,
				$excluirCuentasEnCero,
				$nivel
			);
		}
		else
		{
			return $this->ordenarJerarquicamenteCuentasOrden(
				$cuentasArray,
				0,
				0,
				$excluirCuentasEnCero,
				$nivel
			);
		}
		

		
	}
	public function cerrarCuentasDeBalance()
	{
		// 1. Manejo de transacciones
		$this->db->trans_begin();

		try {
			// 2. Definición de constantes y datos de la sesión
			$id_usuario       = $this->session->userdata('id_usuario');
			$id_funcionario   = $this->session->userdata('id_funcionario');
			$id_dependencia   = $this->session->userdata('id_dependencia_principal');
			$id_entidad       = 1;
			$fecha_actual     = getFechaHoraActual();
			$tipo_cambio      = 6.96;
			$tipo_cierre      = 'CIB';
			$estado_balance   = 'CNS';
			$tipo_comprobante = 'TR';

			// 3. Preparación de datos de cierre
			// $idCuentaCierre      		  = 54;
			// $idCuentaSaldoCierre 	      = 33;
			$fecha_comprobante   		      = '2025-01-01';
			$gestion             		      = date('Y', strtotime($fecha_comprobante));
			$periodo                          = (int)date('m', strtotime($fecha_comprobante));
			$referencia_general_cierre        = 'Cierre de Cuentas de Balance';
			$glosa_general_cierre             = 'Cierre de Cuentas de Balance';
			$referencia_general_cuentasorden  = 'Cierre de Cuentas de Orden';
			$glosa_general__cuentasorden      = 'Para cerrar transitoriamente cuentas de orden apropiadas hasta el cierre del presente periodo.';
			$referencia_general_apertura      = 'Por reinicio de actividades';
			$glosa_general_apertura           = 'Por reinicio de actividades';
			$gestion_apertura				  = $gestion+1;
			$periodo_apertura				  = 1;
			$fecha_comprobante_apertura		  = '2026-01-02';
			$contador            		      = 3; // NÚMERO DE COMPROBANTES A GENERAR
			$idSeleccionado					  = 'radioAl';
			$valorCheckCero 				  = true;

			// 4. Parámetros para la consulta de resultados
			$codigo_cuenta_ingreso = 4;
			$id_cuenta_ingreso     = getIdCuenta($codigo_cuenta_ingreso);
			$codigo_cuenta_egreso  = 5;
			$id_cuenta_egreso      = getIdCuenta($codigo_cuenta_egreso);

			$nivel        = getNivelMaximo();
			$saldoCero    = false;
			$fecha_inicio = '2025-01-01';
			$fecha_fin    = '2025-08-18';
			$fecha_al     = '2025-08-18';
			$moneda       = 'BOB';

			//datos balance general
			// $id_activo=1;
			// $id_pasivo=2;
			// $id_patrimonio=3;
			$codigo_activo     = 1;
			$codigo_pasivo     = 2;
			$codigo_patrimonio = 3;
			$id_activo		   = getIdCuenta($codigo_activo);
			$id_pasivo		   = getIdCuenta($codigo_pasivo);
			$id_patrimonio	   = getIdCuenta($codigo_patrimonio);

			// $id_cuentas_deudoras   	   = 28;
			// $id_cuentas_acreedoras 	   = 29;
			$codigo_cuentas_deudoras   = 6;
			$codigo_cuentas_acreedoras = 7;
			$id_cuentas_deudoras   	   = getIdCuenta($codigo_cuentas_deudoras);
			$id_cuentas_acreedoras 	   = getIdCuenta($codigo_cuentas_acreedoras);


			if($valorCheckCero === true){
				$excluirCuentasEnCero= false;
			}
			else{
				$excluirCuentasEnCero= true;
			}

			if($nivel== 0)
			{
				$nivel=getNivelMaximo();
			}

			$whereFecha = "";
			if($idSeleccionado == 'radioAl'){
				$whereFecha = " AND fecha_comprobante <='$fecha_al' ";
			}
			elseif($idSeleccionado == 'radioEntre'){
				$whereFecha = " AND fecha_comprobante BETWEEN '$fecha_inicio' AND '$fecha_fin' ";
			}


			// 5. Obtención de datos de activos, pasivos, patrimonio,cuentas deudoras, cuentas acreedoras
			$tipo_cuenta_deudor="deudor";
			$tipo_cuenta_acreedor="acreedor";
			$cuentas_activo   	    = $this->BalanceGeneral_model->getBalanceGeneralPorMayor(
					$id_entidad,
					$fecha_inicio,
					$fecha_fin,
					$codigo_activo,
					$id_activo,
					$whereFecha,
					$tipo_cuenta_deudor	);


			// echo("<pre>");
			// print_r($cuentas_activo);
			// echo("</pre>");
			// die();
			$cuentas_pasivo   	    = $this->BalanceGeneral_model->getBalanceGeneralPorMayor(
					$id_entidad,
					$fecha_inicio,
					$fecha_fin,
					$codigo_pasivo,
					$id_pasivo,
					$whereFecha,
					$tipo_cuenta_acreedor);
			$cuentas_patrimonio     = $this->BalanceGeneral_model->getBalanceGeneralPorMayor(
					$id_entidad,
					$fecha_inicio,
					$fecha_fin,
					$codigo_patrimonio,
					$id_patrimonio,
					$whereFecha,
					$tipo_cuenta_acreedor);
			$cuentas_deudoras 	    = $this->BalanceGeneral_model->getBalanceGeneralPorMayor(
					$id_entidad,
					$fecha_inicio,
					$fecha_fin,
					$codigo_cuentas_deudoras,
					$id_cuentas_deudoras,
					$whereFecha,
					$tipo_cuenta_deudor);
			$cuentas_acreedoras     = $this->BalanceGeneral_model->getBalanceGeneralPorMayor(
					$id_entidad,
					$fecha_inicio,
					$fecha_fin,
					$codigo_cuentas_acreedoras,
					$id_cuentas_acreedoras,
					$whereFecha,
					$tipo_cuenta_acreedor);

			// 6. Ordenar y calcular totales
			$tipo1="balance";
			$tipo2="orden";
			list($cuentasActivoOrdenadas, $sumaActivo, $sumaActivoUSD) = $this->ordenarCuentas(
				$cuentas_activo,
				$saldoCero,
				$nivel,
				$tipo1
			);
	echo("<pre>");
			print_r($cuentasActivoOrdenadas);
			echo("</pre>");
			echo("SumaActivo=>".$sumaActivo."<br>");
			echo("SumaActivoUSD=>".$sumaActivoUSD."<br>");
			$totalActivo=count($cuentasActivoOrdenadas);
			list($cuentasPasivoOrdenadas, $sumaPasivo, $sumaPasivoUSD) = $this->ordenarCuentas(
				$cuentas_pasivo,
				$saldoCero,
				$nivel,
				$tipo1
			);
			echo("<pre>");
			print_r($cuentasPasivoOrdenadas);
			echo("</pre>");
			echo("Pasivo=>".$sumaPasivo."<br>");
			echo("PasivoUSD=>".$sumaPasivoUSD."<br>");
			$totalPasivo=count($cuentasPasivoOrdenadas);
			list($cuentasPatrimonioOrdenadas, $sumaPatrimonio, $sumaPatrimonioUSD) = $this->ordenarCuentas(
				$cuentas_patrimonio,
				$saldoCero,
				$nivel,
				$tipo1
			);
			echo("<pre>");
			print_r($cuentasPatrimonioOrdenadas);
			echo("</pre>");
			echo("Patrimonio=>".$sumaPatrimonio."<br>");
			echo("PatrimonioUSD=>".$sumaPatrimonioUSD."<br>");
			$totalPatrimonio=count($cuentasPatrimonioOrdenadas);
			list($cuentasDeudorasOrdenadas, $sumaDeudor, $sumaDeudorasUSD) = $this->ordenarCuentas(
				$cuentas_deudoras,
				$saldoCero,
				$nivel,
				$tipo2
			);
			echo("<pre>");
			print_r($cuentasDeudorasOrdenadas);
			echo("</pre>");
			echo("Acreedoras=>".$sumaDeudor."<br>");
			echo("AcreedorasUSD=>".$sumaDeudorasUSD."<br>");
			$totalDeudor=count($cuentasDeudorasOrdenadas);
			list($cuentasAcreedorasOrdenadas, $sumaAcreedor, $sumaAcreedorUSD) = $this->ordenarCuentas(
				$cuentas_acreedoras,
				$saldoCero,
				$nivel,
				$tipo2
			);
			echo("<pre>");
			print_r($cuentasAcreedorasOrdenadas);
			echo("</pre>");
			echo("Acreedoras=>".$sumaAcreedor."<br>");
			echo("AcreedorasUSD=>".$sumaAcreedorUSD."<br>");
			//die();
			$totalAcreedor=count($cuentasAcreedorasOrdenadas);

			$max_filas  = $totalActivo+$totalPasivo+$totalPatrimonio+$totalDeudor+$totalAcreedor;
			$cuentasUnidas = array_merge($cuentasActivoOrdenadas,$cuentasPasivoOrdenadas, $cuentasPatrimonioOrdenadas,$cuentasDeudorasOrdenadas,$cuentasAcreedorasOrdenadas);


			// $resultado = $this->EstadoDeResultado_model->getMontoResultado(
			// 	$id_entidad,
			// 	$fecha_inicio,
			// 	$fecha_fin,
			// 	$id_cuenta_ingreso,
			// 	$codigo_cuenta_ingreso,
			// 	$id_cuenta_egreso,
			// 	$codigo_cuenta_egreso
			// );

			// $total_resultado    = $resultado[0]->total_estado_resultado ?? 0;
			// $total_resultadousd = $resultado[0]->total_estado_resultadousd ?? 0;

			// 7. Generación de los comprobantes
			// 8. Registro de detalles del comprobante
			$comprobantes = [];
			$id_comprobante_cierre = 0;

			for ($i = 1; $i <= $contador; $i++) {
				
				switch ($i) {
					case 1:
						$saveComprobante = $this->registrarComprobante(
						    $id_entidad,
							$tipo_comprobante,
							$periodo,
							$gestion,
							$referencia_general_cierre,
							$glosa_general_cierre,
							$fecha_comprobante,
							$tipo_cambio,
							$id_usuario,
							$tipo_cierre,
							$id_dependencia,
							$fecha_actual
						);
						$id_comprobante_cierre = $saveComprobante;
						$comprobantes[] = $saveComprobante;
						$cuentasPasivoPatrimonio = array_merge($cuentas_pasivo, $cuentas_patrimonio);
						$this->registrarDetalleCierre(
							$saveComprobante,
							$cuentasPasivoPatrimonio,
							$cuentas_activo,
							$id_entidad,
							$id_usuario,
							$estado_balance,
							'DB',
							'HB'							
						);
						break;
					case 2:
						if($totalAcreedor>0 && $totalDeudor>0)
						{
							$saveComprobante = $this->registrarComprobante(
						    $id_entidad,
							$tipo_comprobante,
							$periodo,
							$gestion,
							$referencia_general_cuentasorden,
							$glosa_general__cuentasorden,
							$fecha_comprobante,
							$tipo_cambio,
							$id_usuario,
							$tipo_cierre,
							$id_dependencia,
							$fecha_actual
							);
							$comprobantes[] = $saveComprobante;
							$this->registrarDetalleCierre(
								$saveComprobante,
								$cuentas_acreedoras,
								$cuentas_deudoras,
								$id_entidad,
								$id_usuario,
								$estado_balance,
								'DB',
								'HB'
							);
						}
						
						break;
					case 3:
						$saveComprobante = $this->registrarComprobante(
						    $id_entidad,
							$tipo_comprobante,
							$periodo_apertura,
							$gestion_apertura,
							$referencia_general_apertura,
							$glosa_general_apertura,
							$fecha_comprobante_apertura,
							$tipo_cambio,//CONSULTAR
							$id_usuario,
							$tipo_cierre,//CONSULTAR
							$id_dependencia,
							$fecha_actual
						);
						$comprobantes[] = $saveComprobante;
						$cuentasPasivoPatrimonio = array_merge($cuentas_pasivo, $cuentas_patrimonio);
						$this->registrarDetalleCierre(
							$saveComprobante,
							$cuentasPasivoPatrimonio,
							$cuentas_activo,
							$id_entidad,
							$id_usuario,
							$estado_balance,
							'HB',
							'DB'
						);
						break;
				}
			}

			// 9. Consolidar cuentas del cierre
			$cuentasConsolidadas = $this->CierresContables_model->getCuentasConMovimientoByIdMayor(
				$id_entidad,
				$fecha_inicio,
				$fecha_fin,
				$id_activo,
				$codigo_activo
			);
			$cuentasConsolidadas = array_merge(
				$cuentasConsolidadas,
				$this->CierresContables_model->getCuentasConMovimientoByIdMayor(
					$id_entidad,
					$fecha_inicio,
					$fecha_fin,
					$id_pasivo,
					$codigo_pasivo
				),
				$this->CierresContables_model->getCuentasConMovimientoByIdMayor(
					$id_entidad,
					$fecha_inicio,
					$fecha_fin,
					$id_patrimonio,
					$codigo_patrimonio
				),
				$this->CierresContables_model->getCuentasConMovimientoByIdMayor(
					$id_entidad,
					$fecha_inicio,
					$fecha_fin,
					$id_cuentas_deudoras,
					$codigo_cuentas_deudoras
				),
				$this->CierresContables_model->getCuentasConMovimientoByIdMayor(
					$id_entidad,
					$fecha_inicio,
					$fecha_fin,
					$id_cuentas_acreedoras,
					$codigo_cuentas_acreedoras
				)
			);

			foreach ($cuentasConsolidadas as $cuenta) {
				$this->Comprobantes_model->updateDetalleComprobante(
					$cuenta->id_detalle_cuenta,
					[
						'estado_balance'        => $estado_balance,
						'fecha_modificacion'    => $fecha_actual,
						'id_funcionario_update' => $id_usuario
					]
				);
			}

			// 10. Guardar el cierre contable
			$datosCierre = [
				'id_entidad'            => $id_entidad,
				'gestion'               => $gestion,
				'mes'                   => $periodo,
				'tipo_cierre'           => $tipo_cierre,
				'fecha_cierre'          => $fecha_actual,
				'id_comprobante'        => $id_comprobante_cierre,
				'descripcion'           => 'Cierre de cuentas de balance',
				'saldo_activo'          => $sumaActivo,
				'saldo_pasivo'          => $sumaPasivo,
				'saldo_patrimonio'      => $sumaPatrimonio,
				'saldo_cuentas_deudor'  => $sumaDeudor,
				'saldo_cuentas_acreedor'=> $sumaAcreedor,
				'saldo_activo_usd'          => $sumaActivoUSD,
				'saldo_pasivo_usd'          => $sumaPasivoUSD,
				'saldo_patrimonio_usd'      => $sumaPatrimonioUSD,
				'saldo_cuentas_deudor_usd'  => $sumaDeudorasUSD,
				'saldo_cuentas_acreedor_usd'=> $sumaAcreedorUSD,
				'id_usuario_registro' => $id_usuario,
				'comprobante'         => implode('-', $comprobantes)
			];
			$this->CierresContables_model->guardarCierre($datosCierre);

			$this->db->trans_commit();
			$response = ['resultado' => 1, 'mensaje' => 'SE REGISTRÓ EL CIERRE DE RESULTADOS CORRECTAMENTE.'];
		} catch (\Exception $e) {
			$this->db->trans_rollback();
			$response = ['resultado' => 0, 'mensaje' => 'ERROR EN EL PROCESO: ' . $e->getMessage()];
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
	private function registrarDetalleCierre(
		$comprobanteId, 
		$cuentasCierre1,
		$cuentasCierre2,
		$entidadId, 
		$usuarioId, 
		$estado_balance, 
		$tipoMovimientoPositivo, 
		$tipoMovimientoNegativo
	){

		// echo("<pre>");
		// print_r($cuentasCierre1);
		// echo("</pre>");
		// die();
		//ACTIVO
		foreach ($cuentasCierre2 as $cuenta) {
			$saldo     = $cuenta->saldo_cuenta;
			$saldoUSD  = $cuenta->saldo_cuenta_usd;

			if ($saldo != 0) {
				$tipo_movimiento = ($saldo > 0) ? $tipoMovimientoNegativo : $tipoMovimientoPositivo;
				// $tipo_movimiento = ($saldo > 0) ? 'HB' : 'DB';
				$saldo           = ($saldo > 0) ? $saldo : $saldo * -1;
				$saldoUSD        = ($saldoUSD > 0) ? $saldoUSD : $saldoUSD * -1;

				$datosDetalle = [
					'id_entidad'                => $entidadId,
					'id_comprobante'            => $comprobanteId,
					'id_cuenta'                 => $cuenta->id,
					'tipo_movimiento'           => $tipo_movimiento,
					'tipo_cambio'               => 6.96,
					'importe_moneda_nacional'   => $saldo,
					'importe_moneda_extranjera' => $saldoUSD,
					'glosa_cuenta'              => '',
					'id_usuario_registro'       => $usuarioId,
					'estado_resultado'          => $estado_balance
				];
				$this->Comprobantes_model->guardarDetalleComprobante($datosDetalle);
			}
		}
		//PASIVO
		foreach ($cuentasCierre1 as $cuenta) {
			$saldo     = $cuenta->saldo_cuenta;
			$saldoUSD  = $cuenta->saldo_cuenta_usd;

			if ($saldo != 0) {
				$tipo_movimiento = ($saldo > 0) ? $tipoMovimientoPositivo : $tipoMovimientoNegativo;
				// $tipo_movimiento = ($saldo > 0) ? 'DB' : 'HB';
				$saldo           = ($saldo > 0) ? $saldo : $saldo * -1;
				$saldoUSD        = ($saldoUSD > 0) ? $saldoUSD : $saldoUSD * -1;

				// echo("TIPO MOVIMIENTO==>".$tipo_movimiento);

				$datosDetalle = [
					'id_entidad'                => $entidadId,
					'id_comprobante'            => $comprobanteId,
					'id_cuenta'                 => $cuenta->id,
					'tipo_movimiento'           => $tipo_movimiento,
					'tipo_cambio'               => 6.96,
					'importe_moneda_nacional'   => $saldo,
					'importe_moneda_extranjera' => $saldoUSD,
					'glosa_cuenta'              => '',
					'id_usuario_registro'       => $usuarioId,
					'estado_resultado'          => $estado_balance
				];
				$this->Comprobantes_model->guardarDetalleComprobante($datosDetalle);
			}
		}
		// echo("<pre>");
		// print_r($cuentasCierre2);
		// echo("</pre>");
		// die();
		
	}



	
}
