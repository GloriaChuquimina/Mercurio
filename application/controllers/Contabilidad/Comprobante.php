<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Comprobante extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->_is_logued_in();
        $this->load->model('Comprobantes_model');
		$this->load->helper('configuraciones_helper');
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
		$dato['nombre_sistema']  = "SISTEMA CONTABLE <BR>MERCURIO";
		
		
		$id_usuario = $this->session->userdata('id_usuario');
		$dato['rolescero'] = $this->session->userdata('rolescero');
		$dato['roles']  = $this->session->userdata('roles');
		$dato['nombre_usuario']  = $this->session->userdata('nombre_completo');

		$titulo = "LISTA DE COMPROBANTES POR ENTIDAD";		
		$dato['titulo'] = $titulo;

		$this->load->view('inicio/cabecera',$dato);
		$this->load->view('inicio/menu',$dato);
		$this->load->view('contabilidad/comprobantes',$dato);
		$this->load->view('inicio/pie');
	}
	public function registroComprobante($entidad)
	{
		//$this->load->library('googlemaps');

		$dato['nombre_usuario']  = $this->session->userdata('nombre_usuario');		
		$dato['nombre_sistema']  = "SISTEMA CONTABLE <BR>MERCURIO";
		

		$id_usuario = $this->session->userdata('id_usuario');
		$dato['rolescero'] = $this->session->userdata('rolescero');
		$dato['roles']  = $this->session->userdata('roles');
		$dato['nombre_usuario']  = $this->session->userdata('nombre_completo');
		$dato['nombre_entidad']  = descripcion_nombre_entidad($entidad);
		// $dato['nombre_entidad']  = $entidad;
		$dato['entidad']  = $entidad;

		$titulo = "REGISTRO DE COMPROBANTE CONTABLE";		
		$dato['titulo'] = $titulo;

		$this->load->view('inicio/cabecera',$dato);
		$this->load->view('inicio/menu',$dato);		
		$this->load->view('contabilidad/registro_comprobante',$dato); //cuerpo
		$this->load->view('inicio/pie');
	}
	public function cargarTablaRegistroCuenta( )
	{
		$draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

		$accion 		   = $this->input->post('accion');
		$cadRegistroCuenta = $this->input->post('cuenta');
		$id_entidad        = $this->input->post('id_entidad');
		// $tipo = $this->input->post('tipo');
		$data = array();
		$num =1;
		$importeDebe=0;
		$importeHaber=0;
		$importeDebeUs=0;
		$importeHaberUs=0;
		if($accion == "nuevo")
		{  
			$filas = explode("|", $cadRegistroCuenta);  
			foreach($filas as $fila )
			{
				if( $fila)
				{
					$row = explode("*", $fila); 
					list( $a,$b, $c,$d,$e,$f,$g) = $row;

					$codigoCuenta = explode("-",$b);
					list($h,$i)	  = $codigoCuenta;
					echo ("A->".$a);
					echo("<br>");
					echo ("B->".$b);
					echo("<br>");
					echo ("C->".$c);
					echo("<br>");
					echo ("D->".$d);
					echo("<br>");
					echo ("E->".$e);
					echo("<br>");
					echo ("F->".$f);
					echo("<br>");
					echo ("G->".$g);
					echo("<br>");
					echo ("H->".$h);
					die();
					if($d == "DB")
					{
						$importeDebe=$f;
						$importeDebeUs=$f*$h;
					}
					elseif ($d == "HB") {
						$importeHaber=$f;
						$importeHaberUs=$f*$h;
					}
					
					$boton = "<span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Baja'><button type='button' class='btn btn-danger btn-circle' onclick=\"eliminarDocumentoT('".$b."','".$c."','".$d."', '". $c."' )\"><i class='mdi mdi-delete'></i></button></span>";
					$data[] = array(
						$h,
						$i,
						$importeDebe,
						$importeHaber,
						$importeDebeUs,
						$importeHaberUs,
						$boton
			   		);
				}		
			}
		}
		else
		{
			// $filas = $this->entidaddocumento_model->getListaEntidadDocumentos($entidad, $tipo);
			// foreach($filas as $fila )
			// {
			// 	$boton = "<span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Baja'><button type='button' class='btn btn-danger btn-circle' onclick=\"eliminarDocumento('".$fila->id."' )\"><i class='mdi mdi-delete'></i></button></span>";
			// 	$data[] = array(
			// 				$num++,
			// 				$fila->documento,
			// 				$fila->numero_documento,
			// 				$fila->fecha_documento,
			// 				$boton 
		    //   	);
			// }
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
	
}