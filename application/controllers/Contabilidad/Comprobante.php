<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
	public function registroComprobante($entidad,$accion='nuevo')
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

		// $idEntidad = $this->input->post('id_entidad');
		$idEntidad = '123';
		return ($idEntidad);
		// die();

		// if (empty($idEntidad) || !is_numeric($idEntidad)) {
		// 	$this->form_validation->set_message('verificarEntidad', 'No se ha registrado una entidad para asociar el comprobante.');
		// 	return FALSE;
		// }
		// return TRUE;
	}
	public function fecha_valida()
	{
		$fecha = $this->input->post('txtFecha');
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
		$draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

		$accion 		   = $this->input->post('accion');
		$cadRegistroCuenta = $this->input->post('cuenta');
		$id_entidad        = $this->input->post('id_entidad');
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
				if($accion == "nuevo")
				{  
					$filas = explode("|", $cadRegistroCuenta);  
					$nro_registros = count($filas)-1;
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


 		$this->form_validation->set_data($data);
 		$resul = 1;
		$mensaje = "OK";

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
		$detalleComprobante  = $this->input->post('detalleComprobante');

		$en=  $this->verificarEntidad();
		echo($en);
		die();

		$validacomprobante   = json_decode($this->validarDatos($data));	
		$resultado   = $validacomprobante[0]->resultado;
		$mensaje     = $validacomprobante[0]->mensaje;
		// echo($resultado);
		// echo($mensaje);
        // $resul=1;
        // $mensaje = "OK";
        $idComprobante="";
		$this->db->trans_start();
        if($resultado == 1)
		{
			
            $accion      		  = $data['txtAccionComprobante'];
			$idComprobante   	  = $data['id_comprobante'];
			$id_entidad       	  = $data['id_entidad'];
			$tipo_comprobante     = $data['txtTipo'];
			$fecha_comprobante    = $data['txtFecha'];
			$tipo_cambio	      = $data['txtTipoCambio'];
			$glosa_general	      = $data['txtGlosaGeneral'];
			$correlativo		  = 0;
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
									echo("FALSOOOOO");
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


		/****************** */
		/******TRANSACT*****/
		/****************** */
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
                            <button type='button' class='btn btn-block btn-warning btn-sm' onclick=\"editarEntidad(". $fila->id . ")\"><i class='fas fa-edit'></i></button>     
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
	
}