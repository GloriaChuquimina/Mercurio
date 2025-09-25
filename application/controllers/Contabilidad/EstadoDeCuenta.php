<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EstadoDeCuenta extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->_is_logued_in();
		$this->load->library('form_validation');
        $this->load->model('Comprobantes_model');
        $this->load->model('EstadoDeCuenta_model');
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
		// $data    	           = $this->input->post('datos_cuenta');

		$estadoCuenta = $this->EstadoDeCuenta_model->getEstadoDeCuenta($id_entidad,$fecha_desde,$fecha_hasta,$id_cuenta);
		// echo("<pre>");
		// print_r($estadoCuenta);
		// echo("</pre>");
		// die();
		$totalDebe =0;
		$totalHaber =0;
		$totalDeudor =0;
		$totalAcreedor =0;
		$importeDebe=0;
		$importeHaber=0;
		$importeDeudor=0;
		$importeAcreedor=0;


		// $data = array(
		// 	'id_entidad'   => $id_entidad,
		// 	'fecha_inicio' => $fecha_desde,
		// 	'fecha_fin'    => $fecha_hasta,
		// 	'id_cuenta'    => $id_cuenta
		// );
		// $validacomprobante = json_decode($this->validarDatos($data));
		// $resultado   = $validacomprobante[0]->resultado;
		// $mensaje     = $validacomprobante[0]->mensaje;
		$resultado = 1; //--- IGNORE ---
		if($resultado == 1)
		{
			foreach ($estadoCuenta as $fila)
			{   
				$codigo_aux 		 = $fila->codigo_aux;
				$descripcion_aux	 = $fila->descripcion_aux;
				$importeDebe	 	 = $fila->debe;
				$importeHaber	 	 = $fila->haber;
				$importeDeudor	 	 = $fila->saldo_deudor;
				$importeAcreedor	 = $fila->saldo_acreedor;

				$data[] = array(
					"<span class='badge badge-warning'>".$codigo_aux."</span>",
					$descripcion_aux,	
					"<div style='text-align: right; color: #28a745; font-weight: bold;'>
					".number_format($importeDebe,2,'.',',').
					"</div>",
					"<div style='text-align: right; color: #dc3545; font-weight: bold;'>
					".number_format($importeHaber,2,'.',',').
					"</div>",
					"<div style='text-align: right; color: #28a745; font-weight: bold;'>
					".number_format($importeDeudor,2,'.',',').
					"</div>",
					"<div style='text-align: right; color: #dc3545; font-weight: bold;'>
					".number_format($importeAcreedor,2,'.',',').
					"</div>",
					
				);
				$totalDebe+=$importeDebe;
				$totalHaber+=$importeHaber;
				$totalDeudor+=$importeDeudor;
				$totalAcreedor+=$importeAcreedor;
								
			}	

		}
		else
		{
			echo("Error en la validacion de datos");
		}
			
		$output =( array(
			             "     resultado" => 1, 
		                  "nro_registros" => count($estadoCuenta) , 
					          "totalDebe" => number_format($totalDebe,2,'.',','),
            		         "totalHaber" => number_format($totalHaber,2,'.',','),
            		        "totalDeudor" => number_format($totalDeudor,2,'.',','),
            		      "totalAcreedor" => number_format($totalAcreedor,2,'.',','),
						          "data" => $data ) );

		echo json_encode($output);
		exit();

	}
	function ReporteEstadoDeCuentaPDF($id_entidad,$fecha_inicio,$fecha_fin,$id_cuenta)
	{			
		/****************************/
		/*INICIO DEL REPORTE*/
		/****************************/		
		$cuenta = getCodigoCuenta($id_cuenta)."-".getCuenta($id_cuenta);
		$this->load->library('fpdf/pdf2');
        $pdf = new Pdf2();
        $pdf->AliasNbPages();
        $pdf->SetAutoPageBreak(true, 30);
        $pdf->SetMargins(20,15,10);		
		$pdf->SetTitle(utf8_decode("Reporte Estado de la Cuenta"));
		$pdf->entidad=descripcion_nombre_entidad($id_entidad);
		$pdf->sigla=sigla_entidad($id_entidad);
		$pdf->tituloCabecera = 'ESTADO DE LA CUENTA';
		$pdf->subtituloCabecera1 = $cuenta;  
		$pdf->subtituloCabecera2 = "DEL  ".formato_fecha_dia_2($fecha_inicio). " AL ".formato_fecha_dia_2($fecha_fin);  
		$pdf->subtituloCabecera3 = "Expresado en Bolivianos";  
        $w = array(15,115,40,50);
        $pdf->setWidthsG($w);
        $pdf->SetAligns(array('C','L','C','C'));
		$pdf->AddPage('P','Letter');
		$pdf->opcion_cabecera=7;
		$pdf->Header();
		$pdf->SetFillColor(255,255,255);
        $pdf->SetTextColor(0);
        $pdf->SetFont('Arial','',6);
		$pdf->Ln(1);
		$pdf->opcion_pie='FOOTER_VACIO';
		/*CUERPO DEL REPORTE*/
		$pdf->SetWidths([30, 105, 15, 15, 15, 15]);
		$pdf->SetAligns(['L','L','R','R','R','R']);
        $num = 0;
        $total=0;
		$estadoCuenta = $this->EstadoDeCuenta_model->getEstadoDeCuenta($id_entidad,$fecha_inicio,$fecha_fin,$id_cuenta);
		$totalDebe =0;
		$totalHaber =0;
		$totalDeudor =0;
		$totalAcreedor =0;
		$importeDebe=0;
		$importeHaber=0;
		$importeDeudor=0;
		$importeAcreedor=0;
		foreach ($estadoCuenta as $fila)
		{   
			$codigo_aux 		 = $fila->codigo_aux;
			$descripcion_aux	 = $fila->descripcion_aux;
			$importeDebe	 	 = $fila->debe;
			$importeHaber	 	 = $fila->haber;
			$importeDeudor	 	 = $fila->saldo_deudor;
			$importeAcreedor	 = $fila->saldo_acreedor;

			$row = array(
				$codigo_aux,
				utf8_decode($descripcion_aux),
				number_format($importeDebe,2,'.',','),
				number_format($importeHaber,2,'.',','),
				number_format($importeDeudor,2,'.',','),
				number_format($importeAcreedor,2,'.',',')

			    );
			$totalDebe+=$importeDebe;
			$totalHaber+=$importeHaber;
			$totalDeudor+=$importeDeudor;
			$totalAcreedor+=$importeAcreedor;
			$pdf->Row_Reportes_EC($row,true, '', 6);	
		} 
		$pdf->SetFont('Arial', 'B', 6);
		$pdf->SetFillColor(230, 230, 225);
		$x=15;
		$y=$pdf->GetY();
		$pdf->Line($x, $y, $x + 195, $y);
		$pdf->Line($x, $y+5, $x + 195, $y+5);
		$TOTALES="TOTAL";
		$y=$pdf->GetY();
		$pdf->SetXY(15,$y);    
		$pdf->Cell(135,5,utf8_decode($TOTALES),0,0,'C',1);
		$pdf->Cell(15,5,utf8_decode(number_format($totalDebe,2,'.',',')),0,0,'R',1);
		$pdf->Cell(15,5,utf8_decode(number_format($totalHaber,2,'.',',')),0,0,'R',1);
		$pdf->Cell(15,5,utf8_decode(number_format($totalDeudor,2,'.',',')),0,0,'R',1);
		$pdf->Cell(15,5,utf8_decode(number_format($totalAcreedor,2,'.',',')),0,0,'R',1); 

		$ini_y=55;
		$y_fin=$pdf->GetY();
		$pdf->Line(15, $ini_y, 15, $y_fin+5);
		$pdf->Line(45, $ini_y, 45, $y_fin);
		$pdf->Line(150, $ini_y, 150, $y_fin+5);
		$pdf->Line(165, $ini_y, 165, $y_fin+5);
		$pdf->Line(180, $ini_y, 180, $y_fin+5);
		$pdf->Line(195, $ini_y, 195, $y_fin+5);
		$pdf->Line(210, $ini_y, 210, $y_fin+5);


		$pdf->Footer();
		$pdf->Output('I',utf8_decode('ReporteEstadoDeCuenta.pdf')); 
	}

	function validarDatos($data)
	{

 		// $txtAccion 	= $data['txtAccionComprobante'];
		// $idEntidad  = $data['id_entidad'];
		// $fecha      = $data['txtFecha'];

		// $verifica =$this->verificarEntidad($idEntidad);
		// $verifica_fecha = $this->fecha_valida($fecha);

		echo json_encode($data);
 		$this->form_validation->set_data($data);
 		$resul = 1;
		$mensaje = "OK";	


		if($this->form_validation->run('validar_estado_cuenta'))
		{
			$resul = 1;
			$mensaje = "OK";
		}
		else
		{
			// $filas = explode("|", $detalleComprobante);
			$resul = 0;
			$mensaje = json_encode($this->form_validation->get_errores_arreglo());
			$mensaje = formaterarValidacion($mensaje);
		}

		
		$resultado ='[{								
					"resultado":"'.$resul.'",
					"mensaje":"'.$mensaje.'"
					}]';

		return $resultado; 		

	}



}
