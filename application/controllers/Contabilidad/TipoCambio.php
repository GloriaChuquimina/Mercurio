<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TipoCambio extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->_is_logued_in();
        $this->load->model('TipoCambio_model');
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

		$titulo = "Tipo Cambio";		
		$dato['titulo'] = $titulo;

		$this->load->view('inicio/cabecera',$dato);
		$this->load->view('inicio/menu',$dato);
		$this->load->view('contabilidad/tipocambio',$dato);
		$this->load->view('inicio/pie');
	}
	public function cargarTipoCambio()
	{
		$id_usuario = $this->session->userdata('id_usuario');
		$filas   = $this->TipoCambio_model->getTipoCambio();
		// echo json_encode($filas);
		$draw    = intval($this->input->get("draw"));
		$start   = intval($this->input->get("start"));
		$length  = intval($this->input->get("length"));	
		$data    = array();
		$num     = 1;

		foreach ($filas as $fila)
		{   
			// $boton   = "
            //             <span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Editar'>
            //                 <button type='button' class='btn btn-block btn-warning btn-sm' onclick=\"editarEntidad(". $fila->id . ",'".$fila->sigla."','".$fila->nombre."')\"><i class='fas fa-edit'></i></button>     
            //             </span>	
            //             <span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Eliminar'>
            //                 <button type='button' class='btn btn-block btn-danger btn-sm' onclick='bajaEntidad(". $fila->id . ")'><i class='fas fa-trash-alt'></i></button>     
            //             </span>	
            //             ";		
			$boton   = "<span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Editar'>
                            <button type='button' class='btn btn-block btn-warning btn-sm' onclick=\"editarTipoCambio(". $fila->id.")\"><i class='fas fa-edit'></i></button>     
                        </span>	
                        <span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Eliminar'>
                            <button type='button' class='btn btn-block btn-danger btn-sm' onclick='elimnarTipoCambio(". $fila->id. ")'><i class='fas fa-trash-alt'></i></button>     
                        </span>	";		

			$data[] = array(
				// formato_fecha($fila->fecha),
				$fila->fecha,
				number_format($fila->valor,2,'.',','),			
				$fila->estado,
				$boton
			);
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