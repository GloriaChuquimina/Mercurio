<?php
/*
*/

class Roles_model extends CI_Model
{
	
	function __construct()
	{
		parent::__construct();	
		$this->db_entorno = $this->load->database('db_entorno', TRUE);		
		$this->db_rrhh = $this->load->database('db_recursos_humanos', TRUE);
	}

	function obtener_roles_cero($id,$aplicacion)
	{
		$query = $this->db->query(" select o.id, o.codigo_opciones,o.opcion,o.link,o.icono,o.nivel,o.orden
									  from seguridad.usuarios_opciones u, aplicaciones.opciones o
									 where u.id_opcion = o.id   	
									   and u.id_usuario = ".$id."
									   and o.id_modulo in (select id from aplicaciones.modulos where id_aplicacion = ".$aplicacion.")
									   and o.nivel = 0
									   and u.estado = 'AC'
									   and o.estado = 'AC'
									 order by o.orden asc" );	
        return $query->result();	
	}
	function obtener_roles($id,$aplicacion)
	{
		$query = $this->db->query(" select o.id, 
			                               o.codigo_opciones,
			                               o.opcion,
			                               o.link,
			                               o.icono,
			                               o.nivel,
			                               o.orden
									  from seguridad.usuarios_opciones u, 
									       aplicaciones.opciones o
									 where u.id_opcion = o.id
									   and u.id_usuario = ".$id."
									   and o.id_modulo in (select id from aplicaciones.modulos where id_aplicacion = ".$aplicacion." )
									   and o.nivel > 0
									   and u.estado = 'AC'
									   and o.estado = 'AC'
									 order by o.codigo_opciones,o.nivel,o.orden asc" );	
        return $query->result();	
	}

	function check_opciones($opcion,$usuario)
	{
		$query = $this->db_entorno->query("select  *
			                         	     from seguridad.usuarios_opciones
										    where id_usuario =". $usuario."
										      and id_opcion =".$opcion."
										      and estado = 'AC'");
        return $query->result();
	}
	
	function guardarOpcionesRol($data)
    {
        $this->db_entorno->insert('seguridad.usuarios_opciones',$data);
        return $this->db_entorno->insert_id();
    }

    function listaFuncionario()
    {
    	$query = $this->db_rrhh->query("select f.id, t.nivel_dependencia,t.numero_item
											  from personal.puesto_funcionario p,
											       personal.funcionario f,
											       entidad.puesto t
											where p.id_funcionario = f.id
											  and p.id_puesto = t.id");
        return $query->result();
    }   

	function usuarioIdPersona($id_funcionario)
	{
		$query = $this->db_entorno->query("select u.id
											  from seguridad.usuarios u
											where u.id_persona =".$id_funcionario);
		return $query->result();
	}

	function listaTipoFuncionario($tipo)
	{
		$query = $this->db_rrhh->query("select f.id as idf, *
										  from personal.funcionario f,
										       personal.incorporacion_planta i
										 where f.id = id_funcionario
										   and f.estado = 'AC'
										   and i.tipo_personal = '".$tipo."'");
		return $query->result();
	}

	function verificarRolParaMenu($id_puesto)
	{
		$query = $this->db_rrhh->query("select *
										  from personal.funcionario f,
										       personal.incorporacion_planta i
										 where f.id = id_funcionario
										   and f.estado = 'AC'
										   and i.tipo_personal = '".$tipo."'");
		return $query->result();
	}

	function obtenerRolporTipoPuesto($descripcion,$id_aplicacion)
	{
		$query = $this->db_entorno->query("select *
											  from aplicaciones.rol
											where estado='AC' AND rol = '".$descripcion."' AND id_aplicacion=".$id_aplicacion);
		return $query->result();
	}

	function obtenerRolporPuesto($id_puesto,$id_aplicacion)
	{
		$query = $this->db_entorno->query("select *
											  from aplicaciones.rol
											where estado='AC' AND puesto = '".$id_puesto."' AND id_aplicacion=".$id_aplicacion);
		return $query->result();
	}

	function obtenerRolporNivelDependencia($nivel_dependencia,$id_aplicacion)
	{
		$query = $this->db_entorno->query("select *
											  from aplicaciones.rol
											where estado='AC' AND puesto=0 AND nivel_dependencia = '".$nivel_dependencia."' AND id_aplicacion=".$id_aplicacion);
		return $query->result();
	}

	function obtenerRolOperador($id_aplicacion)
	{
		$query = $this->db_entorno->query("select *
											  from aplicaciones.rol
											where estado='AC' AND rol='OPERADOR' AND id_aplicacion=".$id_aplicacion);
		return $query->result();
	}

	function obtenerOpcionesPorRol($id_rol)
	{
		$query = $this->db_entorno->query("select ro.*
											from aplicaciones.rol r join aplicaciones.rol_opciones ro ON(r.id=ro.id_rol)
											where ro.estado='AC' AND r.id = ".$id_rol);
		return $query->result();
	}
	/* ABD septiembre 2025*/
	/*USUARIOS ADMINISTRADORES */
	function getusuariosSistema()
	{
		$query = $this->db_rrhh->query("select vdatos_fun.* 
										  from personal.vista_datos_puesto_cargo_funcionario vdatos_fun
										 where vdatos_fun.id_dependencia != 3
										   and vdatos_fun.estado = 'AC'
										   and vdatos_fun.estado_puesto='AC'
									  order by vdatos_fun.id_dependencia , vdatos_fun.nombres asc ");
        return $query->result();
	}

	/*LISTADO PARA DAR PERMISOS EN THOR ADMIN*/
	function getPermisosCero($aplicacion)
	{
		$query = $this->db_entorno->query(" select distinct o.id, 
														    o.codigo_opciones,
															o.opcion,o.link,
															o.icono,
															o.nivel,
															o.orden,
															o.id_aplicacion 
									          from seguridad.usuarios_opciones u, aplicaciones.opciones o
									         where 1 = 1
									           and u.id_opcion = o.codigo_opciones 	
									           and o.id_aplicacion = ".$aplicacion."
									           and o.nivel = 0
									           and u.estado = 'AC'
									           and o.estado = 'AC'
									      order by o.orden asc" );	
        return $query->result();	
	}
	function getPermisos($aplicacion)
	{
		



        $query = $this->db_entorno->query(" select o.id, 
			                               o.codigo_opciones,
			                               o.opcion,
			                               o.link,
			                               o.icono,
			                               o.nivel,
			                               o.orden,
			                               id_aplicacion
									  from aplicaciones.opciones o
									 where 1 = 1									   
									   and o.nivel > 0
									   and o.id_aplicacion = ".$aplicacion."									   
									   and o.estado = 'AC'									   
									 order by o.codigo_opciones,o.nivel,o.orden asc" ); 
        return $query->result();	
	}


	/*DAR DE BAJA A EL USUARIO*/
	function eliminarUsuarioSistema($id,$data)
	{
		$this->db_entorno->where('id',$id);        
		return $this->db_entorno->update('seguridad.usuarios_opciones',$data);  
	}

	function verificarRolUsuario($idUsuario,$idOpcion)
    {
        $query = $this->db_entorno->query(" select 1
                                      from seguridad.usuarios_opciones u
                                     where u.id_opcion = ".$idOpcion."
                                       and u.id_usuario = ".$idUsuario."        
                                       and u.estado = 'AC'" ); 
        return $query->result();
    }

	function updateRolesUsuario($id,$data)
    {       
        $this->db_entorno->where('id',$id);        
        return $this->db_entorno->update('seguridad.usuarios_opciones',$data);  
    }


}
?>