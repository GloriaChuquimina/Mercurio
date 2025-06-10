<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Comprobante extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->_is_logued_in();
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
	public function registroComprobante($entidad,$accion='nuevo')
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
		$dato['accion']  = $accion;

		$titulo = "Comprobante Contable";		
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
				$importeDebe=0;
				$importeHaber=0;
				$importeDebeUs=0;
				$importeHaberUs=0;
				if( $fila)
				{
					$row = explode("*", $fila); 
					list($ini,$id_cuenta,$cuenta, $tipo_movimiento,$tipo_movimiento_literal,$importe,$tipo_cambio,$glosa_cuenta) = $row;

					$codigoCuenta = explode("-",$cuenta);
					list($codigo_cuenta,$descripcion_cuenta)= $codigoCuenta;
					// echo ("A->".$ini);
					// echo("<br>");
					// echo ("B->".$id_cuenta);
					// echo("<br>");
					// echo ("C->".$cuenta);
					// echo("<br>");
					// echo ("D->".$tipo_movimiento);
					// echo("<br>");
					// echo ("E->".$tipo_movimiento_literal);
					// echo("<br>");
					// echo ("F->".$importe);
					// echo("<br>");
					// echo ("G->".$tipo_cambio);
					// echo("<br>");
					// echo ("H->".$glosa_cuenta);
					// echo("<br>");
					// echo ("I->".$codigo_cuenta);
					// echo("<br>");
					// echo ("J->".$descripcion_cuenta);
					// die();
					if($tipo_movimiento == "DB")
					{
						$importeDebe=$importe;
						$importeDebeUs=$importe/$tipo_cambio;
					}
					elseif ($tipo_movimiento == "HB") {
						$importeHaber=$importe;
						$importeHaberUs=$importe/$tipo_cambio;
					}

					$boton = "<span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Baja'><button type='button' class='btn btn-danger btn-circle' onclick=\"eliminarDocumentoT('".$id_cuenta."','".$descripcion_cuenta."','".$tipo_movimiento."', '". $glosa_cuenta."' )\"><i class='mdi mdi-delete'></i></button></span>";
					$cuenta_registro = "<b>".$descripcion_cuenta."</b><br>".$glosa_cuenta;
					$data[] = array(
						$codigo_cuenta,
						$cuenta_registro,
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
	public function guardarComprobante()
	{
		$id_usuario          = $this->session->userdata('id_usuario');
		$id_funcionario      = $this->session->userdata('id_funcionario');
		$id_dependencia      = $this->session->userdata('id_dependencia_principal');
		$fechaActual          = getFechaHoraActual();
		// $data 			 	 = $this->input->post('datos');
		parse_str($this->input->post('datos'), $data);
		$detalleComprobante  = $this->input->post('detalleComprobante');

		// $resultado   = json_decode($this->validarDatos($data));		
		// $resul       = $resultado[0]->resultado;
		// $mensaje     = $resultado[0]->mensaje;
        $resul=1;
        $mensaje = "OK";
        $idComprobante="";
		$this->db->trans_start();
        if($resul == 1)
		{
            $accion      		  = $data['txtAccionComprobante'];
			$idComprobante   	  = $data['id_comprobante'];
			$id_entidad       	  = $data['id_entidad'];
			$tipo_comprobante     = $data['txtTipo'];
			$fecha_comprobante    = $data['txtFecha'];
			$tipo_cambio	      = $data['txtTipoCambio'];
			$glosa_general	      = $data['txtGlosaGeneral'];

			$correlativo		  = 0;
			// $periodo			  = 0;
			// $gestion			  = gestion_actual();
			$periodo        	  = date("Ym", strtotime($fecha_comprobante));
			$gestion        	  = date("Y", strtotime($fecha_comprobante));


			if($accion === 'nuevo')
			{
				$tipoCorrelativo  = $tipo_comprobante ;
				$datosCorrelativo = json_decode(obtenerCorrelativoComprobanteGestionEntidad($tipoCorrelativo,$id_entidad,$id_dependencia, $gestion));
				$idcorrelativoentidadgestion    = $datosCorrelativo[0]->idcorrelativoentidadgestion;
				$correlativo      		        = $datosCorrelativo[0]->correlativo;

				
				$datosComprobante = array(
					'id_entidad'              => $id_entidad,
					'tipo_comprobante'        => $tipo_comprobante,
                    'correlativo'             => $correlativo,
                    'periodo'                 => $periodo,
                    'gestion'                 => $gestion,
                    'glosa_comprobante' 	  => $glosa_general,
					'fecha_comprobante'       => $fecha_comprobante,
					'tipo_cambio'             => $tipo_cambio,
					'id_usuario_registro'     => $id_usuario
				);

				$saveComprobante = $this->Comprobantes_model->guardarComprobante($datosComprobante);
				if($saveComprobante)
				{
					/*REGISTRO DE CUENTAS DEL COMPROBANTE*/
					// echo("<br>");
					// print_r($detalleComprobante);
					$filas = explode("|", $detalleComprobante);
					// echo("<br>");
					// print_r($filas);
					// echo("<br>");
					// echo ("Cantidad de Filas->".count($filas));
					// echo("<br>");
					// die();
					if(!empty($filas))
					{
						foreach($filas as $fila)
						{
							if(!empty($fila) && $fila != "undefined" && $fila != "null")
							{
								$row = explode("*", $fila);
								// echo("<br>");
								// echo ("A->".$fila);
								// echo("<br>");
								// echo ("B->".$row[0]);
								// echo("<br>");
								// echo ("Contador->".count($row));
								if(!isset($row[0]) || empty($row[0]))
								{
									// echo("VERDAD");
									list($inicio,$id_cuenta, $cuenta,$tipo_movimiento,$tipo_movimiento_literal, $importe, $tipo_cambio,$glosa_cuenta) = $row;
									$importe=$importe;
									$importeUs=$importe*$tipo_cambio;
									$datosComprobanteDetalle = array(
										'id_entidad'	            => $id_entidad,
										'id_comprobante'            => $saveComprobante,
										'id_cuenta'                 => $id_cuenta,
										'tipo_movimiento'           => $tipo_movimiento,
										'tipo_cambio'               => $tipo_cambio,
										'importe_moneda_nacional'   => $importe,
										'importe_moneda_extranjera' => $importeUs,
										'glosa_cuenta'              => $glosa_cuenta,
										'id_usuario_registro'       => $id_usuario					
									);
									// echo("<br>");
									// print_r($datosComprobanteDetalle);
									// echo("<br>");
									// die();
									$detalle_comprobante = $this->Comprobantes_model->guardarDetalleComprobante($datosComprobanteDetalle);
									if($detalle_comprobante)
									{
										$updateCorrelativoEntidadGestion = array(
											'correlativo' => $correlativo,
											'fecha_modificacion' => $fechaActual
										);	

										$data = $this->Correlativos_model->updateCorrelativoEntidadGestion($idcorrelativoentidadgestion,$updateCorrelativoEntidadGestion);

										$resul = 1;
										$mensaje = "SE REGISTRO CORRECTAMENTE XXXXX";
									}
									else
									{
										$resul = 0;
										$mensaje = "ERROR EN EL REGISTRO DETALLE COMPROBANTE!!!";
									}
								}
								else
								{
									echo("FALSOOOOO");
									$resul = 0;
									$mensaje = "ERROR EN EL REGISTRO DETALLE COMPROBANTE....!!!";
								}

							}
														
						}
					}

					// $resul = 1;
					// $mensaje = "SE REGISTRO CORRECTAMENTE";
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

		// $resultado ='[{
		// 				"resultado":"'.$resul.'",
		// 				"mensaje":"'.$mensaje.'"
		// 			 }]';

		// echo $resultado;


		/****************** */
		/******TRANSACT*****/
		/****************** */
		if ($this->db->trans_status() === FALSE && $resul == 1) { 
			$this->db->trans_rollback(); // Deshacer los cambios si hay un error
			// echo "Transacción fallida";
			$resultado = 0;
			// echo '[{"resultado":"'.$resultado.'","mensaje":"'.$mensaje.'"}]';
		} else {
			$this->db->trans_commit(); // Confirmar los cambios si todo está bien
			// echo "Transacción exitosa";
			$resultado = 1;
		}
		echo '[{"resultado":"'.$resultado.'",
		          "mensaje":"'.$mensaje.'"}]';

	}
	 public function cargarComprobantesByEntidad()
	{
		$id_usuario  = $this->session->userdata('id_usuario');
			
		$draw    = intval($this->input->get("draw"));
		$start   = intval($this->input->get("start"));
		$length  = intval($this->input->get("length"));	
		$data    = array();
		$num     = 1;

		$id_entidad  = $this->input->post('id_entidad');
		$filas  	 = $this->Comprobantes_model->getComprobanteByIdEntidad($id_entidad);
		// echo("<pre>");
		// echo($id_entidad);
		// echo("<br>");
		// print_r($filas);
		// echo("</pre>");

		foreach ($filas as $fila)
		{   
			$boton   = "
                        <span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Editar'>
                            <button type='button' class='btn btn-block btn-warning btn-sm' onclick=\"editarEntidad(". $fila->id . ")\"><i class='fas fa-edit'></i></button>     
                        </span>	
                        <span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Eliminar'>
                            <button type='button' class='btn btn-block btn-danger btn-sm' onclick='bajaEntidad(". $fila->id . ")'><i class='fas fa-trash-alt'></i></button>     
                        </span>	
                        ";	
						
			// echo("<br>");
			// echo("Tipo Comprobante***->".$fila->tipo_comprobante);
			// echo("<br>");
			$tipo_comprobante = getValor2Configuraciones("TIPO COMPROBANTES CONTABLE", $fila->tipo_comprobante);
			// echo("<br>");
			// echo("Tipo Comprobante->".$tipo_comprobante);
			// echo("<br>");
			$nombre_entidad =descripcion_nombre_entidad($fila->id_entidad);
			$estado =getValor2Configuraciones("ESTADO REGISTRO", $fila->estado);
			$data[] = array(
				$boton,
				$num++,
				$tipo_comprobante,
				$fila->correlativo,
				formato_fecha($fila->fecha_comprobante),
				$fila->glosa_comprobante,
				datos_persona_nombre2($fila->id_usuario_registro),	
				$estado			);
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