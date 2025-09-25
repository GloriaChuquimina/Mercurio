<?php
/*
*/

class Usuariosmercurio_model extends CI_Model
{
	
	function __construct()
	{
		parent::__construct();	
		$this->db = $this->load->database('db_mercurio', TRUE);			
	}
/*
	function loguear($username, $password)
	{
		$query = $this->db->query("select *
									 from sistema.usuarios 
									where usuario = '".$username."' 
									 and contrasenia = '".$password."'");	
        return $query->result();   
	}

	function loguearId($id, $password)
	{
		$query = $this->db->query("select *
									 from sistema.usuarios 
									where id = ".$id."
									 and contrasenia = '".$password."'");	
        return $query->result();   
	}

	function getUsuarioporId($id)
	{
		$query = $this->db->query("select *
										     from sistema.usuarios 
										    where id = ".$id);	
        return $query->result(); 
	}

	function getUsuarioAnteriorId($id)
	{
		$query = $this->db->query("select *
										     from sistema.usuarios_anterior 
										    where id_funcionario = ".$id);	
        return $query->result(); 
	}
*/


/*
	function getUsuarioAdministrador($idEntidad)
	{

		$query = $this->db->query("	select u.*, e.nombre
										 	from  sistema.usuarios u 
											join  entidad.entidades e ON e.id= u.id_entidad 
										Where u.id_entidad= ".$idEntidad."
										  and u.id_tipousuario='ADM'");	
        return $query->result(); 
	}


	function getDatosPersona($idFuncionario)
	{
		$query = $this->db->query("select *
									         from personal.funcionarios 
									        where id = ".$idFuncionario);	
        return $query->result();  
	}


	function listaUsuariosEntidad($idEntidad)
	{
		$query = $this->db->query("select u.id as id_usuario, f.*,u.*
											  from personal.funcionarios f,
											       sistema.usuarios u
											 where f.id = u.id_funcionario
											   and u.id_entidad = ".$idEntidad);	
        return $query->result(); 
	}

	function getUsuariosEntidadID($idUsuario)
	{
		$query = $this->db->query("select u.id as id_usuario, f.*,u.*
											  from personal.funcionarios f,
											       sistema.usuarios u
											 where f.id = u.id_funcionario
											   and u.id = ".$idUsuario);	
        return $query->result(); 
	}

	function listaUsuariosAnterioresEntidad($idEntidad)
	{
		$query = $this->db->query("select *
											 from personal.funcionarios f,
											      sistema.usuarios_anterior u
										    where f.id = u.id_funcionario
											  and u.id_entidad = ".$idEntidad."
											  and f.estado = 'ACT'
											  and not exists(select 1
													  from sistema.usuarios
												         where id_funcionario = f.id)");	
        return $query->result(); 
	}

	function listaPersonasNuevas($idEntidad)
	{
		$query = $this->db->query("select *
											 from personal.funcionarios f
											where f.id_entidad = ".$idEntidad."
											  and f.estado = 'ACT'
											  and not exists(select 1
													  from sistema.usuarios u
													 where u.id_funcionario = f.id)");	
        return $query->result(); 
	}
	

	function ckeckNombreUsuario($usuario)
	{
		$query = $this->db->query("select *
									 		 from sistema.usuarios 
											where usuario ilike '".$usuario."'");	
        return $query->result();  
	}


	function ckeckDatosUsuario($id_entidad,$id_funcionario)
	{
		$query = $this->db->query("select *
									 		 from sistema.usuarios 
											where id_entidad = ".$id_entidad."
											  and id_funcionario = ".$id_funcionario										  
											  );	
        return $query->result();  
	}

	function guardarUsuarioNuevo($data)
	{
		$this->db->insert('sistema.usuarios',$data);
		return $this->db->insert_id();  
	}
	function updateUsuarios($id,$data)
	{
		$this->db->where('id',$id);        
        return $this->db->update('sistema.usuarios',$data);  
	}

	function guardarclaves_historico($data)
	{
		$this->db->insert('sistema.claves_historico',$data);
		return $this->db->insert_id();  
	}
	function updateclaves_historico($id,$data)
	{
		$this->db->where('id_usuario',$id);
		$this->db->where('estado','ACT');        
        return $this->db->update('sistema.claves_historico',$data);  
	}

	
	
	function verificarRolUsuario($idUsuario,$idOpcion)
    {
        $query = $this->db->query(" select 1
                                      from sistema.usuarios_opciones u
                                     where u.id_opcion = ".$idOpcion."
                                       and u.id_usuario = ".$idUsuario."        
                                       and u.estado = 'ACT'" ); 
        return $query->result();
    }*/
/*

    function guardarOpcionesRol($data)
    {       
        $this->db->insert('sistema.usuarios_opciones',$data); 
        return $this->db->insert_id();
    }
    

    function updateRolesUsuario($id,$data)
    {       
        $this->db->where('id',$id);        
        return $this->db->update('sistema.usuarios_opciones',$data);  
    }

    function updateRolesUsuariosInactivos($idusuario,$estado,$data)
    {       
        $this->db->where('id_usuario',$idusuario);        
        $this->db->where('estado',$estado);
        return $this->db->update('sistema.usuarios_opciones',$data);  
    }
    function verificarNivelSuperio($idUsuario,$opcion)
    {
    	$query = $this->db->query("select *
											  from sistema.usuarios_opciones u
											 where u.id_usuario = ".$idUsuario."
											   and u.estado = 'ACT'
											   and u.id_opcion in (select id
																   from sistema.opciones o
																  where o.codigo_opciones = ".$opcion."
																    and o.estado = 'ACT')" ); 
        return $query->result();
    }

	function guardarIngreso($data)
    {       
        $this->db->insert('sistema.control_access',$data); 
        return $this->db->insert_id();
    }

	function check_usuraio($id_persona, $username)
	{
		$query = $this->db->query("select *
									         from sistema.usuarios 
									        where id_persona = ".$id_persona."
									          and login = '".$username."'");	
        return $query->result();   
	}
	function guardarUsuario($data)
    {       
        $this->db->insert('sistema.usuarios',$data); 
        return $this->db->insert_id();
    }


    

    function getUsuario()
    {       
        $query = $this->db->query("select *
									         from sistema.usuarios 
									        order by id asc");	
        return $query->result();
    }
/*
	/*USUARIOS ADMINISTRADORES DEJURBE*/
	function getUsuariosSistema()
    {       
        $query = $this->db->query("select *
								  from administracion.usuarios_sistema
								  where estado = 'ACT'
								  order by id asc");	
        return $query->result();
    }
	/*BUSCA USUARIOS ADMINISTRADORES */
	function getUsuariosSistemaId($id_usuario)
    {       
        $query = $this->db->query("select *
							    from administracion.usuarios_sistema
							    where estado = 'ACT'
								  and id_fun=".$id_usuario."
							 order by id asc");	
		return $query->result();
    }
	/*REGISTRO DE USUARIOS NUEVOS ADMINDEJURBE*/ 	
	function guardarUsuarioSistema($data)
	{
		$this->db->insert('administracion.usuarios_sistema',$data); 
        return $this->db->insert_id();
	}
	
}
?>