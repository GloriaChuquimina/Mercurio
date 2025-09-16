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
											 where estado in ('AC','HI')
										  order by nivel ASC,
  												  codigo ASC;
											" 
										  );
		return $query->result();
	}
	function getComprobanteByIdEntidad($id_entidad,$gestion)
	{
		$query = $this->db_mercurio->query("select *
											  from contabilidad.comprobante
											 where id_entidad = ".$id_entidad."
											   and gestion =".$gestion."
											-- and estado='ACT'
										  order by tipo_comprobante,correlativo desc
											  ;
											"
										  );
		return $query->result();
	}
	function getComprobanteByIdEntidadTipoComprobante($id_entidad,$tipo_comprobante,$gestion)
	{
		$query = $this->db_mercurio->query("select *
											  from contabilidad.comprobante
											 where id_entidad = ".$id_entidad."
											   and tipo_comprobante='".$tipo_comprobante."'
											   and gestion='".$gestion."'
											 -- and estado='ACT'
										   order by correlativo desc
											  ;
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
										  select  dc.*
										         ,p.codigo as codigo_auxiliar
          		 								 ,p.descripcion as descripcion_auxiliar
											from contabilidad.comprobante c 
								 left outer join contabilidad.detalle_comprobante dc on c.id =dc.id_comprobante 
								 left outer join contabilidad.plancuentas_auxiliares p on dc.id_cuenta_auxiliar =p.id and p.estado ='ACT'
										   where c.id=".$id_comprobante."
										     and dc.estado in ('ACT','HI')  
										order by c.fecha_registro asc;
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
	function updateComprobante($id_comprobante,$data)
	{
		$this->db_mercurio->where('id',$id_comprobante);
		return $this->db_mercurio->update('contabilidad.comprobante',$data);
	}
	function updateDetalleComprobante($id_registroCuenta,$data)
	{
		$this->db_mercurio->where('id',$id_registroCuenta);
		return $this->db_mercurio->update('contabilidad.detalle_comprobante',$data);
	}
	function updateRegistroDetalleComprobante($id_comprobante,$id_detalle_comprobante,$data)
	{
		$this->db_mercurio->where('id',$id_detalle_comprobante);
		$this->db_mercurio->where('id_comprobante',$id_comprobante);
		return $this->db_mercurio->update('contabilidad.detalle_comprobante',$data);
	}
	function updateRegistroCuentaComprobante($id_registrocuenta,$data)
	{
		$this->db_mercurio->where('id',$id_registrocuenta);
		return $this->db_mercurio->update('contabilidad.detalle_comprobante',$data);
	}
	function getDetalleComprobanteByIdDetalle($id_registro_cuenta)
	{
		$query = $this->db_mercurio->query("select *
											  from contabilidad.detalle_comprobante
											 where id= ".$id_registro_cuenta.";
											"
										  );
		return $query->result();
	}
	function getDetalleComprobanteByIdComprobante($id_comprobante)
	{
		$query = $this->db_mercurio->query("select *
											  from contabilidad.detalle_comprobante
											 where id_comprobante= ".$id_comprobante."
											   and estado in ('ACT','HI');
											"
										  );
		return $query->result();
	}

}