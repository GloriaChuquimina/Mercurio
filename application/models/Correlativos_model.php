<?php

class Correlativos_model extends CI_Model{

    function __construct()
	{
		parent::__construct();	
		$this->db_mercurio = $this->load->database('db_mercurio', TRUE);
	}
    function getTipoCorrelativo($abreviatura)
	{
		$query = $this->db_mercurio->query("select *
											  from correlativos.correlativos
											 where estado='ACT'
                                               and abreviatura='".$abreviatura."';
											");
		return $query->result();
	}
    function getCorrelativos()
	{
		$query = $this->db_mercurio->query("select *
											  from correlativos.correlativos
											 where estado='ACT';
											");
		return $query->result();
	}
    function getCorrelativoEntidadGestion($id_correlativo,$id_entidad,$id_dependencia,$gestion)
	{
		$query = $this->db_mercurio->query("select *
											  from correlativos.correlativos_entidad_gestion
											 where estado='ACT'
                                               and id_correlativo='".$id_correlativo."'
                                               and id_entidad='".$id_entidad."'
                                               and id_dependencia='".$id_dependencia."' 
                                               and gestion='".$gestion."';
											");
		return $query->result();
	}

    function getCorrelativoIndividualComprobantes($tipo_comprobante)
	{
		$query = $this->db_mercurio->query("select case 
                                                        when max(correlativo_individual) > 0 then max(correlativo_individual) 
                                                        else 0 
                                                   end as correlativo
										      from contabilidad.comprobante 
									         where 1 = 1										    
									           and tipo_comprobante = ".$tipo_comprobante."
                                               and estado = 'ACT'");
        return $query->result();
	}

    function updateCorrelativoEntidadGestion($id_correlativoentidadgestion,$data)
	{
		$this->db_mercurio->where('id',$id_correlativoentidadgestion);
		return $this->db_mercurio->update('correlativos.correlativos_entidad_gestion',$data);
	}
	function guardarCorrelativoEntidadGestion($data)
    {
        $this->db_mercurio->insert('correlativos.correlativos_entidad_gestion',$data);
        return $this->db_mercurio->insert_id();
    }
	 function anularCorrelativoEntidadGestion($id_correlativo,$id_entidad,$id_dependencia,$gestion,$data)
	{
		$this->db_mercurio->where('id_correlativo', $id_correlativo);
		$this->db_mercurio->where('id_entidad', $id_entidad);
		$this->db_mercurio->where('id_dependencia', $id_dependencia);
		$this->db_mercurio->where('gestion',$gestion);
		return $this->db_mercurio->update('correlativos.correlativos_entidad_gestion',$data);
	}



   


}