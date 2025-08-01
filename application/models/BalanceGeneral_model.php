<?php
/*
*/
class BalanceGeneral_model extends CI_Model
{
    function __construct()
	{
		parent::__construct();	
		$this->db_mercurio = $this->load->database('db_mercurio', TRUE);		
	}
  // function getGeneralBalanceGeneral($id_entidad,$fecha_inicio,$fecha_fin)
	// {
  //       $query = $this->db_mercurio->query("
  //                                          SELECT 
  //                                                  pc.id
  //                                                 ,pc.nivel
  //                                                 ,pc.codigo
  //                                                 ,pc.padre
  //                                                 ,pc.ruta
  //                                                 ,pc.descripcion
  //                                                 ,pc.nivel
  //                                                 ,cuentas_con_movimiento.id_entidad
  //                                                 ,cuentas_con_movimiento.nombre
  //                                                   --,cuentas_con_movimiento.importe_moneda_nacional   
  //                                                 ,case 
  //                                                       when cuentas_con_movimiento.importe_moneda_nacional  is null then 0.00
  //                                                       else cuentas_con_movimiento.importe_moneda_nacional 
  //                                                 end  AS importe_moneda_nacional   
  //                                            FROM contabilidad.plancuentas pc
  //                                       LEFT JOIN (
  //                                                   SELECT     pc.id						     
  //                                                             ,pc.codigo
  //                                                             ,pc.descripcion
  //                                                             ,pc.nivel
  //                                                             ,pc.padre
  //                                                             ,pc.ruta
  //                                                             ,e.nombre
  //                                                             ,SUM(dc.importe_moneda_nacional) AS importe_moneda_nacional                      
  //                                                             ,e.id as id_entidad
  //                                                        FROM contabilidad.plancuentas pc
  //                                                   LEFT JOIN contabilidad.detalle_comprobante dc ON pc.id = dc.id_cuenta
  //                                                   LEFT JOIN contabilidad.comprobante c ON dc.id_comprobante = c.id
  //                                                   LEFT JOIN administracion.entidad e ON c.id_entidad = e.id 
  //                                                       WHERE pc.estado IN ('ACT')
  //                                                         AND c.estado IN ('ACT')
  //                                                         AND dc.estado IN ('ACT')
  //                                                         AND e.id = ".$id_entidad."
  //                                                         AND c.fecha_comprobante between '".$fecha_inicio."' AND '".$fecha_fin."'
  //                                                    GROUP BY pc.id, pc.codigo, pc.descripcion, pc.nivel, e.nombre,e.id
  //                                                   ) cuentas_con_movimiento on pc.id =cuentas_con_movimiento.id

  //                                           order by nivel ASC,
  //                                           codigo ASC;
	// 	    							   " 
	// 									  );
  //   return $query->result();
  // }
  function getGeneralBalanceGeneral($id_entidad,$fecha_inicio,$fecha_fin)
	{
        $query = $this->db_mercurio->query("
                                           SELECT 
                                                   pc.id
                                                  ,pc.nivel
                                                  ,pc.codigo
                                                  ,pc.padre
                                                  ,pc.ruta
                                                  ,pc.descripcion
                                                  ,pc.nivel
                                                  ,cuentas_con_movimiento.id_entidad
                                                  ,cuentas_con_movimiento.nombre 
                                                  ,case 
                                                        when cuentas_con_movimiento.saldo_cuenta  is null then 0.00
                                                        else cuentas_con_movimiento.saldo_cuenta 
                                                  end  AS saldo_cuenta   
                                            FROM contabilidad.plancuentas pc
                                       LEFT JOIN (
                                                       SELECT  pc.id						     
                                                              ,pc.codigo
                                                              ,pc.descripcion
                                                              ,pc.nivel
                                                              ,pc.padre
                                                              ,pc.ruta
                                                              ,e.nombre
                                                              ,CASE 
                                                                    WHEN SUM(CASE WHEN dc.tipo_movimiento = 'DB' THEN dc.importe_moneda_nacional ELSE 0 END) > 
                                                                        SUM(CASE WHEN dc.tipo_movimiento = 'HB' THEN dc.importe_moneda_nacional ELSE 0 END)
                                                                    THEN 
                                                                        SUM(CASE WHEN dc.tipo_movimiento = 'DB' THEN dc.importe_moneda_nacional ELSE 0 END) - 
                                                                        SUM(CASE WHEN dc.tipo_movimiento = 'HB' THEN dc.importe_moneda_nacional ELSE 0 END)
                                                                        
                                                                    WHEN SUM(CASE WHEN dc.tipo_movimiento = 'HB' THEN dc.importe_moneda_nacional ELSE 0 END) > 
                                                                        SUM(CASE WHEN dc.tipo_movimiento = 'DB' THEN dc.importe_moneda_nacional ELSE 0 END)
                                                                    THEN 
                                                                      ( SUM(CASE WHEN dc.tipo_movimiento = 'HB' THEN dc.importe_moneda_nacional ELSE 0 END) - 
                                                                        SUM(CASE WHEN dc.tipo_movimiento = 'DB' THEN dc.importe_moneda_nacional ELSE 0 END)) * -1
                                                               ELSE 0 
                                                               END AS saldo_cuenta      
                                                              ,e.id as id_entidad
                                                         FROM contabilidad.plancuentas pc
                                                    LEFT JOIN contabilidad.detalle_comprobante dc ON pc.id = dc.id_cuenta
                                                    LEFT JOIN contabilidad.comprobante c ON dc.id_comprobante = c.id
                                                    LEFT JOIN administracion.entidad e ON c.id_entidad = e.id 
                                                        WHERE pc.estado IN ('ACT')
                                                          AND c.estado IN ('ACT')
                                                          AND dc.estado IN ('ACT')
                                                          AND e.id = ".$id_entidad."
                                                          AND c.fecha_comprobante between '".$fecha_inicio."' AND '".$fecha_fin."'
                                                    GROUP BY pc.id, pc.codigo, pc.descripcion, pc.nivel, e.nombre,e.id
                                                    ) cuentas_con_movimiento on pc.id =cuentas_con_movimiento.id

                                            order by nivel ASC,
                                            codigo ASC;
		    							   " 
										  );
		return $query->result();
    }
}