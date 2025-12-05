<?php
class Login extends CI_Controller
{
	function __construct(){
		parent::__construct();
			$this->load->helper('configuraciones_helper');
			$this->load->model('usuarios_model');
			$this->load->model('roles_model');
			$this->load->model('puestos_model');
    		//$this->load->library('My_PHPMailer');
    		$this->load->helper(array('form', 'url'));
			$this->load->library('form_validation');
	}

	function index($mensaje = "")
	{
		$dato['error'] =$mensaje;
		// $this->load->view("Login/logued",$dato);
		$this->load->view("Login/logued_new",$dato);
	}
	function loguedAnterior()
	{
		// echo ("Ingresa al LOGUED");
		$IDAPLICACION = $this->config->item('IDAPLICACION');
		$username = ($this->input->post('username'));
		$password =  md5(($this->input->post('pass')));
		$ip = $this->obtenerIp();
		// echo $username." - ".$password;
		// die();
		$login = $this->usuarios_model->loguear($username, $password);
		if($login)
		{
			$hoy = date('Y-m-d');
			if($login[0]->fecha_expiracion>=$hoy){
				if( $login[0]->estado == 'AC' )
				{
					$dataingreso = array (
							'id_usuario' => $login[0]->id,	
							'aplicacion' => $IDAPLICACION,
							'ip' => $ip
						);
					$ingresoUsers = $this->usuarios_model->guardarIngreso($dataingreso);
					$persona = datos_persona($login[0]->id_persona);
					$id_usuario = $login[0]->id;
					$id_persona = $login[0]->id_persona;
					$rolescero = $this->roles_model->obtener_roles_cero($id_usuario,$IDAPLICACION);
					$roles     = $this->roles_model->obtener_roles($id_usuario,$IDAPLICACION);

					// echo("<pre>");
					// print_r($roles);
					// echo("</pre>");
					// die();
					
					$lista_puestos = $this->puestos_model->listaPuestos($id_persona);
					if($lista_puestos)
					{

						if(count($lista_puestos)==1){
							$id_puesto_secundario = '';
							$puesto_secundario = '';
							$numero_item_secundario = '';
							$id_dependencia_secundario = '';
							$id_subdependencia_secundario = '';
							$nivel_dependencia_secundario = '';
						} else {
							if($lista_puestos[1]->nivel_dependencia==''){
								$id_puesto_secundario = $lista_puestos[1]->id_puesto;
								$puesto_secundario = $lista_puestos[1]->nombre_puesto;
								$numero_item_secundario = $lista_puestos[1]->numero_item;
								$id_dependencia_secundario = $lista_puestos[1]->id_dependencia;
								$id_subdependencia_secundario = $lista_puestos[1]->id_subdependencia;
								$nivel_dependencia_secundario = $lista_puestos[1]->nivel_dependencia;
							} else {
								if($lista_puestos[1]->depjerarquica_estado=='AC'){
									$id_puesto_secundario = $lista_puestos[1]->id_puesto;
									$puesto_secundario = $lista_puestos[1]->nombre_puesto;
									$numero_item_secundario = $lista_puestos[1]->numero_item;
									$id_dependencia_secundario = $lista_puestos[1]->id_dependencia;
									$id_subdependencia_secundario = $lista_puestos[1]->id_subdependencia;
									$nivel_dependencia_secundario = $lista_puestos[1]->nivel_dependencia;
								} else {
									$id_puesto_secundario = '';
									$puesto_secundario = '';
									$numero_item_secundario = '';
									$id_dependencia_secundario = '';
									$id_subdependencia_secundario = '';
									$nivel_dependencia_secundario = '';
								}
							}
						}
	
						$data = array(
							'is_logued_in'  => TRUE,
							'id_usuario' => $id_usuario,
							'id_funcionario' => $login[0]->id_persona,
							'fecha_expiracion' => $login[0]->fecha_expiracion,
							'rolescero' => $rolescero,
							'sede' => $lista_puestos[0]->sede_trabajo,
							'roles' => $roles,
							'gestion' => gestion_vigente(),
							'nombre_completo' => $persona[0]->nombres." ".$persona[0]->primer_apellido." ".$persona[0]->segundo_apellido,
							'id_puesto_principal' => $lista_puestos[0]->id_puesto,
							'puesto_principal' => $lista_puestos[0]->nombre_puesto,
							'numero_item_principal' => $lista_puestos[0]->numero_item,
							'id_dependencia_principal' => $lista_puestos[0]->id_dependencia,
							'id_subdependencia_principal' => $lista_puestos[0]->id_subdependencia,
							'nivel_dependencia_principal' => $lista_puestos[0]->nivel_dependencia,
							'tipo_puesto' => $lista_puestos[0]->tipo_puesto,
							
							'id_puesto_secundario' => $id_puesto_secundario,
							'puesto_secundario' => $puesto_secundario,
							'numero_item_secundario' => $numero_item_secundario,
							'id_dependencia_secundario' => $id_dependencia_secundario,
							'id_subdependencia_secundario' => $id_subdependencia_secundario,
							'nivel_dependencia_secundario' => $nivel_dependencia_secundario,
							'id_apliacion' => $IDAPLICACION
							
						);
						$this->session->set_userdata($data);
						redirect("inicio");
						
					}
					else
					{
						$this->index('El usuario no se encuentra habilitado contáctese con el administrador');
					}
				}
				else
				{
					//$mensaje ="El usuario no se encuentra habilitado contactece con el administrador";
					$this->index('El usuario no se encuentra habilitado contáctese con el administrador');
				}
			} else {
				$this->index('<center>LA CONTRASEÑA EXPIRÓ. <br>Actualice la contraseña con la opción: OLVIDÓ SU CONTRASEÑA<center>');
			}
		}
		else
		{
			$this->index('Credenciales inválidas. Intenta nuevamente.');
		}

	}

