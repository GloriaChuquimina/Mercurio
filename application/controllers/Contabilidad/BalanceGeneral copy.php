<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BalanceGeneral extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->_is_logued_in();
        $this->load->model('BalanceGeneral_model');
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
	// private function ordenarJerarquicamente($cuentas, $padreId = 0, $indentacion = 0)
	// {
	// 	$ordenadas = [];
	// 	$totalImporte = 0;

	// 	foreach ($cuentas as &$cuenta) {
	// 		if ($cuenta['padre'] == $padreId) {
	// 			// Verificar si tiene hijos
	// 			$tieneHijos = false;
	// 			foreach ($cuentas as $posibleHijo) {
	// 				if (
	// 					$posibleHijo['padre'] == $cuenta['id'] ||
	// 					$posibleHijo['padre'] == $cuenta['ruta'] // Para cuentas mayores
	// 				) {
	// 					$tieneHijos = true;
	// 					break;
	// 				}
	// 			}

	// 			// Procesar hijos recursivamente
	// 			list($hijosOrdenados, $sumaHijos) = $this->ordenarJerarquicamente($cuentas, $cuenta['id'], $indentacion + 1);

	// 			// Sumar importe propio + importe de hijos
	// 			$cuenta['importe_total'] = $cuenta['saldo_cuenta'] + $sumaHijos;

	// 			// Datos extra
	// 			$cuenta['indentacion'] = $indentacion;
	// 			$cuenta['es_padre'] = $tieneHijos;

	// 			// Agregar al resultado
	// 			$ordenadas[] = $cuenta;

	// 			// Agregar los hijos
	// 			$ordenadas = array_merge($ordenadas, $hijosOrdenados);

	// 			// Sumar al total de este nivel
	// 			$totalImporte += $cuenta['importe_total'];
	// 		}
	// 	}

	// 	return [$ordenadas, $totalImporte];
	// }
	private function ordenarJerarquicamente(array $cuentas, int $padreId = 0, int $indentacion = 0)
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
				[$hijosOrdenados, $sumaHijos] =
					$this->ordenarJerarquicamente($cuentas, $cuenta['id'], $indentacion + 1);

				// --- Sumar importe propio + importe hijos -------------------------
				$importePropio              = isset($cuenta['saldo_cuenta'])
												? (float) $cuenta['saldo_cuenta']
												: 0;
				$cuenta['importe_total']    = $importePropio + $sumaHijos;

				// --- Campos extra --------------------------------------------------
				$cuenta['indentacion']      = $indentacion;
				$cuenta['es_padre']         = $tieneHijos;

				// --- Añadir al resultado ------------------------------------------
				$ordenadas[] = $cuenta;
				$ordenadas   = array_merge($ordenadas, $hijosOrdenados);

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
		$fecha_desde  		   = $this->input->post('fecha_desde');
		$fecha_hasta           = $this->input->post('fecha_hasta');

		// $cuentas   = $this->BalanceGeneral_model->getGeneralBalanceGeneral($id_entidad,$cadena,$fecha_desde,$fecha_hasta);
		$cuentas   = $this->BalanceGeneral_model->getGeneralBalanceGeneral($id_entidad,$fecha_desde,$fecha_hasta);
		$cuentas = json_decode(json_encode($cuentas), true);
		// $ordenadas = $this->ordenarJerarquicamente($cuentas);
		// list($cuentasOrdenadas, $importeTotalGeneral) = $this->ordenarJerarquicamente($cuentas);
		$ordenadas = $this->ordenarJerarquicamente($cuentas);
		$cuentasOrdenadas = $ordenadas[0];
		$sumaTotalGlobal  = $ordenadas[1];

		
		echo("<pre>");
		print_r ($cuentasOrdenadas);
		echo("</pre>");
		die();
		
		$draw    = intval($this->input->get("draw"));
		$start   = intval($this->input->get("start"));
		$length  = intval($this->input->get("length"));	
		$data    = array();
		$num     = 1;


		foreach ($cuentasOrdenadas as $fila)
		{  
			$indentacion 			= str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $fila['indentacion']);
			$indentacion_invertida 	= str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $fila['indentacion_invertida']);
			$descripcion 			= $fila['descripcion'];
			$codigo      			= $fila['codigo'];
			$importe_total      	= $fila['saldo_cuenta'] ? number_format($fila['saldo_cuenta'], 2, '.', ',') : '0.00';

			if (($fila['es_padre']) && ($fila['indentacion']== 0)) {
				$descripcion   = "<strong><u>{$descripcion}</u></strong>";
				$codigo 	   =  "<span class='badge badge-primary'><strong><u>{$codigo}</u></strong></span>";
				$importe_total = "<strong><u>{$importe_total}</u></strong>";
			}
			
			else
			{
				$codigo 	   =  "<span class='badge badge-secondary'><strong><u>{$codigo}</u></strong></span>";
			}
								
			$data[] = array(

				// $num++,
				// $indentacion."<span class='badge badge-secondary'>".$codigo."</span>",
				// "<span class='badge badge-secondary'>".$codigo."</span>",
				$codigo,
				$indentacion.$descripcion,
				$indentacion_invertida.$importe_total
			);
		}

		// die();
		$output = array(
			"draw" => $draw,
			"recordsTotal" => count($ordenadas),
			"recordsFiltered" => count($ordenadas),
			"data" => $data
		);
		echo json_encode($output);
		exit();
    }
	// function ReporteBalanceGeneralPDF($id_entidad,$cuentasBuscadas,$fecha_inicio,$fecha_fin)
	// {			
	// 	// $id_entidad      = $this->input->post('id_entidad');		
	// 	/****************************/
	// 	/*INICIO DEL REPORTE*/
	// 	/****************************/
	// 	// echo ($id_entidad);
	// 	// die();
		
	// 	$this->load->library('fpdf/pdf2');
    //     $pdf = new Pdf2();
    //     $pdf->AliasNbPages();
    //     $pdf->SetAutoPageBreak(true, 30);
    //     $pdf->SetMargins(20,15,10);		
	// 	$pdf->SetTitle(utf8_decode("BALANCE GENERAL"));
	// 	$pdf->entidad=descripcion_nombre_entidad($id_entidad);
	// 	$pdf->sigla="xxx";
	// 	$pdf->tituloCabecera = 'BALANCE GENERAL';
	// 	$pdf->subtituloCabecera1 = "Entre el ".formato_fecha_slash($fecha_inicio). " y ".formato_fecha_slash($fecha_fin);  
	// 	$pdf->subtituloCabecera2 = "Expresado en Bolivianos";  
    //     $w = array(15,115,40,50);
    //     $pdf->setWidthsG($w);
    //     $pdf->SetAligns(array('C','L','C','C'));
	// 	$pdf->AddPage('P','Letter');
	// 	$pdf->opcion_cabecera=5;
	// 	$pdf->Header();
	// 	$pdf->SetFillColor(255,255,255);
    //     $pdf->SetTextColor(0);
    //     $pdf->SetFont('Arial','',6);
	// 	$pdf->Ln(1);
	// 	/*CUERPO DEL REPORTE*/
	// 	$pdf->SetFillColor(255,255,255);
    //     $pdf->SetTextColor(0);
    //     $pdf->SetFont('Arial','',6);
    //     $num = 0;
    //     $total=0;

	// 	// 1. Reemplazar guiones por comas
	// 	$cadena = str_replace('-', ',', $cuentasBuscadas);
	// 	// 2. Eliminar la última coma si existe
	// 	$cadena = rtrim($cadena, ',');

	// 	$cuentas   = $this->BalanceGeneral_model->getGeneralBalanceGeneral();
	// 	$cuentas = json_decode(json_encode($cuentas), true);
	// 	$ordenadas = $this->ordenarJerarquicamente($cuentas);
	// 	$cuentasOrdenadas = $ordenadas[0];
	// 	$sumaTotalGlobal  = $ordenadas[1];

	// 	// echo("<pre>");
	// 	// print_r ($cuentasOrdenadas);
	// 	// echo("</pre>");
	// 	// die();

	// 	$pdf->SetFillColor(255,255,255);
	// 	$pdf->SetFont('Arial', '', 8);
	// 	$ini_x=$pdf->GetX();
	// 	// $ini_y=$pdf->GetY();
		
	// 	$pdf->setX(12); 
	// 	$pdf->SetWidths([25, 120, 15, 15, 15, 15]);
	// 	$pdf->SetAligns(['L','L','R','R','R','R']);


	// 	foreach ($cuentasOrdenadas as $fila)
	// 	{  
	// 		$indentacion 			= str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $fila['indentacion']);
	// 		$indentacion_invertida 	= str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $fila['indentacion_invertida']);
	// 		$nivel 					= $fila['nivel'];
	// 		$indentacion_invertida2 = $fila['indentacion_invertida'];
	// 		$descripcion 			= $fila['descripcion'];
	// 		$codigo      			= $fila['codigo'];
	// 		$importe_total      	= $fila['importe_total'] ? number_format($fila['importe_total'], 2, '.', ',') : '0.00';
			
	// 		$fila = array(

	// 			$codigo,
	// 			$descripcion,
	// 			$importe_total
	// 		);

	// 		$pdf->setX(5); 
	// 		$pdf->Row_Reportes_BG($fila,true, '', 4,$indentacion_invertida2,$nivel);								
	// 		$pdf->opcion_pie='FOOTER_VACIO';
	// 	}

	// 	$pdf->Ln();

	// 	$pdf->Footer();
	// 	$pdf->Output('I',utf8_decode('ReporteBalanceGeneral.pdf')); 
	// }
	// function ReporteBalanceGeneralPDF($id_entidad,$cuentasBuscadas,$fecha_inicio,$fecha_fin)
	// {			
	// 	// $id_entidad      = $this->input->post('id_entidad');		
	// 	/****************************/
	// 	/*INICIO DEL REPORTE*/
	// 	/****************************/
	// 	// echo ($id_entidad);
	// 	// die();
		
	// 	$this->load->library('fpdf/pdf2');
    //     $pdf = new Pdf2();
    //     $pdf->AliasNbPages();
    //     $pdf->SetAutoPageBreak(true, 30);
    //     $pdf->SetMargins(20,15,10);		
	// 	$pdf->SetTitle(utf8_decode("BALANCE GENERAL"));
	// 	$pdf->entidad=descripcion_nombre_entidad($id_entidad);
	// 	$pdf->sigla="xxx";
	// 	$pdf->tituloCabecera = 'BALANCE GENERAL';
	// 	$pdf->subtituloCabecera1 = "Entre el ".formato_fecha_slash($fecha_inicio). " y ".formato_fecha_slash($fecha_fin);  
	// 	$pdf->subtituloCabecera2 = "Expresado en Bolivianos";  
    //     $w = array(15,115,40,50);
    //     $pdf->setWidthsG($w);
    //     $pdf->SetAligns(array('C','L','C','C'));
	// 	$pdf->AddPage('P','Letter');
	// 	$pdf->opcion_cabecera=5;
	// 	$pdf->Header();
	// 	$pdf->SetFillColor(255,255,255);
    //     $pdf->SetTextColor(0);
    //     $pdf->SetFont('Arial','',6);
	// 	$pdf->Ln(1);
	// 	/*CUERPO DEL REPORTE*/
	// 	$pdf->SetFillColor(255,255,255);
    //     $pdf->SetTextColor(0);
    //     $pdf->SetFont('Arial','',6);
    //     $num = 0;
    //     $total=0;

	// 	// 1. Reemplazar guiones por comas
	// 	$cadena = str_replace('-', ',', $cuentasBuscadas);
	// 	// 2. Eliminar la última coma si existe
	// 	$cadena = rtrim($cadena, ',');

	// 	$cuentas   = $this->BalanceGeneral_model->getGeneralBalanceGeneral($id_entidad,$fecha_inicio,$fecha_fin);
	// 	$cuentas = json_decode(json_encode($cuentas), true);
	// 	$ordenadas = $this->ordenarJerarquicamente($cuentas);
	// 	$cuentasOrdenadas = $ordenadas[0];
	// 	$sumaTotalGlobal  = $ordenadas[1];

	// 	// echo("<pre>");
	// 	// print_r ($cuentasOrdenadas);
	// 	// echo("</pre>");
	// 	// die();

	// 	$pdf->SetFillColor(255,255,255);
	// 	$pdf->SetFont('Arial', '', 8);
	// 	$ini_x=$pdf->GetX();
	// 	// $ini_y=$pdf->GetY();
		
	// 	$pdf->setX(12); 
	// 	$pdf->SetWidths([110, 15]);
	// 	$pdf->SetAligns(['L','R']);


	// 	foreach ($cuentasOrdenadas as $fila)
	// 	{  
	// 		$indentacion 			= str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $fila['indentacion']);
	// 		$indentacion_invertida 	= str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $fila['indentacion_invertida']);
	// 		$nivel 					= $fila['nivel'];
	// 		$indentacion_invertida2 = $fila['indentacion_invertida'];
	// 		$descripcion 			= $fila['descripcion'];
	// 		$codigo      			= $fila['codigo'];
	// 		$importe_total      	= $fila['saldo_cuenta'] ? number_format($fila['saldo_cuenta'], 2, '.', ',') : '0.00';
			
	// 		$fila = array(

	// 			// $codigo,
	// 			$descripcion,
	// 			$importe_total
	// 		);

	// 		// $pdf->setX(5); 
	// 		$pdf->Row_Reportes_BG($fila,true, '', 4,$indentacion_invertida2,$nivel);								
	// 		$pdf->opcion_pie='FOOTER_VACIO';
	// 	}

	// 	$pdf->Ln();

	// 	$pdf->Footer();
	// 	$pdf->Output('I',utf8_decode('ReporteBalanceGeneral.pdf')); 
	// }

	// private function ordenarJerarquicamenteCuenta(array $cuentas, int $padreId = 0, int $indentacion = 0)
	// {
	// 	$ordenadas     = [];
	// 	$totalImporte  = 0;

	// 	foreach ($cuentas as &$cuenta) {
	// 		if ($cuenta['padre'] == $padreId) {

	// 			// --- ¿Tiene hijos? -------------------------------------------------
	// 			$tieneHijos = false;
	// 			foreach ($cuentas as $posibleHijo) {
	// 				if (
	// 					$posibleHijo['padre'] == $cuenta['id'] ||
	// 					$posibleHijo['padre'] == $cuenta['ruta']   // para cuentas mayores
	// 				) {
	// 					$tieneHijos = true;
	// 					break;
	// 				}
	// 			}

	// 			// --- Procesar hijos recursivamente --------------------------------
	// 			[$hijosOrdenados, $sumaHijos] =
	// 				$this->ordenarJerarquicamenteCuenta($cuentas, $cuenta['id'], $indentacion + 1);

	// 			// --- Sumar importe propio + importe hijos -------------------------
	// 			$importePropio              = isset($cuenta['saldo_cuenta'])
	// 											? (float) $cuenta['saldo_cuenta']
	// 											: 0;
	// 			$cuenta['importe_total']    = $importePropio + $sumaHijos;

	// 			// --- Campos extra --------------------------------------------------
	// 			$cuenta['indentacion']      = $indentacion;
	// 			$cuenta['es_padre']         = $tieneHijos;

	// 			// --- Añadir al resultado ------------------------------------------
	// 			$ordenadas[] = $cuenta;
	// 			$ordenadas   = array_merge($ordenadas, $hijosOrdenados);

	// 			$totalImporte += $cuenta['importe_total'];
	// 		}
	// 	}
	// 	unset($cuenta);   // rompe la referencia del foreach

	// 	/*───────────────────────────────────────────────────────────────
	// 	Invertir la indentación EN ESTE BLOQUE devuelto, sea raíz
	// 	o subárbol: buscamos la profundidad máxima dentro de $ordenadas
	// 	───────────────────────────────────────────────────────────────*/

	// 	if ($ordenadas) {
	// 		$profMax = max(array_column($ordenadas, 'indentacion'));

	// 		foreach ($ordenadas as &$c) {
	// 			$c['indentacion_invertida'] = $profMax - $c['indentacion'];
	// 		}
	// 		unset($c);
	// 	}

	// 	return [$ordenadas, $totalImporte];
	// }
	function ReporteBalanceGeneralPDF($id_entidad,$cuentasBuscadas,$fecha_inicio,$fecha_fin)
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
		$pdf->tituloCabecera = 'BALANCE GENERAL';
		$pdf->subtituloCabecera1 = "Entre el ".formato_fecha_slash($fecha_inicio). " y ".formato_fecha_slash($fecha_fin);  
		$pdf->subtituloCabecera2 = "Expresado en Bolivianos";  
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

		// 1. Reemplazar guiones por comas
		$cadena = str_replace('-', ',', $cuentasBuscadas);
		// 2. Eliminar la última coma si existe
		$cadena = rtrim($cadena, ',');

		/*CUENTAS PARA EL REPORTE*/

		$codigo_activo=1;
		$codigo_pasivo=2;
		$codigo_patrimonio=3;

		/*CUENTAS ACTIVOS*/
		$cuentas_activo   	    = $this->BalanceGeneral_model->getGeneralBalanceGeneralPorMayor($id_entidad,$fecha_inicio,$fecha_fin,$codigo_activo);
		// echo("<pre>");
		// print_r($cuentas_activo);
		// echo("</pre>");
		// echo("**************cuentas_ordenadas*********");
		$total_activo			= count($cuentas_activo);
		$cuentas_activo1 		= json_decode(json_encode($cuentas_activo), true);
		
		$ordenadas_activo 		= $this->ordenarJerarquicamente($cuentas_activo1);
		$cuentasOrdenadasActivo = $ordenadas_activo[0];
		$sumaTotalGlobalActivo  = $ordenadas_activo[1];

		// echo("<pre>");
		// print_r($ordenadas_activo);
		// echo("</pre>");
		// die();

		/*CUENTAS PASIVO*/
		$cuentas_pasivo   	    = $this->BalanceGeneral_model->getGeneralBalanceGeneralPorMayor($id_entidad,$fecha_inicio,$fecha_fin,$codigo_pasivo);
		$total_pasivo  		    = count($cuentas_pasivo);
		$cuentas_pasivo 		= json_decode(json_encode($cuentas_pasivo), true);
		$ordenadas_pasivo 		= $this->ordenarJerarquicamente($cuentas_pasivo);
		$cuentasOrdenadasPasivo = $ordenadas_pasivo[0];
		$sumaTotalGlobalPasivo  = $ordenadas_pasivo[1];

		/*CUENTAS PATRIMONIO*/
		$cuentas_patrimonio   		= $this->BalanceGeneral_model->getGeneralBalanceGeneralPorMayor($id_entidad,$fecha_inicio,$fecha_fin,$codigo_patrimonio);
		$total_patrimonio			= count($cuentas_patrimonio);
		$cuentas_patrimonio 		= json_decode(json_encode($cuentas_patrimonio), true);
		$ordenadas_patrimonio 		= $this->ordenarJerarquicamente($cuentas_patrimonio);
		$cuentasOrdenadasPatrimonio = $ordenadas_patrimonio[0];
		$sumaTotalGlobalPatrimonio  = $ordenadas_patrimonio[1];
		
		$total_pasivopatrimonio 	= $total_pasivo+$total_patrimonio; 
		$max_filas				    = max($total_activo, $total_pasivopatrimonio); 

		// echo("<pre>");
		// print_r ($cuentasOrdenadas);
		// echo("</pre>");
		// die();

		$pdf->SetFillColor(255,255,255);
		$pdf->SetFont('Arial', '', 8);
		$ini_x=$pdf->GetX();
		// $ini_y=$pdf->GetY();
		
		$pdf->setX(12); 
		$pdf->SetWidths([105, 105]);
		$pdf->SetAligns(['L','L']);
		$cabecera1="1 ACTIVO";
		$cabecera2="2 PASIVO";
		$fila= array(
						$cabecera1,
						$cabecera2		
					);

		// $pdf->Row_Reportes_BG($fila,true, '', 3,$indentacion_invertida2,$nivel);								
		$pdf->Row_Reportes_BG($fila,true, '', 3,0,1);								
		$pdf->opcion_pie='FOOTER_VACIO';
		
		$pdf->SetWidths([25,60,15,15,15]);
		$pdf->SetAligns(['L','L','R','R','R']);
		$valor_cero =0;
		$valor_cero_p =0;
		
		for ($i = 0; $i < $max_filas; $i++) {
    		// $pdf->ln();
			// ACTIVO
			if (isset($cuentasOrdenadasActivo[$i])) {

				$cuenta					= $cuentasOrdenadasActivo[$i];

				// print_r($cuentasOrdenadasActivo[$i]);
				// echo("\n");
				// echo("<br>");
				// die();
				// print_r ("=====>".$cuenta['nivel']);

				$nivel 					= $cuenta['nivel'];

				// echo($nivel);
				// die();
				$indentacion_invertida_activo   = $cuenta['indentacion_invertida'];
				$codigo_activo      			= $cuenta['codigo'];
				$descripcion_activo 			= $cuenta['descripcion'];
				$saldo_cuenta_activo      	    = $cuenta['saldo_cuenta'] ? number_format($cuenta['saldo_cuenta'], 2, '.', ',') : '0.00';
				$total_cuenta_activo     	    = $cuenta['importe_total'] ? number_format($cuenta['importe_total'], 2, '.', ',') : '0.00';
				// $pdf->Cell(15, 6, $nivel, 1, 0, 'R');
				// $pdf->Cell(15, 6, $saldo_cuenta, 1, 0, 'R');

				if($nivel==1)
				{
					$pdf->SetFont('Arial', 'BU', 7);
					// $fila=array(
					// 			$codigo,	
					// 			$descripcion,
					// 			$valor_cero,
					// 			$saldo_cuenta,
					// 			$total_cuenta
					// 			);

					$pdf->setX(12);				
					// $pdf->Row_SinLinea($fila,true, '', 5);




					// $pdf->Cell(25, 6, utf8_decode($codigo), 1, 0);
					// $pdf->Cell(60, 6, utf8_decode($descripcion), 1, 0);
					// $pdf->Cell(15, 6, number_format($valor_cero, 2, ',', '.'), 1, 0, 'R');
					// $pdf->Cell(15, 6, $saldo_cuenta, 1, 0, 'R');
					// $pdf->Cell(15, 6, $total_cuenta, 1, 0, 'R');


					// $pdf->Cell(15, 6, number_format($valor_cero, 2, ',', '.'), 1, 0, 'R');
					// $pdf->ln();
					
				}
				else
				{
					if($nivel==2)
					{

						$fila=array(
								$codigo,	
								$descripcion,
								$valor_cero,
								$total_cuenta,
								$valor_cero
								);

						$pdf->SetFont('Arial', 'BU', 7);
						$pdf->setX(12);				
					    // $pdf->Row_SinLinea($fila,true, '', 5);			
						
						
						// $pdf->Cell(25, 6, utf8_decode($codigo), 1, 0);
						// $pdf->Cell(60, 6, utf8_decode($descripcion), 1, 0);
						// $pdf->Cell(15, 6, number_format($valor_cero, 2, ',', '.'), 1, 0, 'R');
						// $pdf->Cell(15, 6, $total_cuenta, 1, 0, 'R');
						// $pdf->Cell(15, 6, number_format($valor_cero, 2, ',', '.'), 1, 0, 'R');
					
					}
					else
					{

						$fila=array(
								$codigo,	
								$descripcion,
								$saldo_cuenta,
								$valor_cero,
								$valor_cero
								);

						$pdf->SetFont('Arial', '', 7);
						$pdf->setX(12);				
					    // $pdf->Row_SinLinea($fila,true, '', 5);	

						// $pdf->Cell(25, 6, utf8_decode($codigo), 1, 0);
						// $pdf->Cell(60, 6, utf8_decode($descripcion), 1, 0);
						// $pdf->Cell(15, 6, $saldo_cuenta, 1, 0, 'R');
						// $pdf->Cell(15, 6, number_format($valor_cero, 2, ',', '.'), 1, 0, 'R');
						// $pdf->Cell(15, 6, number_format($valor_cero, 2, ',', '.'), 1, 0, 'R');

					}
					$pdf->SetFont('Arial', '', 7);
				}
				// $nivel 					= $fila['nivel'];
				// $descripcion 			= $fila['descripcion'];
				// $codigo      			= $fila['codigo'];
				// $importe_total      	= $fila['saldo_cuenta'] ? number_format($fila['saldo_cuenta'], 2, '.', ',') : '0.00';
			

				// [$codA, $nomA, $saldoA] = $datosActivo[$i];
				// $pdf->Cell(30, 6, $codA, 1, 0);
				// $pdf->Cell(70, 6, utf8_decode($nomA), 1, 0);
				// $pdf->Cell(30, 6, number_format($saldoA, 2, ',', '.'), 1, 0, 'R');
				// $totalActivo += $saldoA;
			} else {
				$pdf->Cell(15, 6, '', 1, 0);
				$pdf->Cell(60, 6, '', 1, 0);
				$pdf->Cell(15, 6, '', 1, 0);
				$pdf->Cell(15, 6, '', 1, 0);
				$pdf->Cell(15, 6, '', 1, 0);
	
			}
			// PASIVO
			if (isset($cuentasOrdenadasPasivo[$i])) {

				$cuenta					= $cuentasOrdenadasPasivo[$i];

				$nivel 					= $cuenta['nivel'];

				// echo($nivel);
				// die();
				$indentacion_invertida  = $cuenta['indentacion_invertida'];
				$codigo     			= $cuenta['codigo'];
				$descripcion 			= $cuenta['descripcion'];
				$saldo_cuenta     	    = $cuenta['saldo_cuenta'] ? number_format($cuenta['saldo_cuenta'], 2, '.', ',') : '0.00';
				$total_cuenta    	    = $cuenta['importe_total'] ? number_format($cuenta['importe_total'], 2, '.', ',') : '0.00';
				// $pdf->Cell(15, 6, $nivel, 1, 0, 'R');
				// $pdf->Cell(15, 6, $saldo_cuenta, 1, 0, 'R');

				if($nivel==1)
				{
					$pdf->SetFont('Arial', 'BU', 7);

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
								$total_cuenta
								);

					// $pdf->setX(143);				
					$pdf->setX(12);		
					$pdf->SetWidths([15,60,15,15,15,25,60,15,15,15]);
		            $pdf->SetAligns(['R','L','R','R','R','R','L','R','R','R']);		
					$pdf->Row_SinLinea($fila,true, '', 10);


					// $pdf->Cell(15, 6, utf8_decode($codigo), 1, 0);
					// $pdf->Cell(60, 6, utf8_decode($descripcion), 1, 0);
					// $pdf->Cell(15, 6, number_format($valor_cero, 2, ',', '.'), 1, 0, 'R');
					// $pdf->Cell(15, 6, $saldo_cuenta, 1, 0, 'R');
					// $pdf->Cell(15, 6, $total_cuenta, 1, 0, 'R');


					// $pdf->Cell(15, 6, number_format($valor_cero, 2, ',', '.'), 1, 0, 'R');
					$pdf->ln();
					
				}
				else
				{
					if($nivel==2)
					{

						// $fila=array(
						// 		$codigo,	
						// 		$descripcion,
						// 		$valor_cero,
						// 		$total_cuenta,
						// 		$valor_cero
						// 		);
						
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
								$total_cuenta
								);		
						
						$pdf->setX(12);		
						$pdf->SetWidths([15,60,15,15,15,25,60,15,15,15]);
						$pdf->SetAligns(['R','L','R','R','R','R','L','R','R','R']);		
						$pdf->Row_SinLinea($fila,true, '', 10);

						$pdf->SetFont('Arial', 'BU', 7);
						$pdf->setX(110);				
					    // $pdf->Row_SinLinea($fila,true, '', 5);			
						
						
						// $pdf->Cell(25, 6, utf8_decode($codigo), 1, 0);
						// $pdf->Cell(60, 6, utf8_decode($descripcion), 1, 0);
						// $pdf->Cell(15, 6, number_format($valor_cero, 2, ',', '.'), 1, 0, 'R');
						// $pdf->Cell(15, 6, $total_cuenta, 1, 0, 'R');
						// $pdf->Cell(15, 6, number_format($valor_cero, 2, ',', '.'), 1, 0, 'R');
					
					}
					else
					{

						$fila=array(
								$codigo,	
								$descripcion,
								$saldo_cuenta,
								$valor_cero,
								$valor_cero
								);

						$pdf->SetFont('Arial', '', 7);
						$pdf->setX(110);				
					    // $pdf->Row_SinLinea($fila,true, '', 5);	

						// $pdf->Cell(25, 6, utf8_decode($codigo), 1, 0);
						// $pdf->Cell(60, 6, utf8_decode($descripcion), 1, 0);
						// $pdf->Cell(15, 6, $saldo_cuenta, 1, 0, 'R');
						// $pdf->Cell(15, 6, number_format($valor_cero, 2, ',', '.'), 1, 0, 'R');
						// $pdf->Cell(15, 6, number_format($valor_cero, 2, ',', '.'), 1, 0, 'R');

					}
					$pdf->SetFont('Arial', '', 7);
				}
				// $nivel 					= $fila['nivel'];
				// $descripcion 			= $fila['descripcion'];
				// $codigo      			= $fila['codigo'];
				// $importe_total      	= $fila['saldo_cuenta'] ? number_format($fila['saldo_cuenta'], 2, '.', ',') : '0.00';
			

				// [$codA, $nomA, $saldoA] = $datosActivo[$i];
				// $pdf->Cell(30, 6, $codA, 1, 0);
				// $pdf->Cell(70, 6, utf8_decode($nomA), 1, 0);
				// $pdf->Cell(30, 6, number_format($saldoA, 2, ',', '.'), 1, 0, 'R');
				// $totalActivo += $saldoA;
			} else {
				$pdf->setX(110);
				$fila=array(
								"",	
								"",	
								"",	
								"",	
								""
								);
				$pdf->Row_SinLinea($fila,true, '', 5);	
	
			}


		}
    
		// foreach ($cuentasOrdenadas as $fila)
		// {  
		// 	// $indentacion 			= str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $fila['indentacion']);
		// 	// $indentacion_invertida 	= str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $fila['indentacion_invertida']);
			
		// 	$pdf->SetWidths([15,60,10,10,10]);
		// 	$pdf->SetAligns(['L','L','R','R','R']);
		// 	$nivel1=0;
		// 	$nivel2=0;
		// 	$nivel3=0;
		// 	$nivel 					= $fila['nivel'];
		// 	$indentacion_invertida2 = $fila['indentacion_invertida'];
		// 	$descripcion 			= $fila['descripcion'];
		// 	$codigo      			= $fila['codigo'];
		// 	$importe_total      	= $fila['saldo_cuenta'] ? number_format($fila['saldo_cuenta'], 2, '.', ',') : '0.00';
			
		// 	if($nivel == 1)
		// 	{
		// 		$nivel1=$importe_total;
		// 	}

		// 	$fila = array(
		// 		$codigo,
		// 		$descripcion,
		// 		$nivel1,
		// 		$nivel2,
		// 		$nivel3
		// 	);

		// 	// $pdf->setX(5); 
		// 	$pdf->Row_SinLinea($fila,true,'',5);

		// 	// $pdf->Row_Reportes_BG($fila,true, '', 3,$indentacion_invertida2,$nivel);								
		// 	$pdf->opcion_pie='FOOTER_VACIO';
		// }

		$pdf->Ln();

		$pdf->Footer();
		$pdf->Output('I',utf8_decode('ReporteBalanceGeneral.pdf')); 
	}
}