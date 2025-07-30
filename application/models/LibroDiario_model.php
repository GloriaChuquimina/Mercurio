<?php
/*
*/

class LibroDiario_model extends CI_Model
{
    function __construct()
	{
		parent::__construct();	
		$this->db_mercurio = $this->load->database('db_mercurio', TRUE);		
	}
  // function getLibroDiario($id_entidad,$id_cuenta,$fecha_inicio,$fecha_fin)
	// {
	// 	$query = $this->db_mercurio->query("
  //                                         select  c.id as id_comprobante
  //                                                ,c.fecha_comprobante 
  //                                                ,c.tipo_comprobante
  //                                                ,c.glosa_comprobante
  //                                                ,dc.id_cuenta
  //                                                ,dc.tipo_movimiento
  //                                                ,pc.codigo
  //                                                ,pc.descripcion
  //                                                ,dc.importe_moneda_nacional
  //                                             -- ,dc.importe_moneda_extranjera
  //                                           from contabilidad.comprobante c 
  //                                left outer join contabilidad.detalle_comprobante dc on c.id =dc.id_comprobante
  //                                left outer join administracion.entidad e on c.id_entidad =e.id
  //                                left outer join contabilidad.plancuentas pc on dc.id_cuenta =pc.id
  //                                          where e.id=3
  //                                            and c.estado in ('ACT')
  //                                            and dc.estado in('ACT')
  //                                            and c.fecha_comprobante between '01-01-2023' and '18-07-2025'
	// 	    							   " 
	// 									  );
	// 	return $query->result();
	// }
  function getLibroDiarioComprobantesPorRango($id_entidad,$fecha_inicio,$fecha_fin)
	{
		$query = $this->db_mercurio->query("
                                          select  c.id as id_comprobante
                                                 ,c.fecha_comprobante 
                                                 ,c.tipo_comprobante
                                                 ,c.glosa_comprobante
																								 ,c.correlativo
                                            from contabilidad.comprobante c 
                                 left outer join administracion.entidad e on c.id_entidad =e.id
                                           where e.id=".$id_entidad."
                                             and c.estado in ('ACT')
                                             and c.fecha_comprobante between '".$fecha_inicio."' and '".$fecha_fin."'
                                        order by c.correlativo,c.tipo_comprobante asc
		    							                ");
		return $query->result();
	}
  function getLibroDiarioComprobantesPorRangoByTipo($id_entidad,$fecha_inicio,$fecha_fin,$tipo_comprobante)
	{
		$query = $this->db_mercurio->query("
                                          select  c.id as id_comprobante
                                                 ,c.fecha_comprobante 
                                                 ,c.tipo_comprobante
                                                 ,c.glosa_comprobante
																								 ,c.correlativo
                                            from contabilidad.comprobante c 
                                 left outer join administracion.entidad e on c.id_entidad =e.id
                                           where e.id=".$id_entidad."
                                             and c.tipo_comprobante =('".$tipo_comprobante."')
                                             and c.estado in ('ACT')
                                             and c.fecha_comprobante between '".$fecha_inicio."' and '".$fecha_fin."'
                                        order by c.correlativo,c.tipo_comprobante asc
		    							                ");
		return $query->result();
	}
  function getLibroDiarioComprobantesPorRangoByTipoNumero($id_entidad,$fecha_inicio,$fecha_fin,$tipo_comprobante,$numero_inicio,$numero_fin)
	{
		$query = $this->db_mercurio->query("
                                          select  c.id as id_comprobante
                                                 ,c.fecha_comprobante 
                                                 ,c.tipo_comprobante
                                                 ,c.glosa_comprobante
																								 ,c.correlativo
                                            from contabilidad.comprobante c 
                                 left outer join administracion.entidad e on c.id_entidad =e.id
                                           where e.id=".$id_entidad."
                                             and c.tipo_comprobante =('".$tipo_comprobante."')
                                             and c.estado in ('ACT')
                                             and c.fecha_comprobante between '".$fecha_inicio."' and '".$fecha_fin."'
                                             and c.correlativo between ".$numero_inicio." and ".$numero_fin."
                                        order by c.correlativo,c.tipo_comprobante asc
		    							                ");
		return $query->result();
	}
    
}
