<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PlanDeCuentas extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->_is_logued_in();
        $this->load->model('PlanDeCuentas_model');
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
		$dato['nombre_sistema']  = "MERCURIO";
		$dato['tipo_sistema']  = "Sistema Contable";
		
		
		$id_usuario = $this->session->userdata('id_usuario');
		$dato['rolescero'] = $this->session->userdata('rolescero');
		$dato['roles']  = $this->session->userdata('roles');
		$dato['nombre_usuario']  = $this->session->userdata('nombre_completo');

		$titulo = "Plan de Cuentas";		
		$dato['titulo'] = $titulo;

		$this->load->view('inicio/cabecera',$dato);
		$this->load->view('inicio/menu',$dato);
		$this->load->view('contabilidad/plandecuentas',$dato);
		$this->load->view('inicio/pie');
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
	function listCuentas()
	{
<<<<<<< HEAD
		$id_entidad = $this->input->post('id_entidad');
		$cuentas   = $this->PlanDeCuentas_model->getPlanDeCuentas($id_entidad);
		$cuentas   = json_decode(json_encode($cuentas), true);
=======
		$cuentas   = $this->PlanDeCuentas_model->getPlanDeCuentas();
		$cuentas = json_decode(json_encode($cuentas), true);
>>>>>>> 86e86a998fd13133562672039854ed148e2211bf
		$ordenadas = $this->ordenarJerarquicamente($cuentas);
		$option= "";
		foreach($ordenadas as $fila)
		{
			$option .="<option data-value='".$fila['id']."'><b>".$fila['codigo']."</b>-". $fila['descripcion'] ."</option>";
		}
		echo $option;
	}
