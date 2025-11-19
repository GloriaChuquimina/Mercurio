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
                                             and c.estado in ('ACT','HI')
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
                                             and c.estado in ('ACT','HI')
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
                                             and c.estado in ('ACT','HI')
                                             and c.fecha_comprobante between '".$fecha_inicio."' and '".$fecha_fin."'
                                             and c.correlativo between ".$numero_inicio." and ".$numero_fin."
                                        order by c.correlativo,c.tipo_comprobante asc
		    							                ");
		return $query->result();
	}
    
}
