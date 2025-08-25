<?php
/*
*/

class CierresContables_model extends CI_Model
{
	
	function __construct()
	{
		parent::__construct();	
		$this->db_mercurio = $this->load->database('db_mercurio', TRUE);
	}
    function getCuentasConMovimientoByIdMayor($id_entidad,$fecha_inicio,$fecha_fin,$id_cuenta,$codigo_cuenta,$filtro)
	{
		
		$query = $this->db_mercurio->query(" 
                                              select	 
                                                     dc.id as id_detalle_cuenta
                                                    ,dc.id_cuenta
                                                    ,pc.codigo
                                                    ,pc.descripcion
                                                    ,dc.importe_moneda_nacional
                                                    ,dc.importe_moneda_extranjera
                                               from contabilidad.comprobante c 
                                    left outer join contabilidad.detalle_comprobante dc on c.id =dc.id_comprobante
                                    left outer join administracion.entidad e on c.id_entidad =e.id
                                    left outer join contabilidad.plancuentas pc on dc.id_cuenta =pc.id
                                    left outer join contabilidad.plancuentas_auxiliares pa on dc.id_cuenta_auxiliar =pa.id
                                              where e.id=".$id_entidad."
                                                and c.estado in ('ACT','HI') 
                                                ".$filtro."
                                                and dc.estado in ('ACT') 
                                                and ('".$id_cuenta."' = ANY (string_to_array(pc.ruta, '-')) or pc.codigo = '".$codigo_cuenta."') 
                                                and c.fecha_comprobante between '".$fecha_inicio."' AND '".$fecha_fin."'
                                             
                                             ");
        return $query->result();  
	}
    function getCuentasConMovimientoComprobanteByIdMayor($id_entidad,$fecha_inicio,$fecha_fin,$id_cuenta,$codigo_cuenta,$filtro)
	{
		
		$query = $this->db_mercurio->query(" 
                                             select	 
                                                    distinct c.id as id_comprobante
                                               from contabilidad.comprobante c 
                                    left outer join contabilidad.detalle_comprobante dc on c.id =dc.id_comprobante
                                    left outer join administracion.entidad e on c.id_entidad =e.id
                                    left outer join contabilidad.plancuentas pc on dc.id_cuenta =pc.id
                                    left outer join contabilidad.plancuentas_auxiliares pa on dc.id_cuenta_auxiliar =pa.id
                                              where e.id=".$id_entidad."
                                                and c.estado in ('ACT')
                                                --and c.tipo_cierre not in ('CIR','CIB')
                                                ".$filtro."
                                                and dc.estado in ('ACT')
                                                and ('".$id_cuenta."' = ANY (string_to_array(pc.ruta, '-')) or pc.codigo = '".$codigo_cuenta."') 
                                                and c.fecha_comprobante between '".$fecha_inicio."' AND '".$fecha_fin."'
                                             
                                             ");
        return $query->result();  
	}
    function guardarCierre($dataCierre)
	{
		$this->db_mercurio->insert('contabilidad.cierres_contables', $dataCierre);
		return $this->db_mercurio->insert_id();
	}
    function getCierres($tipo_cierre,$id_entidad)
	{
		$query = $this->db_mercurio->query("select *
											  from contabilidad.cierres_contables
											 where estado='ACT' 
                                               and tipo_cierre = '".$tipo_cierre."'
                                               and id_entidad = ".$id_entidad."
										  order by fecha_cierre ASC;
											");
		return $query->result();
	}
}