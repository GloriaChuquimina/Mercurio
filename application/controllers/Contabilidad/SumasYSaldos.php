<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SumasYSaldos extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->_is_logued_in();
        $this->load->model('SumasSaldos_model');
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

		$titulo = "Balance de Sumas y Saldos";		
		$dato['titulo'] = $titulo;

		$this->load->view('inicio/cabecera',$dato);
		$this->load->view('inicio/menu',$dato);
		$this->load->view('contabilidad/sumasysaldos',$dato);
		$this->load->view('inicio/pie');
	}
	public function cargarDatosSumasySaldos()
	{
		$id_usuario  = $this->session->userdata('id_usuario');
			
		$draw    = intval($this->input->get("draw"));
		$start   = intval($this->input->get("start"));
		$length  = intval($this->input->get("length"));	
		$data    = array();
		$num     = 1;

		$id_entidad             = $this->input->post('id_entidad');
		$cuentasSeleccionadas   = $this->input->post('cuentasSeleccionadas');
		$fecha_inicio  		    = $this->input->post('fecha_desde');
		$fecha_fin              = $this->input->post('fecha_hasta');
		$cuentas_con_movimiento = $this->input->post('cuentas_con_movimiento');
		$fecha_al				= $this->input->post('fecha_al');
		$idSeleccionado         = $this->input->post('idSeleccionado');
		$moneda				    = $this->input->post('moneda');
		$nivel					= $this->input->post('nivel');

		// 1. Reemplazar guiones por comas
		$cadena = str_replace('-', ',', $cuentasSeleccionadas);
		// 2. Eliminar la última coma si existe
		$cadena = rtrim($cadena, ',');
		// 3. Convertir en array usando coma como separador
		$array_cuentas = explode(',', $cadena);

		// 4. Contar los elementos
		$numero_cuentas = count($array_cuentas);

		// echo("Numero de Cuentas".$numero_cuentas);
		// die();

		if($cuentas_con_movimiento === 'true' && $cuentasSeleccionadas == ""  )
		{

			$sumasysaldos  	 = $this->SumasSaldos_model->getSumasSaldosCuentasConMovimiento($id_entidad,$fecha_inicio,$fecha_fin);

		}
		else
		{
			if($cuentas_con_movimiento === 'true' && $numero_cuentas > 0 )
			{
				$sumasysaldos  	 = $this->SumasSaldos_model->getSumasSaldosCuentasConMovimientoByIds($id_entidad,$cadena,$fecha_inicio,$fecha_fin);
			}
			else
			{
				$sumasysaldos  	 = $this->SumasSaldos_model->getGeneralSumasSaldosCuentasByIds($id_entidad,$cadena,$fecha_inicio,$fecha_fin);
			}
		}

		$totalDebe =0;
		$totalHaber =0;
		$totalDeudor=0;
		$totalAcreedor=0;
		foreach ($sumasysaldos as $fila)
		{   
			$deudor =0;
			$acreedor =0;
			$codigo= $fila->codigo;
			$descripcion = $fila->descripcion;

			if($moneda === 'BOB'){
				$debe        = $fila->debe; 
				$haber       = $fila->haber;
			}
			elseif ($moneda === 'USD') {
				$debe        = $fila->debeusd; 
				$haber       = $fila->haberusd;
			} 
			
			
			// $debe = $fila->debe;
			// $haber = $fila->haber;


			if($haber == 0)
			{
				$deudor = $debe;
				$acreedor = 0;
			}
			else{
				if($debe == 0)
				{
					$acreedor = $haber;
					$deudor = 0;
				}
				else
				{
					if($debe <> 0 && $haber <> 0)
					{
						$deudor = $debe-$haber;
						$acreedor = 0;
					}
				
				}
			}
			// number_format($totalimporteDebe,2,'.',','),
			$data[] = array(
				$codigo,
				$descripcion,
				"<div style='text-align: right; color: #28a745; font-weight: bold;'>".number_format($debe,2,'.',',')."</div>",
				"<div style='text-align: right; color: #dc3545; font-weight: bold;'>".number_format($haber,2,'.',',')."</div>",
				"<div style='text-align: right; color: #28a745; font-weight: bold;'>".number_format($deudor,2,'.',',')."</div>",
				"<div style='text-align: right; color: #dc3545; font-weight: bold;'>".number_format($acreedor,2,'.',',')."</div>"
			    );
			$totalDebe += $debe;
			$totalHaber += $haber;		
			$totalDeudor += $deudor;
			$totalAcreedor += $acreedor;
		}
		$output = array(
			             "draw" => $draw,
			    "recordsTotal"  => count($sumasysaldos),
			 "recordsFiltered"  => count($sumasysaldos),
			"totalimporteDebe"  => number_format($totalDebe,2,'.',','),
		   "totalimporteHaber"  => number_format($totalHaber,2,'.',','),
		  "totalimporteDeudor"  => number_format($totalDeudor,2,'.',','),
		"totalimporteAcreedor"  => number_format($totalAcreedor,2,'.',','), 
					    "data"  => $data
		);
		echo json_encode($output);
		exit();
	}
	function ReporteSumasySaldosPDF($id_entidad,$cuentas,$fecha_inicio,$fecha_fin,$cuentas_con_movimiento,$idSeleccionado,$fecha_al,$nivel,$moneda)
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
		$pdf->SetTitle(utf8_decode("Reporte Libro Mayor"));
		$pdf->entidad=descripcion_nombre_entidad($id_entidad);
		$pdf->sigla=sigla_entidad($id_entidad);
		$pdf->tituloCabecera = 'BALANCE SUMAS Y SALDOS';
		$pdf->subtituloCabecera1 = "Entre el ".formato_fecha_slash($fecha_inicio). " y ".formato_fecha_slash($fecha_fin);  
		// $pdf->subtituloCabecera2 = "Expresado en Bolivianos"; 
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
		$pdf->AddPage('P','Letter');
		$pdf->opcion_cabecera=4;
		$pdf->Header();
		$pdf->SetFillColor(255,255,255);
        $pdf->SetTextColor(0);
        $pdf->SetFont('Arial','',6);
		$pdf->Ln(1);
		/*CUERPO DEL REPORTE*/
		$pdf->SetFillColor(255,255,255);
        $pdf->SetTextColor(0);
        $pdf->SetFont('Arial','',6);
		$pdf->opcion_pie='FOOTER_VACIO';
        $num = 0;
        $total=0;

		// 1. Reemplazar guiones por comas
		$cadena = str_replace('-', ',', $cuentas);
		// 2. Eliminar la última coma si existe
		$cadena = rtrim($cadena, ',');
		// 3. Convertir en array usando coma como separador
		$array_cuentas = explode(',', $cadena);

		// 4. Contar los elementos
		$numero_cuentas = count($array_cuentas);

		// echo("Cuentas".$cuentas."<==>");
		// echo("Numero de Cuentas".$numero_cuentas);
		// die();


		$whereFecha = "";
		if($idSeleccionado == 'radioAl'){
			$whereFecha = " AND fecha_comprobante <='$fecha_al' ";
		}
		elseif($idSeleccionado == 'radioEntre'){
			$whereFecha = " AND fecha_comprobante BETWEEN '$fecha_inicio' AND '$fecha_fin' ";
		}

		if($cuentas_con_movimiento === 'true' && $cuentas == 0 )
		{
			// echo("Cuentas con movimiento");
			$sumasysaldos  	 = $this->SumasSaldos_model->getSumasSaldosCuentasConMovimiento($id_entidad,$fecha_inicio,$fecha_fin,$whereFecha);
			// echo("<pre>");
			// print_r($sumasysaldos);
			// echo("</pre>");
			// die();
		}
		else
		{
			if($cuentas_con_movimiento === 'true' && $numero_cuentas > 0 )
			{
				$sumasysaldos  	 = $this->SumasSaldos_model->getSumasSaldosCuentasConMovimientoByIds($id_entidad,$cadena,$fecha_inicio,$fecha_fin,$whereFecha);
			}
			else
			{
				$sumasysaldos  	 = $this->SumasSaldos_model->getGeneralSumasSaldosCuentasByIds($id_entidad,$cadena,$fecha_inicio,$fecha_fin,$whereFecha);
			}
		}

		// $sumasysaldos  	 = $this->SumasSaldos_model->getGeneralSumasSaldosCuentasByIds($id_entidad,$cadena,$fecha_inicio,$fecha_fin);

		$totalDebe =0;
		$totalHaber =0;
		$totalDeudor=0;
		$totalAcreedor=0;

		$pdf->SetFillColor(255,255,255);
		$pdf->SetFont('Arial', '', 7);
		$ini_x=$pdf->GetX();
		// $ini_y=$pdf->GetY();		
		// $pdf->setX(12); 
		$pdf->SetWidths([25, 100, 18, 18, 18, 18]);
		$pdf->SetAligns(['L','L','R','R','R','R']);
		$saldo=0;
        foreach ($sumasysaldos as $fila)
		{   		
				
			$deudor      = 0;
			$acreedor    = 0;
			$codigo      = $fila->codigo;
			$descripcion = $fila->descripcion;	

			if($moneda === 'BOB'){
				$debe        = $fila->debe; 
				$haber       = $fila->haber;
			}
			elseif ($moneda === 'USD') {
				$debe       = $fila->debeusd; 
				$haber       = $fila->haberusd;
			} 

			// $debe        = $fila->debe;
			// $haber       = $fila->haber;

			$saldo = $debe - $haber;
			
			if($saldo>0)
			{
				$deudor = $saldo;
				$acreedor = 0;
			}
			else
			{
				if($saldo < 0)
				{
					$deudor = 0;
					$acreedor = $saldo * -1 ;
				}
			}

			$fila = array(
				$codigo,
				utf8_decode($descripcion), 
				number_format($debe,2,'.',','),
				number_format($haber,2,'.',','),
				number_format($deudor,2,'.',','),
				number_format($acreedor,2,'.',',')
			    );
			$totalDebe += $debe;
			$totalHaber += $haber;		
			$totalDeudor += $deudor;
			$totalAcreedor += $acreedor;
			$pdf->Row_Reportes_SS($fila,true, '', 4);								
			
	    }      
		$pdf->SetFillColor(255,255,255);
		/*LINEA HORIZONTAL*/
		$x=12;
		$y=$pdf->GetY();
		$pdf->Line($x, $y, $x + 197, $y);
		$pdf->Cell(145,2,"",0,0,'R',1);
		$pdf->Cell(60,2,"",0,0,'R',1);
		$pdf->SetFont('Arial','B',7);
		$TOTALES="TOTALES";
		$y=$pdf->GetY();
		$pdf->SetXY(12,$y);
		$pdf->setX(12);     
		$pdf->Cell(125,8,utf8_decode($TOTALES),0,0,'C',1);
		$pdf->Cell(18,8,utf8_decode(number_format($totalDebe,2,'.',',')),0,0,'R',1);
		$pdf->Cell(18,8,utf8_decode(number_format($totalHaber,2,'.',',')),0,0,'R',1);
		$pdf->Cell(18,8,utf8_decode(number_format($totalDeudor,2,'.',',')),0,0,'R',1);
		$pdf->Cell(18,8,utf8_decode(number_format($totalAcreedor,2,'.',',')),0,0,'R',1);  
	    /*DIBUJANDO LINEAS*/
		$ini_y=46;
		$y_fin=$pdf->GetY();
		// $pdf->SetXY(5,$ini_y);
	    $pdf->Line(12, $ini_y, 12, $y_fin+7);
		// $pdf->Setxy(30, $ini_y);
		$pdf->Line(37, $ini_y, 37, $y_fin);
		$pdf->Line(137, $ini_y, 137, $y_fin+7);
		$pdf->Line(173, $ini_y, 173, $y_fin+7);
		$pdf->Line(209, $ini_y, 209, $y_fin+7);

		$x_fin=12;
		$pdf->Line($x_fin, $y_fin+7, $x_fin + 197, $y_fin+7);

		$pdf->Footer();
		$pdf->Output('I',utf8_decode('ReporteComprobante.pdf')); 
	}
	private function ordenarJerarquicamente($cuentas, $padreId = 0, $indentacion = 0)
	{
		$ordenadas = [];

		foreach ($cuentas as $cuenta) {
			if ($cuenta['padre'] == $padreId) {
				// Buscar si esta cuenta tiene hijos
				$tieneHijos = false;
				foreach ($cuentas as $posibleHijo) {
					if ($posibleHijo['padre'] == $cuenta['id']) {
						$tieneHijos = true;
						break;
					}
					elseif($posibleHijo['padre'] == $cuenta['ruta'])/*ojo*/
					{
						$tieneHijos = true;
						break;
					}
				}
				// Añadir campo extra
				$cuenta['indentacion'] = $indentacion;
				$cuenta['es_padre'] = $tieneHijos;

				// Agregar la cuenta ordenada
				$ordenadas[] = $cuenta;

				// Agregar recursivamente los hijos
				$ordenadas = array_merge($ordenadas, $this->ordenarJerarquicamente($cuentas, $cuenta['id'], $indentacion + 1));
			}
		}

		return $ordenadas;
	}
}
