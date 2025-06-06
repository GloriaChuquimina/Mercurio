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
}