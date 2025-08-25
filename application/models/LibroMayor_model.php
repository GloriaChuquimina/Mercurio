<?php
/*
*/

class LibroMayor_model extends CI_Model
{
    function __construct()
	{
		parent::__construct();	
		$this->db_mercurio = $this->load->database('db_mercurio', TRUE);		
	}
    function getLibroMayorBusqueda1($id_entidad,$id_cuenta)
	{
		$query = $this->db_mercurio->query("
                                          select pc.id
                                                ,pc.ruta 
                                                ,pc.codigo 
                                                ,pc.descripcion
                                                ,pc.nivel
                                                ,c.correlativo
                                                ,c.tipo_comprobante
                                                ,c.fecha_comprobante
                                                ,c.glosa_comprobante 
                                                ,dc.tipo_movimiento
                                                ,dc.fecha_registro
                                                ,dc.glosa_cuenta
                                                ,dc.importe_moneda_nacional
                                                ,dc.importe_moneda_extranjera
                                                ,e.nombre 
                                            from contabilidad.plancuentas pc
                                       left join contabilidad.detalle_comprobante dc on pc.id = dc.id_cuenta
                                 left outer join contabilidad.comprobante c on dc.id_comprobante = c.id
                                 left outer join administracion.entidad e  on c.id_entidad = e.id 
                                           where pc.estado in ('ACT') 
                                             and e.id=".$id_entidad."
                                             and pc.id=".$id_cuenta."
                                        group by pc.id
                                                 ,c.correlativo
                                                 ,c.tipo_comprobante
                                                 ,c.fecha_comprobante
                                                 ,c.glosa_comprobante
                                                 ,dc.tipo_movimiento
                                                 ,dc.fecha_registro
                                                 ,dc.glosa_cuenta
                                                 ,dc.importe_moneda_nacional
                                                 ,dc.importe_moneda_extranjera
                                                 ,e.nombre
                                        order by pc.id asc
		    							   " 
										  );
		return $query->result();
	}
    function getLibroMayorBusqueda2($id_entidad,$id_cuenta,$fecha_inicio,$fecha_fin)
	{
		$query = $this->db_mercurio->query("
                                          select pc.id
                                                ,pc.ruta 
                                                ,pc.codigo 
                                                ,pc.descripcion
                                                ,pc.nivel
                                                ,c.correlativo
                                                ,c.tipo_comprobante
                                                ,c.fecha_comprobante
                                                ,c.glosa_comprobante 
                                                ,dc.tipo_movimiento
                                                ,dc.fecha_registro
                                                ,dc.glosa_cuenta
                                                ,dc.importe_moneda_nacional
                                                ,dc.importe_moneda_extranjera
                                                ,e.nombre 
                                            from contabilidad.plancuentas pc
                                       left join contabilidad.detalle_comprobante dc on pc.id = dc.id_cuenta
                                 left outer join contabilidad.comprobante c on dc.id_comprobante = c.id
                                 left outer join administracion.entidad e  on c.id_entidad = e.id 
                                           where pc.estado in ('ACT') 
                                             and c.estado in ('ACT','HI') 
                                             and dc.estado in ('ACT','HI') 
                                             and e.id=".$id_entidad."
                                             and pc.id=".$id_cuenta."
                                             and c.fecha_comprobante between '".$fecha_inicio."' and '".$fecha_fin."'
                                        group by pc.id
                                                 ,c.correlativo
                                                 ,c.tipo_comprobante
                                                 ,c.fecha_comprobante
                                                 ,c.glosa_comprobante
                                                 ,dc.tipo_movimiento
                                                 ,dc.fecha_registro
                                                 ,dc.glosa_cuenta
                                                 ,dc.importe_moneda_nacional
                                                 ,dc.importe_moneda_extranjera
                                                 ,e.nombre
                                        order by pc.id asc
		    							   " 
										  );
		return $query->result();
	}
    

}