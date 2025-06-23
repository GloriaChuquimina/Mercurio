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
											 from contabilidad.comprobante
											where estado='AC'
										 order by nivel ASC,
  												  codigo ASC;
											" 
										  );
		return $query->result();
	}
	function getComprobanteByIdEntidad($id_entidad)
	{
		$query = $this->db_mercurio->query("select *
											 from contabilidad.comprobante
											where estado='ACT'
											  and id_entidad = ".$id_entidad.";
											"
										  );
		return $query->result();
	}
	function getComprobanteById($id_comprobante)
	{
		$query = $this->db_mercurio->query("select *
											  from contabilidad.comprobante
											 where id= ".$id_comprobante.";
											"
										  );
		return $query->result();
	}
	function getDetalleComprobanteById($id_comprobante)
	{
		$query = $this->db_mercurio->query("
										  select dc.*
											from contabilidad.comprobante c 
								 left outer join contabilidad.detalle_comprobante dc on c.id =dc.id_comprobante and dc.estado ='ACT'
										   where c.id=".$id_comprobante.";
											"
										  );
		return $query->result();
	}
	function guardarComprobante($dataComprobante)
	{
		$this->db_mercurio->insert('contabilidad.comprobante', $dataComprobante);
		return $this->db_mercurio->insert_id();
	}
	function guardarDetalleComprobante($dataDetalleComprobante)
	{
		$this->db_mercurio->insert('contabilidad.detalle_comprobante', $dataDetalleComprobante);
		return $this->db_mercurio->insert_id();
	}

}