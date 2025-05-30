<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PlanDeCuentas extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->_is_logued_in();
        $this->load->model('PlanDeCuentas_model');
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

		$titulo = "PLAN DE CUENTAS";		
		$dato['titulo'] = $titulo;

		$this->load->view('inicio/cabecera',$dato);
		$this->load->view('inicio/menu',$dato);
		$this->load->view('contabilidad/plandecuentas',$dato);
		$this->load->view('inicio/pie');
	}
    public function listarPlanDeCuentas()
    {
		$filas   = $this->PlanDeCuentas_model->getPlanDeCuentas();
		
		$draw    = intval($this->input->get("draw"));
		$start   = intval($this->input->get("start"));
		$length  = intval($this->input->get("length"));	
		$data    = array();
		$num     = 1;

		foreach ($filas as $fila)
		{   
			$boton   = "
                        <span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Editar'>
                            <button type='button' class='btn btn-secondary' onclick='editarAplicacion(". $fila->id . ")'><i class='mdi mdi-pencil'></i></button>     
                        </span>	
                        <span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Eliminar'>
                            <button type='button' class='btn btn-warning' onclick='bajaAplicacion(". $fila->id . ")'><i class='mdi mdi-close-circle-outline'></i></button>     
                        </span>	
                        ";		

			$data[] = array(
				$boton,
				$num++,
				$fila->codigo,
				$fila->descripcion,			
                $fila->nivel,
				$fila->estado
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
	public function guardarPlanDeCuentas()
	{
		$id_usuario       = $this->session->userdata('id_usuario');
		$id_funcionario   = $this->session->userdata('id_funcionario');
		$data 			  = $this->input->post();

		// $resultado   = json_decode($this->validarDatos($data));		
		// $resul       = $resultado[0]->resultado;
		// $mensaje     = $resultado[0]->mensaje;
        $resul=1;
        $mensaje = "OK";
        $opcionPadre="";
        if($resul == 1)
		{
            $accion      = $data['txtAccion'];
			$codigo      = $data['txtCodigo'];
			$descripcion = $data['txtDescripcion'];
			$nivel       = $data['opcionNivel'];

            $opcionPadre = $nivel>1 ? $data['opcionPadre'] : 0;
            $ruta        = $nivel>1?$data['opcionPadre']:0;
            if($nivel = 1)
            {
                $ruta = 0;
            }else if($nivel = 2)
            {
                $ruta = $data['opcionPadre'];
            }
            else
            {
                /*OBTENER LA RUTA DEL PADRE Y CONCATENER LA RUTA PADRE SELECCIONADA*/
                $cuenta =$this->PlanDeCuentas_model->getPlanDeCuentasById($data['opcionPadre']);
                if(count($cuenta)>0)
                {
                    $ruta = $cuenta[0]->ruta . "-" . $data['opcionPadre'];
                }
                else
                {
                    $ruta = 0;
                }
                
            }

			$padre       = $opcionPadre;
			$sigla       = $data['txtSigla'];

			if($accion === 'nuevo')
			{
				$datosPlanCuentas = array(
					'codigo'                  => $codigo,
					'descripcion'             => $descripcion,
                    'nivel'                   => $nivel,
                    'padre'                   => $padre,
                    'ruta'                    => $ruta,
                    'id_funcionario_registro' => $id_funcionario,
					'sigla'                   => $sigla
				);

				$plancuentas = $this->PlanDeCuentas_model->guardarPlanDeCuentas($datosPlanCuentas);
				if($plancuentas)
				{
					$resul = 1;
					$mensaje = "SE REGISTRO CORRECTAMENTE.";
				}
				else
				{
					$resul = 0;
					$mensaje = "ERROR EN EL REGISTRO!!!";
				}
			}
			else
			{
				// $updateAplicacion = array(
				// 	'nombre_aplicacion' 		=> $nombre_aplicacion,
				// 	'abreviatura'       		=> $abreviatura,
				// 	'descripcion_aplicacion'    => $descripcion
				//    );

				// $aplicacion = $this->Aplicaciones_model->updateAplicaciones($id_aplicacion,$updateAplicacion);
				// if($aplicacion)
				// {
				// 	$resul = 1;
				// 	$mensaje = "SE ACTUALIZÓ LOS DATOS DE LA APLICACIÓN CORRECTAMENTE.";
				// }
				// else
				// {
				// 	$resul = 0;
				// 	$mensaje = "ERROR EN LA ACTUALIZACIÓN!!!";
				// }
			}

			
		}

		$resultado ='[{
						"resultado":"'.$resul.'",
						"mensaje":"'.$mensaje.'"
					 }]';

		echo $resultado;
	}

	// function validarDatos($data)
	// {
 	// 	//$data = $this->input->post(); 
 	// 	$txtAccion 	= $data['txtAccionAplicacion'];
 	// 	// $tipoCorrespondenciaCite = $data['idtipoCorrespondenciaCite'];

 	// 	$this->form_validation->set_data($data);
 	// 	$resul = 1;
	// 	$mensaje = "OK";

	// 	if($txtAccion == 'nuevo')
	// 	{	
	// 		if($this->form_validation->run('validar_aplicacion'))
	// 		{
	// 			$resul = 1;
	// 			$mensaje = "OK";
	// 		}
	// 		else
	// 		{
	// 			$resul = 0;
	// 			$mensaje = json_encode($this->form_validation->get_errores_arreglo());
	// 			$mensaje = formaterarValidacion($mensaje);
	// 		}
	// 	}
	// 	else
	// 	{
	// 		if($this->form_validation->run('validar_aplicacion_editar'))
	// 		{
	// 			$resul = 1;
	// 			$mensaje = "OK";
	// 		}
	// 		else
	// 		{
	// 			$resul = 0;
	// 			$mensaje = json_encode($this->form_validation->get_errores_arreglo());
	// 			$mensaje = formaterarValidacion($mensaje);
	// 		}
	// 	}

	// 	$resultado ='[{								
	// 				"resultado":"'.$resul.'",
	// 				"mensaje":"'.$mensaje.'"
	// 				}]';

	// 	return $resultado; 		
	// }
    public function cargarCuentaSuperior()
	{
		$nivel = $this->input->post('nivel');
		
	    $option = "<option VALUE='-1'>Seleccione un opción</OPTION>";

        $filas = $this->PlanDeCuentas_model->getPlanDeCuentasByNivel($nivel);
        foreach ($filas as $fila)
        {
            $option.="<option value = '".$fila->id."'>".$fila->descripcion."</option>";
        }
	    echo $option;
	}

}
