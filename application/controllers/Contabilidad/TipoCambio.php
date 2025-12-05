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

		$gestion = $this->input->post('gestion');
		$mes     = $this->input->post('mes');

		

		// echo("GESTION".$gestion);
		// echo("MES".$mes);
		// die();

		if(($gestion != -1 && $mes != -1) && (!empty($gestion) && !empty($mes)))
		{
			$busqueda="and  EXTRACT(YEAR FROM fecha) = '".$gestion."'
					   and  EXTRACT(MONTH  FROM fecha) = '".$mes."'";
		}
		else if($gestion != -1 && ($mes == -1 || empty($mes)))
		{
			$busqueda="and  EXTRACT(YEAR FROM fecha) = '".$gestion."'";
		}
		else
		{
			$busqueda="";
		}
		// echo("BUSQUEDA".$busqueda);
		// die();
		$filas   = $this->TipoCambio_model->getTipoCambio($busqueda);
	
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
                            <button type='button' class='btn btn-block btn-danger btn-sm' onclick='bajaTipoCambio(". $fila->id. ")'><i class='fas fa-trash-alt'></i></button>     
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
	public function guardarTipoCambio()
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
            $accion          = $data['txtAccion'];
            $fecha           = $data['txtFecha'];
			$tipo_cambio  	 = $data['tipo_cambio'];
			$id_tipocambio   = $data['id_tipocambio'];

						
			if($accion === 'nuevo')
			{
				
				$datosTipoCambio = array(
					'fecha'                   => $fecha,
					'valor'                   => $tipo_cambio,
                    'id_usuario_registro'     => $id_usuario
				);

				$tipo_cambio = $this->TipoCambio_model->guardarTipoCambio($datosTipoCambio);
                if (!$tipo_cambio) {
                    $this->db->trans_rollback();
                    echo json_encode([["resultado" => "0", "mensaje" => "ERROR EN EL REGISTRO DEL TIPO DE CAMBIO."]]);
                    return;
                }
                
                $mensaje = "SE REGISTRO CORRECTAMENTE.";
				
			}
			else
			{
				$updateTipoCambio = array(
					'valor'                   => $tipo_cambio,
                    'fecha_modificacion'      => $fecha_actual
				   );

				$actualizarTipoCambio = $this->TipoCambio_model->updateTipoCambio($id_tipocambio,$updateTipoCambio);
				if($actualizarTipoCambio)
				{
					$resul = 1;
					$mensaje = "SE ACTUALIZÓ LOS DATOS DEL TIPO DE CAMBIO CORRECTAMENTE.";
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
	function datosTipoCambio()
	{
		$id_tipocambio = $this->input->post('id_tipocambio');
		$filas = $this->TipoCambio_model->getTipoCambioById($id_tipocambio);
        if($filas)
        {
            $resul = 1;
            $mensaje = "Datos Seleccionados.";
            $resultado ='[{
                "fecha":         "'.$filas[0]->fecha.'",
                "valor":         "'.$filas[0]->valor.'",
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
	public function bajaTipoCambio()
	{
		$id_usuario   = $this->session->userdata('id_usuario');
		$id_tipocambio   = $this->input->post('id_tipocambio');
		$fecha_actual = getFechaHoraActual();
		$estado       = 'ANU';
		$updateTipoCambio = array(
			'fecha_modificacion' => $fecha_actual,
			'estado'           => $estado
		);

		$entidad = $this->TipoCambio_model->updateTipoCambio($id_tipocambio, $updateTipoCambio);
		if ($entidad) {
			$resul = 1;
			$mensaje = "SE REGISTRO LA BAJA CORRECTAMENTE.";
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
