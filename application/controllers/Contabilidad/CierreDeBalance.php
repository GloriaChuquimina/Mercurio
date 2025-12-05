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
        $this->load->model('Comunes_model');
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
	public function cargarCierres()
	{
		$id_usuario  = $this->session->userdata('id_usuario');
		$tipo_cierre = "CIB";
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
	public function cargarDatosBalanceGeneral()
    {
		$id_entidad            = $this->input->post('id_entidad');
		$fecha_cierre          = $this->input->post('fecha_cierre');
		$fecha_fin             = $this->input->post('fecha_cierre');
		$fecha_inicio  		   = primerDiaDelAnio($fecha_cierre);
		// $idSeleccionado        = $this->input->post('idSeleccionado');
		$idSeleccionado		   = 'radioAl';	
		$fecha_al     		   = $this->input->post('fecha_cierre');
		$valorCheckCero        = true;
		$moneda                = 'BOB';
		$nivel				   = 0;
		$saldoCero             = true;//para excluir los saldos cuentas en cero de cuentas de orden 
		$cierre                = $this->input->post('cierre');
		/*CUENTAS PARA EL REPORTE*/

		$codigo_activo			   = 1;
		$codigo_pasivo			   = 2;
		$codigo_patrimonio	   	   = 3;
		$codigo_cuentas_deudoras   = 6;
		$codigo_cuentas_acreedoras = 7;
		$id_activo                 = getIdCuenta($codigo_activo,$id_entidad);
		$id_pasivo                 = getIdCuenta($codigo_pasivo,$id_entidad);
		$id_patrimonio             = getIdCuenta($codigo_patrimonio,$id_entidad);		
		$id_cuentas_deudoras   	   = getIdCuenta($codigo_cuentas_deudoras,$id_entidad);
		$id_cuentas_acreedoras 	   = getIdCuenta($codigo_cuentas_acreedoras,$id_entidad);
		

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

		$tipo_cuenta_deudor="deudor";
		$tipo_cuenta_acreedor="acreedor";

		$tipo_cuenta_balance="balance";
		$tipo_cuenta_orden="orden";


		$whereCierre = "";
		if($cierre == 'false'){

			$anio_cierre  = date("Y", strtotime($fecha_al));
			$tipo_cierre ="CIB";
			$filas = $this->Comunes_model->getFechaCierreGestion($anio_cierre,$tipo_cierre);
			$comprobantes_cierre = $filas[0]->comprobante;
			$comprobantes_cierre = str_replace('-', ',', $comprobantes_cierre); 
			$whereCierre = " and c.id not in(".$comprobantes_cierre.") ";
		}
		elseif($cierre == 'true'){
			$whereCierre = "";
		}

		if($moneda === 'BOB'){
			$cuentas_activo   	    = $this->BalanceGeneral_model->getGeneralBalanceGeneralPorMayorBoliviano($id_entidad,$fecha_inicio,$fecha_fin,$codigo_activo,$id_activo,$whereFecha,$tipo_cuenta_deudor,$whereCierre);
			$cuentas_pasivo   	    = $this->BalanceGeneral_model->getGeneralBalanceGeneralPorMayorBoliviano($id_entidad,$fecha_inicio,$fecha_fin,$codigo_pasivo,$id_pasivo,$whereFecha,$tipo_cuenta_acreedor,$whereCierre);
			$cuentas_patrimonio     = $this->BalanceGeneral_model->getGeneralBalanceGeneralPorMayorBoliviano($id_entidad,$fecha_inicio,$fecha_fin,$codigo_patrimonio,$id_patrimonio,$whereFecha,$tipo_cuenta_acreedor,$whereCierre);
			$cuentas_deudoras 	    = $this->BalanceGeneral_model->getGeneralBalanceGeneralPorMayorBoliviano($id_entidad,$fecha_inicio,$fecha_fin,$codigo_cuentas_deudoras,$id_cuentas_deudoras,$whereFecha,$tipo_cuenta_deudor,$whereCierre);
			$cuentas_acreedoras     = $this->BalanceGeneral_model->getGeneralBalanceGeneralPorMayorBoliviano($id_entidad,$fecha_inicio,$fecha_fin,$codigo_cuentas_acreedoras,$id_cuentas_acreedoras,$whereFecha,$tipo_cuenta_acreedor,$whereCierre);
			// echo("<pre>");
			// echo("CUENTAS DE ORDEN DEUDORAS <br>");
			// print_r($cuentas_acreedoras);
			// echo("</pre>");
			// die();
		}
		elseif ($moneda === 'USD') {

			$cuentas_activo   	    = $this->BalanceGeneral_model->getGeneralBalanceGeneralPorMayorUSD($id_entidad,$fecha_inicio,$fecha_fin,$codigo_activo,$id_activo,$whereFecha,$tipo_cuenta_deudor,$whereCierre);
			$cuentas_pasivo   	    = $this->BalanceGeneral_model->getGeneralBalanceGeneralPorMayorUSD($id_entidad,$fecha_inicio,$fecha_fin,$codigo_pasivo,$id_pasivo,$whereFecha,$tipo_cuenta_acreedor,$whereCierre);
			$cuentas_patrimonio     = $this->BalanceGeneral_model->getGeneralBalanceGeneralPorMayorUSD($id_entidad,$fecha_inicio,$fecha_fin,$codigo_patrimonio,$id_patrimonio,$whereFecha,$tipo_cuenta_acreedor,$whereCierre);
			$cuentas_deudoras 	    = $this->BalanceGeneral_model->getGeneralBalanceGeneralPorMayorUSD($id_entidad,$fecha_inicio,$fecha_fin,$codigo_cuentas_deudoras,$id_cuentas_deudoras,$whereFecha,$tipo_cuenta_deudor,$whereCierre);
			$cuentas_acreedoras     = $this->BalanceGeneral_model->getGeneralBalanceGeneralPorMayorUSD($id_entidad,$fecha_inicio,$fecha_fin,$codigo_cuentas_acreedoras,$id_cuentas_acreedoras,$whereFecha,$tipo_cuenta_acreedor,$whereCierre);
		}
		
		/*ORDENANDO CUENTAS*/
		// $cuentas_activo1 		= json_decode(json_encode($cuentas_activo), true);		
		list($cuentasOrdenadasActivo, $sumaTotalGlobalActivo, $sumaTotalGlobalActivoUSD) = $this->ordenarCuentas(
				$cuentas_activo,
				$saldoCero,
				$nivel,
				$tipo_cuenta_balance
			);

		$total_activo			= count($cuentasOrdenadasActivo);
		/*CUENTAS PASIVO*/

		list($cuentasOrdenadasPasivo, $sumaTotalGlobalPasivo, $sumaTotalGlobalPasivoUSD) = $this->ordenarCuentas(
				$cuentas_pasivo,
				$saldoCero,
				$nivel,
				$tipo_cuenta_balance
			);
		$total_pasivo  		    = count($cuentasOrdenadasPasivo);

		/*CUENTAS PATRIMONIO*/
		list($cuentasOrdenadasPatrimonio, $sumaTotalGlobalPatrimonio, $sumaTotalGlobalPatrimonioUSD) = $this->ordenarCuentas(
				$cuentas_patrimonio,
				$saldoCero,
				$nivel,
				$tipo_cuenta_balance
			);
		$total_patrimonio			   = count($cuentasOrdenadasPatrimonio);
		$sumaTotalGlobalPasivo 		   = $sumaTotalGlobalPasivo + $sumaTotalGlobalPatrimonio;
		$total_pasivopatrimonio 	   = $total_pasivo+$total_patrimonio; 
		/*CUENTAS DE ORDEN DEUDORAS*/
		list($cuentasOrdenadasDeudoras, $sumaTotalGlobalDeudoras, $sumaTotalGlobalDeudorasUSD) = $this->ordenarCuentas(
				$cuentas_deudoras,
				$saldoCero,
				$nivel,
				// 2,
				$tipo_cuenta_orden
			);
		$total_deudoras			     = count($cuentasOrdenadasDeudoras);
		/*CUENTAS DE ORDEN ACREEDORAS*/		
		list($cuentasOrdenadasAcreedoras, $sumaTotalGlobalAcreedoras, $sumaTotalGlobalAcreedorasUSD) = $this->ordenarCuentas(
				$cuentas_acreedoras,
				$saldoCero,
				$nivel,
				$tipo_cuenta_orden
			);
		$total_acreedoras             = count($cuentasOrdenadasAcreedoras);
		$max_filas				       = $total_activo+$total_pasivopatrimonio+$total_deudoras+$total_acreedoras;

		$draw    = intval($this->input->get("draw"));
		$start   = intval($this->input->get("start"));
		$length  = intval($this->input->get("length"));	
		$data    = array();
		// $num     = 1;

		$cuentasUnidas = array_merge($cuentasOrdenadasActivo,$cuentasOrdenadasPasivo, $cuentasOrdenadasPatrimonio,$cuentasOrdenadasDeudoras,$cuentasOrdenadasAcreedoras);

		foreach ($cuentasUnidas as $cuenta) {
			$valor_cero='';
			$valor1=0;
			$valor2=0;
			$valor3=0;
			$nivel							= $cuenta['nivel'];
			$indentacion_invertida          = $cuenta['indentacion_invertida'];
			$codigo            			    = $cuenta['codigo'];
			$descripcion 					= $cuenta['descripcion'];
			$saldo_cuenta     	    		= $cuenta['saldo_cuenta'] ? number_format($cuenta['saldo_cuenta'], 2, '.', ',') : '';
			$total_cuenta    	    		= $cuenta['importe_total'] ? number_format($cuenta['importe_total'], 2, '.', ',') : '0';
			$indentacion 					= str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $cuenta['indentacion']);
			$indentacion_invertida 			= str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $cuenta['indentacion_invertida']);
			if ($nivel== 1) {
				$codigo 	   = "<span class='badge badge-primary'><strong><u>{$codigo}</u></strong></span>";
				$descripcion   = "<strong><u>{$descripcion}</u></strong>";
				$valor1    	   = "<div style='text-align: right; color: black; font-weight: bold;'><u>".$valor_cero."</u></div>";
				$valor2        = "<div style='text-align: right; color: black; font-weight: bold;'><u>".$saldo_cuenta."</u></div>";
				$valor3        = "<div style='text-align: right; color: #28a745; font-weight: bold;'><u>".$total_cuenta."</u></div>";
			}
			elseif ($nivel== 2) {
				$codigo 	   = "<span class='badge badge-info'><strong><u>{$codigo}</u></strong></span>";
				$descripcion   = "<strong><u>{$descripcion}</u></strong>";
				$valor1    	   = "<div style='text-align: right; color: black; font-weight: bold;'><u>".$valor_cero."</u></div>";
				$valor2        = "<div style='text-align: right; color: black; font-weight: bold;'><u>".$total_cuenta."</u></div>";
				$valor3        = "<div style='text-align: right; color: black; font-weight: bold;'><u>".$valor_cero."</u></div>";

			}else{
				$codigo 	   =  "<span class='badge badge-secondary'><strong><u>{$codigo}</u></strong></span>";
				$valor1    	   = "<div style='text-align: right; color: black;'>".$saldo_cuenta."</div>";
				$valor2        = "<div style='text-align: right; color: black;'>".$valor_cero."</div>";
				$valor3        = "<div style='text-align: right; color: black;'>".$valor_cero."</div>";
			}

			$data[] = array(
				$codigo,
				$indentacion.$descripcion,
				$valor1,
				$valor2,
				$valor3
			);

			
		}

		// $sumaTotalGlobalPasivoPatrimonio = $sumaTotalGlobalPasivo + $sumaTotalGlobalPatrimonio;
		$output = array(
			"draw" => $draw,
			"recordsTotal" => count($cuentasUnidas),
			"recordsFiltered" => count($cuentasUnidas),
			"totalimporteActivo" => number_format($sumaTotalGlobalActivo,2,'.',','),
            "totalimportePasivoPatrimonio" => number_format($sumaTotalGlobalPasivo,2,'.',','),
            "totalimporteCuentasOrdenDeudoras" => number_format($sumaTotalGlobalDeudoras,2,'.',','),
            "totalimporteCuentasOrdenAcreedoras" => number_format($sumaTotalGlobalAcreedoras,2,'.',','),
			"data" => $data
		);
		echo json_encode($output);
		exit();
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
				if ($nivelMaximo !== null && isset($cuenta['nivel']) && $cuenta['nivel']>= $nivelMaximo) {
					$saldoConHijos    = $importePropio + $sumaHijos;
					$saldoConHijosUSD = $importePropioUSD + $sumaHijosUSD; 
				} else {
					// No sumamos hijos, solo saldo propio
					$saldoConHijos 	  = $importePropio;
					$saldoConHijosUSD = $importePropioUSD;
				}

				// $saldoConHijos    = $importePropio + $sumaHijos;
				// $saldoConHijosUSD = $importePropioUSD + $sumaHijosUSD;
				// $importeTotal = $importeTotal + $importePropio; 

				$cuenta['saldo_cuenta']      = $saldoConHijos;
				$cuenta['importe_total']     = $importePropio+ $sumaHijos;
				$cuenta['saldo_cuenta_USD']  = $saldoConHijosUSD;
				$cuenta['importe_total_USD'] = $importePropioUSD;
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

				// $totalImporte 	  +=  $cuenta['importe_total'];
				$totalImporte 	 += $cuenta['importe_total'] ;
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
			
			// 3. Preparación de datos de cierre

			$id_entidad       				  = $this->input->post('id_entidad_registro');
			$fecha_actual     				  = getFechaHoraActual();
			// $tipo_cambio      				  = 6.96;
			$tipo_cierre      			      = 'CIB';
			$estado_balance   				  = 'CNS';
			$tipo_comprobante 				  = 'TR';
			$fecha_comprobante   		      = $this->input->post('fechaCierreBalance');
			$referencia_cierre  		      = $this->input->post('referenciaCierre');
			$glosa_cierre  		              = $this->input->post('glosaCierre');
			$referencia_general_cuentasorden  = 'Cierre de Cuentas de Orden';
			$glosa_general__cuentasorden      = 'Para cerrar transitoriamente cuentas de orden apropiadas hasta el cierre del presente periodo.';
			$gestion             		      = date('Y', strtotime($fecha_comprobante));
			$periodo                          = (int)date('m', strtotime($fecha_comprobante));
			$referencia_general_cierre        = $this->input->post('referenciaCierre');
			$glosa_general_cierre             = $this->input->post('glosaCierre');
			$tipo_cambio_cierre    	          =  number_format(getTipoCambio(formato_fecha_slash_invertido2($fecha_comprobante)),2,'.',',');

			//3.1 Datos para la apertura de las cuentas

			$referencia_general_apertura      = $this->input->post("referenciaApertura");
			$glosa_general_apertura           = $this->input->post("glosaApertura");
			$gestion_apertura				  = $gestion+1;
			$periodo_apertura				  = 1;
			$fecha_comprobante_apertura		  = $gestion_apertura.'-01-02';
			// $tipo_cambio_apertura      		  = 6.96;
			$tipo_cambio_apertura      		  = number_format(getTipoCambio(formato_fecha_slash_invertido2($fecha_comprobante)),2,'.',',');///OJO
		
			// 4. Parámetros para la consultas de balance

			$contador            		      = 3; // Número de comprobantes a generar para el cierre de balance(cierre de cuentas de balance , cierre de cuentas de orden , apertura de cuentas)
			$idSeleccionado					  = 'radioAl';
			$valorCheckCero 				  = false; // para excluir cuenta en cero de las cuentas activo, pasivp, patrimonio
			$nivel        			          = getNivelMaximo();
			$saldoCero                        = true;//para excluir los saldos cuentas en cero de cuentas de orden 
			$fecha_fin    					  = $this->input->post('fechaCierreBalance');
			$fecha_inicio 				      = primerDiaDelAnio($fecha_fin);
			$fecha_al     					  = $this->input->post('fechaCierreBalance');
			$moneda       					  = 'BOB';

			//4.-CUENTAS MAYORES DEL CIERRE
			
			$codigo_activo     		   = 1;
			$codigo_pasivo     		   = 2;
			$codigo_patrimonio 		   = 3;
			$codigo_cuentas_deudoras   = 6;
			$codigo_cuentas_acreedoras = 7;
			$id_activo		   		   = getIdCuenta($codigo_activo,$id_entidad);
			$id_pasivo		   		   = getIdCuenta($codigo_pasivo,$id_entidad);
			$id_patrimonio	   		   = getIdCuenta($codigo_patrimonio,$id_entidad);
			$id_cuentas_deudoras   	   = getIdCuenta($codigo_cuentas_deudoras,$id_entidad);
			$id_cuentas_acreedoras 	   = getIdCuenta($codigo_cuentas_acreedoras,$id_entidad);
			$codigo_cuenta_ingreso     = 4;
			$codigo_cuenta_egreso      = 5;
			$codigo_perdidas_ganancias = 9;
			$id_cuenta_ingreso         = getIdCuenta($codigo_cuenta_ingreso,$id_entidad);
			$id_cuenta_egreso          = getIdCuenta($codigo_cuenta_egreso,$id_entidad);
			$id_perdidas_ganancias     = getIdCuenta($codigo_perdidas_ganancias,$id_entidad);

			// die();

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

			if($id_cuentas_deudoras !=0){
				$cuentas_deudoras 	    = $this->BalanceGeneral_model->getBalanceGeneralPorMayor(
				$id_entidad,
				$fecha_inicio,
				$fecha_fin,
				$codigo_cuentas_deudoras,
				$id_cuentas_deudoras,
				$whereFecha,
				$tipo_cuenta_deudor);
			}
			else{
				$cuentas_deudoras = array();
			}
			if($id_cuentas_acreedoras !=0	)
			{
				$cuentas_acreedoras     = $this->BalanceGeneral_model->getBalanceGeneralPorMayor(
					$id_entidad,
					$fecha_inicio,
					$fecha_fin,
					$codigo_cuentas_acreedoras,
					$id_cuentas_acreedoras,
					$whereFecha,
					$tipo_cuenta_acreedor);
			}
			else
			{
				$cuentas_acreedoras = array();
			}
			

			// 6. Ordenar y calcular totales
			$tipo_cuenta_balance="balance";
			$tipo_cuenta_orden="orden";
			list($cuentasActivoOrdenadas, $sumaActivo, $sumaActivoUSD) = $this->ordenarCuentas(
				$cuentas_activo,
				$saldoCero,
				$nivel,
				$tipo_cuenta_balance
			);
			$totalActivo=count($cuentasActivoOrdenadas);

			list($cuentasPasivoOrdenadas, $sumaPasivo, $sumaPasivoUSD) = $this->ordenarCuentas(
				$cuentas_pasivo,
				$saldoCero,
				$nivel,
				$tipo_cuenta_balance
			);
			$totalPasivo=count($cuentasPasivoOrdenadas);
			list($cuentasPatrimonioOrdenadas, $sumaPatrimonio, $sumaPatrimonioUSD) = $this->ordenarCuentas(
				$cuentas_patrimonio,
				$saldoCero,
				$nivel,
				$tipo_cuenta_balance
			);
			$totalPatrimonio=count($cuentasPatrimonioOrdenadas);
			list($cuentasDeudorasOrdenadas, $sumaDeudor, $sumaDeudorasUSD) = $this->ordenarCuentas(
				$cuentas_deudoras,
				$saldoCero,
				$nivel,
				$tipo_cuenta_orden
			);
			$totalDeudor=count($cuentasDeudorasOrdenadas);
			list($cuentasAcreedorasOrdenadas, $sumaAcreedor, $sumaAcreedorUSD) = $this->ordenarCuentas(
				$cuentas_acreedoras,
				$saldoCero,
				$nivel,
				$tipo_cuenta_orden
			);
			$totalAcreedor=count($cuentasAcreedorasOrdenadas);

			$max_filas  = $totalActivo+$totalPasivo+$totalPatrimonio+$totalDeudor+$totalAcreedor;
			$cuentasUnidas = array_merge($cuentasActivoOrdenadas,$cuentasPasivoOrdenadas, $cuentasPatrimonioOrdenadas,$cuentasDeudorasOrdenadas,$cuentasAcreedorasOrdenadas);
			// 7 y 8  Generación de los comprobantes y Registro de detalles del comprobante
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
							$tipo_cambio_cierre,
							$id_usuario,
							$tipo_cierre,
							$id_dependencia,
							$fecha_actual,
							'ACT'
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
							'HB',
							$tipo_cambio_cierre,	
							'ACT'					
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
							$tipo_cambio_cierre,
							$id_usuario,
							$tipo_cierre,
							$id_dependencia,
							$fecha_actual,
							'ACT'
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
								'HB',
								$tipo_cambio_cierre,
								'ACT'
							);
						}
						
						break;
					case 3:
					
						// Primer día del año (1 de enero)
						$fecha_inicio_gestion = $gestion_apertura . "-01-01";

						// Último día del año (31 de diciembre)
						$fecha_fin_gestion = $gestion_apertura . "-12-31";

						$datosGestion = [

							'gestion'            => $gestion_apertura,
							'fecha_inicio'       => $fecha_inicio_gestion,
							'fecha_fin'          => $fecha_fin_gestion,
							
						];
						$this->Comunes_model->addGestion($datosGestion);
						$updateGestion = [
							'fecha_modificacion' => $fecha_actual,
							'estado'          => 'HI',
							
						];
						$this->Comunes_model->updateGestion($gestion,$updateGestion);

						$saveComprobante = $this->registrarComprobante(
						    $id_entidad,
							$tipo_comprobante,
							$periodo_apertura,
							$gestion_apertura,
							$referencia_general_apertura,
							$glosa_general_apertura,
							$fecha_comprobante_apertura,
							$tipo_cambio_apertura,//CONSULTAR
							$id_usuario,
							'',
							$id_dependencia,
							$fecha_actual,
							'HI'
						);
						// echo("stephany");
						$comprobantes[] = $saveComprobante;
						$cuentasPasivoPatrimonio = array_merge($cuentas_pasivo, $cuentas_patrimonio);
						$this->registrarDetalleCierre(
							$saveComprobante,
							$cuentasPasivoPatrimonio,
							$cuentas_activo,
							$id_entidad,
							$id_usuario,
							'PEN',
							'HB',
							'DB',
							$tipo_cambio_apertura,
							'HI'
						);
						break;
				}
			}

			// 9. Consolidar cuentas del cierre(comprobante y detalle)
			
			// Definimos los pares de cuentas a procesar
			$cuentasMayor = [
				[$id_activo, $codigo_activo],
				[$id_pasivo, $codigo_pasivo],
				[$id_patrimonio, $codigo_patrimonio],
				[$id_cuentas_deudoras, $codigo_cuentas_deudoras],
				[$id_cuentas_acreedoras, $codigo_cuentas_acreedoras],
				[$id_cuenta_ingreso, $codigo_cuenta_ingreso],
				[$id_cuenta_egreso, $codigo_cuenta_egreso],
				[$id_perdidas_ganancias, $codigo_perdidas_ganancias]
			];

			// echo("<pre>");
			// print_r($cuentasMayor);
			// echo("</pre>");
			// ===============================
			// 1) Procesar CUENTAS DE COMPROBANTES
			// ===============================
			$filtro_comprobante ="";
			$cuentasConsolidadasComprobantes = [];
			foreach ($cuentasMayor as [$id, $codigo]) {
				$cuentasConsolidadasComprobantes = array_merge(
					$cuentasConsolidadasComprobantes,
					$this->CierresContables_model->getCuentasConMovimientoComprobanteByIdMayor(
						$id_entidad,
						$fecha_inicio,
						$fecha_fin,
						$id,
						$codigo,
						$filtro_comprobante
					)
				);
			}

			// echo("<pre>");
			// print_r($cuentasConsolidadasComprobantes);
			// echo("</pre>");
			
			foreach ($cuentasConsolidadasComprobantes as $cuenta) {
				$this->Comprobantes_model->updateComprobante(
					$cuenta->id_comprobante,
					[
						'estado'                => 'HI',
						'tipo_cierre'           => $tipo_cierre,
						'fecha_modificacion'    => $fecha_actual,
						'id_funcionario_update' => $id_usuario
					]
				);
			}

			// ===============================
			// 2) Procesar CUENTAS GENERALES
			// ===============================
			$filtro= "";
			$cuentasConsolidadas = [];
			foreach ($cuentasMayor as [$id, $codigo]) {
				$cuentasConsolidadas = array_merge(
					$cuentasConsolidadas,
					$this->CierresContables_model->getCuentasConMovimientoByIdMayor(
						$id_entidad,
						$fecha_inicio,
						$fecha_fin,
						$id,
						$codigo,
						$filtro
					)
				);
			}

			// echo("<pre>");
			// print_r($cuentasConsolidadas);
			// echo("</pre>");
			
			foreach ($cuentasConsolidadas as $cuenta) {
				$this->Comprobantes_model->updateDetalleComprobante(
					$cuenta->id_detalle_cuenta,
					[
						'estado'                => 'HI',
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
			$response = ['resultado' => 1, 
			             'mensaje' => 'SE REGISTRÓ EL CIERRE DE BALANCE CORRECTAMENTE.',
						'id_entidad' => $id_entidad,
						'fecha' => $fecha_actual,
						];
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
	$fecha_actual,
	$estado){


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
			'tipo_cierre'            => $tipo_cierre,
			'estado'			     => $estado
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
		$tipoMovimientoNegativo,
		$tipo_cambio,
		$estado
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
				// $saldoUSD        = ($saldoUSD > 0) ? $saldoUSD : $saldoUSD * -1;
				$saldoUSD        = round($saldo/$tipo_cambio,2);
				$datosDetalle = [
					'id_entidad'                => $entidadId,
					'id_comprobante'            => $comprobanteId,
					'id_cuenta'                 => $cuenta->id,
					'tipo_movimiento'           => $tipo_movimiento,
					'tipo_cambio'               => $tipo_cambio,
					'importe_moneda_nacional'   => $saldo,
					'importe_moneda_extranjera' => $saldoUSD,
					'glosa_cuenta'              => '',
					'id_usuario_registro'       => $usuarioId,
					'estado_balance'            => $estado_balance,
					'estado'					=> $estado
				];
				$this->Comprobantes_model->guardarDetalleComprobante($datosDetalle);
			}
		}
		//PASIVO
		foreach ($cuentasCierre1 as $cuenta) {
			$saldo     = $cuenta->saldo_cuenta;
			// $saldoUSD  = $cuenta->saldo_cuenta_usd;
			$saldoUSD  = round($saldo/$tipo_cambio,2);
			if ($saldo != 0) {
				$tipo_movimiento = ($saldo > 0) ? $tipoMovimientoPositivo : $tipoMovimientoNegativo;
				// $tipo_movimiento = ($saldo > 0) ? 'DB' : 'HB';
				$saldo           = ($saldo > 0) ? $saldo : $saldo * -1;
				// $saldoUSD        = ($saldoUSD > 0) ? $saldoUSD : $saldoUSD * -1;
				$saldoUSD        = round($saldo/$tipo_cambio,2);
				// echo("TIPO MOVIMIENTO==>".$tipo_movimiento);

				$datosDetalle = [
					'id_entidad'                => $entidadId,
					'id_comprobante'            => $comprobanteId,
					'id_cuenta'                 => $cuenta->id,
					'tipo_movimiento'           => $tipo_movimiento,
					'tipo_cambio'               => $tipo_cambio,
					'importe_moneda_nacional'   => $saldo,
					'importe_moneda_extranjera' => $saldoUSD,
					'glosa_cuenta'              => '',
					'id_usuario_registro'       => $usuarioId,
					'estado_balance'            => $estado_balance,
					'estado'					=> $estado

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
