<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LibroDiario extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->_is_logued_in();
        $this->load->model('PlanDeCuentas_model');
        $this->load->model('LibroDiario_model');
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
		$dato['nombre_sistema']  = "SISTEMA CONTABLE<BR>MERCURIO";
		
		
		$id_usuario = $this->session->userdata('id_usuario');
		$dato['rolescero'] = $this->session->userdata('rolescero');
		$dato['roles']  = $this->session->userdata('roles');
		$dato['nombre_usuario']  = $this->session->userdata('nombre_completo');

		$titulo = "Libro Diario";		
		$dato['titulo'] = $titulo;

		$this->load->view('inicio/cabecera',$dato);
		$this->load->view('inicio/menu',$dato);
		$this->load->view('contabilidad/librodiario',$dato);
		$this->load->view('inicio/pie');
	}
	public function cargarDatosLibroDiario()
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

		$libroDiarioComprobante = $this->LibroDiario_model->getLibroDiarioComprobantesPorRango($id_entidad,$fecha_desde,$fecha_hasta);
		$totalDebe =0;
		$totalHaber =0;
		$importeDebe=0;
		$importeHaber=0;
		$importe_moneda_nacional=0;
		$importe_moneda_extranjera=0;
		//  $tr .= "<tbody>";
		 $tr = "";

		foreach ($libroDiarioComprobante as $comprobante)
		{   

			$id_comprobante 	 = $comprobante->id_comprobante;
			$fecha_comprobante   = formato_fecha_slash($comprobante->fecha_comprobante);
			// $tipo_comprobante    = $comprobante->tipo_comprobante;
			$tipo_comprobante    = "COMPROBANTE DE ".getValor2Configuraciones("TIPO COMPROBANTES CONTABLE",  $comprobante->tipo_comprobante);
			$glosa_comprobante   = $comprobante->glosa_comprobante;

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
								$importeDebe=number_format($importe_moneda_nacional,2,'.',''); ;
							}
							elseif ($tipo_movimiento == "HB") {
								$importeHaber=number_format($importe_moneda_nacional,2,'.',''); ;
							}
							$tr.="<tr>
									<td>
									".$codigo_cuenta."
									</td>
									<td>
									".$descripcion_cuenta."
									</td>
									<td style='text-align: right; color: #28a745; font-weight: bold;'>
									".$importeDebe."
									</td>
									<td style='text-align: right;  color: #dc3545; font-weight: bold;'>
									".$importeHaber."
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
						".$totalDebe."
						</td>
						<td style='text-align: right'>
						".$totalHaber."
						</td>
					 </tr>";
			}			

		}

		// $tr .= "</tbody>";
		
		$output =( array(
			            "resultado" 	 => 1, 
		              "nro_comprobantes" => count($libroDiarioComprobante) , 
						    "tabla" 	 => $tr ) );

		echo json_encode($output);
		exit();

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
	public function listarPlanDeCuentasBusqueda()
    {

		$marcarRegistro       = $this->input->post('marcareg');
		$cuentasSeleccionadas = $this->input->post('cuentasSeleccionadas');
		$cuentas   = $this->PlanDeCuentas_model->getPlanDeCuentasBusqueda();
		$totalCuentas = count($cuentas);
		$cuentas = json_decode(json_encode($cuentas), true);
		$ordenadas = $this->ordenarJerarquicamente($cuentas);
		
		$draw    = intval($this->input->get("draw"));
		$start   = intval($this->input->get("start"));
		$length  = intval($this->input->get("length"));	
		$data    = array();
		$num     = 1;
		
		foreach ($ordenadas as $fila)
		{   
			$cuenta= $fila['codigo']."-". $fila['descripcion'];
			$boton   = "
                        <span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Seleccionar'>
                            <button type='button' class='btn btn-success btn-sm' onclick=\"busquedaIDCuenta(".$fila['id'].",'".$cuenta."')\"><i>✓</i></button>     
                        </span>				
                        ";		

			$indentacion = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $fila['indentacion']);
			$descripcion = $fila['descripcion'];
			$codigo      = $fila['codigo'];
			$nivel       = $fila['nivel'];

			if (($fila['es_padre']) && ($fila['indentacion']== 0)) {
				$descripcion = "<strong><u>{$descripcion}</u></strong>";
				$codigo =  "<strong><u>{$codigo}</u></strong>";
			}
			$valor=$fila['id'];
			$nameId1 = "chkCuenta_".$valor;

			$cuentasBuscadas = explode("-", $cuentasSeleccionadas);
			// echo("<pre>");
			// echo(count($cuentasSeleccionadas));
			// echo("<br>");
			// echo($totalCuentas);
			// echo("</pre>");
			// die();
			if($marcarRegistro == 1)
			{
				$checkedCom = "checked";	
			}
			else
			{
				
				if($marcarRegistro == 0 && (count($cuentasBuscadas)-1) == $totalCuentas)
				{
					$checkedCom = "";	
				}
				else
				{
					if(count($cuentasBuscadas)>0)
					{
						foreach($cuentasBuscadas as $cuenta)
						{
							if($cuenta == $fila['id'])
							{
								$checkedCom = "checked";
								break;
							}
							else
							{
								$checkedCom = "";
							}
						}
					}
					else
					{
						$checkedCom = "";
					}
				}
				
			}
			
			$seleccion ="<input type='checkbox' value='".$valor."' name = '".$nameId1."' id='".$nameId1."' ".$checkedCom." >";
			$data[] = array(
				$seleccion,
				"<div style='text-align: center;'>$boton</div>",
				"<span class='badge badge-secondary'>".$codigo."</span>",
				$descripcion,
				$nivel
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
	function ReporteLibroDiarioPDF($id_entidad,$fecha_desde,$fecha_hasta)
	{			
			
		$this->load->library('fpdf/pdf2');
        $pdf = new Pdf2();
        $pdf->AliasNbPages();
        $pdf->SetAutoPageBreak(true, 30);
        $pdf->SetMargins(20,15,10);		
		$pdf->SetTitle(utf8_decode("Reporte Libro Diario"));
		$pdf->entidad=descripcion_nombre_entidad($id_entidad);
		$pdf->sigla=sigla_entidad($id_entidad);
		$pdf->tituloCabecera = 'LIBRO DIARIO';
		// $pdf->subtituloCabecera1 = "Entre el ".formato_fecha_slash($fecha_inicio). " y ".formato_fecha_slash($fecha_fin);  
		$pdf->subtituloCabecera2 = "Expresado en Bolivianos";  
        $w = array(15,115,40,50);
        $pdf->setWidthsG($w);
        $pdf->SetAligns(array('C','L','C','C'));
		$pdf->AddPage('P','Letter');
		$pdf->opcion_cabecera=6;
		$pdf->Header();
		$pdf->SetFillColor(255,255,255);
        $pdf->SetTextColor(0);
        // $pdf->SetFont('Arial','',6);
		// $pdf->Ln(1);
		/*CUERPO DEL REPORTE*/
		$pdf->SetFillColor(255,255,255);
        $pdf->SetTextColor(0);
        $pdf->SetFont('Arial','',8);
        $num = 0;
        $total=0;

		$libroDiarioComprobante = $this->LibroDiario_model->getLibroDiarioComprobantesPorRango($id_entidad,$fecha_desde,$fecha_hasta);
		$totalDebe   = 0;
		$totalHaber  = 0;
		$importeDebe = 0;
		$importeHaber= 0;
		$totalGeneralImporteDebe  = 0;
		$totalGeneralImporteHaber = 0;
		$pdf->setY(55); 
        foreach ($libroDiarioComprobante as $comprobante)
		{   

			$totalDebe   = 0;
			$totalHaber  = 0;
			$id_comprobante 	 = $comprobante->id_comprobante;
			$fecha_comprobante   = formato_fecha_slash($comprobante->fecha_comprobante);
			$tipo_comprobante    = "COMPROBANTE DE ".getValor2Configuraciones("TIPO COMPROBANTES CONTABLE",  $comprobante->tipo_comprobante);
			$numero_correlativo  = " Nro.:".$comprobante->correlativo;
			$glosa_comprobante   = $comprobante->glosa_comprobante;

			$detalle_comprobante = $tipo_comprobante." ".$numero_correlativo ;	
			$fila = array(
				$fecha_comprobante,
				$detalle_comprobante,
				'---',
				'---'
			);	
			$y_ini=53;
			$pdf->SetFont('Arial', 'B', 8);
			$pdf->setX(10); 
			$pdf->Cell(30,8,utf8_decode($fecha_comprobante),0,0,'C',1);
			$pdf->Cell(140,8,"-----".utf8_decode($detalle_comprobante)."-----",0,0,'C',1);
			$pdf->Cell(15,8,utf8_decode(" "),0,0,'C',1);
			$pdf->Cell(15,8,utf8_decode(" "),0,0,'C',1);
			$pdf->Ln();
			$datosComprobante    = $this->Comprobantes_model->getDetalleComprobanteByIdComprobante($id_comprobante);
			if($datosComprobante)	
			{
				$pdf->SetFillColor(255,255,255);
				$pdf->SetFont('Arial', '', 8);
				$y=$pdf->GetY();
				$pdf->SetXY(10,$y);
				$pdf->SetWidths([30, 140, 20, 20]);
				$pdf->SetAligns(['L','L','R','R']);
				foreach ($datosComprobante as $detalle_comprobante) 
				{			
						$importeDebe = 0;
						$importeHaber= 0;
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
						$fila = array(
							$codigo_cuenta,
							$descripcion_cuenta,
							$importeDebe ,
							$importeHaber 
						);	
						// $pdf->setX(5); 
						$y=$pdf->GetY();
						$pdf->SetXY(10,$y);
						$pdf->Cell(30,5,utf8_decode($codigo_cuenta),0,0,'L',1);
						$pdf->Cell(130,5,utf8_decode($descripcion_cuenta),0,0,'L',1);
						$pdf->Cell(20,5,number_format($importeDebe,2,',','.'),0,0,'R',1);
						$pdf->Cell(20,5,number_format($importeHaber,2,',','.'),0,0,'R',1);						
						$pdf->Ln();
						$totalDebe     = $totalDebe+$importeDebe;
						$totalHaber    = $totalHaber+$importeHaber;					
				}
			}	
			$pdf->SetFont('Arial', 'B', 8);
			$y=$pdf->GetY();
			$x=10;
			$pdf->SetXY(10,$y);   
			$pdf->MultiCell(30, 8, utf8_decode(" "), 0, 'C', 1);
			$pdf->SetXY($x + 30, $y); // Mover a la derecha
			$pdf->MultiCell(130, 8, utf8_decode($glosa_comprobante), 0, 'L', 1);
			$pdf->SetXY($x + 160, $y); // 30 + 130
			$pdf->MultiCell(20, 8, utf8_decode(number_format($totalDebe,2,',','.')), 0, 'R', 1);
			// Coordenadas finales
			$x_line=175;
			$finalY = $y + 8; // altura de la celda
			$pdf->SetDrawColor(0, 0, 0); 
			$pdf->SetLineWidth(0.2); 
			$pdf->Line($x_line, $finalY - 1.5, $x_line + 14, $finalY - 1.5); 
			$pdf->Line($x_line, $finalY - 0.8, $x_line + 14, $finalY - 0.8); 
			$pdf->SetXY($x + 180, $y); 
			$pdf->MultiCell(20, 8, utf8_decode(number_format($totalHaber,2,',','.')), 0, 'R', 1);
			// Coordenadas finales
			$x_line=195;
			$finalY = $y + 8; // altura de la celda
			$pdf->SetDrawColor(0, 0, 0); 
			$pdf->SetLineWidth(0.2); 
			$pdf->Line($x_line, $finalY - 1.5, $x_line + 14, $finalY - 1.5); 
			$pdf->Line($x_line, $finalY - 0.8, $x_line + 14, $finalY - 0.8); 
			$pdf->SetXY($x + 180, $y); 
			$pdf->Ln();
			$totalGeneralImporteDebe  = $totalGeneralImporteDebe+$totalDebe;
			$totalGeneralImporteHaber = $totalGeneralImporteHaber+$totalHaber;
	    } 	
		$x=10;
		$y=$pdf->GetY();
		$y_fin=$pdf->GetY();
		$pdf->Line($x, $y_ini, $x, $y_fin);	
		$pdf->Line($x+30,  $y_ini, $x+30, $y_fin);	
		$pdf->Line($x+160, $y_ini, $x+160, $y_fin);	
		$pdf->Line($x+180, $y_ini, $x+180, $y_fin);	
		$pdf->Line($x+200, $y_ini, $x+200, $y_fin);	
		// $pdf->setXY(10,$y);
		// $TOTALES="TOTALES";		    
		// $pdf->Cell(160,8,utf8_decode($TOTALES),1,0,'R',1);
		// $pdf->Cell(20,8,utf8_decode(number_format($totalGeneralImporteDebe,2,',','.')),1,0,'R',1);
		// $pdf->Cell(20,8,utf8_decode(number_format($totalGeneralImporteHaber,2,',','.')),1,0,'R',1);
		// // $y=$pdf->GetY();
		// $pdf->Line($x, $y+8, $x + 200, $y+8);
		$pdf->opcion_pie	= 'FOOTER_LIBRODIARIO';
		$pdf->totalLD_Debe  = $totalGeneralImporteDebe;
		$pdf->totalLD_Haber = $totalGeneralImporteHaber;
		$pdf->Footer();
		// $pdf->AddPage('P', 'Letter'); 
		$pdf->Output('I',utf8_decode('ReporteLibroDiario.pdf')); 
	}
}
