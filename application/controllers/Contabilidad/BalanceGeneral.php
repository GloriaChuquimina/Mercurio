<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BalanceGeneral extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->_is_logued_in();
        $this->load->model('BalanceGeneral_model');
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
		$titulo = "Balance General";		
		$dato['titulo'] = $titulo;
		$this->load->view('inicio/cabecera',$dato);
		$this->load->view('inicio/menu',$dato);
		$this->load->view('contabilidad/balancegeneral',$dato);
		$this->load->view('inicio/pie');
	}

	private function ordenarJerarquicamente(
    array $cuentas, 
    int $padreId = 0, 
    int $indentacion = 0, 
    bool $excluirDesdeNivelTres = false,
    int $nivelMaximo = null // nuevo parámetro
	) {
		$ordenadas     = [];
		$totalImporte  = 0;

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
				[$hijosOrdenados, $sumaHijos] =
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

				$importePropio = isset($cuenta['saldo_cuenta']) ? (float)$cuenta['saldo_cuenta'] : 0;

				// Si el nivel máximo está definido y la cuenta está en ese nivel máximo, sumamos saldo hijos
				if ($nivelMaximo !== null && isset($cuenta['nivel']) && $cuenta['nivel'] == $nivelMaximo) {
					$saldoConHijos = $importePropio + $sumaHijos;
				} else {
					// No sumamos hijos, solo saldo propio
					$saldoConHijos = $importePropio;
				}

				$cuenta['saldo_cuenta'] = $saldoConHijos;
				// $cuenta['importe_total'] = $saldoConHijos;
				$cuenta['importe_total'] = $importePropio + $sumaHijos;
			
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

		return [$ordenadas, $totalImporte];
	}
	private function ordenarJerarquicamenteCuentasOrden(array $cuentas, int $padreId = 0, int $indentacion = 0,bool $excluirDesdeNivelDos=false,int $nivelMaximo = null)
	{
		$ordenadas     = [];
		$totalImporte  = 0;

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
				[$hijosOrdenados, $sumaHijos] =
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



				// Si el nivel máximo está definido y la cuenta está en ese nivel máximo, sumamos saldo hijos
				// if ($nivelMaximo !== null && isset($cuenta['nivel']) && $cuenta['nivel'] == $nivelMaximo) {
				if ($nivelMaximo !== null && isset($cuenta['nivel']) && $cuenta['nivel']>= $nivelMaximo) {
					$saldoConHijos = $importePropio + $sumaHijos;
				} else {
					// No sumamos hijos, solo saldo propio
					$saldoConHijos = $importePropio;
				}

				$cuenta['saldo_cuenta'] = $saldoConHijos;
				// $cuenta['importe_total'] = $saldoConHijos;
				$cuenta['importe_total'] = $importePropio + $sumaHijos;

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

		return [$ordenadas, $totalImporte];
	}

	public function cargarDatosBalanceGeneral()
    {
		$id_entidad            = $this->input->post('id_entidad');
		$cuentasSeleccionadas  = $this->input->post('cuentasSeleccionadas');
		$fecha_inicio  		   = $this->input->post('fecha_desde');
		$fecha_fin             = $this->input->post('fecha_hasta');
		$fecha_al              = $this->input->post('fecha_al');
		$fecha_desde           = primerDiaDelAnio($fecha_al);
		$idSeleccionado        = $this->input->post('idSeleccionado');	
		$valorCheckCero        = $this->input->post('valorCheckCero');
		$moneda                = $this->input->post('moneda');
		$nivel				   = $this->input->post('nivel');
		$cierre				   = $this->input->post('cierre');



		/*CUENTAS PARA EL REPORTE*/

		$codigo_activo=1;
		$codigo_pasivo=2;
		$codigo_patrimonio=3;
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
			// $whereFecha = " AND fecha_comprobante <='$fecha_al' ";
			$whereFecha = " AND fecha_comprobante BETWEEN '$fecha_desde' AND '$fecha_al' ";
		}
		elseif($idSeleccionado == 'radioEntre'){
			$whereFecha = " AND fecha_comprobante BETWEEN '$fecha_inicio' AND '$fecha_fin' ";
		}

		$tipo_cuenta_deudor="deudor";
		$tipo_cuenta_acreedor="acreedor";

		$whereCierre = "";
		if($cierre == 'false'){

			$anio_cierre  = date("Y", strtotime($fecha_al));
			$tipo_cierre ="CIB";
			$filas = $this->Comunes_model->getFechaCierreGestion($anio_cierre,$tipo_cierre);
			// $comprobantes_cierre = $filas[0]->comprobante;
			// $comprobantes_cierre = str_replace('-', ',', $comprobantes_cierre); 
			// $whereCierre = " and c.id not in(".$comprobantes_cierre.") ";

			if($filas)
			{
				$comprobantes_cierre = $filas[0]->comprobante;
				$comprobantes_cierre = str_replace('-', ',', $comprobantes_cierre); 
				$whereCierre = " and c.id not in(".$comprobantes_cierre.") ";
			}
			else
			{
				$whereCierre = "";
			}



		}
		elseif($cierre == 'true'){
			$whereCierre = "";
		}

		// echo("Where Fecha: ".$whereFecha);

		if($moneda === 'BOB'){
 
			// echo("Ingresar Steph BOB");
			$cuentas_activo   	    = $this->BalanceGeneral_model->getGeneralBalanceGeneralPorMayorBoliviano($id_entidad,$fecha_inicio,$fecha_fin,$codigo_activo,$id_activo,$whereFecha,$tipo_cuenta_deudor,$whereCierre);
			
			$cuentas_pasivo   	    = $this->BalanceGeneral_model->getGeneralBalanceGeneralPorMayorBoliviano($id_entidad,$fecha_inicio,$fecha_fin,$codigo_pasivo,$id_pasivo,$whereFecha,$tipo_cuenta_acreedor,$whereCierre);
			$cuentas_patrimonio     = $this->BalanceGeneral_model->getGeneralBalanceGeneralPorMayorBoliviano($id_entidad,$fecha_inicio,$fecha_fin,$codigo_patrimonio,$id_patrimonio,$whereFecha,$tipo_cuenta_acreedor,$whereCierre);
			if($id_cuentas_deudoras !=0){
				$cuentas_deudoras 	    = $this->BalanceGeneral_model->getGeneralBalanceGeneralPorMayorBoliviano($id_entidad,$fecha_inicio,$fecha_fin,$codigo_cuentas_deudoras,$id_cuentas_deudoras,$whereFecha,$tipo_cuenta_deudor,$whereCierre);
			}
			else
			{
				$cuentas_deudoras = array();
			}
			if($id_cuentas_acreedoras !=0){
				$cuentas_acreedoras     = $this->BalanceGeneral_model->getGeneralBalanceGeneralPorMayorBoliviano($id_entidad,$fecha_inicio,$fecha_fin,$codigo_cuentas_acreedoras,$id_cuentas_acreedoras,$whereFecha,$tipo_cuenta_acreedor,$whereCierre);
			}
			else
			{
				$cuentas_acreedoras = array();
			}
			// $cuentas_acreedoras     = $this->BalanceGeneral_model->getGeneralBalanceGeneralPorMayorBoliviano($id_entidad,$fecha_inicio,$fecha_fin,$codigo_cuentas_acreedoras,$id_cuentas_acreedoras,$whereFecha,$tipo_cuenta_acreedor,$whereCierre);
		}
		elseif ($moneda === 'USD') {

			$cuentas_activo   	    = $this->BalanceGeneral_model->getGeneralBalanceGeneralPorMayorUSD($id_entidad,$fecha_inicio,$fecha_fin,$codigo_activo,$id_activo,$whereFecha,$tipo_cuenta_deudor,$whereCierre);
			$cuentas_pasivo   	    = $this->BalanceGeneral_model->getGeneralBalanceGeneralPorMayorUSD($id_entidad,$fecha_inicio,$fecha_fin,$codigo_pasivo,$id_pasivo,$whereFecha,$tipo_cuenta_acreedor,$whereCierre);
			$cuentas_patrimonio     = $this->BalanceGeneral_model->getGeneralBalanceGeneralPorMayorUSD($id_entidad,$fecha_inicio,$fecha_fin,$codigo_patrimonio,$id_patrimonio,$whereFecha,$tipo_cuenta_acreedor,$whereCierre);
			if($id_cuentas_deudoras !=0){
				$cuentas_deudoras 	    = $this->BalanceGeneral_model->getGeneralBalanceGeneralPorMayorUSD($id_entidad,$fecha_inicio,$fecha_fin,$codigo_cuentas_deudoras,$id_cuentas_deudoras,$whereFecha,$tipo_cuenta_deudor,$whereCierre);
			}
			else{
				$cuentas_deudoras = array();
			}
			if($id_cuentas_acreedoras !=0)
			{
				$cuentas_acreedoras     = $this->BalanceGeneral_model->getGeneralBalanceGeneralPorMayorUSD($id_entidad,$fecha_inicio,$fecha_fin,$codigo_cuentas_acreedoras,$id_cuentas_acreedoras,$whereFecha,$tipo_cuenta_acreedor,$whereCierre);
			}
			else{
				$cuentas_acreedoras = array();
			}
		}
		
		/*ORDENANDO CUENTAS*/
		$cuentas_activo1 		= json_decode(json_encode($cuentas_activo), true);		
		$ordenadas_activo 		= $this->ordenarJerarquicamente($cuentas_activo1,0,0,$excluirCuentasEnCero,$nivel);
		$cuentasOrdenadasActivo = $ordenadas_activo[0];
		$sumaTotalGlobalActivo  = $ordenadas_activo[1];
		$total_activo			= count($cuentasOrdenadasActivo);

		
		/*CUENTAS PASIVO*/
		$cuentas_pasivo 		= json_decode(json_encode($cuentas_pasivo), true);
		$ordenadas_pasivo 		= $this->ordenarJerarquicamente($cuentas_pasivo,0,0,$excluirCuentasEnCero,$nivel);
		$cuentasOrdenadasPasivo = $ordenadas_pasivo[0];
		$sumaTotalGlobalPasivo  = $ordenadas_pasivo[1];
		$total_pasivo  		    = count($cuentasOrdenadasPasivo);

		/*CUENTAS PATRIMONIO*/
		
		$cuentas_patrimonio 		   = json_decode(json_encode($cuentas_patrimonio), true);
		$ordenadas_patrimonio 		   = $this->ordenarJerarquicamente($cuentas_patrimonio,0,0,$excluirCuentasEnCero,$nivel);
		$cuentasOrdenadasPatrimonio    = $ordenadas_patrimonio[0];
		$sumaTotalGlobalPatrimonio     = $ordenadas_patrimonio[1];
		$total_patrimonio			   = count($cuentasOrdenadasPatrimonio);
		$sumaTotalGlobalPasivo 		   = $sumaTotalGlobalPasivo + $sumaTotalGlobalPatrimonio;

		$total_pasivopatrimonio 	   = $total_pasivo+$total_patrimonio; 
		

		/*CUENTAS DE ORDEN DEUDORAS*/
		$cuentas_deudoras 		     = json_decode(json_encode($cuentas_deudoras), true);		
		$ordenadas_cuentas_deudoras  = $this->ordenarJerarquicamenteCuentasOrden($cuentas_deudoras ,0,0,$excluirCuentasEnCero,$nivel);

		$cuentasOrdenadasDeudoras    = $ordenadas_cuentas_deudoras[0];
		$sumaTotalGlobalDeudoras     = $ordenadas_cuentas_deudoras[1];
		$total_deudoras			     = count($cuentasOrdenadasDeudoras);


		/*CUENTAS DE ORDEN ACREEDORAS*/		
		// $total_acreedoras             = count($cuentas_acreedoras);
		$cuentas_acreedoras 		  = json_decode(json_encode($cuentas_acreedoras), true);
		$ordenadas_cuentas_acreedoras = $this->ordenarJerarquicamenteCuentasOrden($cuentas_acreedoras ,0,0,$excluirCuentasEnCero,$nivel);
		$cuentasOrdenadasAcreedoras   = $ordenadas_cuentas_acreedoras[0];
		$sumaTotalGlobalAcreedoras    = $ordenadas_cuentas_acreedoras[1];
		$total_acreedoras             = count($ordenadas_cuentas_acreedoras);


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

	/*REPORTES CON BUSQUEDAS*/
	function ReporteBalanceGeneralPDF_1($id_entidad,$cuentasBuscadas,$fecha_inicio,$fecha_fin,$valorCheckCero,$idSeleccionado,$fecha_al,$nivel,$moneda,$cierre)
	{			
		// $id_entidad      = $this->input->post('id_entidad');		
		/****************************/
		/*INICIO DEL REPORTE*/
		/****************************/		
		$this->load->library('fpdf/pdf2');
        $pdf = new Pdf2();
        $pdf->AliasNbPages();
        $pdf->SetAutoPageBreak(true, 30);
        $pdf->SetMargins(20,15,10);		
		$pdf->SetTitle(utf8_decode("BALANCE GENERAL"));
		$pdf->entidad=descripcion_nombre_entidad($id_entidad);
		$pdf->sigla=sigla_entidad($id_entidad);
		$pdf->tituloCabecera = 'BALANCE GENERAL DE CIERRE';

		if($idSeleccionado == 'radioAl'){
			$pdf->subtituloCabecera1 = "AL ".formato_fecha_dia_2($fecha_al);  

		}
		elseif($idSeleccionado == 'radioEntre'){
			$pdf->subtituloCabecera1 = "Entre el ".formato_fecha_slash($fecha_inicio). " y el ".formato_fecha_slash($fecha_fin);  
		}
		if($moneda === 'BOB'){
			$pdf->subtituloCabecera2 = "Expresado en Bolivianos";  
		}
		elseif ($moneda === 'USD') {
			$pdf->subtituloCabecera2 = "Expresado en Dólares Americanos";  
		}

		
        $w = array(15,115,40,50);
        $pdf->setWidthsG($w);
        $pdf->SetAligns(array('C','L','C','C'));
		$pdf->AddPage('L','Letter');
		$pdf->opcion_cabecera=5;
		$pdf->Header();
		$pdf->SetFillColor(255,255,255);
        $pdf->SetTextColor(0);
        $pdf->SetFont('Arial','',6);
		$pdf->Ln(1);
		$pdf->opcion_pie='FOOTER_VACIO';
		/*CUERPO DEL REPORTE*/
		$pdf->SetFillColor(255,255,255);
        $pdf->SetTextColor(0);
        $pdf->SetFont('Arial','',6);
        $num = 0;
        $total=0;
		$total_activo=0;
		$total_pasivo=0;
		$total_patrimonio=0;
		$max_filas=0;

		$nivel_deudor=0;
		$nivel_acreedor=0;

		// 1. Reemplazar guiones por comas
		$cadena = str_replace('-', ',', $cuentasBuscadas);
		// 2. Eliminar la última coma si existe
		$cadena = rtrim($cadena, ',');

		/*CUENTAS PARA EL REPORTE*/

		$codigo_activo             = 1;
		$codigo_pasivo			   = 2;
		$codigo_patrimonio		   = 3;
		$codigo_cuentas_deudoras   = 6;
		$codigo_cuentas_acreedoras = 7;


		$id_activo                 = getIdCuenta($codigo_activo,$id_entidad);
		$id_pasivo                 = getIdCuenta($codigo_pasivo,$id_entidad);
		$id_patrimonio             = getIdCuenta($codigo_patrimonio,$id_entidad);		
		$id_cuentas_deudoras   	   = getIdCuenta($codigo_cuentas_deudoras,$id_entidad);
		$id_cuentas_acreedoras 	   = getIdCuenta($codigo_cuentas_acreedoras,$id_entidad);
		/*CONSULTAS CUENTAS EMPRESA*/
		
		// $excluirCuentasEnCero= false;
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


		$fecha_desde           = primerDiaDelAnio($fecha_al);
		$whereFecha = "";
		if($idSeleccionado == 'radioAl'){
			// $whereFecha = " AND fecha_comprobante <='$fecha_al' ";
			$whereFecha = " AND fecha_comprobante BETWEEN '$fecha_desde' AND '$fecha_al' ";
		}
		elseif($idSeleccionado == 'radioEntre'){
			$whereFecha = " AND fecha_comprobante BETWEEN '$fecha_inicio' AND '$fecha_fin' ";
		}

		$whereCierre = "";
		if($cierre == 'false'){

			$anio_cierre  = date("Y", strtotime($fecha_al));
			$tipo_cierre ="CIB";
			$filas = $this->Comunes_model->getFechaCierreGestion($anio_cierre,$tipo_cierre);
			if($filas)
			{
				$comprobantes_cierre = $filas[0]->comprobante;
				$comprobantes_cierre = str_replace('-', ',', $comprobantes_cierre); 
				$whereCierre = " and c.id not in(".$comprobantes_cierre.") ";
			}
			else
			{
				$whereCierre = "";
			}
			
		}
		elseif($cierre == 'true'){
			$whereCierre = "";
		}
	
		$tipo_cuenta_deudor="deudor";
		$tipo_cuenta_acreedor="acreedor";
		if($moneda === 'BOB')
		{

			$pdf->subtituloCabecera2 = "Expresado en Bolivianos";  

			// echo("Ingresar Steph BOB");
			$cuentas_activo   	    = $this->BalanceGeneral_model->getGeneralBalanceGeneralPorMayorBoliviano($id_entidad,$fecha_inicio,$fecha_fin,$codigo_activo,$id_activo,$whereFecha,$tipo_cuenta_deudor,$whereCierre);
			$cuentas_pasivo   	    = $this->BalanceGeneral_model->getGeneralBalanceGeneralPorMayorBoliviano($id_entidad,$fecha_inicio,$fecha_fin,$codigo_pasivo,$id_pasivo,$whereFecha,$tipo_cuenta_acreedor,$whereCierre);
			$cuentas_patrimonio     = $this->BalanceGeneral_model->getGeneralBalanceGeneralPorMayorBoliviano($id_entidad,$fecha_inicio,$fecha_fin,$codigo_patrimonio,$id_patrimonio,$whereFecha,$tipo_cuenta_acreedor,$whereCierre);
			// $cuentas_deudoras 	    = $this->BalanceGeneral_model->getGeneralBalanceGeneralPorMayorBoliviano($id_entidad,$fecha_inicio,$fecha_fin,$codigo_cuentas_deudoras,$id_cuentas_deudoras,$whereFecha,$tipo_cuenta_deudor,$whereCierre);
			// $cuentas_acreedoras     = $this->BalanceGeneral_model->getGeneralBalanceGeneralPorMayorBoliviano($id_entidad,$fecha_inicio,$fecha_fin,$codigo_cuentas_acreedoras,$id_cuentas_acreedoras,$whereFecha,$tipo_cuenta_acreedor,$whereCierre);
			if($id_cuentas_deudoras !=0){
				$cuentas_deudoras 	    = $this->BalanceGeneral_model->getGeneralBalanceGeneralPorMayorBoliviano($id_entidad,$fecha_inicio,$fecha_fin,$codigo_cuentas_deudoras,$id_cuentas_deudoras,$whereFecha,$tipo_cuenta_deudor,$whereCierre);
			}
			else
			{
				$cuentas_deudoras = array();
			}
			if($id_cuentas_acreedoras !=0){
				
				$cuentas_acreedoras     = $this->BalanceGeneral_model->getGeneralBalanceGeneralPorMayorBoliviano($id_entidad,$fecha_inicio,$fecha_fin,$codigo_cuentas_acreedoras,$id_cuentas_acreedoras,$whereFecha,$tipo_cuenta_acreedor,$whereCierre);
			}
			else
			{
				$cuentas_acreedoras = array();
			}
		}if($moneda === 'USD'){
			$pdf->subtituloCabecera2 = "Expresado en Dólares Americanos"; 

			$cuentas_activo   	    = $this->BalanceGeneral_model->getGeneralBalanceGeneralPorMayorUSD($id_entidad,$fecha_inicio,$fecha_fin,$codigo_activo,$id_activo,$whereFecha,$tipo_cuenta_deudor,$whereCierre);
			$cuentas_pasivo   	    = $this->BalanceGeneral_model->getGeneralBalanceGeneralPorMayorUSD($id_entidad,$fecha_inicio,$fecha_fin,$codigo_pasivo,$id_pasivo,$whereFecha,$tipo_cuenta_acreedor,$whereCierre);
			$cuentas_patrimonio     = $this->BalanceGeneral_model->getGeneralBalanceGeneralPorMayorUSD($id_entidad,$fecha_inicio,$fecha_fin,$codigo_patrimonio,$id_patrimonio,$whereFecha,$tipo_cuenta_acreedor,$whereCierre);
			if($id_cuentas_deudoras !=0){
				$cuentas_deudoras 	    = $this->BalanceGeneral_model->getGeneralBalanceGeneralPorMayorUSD($id_entidad,$fecha_inicio,$fecha_fin,$codigo_cuentas_deudoras,$id_cuentas_deudoras,$whereFecha,$tipo_cuenta_deudor,$whereCierre);
			}else{
				$cuentas_deudoras = array();
			}
			if($id_cuentas_acreedoras !=0){
				$cuentas_acreedoras     = $this->BalanceGeneral_model->getGeneralBalanceGeneralPorMayorUSD($id_entidad,$fecha_inicio,$fecha_fin,$codigo_cuentas_acreedoras,$id_cuentas_acreedoras,$whereFecha,$tipo_cuenta_acreedor,$whereCierre);
			}
			else{
				$cuentas_acreedoras = array();
			}
			// $cuentas_acreedoras     = $this->BalanceGeneral_model->getGeneralBalanceGeneralPorMayorUSD($id_entidad,$fecha_inicio,$fecha_fin,$codigo_cuentas_acreedoras,$id_cuentas_acreedoras,$whereFecha,$tipo_cuenta_acreedor,$whereCierre);
		}
		
	
		/*CUENTAS ACTIVOS*/
		// echo("**************cuentas_ordenadas*********");
		// $total_activo			= count($cuentas_activo);
		$cuentas_activo1 		= json_decode(json_encode($cuentas_activo), true);		
		$ordenadas_activo 		= $this->ordenarJerarquicamente($cuentas_activo1,0,0,$excluirCuentasEnCero,$nivel);

		$cuentasOrdenadasActivo = $ordenadas_activo[0];
		$sumaTotalGlobalActivo  = $ordenadas_activo[1];
		$total_activo			= count($cuentasOrdenadasActivo);

		/*CUENTAS PASIVO*/
		
		// $total_pasivo  		    = count($cuentas_pasivo);
		$cuentas_pasivo 		= json_decode(json_encode($cuentas_pasivo), true);
		$ordenadas_pasivo 		= $this->ordenarJerarquicamente($cuentas_pasivo,0,0,$excluirCuentasEnCero,$nivel);
		
		// $id_activo=1;
		// $id_pasivo=2;
		// $id_patrimonio=3;
		// $id_cuentas_deudoras   	   = 4;
		// $id_cuentas_acreedoras 	   = 5;

		$cuentasOrdenadasPasivo = $ordenadas_pasivo[0];
		$sumaTotalGlobalPasivo  = $ordenadas_pasivo[1];
		$total_pasivo  		    = count($cuentasOrdenadasPasivo);

		/*CUENTAS PATRIMONIO*/
		
		// $total_patrimonio			   = count($cuentas_patrimonio);
		$cuentas_patrimonio 		   = json_decode(json_encode($cuentas_patrimonio), true);
		$ordenadas_patrimonio 		   = $this->ordenarJerarquicamente($cuentas_patrimonio,0,0,$excluirCuentasEnCero,$nivel);

		$cuentasOrdenadasPatrimonio    = $ordenadas_patrimonio[0];
		$sumaTotalGlobalPatrimonio     = $ordenadas_patrimonio[1];
		$total_patrimonio			   = count($cuentasOrdenadasPatrimonio);
		
		$sumaTotalGlobalPasivo 		   = $sumaTotalGlobalPasivo + $sumaTotalGlobalPatrimonio;
		$cuentasUnidasPasivoPatrimonio = array_merge($cuentasOrdenadasPasivo, $cuentasOrdenadasPatrimonio);

		$total_pasivopatrimonio 	   = $total_pasivo+$total_patrimonio; 
		$max_filas				       = max($total_activo, $total_pasivopatrimonio); 

		$pdf->SetFillColor(255,255,255);
		$pdf->SetFont('Arial', '', 8);
		$ini_x=$pdf->GetX();
		// $ini_y=$pdf->GetY();
		
		$pdf->setX(10); 
		$pdf->SetWidths([105, 105]);
		$pdf->SetAligns(['L','L']);
		$cabecera1="1 ACTIVO";
		$cabecera2="2 PASIVO";
		$fila= array(
						$cabecera1,
						$cabecera2		
					);

		// $pdf->Row_Reportes_BG($fila,true, '', 3,$indentacion_invertida2,$nivel);								
		// $pdf->Row_Reportes_BG($fila,true, '', 3,0,1);								
		
		$pdf->SetWidths([25,60,15,15,15]);
		$pdf->SetAligns(['L','L','R','R','R']);
		$valor_cero =0;
		$valor_cero_p =0;
		$total_activos=0;
		$total_pasivos=0;
		$formato=0;
		$pdf->ln(4);
		
		for ($i = 0; $i < $max_filas; $i++) {
    		// $pdf->ln();
			// ACTIVO
			if (isset($cuentasOrdenadasActivo[$i])) {

				$cuenta					= $cuentasOrdenadasActivo[$i];
				$nivel_activo 					= $cuenta['nivel'];
				$indentacion_invertida_activo   = $cuenta['indentacion_invertida'];
				$codigo_activo      			= $cuenta['codigo'];
				$descripcion_activo 			= $cuenta['descripcion'];
				$saldo_cuenta_activo      	    = $cuenta['saldo_cuenta'] ? number_format($cuenta['saldo_cuenta'], 2, '.', ',') : '';
				$total_cuenta_activo     	    = $cuenta['importe_total'] ? number_format($cuenta['importe_total'], 2, '.', ',') : '0';
				
			} else {
				$indentacion_invertida_activo   = '';
				$codigo_activo      			= '';
				$descripcion_activo 			= '';
				$saldo_cuenta_activo      	    = '';
				$total_cuenta_activo     	    = '';
				$valor_cero						= '';
				$nivel_activo 					= 0;
	
			}
			// PASIVO
			// if (isset($cuentasOrdenadasPasivo[$i])) {
			if (isset($cuentasUnidasPasivoPatrimonio[$i])) {

				// $cuentasUnidas 					= array_merge($cuentasOrdenadasPasivo, $cuentasOrdenadasPatrimonio);

				$cuenta							= $cuentasUnidasPasivoPatrimonio[$i];
				$nivel_pasivo 					= $cuenta['nivel'];
				$indentacion_invertida_pasivo   = $cuenta['indentacion_invertida'];
				$codigo     				    = $cuenta['codigo'];
				$descripcion 					= $cuenta['descripcion'];
				$saldo_cuenta     	    		= $cuenta['saldo_cuenta'] ? number_format($cuenta['saldo_cuenta'], 2, '.', ',') : '0.00';
				$total_cuenta    	    		= $cuenta['importe_total'] ? number_format($cuenta['importe_total'], 2, '.', ',') : '0.00';
			} else {
	
				$nivel_pasivo 			 = 0;
				$indentacion_invertida   = '';
				$codigo     			 = '';
				$descripcion 			 = '';
				$saldo_cuenta      	     = '';
				$total_cuenta     	     = '';
				$valor_cero				 = '';
	
			}
			// PATRIMONIO

			if($nivel_activo==1 && $nivel_pasivo==1)
			{
				$formato=3;
				$pdf->SetFont('Arial', 'BU', 7);
				$valor_cero='';
				$fila=array(
							$codigo_activo,	
							$descripcion_activo,
							$valor_cero,
							$saldo_cuenta_activo,
							$total_cuenta_activo,								
							$codigo,	
							$descripcion,
							$valor_cero,
							$saldo_cuenta,
							$total_cuenta,
							$formato
							);
				$pdf->setX(8);	
				$pdf->SetWidths([20,50,20,20,20,20,50,20,20,20,5]);
				$pdf->SetAligns(['R','L','R','R','R','R','L','R','R','R','R']);		
				$pdf->Row_SinLinea_BG($fila,true, '', 11);

			}
			else
			{
				if($nivel_activo==2 && $nivel_pasivo==2 )
				{
					$formato=3;
					$fila=array(
							$codigo_activo,	
							$descripcion_activo,
							$valor_cero,
							$total_cuenta_activo,
							$valor_cero,
							$codigo,	
							$descripcion,
							$valor_cero,
							$total_cuenta,
							$valor_cero,
							$formato
							);		
					
					$pdf->SetFont('Arial', 'BU', 7);
					$pdf->setX(8);		
					// $pdf->SetWidths([25,50,15,15,15,25,50,15,15,15,5]);
					$pdf->SetWidths([20,50,20,20,20,20,50,20,20,20,5]);
					$pdf->SetAligns(['R','L','R','R','R','R','L','R','R','R','R']);		
					$pdf->Row_SinLinea_BG($fila,true, '', 11);

				}
				else
				{
					$valor1=0;
					$valor2=0;
					$valor3=0;
					$valor4=0;
					$valor5=0;
					$valor6=0;
					if(($nivel_activo ==1 || $nivel_activo ==2) && ($nivel_pasivo !=1 && $nivel_pasivo !=2))
					{
						if($nivel_activo ==1)
						{
							$valor1=$valor_cero;
							$valor2=$saldo_cuenta_activo;
							$valor3=$total_cuenta_activo;
						}
						elseif($nivel_activo ==2 )
						{
							$valor1=$valor_cero;
							$valor2=$total_cuenta_activo;
							$valor3=$valor_cero;
						}
						$valor4=$saldo_cuenta;
						$valor5="";
						$valor6="";
						$formato=1;
					}else
					{
						if(($nivel_activo !=1 && $nivel_activo !=2) && ($nivel_pasivo ==1 || $nivel_pasivo ==2))
						{
							// var_dump($nivel_activo, $nivel_pasivo);
							if($nivel_pasivo ==1)
							{
								$valor4=$valor_cero;
								$valor5=$saldo_cuenta;
								$valor6=$total_cuenta;
							}
							elseif($nivel_pasivo ==2 )
							{
								$valor4=$valor_cero;
								$valor5=$total_cuenta;
								$valor6=$valor_cero;
							}
							$valor1=$saldo_cuenta_activo;
							$valor2="";
							$valor3="";
							$formato=2;
						}
						else
						{

							if(($nivel_activo ==1 || $nivel_activo ==2) && ($nivel_pasivo ==1 || $nivel_pasivo ==2))
							{

								// var_dump($nivel_activo, $nivel_pasivo);
								if($nivel_activo ==1)
								{
									$valor1=$valor_cero;
									$valor2=$saldo_cuenta_activo;
									$valor3=$total_cuenta_activo;
								}
								elseif($nivel_activo ==2 )
								{
									$valor1=$valor_cero;
									$valor2=$total_cuenta_activo;
									$valor3=$valor_cero;
								}
								if($nivel_pasivo ==1)
								{
									$valor4=$valor_cero;
									$valor5=$saldo_cuenta;
									$valor6=$total_cuenta;
								}
								elseif($nivel_pasivo ==2 )
								{
									$valor4=$valor_cero;
									$valor5=$total_cuenta;
									$valor6=$valor_cero;
								}
								$formato=3;

							}
							else{
								// var_dump($nivel_activo, $nivel_pasivo);
								$valor1=$saldo_cuenta_activo;
								$valor2="";
								$valor3="";
								$valor4=$saldo_cuenta;
								$valor5="";
								$valor6="";	
								$formato=0;
							}
						}
					}			
			 			$fila=array(
						$codigo_activo,	
						$descripcion_activo,
						$valor1,
						$valor2,
						$valor3,
						$codigo,	
						$descripcion,
						$valor4,
						$valor5,
						$valor6,
						$formato
						);	
						
					// $pdf->ln(2);
					$pdf->SetFont('Arial', '', 7);
					$pdf->setX(8);		
					// $pdf->SetWidths([25,50,15,15,15,25,50,15,15,15,5]);
					$pdf->SetWidths([20,50,20,20,20,20,50,20,20,20,5]);
					$pdf->SetAligns(['R','L','R','R','R','R','L','R','R','R']);		
					$pdf->Row_SinLinea_BG($fila,true, '', 11);
					
				}
				
			}


		}
		$pdf->ln(5);

		$fila=array(
					"TOTAL ACTIVO",
					number_format($sumaTotalGlobalActivo,2,'.',',' ),
					"TOTAL PASIVO Y PATRIMONIO",
					number_format($sumaTotalGlobalPasivo,2,'.',',')
					);		
					
					$pdf->setX(8);		
					$pdf->SetWidths([110,20,110,20]);
					$pdf->SetAligns(['C','R','C','R']);	
					$pdf->SetFont('Arial', 'B', 7);	
					$pdf->Row_SinLinea_BG_SUBTOTALES($fila,true, '', 4);
		//  $pdf->ln();
		/*******************/
		/*CUENTAS DE ORDEN */
		/*******************/
		$pdf->ln(5);			
		
		
		/*CUENTAS DE ORDEN DEUDORAS*/
		
		// $total_deudoras			= count($cuentas_deudoras);
		$cuentas_deudoras 		= json_decode(json_encode($cuentas_deudoras), true);
		
		$ordenadas_cuentas_deudoras  = $this->ordenarJerarquicamenteCuentasOrden($cuentas_deudoras ,0,0,$excluirCuentasEnCero,$nivel);
		$cuentasOrdenadasDeudoras    = $ordenadas_cuentas_deudoras[0];
		$sumaTotalGlobalDeudoras     = $ordenadas_cuentas_deudoras[1];
		$total_deudoras			     = count($cuentasOrdenadasDeudoras);
		/*CUENTAS DE ORDEN ACREEDORAS*/
		
		// $total_acreedoras             = count($cuentas_acreedoras);
		$cuentas_acreedoras 		  = json_decode(json_encode($cuentas_acreedoras), true);
		$ordenadas_cuentas_acreedoras = $this->ordenarJerarquicamenteCuentasOrden($cuentas_acreedoras ,0,0,$excluirCuentasEnCero,$nivel);
		$cuentasOrdenadasAcreedoras   = $ordenadas_cuentas_acreedoras[0];
		$sumaTotalGlobalAcreedoras    = $ordenadas_cuentas_acreedoras[1];
		$total_acreedoras             = count($ordenadas_cuentas_acreedoras);
	
		$max_filas_cuentas_orden      = max($total_deudoras, $total_acreedoras); 

		$formato2=0;
		for ($i = 0; $i < $max_filas_cuentas_orden; $i++) {
    		// $pdf->ln();
			// CUENTAS DEUDORAS
			if (isset($cuentasOrdenadasDeudoras[$i])) {

				$cuenta							= $cuentasOrdenadasDeudoras[$i];
				$nivel_deudor 					= $cuenta['nivel'];
				$indentacion_invertida_deudor   = $cuenta['indentacion_invertida'];
				$codigo_deudor      			= $cuenta['codigo'];
				$descripcion_deudor				= $cuenta['descripcion'];
				$saldo_cuenta_deudor      	    = $cuenta['saldo_cuenta'] ? number_format($cuenta['saldo_cuenta'], 2, '.', ',') : '0.00';
				$total_cuenta_deudor     	    = $cuenta['importe_total'] ? number_format($cuenta['importe_total'], 2, '.', ',') : '0.00';
				
			} else {
				$indentacion_invertida_deudor   = '';
				$codigo_deudor      			= '';
				$descripcion_deudor 			= '';
				$saldo_cuenta_deudor      	    = '';
				$total_cuenta_deudor     	    = '';
				$valor_cero						= '';
	
			}
			// CUENTAS ACREEDORAS
			if (isset($cuentasOrdenadasAcreedoras[$i])) {

				$cuenta							= $cuentasOrdenadasAcreedoras[$i];
				$nivel_acreedor 				= $cuenta['nivel'];
				$indentacion_invertida_acreedor = $cuenta['indentacion_invertida'];
				$codigo_acreedor     			= $cuenta['codigo'];
				$descripcion_acreedor 			= $cuenta['descripcion'];
				$saldo_cuenta_acreedor     	    = $cuenta['saldo_cuenta'] ? number_format($cuenta['saldo_cuenta'], 2, '.', ',') : '0.00';
				$total_cuenta_acreedor    	    = $cuenta['importe_total'] ? number_format($cuenta['importe_total'], 2, '.', ',') : '0.00';
			} else {
	
				$indentacion_invertida_acreedor   = '';
				$codigo_acreedor     			  = '';
				$descripcion_acreedor 			  = '';
				$saldo_cuenta_acreedor      	  = '';
				$total_cuenta_acreedor     	      = '';
				$valor_cero				          = '';

			}

			if($nivel_deudor==1 && $nivel_acreedor==1)
			{
				$pdf->SetFont('Arial', 'BU', 7);

				$fila=array(
							$codigo_deudor,	
							$descripcion_deudor,
							$valor_cero,
							$saldo_cuenta_deudor,
							$total_cuenta_deudor,								
							$codigo_acreedor,	
							$descripcion_acreedor,
							$valor_cero,
							$saldo_cuenta_acreedor,
							$total_cuenta_acreedor,
							$formato2
							
							);
				$pdf->setX(8);	
				// $pdf->SetWidths([25,50,15,15,15,25,50,15,15,15,5]);
				$pdf->SetWidths([20,50,20,20,20,20,50,20,20,20,5]);
				$pdf->SetAligns(['R','L','R','R','R','R','L','R','R','R','R']);		
				$pdf->Row_SinLinea_BG($fila,true, '', 11);

			}
			else
			{
				if($nivel_deudor==2 && $nivel_acreedor==2 )
				{
					
					$fila=array(
							$codigo_deudor,	
							$descripcion_deudor,
							$valor_cero,
							$total_cuenta_deudor,
							$valor_cero,
							$codigo_acreedor,	
							$descripcion_acreedor,
							$valor_cero,
							$total_cuenta_acreedor,
							$valor_cero,
							$formato2
							);		
					// $pdf->ln(2);
					$pdf->SetFont('Arial', '', 7);
					$pdf->setX(8);		
					// $pdf->SetWidths([25,50,15,15,15,25,50,15,15,15,5]);
					$pdf->SetWidths([20,50,20,20,20,20,50,20,20,20,5]);
					$pdf->SetAligns(['R','L','R','R','R','R','L','R','R','R','R']);		
					$pdf->Row_SinLinea_BG($fila,true, '', 11);

				}
				else
				{
					$valor1=0;
					$valor2=0;
					$valor3=0;
					$valor4=0;
					$valor5=0;
					$valor6=0;
					if(($nivel_deudor ==1 || $nivel_deudor ==2) && ($nivel_acreedor !=1 || $nivel_acreedor !=2))
					{
						if($nivel_deudor ==1)
						{
							$valor1=$valor_cero;
							$valor2=$saldo_cuenta_deudor;
							$valor3=$total_cuenta_deudor;
						}
						elseif($nivel_deudor ==2 )
						{
							$valor1=$valor_cero;
							$valor2=$total_cuenta_deudor;
							$valor3=$valor_cero;
						}
						$valor4=$saldo_cuenta_acreedor;
						$valor5="";
						$valor6="";
						$formato2=1;
					}else
					{
						if(($nivel_deudor !=1 || $nivel_deudor !=2) && ($nivel_acreedor ==1 || $nivel_acreedor ==2))
						{
							if($nivel_acreedor ==1)
							{
								$valor4=$valor_cero;
								$valor5=$saldo_cuenta_acreedor;
								$valor6=$total_cuenta_acreedor;
							}
							elseif($nivel_acreedor ==2 )
							{
								$valor4=$valor_cero;
								$valor5=$total_cuenta_acreedor;
								$valor6=$valor_cero;
							}
							$valor1=$saldo_cuenta_deudor;
							$valor2="";
							$valor3="";
							$formato2=2;
						}
						else
						{
							$valor1=$saldo_cuenta_deudor;
							$valor2="";
							$valor3="";
							$valor4=$saldo_cuenta_acreedor;
							$valor5="";
							$valor6="";
						}
					}
					

					$fila=array(
						$codigo_deudor,	
						$descripcion_deudor,
						$valor1,
						$valor2,
						$valor3,
						$codigo_acreedor,	
						$descripcion_acreedor,
						$valor4,
						$valor5,
						$valor6,
						$formato
						);	
						
					// $pdf->ln(2);
					$pdf->SetFont('Arial', '', 7);
					$pdf->setX(8);		
					// $pdf->SetWidths([25,50,15,15,15,25,50,15,15,15,5]);
					$pdf->SetWidths([20,50,20,20,20,20,50,20,20,20,5]);
					$pdf->SetAligns(['R','L','R','R','R','R','L','R','R','R','R']);		
					$pdf->Row_SinLinea_BG($fila,true, '', 11);

				}
				
			}
		}
		$pdf->ln(5);

		$fila=array(
					"TOTAL CUENTAS DE ORDEN DEUDOR",
					number_format($sumaTotalGlobalDeudoras,2,'.',',' ),
					"TOTAL CUENTAS DE ORDEN ACREEDOR",
					number_format($sumaTotalGlobalAcreedoras,2,'.',',')
					);		
					
					$pdf->setX(8);		
					$pdf->SetWidths([110,20,110,20]);
					$pdf->SetAligns(['C','R','C','R']);	
					$pdf->SetFont('Arial', 'B', 7);	
					$pdf->Row_SinLinea_BG_SUBTOTALES($fila,true, '', 4);
		$pdf->ln(4);
		
		$fila=array(
					"TOTAL ACTIVOS Y CUENTAS DE ORDEN",
					number_format(($sumaTotalGlobalActivo+$sumaTotalGlobalDeudoras),2,'.',',' ),
					"TOTAL PASIVO, PATRIMONIO Y CUENTAS DE ORDEN ACREEDOR",
					number_format(($sumaTotalGlobalAcreedoras+$sumaTotalGlobalPasivo),2,'.',',')
					);		
					
					$pdf->setX(8);		
					$pdf->SetWidths([110,20,110,20]);
					$pdf->SetAligns(['C','R','C','R']);	
					$pdf->SetFont('Arial', 'B', 6);	
					$pdf->Row_SinLinea_BG_TOTALES($fila,true, '', 4);
		
		$pdf->Output('I',utf8_decode('ReporteBalanceGeneral.pdf')); 
	}
	function ReporteBalanceGeneralPDF_2($id_entidad,$cuentasBuscadas,$fecha_inicio,$fecha_fin,$valorCheckCero,$idSeleccionado,$fecha_al,$nivel,$moneda,$cierre)
	{			
	
		/****************************/
		/*INICIO DEL REPORTE*/
		/****************************/
		
		$this->load->library('fpdf/pdf2');
        $pdf = new Pdf2();
        $pdf->AliasNbPages();
        $pdf->SetAutoPageBreak(true, 30);
        $pdf->SetMargins(20,15,10);		
		$pdf->SetTitle(utf8_decode("BALANCE GENERAL"));
		$pdf->entidad=descripcion_nombre_entidad($id_entidad);
		$pdf->sigla=sigla_entidad($id_entidad);
		$pdf->tituloCabecera = 'BALANCE GENERAL';

		$fecha_desde           = primerDiaDelAnio($fecha_al);
		$whereFecha = "";
		if($idSeleccionado == 'radioAl'){
			// $whereFecha = " AND fecha_comprobante <='$fecha_al' ";
			$whereFecha = " AND fecha_comprobante BETWEEN '$fecha_desde' AND '$fecha_al' ";
			$pdf->subtituloCabecera1 = "AL ".formato_fecha_dia_2($fecha_al);  
		}
		elseif($idSeleccionado == 'radioEntre'){
			$pdf->subtituloCabecera1 = "Entre el ".formato_fecha_slash($fecha_inicio). " y el ".formato_fecha_slash($fecha_fin);  
		}
		if($moneda === 'BOB'){
			$pdf->subtituloCabecera2 = "Expresado en Bolivianos";  
		}
		elseif ($moneda === 'USD') {
			$pdf->subtituloCabecera2 = "Expresado en Dólares Americanos";  
		}

        $w = array(15,115,40,50);
        $pdf->setWidthsG($w);
        $pdf->SetAligns(array('C','L','C','C'));
		$pdf->AddPage('P','Letter');
		$pdf->opcion_cabecera=9;
		$pdf->Header();
		$pdf->SetFillColor(255,255,255);
        $pdf->SetTextColor(0);
        $pdf->SetFont('Arial','',6);
		$pdf->Ln(1);
		/*CUERPO DEL REPORTE*/
		$pdf->SetFillColor(255,255,255);
        $pdf->SetTextColor(0);
        $pdf->SetFont('Arial','',6);
        $num = 0;
        $total=0;
		$nivel_deudor=0;
		$nivel_acreedor=0;

		/*CUENTAS DEL REPORTE */

		$codigo_activo             = 1;
		$codigo_pasivo			   = 2;
		$codigo_patrimonio		   = 3;
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

		$whereCierre="";
		if($cierre == 'false'){

			$anio_cierre  = date("Y", strtotime($fecha_al));
			$tipo_cierre ="CIB";
			$filas = $this->Comunes_model->getFechaCierreGestion($anio_cierre,$tipo_cierre);
			if($filas)
			{
				$comprobantes_cierre = $filas[0]->comprobante;
				$comprobantes_cierre = str_replace('-', ',', $comprobantes_cierre); 
				$whereCierre = " and c.id not in(".$comprobantes_cierre.") ";
			}
			else
			{
				$whereCierre = "";
			}



		}
		elseif($cierre == 'true'){
			$whereCierre = "";
		}

		$tipo_cuenta_deudor="deudor";
		$tipo_cuenta_acreedor="acreedor";
		if($moneda === 'BOB'){

			$pdf->subtituloCabecera2 = "Expresado en Bolivianos";  

			// echo("Ingresar Steph BOB");
			$cuentas_activo   	    = $this->BalanceGeneral_model->getGeneralBalanceGeneralPorMayorBoliviano($id_entidad,$fecha_inicio,$fecha_fin,$codigo_activo,$id_activo,$whereFecha,$tipo_cuenta_deudor,$whereCierre);
			$cuentas_pasivo   	    = $this->BalanceGeneral_model->getGeneralBalanceGeneralPorMayorBoliviano($id_entidad,$fecha_inicio,$fecha_fin,$codigo_pasivo,$id_pasivo,$whereFecha,$tipo_cuenta_acreedor,$whereCierre);
			$cuentas_patrimonio     = $this->BalanceGeneral_model->getGeneralBalanceGeneralPorMayorBoliviano($id_entidad,$fecha_inicio,$fecha_fin,$codigo_patrimonio,$id_patrimonio,$whereFecha,$tipo_cuenta_acreedor,$whereCierre);
			
			if($id_cuentas_deudoras !=0){

				$cuentas_deudoras 	    = $this->BalanceGeneral_model->getGeneralBalanceGeneralPorMayorBoliviano($id_entidad,$fecha_inicio,$fecha_fin,$codigo_cuentas_deudoras,$id_cuentas_deudoras,$whereFecha,$tipo_cuenta_deudor,$whereCierre);
			}else{
				$cuentas_deudoras = array();
			}
			if($id_cuentas_acreedoras != 0){
				$cuentas_acreedoras     = $this->BalanceGeneral_model->getGeneralBalanceGeneralPorMayorBoliviano($id_entidad,$fecha_inicio,$fecha_fin,$codigo_cuentas_acreedoras,$id_cuentas_acreedoras,$whereFecha,$tipo_cuenta_acreedor,$whereCierre);
			}else{
				$cuentas_acreedoras = array();
			}

		}
		elseif ($moneda === 'USD') {

			$pdf->subtituloCabecera2 = "Expresado en Dólares Americanos";  

			$cuentas_activo   	    = $this->BalanceGeneral_model->getGeneralBalanceGeneralPorMayorUSD($id_entidad,$fecha_inicio,$fecha_fin,$codigo_activo,$id_activo,$whereFecha,$tipo_cuenta_deudor,$whereCierre);
			$cuentas_pasivo   	    = $this->BalanceGeneral_model->getGeneralBalanceGeneralPorMayorUSD($id_entidad,$fecha_inicio,$fecha_fin,$codigo_pasivo,$id_pasivo,$whereFecha,$tipo_cuenta_acreedor,$whereCierre);
			$cuentas_patrimonio     = $this->BalanceGeneral_model->getGeneralBalanceGeneralPorMayorUSD($id_entidad,$fecha_inicio,$fecha_fin,$codigo_patrimonio,$id_patrimonio,$whereFecha,$tipo_cuenta_acreedor,$whereCierre);
			if($cuentas_deudoras !=0){
				$cuentas_deudoras 	    = $this->BalanceGeneral_model->getGeneralBalanceGeneralPorMayorUSD($id_entidad,$fecha_inicio,$fecha_fin,$codigo_cuentas_deudoras,$id_cuentas_deudoras,$whereFecha,$tipo_cuenta_deudor,$whereCierre);
			}
			else{
				$cuentas_deudoras = array();
			}
			if($cuentas_acreedoras != 0)
				$cuentas_acreedoras     = $this->BalanceGeneral_model->getGeneralBalanceGeneralPorMayorUSD($id_entidad,$fecha_inicio,$fecha_fin,$codigo_cuentas_acreedoras,$id_cuentas_acreedoras,$whereFecha,$tipo_cuenta_acreedor,$whereCierre);
			else
				$cuentas_acreedoras = array();
			
		}
		
		/*ORDENANDO CUENTAS*/
		$cuentas_activo1 		= json_decode(json_encode($cuentas_activo), true);		
		$ordenadas_activo 		= $this->ordenarJerarquicamente($cuentas_activo1,0,0,$excluirCuentasEnCero,$nivel);
		$cuentasOrdenadasActivo = $ordenadas_activo[0];
		$sumaTotalGlobalActivo  = $ordenadas_activo[1];
		$total_activo			= count($cuentasOrdenadasActivo);

		// echo("<pre>");
		// print_r($cuentasOrdenadasActivo);
		// echo("</pre>");
		// die();

		/*CUENTAS PASIVO*/
		$cuentas_pasivo 		= json_decode(json_encode($cuentas_pasivo), true);
		$ordenadas_pasivo 		= $this->ordenarJerarquicamente($cuentas_pasivo,0,0,$excluirCuentasEnCero,$nivel);
		$cuentasOrdenadasPasivo = $ordenadas_pasivo[0];
		$sumaTotalGlobalPasivo  = $ordenadas_pasivo[1];
		$total_pasivo  		    = count($cuentasOrdenadasPasivo);

		/*CUENTAS PATRIMONIO*/
		
		$cuentas_patrimonio 		   = json_decode(json_encode($cuentas_patrimonio), true);
		$ordenadas_patrimonio 		   = $this->ordenarJerarquicamente($cuentas_patrimonio,0,0,$excluirCuentasEnCero,$nivel);
		$cuentasOrdenadasPatrimonio    = $ordenadas_patrimonio[0];
		$sumaTotalGlobalPatrimonio     = $ordenadas_patrimonio[1];
		$total_patrimonio			   = count($cuentasOrdenadasPatrimonio);
		$sumaTotalGlobalPasivo 		   = $sumaTotalGlobalPasivo + $sumaTotalGlobalPatrimonio;

		$total_pasivopatrimonio 	   = $total_pasivo+$total_patrimonio; 
		

		/*CUENTAS DE ORDEN DEUDORAS*/
		// $total_deudoras			= count($cuentas_deudoras);
		$cuentas_deudoras 		= json_decode(json_encode($cuentas_deudoras), true);
		
		$ordenadas_cuentas_deudoras  = $this->ordenarJerarquicamenteCuentasOrden($cuentas_deudoras ,0,0,$excluirCuentasEnCero,$nivel);
		$cuentasOrdenadasDeudoras    = $ordenadas_cuentas_deudoras[0];
		$sumaTotalGlobalDeudoras     = $ordenadas_cuentas_deudoras[1];
		$total_deudoras			     = count($cuentasOrdenadasDeudoras);

		/*CUENTAS DE ORDEN ACREEDORAS*/
		
		// $total_acreedoras             = count($cuentas_acreedoras);
		$cuentas_acreedoras 		  = json_decode(json_encode($cuentas_acreedoras), true);
		$ordenadas_cuentas_acreedoras = $this->ordenarJerarquicamenteCuentasOrden($cuentas_acreedoras ,0,0,$excluirCuentasEnCero,$nivel);
		$cuentasOrdenadasAcreedoras   = $ordenadas_cuentas_acreedoras[0];
		$sumaTotalGlobalAcreedoras    = $ordenadas_cuentas_acreedoras[1];
		$total_acreedoras             = count($ordenadas_cuentas_acreedoras);


		$max_filas				       = $total_activo+$total_pasivopatrimonio+$total_deudoras+$total_acreedoras;

		$draw    = intval($this->input->get("draw"));
		$start   = intval($this->input->get("start"));
		$length  = intval($this->input->get("length"));	
		$data    = array();
		// $num     = 1;

		$cuentasUnidas = array_merge($cuentasOrdenadasActivo,$cuentasOrdenadasPasivo, $cuentasOrdenadasPatrimonio,$cuentasOrdenadasDeudoras,$cuentasOrdenadasAcreedoras);


		$pdf->SetFillColor(255,255,255);
		$pdf->SetFont('Arial', '', 7);
		$ini_x=$pdf->GetX();
		// $ini_y=$pdf->GetY();
		
		$pdf->setX(12); 
		$pdf->SetWidths([25, 120, 15, 15, 15]);
		$pdf->SetAligns(['L','L','R','R','R']);

		// ACTIVO 
		foreach ($cuentasOrdenadasActivo as $cuenta)
		{  

			$nivel_activo 					= $cuenta['nivel'];
			$indentacion_invertida_activo   = $cuenta['indentacion_invertida'];
			$codigo_activo      			= $cuenta['codigo'];
			$descripcion_activo 			= $cuenta['descripcion'];
			$saldo_cuenta_activo      	    = $cuenta['saldo_cuenta'] ? number_format($cuenta['saldo_cuenta'], 2, '.', ',') : '';
			$total_cuenta_activo     	    = $cuenta['importe_total'] ? number_format($cuenta['importe_total'], 2, '.', ',') : '0';

			$valor_cero='';
			$valor1=0;
			$valor2=0;
			$valor3=0;

			if($nivel_activo==1)
			{
				$valor1=$valor_cero;
				$valor2=$saldo_cuenta_activo;
				$valor3=$total_cuenta_activo;
			}elseif($nivel_activo==2)
			{
				$valor1=$valor_cero;
				$valor2=$total_cuenta_activo;
				$valor3=$valor_cero;
			}else{
				$valor1=$saldo_cuenta_activo;
				$valor2="";
				$valor3="";
			}


			$fila=array(
						$codigo_activo,	
						$descripcion_activo,
						$valor1,
						$valor2,
						$valor3,
						$nivel_activo
						);

			$pdf->setX(5); 
			$pdf->SetWidths([25,100,20,20,20,5]);
			$pdf->SetAligns(['R','L','R','R','R']);		
			$pdf->Row_Reportes_BG($fila,true, '', 6);	
						
		}
		$pdf->ln(5);

		$fila=array(
					"TOTAL ACTIVO",
					number_format($sumaTotalGlobalActivo,2,'.',',' )
					);		
					
		$pdf->setX(5);		
		$pdf->SetWidths([170,20]);
		$pdf->SetAligns(['C','R']);	
		$pdf->SetFont('Arial', 'B', 7);	
		$pdf->Row_SinLinea_BG_SUBTOTALES($fila,true, '', 2);

		$pdf->ln(5);
		// PASIVO
		foreach ($cuentasOrdenadasPasivo as $cuenta)
		{  

			$nivel_pasivo 					= $cuenta['nivel'];
			$indentacion_invertida_pasivo   = $cuenta['indentacion_invertida'];
			$codigo_pasivo      			= $cuenta['codigo'];
			$descripcion_pasivo 			= $cuenta['descripcion'];
			$saldo_cuenta_pasivo      	    = $cuenta['saldo_cuenta'] ? number_format($cuenta['saldo_cuenta'], 2, '.', ',') : '';
			$total_cuenta_pasivo     	    = $cuenta['importe_total'] ? number_format($cuenta['importe_total'], 2, '.', ',') : '0';

			$valor_cero='';
			$valor1=0;
			$valor2=0;
			$valor3=0;

			if($nivel_pasivo==1)
			{
				$valor1=$valor_cero;
				$valor2=$saldo_cuenta_pasivo;
				$valor3=$total_cuenta_pasivo;
			}elseif($nivel_pasivo==2)
			{
				$valor1=$valor_cero;
				$valor2=$total_cuenta_pasivo;
				$valor3=$valor_cero;
			}else{
				$valor1=$saldo_cuenta_pasivo;
				$valor2="";
				$valor3="";
			}


			$fila=array(
						$codigo_pasivo,	
						$descripcion_pasivo,
						$valor1,
						$valor2,
						$valor3,
						$nivel_pasivo
						);

			$pdf->setX(5); 
			$pdf->SetWidths([25,100,20,20,20,5]);
			$pdf->SetAligns(['R','L','R','R','R']);		
			$pdf->Row_Reportes_BG($fila,true, '', 6);	
						
		}
		$pdf->ln(3);
		// PATRIMONIO
		foreach ($cuentasOrdenadasPatrimonio as $cuenta)
		{  

			$nivel_patrimonio 					= $cuenta['nivel'];
			$indentacion_invertida_patrimonio   = $cuenta['indentacion_invertida'];
			$codigo_patrimonio      			= $cuenta['codigo'];
			$descripcion_patrimonio 			= $cuenta['descripcion'];
			$saldo_cuenta_patrimonio     	    = $cuenta['saldo_cuenta'] ? number_format($cuenta['saldo_cuenta'], 2, '.', ',') : '';
			$total_cuenta_patrimonio     	    = $cuenta['importe_total'] ? number_format($cuenta['importe_total'], 2, '.', ',') : '0';

			$valor_cero='';
			$valor1=0;
			$valor2=0;
			$valor3=0;

			if($nivel_patrimonio==1)
			{
				$valor1=$valor_cero;
				$valor2=$saldo_cuenta_patrimonio;
				$valor3=$total_cuenta_patrimonio;
			}elseif($nivel_activo==2)
			{
				$valor1=$valor_cero;
				$valor2=$total_cuenta_patrimonio;
				$valor3=$valor_cero;
			}else{
				$valor1=$saldo_cuenta_patrimonio;
				$valor2="";
				$valor3="";
			}


			$fila=array(
						$codigo_patrimonio,	
						$descripcion_patrimonio,
						$valor1,
						$valor2,
						$valor3,
						$nivel_patrimonio
						);

			$pdf->setX(5); 
			$pdf->SetWidths([25,100,20,20,20,5]);
			$pdf->SetAligns(['R','L','R','R','R']);		
			$pdf->Row_Reportes_BG($fila,true, '', 6);	
						
		}
		$pdf->ln(5);

		$fila=array(
					"TOTAL PASIVO y PATRIMONIO",
					number_format($sumaTotalGlobalPasivo,2,'.',',' )
					);		
					
		$pdf->setX(5);		
		$pdf->SetWidths([170,20]);
		$pdf->SetAligns(['C','R']);	
		$pdf->SetFont('Arial', 'B', 7);	
		$pdf->Row_SinLinea_BG_SUBTOTALES($fila,true, '', 2);
		$pdf->ln(5);		
		// CUENTAS DE ORDEN DEUDORAS
		$titulo="CUENTAS DE ORDEN";
		$pdf->SetFont('Arial', 'B', 7);
		$pdf->SetFillColor(230, 230, 225);
		// $pdf->SetXY(10, $y_i);
		$pdf->SetX(15);
		$pdf->MultiCell(190, 5, "-----" . utf8_decode($titulo) . "-----", 0,'C', 1);
		$pdf->ln(3);
		// $y=$pdf->GetY();

		foreach ($cuentasOrdenadasDeudoras as $cuenta)
		{  

			$nivel_cd 					= $cuenta['nivel'];
			$indentacion_invertida_cd   = $cuenta['indentacion_invertida'];
			$codigo_cd      			= $cuenta['codigo'];
			$descripcion_cd 			= $cuenta['descripcion'];
			$saldo_cuenta_cd      	    = $cuenta['saldo_cuenta'] ? number_format($cuenta['saldo_cuenta'], 2, '.', ',') : '';
			$total_cuenta_cd     	    = $cuenta['importe_total'] ? number_format($cuenta['importe_total'], 2, '.', ',') : '0';

			$valor_cero='';
			$valor1=0;
			$valor2=0;
			$valor3=0;

			if($nivel_cd==1)
			{
				$valor1=$valor_cero;
				$valor2=$saldo_cuenta_cd;
				$valor3=$total_cuenta_cd;
			}elseif($nivel_cd==2)
			{
				$valor1=$valor_cero;
				$valor2=$total_cuenta_cd;
				$valor3=$valor_cero;
			}else{
				$valor1=$saldo_cuenta_cd;
				$valor2="";
				$valor3="";
			}


			$fila=array(
						$codigo_cd,	
						$descripcion_cd,
						$valor1,
						$valor2,
						$valor3,
						$nivel_cd
						);

			$pdf->setX(5); 
			$pdf->SetWidths([25,100,20,20,20,5]);
			$pdf->SetAligns(['R','L','R','R','R']);		
			$pdf->Row_Reportes_BG_CUENTAS($fila,true, '', 6);	
						
		}
		$pdf->ln(5);

		$fila=array(
					"TOTAL CUENTAS DE ORDEN DEUDORAS",
					number_format($sumaTotalGlobalDeudoras,2,'.',',' )
					);		
					
		$pdf->setX(5);		
		$pdf->SetWidths([170,20]);
		$pdf->SetAligns(['C','R']);	
		$pdf->SetFont('Arial', 'B', 7);	
		$pdf->Row_SinLinea_BG_SUBTOTALES($fila,true, '', 2);

		$pdf->ln(5);
		// CUENTAS DE ORDEN ACREEDORAS
		foreach ($cuentasOrdenadasAcreedoras as $cuenta)
		{  

			$nivel_ca 					= $cuenta['nivel'];
			$indentacion_invertida_ca   = $cuenta['indentacion_invertida'];
			$codigo_ca      			= $cuenta['codigo'];
			$descripcion_ca 			= $cuenta['descripcion'];
			$saldo_cuenta_ca      	    = $cuenta['saldo_cuenta'] ? number_format($cuenta['saldo_cuenta'], 2, '.', ',') : '';
			$total_cuenta_ca     	    = $cuenta['importe_total'] ? number_format($cuenta['importe_total'], 2, '.', ',') : '0';

			$valor_cero='';
			$valor1=0;
			$valor2=0;
			$valor3=0;

			if($nivel_ca==1)
			{
				$valor1=$valor_cero;
				$valor2=$saldo_cuenta_ca;
				$valor3=$total_cuenta_ca;
			}elseif($nivel_ca==2)
			{
				$valor1=$valor_cero;
				$valor2=$total_cuenta_ca;
				$valor3=$valor_cero;
			}else{
				$valor1=$saldo_cuenta_ca;
				$valor2="";
				$valor3="";
			}


			$fila=array(
						$codigo_ca,	
						$descripcion_ca,
						$valor1,
						$valor2,
						$valor3,
						$nivel_ca
						);

			$pdf->setX(5); 
			$pdf->SetWidths([25,100,20,20,20,5]);
			$pdf->SetAligns(['R','L','R','R','R']);		
			$pdf->Row_Reportes_BG_CUENTAS($fila,true, '', 6);	
						
		}
		$pdf->ln(5);

		$fila=array(
					"TOTAL CUENTAS DE ORDEN ACREEDORAS",
					number_format($sumaTotalGlobalAcreedoras,2,'.',',' )
					);		
					
		$pdf->setX(5);		
		$pdf->SetWidths([170,20]);
		$pdf->SetAligns(['C','R']);	
		$pdf->SetFont('Arial', 'B', 7);	
		$pdf->Row_SinLinea_BG_SUBTOTALES($fila,true, '', 2);


		// $pdf->Ln();
		$pdf->opcion_pie='FOOTER_VACIO';
		$pdf->Footer();
		$pdf->Output('I',utf8_decode('ReporteBalanceGeneral.pdf')); 
	}


}