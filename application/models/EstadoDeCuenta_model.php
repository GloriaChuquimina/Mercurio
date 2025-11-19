<?php
/*
*/

class EstadoDeCuenta_model extends CI_Model
{
    function __construct()
	{
		parent::__construct();	
		$this->db_mercurio = $this->load->database('db_mercurio', TRUE);		
	}
    // function getEstadoDeCuenta($id_entidad,$fecha_inicio,$fecha_fin,$id_cuenta)
	// {
	// 	$query = $this->db_mercurio->query("
    //                                       select  			
	// 											 pa.codigo as codigo_aux
	// 											,pa.descripcion as descripcion_aux
	// 											,SUM(CASE WHEN dc.tipo_movimiento = 'DB' THEN dc.importe_moneda_nacional ELSE 0 END) AS debe
	// 											,SUM(CASE WHEN dc.tipo_movimiento = 'HB' THEN dc.importe_moneda_nacional ELSE 0 END) AS haber 
	// 											,CASE 
	// 												WHEN SUM(CASE WHEN dc.tipo_movimiento = 'DB' THEN dc.importe_moneda_nacional ELSE 0 END) > 
	// 													SUM(CASE WHEN dc.tipo_movimiento = 'HB' THEN dc.importe_moneda_nacional ELSE 0 END)
	// 												THEN 
	// 													SUM(CASE WHEN dc.tipo_movimiento = 'DB' THEN dc.importe_moneda_nacional ELSE 0 END) - 
	// 													SUM(CASE WHEN dc.tipo_movimiento = 'HB' THEN dc.importe_moneda_nacional ELSE 0 END)
	// 												ELSE 0 
	// 											END AS saldo_deudor			
	// 											,CASE 
	// 												WHEN SUM(CASE WHEN dc.tipo_movimiento = 'HB' THEN dc.importe_moneda_nacional ELSE 0 END) > 
	// 													SUM(CASE WHEN dc.tipo_movimiento = 'DB' THEN dc.importe_moneda_nacional ELSE 0 END)
	// 												THEN 
	// 													SUM(CASE WHEN dc.tipo_movimiento = 'HB' THEN dc.importe_moneda_nacional ELSE 0 END) - 
	// 													SUM(CASE WHEN dc.tipo_movimiento = 'DB' THEN dc.importe_moneda_nacional ELSE 0 END)
	// 												ELSE 0 
	// 											END AS saldo_acreedor
	// 									   from contabilidad.comprobante c 
	// 							left outer join contabilidad.detalle_comprobante dc on c.id =dc.id_comprobante and dc.id_cuenta_auxiliar is not null
	// 							left outer join administracion.entidad e on c.id_entidad =e.id
	// 							left outer join contabilidad.plancuentas pc on dc.id_cuenta =pc.id
	// 							left outer join contabilidad.plancuentas_auxiliares pa on dc.id_cuenta_auxiliar =pa.id
	// 									where e.id=".$id_entidad."
	// 										and c.estado in ('ACT')
	// 										and dc.estado in('ACT')
	// 										and pc.id =".$id_cuenta."
	// 										and c.fecha_comprobante between '".$fecha_inicio."' and '".$fecha_fin."'
	// 								group by pa.codigo,pa.descripcion	
	// 	    							");
	// 	return $query->result();
	// }
    function getEstadoDeCuenta($id_entidad,$fecha_inicio,$fecha_fin,$id_cuenta)
	{
		$query = $this->db_mercurio->query("
                                          select  			
												 COALESCE(pa.codigo, pc.codigo) AS codigo_aux
    											,COALESCE(pa.descripcion, pc.descripcion) AS descripcion_aux
												,SUM(CASE WHEN dc.tipo_movimiento = 'DB' THEN dc.importe_moneda_nacional ELSE 0 END) AS debe
												,SUM(CASE WHEN dc.tipo_movimiento = 'HB' THEN dc.importe_moneda_nacional ELSE 0 END) AS haber 
												,SUM(CASE WHEN dc.tipo_movimiento = 'DB' THEN dc.importe_moneda_extranjera ELSE 0 END) AS debe_USD
												,SUM(CASE WHEN dc.tipo_movimiento = 'HB' THEN dc.importe_moneda_extranjera ELSE 0 END) AS haber_USD
												,CASE 
													WHEN SUM(CASE WHEN dc.tipo_movimiento = 'DB' THEN dc.importe_moneda_nacional ELSE 0 END) > 
														SUM(CASE WHEN dc.tipo_movimiento = 'HB' THEN dc.importe_moneda_nacional ELSE 0 END)
													THEN 
														SUM(CASE WHEN dc.tipo_movimiento = 'DB' THEN dc.importe_moneda_nacional ELSE 0 END) - 
														SUM(CASE WHEN dc.tipo_movimiento = 'HB' THEN dc.importe_moneda_nacional ELSE 0 END)
													ELSE 0 
												END AS saldo_deudor			
												,CASE 
													WHEN SUM(CASE WHEN dc.tipo_movimiento = 'HB' THEN dc.importe_moneda_nacional ELSE 0 END) > 
														SUM(CASE WHEN dc.tipo_movimiento = 'DB' THEN dc.importe_moneda_nacional ELSE 0 END)
													THEN 
														SUM(CASE WHEN dc.tipo_movimiento = 'HB' THEN dc.importe_moneda_nacional ELSE 0 END) - 
														SUM(CASE WHEN dc.tipo_movimiento = 'DB' THEN dc.importe_moneda_nacional ELSE 0 END)
													ELSE 0 
												END AS saldo_acreedor
												,CASE 
													WHEN SUM(CASE WHEN dc.tipo_movimiento = 'DB' THEN dc.importe_moneda_extranjera ELSE 0 END) > 
														SUM(CASE WHEN dc.tipo_movimiento = 'HB' THEN dc.importe_moneda_extranjera ELSE 0 END)
													THEN 
														SUM(CASE WHEN dc.tipo_movimiento = 'DB' THEN dc.importe_moneda_extranjera ELSE 0 END) - 
														SUM(CASE WHEN dc.tipo_movimiento = 'HB' THEN dc.importe_moneda_extranjera ELSE 0 END)
													ELSE 0 
												END AS saldo_deudor_USD			
												,CASE 
													WHEN SUM(CASE WHEN dc.tipo_movimiento = 'HB' THEN dc.importe_moneda_extranjera ELSE 0 END) > 
														SUM(CASE WHEN dc.tipo_movimiento = 'DB' THEN dc.importe_moneda_extranjera ELSE 0 END)
													THEN 
														SUM(CASE WHEN dc.tipo_movimiento = 'HB' THEN dc.importe_moneda_extranjera ELSE 0 END) - 
														SUM(CASE WHEN dc.tipo_movimiento = 'DB' THEN dc.importe_moneda_extranjera ELSE 0 END)
													ELSE 0 
												END AS saldo_acreedor_USD
										   from contabilidad.comprobante c 
								left outer join contabilidad.detalle_comprobante dc on c.id =dc.id_comprobante --and dc.id_cuenta_auxiliar is not null
								left outer join administracion.entidad e on c.id_entidad =e.id
								left outer join contabilidad.plancuentas pc on dc.id_cuenta =pc.id
								left outer join contabilidad.plancuentas_auxiliares pa on dc.id_cuenta_auxiliar =pa.id
										  where e.id=".$id_entidad."
											and c.estado in ('ACT','HI') 
											and dc.estado in ('ACT','HI') 
											and pc.id =".$id_cuenta."
											and c.fecha_comprobante between '".$fecha_inicio."' and '".$fecha_fin."'
									GROUP BY 
												COALESCE(pa.codigo, pc.codigo),
												COALESCE(pa.descripcion, pc.descripcion);
		    							");
		return $query->result();
	}
}
