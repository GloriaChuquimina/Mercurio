<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// require_once APPPATH . "libraries/fpdf/easyTable.php";
// require_once APPPATH . "libraries/fpdf/exfpdfCartaContable.php";

class LibroMayor extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->_is_logued_in();
        $this->load->model('LibroMayor_model');
        $this->load->model('PlanDeCuentas_model');
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

		$titulo = "Libro Mayor";		
		$dato['titulo'] = $titulo;

		$this->load->view('inicio/cabecera',$dato);
		$this->load->view('inicio/menu',$dato);
		$this->load->view('contabilidad/libromayor',$dato);
		$this->load->view('inicio/pie');
	}
	public function listarBusquedaLibroMayor()
    {
		$plandecuentas   = $this->PlanDeCuentas_model->getPlanDeCuentasBusqueda();
		$id_entidad      = $this->input->post('id_entidad');
		// echo($id_entidad);
		
		// $cuentas = json_decode(json_encode($cuentas), true);
		// $ordenadas = $this->ordenarJerarquicamente($cuentas);
		
		$draw    = intval($this->input->get("draw"));
		$start   = intval($this->input->get("start"));
		$length  = intval($this->input->get("length"));	
		$data    = array();
		$num     = 1;


		$tr='';

		$tr= "<table class='table table-striped table-hover' id='tbl_libroMayor' name ='tbl_libroMayor'>
		      <thead class='bg-dark'>
					<tr>
					  <th rowspan='2' style='color: white; width:100px '>FECHA</th>
					  <th rowspan='2' style='color: white; width:120px '>COMPROBANTE</th>
					  <th rowspan='2' style='color: white; width:100px '>TIPO</th>
					  <th rowspan='2' style='color: white;'>DESCRIPCIÓN(GLOSA)</th>                
					  <th colspan='2' style='color: white; width:120px;text-align: right' >MOVIMIENTOS</th>
					  <th colspan='2' style='color: white; width:120px;text-align: right'>SALDOS</th>
					</tr>
					<tr>
					  <th style='color: white; width:120px;text-align: right' >DEBE</th>
					  <th style='color: white; width:120px;text-align: right'>HABER</th>
					  <th style='color: white; width:120px;text-align: right'>DEUDOR</th>
					  <th style='color: white; width:120px;text-align: right'>ACREEDOR</th>
					</tr>
			  </thead>";
			  $tr .= "<tbody>";

		foreach ($plandecuentas as $cuenta)
		{   
			$cabercera1_cuenta = "Cuenta:".$cuenta->codigo;			   
			// echo($cuenta->ruta);
			// echo("<br>");
			// echo($cuentas_ruta);
			// echo("<br>");
			// echo(count($cuentas_ruta));
			// die();
			$cabercera2_cuenta="";
			
			if($cuenta->ruta == 0)
			{
				$cabercera2_cuenta="<b>".$cuenta->descripcion."</b>";
			}
			else
			{
				$cuentas_ruta      = explode("-", $cuenta->ruta);
				$nro_ruta =1;
				foreach($cuentas_ruta as $ruta)
				{
					if(count($cuentas_ruta) === $nro_ruta)
					{
						$cabercera2_cuenta .="<b>".getCuenta($ruta)."-->".$cuenta->descripcion."</b>";
					}
					else
					{
						if($ruta != 0)
						{
							$cabercera2_cuenta .="<b>".getCuenta($ruta)."--></b>";
						}
					}					
					$nro_ruta++;
					
				}
			}			
			$tr.="<tr>
					<td colspan ='8'>
					".$cabercera1_cuenta."
					</td>
					</tr>";
			$tr.="<tr>
					<td colspan ='8'>
					".$cabercera2_cuenta."
					</td>
					</tr>";
			$cuentasLibroMayor   = $this->LibroMayor_model->getLibroMayorBusqueda1($id_entidad,$cuenta->id);
			$totalImporteDebe =0;
			$totalImporteHaber =0;
			$totalImporteDeudor=0;
			$totalImporteAcreedor =0;
			if(count($cuentasLibroMayor)==0)
			{
				$detalle_movimiento ="SIN MOVIMIENTO";
				$tr.="<tr>
						<td colspan ='8'>
						".$detalle_movimiento."
						</td>
					  </tr>";
			}
			else
			{
				foreach($cuentasLibroMayor as $registro)
				{
					$fecha_comprobante  = formato_fecha($registro->fecha_comprobante);
					$tipo_comprobante   = getValor2Configuraciones("TIPO COMPROBANTES CONTABLE", $registro->tipo_comprobante);
					$numero_correlativo = $registro->correlativo;
					$glosa_cuenta       = $registro->glosa_cuenta;
					$importeDebe =0;
					$importeHaber =0;
					if($registro->tipo_movimiento == "DB")
					{
						$importeDebe   = $registro->importe_moneda_nacional;
					}
					else
					{
						$importeHaber = $registro->importe_moneda_nacional;
					}
					$importeDeudor=0;
					$importeAcreedor=0;
					$saldoCuenta=172966.50;
					
					$importeDeudor   = $importeDeudor + $importeDebe;
					$importeAcreedor = $saldoCuenta-$importeHaber; 
										
					$tr.="<tr>
							<td>
							".$fecha_comprobante."
							</td>
							<td>
							".$tipo_comprobante."
							</td>
							<td>
							".$numero_correlativo."
							</td>
							<td>
							".$glosa_cuenta."
							</td>
							<td style='text-align: right'>
							".$importeDebe."
							</td>
							<td style='text-align: right'>
							".$importeHaber."
							</td>
							<td style='text-align: right'>
							".$importeDeudor."
							</td>
							<td style='text-align: right'>
							".$importeAcreedor."
							</td>
					  	  </tr>";		
						  
					$totalImporteDebe     = $totalImporteDebe+$importeDebe;
					$totalImporteHaber    = $totalImporteHaber+$importeHaber;
					$totalImporteDeudor   = $totalImporteDeudor+$importeDeudor;
					$totalImporteAcreedor = $totalImporteAcreedor+$importeAcreedor;
					
				}
				
				$tr.="<tr style='background-color:rgb(248, 232, 228); font-weight: bold;'>
						<td colspan ='4' style='text-align: right'>
						TOTALES:
						</td>
						<td style='text-align: right'>
						".$totalImporteDebe."
						</td>
						<td style='text-align: right'>
						".$totalImporteHaber."
						</td>
						<td style='text-align: right'>
						".$totalImporteDeudor."
						</td>
						<td style='text-align: right'>
						".$totalImporteAcreedor."
						</td>
					  </tr>";
			}
		}
		$tr .= "</tbody></table>";
		// echo ($tr);
		// die();
		$output =( array(
			            "resultado" => 1, 
		              "nro_cuentas" => count($plandecuentas) , 
						    "tabla" => $tr ) );

		echo json_encode($output);
		exit();
    }
	function ReporteLibroMayorPDF($fecha_inicio,$fecha_fin)
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
		$pdf->SetTitle(utf8_decode("Reporte Libro Mayor"));
		$pdf->entidad="PRUEBAS";
		$pdf->sigla="xxx";
		$pdf->tituloCabecera = 'LIBRO MAYOR';
		$pdf->subtituloCabecera1 = "Entre el ".formato_fecha_slash($fecha_inicio). " y ".formato_fecha_slash($fecha_fin);  
		$pdf->subtituloCabecera2 = "Expresado en Bolivianos";  
        $w = array(15,115,40,50);
        $pdf->setWidthsG($w);
        $pdf->SetAligns(array('C','L','C','C'));
		$pdf->AddPage('P','Letter');
		$pdf->opcion_cabecera=3;
		$pdf->Header();
		$pdf->SetFillColor(255,255,255);
        $pdf->SetTextColor(0);
        $pdf->SetFont('Arial','',6);
		$pdf->Ln(2);
		/*CUERPO DEL REPORTE*/
		$pdf->SetFillColor(255,255,255);
        $pdf->SetTextColor(0);
        $pdf->SetFont('Arial','',6);
        $num = 0;
        $total=0;

		$plandecuentas   = $this->PlanDeCuentas_model->getPlanDeCuentasBusqueda();
		// $id_entidad      = $this->input->post('id_entidad');
		$id_entidad      = 1;

		$totalGeneralImporteDebe =0;
		$totalGeneralImporteHaber =0;
		$totalGeneralImporteDeudor=0;
		$totalGeneralImporteAcreedor =0;
        foreach ($plandecuentas as $cuenta)
		{   
			$cabercera1_cuenta = "Cuenta:".$cuenta->codigo;			   

			$cabercera2_cuenta="";
			
			if($cuenta->ruta == 0)
			{
				$cabercera2_cuenta=$cuenta->descripcion;
			}
			else
			{
				$cuentas_ruta      = explode("-", $cuenta->ruta);
				$nro_ruta =1;
				foreach($cuentas_ruta as $ruta)
				{
					if(count($cuentas_ruta) === $nro_ruta)
					{
						$cabercera2_cuenta .=getCuenta($ruta)."-->".$cuenta->descripcion;
					}
					else
					{
						if($ruta != 0)
						{
							$cabercera2_cuenta .=getCuenta($ruta)."-->";
						}
					}					
					$nro_ruta++;
					
				}
			}	
			$pdf->SetFillColor(245, 245, 240);
			$pdf->SetFont('Arial', 'B', 8);
			$pdf->setX(5);     
	        $pdf->Cell(205,6,utf8_decode($cabercera1_cuenta),0,0,'L',1);
			$pdf->Ln();
			$y=$pdf->GetY();
            $pdf->SetXY(5,$y);
			$pdf->setX(5);     
	        $pdf->Cell(205,6,utf8_decode($cabercera2_cuenta),0,0,'L',1);
			$pdf->Ln();

			$cuentasLibroMayor   = $this->LibroMayor_model->getLibroMayorBusqueda1($id_entidad,$cuenta->id);
			$totalImporteDebe =0;
			$totalImporteHaber =0;
			$totalImporteDeudor=0;
			$totalImporteAcreedor =0;
			
			if(count($cuentasLibroMayor)==0)
			{
				$pdf->SetFont('Arial', '', 8);
				$detalle="SIN MOVIMIENTO";
				$pdf->SetFillColor(255,255,255);
				$y=$pdf->GetY();
				$pdf->SetXY(5,$y);
				$pdf->setX(5);     
				$pdf->Cell(205,6,utf8_decode($detalle),0,0,'L',1);
				$pdf->Ln();

				$y=$pdf->GetY();
				$pdf->SetXY(5,$y);
				$pdf->setX(5);     
				// $pdf->SetAligns(['R','R','R','C','C']);
				$pdf->Cell(145,2,"",0,0,'R',1);
				$pdf->Cell(60,2,"","BR",0,'R',1);
				$pdf->Ln();

				$TOTALES="SUBTOTALES";
				$pdf->SetFillColor(255,255,255);
				$y=$pdf->GetY();
				$pdf->SetXY(5,$y);
				$pdf->setX(5);     
				$pdf->Cell(145,8,utf8_decode($TOTALES),0,0,'R',1);
				$pdf->Cell(15,8,utf8_decode($totalImporteDebe),0,0,'R',1);
				$pdf->Cell(15,8,utf8_decode($totalImporteHaber),0,0,'R',1);
				$pdf->Cell(15,8,utf8_decode("-"),0,0,'C',1);
				$pdf->Cell(15,8,utf8_decode("-"),0,0,'C',1);
				$pdf->Ln();
			}
			else
			{
				$pdf->SetFillColor(255,255,255);
				$pdf->SetFont('Arial', '', 8);
				$pdf->setX(5); 
				$pdf->SetWidths([20, 15, 20, 90, 15, 15, 15, 15]);
				$pdf->SetAligns(['C','C','C','L','R','R','R','R']);
				foreach($cuentasLibroMayor as $registro)
				{
					
					$fecha_comprobante  = formato_fecha_slash($registro->fecha_comprobante);
					// $tipo_comprobante   = getValor2Configuraciones("TIPO COMPROBANTES CONTABLE", $registro->tipo_comprobante);
					$tipo_comprobante   = $registro->tipo_comprobante;
					$numero_correlativo = $registro->correlativo;
					$glosa_cuenta       = $registro->glosa_cuenta;
					$importeDebe =0;
					$importeHaber =0;
					if($registro->tipo_movimiento == "DB")
					{
						$importeDebe   = $registro->importe_moneda_nacional;
					}
					else
					{
						$importeHaber = $registro->importe_moneda_nacional;
					}
					$importeDeudor=0;
					$importeAcreedor=0;
					$saldoCuenta=172966.50;
					
					$importeDeudor   = $importeDeudor + $importeDebe;
					$importeAcreedor = $saldoCuenta-$importeHaber; 
										
					$fila = array(
							$fecha_comprobante,
							$tipo_comprobante,
							$numero_correlativo,
							$glosa_cuenta,
							number_format($importeDebe,0,',','.') ,
							number_format($importeHaber,0,',','.') ,
							number_format($importeDeudor,0,',','.') ,
							number_format($importeAcreedor,0,',','.') 
						);	
					$pdf->setX(5); 
					$pdf->Row_Reportes_LM($fila,true, '', 4);	
					
						  
					$totalImporteDebe     = $totalImporteDebe+$importeDebe;
					$totalImporteHaber    = $totalImporteHaber+$importeHaber;
					$totalImporteDeudor   = $totalImporteDeudor+$importeDeudor;
					$totalImporteAcreedor = $totalImporteAcreedor+$importeAcreedor;
					
				}
				$pdf->Ln();
				$pdf->SetFillColor(255,255,255);
				$y=$pdf->GetY();
				$pdf->SetXY(5,$y);
				$pdf->setX(5);     
				// $pdf->SetAligns(['R','R','R','C','C']);
				$pdf->Cell(145,2,"",0,0,'R',1);
				$pdf->Cell(60,2,"","BR",0,'R',1);
				$pdf->Ln();

				$TOTALES="SUBTOTALES";
				$y=$pdf->GetY();
				$pdf->SetXY(5,$y);
				$pdf->setX(5);     
				$pdf->Cell(145,8,utf8_decode($TOTALES),0,0,'R',1);
				$pdf->Cell(15,8,utf8_decode($totalImporteDebe),0,0,'R',1);
				$pdf->Cell(15,8,utf8_decode($totalImporteHaber),0,0,'R',1);
				$pdf->Cell(15,8,utf8_decode($totalImporteDeudor),0,0,'C',1);
				$pdf->Cell(15,8,utf8_decode($totalImporteAcreedor),0,0,'C',1);

			}
			
			$totalGeneralImporteDebe =$totalGeneralImporteDebe+$totalImporteDebe;
			$totalGeneralImporteHaber =$totalGeneralImporteHaber+$totalImporteHaber;
			$totalGeneralImporteDeudor=$totalGeneralImporteDeudor+$totalImporteDeudor;
			$totalGeneralImporteAcreedor =$totalGeneralImporteAcreedor+$totalImporteAcreedor;

			

			$pdf->opcion_pie='FOOTER_VACIO';
			$pdf->Ln();
	    }      
		// $pdf->Ln();
		$pdf->SetFillColor(255,255,255);
		$y=$pdf->GetY();
		$pdf->SetXY(5,$y);
		$pdf->setX(5);     
		// $pdf->SetAligns(['R','R','R','C','C']);
		$pdf->Cell(145,2,"",0,0,'R',1);
		$pdf->Cell(60,2,"","BR",0,'R',1);
		$pdf->Ln();
		$TOTALES="TOTALES";
		$y=$pdf->GetY();
		$pdf->SetXY(5,$y);
		$pdf->setX(5);     
		$pdf->Cell(145,8,utf8_decode($TOTALES),0,0,'R',1);
		$pdf->Cell(15,8,utf8_decode($totalGeneralImporteDebe),0,0,'R',1);
		$pdf->Cell(15,8,utf8_decode($totalGeneralImporteHaber),0,0,'R',1);
		$pdf->Cell(15,8,utf8_decode($totalGeneralImporteDeudor),0,0,'C',1);
		$pdf->Cell(15,8,utf8_decode($totalGeneralImporteAcreedor),0,0,'C',1);  
		// $pdf->AddPage('P', 'Letter'); 
		// $pdf->setX(5); 
		// $pdf->opcion_pie='FOOTER_VACIO';
		$pdf->Footer();
		$pdf->Output('I',utf8_decode('ReporteComprobante.pdf')); 
	}
}