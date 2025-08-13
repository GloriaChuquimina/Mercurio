<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EstadoDeResultados extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->_is_logued_in();
        $this->load->model('Comprobantes_model');
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

		$titulo = "Estado de Resultados";		
		$dato['titulo'] = $titulo;

		$this->load->view('inicio/cabecera',$dato);
		$this->load->view('inicio/menu',$dato);
		$this->load->view('contabilidad/estadoderesultados',$dato);
		$this->load->view('inicio/pie');
	}
	public function cargarDatosEstadoDeResultadosIngreso()
	{
		$id_usuario  = $this->session->userdata('id_usuario');
			
		$draw    = intval($this->input->get("draw"));
		$start   = intval($this->input->get("start"));
		$length  = intval($this->input->get("length"));	
		$data    = array();
		$num     = 1;

		$id_entidad            = $this->input->post('id_entidad');
		$fecha_inicio  		   = $this->input->post('fecha_inicio');
		$fecha_fin           = $this->input->post('fecha_fin');
		// $id_cuenta             = $this->input->post('id_cuenta');

		$id_cuenta_ingreso   	   = 40;
		$codigo_cuenta_ingreso     = 4;
		$id_cuenta_egreso   	   = 41;
		$codigo_cuenta_egreso     = 5;
		

		$estadoResultadoAcreedor = $this->EstadoDeResultado_model->getEstadoDeResultadosIngreso($id_entidad,$fecha_inicio,$fecha_fin,$id_cuenta_ingreso,$codigo_cuenta_ingreso);

		// $resultado    = $this->EstadoDeResultado_model->getMontoResultado($id_entidad,$fecha_desde,$fecha_hasta);
		$resultado    = $this->EstadoDeResultado_model->getMontoResultado($id_entidad,$fecha_inicio,$fecha_fin,$id_cuenta_ingreso,$codigo_cuenta_ingreso,$id_cuenta_egreso,$codigo_cuenta_egreso);
		if (!empty($resultado) && isset($resultado[0]->total_estado_resultado)) {
			$total_resultado = $resultado[0]->total_estado_resultado;
		} else {
			$total_resultado = 0; 
		}
		$totalSaldoAcreedor =0;

		foreach ($estadoResultadoAcreedor as $fila)
		{   
			$codigo 		 = $fila->codigo;
			$descripcion     	 = $fila->descripcion;
			$saldoAcreedor	     = $fila->saldo_acreedor;

			$data[] = array(
				"<span class='badge badge-secondary'>".$codigo."</span>",
				"<span style='text-align: left; '>".$descripcion."</span>",	
				"<div style='text-align: right; color: #28a745; font-weight: bold;'>
				".number_format($saldoAcreedor,2,'.',',').
				"</div>",				
			);
			$totalSaldoAcreedor+=$saldoAcreedor;
							
		}		
		$output =( array(
			             "     resultado" => 1, 
		                  "nro_registros" => count($estadoResultadoAcreedor) , 
					 "totalSaldoAcreedor" => number_format($totalSaldoAcreedor,2,'.',','),
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

		$id_entidad            = $this->input->post('id_entidad');
		$fecha_inicio  		   = $this->input->post('fecha_inicio');
		$fecha_fin           = $this->input->post('fecha_fin');
		// $id_cuenta             = $this->input->post('id_cuenta');

		$id_cuenta_ingreso   	   = 40;
		$codigo_cuenta_ingreso     = 4;
		$id_cuenta_egreso   	   = 41;
		$codigo_cuenta_egreso     = 5;

		$estadoResultadoDeudor = $this->EstadoDeResultado_model->getEstadoDeResultadosEgreso($id_entidad,$fecha_inicio,$fecha_fin,$id_cuenta_egreso,$codigo_cuenta_egreso);
		
		
		// $resultado    = $this->EstadoDeResultado_model->getMontoResultado($id_entidad,$fecha_desde,$fecha_hasta);
		$resultado    = $this->EstadoDeResultado_model->getMontoResultado($id_entidad,$fecha_inicio,$fecha_fin,$id_cuenta_ingreso,$codigo_cuenta_ingreso,$id_cuenta_egreso,$codigo_cuenta_egreso);


		if (!empty($resultado) && isset($resultado[0]->total_estado_resultado)) {
			$total_resultado = $resultado[0]->total_estado_resultado;
		} else {
			$total_resultado = 0; 
		}
		$totalSaldoDeudor =0;

		foreach ($estadoResultadoDeudor as $fila)
		{   
			$codigo 		 = $fila->codigo;
			$descripcion     = $fila->descripcion;
			$totalDeudor	 = $fila->saldo_deudor;

			$data[] = array(
				"<span class='badge badge-secondary'>".$codigo."</span>",
				"<span style='text-align: left; '>".$descripcion."</span>",	
				"<div style='text-align: right; color: #dc3545; font-weight: bold;'>
				".number_format($totalDeudor,2,'.',',').
				"</div>",				
			);
			$totalSaldoDeudor+=$totalDeudor;
							
		}		
		$output =( array(
			             "     resultado" => 1, 
		                  "nro_registros" => count($estadoResultadoDeudor) , 
					   "totalSaldoDeudor" => number_format($totalSaldoDeudor,2,'.',','),
					     "totalResultado" => number_format($total_resultado,2,'.',','),
						           "data" => $data ) );

		echo json_encode($output);
		exit();

	}
	function ReporteEstadoDeResultadosPDF($id_entidad,$fecha_inicio,$fecha_fin)
	{			
		/****************************/
		/*INICIO DEL REPORTE*/
		/****************************/		
		$this->load->library('fpdf/pdf2');
        $pdf = new Pdf2();
        $pdf->AliasNbPages();
        $pdf->SetAutoPageBreak(true, 30);
        $pdf->SetMargins(20,15,10);		
		$pdf->SetTitle(utf8_decode("Reporte Estado de la Cuenta"));
		$pdf->entidad=descripcion_nombre_entidad($id_entidad);
		$pdf->sigla=sigla_entidad($id_entidad);
		$pdf->tituloCabecera = 'ESTADO DE RESULTADOS';
		$pdf->subtituloCabecera1 = "DEL  ".formato_fecha_dia_2($fecha_inicio). " AL ".formato_fecha_dia_2($fecha_fin);  
		$pdf->subtituloCabecera2 = "Expresado en Bolivianos";  
        $w = array(15,115,40,50);
        $pdf->setWidthsG($w);
        $pdf->SetAligns(array('C','L','C','C'));
		$pdf->AddPage('P','Letter');
		$pdf->opcion_cabecera=8;
		$pdf->Header();
		$pdf->SetFillColor(255,255,255);
        $pdf->SetTextColor(0);
        $pdf->SetFont('Arial','',6);
		$pdf->Ln(1);
		$pdf->opcion_pie='FOOTER_VACIO';
		/*CUERPO DEL REPORTE*/
		$pdf->SetWidths([30, 135, 30]);
		$pdf->SetAligns(['L','L','R']);
        $num = 0;
        $total=0;

		$id_cuenta_ingreso   	   = 40;
		$codigo_cuenta_ingreso     = 4;

		$id_cuenta_egreso   	   = 41;
		$codigo_cuenta_egreso     = 5;

		$estadoResultadoAcreedor = $this->EstadoDeResultado_model->getEstadoDeResultadosIngreso($id_entidad,$fecha_inicio,$fecha_fin,$id_cuenta_ingreso,$codigo_cuenta_ingreso);
		
		
		$resultado    = $this->EstadoDeResultado_model->getMontoResultado($id_entidad,$fecha_inicio,$fecha_fin,$id_cuenta_ingreso,$codigo_cuenta_ingreso,$id_cuenta_egreso,$codigo_cuenta_egreso);

		if (!empty($resultado) && isset($resultado[0]->total_estado_resultado)) {
			$total_resultado = $resultado[0]->total_estado_resultado;
		} else {
			$total_resultado = 0; 
		}
		$totalSaldoAcreedor =0;
		$pdf->SetXY(15,40);
		$pdf->SetFillColor(230, 230, 225);
		$pdf->SetTextColor(0);
        $pdf->SetFont('Arial','B',7);
		$pdf->Cell(195,5,utf8_decode('CUENTAS DE INGRESO'), 1, 0, 'C', 1);
		$pdf->SetXY(15,45);
		$pdf->SetFillColor(230, 230, 225);
		$pdf->SetTextColor(0);
		$pdf->SetFont('Arial','B',7);
		$pdf->Cell(30,5,utf8_decode('CÓDIGO'), 1, 0, 'C', 1);
		$pdf->Cell(135,5,utf8_decode('CUENTA'), 1, 0, 'C', 1);
		$pdf->Cell(30,5,utf8_decode('BOLIVIANOS'),1,0,'C',1);

		$pdf->SetY(50);
		foreach ($estadoResultadoAcreedor as $fila)
		{   
			$codigo 		 = $fila->codigo;
			$descripcion     = $fila->descripcion;
			$saldo_acreedor	 = $fila->saldo_acreedor;


			$row = array(
				$codigo,
				$descripcion,
				number_format($saldo_acreedor,2,'.',',')
			    );
			$totalSaldoAcreedor+=$saldo_acreedor;
			$pdf->SetFont('Arial','',7);
			// $pdf -> SetX(15);
			$pdf->Row_Reportes_ER($row,true, '', 3);	
		} 
		$pdf->SetFont('Arial','B',7);
		$x=15;
		$y=$pdf->GetY();
		$pdf->Line($x, $y, $x + 195, $y);
		$pdf->Line($x, $y+5, $x + 195, $y+5);
		$TOTALES="TOTAL CUENTAS DE INGRESO";
		$y=$pdf->GetY();
		$pdf->SetXY(15,$y);    
		$pdf->Cell(165,5,utf8_decode($TOTALES),0,0,'C',1);
		$pdf->Cell(30,5,utf8_decode(number_format($totalSaldoAcreedor,2,'.',',')),0,0,'R',1);		
		$y_fin=$pdf->GetY();
		$pdf->ln(5);
		$x=15;
		$y=$pdf->GetY();
		$pdf->Line($x, $y, $x + 195, $y);
		$pdf->Line($x, $y+5, $x + 195, $y+5);
		$TOTALES="RESULTADO DEL EJERCICIO";
		$y=$pdf->GetY();
		$pdf->SetXY(15,$y);    
		$pdf->Cell(165,5,utf8_decode($TOTALES),0,0,'C',1);
		$pdf->Cell(30,5,utf8_decode(number_format($total_resultado,2,'.',',')),0,0,'R',1);	
		$ini_y=50;	
		$y_fin=$pdf->GetY();
		$pdf->Line(15, $ini_y, 15, $y_fin+5);
		$pdf->Line(45, $ini_y, 45, $y_fin-5);
		$pdf->Line(180, $ini_y, 180, $y_fin+5);
		$pdf->Line(210, $ini_y, 210, $y_fin+5);
		$pdf->ln(10);


		// TABLA CUENTAS DE EGRESO 

		$y_ini=$pdf->GetY();
		$pdf->SetXY(15,$y_ini);
		$pdf->SetFillColor(230, 230, 225);
		$pdf->SetTextColor(0);
        $pdf->SetFont('Arial','B',7);
		$pdf->Cell(195,5,utf8_decode('CUENTAS DE EGRESO'), 1, 0, 'C', 1);
		$pdf->ln(5);
		$pdf->SetX(15);
		$pdf->SetFillColor(230, 230, 225);
		$pdf->SetTextColor(0);
		$pdf->SetFont('Arial','B',7);
		$pdf->Cell(30,5,utf8_decode('CÓDIGO'), 1, 0, 'C', 1);
		$pdf->Cell(135,5,utf8_decode('CUENTA'), 1, 0, 'C', 1);
		$pdf->Cell(30,5,utf8_decode('BOLIVIANOS'),1,0,'C',1);
		$pdf->ln(5);
		$totalSaldoDeudor=0;

		

		$estadoResultadoDeudor = $this->EstadoDeResultado_model->getEstadoDeResultadosEgreso($id_entidad,$fecha_inicio,$fecha_fin,$id_cuenta_egreso,$codigo_cuenta_egreso);
		foreach ($estadoResultadoDeudor as $fila)
		{   
			$codigo 		 = $fila->codigo;
			$descripcion     = $fila->descripcion;
			$totalDeudor	 = $fila->saldo_deudor;


			$row = array(
				$codigo,
				$descripcion,
				number_format($totalDeudor,2,'.',',')
			    );
			$totalSaldoDeudor+=$totalDeudor;
			$pdf->SetFont('Arial','',7);	
			$pdf->Row_Reportes_ER($row,true, '', 3);	
		} 
		$pdf->SetFont('Arial','B',7);
		$x=15;
		$y=$pdf->GetY();
		$pdf->Line($x, $y, $x + 195, $y);
		$pdf->Line($x, $y+5, $x + 195, $y+5);
		$TOTALES="TOTAL CUENTAS DE EGRESO";
		$y=$pdf->GetY();
		$pdf->SetXY(15,$y);    
		$pdf->Cell(165,5,utf8_decode($TOTALES),0,0,'C',1);
		$pdf->Cell(30,5,utf8_decode(number_format($totalSaldoDeudor,2,'.',',')),0,0,'R',1);		
		$pdf->ln(5);
		$y=$pdf->GetY();
		$pdf->Line($x, $y, $x + 195, $y);
		$pdf->Line($x, $y+5, $x + 195, $y+5);
		$TOTALES="RESULTADO DEL EJERCICIO";
		$y=$pdf->GetY();
		$pdf->SetXY(15,$y);    
		$pdf->Cell(165,5,utf8_decode($TOTALES),0,0,'C',1);
		$pdf->Cell(30,5,utf8_decode(number_format($total_resultado,2,'.',',')),0,0,'R',1);		
		$y_fin=$pdf->GetY();
		$pdf->Line(15, $y_ini+10, 15, $y_fin+5);
		$pdf->Line(45, $y_ini+10, 45, $y_fin-5 );
		$pdf->Line(180, $y_ini+10, 180, $y_fin+5);
		$pdf->Line(210, $y_ini+10, 210, $y_fin+5);
		$pdf->ln(5);

		$pdf->Footer();
		$pdf->Output('I',utf8_decode('ReporteEstadoDeCuenta.pdf')); 
	}
}
