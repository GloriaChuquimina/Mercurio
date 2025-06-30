<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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

		$tr= "<thead class='bg-dark'>
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
							<td>
							".$importeDebe."
							</td>
							<td>
							".$importeHaber."
							</td>
							<td>
							".$importeDeudor."
							</td>
							<td>
							".$importeAcreedor."
							</td>
					  	  </tr>";				
					
				}
			}
		}
		$tr .= "</tbody>";
		// echo ($tr);
		// die();
		$output =( array(
			            "resultado" => 1, 
		              "nro_cuentas" => count($plandecuentas) , 
						    "tabla" => $tr ) );

		echo json_encode($output);
		exit();
    }
}