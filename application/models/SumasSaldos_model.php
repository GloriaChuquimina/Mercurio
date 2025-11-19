<?php
/*
*/

class SumasSaldos_model extends CI_Model
{
    function __construct()
	{
		parent::__construct();	
		$this->db_mercurio = $this->load->database('db_mercurio', TRUE);		
	}
    function getSumasSaldosCuentasConMovimiento($id_entidad,$fecha_inicio,$fecha_fin ,$whereFecha)
	{
        $query = $this->db_mercurio->query("
                                          SELECT  pc.id
                                                 ,pc.codigo
                                                 ,pc.descripcion
                                                 ,pc.nivel
                                                 ,e.nombre
                                                 ,SUM(CASE WHEN dc.tipo_movimiento = 'DB' THEN dc.importe_moneda_nacional ELSE 0 END) AS debe
                                                 ,SUM(CASE WHEN dc.tipo_movimiento = 'HB' THEN dc.importe_moneda_nacional ELSE 0 END) AS haber
                                                 ,SUM(CASE WHEN dc.tipo_movimiento = 'DB' THEN dc.importe_moneda_extranjera ELSE 0 END) AS debeUSD
                                                 ,SUM(CASE WHEN dc.tipo_movimiento = 'HB' THEN dc.importe_moneda_extranjera ELSE 0 END) AS haberUSD
                                            FROM contabilidad.plancuentas pc
                                       LEFT JOIN contabilidad.detalle_comprobante dc ON pc.id = dc.id_cuenta
                                       LEFT JOIN contabilidad.comprobante c ON dc.id_comprobante = c.id
                                       LEFT JOIN administracion.entidad e ON c.id_entidad = e.id 
                                           WHERE pc.estado IN ('ACT')
                                             AND c.estado in ('ACT','HI') 
                                             AND dc.estado in ('ACT','HI') 
                                             AND e.id = ".$id_entidad."
                                          -- AND c.fecha_comprobante BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."'
																					       ".$whereFecha."
                                        GROUP BY pc.id, pc.codigo, pc.descripcion, pc.nivel, e.nombre
                                        ORDER BY pc.codigo ,pc.nivel ASC;
		    							   " 
										  );
		return $query->result();
    }
    function getSumasSaldosCuentasConMovimientoByIds($id_entidad,$cuentas,$fecha_inicio,$fecha_fin,$whereFecha)
	{
        $query = $this->db_mercurio->query("
                                          SELECT  pc.id
                                                 ,pc.codigo
                                                 ,pc.descripcion
                                                 ,pc.nivel
                                                 ,e.nombre
                                                 ,SUM(CASE WHEN dc.tipo_movimiento = 'DB' THEN dc.importe_moneda_nacional ELSE 0 END) AS debe
                                                 ,SUM(CASE WHEN dc.tipo_movimiento = 'HB' THEN dc.importe_moneda_nacional ELSE 0 END) AS haber
                                                 ,SUM(CASE WHEN dc.tipo_movimiento = 'DB' THEN dc.importe_moneda_extranjera ELSE 0 END) AS debeUSD
                                                 ,SUM(CASE WHEN dc.tipo_movimiento = 'HB' THEN dc.importe_moneda_extranjera ELSE 0 END) AS haberUSD
                                            FROM contabilidad.plancuentas pc
                                       LEFT JOIN contabilidad.detalle_comprobante dc ON pc.id = dc.id_cuenta
                                       LEFT JOIN contabilidad.comprobante c ON dc.id_comprobante = c.id
                                       LEFT JOIN administracion.entidad e ON c.id_entidad = e.id 
                                           WHERE pc.estado IN ('ACT')
                                             AND c.estado in ('ACT','HI') 
                                             AND dc.estado in ('ACT','HI') 
                                             AND e.id = ".$id_entidad."
                                             AND pc.id in(".$cuentas.")                                          
                                         --  AND c.fecha_comprobante BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."'
																				  			".$whereFecha."
                                        GROUP BY pc.id, pc.codigo, pc.descripcion, pc.nivel, e.nombre
                                        ORDER BY pc.id ASC;
		    							   " 
										  );
		return $query->result();
    }
    function getGeneralSumasSaldosCuentasByIds($id_entidad,$cuentas,$fecha_inicio,$fecha_fin,$whereFecha)
	{
        $query = $this->db_mercurio->query("
                                          SELECT 
                                                     pc.id
                                                    ,pc.nivel
                                                    ,pc.codigo
                                                    ,pc.descripcion
                                                    ,pc.nivel
                                                    ,cuentas_con_movimiento.id_entidad
                                                    ,cuentas_con_movimiento.nombre
                                                    ,cuentas_con_movimiento.debe
                                                    ,cuentas_con_movimiento.haber
                                                    ,cuentas_con_movimiento.debeUSD
                                                    ,cuentas_con_movimiento.haberUSD
                                                FROM contabilidad.plancuentas pc
                                        LEFT JOIN (
                                                    SELECT     pc.id						     
                                                              ,pc.codigo
                                                              ,pc.descripcion
                                                              ,pc.nivel
                                                              ,e.nombre
                                                              ,SUM(CASE WHEN dc.tipo_movimiento = 'DB' THEN dc.importe_moneda_nacional ELSE 0 END) AS debe
                                                              ,SUM(CASE WHEN dc.tipo_movimiento = 'HB' THEN dc.importe_moneda_nacional ELSE 0 END) AS haber
                                                              ,SUM(CASE WHEN dc.tipo_movimiento = 'DB' THEN dc.importe_moneda_extranjera ELSE 0 END) AS debeUSD
                                                              ,SUM(CASE WHEN dc.tipo_movimiento = 'HB' THEN dc.importe_moneda_extranjera ELSE 0 END) AS haberUSD
                                                              ,e.id as id_entidad 
                                                         FROM contabilidad.plancuentas pc
                                                    LEFT JOIN contabilidad.detalle_comprobante dc ON pc.id = dc.id_cuenta
                                                    LEFT JOIN contabilidad.comprobante c ON dc.id_comprobante = c.id
                                                    LEFT JOIN administracion.entidad e ON c.id_entidad = e.id 
                                                        WHERE pc.estado IN ('ACT')
                                                          AND c.estado in ('ACT','HI') 
                                                          AND dc.estado in ('ACT','HI') 
                                                          AND e.id = ".$id_entidad."
                                                          AND pc.id in(".$cuentas.")    
                                                       -- AND c.fecha_comprobante BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."'
																											  			".$whereFecha."
                                                     GROUP BY pc.id, pc.codigo, pc.descripcion, pc.nivel, e.nombre,e.id
                                                        ) cuentas_con_movimiento on pc.id =cuentas_con_movimiento.id
                                              WHERE pc.id in(".$cuentas.")  
                                            ORDER BY pc.id ASC;
		    							   " 
										  );
		return $query->result();
    }
}
