<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Entidades extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->_is_logued_in();
        $this->load->model('Entidades_model');
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

		$titulo = "Gestión de Entidades";		
		$dato['titulo'] = $titulo;

		$this->load->view('inicio/cabecera',$dato);
		$this->load->view('inicio/menu',$dato);
		$this->load->view('entidad/entidad',$dato);
		$this->load->view('inicio/pie');
	}
    public function cargarEntidades()
	{
		$id_usuario = $this->session->userdata('id_usuario');
		$filas   = $this->Entidades_model->getEntidades();
		
		$draw    = intval($this->input->get("draw"));
		$start   = intval($this->input->get("start"));
		$length  = intval($this->input->get("length"));	
		$data    = array();
		$num     = 1;

		foreach ($filas as $fila)
		{   
			$boton   = "
                        <span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Editar'>
                            <button type='button' class='btn btn-block btn-warning btn-sm' onclick=\"editarEntidad(". $fila->id . ",'".$fila->sigla."','".$fila->nombre."')\"><i class='fas fa-edit'></i></button>     
                        </span>	
                        <span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Eliminar'>
                            <button type='button' class='btn btn-block btn-danger btn-sm' onclick='bajaEntidad(". $fila->id . ")'><i class='fas fa-trash-alt'></i></button>     
                        </span>	
                        ";		

			$data[] = array(
				$boton,
				$num++,
				$fila->nombre,
				$fila->sigla,			
				$fila->observaciones,	
				formato_fecha($fila->fecha_registro),	
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
    public function guardarEntidad()
	{
		$id_usuario       = $this->session->userdata('id_usuario');
		$id_funcionario   = $this->session->userdata('id_funcionario');
		$id_dependencia   = $this->session->userdata('id_dependencia_principal');
		$fecha_actual	  = getFechaHoraActual();
		$data 			  = $this->input->post();

		// $resultado   = json_decode($this->validarDatos($data));		
		// $resul       = $resultado[0]->resultado;
		// $mensaje     = $resultado[0]->mensaje;
        $resul=1;
        $mensaje = "OK";
        $this->db->trans_begin();
        if($resul == 1)
		{
            $accion        = $data['txtAccion'];
			$sigla         = $data['txtSigla'];
			$nombre        = $data['txtNombre'];
			$id_entidad    = $data['id_entidad'];
			$observaciones = $data['txtObservaciones'];
						
			if($accion === 'nuevo')
			{
				
				$datosEntidad = array(
					'nombre'                  => $nombre,
					'sigla'                   => $sigla,
                    'activo'                  => true,
                    'id_usuario'              => $id_usuario,
                    'id_dependencia'          => $id_dependencia,
                    'observaciones'           => $observaciones
				);

				$entidad = $this->Entidades_model->guardarEntidad($datosEntidad);
                if (!$entidad) {
                    $this->db->trans_rollback();
                    echo json_encode([["resultado" => "0", "mensaje" => "ERROR EN EL REGISTRO DE LA ENTIDAD."]]);
                    return;
                }
                
                $datosEntidadDependencia =array(
                    'id_entidad'         => $entidad,
                    'id_dependencia'     => $id_dependencia,
                    'id_usuario_registro'=> $id_usuario
                );
                $entidad_dependencia =$this->Entidades_model->guardarEntidadDependencia($datosEntidadDependencia);
                if (!$entidad_dependencia) {
                    $this->db->trans_rollback();
                    echo json_encode([["resultado" => "0", "mensaje" => "ERROR EN EL REGISTRO DE DEPENDENCIA."]]);
                    return;
                }
                $mensaje = "SE REGISTRO CORRECTAMENTE.";
				
			}
			else
			{
				$updateEntidad = array(
					'nombre'                  => $nombre,
					'sigla'                   => $sigla,
                    'observaciones'           => $observaciones,
                    'fecha_modificacion'      => $fecha_actual
				   );

				$actualizarEntidad = $this->Entidades_model->updateEntidad($id_entidad,$updateEntidad);
				if($actualizarEntidad)
				{
					$resul = 1;
					$mensaje = "SE ACTUALIZÓ LOS DATOS DE LA ENTIDAD CORRECTAMENTE.";
				}
				else
				{
					$resul = 0;
					$mensaje = "ERROR EN LA ACTUALIZACIÓN!!!";
				}
			}			
		}

		/******TRANSACT*****/

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            echo json_encode([["resultado" => "0", "mensaje" => "OCURRIÓ UN ERROR EN LA TRANSACCIÓN."]]);
        } else {
            $this->db->trans_commit();
            echo json_encode([["resultado" => "1", "mensaje" => $mensaje]]);
        }       

		// $resultado ='[{
		// 				"resultado":"'.$resul.'",
		// 				"mensaje":"'.$mensaje.'"
		// 			 }]';

		// echo $resultado;
	}
    function datosEntidad()
	{
		$id_entidad = $this->input->post('id_entidad');
		$filas = $this->Entidades_model->getEntidadesById($id_entidad);
        if($filas)
        {
            $resul = 1;
            $mensaje = "Datos Seleccionados.";
            $resultado ='[{
                "nombre":        "'.$filas[0]->nombre.'",
                "sigla":         "'.$filas[0]->sigla.'",
                "observaciones": "'.$filas[0]->observaciones.'",
                "resultado":     "'.$resul.'",
                "mensaje":       "'.$mensaje.'"
                }]';
        }
        else
        {       
                $resul = 0;
                $mensaje = "No se obtuvieron registros.";
        }
		echo $resultado;
	}
	public function bajaEntidad()
	{
		$id_usuario   = $this->session->userdata('id_usuario');
		$id_entidad   = $this->input->post('id_entidad');
		$fecha_actual = getFechaHoraActual();
		$estado       = 'ANU';
		$updateEntidad = array(
			'fecha_modificacion' => $fecha_actual,
			'estado'           => $estado
		);

		$entidad = $this->Entidades_model->updateEntidad($id_entidad, $updateEntidad);
		if ($entidad) {
			$resul = 1;
			$mensaje = "SE REGISTRO LA BAJA DE LA ENTIDAD CORRECTAMENTE.";
		} else {
			$resul = 0;
			$mensaje = "ERROR EN EL REGISTRO DE LA BAJA!!!";
		}

		$resultado = '[{
						"resultado":"' . $resul . '",
						"mensaje":"' . $mensaje . '"
					 }]';

		echo $resultado;
	}
}