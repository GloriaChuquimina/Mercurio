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
		$dato['nombre_sistema']  = "SISTEMA CONTABLE<BR>MERCURIO";		
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
	// 			$cuenta['importe_total'] = $cuenta['importe_moneda_nacional'] + $sumaHijos;

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
				$importePropio              = isset($cuenta['importe_moneda_nacional'])
												? (float) $cuenta['importe_moneda_nacional']
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
		$cuentas   = $this->BalanceGeneral_model->getGeneralBalanceGeneral();
		// echo("<br>");
		// print_r($cuentas);
		// echo("</pre>");
		$cuentas = json_decode(json_encode($cuentas), true);
		// $ordenadas = $this->ordenarJerarquicamente($cuentas);
		// list($cuentasOrdenadas, $importeTotalGeneral) = $this->ordenarJerarquicamente($cuentas);
		$ordenadas = $this->ordenarJerarquicamente($cuentas);
		$cuentasOrdenadas = $ordenadas[0];
		$sumaTotalGlobal  = $ordenadas[1];

		
		// echo("<pre>");
		// print_r ($cuentasOrdenadas);
		// echo("</pre>");
		// die();
		
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
			// $importe_total      	= $fila['importe_total'];
			$importe_total      	= $fila['importe_total'] ? number_format($fila['importe_total'], 2, '.', ',') : '0.00';

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
	function ReporteBalanceGeneralPDF($id_entidad,$cuentasBuscadas,$fecha_inicio,$fecha_fin)
	{			
		// $id_entidad      = $this->input->post('id_entidad');		
		/****************************/
		/*INICIO DEL REPORTE*/
		/****************************/
		// echo ($id_entidad);
		// die();
		
		$this->load->library('fpdf/pdf2');
        $pdf = new Pdf2();
        $pdf->AliasNbPages();
        $pdf->SetAutoPageBreak(true, 30);
        $pdf->SetMargins(20,15,10);		
		$pdf->SetTitle(utf8_decode("BALANCE GENERAL"));
		$pdf->entidad=descripcion_nombre_entidad($id_entidad);
		$pdf->sigla="xxx";
		$pdf->tituloCabecera = 'BALANCE GENERAL';
		$pdf->subtituloCabecera1 = "Entre el ".formato_fecha_slash($fecha_inicio). " y ".formato_fecha_slash($fecha_fin);  
		$pdf->subtituloCabecera2 = "Expresado en Bolivianos";  
        $w = array(15,115,40,50);
        $pdf->setWidthsG($w);
        $pdf->SetAligns(array('C','L','C','C'));
		$pdf->AddPage('P','Letter');
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

		// 1. Reemplazar guiones por comas
		$cadena = str_replace('-', ',', $cuentasBuscadas);
		// 2. Eliminar la última coma si existe
		$cadena = rtrim($cadena, ',');

		$cuentas   = $this->BalanceGeneral_model->getGeneralBalanceGeneral();
		$cuentas = json_decode(json_encode($cuentas), true);
		$ordenadas = $this->ordenarJerarquicamente($cuentas);
		$cuentasOrdenadas = $ordenadas[0];
		$sumaTotalGlobal  = $ordenadas[1];

		$pdf->SetFillColor(255,255,255);
		$pdf->SetFont('Arial', '', 8);
		$ini_x=$pdf->GetX();
		// $ini_y=$pdf->GetY();
		
		$pdf->setX(12); 
		$pdf->SetWidths([25, 120, 15, 15, 15, 15]);
		$pdf->SetAligns(['L','L','R','R','R','R']);


		foreach ($cuentasOrdenadas as $fila)
		{  
			$indentacion 			= str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $fila['indentacion']);
			$indentacion_invertida 	= str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $fila['indentacion_invertida']);
			$descripcion 			= $fila['descripcion'];
			$codigo      			= $fila['codigo'];
			$importe_total      	= $fila['importe_total'] ? number_format($fila['importe_total'], 2, '.', ',') : '0.00';
			// $importe_total			= number_format($importe_total,2,'.',',');
							
			$fila = array(

				$codigo,
				$descripcion,
				$importe_total
			);


			// $xBase = 10;
			// foreach ($lista as $c) {
			// 	$pdf->SetX($xBase + $c['indentacion_invertida'] * 5);
			// 	…
			// }

			
			$pdf->setX(5); 
			$pdf->Row_Reportes_LM($fila,true, '', 4);								
			$pdf->opcion_pie='FOOTER_VACIO';
		}

		$pdf->Ln();

		$pdf->Footer();
		$pdf->Output('I',utf8_decode('ReporteComprobante.pdf')); 
	}
}