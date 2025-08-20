<?php
/*
*/

class Usuarios_model extends CI_Model
{
	
	function __construct()
	{
		parent::__construct();	
		$this->db_entorno = $this->load->database('db_entorno', TRUE);
		$this->db_rrhh = $this->load->database('db_recursos_humanos', TRUE);
	}

	function loguear($username, $password)
	{
		$query = $this->db_entorno->query("select *
									 from seguridad.usuarios 
									where login = '".$username."' 
									  and password = '".$password."'");	
        return $query->result();   
	}

	function guardarIngreso($data)
    {       
        $this->db_entorno->insert('accesos.control_access',$data); 
        return $this->db_entorno->insert_id();
    }

	function check_usuraio($id_persona, $username)
	{
		$query = $this->db_entorno->query("select *
									         from seguridad.usuarios 
									        where id_persona = ".$id_persona."
									          and login = '".$username."'");	
        return $query->result();   
	}
	function guardarUsuario($data)
    {       
        $this->db_entorno->insert('seguridad.usuarios',$data); 
        return $this->db_entorno->insert_id();
    }

    function updateUsuario($id_persona, $data)
    {       
        $this->db_entorno->where('id_persona',$id_persona);
        return $this->db_entorno->update('seguridad.usuarios',$data);
    }

    function getUsuario()
    {       
        $query = $this->db_entorno->query("select *
									         from seguridad.usuarios 
									        order by id asc");	
        return $query->result();
    }

	function getListarClavesInicial()
	{
		$query = $this->db_entorno->query("select *
									 from seguridad.usuarios 
									where estado not in ('AN','BA')");	
        return $query->result();   
	}
	function setImportarClavesEnHistorico($data)
    {       
        $this->db_entorno->insert('seguridad.claves_historico',$data); 
        return $this->db_entorno->insert_id();
    }
	
    function actualizarDatosUsuario($id, $data)
    {       
        $this->db_entorno->where('id',$id);
        return $this->db_entorno->update('seguridad.usuarios',$data);
    }

	function verificarClaveActual($id_usuario, $clave)
	{
		$query = $this->db_entorno->query("select *
									         from seguridad.usuarios 
									        where id = ".$id_usuario."
									          and password = '".$clave."'");	
        return $query->result();
	}

	function verificarClavesAntiguas($id_usuario, $clave)
	{
		$query = $this->db_entorno->query("select *
									         from seguridad.claves_historico 
									        where id_usuario = ".$id_usuario."
									          and password = '".$clave."'");	
        return $query->result();
	}

	function guardarNuevaClaveEnHistorico($data)
    {       
        $this->db_entorno->insert('seguridad.claves_historico',$data); 
        return $this->db_entorno->insert_id();
    }

	function verificarDatosRequeridos($ci, $fecha_nacimiento)
	{
		$query = $this->db_rrhh->query("select *
									         from personal.funcionario
									        where numero_documento = ".$ci."
									          and fecha_nacimiento = '".$fecha_nacimiento."'
									          and estado not in ('AN','BA')");	
		return $query->result();
	}

	function getUsuarioporIdFuncionario($id_persona,$condicion)
	{
		$query = $this->db_entorno->query("select *
											from seguridad.usuarios 
											where id_persona = ".$id_persona.$condicion);	
		return $query->result();
	}

	function getUsuarioporId($id_usuario,$condicion)
	{
		$query = $this->db_entorno->query("select *
											 from seguridad.usuarios 
											where id = ".$id_usuario.$condicion);
		return $query->result();
	}

	function buscarUsuarioPorCampoLogin($usuario)
	{
		$query = $this->db_entorno->query("select count(*) as cantidad
											 from seguridad.usuarios 
											where login ILIKE '".$usuario."'");
		return $query->result();
	}

	function listarUsuariosPorEstado($estado,$orden)
	{
		$query = $this->db_entorno->query("select * from(
													select DISTINCT ON(u.id_persona) id_persona, u.id, u.login, u.estado, f.*
														from seguridad.usuarios u JOIN seguridad.vista_funcionarios_sigap f ON(u.id_persona=f.id_funcionario_s)
														where u.estado = '".$estado."' AND f.estado_s = 'AC' AND f.tipo_puesto IN ('PLA','CON')
														order by u.id_persona) AS tabla".
											$orden);
        return $query->result();   
	}

	function listarUsuariosBaja($estado,$orden)
	{
		$query = $this->db_entorno->query("select * from(
													select u.id_persona, u.id, u.login, u.estado, f.*
														from seguridad.usuarios u JOIN seguridad.vista_funcionarios_sigap f ON(u.id_persona=f.id_funcionario_s)
														where u.estado = '".$estado."' AND f.tipo_puesto IN ('PLA','CON')
														order by u.id_persona) AS tabla".
											$orden);
        return $query->result();   
	}

	function listarUsuariosParaHabilitar()
	{
		$query = $this->db_entorno->query("select *
											from seguridad.vista_funcionarios_sigap vfs
											where tipo_puesto IN('PLA','CON','OTR') AND estado_s IN('AC','IN')
												AND (vfs.id_funcionario_s not in (select id_persona from seguridad.usuarios))
											order by id_funcionario_s desc
										");
        return $query->result();   
	}

	function getUsuariosInfo($id_persona)
	{
		$query = $this->db_entorno->query("select DISTINCT ON (u.id_persona) id_persona,u.login,u.password,u.fecha_expiracion,vfs.*
											FROM seguridad.usuarios u JOIN seguridad.vista_funcionarios_sigap vfs on (u.id_persona=vfs.id_funcionario_s)
											WHERE tipo_puesto IN ('PLA','CON') AND estado_s='AC' AND u.id=".$id_persona
										);
        return $query->result();
		  
	}
	/*STEPH */
	function getUsuarioIdPersona($id_funcionario)
	{
		$query = $this->db_entorno->query("select u.id
											 from seguridad.usuarios u
											where u.id_persona =".$id_funcionario);
		return $query->result();
	}
	function actualizarEstadoUsuario($id_persona, $data)
    {       
        $this->db_entorno->where('id_persona',$id_persona);
        return $this->db_entorno->update('seguridad.usuarios',$data);
    }
	/*STEPH */

	function buscarUsuarioPorUsername($username)
	{
		$query = $this->db_entorno->query("select *
											 from seguridad.usuarios 
											where login='".$username."'");
		return $query->result();
	}
}
?>