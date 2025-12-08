<?php
/*
*/

class Comunes_model extends CI_Model
{
	
	function __construct()
	{
		parent::__construct();	
		$this->db_mercurio = $this->load->database('db_mercurio', TRUE);
	}
    function getCatalogoDominio($concepto,$estado)
	{
		$concepto 	= "'".$concepto."'";
		$estado 	= "'".$estado."'";
		$query = $this->db_mercurio->query(" select d.valor1, d.id , 
											       d.valor2
											  from administracion.dominios d
											 where 1 = 1
											   and d.concepto = ".$concepto."
											   and d.estado   = ".$estado."
											 order by d.orden asc");
        return $query->result();  
	}
    function getGestion()
	{
		$query = $this->db_mercurio->query(" select distinct(g.gestion)
											   from configuraciones.gestion g
											  where 1 = 1
											    and g.estado IN ('ACT','HI')
										   order by g.gestion desc");
        return $query->result();  
	}
    function getGestionesEntidad()
	{
		$query = $this->db_mercurio->query(" select *
											   from configuraciones.gestion
											  where 1 = 1
											    and estado IN ('ACT','HI')
												and id_entidad =".$id_entidad."
										   order by gestion desc");
        return $query->result();  
	}
    function getVerificaGestion($id_entidad,$gestion)
	{
		$query = $this->db_mercurio->query(" select *
											   from configuraciones.gestion
											  where 1 = 1
											    and estado IN ('ACT','HI')
												and id_entidad =".$id_entidad."
												and gestion=".$gestion."
										   order by gestion desc");
        return $query->result();  
	}
    function addGestion($data)
	{
		$this->db_mercurio->insert('configuraciones.gestion',$data);
         return $this->db_mercurio->insert_id();
	}		
	function updateGestion($gestion,$id_entidad,$data)
	{
		$this->db_mercurio->where('gestion',$gestion);
		$this->db_mercurio->where('id_entidad',$id_entidad);	
		return $this->db_mercurio->update('configuraciones.gestion',$data);
	}
	function getFechaCierreGestion($gestion,$tipo_cierre)
	{
		$query = $this->db_mercurio->query(" select *
											   from contabilidad.cierres_contables
											  where 1 = 1
											    and gestion =".$gestion."
											    and tipo_cierre='".$tipo_cierre."'
											    and estado IN ('ACT')");
        return $query->result();  
	}

	
}