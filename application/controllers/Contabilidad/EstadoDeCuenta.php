<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EstadoDeCuenta extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->_is_logued_in();
        $this->load->model('Comprobantes_model');
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

		$titulo = "Estado de Cuentas";		
		$dato['titulo'] = $titulo;

		$this->load->view('inicio/cabecera',$dato);
		$this->load->view('inicio/menu',$dato);
		$this->load->view('contabilidad/estadodecuenta',$dato);
		$this->load->view('inicio/pie');
	}
	public function cargarDatosEstadoDeCuenta()
	{
		$id_usuario  = $this->session->userdata('id_usuario');
			
		$draw    = intval($this->input->get("draw"));
		$start   = intval($this->input->get("start"));
		$length  = intval($this->input->get("length"));	
		$data    = array();
		$num     = 1;

		$id_entidad            = $this->input->post('id_entidad');
		$fecha_desde  		   = $this->input->post('fecha_inicio');
		$fecha_hasta           = $this->input->post('fecha_fin');
		$id_cuenta             = $this->input->post('id_cuenta');

		$estadoCuenta = $this->EstadoDeCuenta_model->getEstadoDeCuenta($id_entidad,$fecha_desde,$fecha_hasta);
		$totalDebe =0;
		$totalHaber =0;
		$importeDebe=0;
		$importeHaber=0;
		$importe_moneda_nacional=0;
		$importe_moneda_extranjera=0;
		//  $tr .= "<tbody>";
		 $tr = "";
		$totalGeneralImporteDebe  = 0;
		$totalGeneralImporteHaber = 0;
		foreach ($estadoCuenta as $fila)
		{   
			$codigo_aux 		 = $fila->codigo_aux;
			$descripcion_aux	 = $fila->descripcion_aux;

			$data[] = array(
				"<span class='badge badge-secondary'>".$codigo_aux."</span>",
				$descripcion_aux,	
				$tipo,
                $fila['nivel'],
                $fila['sigla'],
				$estado
			);

			$tr.="<tr style='background-color:rgb(209, 226, 239); font-weight: bold;'>
					<td>
						".$fecha_comprobante."
					</td>
					<td>
						".$tipo_comprobante."
					</td>
					<td style='text-align: right;'>
						---
					</td>
					<td style='text-align: right;'>
						---
					</td>
				  </tr>";
			$datosComprobante    = $this->Comprobantes_model->getDetalleComprobanteByIdComprobante($id_comprobante);
			if($datosComprobante)	{
					foreach ($datosComprobante as $detalle_comprobante) {	
						
						
							$importeDebe=0;
							$importeHaber=0;

							$id_entidad      		   = $detalle_comprobante->id_entidad;
							$id_comprobante 		   = $detalle_comprobante->id_comprobante;
							$id_cuenta     		       = $detalle_comprobante->id_cuenta;
							$tipo_movimiento	       = $detalle_comprobante->tipo_movimiento;
							$tipo_cambio  		       = $detalle_comprobante->tipo_cambio ;
							$importe_moneda_nacional   = $detalle_comprobante->importe_moneda_nacional;
							$importe_moneda_extranjera = $detalle_comprobante->importe_moneda_extranjera;
							$glosa_cuenta     	       = $detalle_comprobante->glosa_cuenta;
							$estado     		       = $detalle_comprobante->estado;
							$codigo_cuenta			   = getCodigoCuenta($id_cuenta);
							$descripcion_cuenta		   = getCuenta($id_cuenta);
							$codigo_descripcion		   = $codigo_cuenta."-".$descripcion_cuenta;
							$resul 				       = 1;
							$mensaje				   = "OK";	
							if($tipo_movimiento == "DB"){
								$importeDebe=$importe_moneda_nacional; 
							}
							elseif ($tipo_movimiento == "HB") {
								$importeHaber=$importe_moneda_nacional; 
							}
							$tr.="<tr>
									<td>
									".$codigo_cuenta."
									</td>
									<td>
									".$descripcion_cuenta."
									</td>
									<td style='text-align: right; color: #28a745; font-weight: bold;'>
									".number_format($importeDebe,2,'.',',')."
									</td>
									<td style='text-align: right;  color: #dc3545; font-weight: bold;'>
									".number_format($importeHaber,2,'.',',')."
									</td>		
								</tr>";				
							// $data[] = array(
							// 	$codigo,
							// 	$descripcion,
							// 	number_format($debe,2,'.',','),
							// 	number_format($haber,2,'.',',')
							// 	);
							$totalDebe += $importeDebe;
							$totalHaber += $importeHaber;
					}
				$tr.="<tr style='background-color:rgb(248, 232, 228); font-weight: bold;'>
						<td style='text-align: left'>
							---
						</td>
						<td style='text-align: left'>
						".$glosa_comprobante."
						</td>
						<td style='text-align: right'>
						".number_format($totalDebe,2,'.',',')."
						</td>
						<td style='text-align: right'>
						".number_format($totalHaber,2,'.',',')."
						</td>
					 </tr>";
			}
			$totalGeneralImporteDebe  = $totalGeneralImporteDebe+$totalDebe;
			$totalGeneralImporteHaber = $totalGeneralImporteHaber+$totalHaber;			

		}

		// $tr .= "</tbody>";
		
		$output =( array(
			            "resultado" 	 => 1, 
		              "nro_comprobantes" => count($libroDiarioComprobante) , 
					  "totalimporteDebe" => number_format($totalGeneralImporteDebe,2,'.',','),
            		 "totalimporteHaber" => number_format($totalGeneralImporteHaber,2,'.',','),
						         "tabla" => $tr ) );

		echo json_encode($output);
		exit();

	}
	function ReporteEstadoDeCuentaPDF($id_entidad,$cuentas,$fecha_inicio,$fecha_fin)
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
		$plandecuentas   = $this->PlanDeCuentas_model->getPlanDeCuentasBusquedaIds($cadena);

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
						$cabercera2_cuenta .=getCuenta($ruta)." --> ".$cuenta->descripcion;
						
					}
					else
					{
						if($ruta != 0)
						{
							$cabercera2_cuenta .=getCuenta($ruta)." --> " ;
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
			// $pdf->setX(5);     
	        $pdf->Cell(205,6,utf8_decode($cabercera2_cuenta),0,0,'L',1);

			$pdf->Ln();

			// $cuentasLibroMayor   = $this->LibroMayor_model->getLibroMayorBusqueda1($id_entidad,$cuenta->id);
			$cuentasLibroMayor   = $this->LibroMayor_model->getLibroMayorBusqueda2($id_entidad,$cuenta->id,$fecha_inicio,$fecha_fin);
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
				$x=$pdf->GetX();
				$y=$pdf->GetY();
				$pdf->Line($x, $y, $x + 60, $y);
				$pdf->Cell(60,2,"",0,0,'R',1);
				$pdf->Ln();

				$TOTALES="SUBTOTALES";
				$pdf->SetFillColor(255,255,255);
				$y=$pdf->GetY();
				$pdf->SetXY(5,$y);
				// $pdf->setX(5);     
				$pdf->Cell(145,8,utf8_decode($TOTALES),0,0,'R',1);
				$pdf->Cell(15,8,utf8_decode($totalImporteDebe),0,0,'R',1);
				$pdf->Cell(15,8,utf8_decode($totalImporteHaber),0,0,'R',1);
				$pdf->Cell(15,8,utf8_decode("-"),0,0,'C',1);
				$pdf->Cell(15,8,utf8_decode("-"),0,0,'C',1);
				// $pdf->Ln();
			}
			else
			{
				$pdf->SetFillColor(255,255,255);
				$pdf->SetFont('Arial', '', 8);
				$pdf->setX(5); 
				$pdf->SetWidths([20, 15, 20, 90, 15, 15, 15, 15]);
				$pdf->SetAligns(['C','C','C','L','R','R','R','R']);
				$importeDeudor=0;
				$importeAcreedor=0;
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
						$importeDeudor   = $importeDeudor + $importeDebe;
					}
					else
					{
						$importeHaber = $registro->importe_moneda_nacional;
						$importeAcreedor = $importeAcreedor-$importeHaber; 
					}
					// $importeDeudor=0;
					// $importeAcreedor=0;
					$saldoCuenta=0;
					
										
					$fila = array(
							$fecha_comprobante,
							$tipo_comprobante,
							$numero_correlativo,
							$glosa_cuenta,
							number_format($importeDebe,2,',','.') ,
							number_format($importeHaber,2,',','.') ,
							number_format($importeDeudor,2,',','.') ,
							number_format($importeAcreedor,2,',','.') 
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
				// $pdf->SetXY();
				$x=$pdf->GetX();
				$y=$pdf->GetY();
				$pdf->Line($x, $y, $x + 60, $y);
				$pdf->Cell(60,2,"",0,0,'R',1);
				$pdf->Ln();

				$TOTALES="SUBTOTALES";
				$y=$pdf->GetY();
				$pdf->SetXY(5,$y);
				$pdf->setX(5);     
				$pdf->Cell(145,8,utf8_decode($TOTALES),0,0,'R',1);
				$pdf->Cell(15,8,utf8_decode($totalImporteDebe),0,0,'R',1);
				$pdf->Cell(15,8,utf8_decode($totalImporteHaber),0,0,'R',1);
				$pdf->Cell(15,8,utf8_decode($totalImporteDeudor),0,0,'R',1);
				$pdf->Cell(15,8,utf8_decode($totalImporteAcreedor),0,0,'R',1);

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
		// $pdf->setX(5);     
		// $pdf->SetAligns(['R','R','R','C','C']);
		$pdf->Cell(145,2,"",0,0,'R',1);
		$x=$pdf->GetX();
		$y=$pdf->GetY();
		$pdf->Line($x, $y, $x + 60, $y);
		$pdf->Cell(60,2,"",0,0,'R',1);
		$pdf->Ln();
		$TOTALES="TOTALES";
		$y=$pdf->GetY();
		$pdf->SetXY(5,$y);
		$pdf->setX(5);     
		$pdf->Cell(145,8,utf8_decode($TOTALES),0,0,'R',1);
		$pdf->Cell(15,8,utf8_decode($totalGeneralImporteDebe),0,0,'R',1);
		$pdf->Cell(15,8,utf8_decode($totalGeneralImporteHaber),0,0,'R',1);
		$pdf->Cell(15,8,utf8_decode($totalGeneralImporteDeudor),0,0,'R',1);
		$pdf->Cell(15,8,utf8_decode($totalGeneralImporteAcreedor),0,0,'R',1);  
		// $pdf->AddPage('P', 'Letter'); 
		// $pdf->setX(5); 
		// $pdf->opcion_pie='FOOTER_VACIO';
		$pdf->Footer();
		$pdf->Output('I',utf8_decode('ReporteComprobante.pdf')); 
	}
}
