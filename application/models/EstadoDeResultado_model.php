<?php
/*
*/

class EstadoDeResultado_model extends CI_Model
{
    function __construct()
	{
		parent::__construct();	
		$this->db_mercurio = $this->load->database('db_mercurio', TRUE);		
	}
    // function getEstadoDeResultadosIngreso($id_entidad,$fecha_inicio,$fecha_fin,$id_cuenta_mayor_ingreso,$cuenta_mayor_ingreso)
	// {
	// 	$query = $this->db_mercurio->query("
    //                                        SELECT * FROM (
    //                                                                  select	 pc.codigo
    //                                                                         ,pc.descripcion 
    //                                                                         ,CASE 
    //                                                                             WHEN SUM(CASE WHEN dc.tipo_movimiento = 'HB' THEN dc.importe_moneda_nacional ELSE 0 END) > 
    //                                                                                 SUM(CASE WHEN dc.tipo_movimiento = 'DB' THEN dc.importe_moneda_nacional ELSE 0 END)
    //                                                                             THEN 
    //                                                                                 SUM(CASE WHEN dc.tipo_movimiento = 'HB' THEN dc.importe_moneda_nacional ELSE 0 END) - 
    //                                                                                 SUM(CASE WHEN dc.tipo_movimiento = 'DB' THEN dc.importe_moneda_nacional ELSE 0 END)
    //                                                                             ELSE 0 
    //                                                                         END AS saldo_acreedor             
    //                                                                    from contabilidad.comprobante c 
    //                                                         left outer join contabilidad.detalle_comprobante dc on c.id =dc.id_comprobante
    //                                                         left outer join administracion.entidad e on c.id_entidad =e.id
    //                                                         left outer join contabilidad.plancuentas pc on dc.id_cuenta =pc.id
    //                                                         left outer join contabilidad.plancuentas_auxiliares pa on dc.id_cuenta_auxiliar =pa.id
    //                                                                 where e.id=".$id_entidad."
    //                                                                     and c.estado in ('ACT')
    //                                                                     and dc.estado in('ACT')
	// 																	and ('".$id_cuenta_mayor_ingreso."' = ANY (string_to_array(pc.ruta, '-')) or pc.codigo = '".$cuenta_mayor_ingreso."') 
    //                                                                     and c.fecha_comprobante between '".$fecha_inicio."' AND '".$fecha_fin."'
    //                                                             group by pc.codigo,pc.descripcion
    //                                                         ) z_q WHERE saldo_acreedor <> 0
	// 	    							");
	// 	return $query->result();
	// }
    function getEstadoDeResultadosIngreso($id_entidad,$fecha_inicio,$fecha_fin,$id_cuenta_mayor_ingreso,$cuenta_mayor_ingreso)
	{
		$query = $this->db_mercurio->query("
                                           SELECT * FROM (
                                                                     select	 pc.codigo
                                                                            ,pc.descripcion 
                                                                            ,CASE 
																				WHEN SUM(CASE WHEN dc.tipo_movimiento = 'DB' THEN dc.importe_moneda_nacional ELSE 0 END) > 
																					SUM(CASE WHEN dc.tipo_movimiento = 'HB' THEN dc.importe_moneda_nacional ELSE 0 END)
																				THEN 
																					SUM(CASE WHEN dc.tipo_movimiento = 'DB' THEN dc.importe_moneda_nacional ELSE 0 END) - 
																					SUM(CASE WHEN dc.tipo_movimiento = 'HB' THEN dc.importe_moneda_nacional ELSE 0 END)
																				WHEN SUM(CASE WHEN dc.tipo_movimiento = 'HB' THEN dc.importe_moneda_nacional ELSE 0 END) > 
																					SUM(CASE WHEN dc.tipo_movimiento = 'DB' THEN dc.importe_moneda_nacional ELSE 0 END)
																				THEN 
																					(SUM(CASE WHEN dc.tipo_movimiento = 'HB' THEN dc.importe_moneda_nacional ELSE 0 END) - 
																						SUM(CASE WHEN dc.tipo_movimiento = 'DB' THEN dc.importe_moneda_nacional ELSE 0 END)) * -1
																				ELSE 0 
																			END AS saldo_acreedor            
                                                                       from contabilidad.comprobante c 
                                                            left outer join contabilidad.detalle_comprobante dc on c.id =dc.id_comprobante
                                                            left outer join administracion.entidad e on c.id_entidad =e.id
                                                            left outer join contabilidad.plancuentas pc on dc.id_cuenta =pc.id
                                                            left outer join contabilidad.plancuentas_auxiliares pa on dc.id_cuenta_auxiliar =pa.id
                                                                    where e.id=".$id_entidad."
                                                                        and c.estado in ('ACT')
                                                                        and dc.estado in('ACT')
																		and ('".$id_cuenta_mayor_ingreso."' = ANY (string_to_array(pc.ruta, '-')) or pc.codigo = '".$cuenta_mayor_ingreso."') 
                                                                        and c.fecha_comprobante between '".$fecha_inicio."' AND '".$fecha_fin."'
                                                                group by pc.codigo,pc.descripcion
                                                            ) z_q WHERE saldo_acreedor <> 0
		    							");
		return $query->result();
	}
    // function getEstadoDeResultadosEgreso($id_entidad,$fecha_inicio,$fecha_fin,$id_cuenta_mayor_egreso,$cuenta_mayor_egreso)
	// {
	// 	$query = $this->db_mercurio->query("
    //                                          SELECT * FROM (       
    //                                                             select   pc.codigo
    //                                                                     ,pc.descripcion 
    //                                                                     ,CASE 
    //                                                                         WHEN SUM(CASE WHEN dc.tipo_movimiento = 'DB' THEN dc.importe_moneda_nacional ELSE 0 END) > 
    //                                                                             SUM(CASE WHEN dc.tipo_movimiento = 'HB' THEN dc.importe_moneda_nacional ELSE 0 END)
    //                                                                         THEN 
    //                                                                             SUM(CASE WHEN dc.tipo_movimiento = 'DB' THEN dc.importe_moneda_nacional ELSE 0 END) - 
    //                                                                             SUM(CASE WHEN dc.tipo_movimiento = 'HB' THEN dc.importe_moneda_nacional ELSE 0 END)
    //                                                                         ELSE 0 
    //                                                                     END AS saldo_deudor            
    //                                                                from contabilidad.comprobante c 
    //                                                     left outer join contabilidad.detalle_comprobante dc on c.id =dc.id_comprobante
    //                                                     left outer join administracion.entidad e on c.id_entidad =e.id
    //                                                     left outer join contabilidad.plancuentas pc on dc.id_cuenta =pc.id
    //                                                     left outer join contabilidad.plancuentas_auxiliares pa on dc.id_cuenta_auxiliar =pa.id
    //                                                             where e.id=".$id_entidad."
    //                                                                 and c.estado in ('ACT')
    //                                                                 and dc.estado in('ACT')
	// 																and ('".$id_cuenta_mayor_egreso."' = ANY (string_to_array(pc.ruta, '-')) or pc.codigo = '".$cuenta_mayor_egreso."') 
    //                                                                 and c.fecha_comprobante between '".$fecha_inicio."' AND '".$fecha_fin."'
    //                                                         group by pc.codigo,pc.descripcion
    //                                                             ) z_q WHERE saldo_deudor <> 0
	// 	    							");
	// 	return $query->result();
	// }
    function getEstadoDeResultadosEgreso($id_entidad,$fecha_inicio,$fecha_fin,$id_cuenta_mayor_egreso,$cuenta_mayor_egreso)
	{
		$query = $this->db_mercurio->query("
                                             SELECT * FROM (       
                                                                select   pc.codigo
                                                                        ,pc.descripcion 
                                                                        ,CASE 
																				WHEN SUM(CASE WHEN dc.tipo_movimiento = 'DB' THEN dc.importe_moneda_nacional ELSE 0 END) > 
																					SUM(CASE WHEN dc.tipo_movimiento = 'HB' THEN dc.importe_moneda_nacional ELSE 0 END)
																				THEN 
																					SUM(CASE WHEN dc.tipo_movimiento = 'DB' THEN dc.importe_moneda_nacional ELSE 0 END) - 
																					SUM(CASE WHEN dc.tipo_movimiento = 'HB' THEN dc.importe_moneda_nacional ELSE 0 END)
																				WHEN SUM(CASE WHEN dc.tipo_movimiento = 'HB' THEN dc.importe_moneda_nacional ELSE 0 END) > 
																					SUM(CASE WHEN dc.tipo_movimiento = 'DB' THEN dc.importe_moneda_nacional ELSE 0 END)
																				THEN 
																					(SUM(CASE WHEN dc.tipo_movimiento = 'HB' THEN dc.importe_moneda_nacional ELSE 0 END) - 
																						SUM(CASE WHEN dc.tipo_movimiento = 'DB' THEN dc.importe_moneda_nacional ELSE 0 END)) * -1
																				ELSE 0 
                                                                        END AS saldo_deudor            
                                                                   from contabilidad.comprobante c 
                                                        left outer join contabilidad.detalle_comprobante dc on c.id =dc.id_comprobante
                                                        left outer join administracion.entidad e on c.id_entidad =e.id
                                                        left outer join contabilidad.plancuentas pc on dc.id_cuenta =pc.id
                                                        left outer join contabilidad.plancuentas_auxiliares pa on dc.id_cuenta_auxiliar =pa.id
                                                                where e.id=".$id_entidad."
                                                                    and c.estado in ('ACT')
                                                                    and dc.estado in('ACT')
																	and ('".$id_cuenta_mayor_egreso."' = ANY (string_to_array(pc.ruta, '-')) or pc.codigo = '".$cuenta_mayor_egreso."') 
                                                                    and c.fecha_comprobante between '".$fecha_inicio."' AND '".$fecha_fin."'
                                                            group by pc.codigo,pc.descripcion
                                                                ) z_q WHERE saldo_deudor <> 0
		    							");
		return $query->result();
	}
    function getMontoResultado($id_entidad,$fecha_inicio,$fecha_fin)
    {
        $query = $this->db_mercurio->query("
                                             SELECT sum(resultado.saldo_acreedor)-sum(resultado.saldo_deudor) as total_estado_resultado 
                                               FROM (
                                                                 select  pc.codigo
                                                                        ,pc.descripcion 
                                                                        ,CASE 
                                                                            WHEN SUM(CASE WHEN dc.tipo_movimiento = 'HB' THEN dc.importe_moneda_nacional ELSE 0 END) > 
                                                                                 SUM(CASE WHEN dc.tipo_movimiento = 'DB' THEN dc.importe_moneda_nacional ELSE 0 END)
                                                                            THEN 
                                                                                SUM(CASE WHEN dc.tipo_movimiento = 'HB' THEN dc.importe_moneda_nacional ELSE 0 END) - 
                                                                                SUM(CASE WHEN dc.tipo_movimiento = 'DB' THEN dc.importe_moneda_nacional ELSE 0 END)
                                                                            ELSE 0 
                                                                        END AS saldo_acreedor 
                                                                        ,CASE 
                                                                            WHEN SUM(CASE WHEN dc.tipo_movimiento = 'DB' THEN dc.importe_moneda_nacional ELSE 0 END) > 
                                                                                 SUM(CASE WHEN dc.tipo_movimiento = 'HB' THEN dc.importe_moneda_nacional ELSE 0 END)
                                                                            THEN 
                                                                                SUM(CASE WHEN dc.tipo_movimiento = 'DB' THEN dc.importe_moneda_nacional ELSE 0 END) - 
                                                                                SUM(CASE WHEN dc.tipo_movimiento = 'HB' THEN dc.importe_moneda_nacional ELSE 0 END)
                                                                            ELSE 0 
                                                                        END AS saldo_deudor             
                                                                   from contabilidad.comprobante c 
                                                        left outer join contabilidad.detalle_comprobante dc on c.id =dc.id_comprobante
                                                        left outer join administracion.entidad e on c.id_entidad =e.id
                                                        left outer join contabilidad.plancuentas pc on dc.id_cuenta =pc.id
                                                        left outer join contabilidad.plancuentas_auxiliares pa on dc.id_cuenta_auxiliar =pa.id
                                                                  where e.id=".$id_entidad."
                                                                    and c.estado in ('ACT')
                                                                    and dc.estado in('ACT')
                                                                    and c.fecha_comprobante between '".$fecha_inicio."' AND '".$fecha_fin."'
                                                               group by pc.codigo,pc.descripcion
                                                        ) as resultado
		    							");
		return $query->result();
    }
}
