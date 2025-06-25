<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . "libraries/fpdf/easyTable.php";
require_once APPPATH . "libraries/fpdf/fpdfde.php";
require_once APPPATH . "libraries/fpdf/exfpdfCartaContable.php";

class Comprobante extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->_is_logued_in();
		$this->load->library('form_validation');
        $this->load->model('Comprobantes_model');
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
		$dato['nombre_sistema']  = "SISTEMA CONTABLE <BR>MERCURIO";
		
		
		$id_usuario = $this->session->userdata('id_usuario');
		$dato['rolescero'] = $this->session->userdata('rolescero');
		$dato['roles']  = $this->session->userdata('roles');
		$dato['nombre_usuario']  = $this->session->userdata('nombre_completo');

		$titulo = "Gestión de Comprobantes";		
		$dato['titulo'] = $titulo;

		$this->load->view('inicio/cabecera',$dato);
		$this->load->view('inicio/menu',$dato);
		$this->load->view('contabilidad/comprobantes',$dato);
		$this->load->view('inicio/pie');
	}
	public function principalComprobante($entidad=-1)
	{

		$dato['nombre_usuario']  = $this->session->userdata('nombre_usuario');		
		$dato['nombre_sistema']  = "SISTEMA CONTABLE <BR>MERCURIO";
		
		
		$id_usuario = $this->session->userdata('id_usuario');
		$dato['rolescero'] = $this->session->userdata('rolescero');
		$dato['roles']  = $this->session->userdata('roles');
		$dato['nombre_usuario']  = $this->session->userdata('nombre_completo');

		$titulo = "Gestión de Comprobantes";		
		$dato['titulo'] = $titulo;
		$dato['entidad'] = $entidad;

		$this->load->view('inicio/cabecera',$dato);
		$this->load->view('inicio/menu',$dato);
		$this->load->view('contabilidad/comprobantes',$dato);
		$this->load->view('inicio/pie');
	}
	public function registroComprobante($entidad,$accion='nuevo',$id_comprobante=0)
	{
		$dato['nombre_usuario']  = $this->session->userdata('nombre_usuario');		
		$dato['nombre_sistema']  = "SISTEMA CONTABLE <BR>MERCURIO";
		$id_usuario = $this->session->userdata('id_usuario');
		$dato['rolescero'] = $this->session->userdata('rolescero');
		$dato['roles']  = $this->session->userdata('roles');
		$dato['nombre_usuario']  = $this->session->userdata('nombre_completo');
		$dato['nombre_entidad']  = descripcion_nombre_entidad($entidad);
		$dato['entidad']  = $entidad;
		$dato['accion']  = $accion;
		$dato['id_comprobante']  = $id_comprobante;

		$titulo = "Comprobante Contable";		
		$dato['titulo'] = $titulo;

		$this->load->view('inicio/cabecera',$dato);
		$this->load->view('inicio/menu',$dato);		
		$this->load->view('contabilidad/registro_comprobante',$dato); //cuerpo
		$this->load->view('inicio/pie');
	}
	function verificarValorCombo($valor)
	{
		if($valor == -1 || strlen($valor) == 0)
		{
			$this->form_validation->set_message('verificarValorCombo', 'Seleccione una opción del combo TIPO de movimiento.');
			return false;
		}
		else
		{
			return true;	
		}
	}
	function verificarValorCuentaBusqueda()
	{

		$idCuenta = $this->input->post('id_cuenta');

		if (empty($idCuenta) || !is_numeric($idCuenta)) {
			$this->form_validation->set_message('verificarValorCuentaBusqueda', 'La cuenta seleccionada no es válida. Seleccione una cuenta válida de la lista.');
			return FALSE;
		}
		return TRUE;
	}
	function verificarEntidad()
	{

		$idEntidad = $this->input->post('nombre_entidad');
		// if (empty($idEntidad) || (!is_numeric($idEntidad) && $idEntidad == -1)) {
		if (empty($idEntidad)) {
			$this->form_validation->set_message('verificarEntidad', 'No se ha registrado una entidad para asociar el comprobante.');
			return FALSE;
		}
		return TRUE;
	}
	public function fecha_valida($fecha)
	{
		// $fecha = $this->input->post('txtFecha');
		// echo ($fecha);
		if (DateTime::createFromFormat('Y-m-d', $fecha) !== false) {
			return true;
		} else {
			$this->form_validation->set_message('fecha_valida', 'El campo {field} no contiene una fecha válida.');
			return false;
		}
		
	}

	function validarDatosRegistroCuenta()
	{
 		$data = $this->input->post(); 
 		$accion 	= $data['txtAccionMovimiento'];
 		$this->form_validation->set_data($data);
 		$resul = 1;
		$mensaje = "OK";

		if($accion == 'nuevo')
		{	
			if($this->form_validation->run('validar_registro_movimiento_cuenta'))
			{
				$resul = 1;
				$mensaje = "OK";
			}
			else
			{
				$resul = 0;
				$mensaje = json_encode($this->form_validation->get_errores_arreglo());
				$mensaje = formaterarValidacion($mensaje);
			}
		}
		else
		{
			if($this->form_validation->run('validar_opcion_editar'))
			{
				$resul = 1;
				$mensaje = "OK";
			}
			else
			{
				$resul = 0;
				$mensaje = json_encode($this->form_validation->get_errores_arreglo());
				$mensaje = formaterarValidacion($mensaje);
			}
		}

		$resultado ='[{								
					"resultado":"'.$resul.'",
					"mensaje":"'.$mensaje.'"
					}]';

		// echo json_encode($resultado);
		echo $resultado; 		
	}



	public function cargarTablaRegistroCuenta( )
	{

		$id_usuario          = $this->session->userdata('id_usuario');
		$id_funcionario      = $this->session->userdata('id_funcionario');
		$id_dependencia      = $this->session->userdata('id_dependencia_principal');

		$draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

		// $datos_registro_cuenta	   = $this->input->post('datos_cuenta');
		$datos    	        = $this->input->post('datos_cuenta');
		// $data 			= $this->input->post(); 
		parse_str($datos, $datos_registro_cuenta);
		// // echo("<pre>");
		// // print_r ($datos_registro_cuenta);
		// // echo("</pre>");
		// // die();
 		// $accion 	= $data['txtAccionMovimiento'];
		$accion_comprobante		   = $datos_registro_cuenta['txtAccionComprobanteCuenta'];
		$accion_cuenta   		   = $datos_registro_cuenta['txtAccionMovimiento'];
		$id_comprobante   		   = $datos_registro_cuenta['id_comprobante'];
		$id_entidad       		   = $datos_registro_cuenta['id_entidad_registro'];
		$id_cuenta   		       = $datos_registro_cuenta['id_cuenta'];
		$tipo_cambio     		   = $datos_registro_cuenta['tipo_cambio_movimiento'];
		$tipo_movimiento   		   = $datos_registro_cuenta['txtTipoMovimiento'];
		$importe   		           = $datos_registro_cuenta['txtImporte'];
		$glosa_cuenta   		   = $datos_registro_cuenta['txtGlosaCuenta'];
		$cadRegistroCuenta		   = $datos_registro_cuenta['registroCuentaT'];
		
				
		// $accion_cuenta 		   = $this->input->post('accion_cuenta');
		// $accion_comprobante    = $this->input->post('accion_cuenta');
		// $cadRegistroCuenta     = $this->input->post('cuenta');
		// $id_entidad            = $this->input->post('id_entidad');
		// $id_comprobante        = $this->input->post('id_comprobante');
		// $id_cuenta             = $this->input->post('id_cuenta');

		$resul =1;
		$data = array();
		$num =1;
		$importeDebe=0;
		$importeHaber=0;
		$importeDebeUs=0;
		$importeHaberUs=0;
		$totalimporteDebe=0;
		$totalimporteHaber=0;
		$totalimporteDebeUs=0;
		$totalimporteHaberUs=0;
		$nro_registros=0;
		if($resul == 1)
		{
				if($accion_comprobante == "nuevo" && $accion_cuenta == "nuevo")
				{  
					$filas = explode("|", $cadRegistroCuenta);  
					$nro_registros = count($filas)-1;
					echo ($filas);
					echo ($nro_registros);
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
							if($tipo_movimiento == "DB")
							{
								$importeDebe=$importe;
								// $importeDebeUs=$tipo_cambio==0?0:round($importe/$tipo_cambio,2);
								$importeDebeUs=$tipo_cambio==0?0:$importe/$tipo_cambio;
							}
							elseif ($tipo_movimiento == "HB") {
								$importeHaber=$importe;
								// $importeHaberUs=$tipo_cambio==0?0:round($importe/$tipo_cambio,2);
								$importeHaberUs=$tipo_cambio==0?0:$importe/$tipo_cambio;
							}

							$botonEditar = "<div style='text-align: center;'>
										<span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Baja'>
											<button type='button' class='btn btn-primary btn-xs mr-1' onclick=\"eliminarDocumentoT('".$id_cuenta."','".$descripcion_cuenta."','".$tipo_movimiento."','". $glosa_cuenta."' )\"><i>✏️</i></button>
										</span>										
									</div>";
							$botonEliminar = "<div style='text-align: center;'>
										<span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Eliminar Registro'>
											<button type='button' class='btn btn-danger btn-xs' onclick=\"eliminarRegistroCuentaTemporal('".$id_cuenta."','".$cuenta."','".$tipo_movimiento."','".$tipo_movimiento_literal."',".$importe.",'".$tipo_cambio."','".$glosa_cuenta."','".$cadRegistroCuenta."')\"><i>🗑️</i></button>
										</span>										
									</div>";
							$cuenta_registro = "<b>".$descripcion_cuenta."</b><br>".$glosa_cuenta;
							$data[] = array(
								"<span class='badge badge-secondary'>".$codigo_cuenta."</span>",
								$cuenta_registro,
								"<div style='text-align: right; color: #28a745; font-weight: bold;'>".number_format($importeDebe, 2, '.', ',')."</div>",
								"<div style='text-align: right; color: #dc3545; font-weight: bold;'>".number_format($importeHaber,2,'.',',')."</div>",
								"<div style='text-align: right; color: #28a745; font-weight: bold;'>".number_format($importeDebeUs,2,'.',',')."</div>",
								"<div style='text-align: right; color: #dc3545; font-weight: bold;'>".number_format($importeHaberUs,2,'.',',')."</div>",
								$botonEliminar
							);
							$totalimporteDebe+=$importeDebe;
							$totalimporteHaber+=$importeHaber;
							$totalimporteDebeUs+=$importeDebeUs;
							$totalimporteHaberUs+=$importeHaberUs;
						}
						else
						{
							// echo("VACIOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOO");
							// die();
						}			
					}
				}
				else
				{

					/*AÑADIR NUEVO REGISTRO DE CUENTA EN EL COMPROBANTE */
					// $importe=$importe;
					$importe_sin_comas = str_replace(',', '', $importe);
					$importeUs=$importe_sin_comas*$tipo_cambio;
					$datosComprobanteDetalle = array(
						'id_entidad'	            => $id_entidad,
						'id_comprobante'            => $id_comprobante,
						'id_cuenta'                 => $id_cuenta,
						'tipo_movimiento'           => $tipo_movimiento,
						'tipo_cambio'               => $tipo_cambio,
						'importe_moneda_nacional'   => $importe_sin_comas,
						'importe_moneda_extranjera' => $importeUs,
						'glosa_cuenta'              => $glosa_cuenta,
						'id_usuario_registro'       => $id_usuario					
					);
					$save_count_record = $this->Comprobantes_model->guardarDetalleComprobante($datosComprobanteDetalle);
					if($save_count_record)
					{
						$filas = $this->Comprobantes_model->getDetalleComprobanteById($id_comprobante);
						foreach($filas as $fila )
						{

							$importeDebe=0;
							$importeHaber=0;
							$importeDebeUs=0;
							$importeHaberUs=0;

							/*DATOS*/
							$id_registro			   = $fila->id;
							$id_entidad				   = $fila->id_entidad;
							$id_comprobante			   = $fila->id_comprobante;
							$id_cuenta				   = $fila->id_cuenta;
							$descripcion_cuenta		   = getCuenta($fila->id_cuenta);
							$tipo_movimiento		   = $fila->tipo_movimiento;
							$tipo_cambio			   = $fila->tipo_cambio;
							$importe_moneda_nacional   = $fila->importe_moneda_nacional;
							$importe_moneda_extranjera = $fila->importe_moneda_extranjera;
							$glosa_cuenta			   = $fila->glosa_cuenta;
							$fecha_registro			   = $fila->fecha_registro;
							$codigo_cuenta			   = getCodigoCuenta($fila->id_cuenta);
							if($tipo_movimiento == "DB")
							{
								$importeDebe=$importe_moneda_nacional;
								// $importeDebeUs=$tipo_cambio==0?0:round($importe/$tipo_cambio,2);
								$importeDebeUs=$importe_moneda_extranjera;
							}
							elseif ($tipo_movimiento == "HB") {
								$importeHaber=$importe_moneda_nacional;
								// $importeHaberUs=$tipo_cambio==0?0:round($importe/$tipo_cambio,2);
								$importeHaberUs=$importe_moneda_extranjera;
							}

							$botonEditar = "<div style='text-align: center;'>
										<span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Baja'>
											<button type='button' class='btn btn-primary btn-xs mr-1' onclick=\"eliminarDocumentoT(".$id_cuenta.")\"><i>✏️</i></button>
										</span>										
									</div>";
							$botonEliminar = "<div style='text-align: center;'>
										<span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Eliminar Registro'>
											<button type='button' class='btn btn-danger btn-xs' onclick=\"eliminarRegistroCuentaTemporal(".$id_cuenta.")\"><i>🗑️</i></button>
										</span>										
									</div>";

							$cuenta_registro = "<b>".$descripcion_cuenta."</b><br>".$glosa_cuenta;
							$data[] = array(
								"<span class='badge badge-secondary'>".$codigo_cuenta."</span>",
								$cuenta_registro,
								"<div style='text-align: right; color: #28a745; font-weight: bold;'>".number_format($importeDebe, 2, '.', ',')."</div>",
								"<div style='text-align: right; color: #dc3545; font-weight: bold;'>".number_format($importeHaber,2,'.',',')."</div>",
								"<div style='text-align: right; color: #28a745; font-weight: bold;'>".number_format($importeDebeUs,2,'.',',')."</div>",
								"<div style='text-align: right; color: #dc3545; font-weight: bold;'>".number_format($importeHaberUs,2,'.',',')."</div>",
								$botonEliminar.$botonEditar
							);
							$totalimporteDebe+=$importeDebe;
							$totalimporteHaber+=$importeHaber;
							$totalimporteDebeUs+=$importeDebeUs;
							$totalimporteHaberUs+=$importeHaberUs;


							// $boton = "<span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Baja'><button type='button' class='btn btn-danger btn-circle' onclick=\"eliminarDocumento('".$fila->id."' )\"><i class='mdi mdi-delete'></i></button></span>";
							// $data[] = array(
							// 			$num++,
							// 			$fila->documento,
							// 			$fila->numero_documento,
							// 			$fila->fecha_documento,
							// 			$boton 
							// );
						}

					}
				}
		
		}	
		 $output = array(
            "draw" => $draw,
            "recordsTotal" => count($filas)-1,
            "recordsFiltered" => count($filas)-1,
            "nro_registros" => $nro_registros,
            "totalimporteDebe" => number_format($totalimporteDebe,2,'.',','),
            "totalimporteHaber" => number_format($totalimporteHaber,2,'.',','),
            "totalimporteDebeUs" => number_format($totalimporteDebeUs,2,'.',','),
            "totalimporteHaberUs" => number_format($totalimporteHaberUs,2,'.',','),
            "data" => $data
        );
	    echo json_encode($output);
	    exit();
	}	
	
	function validarDatos($data)
	{

 		$txtAccion 	= $data['txtAccionComprobante'];
		$idEntidad  = $data['id_entidad'];
		$fecha      = $data['txtFecha'];

		// $verifica =$this->verificarEntidad($idEntidad);
		$verifica_fecha = $this->fecha_valida($fecha);
		// echo $verifica;	
		// echo $verifica_fecha;	
		// die();

 		$this->form_validation->set_data($data);
 		$resul = 1;
		$mensaje = "OK";

		// echo json_encode($data);

		if($txtAccion == 'nuevo')
		{	
			if($this->form_validation->run('validar_registro_comprobante'))
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
		}
		else
		{
			if($this->form_validation->run('validar_registro_comprobante_editar'))
			{
				$resul = 1;
				$mensaje = "OK";
			}
			else
			{
				$resul = 0;
				$mensaje = json_encode($this->form_validation->get_errores_arreglo());
				$mensaje = formaterarValidacion($mensaje);
			}
		}

		$resultado ='[{								
					"resultado":"'.$resul.'",
					"mensaje":"'.$mensaje.'"
					}]';

		return $resultado; 		

	}
	public function guardarComprobante()
	{
		$id_usuario          = $this->session->userdata('id_usuario');
		$id_funcionario      = $this->session->userdata('id_funcionario');
		$id_dependencia      = $this->session->userdata('id_dependencia_principal');
		$fechaActual          = getFechaHoraActual();
		// $data 			 	 = $this->input->post('datos');
		parse_str($this->input->post('datos'), $data);
		// echo("<pre>");
		// print_r ($data);
		// echo("</pre>");
		$detalleComprobante  = $this->input->post('detalleComprobante');
	
		$validacomprobante   = json_decode($this->validarDatos($data));	
		$resultado   = $validacomprobante[0]->resultado;
		$mensaje     = $validacomprobante[0]->mensaje;
		// echo ($resultado);
		// echo($mensaje);
		// die();
        $idComprobante=0;
		$this->db->trans_start();
        if($resultado == 1)
		{
			
            $accion      		  = $data['txtAccionComprobante'];
			// $idComprobante   	  = $data['id_comprobante'];
			$id_entidad       	  = $data['id_entidad'];
			$fecha_comprobante    = $data['txtFecha'];
			$tipo_cambio	      = $data['txtTipoCambio'];
			$referencia_general   = $data['txtReferencia'];
			$glosa_general	      = $data['txtGlosaGeneral'];
			$correlativo		  = 0;
			$periodo        	  = date("m", strtotime($fecha_comprobante));
			$gestion        	  = date("Y", strtotime($fecha_comprobante));
			
			if($accion === 'nuevo')
			{
				// $idComprobante   	  = $data['id_comprobante'];
				$tipo_comprobante     = $data['txtTipo'];

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
                    'referencia_comprobante'  => $referencia_general,
                    'glosa_comprobante' 	  => $glosa_general,
					'fecha_comprobante'       => $fecha_comprobante,
					'tipo_cambio'             => $tipo_cambio,
					'id_usuario_registro'     => $id_usuario
				);

				$saveComprobante = $this->Comprobantes_model->guardarComprobante($datosComprobante);
				if($saveComprobante)
				{
					/*REGISTRO DE CUENTAS DEL COMPROBANTE*/
					$filas = explode("|", $detalleComprobante);
					if(!empty($filas))
					{
						foreach($filas as $fila)
						{
							if(!empty($fila) && $fila != "undefined" && $fila != "null")
							{
								$row = explode("*", $fila);
								if(!isset($row[0]) || empty($row[0]))
								{
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
									// echo("FALSOOOOO");
									$resul = 0;
									$mensaje = "ERROR EN EL REGISTRO DETALLE COMPROBANTE....!!!";
								}

							}
														
						}
					}
				}
				else
				{
					$resul = 0;
					$mensaje = "ERROR EN EL REGISTRO!!!";
				}
			}
			else
			{
				$idComprobante   	  = $data['id_comprobanteP'];
				$updateComprobante = array(
                    'periodo'                 => $periodo,
                    'gestion'                 => $gestion,
                    'glosa_comprobante' 	  => $glosa_general,
					'referencia_comprobante'  => $referencia_general,
					'fecha_comprobante'       => $fecha_comprobante,
					'tipo_cambio'             => $tipo_cambio,
					'fecha_modificacion'      => $fechaActual,
					'id_funcionario_update'   => $id_funcionario
				);
				$saveComprobante = $this->Comprobantes_model->updateComprobante($idComprobante,$updateComprobante);
				if($saveComprobante)
				{
					$resul = 1;
					$mensaje = "SE REGISTRO CORRECTAMENTE XXXXX";
				}
				else
				{
					$resul = 0;
					$mensaje = "ERROR EN EL REGISTRO!!!";
				}
			}			
		}
		/******TRANSACT*****/
		if ($this->db->trans_status() === FALSE && $resul == 1) { 
			$this->db->trans_rollback(); // Deshacer los cambios si hay un error
			// $resultado = 0;
		} else {
			$this->db->trans_commit(); // Confirmar los cambios si todo está bien
			// $resultado = 1;
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

		foreach ($filas as $fila)
		{   
			$boton   = "
                        <span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Editar'>
                            <button type='button' class='btn btn-block btn-info btn-sm' onclick=\"editarComprobante(". $fila->id . ")\"><i class='fas fa-edit'></i></button>     
                        </span>	
                        <span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Imprimir'>
                            <button type='button' class='btn btn-block btn-warning btn-sm' onclick=\"generarReportePComprobanteRegistrado(". $fila->id . ")\"><i class='fas fa-print'></i></button>     
                        </span>	
                        <span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Eliminar'>
                            <button type='button' class='btn btn-block btn-danger btn-sm' onclick='bajaEntidad(". $fila->id . ")'><i class='fas fa-trash-alt'></i></button>     
                        </span>	
                        ";	
						
			$tipo_comprobante = getValor2Configuraciones("TIPO COMPROBANTES CONTABLE", $fila->tipo_comprobante);
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
	function eliminarRegistroCuenta()
	{				
		$accion 				  = $this->input->post('accion');
		$id_cuenta   			  = $this->input->post('id_cuenta');
		$codigoCuenta   		  = $this->input->post('codigoCuenta');
		$tipo_movimiento   		  = $this->input->post('tipo_movimiento');
		$tipo_movimiento_literal  = $this->input->post('tipo_movimiento_literal');
		$importe   				  = $this->input->post('importe');
		$tipo_cambio   			  = $this->input->post('tipo_cambio');
		$glosa_cuenta   		  = $this->input->post('glosa_cuenta');
		$cadRegistroCuenta		  = $this->input->post('cadRegistroCuenta');

		$doc  			= $this->input->post('doc');
		$nrodoc   		= $this->input->post('nrodoc');
		$fechadoc 		= $this->input->post('fechadoc');
		$cadDocumentos 	=$this->input->post('documentos');
		if(trim($accion) == 'nuevo')
		{
			$cadCuentas = str_replace('*'.$id_cuenta.'*'.$codigoCuenta.'*'.$tipo_movimiento.'*'.$tipo_movimiento_literal.'*'.$importe.'*'.$tipo_cambio.'*'.$glosa_cuenta."|","",$cadRegistroCuenta);
			$mensaje = array("resultado" => 1 , "mensaje"=>"Se ha eliminado el registro", "cuentas"=> $cadCuentas);
		}
		else{
			$data = array (
			'estado' => 'AN',
			'fecha' => date('Y-m-d H:i:s')
			);
			$filas = $this->entidaddocumento_model->updateEntidadDocumento($id,$data);
			$mensaje = array("resultado" => 1 , "mensaje"=>"Se ha eliminado el registro", "cuentas"=>"");
		}	
		echo json_encode($mensaje);
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
	public function listarPlanDeCuentasBusquedaComprobante()
    {
		$cuentas   = $this->PlanDeCuentas_model->getPlanDeCuentas();
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

			if (($fila['es_padre']) && ($fila['indentacion']== 0)) {
				$descripcion = "<strong><u>{$descripcion}</u></strong>";
				$codigo =  "<strong><u>{$codigo}</u></strong>";
			}
			$data[] = array(
				"<div style='text-align: center;'>$boton</div>",
				"<span class='badge badge-secondary'>".$codigo."</span>",
				$descripcion
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
	function ReporteComprobanteRegistradoPDF($id_comprobante,$accion)
	{

		$id_dependencia      = $this->session->userdata('id_dependencia_principal');
		// $datos_json = json_decode($this->input->post('datos'));
		// $detalle_json =json_decode($this->input->post('detalleComprobante'));
		
		// parse_str($datos_json, $datos1);		
		
		$datosComprobante    = $this->Comprobantes_model->getComprobanteById($id_comprobante);
		// echo json_encode($datosComprobante);
		/****************************/
		/*INICIO DEL REPORTE*/
		/****************************/
		$orden = array("\r\n", "\n", "\r" ,'"') ;
		$pdf=new exFPDFCartaContable('P','mm','Letter');
		// $this->load->library('fpdf/pdf2');
		// $pdf=new Pdf2('P','mm','Letter');
		$pdf->fechahora_impresion='SI';
		// $pdf->opcion_pie ="PAGINADOR_FECHA";
		$pdf->SetFont('Arial','',14);
		$pdf->AliasNbPages();
		$pdf->opcion_pie='COMPROBANTE';
		// $pdf->marcaDeAgua = $marcaAgua;
		$pdf->AddPage(); 


		/****************************/
		/*DATOS CABECERA DEL REPORTE*/
		/****************************/
		// $accion = $datos1['txtAccionComprobante'];
		if($accion === 'editar'){

			$id_comprobante					= $datosComprobante[0]->id;
			$marcaAgua='SI';
			$nombre_entidad					= descripcion_nombre_entidad($datosComprobante[0]->id_entidad);
			$sigla_entidad					= "XXXXXX";/*CONSULTAR*/
			$sigla_senape					= "SENAPE";
			// $this->Cell(20, 3, utf8_decode('Página ' . $this->PageNo() . '/{nb}'), 0, 0, 'C');
			$paginador 						= "Pag. 1/2";
			// $paginador 						= utf8_decode('Página ' . $pdf->PageNo() . '/{nb}');
			$fecha_comprobante  			= formato_fecha_slash($datosComprobante[0]->fecha_comprobante);
			$tipo_cambio					= number_format($datosComprobante[0]->tipo_cambio,2,'.',',');
			$tipo_comprobante				= "COMPROBANTE DE ".getValor2Configuraciones("TIPO COMPROBANTES CONTABLE", $datosComprobante[0]->tipo_comprobante);
			// $mes							= date("n", strtotime($fecha_comprobante));
			$mes							= $datosComprobante[0]->periodo;
			$periodo						= mb_strtolower(getValor2Configuraciones("MESES", $mes));
			$gestion						= $datosComprobante[0]->gestion;
			$correlativo      		        = $datosComprobante[0]->correlativo;
			$correlativo_comprobante		= $correlativo;
			$referencia_comprobante 		= $datosComprobante[0]->referencia_comprobante;
			$glosa_general					= $datosComprobante[0]->glosa_comprobante;


			$table = new easyTable($pdf, '{40,90,60}', 'width:190;align:{C,R,R}; font-size:7; bgcolor:#F2F2F2;border:0;border-color:#c0c0c0;valign:M;');

			$texto_cabecera_izquierda = utf8_decode(mb_strtoupper($nombre_entidad)) . "\n" .
										utf8_decode(mb_strtoupper($sigla_entidad)) . "\n" .
										utf8_decode(mb_strtoupper($sigla_senape));
			$table->easyCell($texto_cabecera_izquierda, 'valign:M;halign:C;font-size:7');

			$table->easyCell(' ', 'valign:M;halign:C;font-size:7');

			$texto_cabecera_derecha = utf8_decode($paginador) . "\n" .
									utf8_decode($fecha_comprobante) . "\n" .
									utf8_decode("T.C.:".$tipo_cambio);
			$table->easyCell($texto_cabecera_derecha, 'valign:M;halign:R;font-size:7');

			$table->printRow();
			$table->endTable(0);

			$table = new easyTable($pdf, '{190}', 'width:190;align:{C}; font-size:8; bgcolor:#F2F2F2;border:0;border-color:#c0c0c0;valign:M;');
			$table->easyCell(utf8_decode(mb_strtoupper($tipo_comprobante)), 'valign:M;halign:C;font-size:8 ;font-style:BU');
			$table->printRow();
			$table->endTable(0);

			$table = new easyTable($pdf, '{40,25,40,35,50}', 'width:190; align:{L,L,R,L,L}; font-size:8; bgcolor:#F2F2F2;border:0;border-color:#c0c0c0;valign:M;');

			$table->easyCell(' ', 'valign:M;font-size:7;halign:R');
			$table->easyCell(' ', 'valign:M;font-size:7;halign:R');

			$texto_periodo =  utf8_decode("Periodo: <b>".$periodo."-".$gestion."</b>");
			$table->easyCell($texto_periodo, 'val
			// $filas = explode("|", $detalleComprobante);ign:M;font-size:8;halign:R;');

			$texto_nrocomprobante =  utf8_decode("Nro: <b>".$correlativo_comprobante."</b>");
			$table->easyCell($texto_nrocomprobante, 'valign:M;font-size:8;halign:L;');

			$table->easyCell(' ', 'valign:M;font-size:7;halign:R');

			$table->printRow();
			$table->endTable(2);


			$table = new easyTable($pdf, '{190}', 'width:190;align:{L}; font-size:8; bgcolor:#FFFFFF;border:0;border-color:#c0c0c0;valign:M;');
			$table->easyCell(utf8_decode("<b>Ref.:</b>".$referencia_comprobante), 'valign:M;halign:L;font-size:8 ;font-style:N');
			$table->printRow();
			$table->endTable(2);

			/******************************/
			/*CUERPO*/
			/******************************/
			/*CABECERA*/
			$table=new easyTable($pdf, '{30,80,20,20,20,20}', 'width:190;align:{CLCCCC}; font-size:7; paddingY:1;bgcolor:#F2F2F2;border:LTRB;border-color:#c0c0c0;line-height:1.2;');
			$table->easyCell(utf8_decode("CÓDIGO"),'valign:M;font-style:B;font-size:6;' );
			$table->easyCell(utf8_decode("DESCRIPCIÓN"),'valign:M;font-style:B;font-size:6;' );
			$table->easyCell(utf8_decode("DEBE") ,'valign:M;font-style:B;font-size:6;' );
			$table->easyCell(utf8_decode("HABER"),'valign:M;font-style:B;font-size:6;' );
			$table->easyCell(utf8_decode("DEBE Us."),'valign:M;font-style:B;font-size:6;' );
			$table->easyCell(utf8_decode("HABER Us."),'valign:M;font-style:B;font-size:6;' );
			$table->printRow(true);
			$table->endTable(0);

			$table=new easyTable($pdf, '{30,80,20,20,20,20}', 'width:190;align:{LLRRRR}; font-size:7; paddingY:1;bgcolor:#F2F2F2;border:LR;border-color:#c0c0c0;line-height:1.2;');
			// $detalleComprobante=$detalle_json;		
			// $filas = explode("|", $detalleComprobante);
			$detalleComprobante    = $this->Comprobantes_model->getDetalleComprobanteById($id_comprobante);
			// echo json_encode($detalleComprobante);
			$importeDebe=0;
			$importeHaber=0;
			$importeDebeUs=0;
			$importeHaberUs=0;
			$totalimporteDebe=0;
			$totalimporteHaber=0;
			$totalimporteDebeUs=0;
			$totalimporteHaberUs=0;
			$nro_registros=0;

			if(!empty($detalleComprobante))
			{
				foreach($detalleComprobante as $fila)
				{
							$importeDebe=0;
							$importeHaber=0;
							$importeDebeUs=0;
							$importeHaberUs=0;

							$id_registro 			   = $fila->id;
							$id_cuenta   			   = $fila->id_cuenta;
							$descripcion_cuenta		   = getCuenta($id_cuenta);
							$tipo_movimiento		   = $fila->tipo_movimiento;
							$tipo_movimiento_literal   = getValor2Configuraciones("TIPO MOVIMIENTO", $fila->tipo_movimiento);
							$importe				   = $fila->importe_moneda_nacional;
							$importe_moneda_extranjera = $fila->importe_moneda_extranjera;
							$tipo_cambio			   = $fila->tipo_cambio;
							$glosa_cuenta			   = $fila->glosa_cuenta;
							$codigo_cuenta			   = getCodigoCuenta($id_cuenta);
							
							if($tipo_movimiento == "DB")
							{
								$importeDebe   = $importe;
								$importeDebeUs = $importe_moneda_extranjera;
								// $importeDebeUs=$tipo_cambio==0?0:$importe/$tipo_cambio;
							}
							elseif ($tipo_movimiento == "HB") {
								$importeHaber   = $importe;
								$importeHaberUs = $importe_moneda_extranjera;
								// $importeHaberUs=$tipo_cambio==0?0:$importe/$tipo_cambio;
							}

							$cuenta_registro = "<b>".$descripcion_cuenta."</b> \n" .$glosa_cuenta;
							$table->easyCell($codigo_cuenta,'valign:M;bgcolor:#fff;font-size:7;rowspam:2;' );
							$table->easyCell(utf8_decode("<b>".$descripcion_cuenta."</b>"),'valign:M;font-style:N ;bgcolor:#fff;font-size:7;font-style:BU' );
							
							if($importeDebe > 0)
							{
								$table->easyCell(number_format($importeDebe, 2, '.', ','),'valign:M;halign:R;font-style:N ;bgcolor:#fff;font-size:7;' );
							}
							else
							{
								$table->easyCell('','valign:M;halign:R;font-style:N ;bgcolor:#fff;font-size:7;' );
							}
							if($importeHaber > 0)
							{
								$table->easyCell(number_format($importeHaber,2,'.',','),'valign:M;halign:R;bgcolor:#fff;font-size:7;' );
							}
							else
							{
								$table->easyCell('','valign:M;halign:R;bgcolor:#fff;font-size:7;' );
							}
							if($importeDebeUs>0)
							{
								$table->easyCell(number_format($importeDebeUs,2,'.',','),'valign:M;halign:R;bgcolor:#fff;font-size:7;' );
							}
							else
							{
								$table->easyCell('','valign:M;halign:R;bgcolor:#fff;font-size:7;' );
							}
							if($importeHaberUs>0)
							{
								$table->easyCell(number_format($importeHaberUs,2,'.',','),'valign:M;halign:R;bgcolor:#fff;font-size:7;' );
							}
							else
							{
								$table->easyCell('','valign:M;halign:R;bgcolor:#fff;font-size:7;' );
							}
							$table->printRow();
							$table->easyCell('','valign:M;halign:R;bgcolor:#fff;font-size:7;' );
							$table->easyCell(utf8_decode($glosa_cuenta),'valign:M;font-style:N ;bgcolor:#fff;font-size:7;' );
							$table->easyCell('','valign:M;halign:R;font-style:N ;bgcolor:#fff;font-size:7;' );
							$table->easyCell('','valign:M;halign:R;font-style:N ;bgcolor:#fff;font-size:7;' );
							$table->easyCell('','valign:M;halign:R;font-style:N ;bgcolor:#fff;font-size:7;' );
							$table->easyCell('','valign:M;halign:R;font-style:N ;bgcolor:#fff;font-size:7;' );
							$table->printRow();

							$totalimporteDebe+=$importeDebe;
							$totalimporteHaber+=$importeHaber;
							$totalimporteDebeUs+=$importeDebeUs;
							$totalimporteHaberUs+=$importeHaberUs;
				}
			}
				
			$table->endTable(0);
		}
		
		$table=new easyTable($pdf, '{110,20,20,20,20}', 'width:190;align:{RRRRR}; font-size:7; paddingY:1;bgcolor:#F2F2F2;border:LTRB;border-color:#c0c0c0;line-height:1.2;');
		$table->easyCell(utf8_decode("TOTALES:"),'valign:M;font-style:B;font-size:6;' );
		$table->easyCell(number_format($totalimporteDebe, 2, '.', ','),'valign:M;font-style:N ;bgcolor:#fff;font-size:7;' );
		$table->easyCell(number_format($totalimporteHaber,2,'.',','),'valign:M;bgcolor:#fff;font-size:7;' );
		$table->easyCell(number_format($totalimporteDebeUs,2,'.',','),'valign:M;bgcolor:#fff;font-size:7;' );
		$table->easyCell(number_format($totalimporteHaberUs,2,'.',','),'valign:M;bgcolor:#fff;font-size:7;' );
		$table->printRow(true);
		$table->endTable(3);
		$table=new easyTable($pdf, '{110,7,30,7,30,6}', 'width:190;align:{LCCCCC}; font-size:7; paddingY:1;bgcolor:#F2F2F2;border-color:#c0c0c0;line-height:1.2;');

		$table->easyCell(utf8_decode("<b>Glosa:</b> ".$glosa_general), 'valign:T;rowspan:5;font-size:7;');
		$table->easyCell(utf8_decode(""),'valign:B;font-style:N ;bgcolor:#fff;font-size:8' );	
		$table->easyCell(utf8_decode(""),'valign:B;font-style:N ;bgcolor:#fff;font-size:8' );	
		$table->easyCell(utf8_decode(""),'valign:B;font-style:N ;bgcolor:#fff;font-size:8' );	
		$table->easyCell(utf8_decode(""),'valign:B;font-style:N ;bgcolor:#fff;font-size:8' );	
		$table->easyCell(utf8_decode(""),'valign:B;font-style:N ;bgcolor:#fff;font-size:8' );	
		$table->printRow(true);	

		$table->easyCell(utf8_decode(""),'valign:B;font-style:N ;bgcolor:#fff;font-size:8' );	
		$table->easyCell(utf8_decode(""),'valign:B;font-style:N ;bgcolor:#fff;font-size:8' );	
		$table->easyCell(utf8_decode(""),'valign:B;font-style:N ;bgcolor:#fff;font-size:8' );	
		$table->easyCell(utf8_decode(""),'valign:B;font-style:N ;bgcolor:#fff;font-size:8' );	
		$table->easyCell(utf8_decode(""),'valign:B;font-style:N ;bgcolor:#fff;font-size:8' );	
		$table->printRow(true);	

		$table->easyCell(utf8_decode(""),'valign:B;font-style:N ;bgcolor:#fff;font-size:8' );	
		$table->easyCell(utf8_decode(""),'valign:B;font-style:N ;bgcolor:#fff;font-size:8' );	
		$table->easyCell(utf8_decode(""),'valign:B;font-style:N ;bgcolor:#fff;font-size:8' );	
		$table->easyCell(utf8_decode(""),'valign:B;font-style:N ;bgcolor:#fff;font-size:8' );	
		$table->easyCell(utf8_decode(""),'valign:B;font-style:N ;bgcolor:#fff;font-size:8' );	
		$table->printRow(true);	

		$table->easyCell(utf8_decode(""),'valign:B;font-style:N ;bgcolor:#fff;font-size:8' );	
		$table->easyCell(utf8_decode(""),'valign:B;font-style:N ;bgcolor:#fff;font-size:8' );	
		$table->easyCell(utf8_decode(""),'valign:B;font-style:N ;bgcolor:#fff;font-size:8' );	
		$table->easyCell(utf8_decode(""),'valign:B;font-style:N ;bgcolor:#fff;font-size:8' );	
		$table->easyCell(utf8_decode(""),'valign:B;font-style:N ;bgcolor:#fff;font-size:8' );	
		$table->printRow(true);	


		$pie_contador="CONTADOR";
		$pie_gerentegeneral="GERENTE GENERAL";
		
		$table->easyCell(utf8_decode(""),'valign:B;font-style:N ;bgcolor:#fff;font-size:8;' );	
		$table->easyCell(utf8_decode("$pie_contador"),'valign:B;font-style:N ;bgcolor:#fff;font-size:8;border:T;' );	
		$table->easyCell(utf8_decode(""),'valign:B;font-style:N ;bgcolor:#fff;font-size:8;T;' );	
		$table->easyCell(utf8_decode($pie_gerentegeneral),'valign:B;font-style:N ;bgcolor:#fff;font-size:8;border:T;' );	
		$table->easyCell(utf8_decode(""),'valign:B;font-style:N ;bgcolor:#fff;font-size:8;' );	
		$table->printRow(true);	
		$table->endTable(5);
				
		$pdf->Output('I',utf8_decode('ReporteComprobante.pdf')); 
	}
	function ReporteComprobantePDF()
	{

		$id_dependencia      = $this->session->userdata('id_dependencia_principal');
		// $datos_json = $this->input->post('datos');
		$datos_json = json_decode($this->input->post('datos'));
		$detalle_json =json_decode($this->input->post('detalleComprobante'));
		// ******parse_str($this->input->post('datos'), $data1);
		parse_str($datos_json, $datos1);		
		// echo("<pre>");
		// print_r($datos1);
		// echo("<br>");
		// print_r($detalle_json);
		// echo("</pre>");
		// parse_str($this->input->post('datos'), $data);
		$orden = array("\r\n", "\n", "\r" ,'"') ;
		$pdf=new exFPDFCartaContable('P','mm','Letter');
		// $this->load->library('fpdf/pdf2');
		// $pdf=new Pdf2('P','mm','Letter');
		$pdf->fechahora_impresion='SI';
		// $pdf->opcion_pie ="PAGINADOR_FECHA";
		$pdf->SetFont('Arial','',14);
		$pdf->AliasNbPages();
		$pdf->opcion_pie='COMPROBANTE';
		// $pdf->marcaDeAgua = $marcaAgua;
		$pdf->AddPage(); 


		/****************************/
		/*DATOS CABECERA DEL REPORTE*/
		/****************************/
		$accion = $datos1['txtAccionComprobante'];
		if($accion === 'nuevo'){

			$marcaAgua='SI';
			$nombre_entidad					= descripcion_nombre_entidad($datos1['id_entidad']);
			$sigla_entidad					= "XXXXXX";/*CONSULTAR*/
			$sigla_senape					= "SENAPE";
			// $this->Cell(20, 3, utf8_decode('Página ' . $this->PageNo() . '/{nb}'), 0, 0, 'C');
			$paginador 						= "Pag. 1/2";
			// $paginador 						= utf8_decode('Página ' . $pdf->PageNo() . '/{nb}');
			$fecha_comprobante  			= formato_fecha_slash($datos1['txtFecha']);
			$tipo_cambio					= number_format($datos1['txtTipoCambio'],2,'.',',');
			$tipo_comprobante				= "COMPROBANTE DE ".getValor2Configuraciones("TIPO COMPROBANTES CONTABLE", $datos1['txtTipo']);
			$mes							= date("n", strtotime($fecha_comprobante));
			$periodo						= mb_strtolower(getValor2Configuraciones("MESES", $mes));
			$gestion						= date("Y", strtotime($fecha_comprobante));	
			$tipoCorrelativo  				= $datos1['txtTipo'] ;
			$datosCorrelativo   			= json_decode(obtenerCorrelativoComprobanteGestionEntidad($tipoCorrelativo,$datos1["id_entidad"],$id_dependencia, $gestion));
			$idcorrelativoentidadgestion    = $datosCorrelativo[0]->idcorrelativoentidadgestion;
			$correlativo      		        = $datosCorrelativo[0]->correlativo;
			$correlativo_comprobante		= $correlativo;
			$referencia_comprobante 		= ".....";
			$glosa_general					= $datos1['txtGlosaGeneral'];

			$table = new easyTable($pdf, '{40,90,60}', 'width:190;align:{C,R,R}; font-size:7; bgcolor:#F2F2F2;border:0;border-color:#c0c0c0;valign:M;');

			$texto_cabecera_izquierda = utf8_decode(mb_strtoupper($nombre_entidad)) . "\n" .
										utf8_decode(mb_strtoupper($sigla_entidad)) . "\n" .
										utf8_decode(mb_strtoupper($sigla_senape));
			$table->easyCell($texto_cabecera_izquierda, 'valign:M;halign:C;font-size:7');

			$table->easyCell(' ', 'valign:M;halign:C;font-size:7');

			$texto_cabecera_derecha = utf8_decode($paginador) . "\n" .
									utf8_decode($fecha_comprobante) . "\n" .
									utf8_decode("T.C.:".$tipo_cambio);
			$table->easyCell($texto_cabecera_derecha, 'valign:M;halign:R;font-size:7');

			$table->printRow();
			$table->endTable(0);

			$table = new easyTable($pdf, '{190}', 'width:190;align:{C}; font-size:8; bgcolor:#F2F2F2;border:0;border-color:#c0c0c0;valign:M;');
			$table->easyCell(utf8_decode(mb_strtoupper($tipo_comprobante)), 'valign:M;halign:C;font-size:8 ;font-style:BU');
			$table->printRow();
			$table->endTable(0);

			$table = new easyTable($pdf, '{40,25,40,35,50}', 'width:190; align:{L,L,R,L,L}; font-size:8; bgcolor:#F2F2F2;border:0;border-color:#c0c0c0;valign:M;');

			$table->easyCell(' ', 'valign:M;font-size:7;halign:R');
			$table->easyCell(' ', 'valign:M;font-size:7;halign:R');

			$texto_periodo =  utf8_decode("Periodo: <b>".$periodo."-".$gestion."</b>");
			$table->easyCell($texto_periodo, 'valign:M;font-size:8;halign:R;');

			$texto_nrocomprobante =  utf8_decode("Nro: <b>".$correlativo_comprobante."</b>");
			$table->easyCell($texto_nrocomprobante, 'valign:M;font-size:8;halign:L;');

			$table->easyCell(' ', 'valign:M;font-size:7;halign:R');

			$table->printRow();
			$table->endTable(2);


			$table = new easyTable($pdf, '{190}', 'width:190;align:{L}; font-size:8; bgcolor:#FFFFFF;border:0;border-color:#c0c0c0;valign:M;');
			$table->easyCell(utf8_decode("<b>Ref.:</b>".$referencia_comprobante), 'valign:M;halign:L;font-size:8 ;font-style:N');
			$table->printRow();
			$table->endTable(2);

			/******************************/
			/*CUERPO*/
			/******************************/
			/*CABECERA*/
			$table=new easyTable($pdf, '{30,80,20,20,20,20}', 'width:190;align:{CLCCCC}; font-size:7; paddingY:1;bgcolor:#F2F2F2;border:LTRB;border-color:#c0c0c0;line-height:1.2;');
			$table->easyCell(utf8_decode("CÓDIGO"),'valign:M;font-style:B;font-size:6;' );
			$table->easyCell(utf8_decode("DESCRIPCIÓN"),'valign:M;font-style:B;font-size:6;' );
			$table->easyCell(utf8_decode("DEBE") ,'valign:M;font-style:B;font-size:6;' );
			$table->easyCell(utf8_decode("HABER"),'valign:M;font-style:B;font-size:6;' );
			$table->easyCell(utf8_decode("DEBE Us."),'valign:M;font-style:B;font-size:6;' );
			$table->easyCell(utf8_decode("HABER Us."),'valign:M;font-style:B;font-size:6;' );
			$table->printRow(true);
			$table->endTable(0);

			$table=new easyTable($pdf, '{30,80,20,20,20,20}', 'width:190;align:{LLRRRR}; font-size:7; paddingY:1;bgcolor:#F2F2F2;border:LR;border-color:#c0c0c0;line-height:1.2;');
			$detalleComprobante=$detalle_json;		
			$filas = explode("|", $detalleComprobante);
			
			$importeDebe=0;
			$importeHaber=0;
			$importeDebeUs=0;
			$importeHaberUs=0;
			$totalimporteDebe=0;
			$totalimporteHaber=0;
			$totalimporteDebeUs=0;
			$totalimporteHaberUs=0;
			$nro_registros=0;

			if(!empty($filas))
			{
				foreach($filas as $fila)
				{
					$importeDebe=0;
					$importeHaber=0;
					$importeDebeUs=0;
					$importeHaberUs=0;
					if(!empty($fila) && $fila != "undefined" && $fila != "null")
					{
						$row = explode("*", $fila);
						if(!isset($row[0]) || empty($row[0]))
						{
							
							list($ini,$id_cuenta,$cuenta, $tipo_movimiento,$tipo_movimiento_literal,$importe,$tipo_cambio,$glosa_cuenta) = $row;

							$codigoCuenta = explode("-",$cuenta);
							list($codigo_cuenta,$descripcion_cuenta)= $codigoCuenta;
							if($tipo_movimiento == "DB")
							{
								$importeDebe=$importe;
								// $importeDebeUs=$tipo_cambio==0?0:round($importe/$tipo_cambio,2);
								$importeDebeUs=$tipo_cambio==0?0:$importe/$tipo_cambio;
							}
							elseif ($tipo_movimiento == "HB") {
								$importeHaber=$importe;
								// $importeHaberUs=$tipo_cambio==0?0:round($importe/$tipo_cambio,2);
								$importeHaberUs=$tipo_cambio==0?0:$importe/$tipo_cambio;
							}
							$cuenta_registro = "<b>".$descripcion_cuenta."</b> \n" .$glosa_cuenta;
							$table->easyCell($codigo_cuenta,'valign:M;bgcolor:#fff;font-size:7;rowspam:2;' );
							$table->easyCell(utf8_decode("<b>".$descripcion_cuenta."</b>"),'valign:M;font-style:N ;bgcolor:#fff;font-size:7;font-style:BU' );
							
							if($importeDebe > 0)
							{
								$table->easyCell(number_format($importeDebe, 2, '.', ','),'valign:M;halign:R;font-style:N ;bgcolor:#fff;font-size:7;' );
							}
							else
							{
								$table->easyCell('','valign:M;halign:R;font-style:N ;bgcolor:#fff;font-size:7;' );
							}
							if($importeHaber > 0)
							{
								$table->easyCell(number_format($importeHaber,2,'.',','),'valign:M;halign:R;bgcolor:#fff;font-size:7;' );
							}
							else
							{
								$table->easyCell('','valign:M;halign:R;bgcolor:#fff;font-size:7;' );
							}
							if($importeDebeUs>0)
							{
								$table->easyCell(number_format($importeDebeUs,2,'.',','),'valign:M;halign:R;bgcolor:#fff;font-size:7;' );
							}
							else
							{
								$table->easyCell('','valign:M;halign:R;bgcolor:#fff;font-size:7;' );
							}
							if($importeHaberUs>0)
							{
								$table->easyCell(number_format($importeHaberUs,2,'.',','),'valign:M;halign:R;bgcolor:#fff;font-size:7;' );
							}
							else
							{
								$table->easyCell('','valign:M;halign:R;bgcolor:#fff;font-size:7;' );
							}
							$table->printRow();
							$table->easyCell('','valign:M;halign:R;bgcolor:#fff;font-size:7;' );
							$table->easyCell(utf8_decode($glosa_cuenta),'valign:M;font-style:N ;bgcolor:#fff;font-size:7;' );
							$table->easyCell('','valign:M;halign:R;font-style:N ;bgcolor:#fff;font-size:7;' );
							$table->easyCell('','valign:M;halign:R;font-style:N ;bgcolor:#fff;font-size:7;' );
							$table->easyCell('','valign:M;halign:R;font-style:N ;bgcolor:#fff;font-size:7;' );
							$table->easyCell('','valign:M;halign:R;font-style:N ;bgcolor:#fff;font-size:7;' );
							$table->printRow();

							$totalimporteDebe+=$importeDebe;
							$totalimporteHaber+=$importeHaber;
							$totalimporteDebeUs+=$importeDebeUs;
							$totalimporteHaberUs+=$importeHaberUs;

						}
					}
				}
			}
				
			$table->endTable(0);
		}
		$table=new easyTable($pdf, '{110,20,20,20,20}', 'width:190;align:{RRRRR}; font-size:7; paddingY:1;bgcolor:#F2F2F2;border:LTRB;border-color:#c0c0c0;line-height:1.2;');
		$table->easyCell(utf8_decode("TOTALES:"),'valign:M;font-style:B;font-size:6;' );
		$table->easyCell(number_format($totalimporteDebe, 2, '.', ','),'valign:M;font-style:N ;bgcolor:#fff;font-size:7;' );
		$table->easyCell(number_format($totalimporteHaber,2,'.',','),'valign:M;bgcolor:#fff;font-size:7;' );
		$table->easyCell(number_format($totalimporteDebeUs,2,'.',','),'valign:M;bgcolor:#fff;font-size:7;' );
		$table->easyCell(number_format($totalimporteHaberUs,2,'.',','),'valign:M;bgcolor:#fff;font-size:7;' );
		$table->printRow(true);
		$table->endTable(3);
		$table=new easyTable($pdf, '{110,7,30,7,30,6}', 'width:190;align:{LCCCCC}; font-size:7; paddingY:1;bgcolor:#F2F2F2;border-color:#c0c0c0;line-height:1.2;');

		$table->easyCell(utf8_decode("<b>Glosa:</b> ".$glosa_general), 'valign:T;rowspan:5;font-size:7;');
		$table->easyCell(utf8_decode(""),'valign:B;font-style:N ;bgcolor:#fff;font-size:8' );	
		$table->easyCell(utf8_decode(""),'valign:B;font-style:N ;bgcolor:#fff;font-size:8' );	
		$table->easyCell(utf8_decode(""),'valign:B;font-style:N ;bgcolor:#fff;font-size:8' );	
		$table->easyCell(utf8_decode(""),'valign:B;font-style:N ;bgcolor:#fff;font-size:8' );	
		$table->easyCell(utf8_decode(""),'valign:B;font-style:N ;bgcolor:#fff;font-size:8' );	
		$table->printRow(true);	

		$table->easyCell(utf8_decode(""),'valign:B;font-style:N ;bgcolor:#fff;font-size:8' );	
		$table->easyCell(utf8_decode(""),'valign:B;font-style:N ;bgcolor:#fff;font-size:8' );	
		$table->easyCell(utf8_decode(""),'valign:B;font-style:N ;bgcolor:#fff;font-size:8' );	
		$table->easyCell(utf8_decode(""),'valign:B;font-style:N ;bgcolor:#fff;font-size:8' );	
		$table->easyCell(utf8_decode(""),'valign:B;font-style:N ;bgcolor:#fff;font-size:8' );	
		$table->printRow(true);	

		$table->easyCell(utf8_decode(""),'valign:B;font-style:N ;bgcolor:#fff;font-size:8' );	
		$table->easyCell(utf8_decode(""),'valign:B;font-style:N ;bgcolor:#fff;font-size:8' );	
		$table->easyCell(utf8_decode(""),'valign:B;font-style:N ;bgcolor:#fff;font-size:8' );	
		$table->easyCell(utf8_decode(""),'valign:B;font-style:N ;bgcolor:#fff;font-size:8' );	
		$table->easyCell(utf8_decode(""),'valign:B;font-style:N ;bgcolor:#fff;font-size:8' );	
		$table->printRow(true);	

		$table->easyCell(utf8_decode(""),'valign:B;font-style:N ;bgcolor:#fff;font-size:8' );	
		$table->easyCell(utf8_decode(""),'valign:B;font-style:N ;bgcolor:#fff;font-size:8' );	
		$table->easyCell(utf8_decode(""),'valign:B;font-style:N ;bgcolor:#fff;font-size:8' );	
		$table->easyCell(utf8_decode(""),'valign:B;font-style:N ;bgcolor:#fff;font-size:8' );	
		$table->easyCell(utf8_decode(""),'valign:B;font-style:N ;bgcolor:#fff;font-size:8' );	
		$table->printRow(true);	
		$pie_contador="CONTADOR";
		$pie_gerentegeneral="GERENTE GENERAL";
		
		$table->easyCell(utf8_decode(""),'valign:B;font-style:N ;bgcolor:#fff;font-size:8;' );	
		$table->easyCell(utf8_decode("$pie_contador"),'valign:B;font-style:N ;bgcolor:#fff;font-size:8;border:T;' );	
		$table->easyCell(utf8_decode(""),'valign:B;font-style:N ;bgcolor:#fff;font-size:8;T;' );	
		$table->easyCell(utf8_decode($pie_gerentegeneral),'valign:B;font-style:N ;bgcolor:#fff;font-size:8;border:T;' );	
		$table->easyCell(utf8_decode(""),'valign:B;font-style:N ;bgcolor:#fff;font-size:8;' );	
		$table->printRow(true);	
		$table->endTable(5);
				
		$pdf->Output('I',utf8_decode('ReporteComprobante.pdf')); 
	}
	/*EDITAR*/
	public function cargarComprobanteByIdComprobanteEntidad()
	{
		$id_usuario       = $this->session->userdata('id_usuario');
		$id_entidad  = $this->input->post('id_entidad');
		$id_comprobanteP  = $this->input->post('id_comprobanteP');

		/*DATOS CABECERA*/
		
		$draw    = intval($this->input->get("draw"));
		$start   = intval($this->input->get("start"));
		$length  = intval($this->input->get("length"));	
		$data    = array();
		$num     = 1;

		
		$datos_comprobante  	 = $this->Comprobantes_model->getComprobanteById($id_comprobanteP);

		if($datos_comprobante)
		{
			$id_comprobante  		 = $id_comprobanteP;
			$id_entidad      		 = $id_entidad;
			$tipo_comprobante 		 = $datos_comprobante[0]->tipo_comprobante;
			$correlativo     		 = $datos_comprobante[0]->correlativo;
			$periodo     		     = $datos_comprobante[0]->periodo;
			$gestion      		     = $datos_comprobante[0]->gestion ;
			$referencia_comprobante  = $datos_comprobante[0]->referencia_comprobante;
			$glosa_comprobante     	 = $datos_comprobante[0]->glosa_comprobante;
			$fecha_comprobante     	 = formato_fecha_slash_invertido2($datos_comprobante[0]->fecha_comprobante);
			$tipo_cambio     		 = $datos_comprobante[0]->tipo_cambio;
			$estado     		     = $datos_comprobante[0]->estado;
			$resul 				     = 1;
			$mensaje				 = "OK";	
		}
	
		$data = array(
						'id_comprobante'  		 => $id_comprobante,
						'id_entidad'      		 => $id_entidad,
						'tipo_comprobante' 		 => $tipo_comprobante,
						'correlativo'     		 => $correlativo,
						'periodo'     		     => $periodo,
						'gestion'      		     => $gestion ,
						'referencia_comprobante' => $referencia_comprobante,
						'glosa_comprobante'    	 => $glosa_comprobante,
						'fecha_comprobante'    	 => $fecha_comprobante,
						'tipo_cambio'     		 => $tipo_cambio,
						'estado'     		     => $estado,
						'resultado'				 => $resul,
						'mensaje'				 => $mensaje
					);
		echo json_encode($data);
		// exit();
	}
	public function cargarDetalleComprobanteByIdComprobanteEntidad()
	{
		$id_usuario  = $this->session->userdata('id_usuario');
			
		$draw    = intval($this->input->get("draw"));
		$start   = intval($this->input->get("start"));
		$length  = intval($this->input->get("length"));	
		$data    = array();
		$num     = 1;

		$id_comprobante  = $this->input->post('id_comprobante');
		$detalleComprobante    = $this->Comprobantes_model->getDetalleComprobanteById($id_comprobante);
		// echo json_encode($detalleComprobante);
		$importeDebe=0;
		$importeHaber=0;
		$importeDebeUs=0;
		$importeHaberUs=0;
		$totalimporteDebe=0;
		$totalimporteHaber=0;
		$totalimporteDebeUs=0;
		$totalimporteHaberUs=0;
		$nro_registros=count($detalleComprobante);	

		foreach ($detalleComprobante as $fila)
		{   
			$boton   = "
                        <span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Editar'>
                            <button type='button' class='btn btn-block btn-info btn-sm' onclick=\"editarComprobante(". $fila->id . ")\"><i class='fas fa-edit'></i></button>     
                        </span>	
                        <span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Eliminar'>
                            <button type='button' class='btn btn-block btn-danger btn-sm' onclick='bajaEntidad(". $fila->id . ")'><i class='fas fa-trash-alt'></i></button>     
                        </span>	
                        ";	
			$importeDebe=0;
			$importeHaber=0;
			$importeDebeUs=0;
			$importeHaberUs=0;

			$id_registro 			   = $fila->id;
			$id_cuenta   			   = $fila->id_cuenta;
			$descripcion_cuenta		   = getCuenta($id_cuenta);
			$tipo_movimiento		   = $fila->tipo_movimiento;
			$tipo_movimiento_literal   = getValor2Configuraciones("TIPO MOVIMIENTO", $fila->tipo_movimiento);
			$importe				   = $fila->importe_moneda_nacional;
			$importe_moneda_extranjera = $fila->importe_moneda_extranjera;
			$tipo_cambio			   = $fila->tipo_cambio;
			$glosa_cuenta			   = $fila->glosa_cuenta;
			$codigo_cuenta			   = getCodigoCuenta($id_cuenta);
			
			if($tipo_movimiento == "DB")
			{
				$importeDebe   = $importe;
				$importeDebeUs = $importe_moneda_extranjera;
				// $importeDebeUs=$tipo_cambio==0?0:$importe/$tipo_cambio;
			}
			elseif ($tipo_movimiento == "HB") {
				$importeHaber   = $importe;
				$importeHaberUs = $importe_moneda_extranjera;
				// $importeHaberUs=$tipo_cambio==0?0:$importe/$tipo_cambio;
			}

			$cuenta_registro = "<b>".$descripcion_cuenta."</b> \n" .$glosa_cuenta;			


			$botonEditar = "<div style='text-align: center;'>
						<span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Editar Registro Cuenta'>
							<button type='button' class='btn btn-primary btn-xs mr-1' onclick=\"editarRegistroCuentaComprobante(".$fila->id.")\"><i>✏️</i></button>
						</span>										
					</div>";
			$botonEliminar = "<div style='text-align: center;'>
						<span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Eliminar Registro Cuenta'>
							<button type='button' class='btn btn-danger btn-xs' onclick=\"eliminarRegistroCuentaComprobante(".$fila->id.")\"><i>🗑️</i></button>
						</span>										
					</div>";
			$cuenta_registro = "<b>".$descripcion_cuenta."</b><br>".$glosa_cuenta;
			$data[] = array(
				"<span class='badge badge-secondary'>".$codigo_cuenta."</span>",
				$cuenta_registro,
				"<div style='text-align: right; color: #28a745; font-weight: bold;'>".number_format($importeDebe, 2, '.', ',')."</div>",
				"<div style='text-align: right; color: #dc3545; font-weight: bold;'>".number_format($importeHaber,2,'.',',')."</div>",
				"<div style='text-align: right; color: #28a745; font-weight: bold;'>".number_format($importeDebeUs,2,'.',',')."</div>",
				"<div style='text-align: right; color: #dc3545; font-weight: bold;'>".number_format($importeHaberUs,2,'.',',')."</div>",
				$botonEliminar.$botonEditar
			);
			$totalimporteDebe+=$importeDebe;
			$totalimporteHaber+=$importeHaber;
			$totalimporteDebeUs+=$importeDebeUs;
			$totalimporteHaberUs+=$importeHaberUs;

		}
		$output = array(
            "draw" => $draw,
            "recordsTotal" => count($detalleComprobante)-1,
            "recordsFiltered" => count($detalleComprobante)-1,
            "nro_registros" => $nro_registros,
            "totalimporteDebe" => number_format($totalimporteDebe,2,'.',','),
            "totalimporteHaber" => number_format($totalimporteHaber,2,'.',','),
            "totalimporteDebeUs" => number_format($totalimporteDebeUs,2,'.',','),
            "totalimporteHaberUs" => number_format($totalimporteHaberUs,2,'.',','),
            "data" => $data
        );
	    echo json_encode($output);
	    exit();
	}
	
}