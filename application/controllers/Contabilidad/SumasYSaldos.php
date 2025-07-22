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

		$id_entidad            = $this->input->post('id_entidad');
		$cuentasSeleccionadas  = $this->input->post('cuentasSeleccionadas');
		$fecha_desde  		   = $this->input->post('fecha_desde');
		$fecha_hasta           = $this->input->post('fecha_hasta');

		$cadena = str_replace('-', ',', $cuentasSeleccionadas);
		$cadena = rtrim($cadena, ',');

		$filas  	 = $this->SumasSaldos_model->getGeneralSumasSaldosCuentasByIds($id_entidad,$cadena,$fecha_desde,$fecha_hasta);
		$totalDebe =0;
		$totalHaber =0;
		$totalDeudor=0;
		$totalAcreedor=0;
		foreach ($filas as $fila)
		{   
			$deudor =0;
			$acreedor =0;
			$codigo= $fila->codigo;
			$descripcion = $fila->descripcion;	
			$debe = $fila->debe;
			$haber = $fila->haber;
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
				number_format($debe,2,'.',','),
				number_format($haber,2,'.',','),
				number_format($deudor,2,'.',','),
				number_format($acreedor,2,'.',',')
			    );
			$totalDebe += $debe;
			$totalHaber += $haber;		
			$totalDeudor += $deudor;
			$totalAcreedor += $acreedor;
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
	function ReporteSumasySaldosPDF($id_entidad,$cuentas,$fecha_inicio,$fecha_fin)
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
		$pdf->sigla="xxx";
		$pdf->tituloCabecera = 'BALANCE SUMAS Y SALDOS';
		$pdf->subtituloCabecera1 = "Entre el ".formato_fecha_slash($fecha_inicio). " y ".formato_fecha_slash($fecha_fin);  
		$pdf->subtituloCabecera2 = "Expresado en Bolivianos";  
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
        $num = 0;
        $total=0;

		// 1. Reemplazar guiones por comas
		$cadena = str_replace('-', ',', $cuentas);
		// 2. Eliminar la última coma si existe
		$cadena = rtrim($cadena, ',');

		$sumasysaldos  	 = $this->SumasSaldos_model->getGeneralSumasSaldosCuentasByIds($id_entidad,$cadena,$fecha_inicio,$fecha_fin);

		$totalDebe =0;
		$totalHaber =0;
		$totalDeudor=0;
		$totalAcreedor=0;

		$pdf->SetFillColor(255,255,255);
		$pdf->SetFont('Arial', '', 8);
		$ini_x=$pdf->GetX();
		// $ini_y=$pdf->GetY();
		
		$pdf->setX(12); 
		$pdf->SetWidths([25, 120, 15, 15, 15, 15]);
		$pdf->SetAligns(['L','L','R','R','R','R']);
        foreach ($sumasysaldos as $fila)
		{   		
				
			$deudor =0;
			$acreedor =0;
			$codigo= $fila->codigo;
			$descripcion = $fila->descripcion;	
			$debe = $fila->debe;
			$haber = $fila->haber;
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
			$fila = array(
				$codigo,
				$descripcion,
				number_format($debe,2,'.',','),
				number_format($haber,2,'.',','),
				number_format($deudor,2,'.',','),
				number_format($acreedor,2,'.',',')
			    );
			$totalDebe += $debe;
			$totalHaber += $haber;		
			$totalDeudor += $deudor;
			$totalAcreedor += $acreedor;

			$pdf->setX(5); 
			$pdf->Row_Reportes_LM($fila,true, '', 4);								
			$pdf->opcion_pie='FOOTER_VACIO';
			// $pdf->Ln();
	    }      
		// $pdf->Ln();
		$pdf->SetFillColor(255,255,255);
		$y=$pdf->GetY();
		$pdf->SetXY(5,$y);
		// $pdf->setX(5);     
		// $pdf->SetAligns(['R','R','R','C','C']);
		
		// $x=$pdf->GetX();
		

		/*LINEA HORIZONTAL*/
		$x=5;
		$y=$pdf->GetY();
		$pdf->Line($x, $y, $x + 205, $y);
		$pdf->Cell(145,2,"",0,0,'R',1);
		$pdf->Cell(60,2,"",0,0,'R',1);
		// $pdf->Ln();
		$pdf->SetFont('Arial','B',8);
		$TOTALES="TOTALES";
		$y=$pdf->GetY();
		$pdf->SetXY(5,$y);
		$pdf->setX(5);     
		$pdf->Cell(145,8,utf8_decode($TOTALES),0,0,'R',1);
		$pdf->Cell(15,8,utf8_decode(number_format($totalDebe,2,'.',',')),0,0,'R',1);
		$pdf->Cell(15,8,utf8_decode(number_format($totalHaber,2,'.',',')),0,0,'R',1);
		$pdf->Cell(15,8,utf8_decode(number_format($totalDeudor,2,'.',',')),0,0,'R',1);
		$pdf->Cell(15,8,utf8_decode(number_format($totalAcreedor,2,'.',',')),0,0,'R',1);  



	    /*DIBUJANDO LINEAS*/
		$ini_y=46;
		$y_fin=$pdf->GetY();
		// $pdf->SetXY(5,$ini_y);
	    $pdf->Line(5, $ini_y, 5, $y_fin+7);
		// $pdf->Setxy(30, $ini_y);
		$pdf->Line(30, $ini_y, 30, $y_fin);
		$pdf->Line(150, $ini_y, 150, $y_fin+7);
		$pdf->Line(180, $ini_y, 180, $y_fin+7);
		$pdf->Line(210, $ini_y, 210, $y_fin+7);

		$x_fin=5;
		$pdf->Line($x_fin, $y_fin+7, $x_fin + 205, $y_fin+7);

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