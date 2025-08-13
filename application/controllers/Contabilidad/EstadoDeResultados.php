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
    private function ordenarJerarquicamenteCuentasOrdenEstadoDeResultados(array $cuentas, int $padreId = 0, int $indentacion = 0,bool $excluirDesdeNivelDos=false,int $nivelMaximo = null)
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
					$this->ordenarJerarquicamenteCuentasOrdenEstadoDeResultados(
						$cuentas, 
						$cuenta['id'], 
						$indentacion + 1,
						$excluirDesdeNivelDos,
						$nivelMaximo
					);

				// --- Sumar importe propio + importe hijos -------------------------
				$importePropio              = isset($cuenta['saldo'])
												? (float) $cuenta['saldo']
												: 0;
				$importePropio              = isset($cuenta['saldUSD'])
												? (float) $cuenta['saldoUSD']
												: 0;



				// Si el nivel máximo está definido y la cuenta está en ese nivel máximo, sumamos saldo hijos
				if ($nivelMaximo !== null && isset($cuenta['nivel']) && $cuenta['nivel'] == $nivelMaximo) {
					$saldoConHijos = $importePropio + $sumaHijos;
				} else {
					// No sumamos hijos, solo saldo propio
					$saldoConHijos = $importePropio;
				}

				$cuenta['saldo_cuenta'] = $saldoConHijos;
				$cuenta['importe_total'] = $saldoConHijos;

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
		$fecha_fin             = $this->input->post('fecha_fin');
		// $id_cuenta             = $this->input->post('id_cuenta');
		$moneda                  = $this->input->post('moneda');
		$nivel				     = $this->input->post('nivel');
		$saldoCero  			 = $this->input->post('saldoCero');
		$cuentasSeleccionadas    = $this->input->post('cuentasSeleccionadas');

		$id_cuenta_ingreso   	   = 40;
		$codigo_cuenta_ingreso     = 4;
		$id_cuenta_egreso   	   = 41;
		$codigo_cuenta_egreso      = 5;

		if($nivel== 0)
		{
			$nivel=getNivelMaximo();
		}

		if($saldoCero == "true")
		{
			$andCuentasCeroAcreedor    = "";
			$andCuentasCeroDeudor      = "";
			$excluirCuentasEnCero      = false;
		}
		else
		{
			$excluirCuentasEnCero      = true;
			$andCuentasCeroAcreedor    = "AND saldo_acreedor <> 0";
			$andCuentasCeroDeudor      = "AND saldo_deudor <> 0";
		}
		
		$estadoResultadoIngreso    = $this->EstadoDeResultado_model->getEstadoDeResultadosIngreso($id_entidad,$fecha_inicio,$fecha_fin,$id_cuenta_ingreso,$codigo_cuenta_ingreso,$andCuentasCeroAcreedor);
		$cuentasIngreso  	       = json_decode(json_encode($estadoResultadoIngreso), true);		
		$ordenadas_cuentas_ingreso = $this->ordenarJerarquicamenteCuentasOrdenEstadoDeResultados($cuentasIngreso ,0,0,$excluirCuentasEnCero,$nivel);
		$cuentasOrdenadasIngreso   = $ordenadas_cuentas_ingreso[0];
		$sumaTotalGlobalIngreso    = $ordenadas_cuentas_ingreso[1];

		// $resultado    = $this->EstadoDeResultado_model->getMontoResultado($id_entidad,$fecha_desde,$fecha_hasta);
		$resultado    = $this->EstadoDeResultado_model->getMontoResultado($id_entidad,$fecha_inicio,$fecha_fin,$id_cuenta_ingreso,$codigo_cuenta_ingreso,$id_cuenta_egreso,$codigo_cuenta_egreso);
		if (!empty($resultado) && isset($resultado[0]->total_estado_resultado)) {
			$total_resultado = $resultado[0]->total_estado_resultado;
		} else {
			$total_resultado = 0; 
		}
		$totalSaldoAcreedor =0;


		// echo("<pre>");
		// print_r($ordenadas_cuentas_acreedor);
		// echo("</pre>");
		// die();

		foreach ($cuentasOrdenadasIngreso as $cuenta)
		{   
			$codigo 		 = $cuenta['codigo'];

			$descripcion     = $cuenta['descripcion'];
			
			if($moneda === 'BOB'){
				$saldoAcreedor	 = $cuenta['saldo'];
			}
			elseif ($moneda === 'USD') {
				$saldoAcreedor	 = $cuenta['saldoUSD'];
			} 

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
		                  "nro_registros" => count($cuentasOrdenadasIngreso) , 
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
		$fecha_fin             = $this->input->post('fecha_fin');
		// $id_cuenta             = $this->input->post('id_cuenta');
		$moneda                = $this->input->post('moneda');
		$nivel				   = $this->input->post('nivel');
		$saldoCero  			 = $this->input->post('saldoCero');
		$cuentasSeleccionadas    = $this->input->post('cuentasSeleccionadas');
		$id_cuenta_ingreso   	   = 40;
		$codigo_cuenta_ingreso     = 4;
		$id_cuenta_egreso   	   = 41;
		$codigo_cuenta_egreso     = 5;


		if($saldoCero == "true")
		{
			$andCuentasCeroAcreedor    = "";
			$andCuentasCeroDeudor      = "";
			$excluirCuentasEnCero      = false;
		}
		else
		{
			$excluirCuentasEnCero      = true;
			$andCuentasCeroAcreedor    = "AND saldo_acreedor <> 0";
			$andCuentasCeroDeudor      = "AND saldo_deudor <> 0";
		}

		$estadoResultadoEgreso     = $this->EstadoDeResultado_model->getEstadoDeResultadosEgreso($id_entidad,$fecha_inicio,$fecha_fin,$id_cuenta_egreso,$codigo_cuenta_egreso,$andCuentasCeroDeudor );
		echo("<pre>");
		print_r($estadoResultadoEgreso);
		echo("<pre>");
		$cuentasDeEgreso		   = json_decode(json_encode($estadoResultadoEgreso), true);		
		$ordenadas_cuentas_egreso  = $this->ordenarJerarquicamenteCuentasOrdenEstadoDeResultados($cuentasDeEgreso ,0,0,$excluirCuentasEnCero,$nivel);
		$cuentasOrdenadasEgreso    = $ordenadas_cuentas_egreso[0];
		$sumaTotalGlobalEgreso     = $ordenadas_cuentas_egreso[1];
		echo("<pre>");
		print_r($ordenadas_cuentas_egreso);
		echo("</pre>");
		die();
		
		
		// $resultado    = $this->EstadoDeResultado_model->getMontoResultado($id_entidad,$fecha_desde,$fecha_hasta);
		$resultado    = $this->EstadoDeResultado_model->getMontoResultado($id_entidad,$fecha_inicio,$fecha_fin,$id_cuenta_ingreso,$codigo_cuenta_ingreso,$id_cuenta_egreso,$codigo_cuenta_egreso);


		if (!empty($resultado) && isset($resultado[0]->total_estado_resultado)) {
			$total_resultado = $resultado[0]->total_estado_resultado;
		} else {
			$total_resultado = 0; 
		}
		$totalSaldoDeudor =0;

		foreach ($cuentasOrdenadasEgreso as $cuenta)
		{   
			$codigo 		 = $cuenta['codigo'];
			$descripcion     = $cuenta['descripcion'];
			
			if($moneda === 'BOB'){
				$totalDeudor	 = $cuenta['saldo'];
			}
			elseif ($moneda === 'USD') {
				$totalDeudor	 = $cuenta['saldoUSD'];
			} 

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
		                  "nro_registros" => count($cuentasOrdenadasEgreso) , 
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
