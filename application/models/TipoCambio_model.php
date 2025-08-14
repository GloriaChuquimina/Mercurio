<?php
/*
*/

class TipoCambio_model extends CI_Model
{
    function __construct()
	{
		parent::__construct();	
		$this->db_mercurio = $this->load->database('db_mercurio', TRUE);
	}
    function getTipoCambio()
	{
		$query = $this->db_mercurio->query("select *
											  from contabilidad.tipo_cambio
											 where estado='ACT'
										  order by fecha desc;
											");
		return $query->result();
	}
    function getGestionTipoCambio()
	{
		$query = $this->db_mercurio->query("  select DISTINCT CAST(EXTRACT(YEAR FROM fecha) AS INT) AS gestion
												from contabilidad.tipo_cambio
											   where estado = 'ACT'
											order by gestion DESC;
											");
		return $query->result();
	}
    
	function getTipoCambioFecha($fecha)
	{
		$query = $this->db_mercurio->query("select *
											  from contabilidad.tipo_cambio
											 where estado='ACT'
											   and fecha ='".$fecha."';
											");
		return $query->result();
	}
}