<<<<<<< HEAD

    public function listarPlanDeCuentas()
    {
		$id_entidad = $this->input->post('id_entidad');
		$cuentas    = $this->PlanDeCuentas_model->getPlanDeCuentas($id_entidad);
		$cuentas    = json_decode(json_encode($cuentas), true);
		$ordenadas  = $this->ordenarJerarquicamente($cuentas);
=======
    public function listarPlanDeCuentas()
    {
		$cuentas   = $this->PlanDeCuentas_model->getPlanDeCuentas();
		$cuentas = json_decode(json_encode($cuentas), true);
		$ordenadas = $this->ordenarJerarquicamente($cuentas);
>>>>>>> 86e86a998fd13133562672039854ed148e2211bf

		// echo json_encode($ordenadas);
		// die();
		
		$draw    = intval($this->input->get("draw"));
		$start   = intval($this->input->get("start"));
		$length  = intval($this->input->get("length"));	
		$data    = array();
		$num     = 1;
		$mayores = "";
		foreach ($ordenadas as $fila)
		{   
			if($fila['estado']==='ACT')
			{
				$boton   = "
                        <span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Editar'>
                            <button type='button' class='btn btn-block btn-warning btn-sm' onclick=\"editarCuentas(". $fila['id']. ",'". $fila['codigo']."','". $fila['sigla']."','". $fila['descripcion']."','". $fila['tipo_moneda_cuenta']."')\"><i class='fas fa-edit'></i></button>     
                        </span>	
                        <span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Agregar SubCuenta'>
<<<<<<< HEAD
                            <button type='button' class='btn btn-block btn-info btn-sm' onclick=\"agregarSubCuentas(". $fila['id']. ",'". $fila['codigo']."','". $fila['descripcion']."',". $fila['nivel'].",". $fila['padre'] .",'". $fila['ruta'] ."',". $fila['id_entidad'] .")\"><i class='fas fa-plus-circle'></i></button>     
                        </span>				
                        <span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='AGREGAR CUENTAS AUXILIARES'>
                            <button type='button' class='btn btn-block btn-success btn-sm' onclick=\"agregarCuentasAuxiliares(". $fila['id']. ",'". $fila['codigo']."','". $fila['sigla']."','". $fila['descripcion']."',". $fila['id_entidad'] .")\"><i class='fas fa-list-alt'></i></button>     
=======
                            <button type='button' class='btn btn-block btn-info btn-sm' onclick=\"agregarSubCuentas(". $fila['id']. ",'". $fila['codigo']."','". $fila['descripcion']."',". $fila['nivel'].",". $fila['padre'] .",'". $fila['ruta'] ."')\"><i class='fas fa-plus-circle'></i></button>     
                        </span>				
                        <span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='AGREGAR CUENTAS AUXILIARES'>
                            <button type='button' class='btn btn-block btn-success btn-sm' onclick=\"agregarCuentasAuxiliares(". $fila['id']. ",'". $fila['codigo']."','". $fila['sigla']."','". $fila['descripcion']."')\"><i class='fas fa-list-alt'></i></button>     
>>>>>>> 86e86a998fd13133562672039854ed148e2211bf
                        </span>				
                        ";	

				// Códigos que NO deben poder eliminarse
<<<<<<< HEAD
				$protegidos = [1,2,3,4,5,6,7,8,9,10];
=======
				$protegidos = [1,2,3,4,5,6,7,8,9];
>>>>>>> 86e86a998fd13133562672039854ed148e2211bf
					

				if ( !in_array($fila['codigo'], $protegidos) ) {
					$mayores = "
						<span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Eliminar'>
<<<<<<< HEAD
							<button type='button' class='btn btn-block btn-danger btn-sm' onclick='bajaCuenta(". $fila['id']. ",". $fila['id_entidad'] .")'>
=======
							<button type='button' class='btn btn-block btn-danger btn-sm' onclick='bajaAplicacion(". $fila['id']. ")'>
>>>>>>> 86e86a998fd13133562672039854ed148e2211bf
								<i class='fas fa-trash-alt'></i>
							</button>     
						</span>";
				}
				else
				{
					$mayores ="";
					// echo($fila['codigo']);		
				}
				
			}
			else
			{
				$boton = "";
			}
				

			$indentacion		= str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $fila['indentacion']);
			$descripcion 		= $fila['descripcion'];
			$codigo      		= $fila['codigo'];
			$codigo_cuenta      = $fila['codigo_cuenta'];

			if (($fila['es_padre']) && ($fila['indentacion']== 0)) {
				$descripcion = "<strong><u>{$descripcion}</u></strong>";
				$codigo =  "<strong><u>{$codigo}</u></strong>";
			}

			$cadena = $fila['ruta'];
			$partes = explode('-', $cadena);
			$cont =count($partes);
			// $primer_valor = $partes[0];
			// $segundo_valor = $partes[1];
			if($cont == 1)
			{
				$tipo =getCuenta($fila['padre']);
			}
			else
			{
				$segundo_valor = $partes[1];
				$tipo =getCuenta($segundo_valor);
			}

			$codigoXXX="";

			for ($i = 1; $i < $cont; $i++) {
				$valor=$partes[$i];
				$codigoXXX =$codigoXXX.".".getCodigoCuentaById($valor);
			}
			$codigoXXX=$codigoXXX.".".$codigo_cuenta;


			$estado =getValor2Configuraciones("ESTADO REGISTRO", $fila['estado']);
			switch ($fila['estado']) {
			case "ACT":
				$estado="<span class='badge badge-success'>".$estado."</span>";
				break;
			case "ANU":
				$estado="<span class='badge badge-danger'>".$estado."</span>";
				// $boton="";
				break;
			default:
				$estado="<span class='badge badge-secondary'>".$estado."</span>";
				break;
			}
			
			$moneda =getValor2Configuraciones("MONEDA", $fila['tipo_moneda_cuenta']);

			$data[] = array(
				$boton.$mayores,
				// $num++,
				// $indentacion."<span class='badge badge-secondary'>".$codigo."</span>",
				"<span class='badge badge-secondary'>".$codigo."</span>",
				$indentacion.$descripcion,	
				$tipo,
                $fila['nivel'],
				$moneda,
                // $fila['sigla'],
				$estado,
				// "<span class='badge badge-secondary'>".substr($codigoXXX,1)."</span>"
			);
<<<<<<< HEAD
			/*VERIFICAR SI UNA CUENTA POSEE CUENTAS AUXILIARES*/ 
			$cuentas_auxiliares = $this->PlanDeCuentas_model->getAuxiliaresPlanDeCuentasById($fila['id']);

			foreach ($cuentas_auxiliares as $cuenta_auxiliar) {
				
				$descripcion 		= $cuenta_auxiliar->descripcion;
				$codigo      		= $cuenta_auxiliar->codigo;
		
				$codigo =  "<strong>{$codigo}</strong>";
				$descripcion = "<strong>{$descripcion}</strong>";
				
				$estado =getValor2Configuraciones("ESTADO REGISTRO", $cuenta_auxiliar->estado);
				switch ($cuenta_auxiliar->estado) {
				case "ACT":
					$estado="<span class='badge badge-success'>".$estado."</span>";
					break;
				case "ANU":
					$estado="<span class='badge badge-danger'>".$estado."</span>";
					break;
				default:
					$estado="<span class='badge badge-secondary'>".$estado."</span>";
					break;
				}
				
				$boton="";
				$data[] = array(
					$boton,
					"<span class='badge badge-primary'>".$codigo."</span>",
					$indentacion.$descripcion,	
					$tipo,
					$fila['nivel'],
					$moneda,
					$estado
				);
			}
=======
>>>>>>> 86e86a998fd13133562672039854ed148e2211bf
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
	public function guardarPlanDeCuentas()
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
        $opcionPadre="";
        if($resul == 1)
		{
            $accion      = $data['txtAccion'];
			$codigo      = $data['txtCodigoCuenta'];
			$descripcion = $data['txtDescripcionCuenta'];
			$id_cuenta   = $data['idCuenta'];
			$sigla       = $data['txtSiglaCuenta'];
<<<<<<< HEAD
			$id_entidad  = $data['id_entidad'];
=======
>>>>>>> 86e86a998fd13133562672039854ed148e2211bf
			// $nivel       = $data['opcionNivel'];
			
			if($accion === 'nuevo')
			{
				/*DATOS PARA NUEVO REGISTRO */
				$nivel       = $data['nivel'];
				$ruta        = 0;
				$padre       = 0;
				/*DATOS PARA NUEVO REGISTRO */
				$datosPlanCuentas = array(
					'codigo'                  => $codigo,
					'descripcion'             => $descripcion,
                    'nivel'                   => $nivel,
                    'padre'                   => $padre,
                    'ruta'                    => $ruta,
                    'id_funcionario_registro' => $id_funcionario,
<<<<<<< HEAD
					'sigla'                   => $sigla,
					'id_entidad'              => $id_entidad
=======
					'sigla'                   => $sigla
>>>>>>> 86e86a998fd13133562672039854ed148e2211bf
				);

				$plancuentas = $this->PlanDeCuentas_model->guardarPlanDeCuentas($datosPlanCuentas);
				if($plancuentas)
				{
					/*DATOS PARA NUEVO REGISTRO DE PLANCUENTA DEPENDENCIA */
					$datosPlanCuentasDependencia = array(
						'id_plancuenta'              => $plancuentas,
						'id_dependencia'          => $id_dependencia,
						'id_usuario_registro'     => $id_funcionario
					);
					$plancuentas_dependencia = $this->PlanDeCuentas_model->guardarPlanDeCuentasDependencia($datosPlanCuentasDependencia);

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
				$updatePlanCuenta = array(
					'codigo' 				    => $codigo,
					'descripcion'       		=> $descripcion,
					'sigla'       				=> $sigla,
					'fecha_modificacion'		=> $fecha_actual,
					'id_funcionario_update'		=> $id_funcionario
				   );

				$plancuenta = $this->PlanDeCuentas_model->updatePlanDeCuentas($id_cuenta,$updatePlanCuenta);
				if($plancuenta)
				{
					$resul = 1;
					$mensaje = "SE ACTUALIZÓ LOS DATOS DE LA CUENTA CORRECTAMENTE.";
				}
				else
				{
					$resul = 0;
					$mensaje = "ERROR EN LA ACTUALIZACIÓN!!!";
				}
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
    // public function cargarCuentaSuperior()
	// {
	// 	$nivel = $this->input->post('nivel');
		
	//     $option = "<option VALUE='-1'>Seleccione un opción</OPTION>";

    //     $filas = $this->PlanDeCuentas_model->getPlanDeCuentasByNivel($nivel);
    //     foreach ($filas as $fila)
    //     {
    //         $option.="<option value = '".$fila->id."'>".$fila->descripcion."</option>";
    //     }
	//     echo $option;
	// }
	public function guardarPlanDeSubCuentas()
	{
		$id_usuario       = $this->session->userdata('id_usuario');
		$id_funcionario   = $this->session->userdata('id_funcionario');
		$id_dependencia   = $this->session->userdata('id_dependencia_principal');
		$data 			  = $this->input->post();

		// $resultado   = json_decode($this->validarDatos($data));		
		// $resul       = $resultado[0]->resultado;
		// $mensaje     = $resultado[0]->mensaje;
        $resul=1;
        $mensaje = "OK";
        $opcionPadre="";
		$nivel_subcuenta=0;
        if($resul == 1)
		{
            $accion         = $data['txtAccionSubCuenta'];
			/*DATOS CUENTA PRINCIPAL SELECCIONADA*/
			$id_cuenta      = $data['id_cuenta'];
			$nivel          = $data['nivel_padre'];
			$padre          = $data['id_padre'];
			$ruta           = $data['ruta'];
			$codigo_cuenta_p= $data['codigo_cuenta_padre'];
			
			
			/*DATOS A REGISTRAR DE LA SUBCUENTA*/
			$codigo      		= $data['txtCodigo'];
			$descripcion 		= $data['txtDescripcion'];
			$nivel_subcuenta	= (int)$nivel+1;
			$padre_subcuenta	= $id_cuenta;
			// $ruta_subcuenta		= $padre."-".$id_cuenta;
			$ruta_subcuenta		= $ruta."-".$id_cuenta;
			$sigla       		= $data['txtSigla'];
			$codigo_cuenta      = str_replace($codigo_cuenta_p, "", $codigo);
			$moneda       		= $data['txtTipoMoneda'];
<<<<<<< HEAD
			$id_entidad    		= $data['id_entidad_sub'];
=======
>>>>>>> 86e86a998fd13133562672039854ed148e2211bf

			if($accion === 'nuevo')
			{
				$datosPlanCuentas = array(
					'codigo'                  => $codigo,
					'descripcion'             => $descripcion,
                    'nivel'                   => $nivel_subcuenta,
                    'padre'                   => $padre_subcuenta,
                    'ruta'                    => $ruta_subcuenta,
                    'id_funcionario_registro' => $id_funcionario,
					'sigla'                   => $sigla,
					'codigo_cuenta'			  => $codigo_cuenta,
<<<<<<< HEAD
					'tipo_moneda_cuenta'      => $moneda,
					'id_entidad'              => $id_entidad
=======
					'tipo_moneda_cuenta'      => $moneda
>>>>>>> 86e86a998fd13133562672039854ed148e2211bf
				);
				// echo json_encode($datosPlanCuentas);
				// die();

				$plancuentas = $this->PlanDeCuentas_model->guardarPlanDeCuentas($datosPlanCuentas);
				if($plancuentas)
				{
					/*DATOS PARA NUEVO REGISTRO DE PLANCUENTA DEPENDENCIA */
					$datosPlanCuentasDependencia = array(
						'id_plancuenta'           => $plancuentas,
						'id_dependencia'          => $id_dependencia,
						'id_usuario_registro'     => $id_funcionario
					);
					$plancuentas_dependencia = $this->PlanDeCuentas_model->guardarPlanDeCuentasDependencia($datosPlanCuentasDependencia);

					$resul = 1;
					$mensaje = "SE REGISTRO CORRECTAMENTE";
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
	public function listarPlanDeSubCuentas()
    {
		$id_cuenta = $this->input->post('id_cuenta');
		$filas   = $this->PlanDeCuentas_model->getPlanDeCuentasByPadre($id_cuenta);
		
		$draw    = intval($this->input->get("draw"));
		$start   = intval($this->input->get("start"));
		$length  = intval($this->input->get("length"));	
		$data    = array();
		$num     = 1;

		foreach ($filas as $fila)
		{   

			$estado =getValor2Configuraciones("ESTADO REGISTRO", $fila->estado);
			switch ($fila->estado) {
			case "ACT":
				$estado="<span class='badge badge-success'>".$estado."</span>";
				break;
			case "ANU":
				$estado="<span class='badge badge-danger'>".$estado."</span>";
				// $boton="";
				break;
			default:
				$estado="<span class='badge badge-secondary'>".$estado."</span>";
				break;
			}



			$data[] = array(
				$num++,
				$fila->codigo,
				$fila->descripcion,			
                $fila->nivel,
				$estado
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
	/*VERIFICAR HIJOS DE LA CUENTA===>Verificar si la cuenta ya fue usada no se puede modificar ni eliminar */
	function verificarCuenta()
	{
		$id_cuenta           = $this->input->post('id_cuenta');
		$datosCuentaHijos    = count($this->PlanDeCuentas_model->getPlanDeCuentasByPadre($id_cuenta));

		if($datosCuentaHijos>0)
		{

			$resul           =  1;
			$mensaje         =  "La cuenta seleccionada tiene subcuentas asociadas, por lo que no puede ser eliminada.";
			$asociacionHRP   =  1;
		}
		// else
		// {
		// 	$datosCiteHojaRuta = $this->cites_model->getCite_HojaRuta($id_cite);
		// 	if(count($datosCiteHojaRuta)>1)
		// 	{

		// 		$resul           =  1;
		// 		$mensaje         =  "Cite asociado a una hoja de ruta clonada, no se puede realizar la solicitud.";
		// 	}
		// }
		$resultado ='[{					
					"resultado":"'.$resul.'",
					"mensaje":"'.$mensaje.'"
					}]';
		echo $resultado; 
	}
<<<<<<< HEAD
	/*PARA REPORTES */
	public function listarPlanDeCuentasBusqueda()
    {
		$id_entidad = $this->input->post('id_entidad');
		$cuentas   = $this->PlanDeCuentas_model->getPlanDeCuentas($id_entidad);
=======
	public function listarPlanDeCuentasBusqueda()
    {
		$cuentas   = $this->PlanDeCuentas_model->getPlanDeCuentas();
>>>>>>> 86e86a998fd13133562672039854ed148e2211bf
		$cuentas = json_decode(json_encode($cuentas), true);
		$ordenadas = $this->ordenarJerarquicamente($cuentas);

		// echo json_encode($ordenadas);
		// die();
		
		$draw    = intval($this->input->get("draw"));
		$start   = intval($this->input->get("start"));
		$length  = intval($this->input->get("length"));	
		$data    = array();
		$num     = 1;

		foreach ($ordenadas as $fila)
		{   

			$boton   = "
                        <span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Agregar SubCuenta'>
                            <button type='button' class='btn btn-success btn-sm' onclick=\"editarCuentas(". $fila['id']. ",'". $fila['codigo']."','". $fila['sigla']."','". $fila['descripcion']."','". $fila['tipo_moneda_cuenta']."')\"><i>✓</i></button>     
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
	/*GUARDAR CUENTAS AUXILIARES */
	public function guardarAuxiliarPlanCuentas()
	{
		$id_usuario       = $this->session->userdata('id_usuario');
		$id_funcionario   = $this->session->userdata('id_funcionario');
		$id_dependencia   = $this->session->userdata('id_dependencia_principal');
		$data 			  = $this->input->post();

		// $resultado   = json_decode($this->validarDatos($data));		
		// $resul       = $resultado[0]->resultado;
		// $mensaje     = $resultado[0]->mensaje;
        $resul=1;
        $mensaje = "OK";
        $opcionPadre="";
		$nivel_subcuenta=0;
        if($resul == 1)
		{
            $accion      = $data['txtAccionSubCuenta'];
			/*DATOS CUENTA PRINCIPAL SELECCIONADA*/
			$id_cuenta   	   = $data['id_cuenta'];		
			
			/*DATOS A REGISTRAR DEL AUXILIAR DE LA CUENTA*/
			$codigo      		= $data['txtCodigoAux'];
			$descripcion 		= $data['txtDescripcionAux'];
			
			if($accion === 'nuevo')
			{
				$datosAuxiliarPlanCuentas = array(
					'id_plancuenta'           => $id_cuenta,
					'codigo'	              => $codigo,
					'descripcion'             => $descripcion,
                    'id_funcionario_registro' => $id_funcionario
				);
				$auxiliarplancuentas = $this->PlanDeCuentas_model->guardarAuxiliaresPlanDeCuentas($datosAuxiliarPlanCuentas);
				if($auxiliarplancuentas)
				{
					$resul = 1;
					$mensaje = "SE REGISTRO CORRECTAMENTE";
				}
				else
				{
					$resul = 0;
					$mensaje = "ERROR EN EL REGISTRO!!!";
				}
			}
			else
			{
					///PROCESO
			}

			
		}

		$resultado ='[{
						"resultado":"'.$resul.'",
						"mensaje":"'.$mensaje.'"
					 }]';

		echo $resultado;
	}
	public function listarAuxiliaresPlanDeCuentas()
    {
		$id_cuenta = $this->input->post('id_cuenta');
		$filas   = $this->PlanDeCuentas_model->getAuxiliaresPlanDeCuentasById($id_cuenta);
		
		$draw    = intval($this->input->get("draw"));
		$start   = intval($this->input->get("start"));
		$length  = intval($this->input->get("length"));	
		$data    = array();
		$num     = 1;

		foreach ($filas as $fila)
		{   

			$boton   = "
                        <span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Eliminar'>
                            <button type='button' class='btn btn-block btn-danger btn-sm' onclick='eliminarAuxiliarCuenta(".$fila->id. ",".$fila->id_plancuenta.")'><i class='fas fa-trash-alt'></i></button>     
                        </span>				
                        ";		

			$estado =getValor2Configuraciones("ESTADO REGISTRO", $fila->estado);
			switch ($fila->estado) {
			case "ACT":
				$estado="<span class='badge badge-success'>".$estado."</span>";
				break;
			case "ANU":
				$estado="<span class='badge badge-danger'>".$estado."</span>";
				break;
			default:
				$estado="<span class='badge badge-secondary'>".$estado."</span>";
				break;
			}

			$data[] = array(
				$num++,
				$fila->codigo,
				$fila->descripcion,			
				$estado,
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
	public function eliminarAuxiliarCuenta()
	{
		$id_usuario       = $this->session->userdata('id_usuario');
		$id_funcionario   = $this->session->userdata('id_funcionario');

		$id_auxiliar_cuenta = $this->input->post('id_auxiliar_cuenta');
		$fecha_actual	    = getFechaHoraActual();
		$estado  		    = 'ANU';
		$dataAuxiliarCuenta     = array(
									'fecha_modificacion'    => $fecha_actual,
									'id_funcionario_update' => $id_funcionario,
									'estado'       		    => $estado
								 );
		
		$updateAuxiliarCuenta = $this->PlanDeCuentas_model->updateAuxiliarCuenta($id_auxiliar_cuenta,$dataAuxiliarCuenta);
		if($updateAuxiliarCuenta)
		{
			$resul = 1;
			$mensaje = "SE ELIMINO EL AUXILIAR DE LA CUENTA CORRECTAMENTE.";
		}
		else
		{
			$resul = 0;
			$mensaje = "ERROR EN LA ELIMINACIÓN!!!";
		}
		

		$resultado ='[{
						"resultado":"'.$resul.'",
						"mensaje":"'.$mensaje.'"
					 }]';

<<<<<<< HEAD
	
=======
		echo $resultado;
		$resultado ='[{
						"resultado":"'.$resul.'",
						"mensaje":"'.$mensaje.'"
					 }]';

>>>>>>> 86e86a998fd13133562672039854ed148e2211bf
		echo $resultado;
	}
	public function buscarCuentas() {
		$nivel 				  = $this->input->post('filtroNivel');
		$codigo_mayor 		  = $this->input->post('filtroMayores');
		$codigo_subcuenta 	  = $this->input->post('filtroSubCuentas');
		$codigo_otrascuentas  = $this->input->post('filtroOtrasSubCuentas');
		$cadena_busqueda      = $this->input->post('filtroBusqueda');
<<<<<<< HEAD
		$id_entidad			  = $this->input->post('id_entidad');
=======
>>>>>>> 86e86a998fd13133562672039854ed148e2211bf

		// echo("Ingresaaaa");
		$sql="";

		if(!empty($cadena_busqueda))
		{
			$cadena_busqueda = trim($cadena_busqueda);
			$sql .= "AND (codigo LIKE '".$cadena_busqueda."%' OR descripcion LIKE '".$cadena_busqueda."%') ";
		}
		else{
			if (!empty($nivel) and $nivel != "-1") {
				$sql .= "AND nivel = ".$nivel." ";
			}
<<<<<<< HEAD
			if ((!empty($codigo_mayor) and $codigo_mayor != "-1") && empty($codigo_subcuenta)) {
				$sql .= "AND padre = ".$codigo_mayor." ";
			}
			if (!empty($codigo_subcuenta) and $codigo_subcuenta != "-1" and $codigo_otrascuentas == "-1") {
				// $sql = " AND ruta like( '0-".$codigo_mayor."-".$codigo_subcuenta."%')";
				$sql = " AND ruta like( '0-".$codigo_mayor."%')";
=======
			if (!empty($codigo_mayor) and $codigo_mayor != "-1" || empty($codigo_subcuenta)) {
				$sql .= "AND padre = ".$codigo_mayor." ";
			}
			if (!empty($codigo_subcuenta) and $codigo_subcuenta != "-1" and $codigo_otrascuentas == "-1") {
				$sql = " AND ruta like( '0-".$codigo_mayor."-".$codigo_subcuenta."%')";
>>>>>>> 86e86a998fd13133562672039854ed148e2211bf
			}else{
				if (!empty($codigo_otrascuentas) and $codigo_otrascuentas != "-1") {
					$sql = " AND ruta like( '0-".$codigo_mayor."-".$codigo_subcuenta."-".$codigo_otrascuentas."%')";
				}
			}
		}
<<<<<<< HEAD
		// echo($sql);
		if($sql == "")
		{
			$sql    .= " AND id_entidad = ".$id_entidad." ";
			$cuentas = $this->PlanDeCuentas_model->buscarPlanDeCuentas($sql);
			$cuentas = json_decode(json_encode($cuentas), true);	
			$cuentas = $this->ordenarJerarquicamente($cuentas);	
		}
		else
		{
			$sql .= " AND id_entidad = ".$id_entidad." ";
			$cuentas   = $this->PlanDeCuentas_model->buscarPlanDeCuentas($sql);
			$cuentas = json_decode(json_encode($cuentas), true);		

		}
		
=======

		$cuentas   = $this->PlanDeCuentas_model->buscarPlanDeCuentas($sql);
		$cuentas = json_decode(json_encode($cuentas), true);		
		// if(!empty($nivel)){
			
		// 	foreach ($cuentas as $k => $fila) {
		// 		$cuentas[$k]['indentacion'] = 0;
		// 		$cuentas[$k]['es_padre'] = $nivel;
		// 	}
		// 	$ordenadas = $cuentas;
		// }
		// else
		// {
		// 	$ordenadas = $this->ordenarJerarquicamente($cuentas);
		// }	
>>>>>>> 86e86a998fd13133562672039854ed148e2211bf
		
		foreach ($cuentas as $k => $fila) {
			$cuentas[$k]['indentacion'] = 0;
			$cuentas[$k]['es_padre'] = $nivel;
		}
		$ordenadas = $cuentas;		
		$draw    = intval($this->input->get("draw"));
		$start   = intval($this->input->get("start"));
		$length  = intval($this->input->get("length"));	
		$data    = array();
		$num     = 1;
		$mayores = "";
		foreach ($ordenadas as $fila)
		{   
			if($fila['estado']==='ACT')
			{
				$boton   = "
                        <span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Editar'>
                            <button type='button' class='btn btn-block btn-warning btn-sm' onclick=\"editarCuentas(". $fila['id']. ",'". $fila['codigo']."','". $fila['sigla']."','". $fila['descripcion']."','". $fila['tipo_moneda_cuenta']."')\"><i class='fas fa-edit'></i></button>     
                        </span>	
                        <span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Agregar SubCuenta'>
                            <button type='button' class='btn btn-block btn-info btn-sm' onclick=\"agregarSubCuentas(". $fila['id']. ",'". $fila['codigo']."','". $fila['descripcion']."',". $fila['nivel'].",". $fila['padre'] .",'". $fila['ruta'] ."')\"><i class='fas fa-plus-circle'></i></button>     
                        </span>				
                        <span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='AGREGAR CUENTAS AUXILIARES'>
                            <button type='button' class='btn btn-block btn-success btn-sm' onclick=\"agregarCuentasAuxiliares(". $fila['id']. ",'". $fila['codigo']."','". $fila['sigla']."','". $fila['descripcion']."')\"><i class='fas fa-list-alt'></i></button>     
                        </span>				
                        ";	

				// Códigos que NO deben poder eliminarse
				$protegidos = [1,2,3,4,5,6,7,8,9];
					

				if ( !in_array($fila['codigo'], $protegidos) ) {
					$mayores = "
						<span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Eliminar'>
<<<<<<< HEAD
							<button type='button' class='btn btn-block btn-danger btn-sm' onclick='bajaCuenta(". $fila['id']. ", ". $fila['id_entidad'] .")'>
=======
							<button type='button' class='btn btn-block btn-danger btn-sm' onclick='bajaAplicacion(". $fila['id']. ")'>
>>>>>>> 86e86a998fd13133562672039854ed148e2211bf
								<i class='fas fa-trash-alt'></i>
							</button>     
						</span>";
				}
				else
				{
					$mayores ="";
					// echo($fila['codigo']);		
				}
				
			}
			else
			{
				$boton = "";
			}
				

			$indentacion		= str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $fila['indentacion']);
			$descripcion 		= $fila['descripcion'];
			$codigo      		= $fila['codigo'];
			$codigo_cuenta      = $fila['codigo_cuenta'];

			if (($fila['es_padre']) && ($fila['indentacion']== 0)) {
				$descripcion = "<strong><u>{$descripcion}</u></strong>";
				$codigo =  "<strong><u>{$codigo}</u></strong>";
			}

			$cadena = $fila['ruta'];
			$partes = explode('-', $cadena);
			$cont =count($partes);
			// $primer_valor = $partes[0];
			// $segundo_valor = $partes[1];
			if($cont == 1)
			{
				$tipo =getCuenta($fila['padre']);
			}
			else
			{
				$segundo_valor = $partes[1];
				$tipo =getCuenta($segundo_valor);
			}

			$codigoXXX="";

			for ($i = 1; $i < $cont; $i++) {
				$valor=$partes[$i];
				$codigoXXX =$codigoXXX.".".getCodigoCuentaById($valor);
			}
			$codigoXXX=$codigoXXX.".".$codigo_cuenta;


			$estado =getValor2Configuraciones("ESTADO REGISTRO", $fila['estado']);
			switch ($fila['estado']) {
			case "ACT":
				$estado="<span class='badge badge-success'>".$estado."</span>";
				break;
			case "ANU":
				$estado="<span class='badge badge-danger'>".$estado."</span>";
				// $boton="";
				break;
			default:
				$estado="<span class='badge badge-secondary'>".$estado."</span>";
				break;
			}
			
			$moneda =getValor2Configuraciones("MONEDA", $fila['tipo_moneda_cuenta']);

			$data[] = array(
				$boton.$mayores,
				// $num++,
				// $indentacion."<span class='badge badge-secondary'>".$codigo."</span>",
				"<span class='badge badge-secondary'>".$codigo."</span>",
				$indentacion.$descripcion,	
				$tipo,
                $fila['nivel'],
				$moneda,
                // $fila['sigla'],
				$estado,
				// "<span class='badge badge-secondary'>".substr($codigoXXX,1)."</span>"
			);
<<<<<<< HEAD

			/*VERIFICAR SI UNA CUENTA POSEE CUENTAS AUXILIARES*/ 
			$cuentas_auxiliares = $this->PlanDeCuentas_model->getAuxiliaresPlanDeCuentasById($fila['id']);

			foreach ($cuentas_auxiliares as $cuenta_auxiliar) {
				
				$descripcion 		= $cuenta_auxiliar->descripcion;
				$codigo      		= $cuenta_auxiliar->codigo;
		
				$codigo =  "<strong>{$codigo}</strong>";
				$descripcion = "<strong>{$descripcion}</strong>";
				
				$estado =getValor2Configuraciones("ESTADO REGISTRO", $cuenta_auxiliar->estado);
				switch ($cuenta_auxiliar->estado) {
				case "ACT":
					$estado="<span class='badge badge-success'>".$estado."</span>";
					break;
				case "ANU":
					$estado="<span class='badge badge-danger'>".$estado."</span>";
					break;
				default:
					$estado="<span class='badge badge-secondary'>".$estado."</span>";
					break;
				}
				
				$boton="";
				$data[] = array(
					$boton,
					"<span class='badge badge-primary'>".$codigo."</span>",
					$indentacion.$descripcion,	
					$tipo,
					$fila['nivel'],
					$moneda,
					$estado
				);
			}







=======
>>>>>>> 86e86a998fd13133562672039854ed148e2211bf
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
<<<<<<< HEAD
	public function bajaCuenta()
	{
		$id_usuario   = $this->session->userdata('id_usuario');
		$id_entidad   = $this->input->post('id_entidad');
		$id_cuenta   = $this->input->post('id_cuenta');
		$fecha_actual = getFechaHoraActual();
		$estado       = 'ANU';
		$updateCuenta = array(
			'fecha_modificacion' => $fecha_actual,
			'id_funcionario_update' => $id_usuario,
			'estado'           => $estado
		);

		$cuenta = $this->PlanDeCuentas_model->updatePlanDeCuentas($id_cuenta, $updateCuenta);
		if($cuenta)
		{
			$resul = 1;
			$mensaje = "SE ELIMINO LA CUENTA CORRECTAMENTE.";
		}
		else
		{
			$resul = 0;
			$mensaje = "ERROR EN LA ELIMINACIÓN!!!";
		}
		$resultado = '[{
						"resultado":"' . $resul . '",
						"mensaje":"' . $mensaje . '"
					 }]';

		echo $resultado;
	}
=======
>>>>>>> 86e86a998fd13133562672039854ed148e2211bf

}
