<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuarios extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->_is_logued_in();
		$this->load->library('form_validation');
		$this->load->model('usuariosmercurio_model');
		$this->load->model('usuarios_model');
		$this->load->model('funcionarios_model');
		$this->load->helper('configuraciones_helper');
		$this->load->model('roles_model');	
		$this->load->helper('usuarios_helper');
	}
	function _is_logued_in()
	{
		//colocar esta confirguracion en el archivo config.php,  $config['IDAPLICACION'] = 3;		

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
				
		//echo json_encode($rolescero);
		$titulo = "USUARIOS DEL SISTEMA";		
		$dato['titulo'] = $titulo;
		$this->load->view('inicio/cabecera',$dato);
		$this->load->view('inicio/menu',$dato);

		$this->load->view('usuarios/usuarios',$dato); //cuerpo
		$this->load->view('inicio/pie');
	}
	function cargarTablaUsuarios()
	{

		$fila = $this->usuariosmercurio_model->getUsuariosSistema(); //*usuarios
		//echo json_encode($fila);
	    $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));	
		$data = array();
		$num = 1;
		$remplace = array("<>");
		$newTexto = array("<br>");
		foreach($fila as $filas)
		{
			// echo("Funcionario Usuario:".$filas->id_fun);
			$dato = $this->funcionarios_model->datosPersonalesDependencia($filas->id_fun);
			$boton ="<span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Editar Permisos'>
						<button type='button' class='btn btn-info btn-circle' onclick=\"opcionesUsuario('".$dato[0]->id."')\"><i class='fa fa-cogs'></i></button>
					</span>
					<!--<span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Dar baja'>
						<button type='button' class='btn btn-danger btn-circle' onclick=\"eliminarUsuario(".$dato[0]->id.")\"><i class='mdi mdi-arrow-down'></i></button>
					</span>-->
					";

	        $data[] = array(
				$boton,
				$num++,
				$dato[0]->nombres." ".$dato[0]->primer_apellido." ".$dato[0]->segundo_apellido,
				$dato[0]->nombre_puesto
	        );
		}
		$output = array(
            "draw" => $draw,
            "recordsTotal" => count($fila),
            "recordsFiltered" => count($fila),
            "data" => $data
        );
	    echo json_encode($output);
	    exit();
	
	}
	function permisosUsuarios()
	{		
		$is_logued_in   			= $this->session->userdata('is_logued_in');
		$id_usuario_administrador 	= $this->session->userdata('id_usuario');				
		$idApliacion 				= $this->config->item('IDAPLICACION');

		$valorOpcionBD 				= 400;//VALORRRRRR ???

		$permiso 	 = verificarPermisosUsuario($id_usuario_administrador,$valorOpcionBD);
		// echo json_encode($permiso);
		//$permiso = array("ss"=>"ww");
		$formulario  = "";
        if($permiso > 0 && $is_logued_in == TRUE)
       	{       			
			$resul = 1;
			$mensaje = "OK";
			$formulario= "<div class='col-12 row'>";
       		$idUsuario      = $this->input->post('idUser');
       		$permisosCero   = $this->roles_model->getPermisosCero($idApliacion);
        	$permisos       = $this->roles_model->getPermisos($idApliacion);
        	$con = 1;            
			$aux = 0;
            $aux1 = 0;
            $aux2 = 0;
            $aux3 = 0;
        	foreach($permisosCero as $fila)
            {
                $nameId1 = "chPermiso_".$fila->id;
                $valor = $fila->id;
                $checkedCom = verificarUsuarioPermiso($valor,$idUsuario);//"checked";
                if($fila->nivel == 0)
                {                
                    $formulario = $formulario."<div class='col-lg-12 grid-margin grid-margin-lg-0'>";
                    if($aux == 0)
                    { 
                        $formulario = $formulario."<h3>Permisos sin dependencias</h3>";
                        $aux=1;
                    }

                    $formulario = $formulario."     <div class='form-check'>
                                                      <input class='form-check-input' type='checkbox' value='".$valor."' name = '".$nameId1."' id='".$nameId1."' ".$checkedCom.">
                                                      <label class='form-check-label' for='flexCheckDefault'>
                                                       ".$fila->opcion."
                                                      </label>
                                                    </div>
                                                </div><br>";     
                }
            }
            foreach($permisos as $fila)
            {
                $nameId1 = "chPermiso_".$fila->id;
                $valor   = $fila->id;
				$checkedCom = verificarUsuarioPermiso($valor,$idUsuario);//"checked";
                if($fila->nivel == 1)
                {                
                    $formulario = $formulario."<div class='col-lg-12 grid-margin grid-margin-lg-0'>";
                    if($aux1 == 0)
                    { 
                        $formulario = $formulario."<h3>Permisos con dependencias</h3>";
                        $aux1 = 1;
                    }

                    $formulario = $formulario."     <div class='form-check'>
                                                      
                                                      <label class='form-check-label' for='flexCheckDefault'><strong><h3>
                                                       ".$fila->opcion."</h3></strong>
                                                      </label>
                                                    </div>
                                                </div>";     
                }
                if($fila->nivel == 2)
                {
                    //$formulario = $formulario."<div class='col-lg-2 grid-margin grid-margin-lg-0'>";               
                    $permisoAnterior = "";
                    $formulario = $formulario."<div class='col-lg-8 grid-margin grid-margin-lg-0'>
                                                    <div class='form-check'>
                                                      <input class='form-check-input' type='checkbox' value='".$valor."' name = '".$nameId1."' id='".$nameId1."' ".$checkedCom.">
                                                      <label class='form-check-label' for='flexCheckDefault'>
                                                       ".$fila->opcion."
                                                      </label>
                                                    </div>
                                                </div>"; 
                }               
                
            }
            $formulario= $formulario."</div>";   		
			echo $formulario;
       	}
       	else
       	{
       		$this->session->sess_destroy();
			$resul = 0;
			$mensaje = "Ocurrio un error al cargar la información de documentos";
			echo 0;
       	} 
	}
	/*GUARDAR PERMISOS PARA USUARIOS  ADMIN*/
	function guardarPermisosUsuarios()
	{		
		$is_logued_in                = $this->session->userdata('is_logued_in');
		$id_usuario_administrador 	 = $this->session->userdata('id_usuario');	
		$fechaActual 				 = getFechaHoraActual();
		$idApliacion 				 = $this->config->item('IDAPLICACION');	
		
		$valorOpcionBD  = 400;
		
		$permiso 		= verificarPermisosUsuario($id_usuario_administrador,$valorOpcionBD);
		$formulario  = "";
        if($permiso > 0 && $is_logued_in == TRUE)
		{       			
			
			$idUsuario     = $this->input->post('txtDato');
			$permisosCero  = $this->roles_model->getPermisosCero($idApliacion);
        	$permisos      = $this->roles_model->getPermisos($idApliacion);
        	$fechaActual   = getFechaHoraActual();
        	$permiso       = 0;
        	foreach($permisosCero as $fila)
            {
				$nombreCampo = "chPermiso_".$fila->id;                       
                $idPermiso   	 = $fila->id;
                $id_aplicacion   = $fila->id_aplicacion;
                
                $permiso     = $this->input->post($nombreCampo);
                if($permiso > 0)
                {

					if(!$this->roles_model->check_opciones($idPermiso,$idUsuario))
                    {                
						$data = array(                    
							'id_opcion'  				=> $idPermiso,
                            'id_usuario' 				=> $idUsuario,
                            'id_aplicacion'				=> $id_aplicacion,
                        );
                        $actualizar = $this->roles_model->guardarOpcionesRol($data); 
                    }
                }
                else
                {

					$rol = $this->roles_model->check_opciones($idPermiso,$idUsuario);                    
                    if($rol)
                    {
						$idRol = $rol[0]->id;
                    	$data  = array(
							'fecha_modificacion' 		=> $fechaActual,
	                        'estado' => 'AN',
                    	);                    	
                    	$actualizar = $this->roles_model->updateRolesUsuario($idRol,$data);
                    }	
                }
            }
			
            foreach($permisos as $fila)
            {
				$nombreCampo = "chPermiso_".$fila->id;                       
                $idPermiso   = $fila->id;
                $permiso     = $this->input->post($nombreCampo);
                $id_aplicacion   = $fila->id_aplicacion;
                if($permiso > 0)
                {
                    if($fila->nivel == 2 )
                    {
                        $idPermisoSuperior = $fila->codigo_opciones;
                        if(!$this->roles_model->check_opciones($idPermisoSuperior,$idUsuario,))
                        {                
                            $data = array(                    
                                'id_opcion'  		=> $idPermisoSuperior,
                                'id_usuario' 		=> $idUsuario,
                                'id_aplicacion'		=> $id_aplicacion,
                            );
                            $actualizar = $this->roles_model->guardarOpcionesRol($data); 
                        }
                        if(!$this->roles_model->check_opciones($idPermiso,$idUsuario,))
	                    {                
	                        $data = array(                    
	                            'id_opcion'  				=> $idPermiso,
	                            'id_usuario' 				=> $idUsuario,
	                            'id_aplicacion'				=> $id_aplicacion,
	                        );
	                        $actualizar = $this->roles_model->guardarOpcionesRol($data); 
	                    }
                    }                    
                }
                else
                {                    
                    if($fila->nivel == 2 )
                    {

	                    $rol = $this->roles_model->check_opciones($idPermiso,$idUsuario);                    
	                    if($rol)
	                    {
	                    	$idRol = $rol[0]->id;                    	
	                    	$data  = array(
		                        'fecha_modificacion' 		=> $fechaActual,
		                        'estado' => 'AN',
	                    	);                    	
	                    	$actualizar = $this->roles_model->updateRolesUsuario($idRol,$data);
	                    }
	                }
                    $idPermisoSuperior = $fila->codigo_opciones;
                    $cantidadRoles = count($this->usuarios_model->verificarNivelSuperior($idUsuario,$idPermisoSuperior));
                    if($cantidadRoles == 1)
                    {

                    	$rol = $this->roles_model->check_opciones($idPermisoSuperior,$idUsuario);                    
	                    if($rol)
	                    {
	                    	$idRol = $rol[0]->id;
	                    	$data  = array(
		                        'fecha_modificacion' 		=> $fechaActual,
		                        'estado' => 'AN',
	                    	);                    	
	                    	$actualizar = $this->roles_model->updateRolesUsuario($idRol,$data);
	                    }
                    }
                }
            }
			$resul = 1;
			$mensaje = "Se Registro correctamente los roles";
				
       	}
       	else
       	{
       		//$this->session->sess_destroy();
			$resul = 0;
			$mensaje = "Ocurrio un error al cargar la información";			
       	} 
       	
       	$resultado ='[{                 
                    "resultado":"'.$resul.'",
                    "mensaje":"'.$mensaje.'"
                    }]';
        echo $resultado;
       	
	}
	/*CARGAR USUARIOS DRP*/
	function cargarTablaUsuariosDRP()
	{
		// $rubro=$this->input->post('rubro');
		$filas = $this->roles_model->getusuariosDRP();
		// echo json_encode($filas);
		$draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));	
		$data = array();
		$num = 1;
		$remplace = array("<>");
		$newTexto = array("<br>");
	    foreach ($filas as $fila)
	    {		
			$boton ="
					<span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Añadir'>
						<button type='button' class='btn btn-info btn-circle' onclick=\"guardarUsuarioAdmin('".$fila->id."')\"><i class='fas fa-plus-circle'></i></button>
					</span>
					";
	        $data[] = array(
				$boton,
				$num++,
				$fila->nombres." ".$fila->primer_apellido." ".$fila->segundo_apellido,
				$fila->nombre_puesto,
				$fila->nombre_subdependencia
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
	function guardarUsuarioSistema()
	{
		// $tipo_documento		= strtoupper(trim($this->input->post('tipo_documento')));
		$id_administrador	= $this->session->userdata('id_usuario');
		$id_dependencia   = $this->session->userdata('id_dependencia_principal');
		$id_fun				= $this->input->post('id_fun');
		$estado  			= "ACT";	
		$verificarUsuario   = $this->usuariosmercurio_model->getUsuariosSistemaId($id_fun);
		$fechaactual		= getFechaHoraActual();
		// $orden = $this->documentos_model->getOrdenDocumentoRubro($rubro);
		// $orden=$orden[0]->max;
		if(!$verificarUsuario)
		{
			$data = array( 
				"id_fun"  	 		 => $id_fun,
				"id_dependencia_principal" 	 => $id_dependencia,
				"id_administrador"   => $id_administrador,
				"fecha_registro"     => $fechaactual,
				"estado"		     => $estado,
				);
	
			$idUsuario = $this->usuariosmercurio_model->guardarUsuarioSistema($data);	
			$resul           =  1;
			$mensaje         =  "OK";

		}	
		else
		{
			$resul = 0;
			$mensaje = "El usuario ya se encuentra registrado en el sistema";
		}
		$resultado ='[{									
					"resultado":"'.$resul.'",
					"mensaje":"'.$mensaje.'"
					}]';
		echo $resultado;

	}
	/*CARGAR USUARIOS */
	function cargarTablaUsuario()
	{
		// $rubro=$this->input->post('rubro');
		$filas = $this->roles_model->getusuariosSistema();
		// echo json_encode($filas);
		$draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));	
		$data = array();
		$num = 1;
		$remplace = array("<>");
		$newTexto = array("<br>");
	    foreach ($filas as $fila)
	    {		
			$boton ="
					<span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Añadir'>
						<button type='button' class='btn btn-info btn-circle' onclick=\"guardarUsuarioAdmin('".$fila->id."')\"><i class='fas fa-plus-circle'></i></button>
					</span>
					";
	        $data[] = array(
				$boton,
				$num++,
				$fila->nombres." ".$fila->primer_apellido." ".$fila->segundo_apellido,
				$fila->nombre_puesto,
				$fila->nombre_dependencia,
				$fila->nombre_subdependencia
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
	/*DAR DE BAJA AL USUARION ADMINDEJURBE*/
	function eliminarUsuario()
	{
		$id             		  = $this->input->post('id');			
		$id_usuario               = $this->session->userdata('id_usuario');
		$fechaActual              = getFechaHoraActual();
		$data = array(		                
		                'id_administrador'          => $id_usuario, 
		                'fecha_modificacion'        => $fechaActual,
		                'estado'                    => 'AN',
            		);
		
		$updateUsuario = $this->roles_model->eliminarUsuarioSistema($id,$data);	
		
		$resul           =  1;
		$mensaje         =  "OK";
		$resultado ='[{									
					"resultado":"'.$resul.'",
					"mensaje":"'.$mensaje.'"
					}]';
		echo $resultado;
	}

}
