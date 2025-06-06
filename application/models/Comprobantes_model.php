<?php
/*
*/

class Comprobantes_model extends CI_Model
{
	
	function __construct()
	{
		parent::__construct();	
		$this->db_mercurio = $this->load->database('db_mercurio', TRUE);		
	}

	function getComprobanteAll()
	{
		$query = $this->db_mercurio->query("select *
											 from contabilidad.plancuentas
											where estado='AC'
										 order by nivel ASC,
  												  codigo ASC;
											" 
										  );
		return $query->result();
	}
	function getComprobanteByIdEntidad()
	{
		$query = $this->db_mercurio->query("select *
											 from contabilidad.plancuentas
											where estado='AC'
										 order by nivel ASC,
  												  codigo ASC;
											" 
										  );
		return $query->result();
	}
}