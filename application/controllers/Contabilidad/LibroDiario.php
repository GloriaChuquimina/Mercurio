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
		$dato['nombre_sistema']  = "MERCURIO";
		$dato['tipo_sistema']  = "Sistema Contable";
		
		
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
		$tipo_comprobante      = $this->input->post('tipo_comprobante');
		$numero_inicio         = $this->input->post('numero_inicio');
		$numero_fin            = $this->input->post('numero_fin');

		
		// $libroDiarioComprobante = $this->LibroDiario_model->getLibroDiarioComprobantesPorRango($id_entidad,$fecha_desde,$fecha_hasta);
		if($tipo_comprobante == -1)
		{
			$libroDiarioComprobante = $this->LibroDiario_model->getLibroDiarioComprobantesPorRango($id_entidad,$fecha_desde,$fecha_hasta);
			// echo("<pre>");
			// print_r($libroDiarioComprobante);
			// echo("</pre>");
			// die();
		}
		else
		{
			if(!empty($numero_inicio) && !empty($numero_fin))
			{
				$libroDiarioComprobante = $this->LibroDiario_model->getLibroDiarioComprobantesPorRangoByTipoNumero($id_entidad,$fecha_desde,$fecha_hasta,$tipo_comprobante,$numero_inicio,$numero_fin);
			}
			else
			{
				$libroDiarioComprobante = $this->LibroDiario_model->getLibroDiarioComprobantesPorRangoByTipo($id_entidad,$fecha_desde,$fecha_hasta,$tipo_comprobante);
			}
		}
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
		foreach ($libroDiarioComprobante as $comprobante)
		{   

			$id_comprobante 	 = $comprobante->id_comprobante;
			$fecha_comprobante   = formato_fecha_slash($comprobante->fecha_comprobante);
			// $tipo_comprobante    = $comprobante->tipo_comprobante;
			$tipo_comprobante    = "COMPROBANTE DE ".getValor2Configuraciones("TIPO COMPROBANTES CONTABLE",  $comprobante->tipo_comprobante);
			$glosa_comprobante   = $comprobante->glosa_comprobante;
			$numero_correlativo  = " Nro.:".$comprobante->correlativo;
			$detalle_comprobante = $tipo_comprobante." ".$numero_correlativo ;


			$tr.="<tr style='background-color:rgb(209, 226, 239); font-weight: bold;'>
					<td>
						".$fecha_comprobante."
					</td>
					<td>
						".$detalle_comprobante."
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
	function ReporteLibroDiarioPDF($id_entidad,$fecha_desde,$fecha_hasta,$tipo_comprobante,$numero_inicio,$numero_fin)
	{			
			
		$this->load->library('fpdf/pdf2');
		$pdf = new Pdf2();
		$pdf->AliasNbPages();
		$pdf->SetAutoPageBreak(true, 10);
		$pdf->SetMargins(20,15,10);		
		$pdf->SetTitle(utf8_decode("Reporte Libro Diario"));
		$pdf->entidad=descripcion_nombre_entidad($id_entidad);
		$pdf->sigla=sigla_entidad($id_entidad);
		$pdf->tituloCabecera = 'LIBRO DIARIO';
		$pdf->subtituloCabecera1 = "Entre el ".formato_fecha_slash($fecha_desde). " y ".formato_fecha_slash($fecha_hasta);  
		$pdf->subtituloCabecera2 = "Expresado en Bolivianos";  
		$w = array(15,115,40,50);
		$pdf->setWidthsG($w);
		$pdf->SetAligns(array('C','L','C','C'));
		$pdf->AddPage('P','Letter');
		$pdf->opcion_cabecera=6;
		$pdf->Header();
		$pdf->opcion_pie	= 'FOOTER_LIBRODIARIO';
		$pdf->SetFillColor(255,255,255);
		$pdf->SetTextColor(0);
		$pdf->SetFont('Arial','',8);

		// Obtener datos
		if($tipo_comprobante == -1){
			$libroDiarioComprobante = $this->LibroDiario_model->getLibroDiarioComprobantesPorRango($id_entidad,$fecha_desde,$fecha_hasta);
		}
		else{
			if(!empty($numero_inicio) && !empty($numero_fin))
			{
				$libroDiarioComprobante = $this->LibroDiario_model->getLibroDiarioComprobantesPorRangoByTipoNumero($id_entidad,$fecha_desde,$fecha_hasta,$tipo_comprobante,$numero_inicio,$numero_fin);
			}
			else
			{
				$libroDiarioComprobante = $this->LibroDiario_model->getLibroDiarioComprobantesPorRangoByTipo($id_entidad,$fecha_desde,$fecha_hasta,$tipo_comprobante);
			}
		}

		$contador_registros = count($libroDiarioComprobante);

		$importeDebePagina = 0;
		$importeHaberPagina = 0;

		if($contador_registros > 0)
		{
			$totalGeneralImporteDebe  = 0;
			$totalGeneralImporteHaber = 0;
			// $importeDebePagina = 0;
			// $importeHaberPagina = 0;
			// $totalDebe   = 0;
			// $totalHaber  = 0;

			// POSICIONES
			$x_inicial 		 = 10;
			$col_fecha 		 = 30;
			$col_descripcion = 130;
			$col_debe 		 = 20;
			$col_haber 		 = 20;

			// CONTROL DE PÁGINAS
			$pagina_actual = $pdf->PageNo();
			$pdf->setY(53); 
			$y_inicio_pagina = $pdf->GetY();
			$y_line_end = 255; // límite para dibujar líneas en páginas completas

			// helper para dibujar las líneas verticales de una página
			$draw_page_lines = function($y_start, $y_end) use ($pdf, $x_inicial, $col_fecha, $col_descripcion, $col_debe, $col_haber) {
				$pdf->Line($x_inicial,              $y_start, $x_inicial,              $y_end);
				$pdf->Line($x_inicial + $col_fecha, $y_start, $x_inicial + $col_fecha, $y_end);
				$pdf->Line($x_inicial + $col_fecha + $col_descripcion, $y_start, 
						$x_inicial + $col_fecha + $col_descripcion, $y_end);
				$pdf->Line($x_inicial + $col_fecha + $col_descripcion + $col_debe, $y_start, 
						$x_inicial + $col_fecha + $col_descripcion + $col_debe, $y_end);
				$pdf->Line($x_inicial + $col_fecha + $col_descripcion + $col_debe + $col_haber, $y_start, 
						$x_inicial + $col_fecha + $col_descripcion + $col_debe + $col_haber, $y_end);
			};


			// Helper para manejar salto de página
			$handle_page_break = function() use ($pdf, &$pagina_actual, &$y_inicio_pagina, &$importeDebePagina, &$importeHaberPagina, $draw_page_lines, $y_line_end) {
				$draw_page_lines($y_inicio_pagina, $y_line_end);
				// Actualizar totales acumulados en el PDF antes de cambiar página
				$pdf->paginaDebe  = $importeDebePagina;
				$pdf->paginaHaber = $importeHaberPagina;
				
				$pdf->AddPage('P','Letter');
				$pagina_actual = $pdf->PageNo();
				$y_inicio_pagina = 53;
				$pdf->SetY($y_inicio_pagina);
				
				// Reiniciar acumuladores para la nueva página
				$importeDebePagina = 0;
				$importeHaberPagina = 0;
			};
			// Recorrer comprobantes
			foreach ($libroDiarioComprobante as $comprobante)
			{
				// Si la página cambió automáticamente (por header u otro), dibujar las líneas de la página anterior
				if ($pdf->PageNo() != $pagina_actual) {
					$handle_page_break();
				}

				// ENCABEZADO DEL COMPROBANTE
				$totalDebe   = 0;
				$totalHaber  = 0;
				$id_comprobante 	 = $comprobante->id_comprobante;
				$fecha_comprobante   = formato_fecha_slash($comprobante->fecha_comprobante);
				$tipo_comprobante    = "COMPROBANTE DE ".getValor2Configuraciones("TIPO COMPROBANTES CONTABLE",  $comprobante->tipo_comprobante);
				$numero_correlativo  = " Nro.:".$comprobante->correlativo;
				$glosa_comprobante   = $comprobante->glosa_comprobante;
				$detalle_comprobante = $tipo_comprobante." ".$numero_correlativo ;	

				// Si no hay espacio suficiente para el encabezado, antes de agregar página dibujar líneas y luego nueva página
				if ($pdf->GetY() > 250) {
					$handle_page_break();
				}

				$pdf->SetFont('Arial', 'B', 7);
				$pdf->SetFillColor(230, 230, 225);
				$pdf->SetX($x_inicial);
				$pdf->Cell($col_fecha, 5, utf8_decode($fecha_comprobante), 0, 0, 'C', 1);
				$pdf->Cell($col_descripcion, 5, "-----" . utf8_decode($detalle_comprobante) . "-----", 0, 0, 'C', 1);
				$pdf->Cell($col_debe, 5, "", 0, 0, 'R', 1);
				$pdf->Cell($col_haber, 5, "", 0, 1, 'R', 1);

				$datosComprobante = $this->Comprobantes_model->getDetalleComprobanteByIdComprobante($id_comprobante);
				if($datosComprobante)	
				{
					$pdf->SetFillColor(255,255,255);
					$pdf->SetFont('Arial', '', 7);
					$pdf->SetWidths([$col_fecha, $col_descripcion, $col_debe, $col_haber]);
					$pdf->SetAligns(['L','L','R','R']);
					foreach ($datosComprobante as $detalle_comprobante) 
					{
						// Si falta espacio para la línea, dibujar líneas de la página y agregar nueva página antes de continuar
						if ($pdf->GetY() > 250) {
							$handle_page_break();
						}

						$pdf->SetX($x_inicial);
						
						// $importeDebe = 0;
						// $importeHaber= 0;
						
						$id_entidad      		   = $detalle_comprobante->id_entidad;
						$id_comprobante 		   = $detalle_comprobante->id_comprobante;
						$id_cuenta     		       = $detalle_comprobante->id_cuenta;
						$tipo_movimiento	       = $detalle_comprobante->tipo_movimiento;
						$importe_moneda_nacional   = $detalle_comprobante->importe_moneda_nacional;
						$glosa_cuenta     	       = $detalle_comprobante->glosa_cuenta;
						$codigo_cuenta			   = getCodigoCuenta($id_cuenta);
						$descripcion_cuenta		   = getCuenta($id_cuenta);
						
						$importeDebe = $detalle_comprobante->tipo_movimiento == "DB" ? $detalle_comprobante->importe_moneda_nacional : 0;
						$importeHaber = $detalle_comprobante->tipo_movimiento == "HB" ? $detalle_comprobante->importe_moneda_nacional : 0;
						// $importeDebePagina += $importeDebe;
						// $importeHaberPagina += $importeHaber;
						
						$fila = array(
							$codigo_cuenta,
							utf8_decode($descripcion_cuenta),
<<<<<<< HEAD
							number_format($importeDebe, 2, ',', '.'),
							number_format($importeHaber, 2, ',', '.')
=======
							$importeDebe ,
							$importeHaber 
>>>>>>> 86e86a998fd13133562672039854ed148e2211bf
						);	

						$pdf->Row_SinLinea($fila,true, '', 4);
						$totalDebe     += $importeDebe;
						$totalHaber    += $importeHaber;	
						// Acumular en totales de página
						$importeDebePagina  += $importeDebe;
						$importeHaberPagina += $importeHaber;				
					}
				}	
				// PIE DEL COMPROBANTE (TOTALES) - controlar salto de página antes de imprimir
				if ($pdf->GetY() > 250) {
					$handle_page_break();
				}

				$pdf->SetX($x_inicial);
				$pdf->SetWidths([$col_fecha, $col_descripcion, $col_debe, $col_haber]);

				$pdf->SetAligns(['L','L','R','R']);
				$pdf->SetFillColor(230, 230, 225);
				$pdf->SetFont('Arial', 'B', 7);
				$fila_pie_comprobante=array (
												"----",
												utf8_decode($glosa_comprobante),
<<<<<<< HEAD
												number_format($totalDebe, 2, ',', '.'),
												number_format($totalHaber, 2, ',', '.')
=======
												$totalDebe,
												$totalHaber
>>>>>>> 86e86a998fd13133562672039854ed148e2211bf
											);

				$pdf->Row_SinLinea($fila_pie_comprobante,true, '', 5);
				$totalGeneralImporteDebe  = $totalGeneralImporteDebe+$totalDebe;
				$totalGeneralImporteHaber = $totalGeneralImporteHaber+$totalHaber;
			} 	


			// Dibujar líneas de la última página hasta la posición final
			$y_fin_final = $pdf->GetY();
			$draw_page_lines($y_inicio_pagina, $y_fin_final);

			// Actualizar totales finales de página
			$pdf->paginaDebe  = $importeDebePagina;
			$pdf->paginaHaber = $importeHaberPagina;

			// Totales generales para el pie
			$pdf->mostrar_total_general = true;
			$pdf->totalLD_Debe  = $totalGeneralImporteDebe;
			$pdf->totalLD_Haber = $totalGeneralImporteHaber;
		}
		else{
			$pdf->opcion_pie	= 'FOOTER_SIN_MOVIMIENTO_LD';
			$pdf->Footer();
		}
		$pdf->Output('I',utf8_decode('ReporteLibroDiario.pdf')); 
	}


}