	function loguedNuevo()
	{
		$IDAPLICACION = $this->config->item('IDAPLICACION');
		$IDENTIDAD = 1;
		$username = $this->input->post('username');
		$password = $this->input->post('pass');
		$ip = $this->obtenerIp();
		//Verificar el usuario y capturar sus datos
		$usuario=$this->usuarios_model->buscarUsuarioPorUsername(trim($username));

		if($usuario){
			if($usuario[0]->nuevo_password=='SI'){
				//Verificamos usuario con bcrypt
				$verificar = password_verify($password,$usuario[0]->password);
				if($verificar) $login=$usuario;

			} else {
				//Verificamos usuario con md5
				$login = $this->usuarios_model->loguear($username,md5($password));
				if($login) $verificar=true;
				else $verificar=false;
			}

			if($verificar){
				$hoy = date('Y-m-d');
				if($login[0]->fecha_expiracion>=$hoy){
					if( $login[0]->estado == 'AC' )
					{
						$dataingreso = array (
								'id_usuario' => $login[0]->id,	
								'aplicacion' => $IDAPLICACION,
								'ip' => $ip
							);
						$ingresoUsers = $this->usuarios_model->guardarIngreso($dataingreso);
						$persona = datos_persona($login[0]->id_persona);
						$id_usuario = $login[0]->id;
						$id_persona = $login[0]->id_persona;
						$rolescero = $this->roles_model->obtener_roles_cero($id_usuario,$IDAPLICACION);
						$roles     = $this->roles_model->obtener_roles($id_usuario,$IDAPLICACION);
						
						$lista_puestos = $this->puestos_model->listaPuestos($id_persona);
						if($lista_puestos)
						{
							if(count($lista_puestos)==1){
								$id_puesto_secundario = '';
								$puesto_secundario = '';
								$numero_item_secundario = '';
								$id_dependencia_secundario = '';
								$id_subdependencia_secundario = '';
								$nivel_dependencia_secundario = '';
							} else {
								if($lista_puestos[1]->nivel_dependencia==''){
									$id_puesto_secundario = $lista_puestos[1]->id_puesto;
									$puesto_secundario = $lista_puestos[1]->nombre_puesto;
									$numero_item_secundario = $lista_puestos[1]->numero_item;
									$id_dependencia_secundario = $lista_puestos[1]->id_dependencia;
									$id_subdependencia_secundario = $lista_puestos[1]->id_subdependencia;
									$nivel_dependencia_secundario = $lista_puestos[1]->nivel_dependencia;
								} else {
									if($lista_puestos[1]->depjerarquica_estado=='AC'){
										$id_puesto_secundario = $lista_puestos[1]->id_puesto;
										$puesto_secundario = $lista_puestos[1]->nombre_puesto;
										$numero_item_secundario = $lista_puestos[1]->numero_item;
										$id_dependencia_secundario = $lista_puestos[1]->id_dependencia;
										$id_subdependencia_secundario = $lista_puestos[1]->id_subdependencia;
										$nivel_dependencia_secundario = $lista_puestos[1]->nivel_dependencia;
									} else {
										$id_puesto_secundario = '';
										$puesto_secundario = '';
										$numero_item_secundario = '';
										$id_dependencia_secundario = '';
										$id_subdependencia_secundario = '';
										$nivel_dependencia_secundario = '';
									}
								}
							}
							$data = array(
								'is_logued_in'  => TRUE,
								'id_usuario' => $id_usuario,
								'id_funcionario' => $login[0]->id_persona,
								'fecha_expiracion' => $login[0]->fecha_expiracion,
								'rolescero' => $rolescero,
								'sede' => $lista_puestos[0]->sede_trabajo,
								'roles' => $roles,
								'gestion' => gestion_vigente(),
								'nombre_completo' => $persona[0]->nombres." ".$persona[0]->primer_apellido." ".$persona[0]->segundo_apellido,
								'id_puesto_principal' => $lista_puestos[0]->id_puesto,
								'puesto_principal' => $lista_puestos[0]->nombre_puesto,
								'numero_item_principal' => $lista_puestos[0]->numero_item,
								'id_dependencia_principal' => $lista_puestos[0]->id_dependencia,
								'id_subdependencia_principal' => $lista_puestos[0]->id_subdependencia,
								'nivel_dependencia_principal' => $lista_puestos[0]->nivel_dependencia,
								'tipo_puesto' => $lista_puestos[0]->tipo_puesto,
								
								'id_puesto_secundario' => $id_puesto_secundario,
								'puesto_secundario' => $puesto_secundario,
								'numero_item_secundario' => $numero_item_secundario,
								'id_dependencia_secundario' => $id_dependencia_secundario,
								'id_subdependencia_secundario' => $id_subdependencia_secundario,
								'nivel_dependencia_secundario' => $nivel_dependencia_secundario,
								'id_apliacion' => $IDAPLICACION,
								'id_entidad' => $IDENTIDAD,
							);
							$this->session->set_userdata($data);
							redirect("inicio");
						}
						else
						{
							$this->index('El usuario no se encuentra habilitado, contáctese con el administrador');
						}
					}
					else
					{
						$this->index('El usuario no se encuentra habilitado, contáctese con el administrador');
					}
				} else {
					$this->index('<center>LA CONTRASEÑA EXPIRÓ. <br>Actualice la contraseña con la opción: OLVIDÓ SU CONTRASEÑA<center>');
				}
			} else {
				$this->index('CONTRASEÑA INCORRECTA, INTENTE NUEVAMENTE');
			}
		} else {
			$this->index('NO EXISTE EL USUARIO');
		}
	}
	
	function obtenerIp()
	{
		 $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if(getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED'))
           $ipaddress = getenv('HTTP_FORWARDED');
        else if(getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;

	}

	function salir()
	{
		$this->session->sess_destroy();
		redirect('Login');
	}
}

?>
