<?php

class Entidades_model extends CI_Model{

    function __construct()
	{
		parent::__construct();	
		$this->db_mercurio = $this->load->database('db_mercurio', TRUE);
	}
    function getEntidades()
	{
		$query = $this->db_mercurio->query("select *
											  from administracion.entidad
											 where estado='AC'
										  order by nombre ASC;
											");
		return $query->result();
	}
    function getEntidadesById($id_entidad)
	{
		$query = $this->db_mercurio->query("select *
											  from administracion.entidad
											 where estado='AC'
                                               and id=".$id_entidad."
										  order by nombre ASC;
											");
		return $query->result();
	}
    function guardarEntidad($data)
    {
        $this->db_mercurio->insert('administracion.entidad',$data);
        return $this->db_mercurio->insert_id();
    }

	function updateEntidad($id_entidad,$data)
	{
		$this->db_mercurio->where('id',$id_entidad);
		return $this->db_mercurio->update('administracion.entidad',$data);
	}
    /*ENTIDAD-DEPENDENCIA */
    function guardarEntidadDependencia($data)
    {
        $this->db_mercurio->insert('administracion.entidad_dependencia',$data);
        return $this->db_mercurio->insert_id();
    }


}