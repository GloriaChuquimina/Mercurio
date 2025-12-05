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
//   function getGeneralBalanceGeneral($id_entidad,$fecha_inicio,$fecha_fin)
//   {
//         $query = $this->db_mercurio->query("
//                                            SELECT 
//                                                    pc.id
//                                                   ,pc.nivel
//                                                   ,pc.codigo
//                                                   ,pc.padre
//                                                   ,pc.ruta
//                                                   ,pc.descripcion
//                                                   ,pc.nivel
//                                                   ,cuentas_con_movimiento.id_entidad
//                                                   ,cuentas_con_movimiento.nombre
//                                                     --,cuentas_con_movimiento.importe_moneda_nacional   
//                                                   ,case 
//                                                         when cuentas_con_movimiento.importe_moneda_nacional  is null then 0.00
//                                                         else cuentas_con_movimiento.importe_moneda_nacional 
//                                                   end  AS importe_moneda_nacional   
//                                              FROM contabilidad.plancuentas pc
//                                         LEFT JOIN (
//                                                     SELECT     pc.id						     
//                                                               ,pc.codigo
//                                                               ,pc.descripcion
//                                                               ,pc.nivel
//                                                               ,pc.padre
//                                                               ,pc.ruta
//                                                               ,e.nombre
//                                                               ,SUM(dc.importe_moneda_nacional) AS importe_moneda_nacional                      
//                                                               ,e.id as id_entidad
//                                                          FROM contabilidad.plancuentas pc
//                                                     LEFT JOIN contabilidad.detalle_comprobante dc ON pc.id = dc.id_cuenta
//                                                     LEFT JOIN contabilidad.comprobante c ON dc.id_comprobante = c.id
//                                                     LEFT JOIN administracion.entidad e ON c.id_entidad = e.id 
//                                                         WHERE pc.estado IN ('ACT')
//                                                           AND c.estado IN ('ACT')
//                                                           AND dc.estado IN ('ACT')
//                                                           AND e.id = ".$id_entidad."
//                                                           AND c.fecha_comprobante between '".$fecha_inicio."' AND '".$fecha_fin."'
//                                                      GROUP BY pc.id, pc.codigo, pc.descripcion, pc.nivel, e.nombre,e.id
//                                                     ) cuentas_con_movimiento on pc.id =cuentas_con_movimiento.id

//                                             order by nivel ASC,
//                                             codigo ASC;
// 		    							   " 
// 										  );
//     return $query->result();
//   }
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
                                                                    WHEN ('1' = ANY (string_to_array(pc.ruta, '-')) or pc.codigo = '1')
                                                                    THEN 
                                                                        SUM(CASE WHEN dc.tipo_movimiento = 'DB' THEN dc.importe_moneda_nacional ELSE 0 END) - 
                                                                        SUM(CASE WHEN dc.tipo_movimiento = 'HB' THEN dc.importe_moneda_nacional ELSE 0 END)
                                                                        
                                                                    WHEN ('2' = ANY (string_to_array(pc.ruta, '-')) or pc.codigo = '2')or ('3' = ANY (string_to_array(pc.ruta, '-')) or pc.codigo = '3')
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
                                                        WHERE pc.estado in ('ACT')
                                                          AND c.estado in ('ACT','HI')
                                                          AND dc.estado in ('ACT','HI')
                                                          AND e.id = ".$id_entidad."
                                                          AND c.fecha_comprobante between '".$fecha_inicio."' AND '".$fecha_fin."'
                                                    GROUP BY pc.id, pc.codigo, pc.descripcion, pc.nivel, e.nombre,e.id
                                                    ) cuentas_con_movimiento on pc.id =cuentas_con_movimiento.id
<<<<<<< HEAD
                                        where pc.id_entidad = ".$id_entidad."
=======

>>>>>>> 86e86a998fd13133562672039854ed148e2211bf
                                            order by nivel ASC,
                                            codigo ASC;
		    							   " 
										  );
		      return $query->result();
    }
    // FUNCIONA
    // function getGeneralBalanceGeneralPorMayor($id_entidad,$fecha_inicio,$fecha_fin,$cuenta_mayor,$id_cuenta_mayor)
	//   {
    //     $query = $this->db_mercurio->query("
    //                               SELECT 
    //                                        pc.id
    //                                       ,pc.nivel
    //                                       ,pc.codigo
    //                                       ,pc.padre
    //                                       ,pc.ruta
    //                                       ,pc.descripcion
    //                                       ,pc.nivel
    //                                       ,cuentas_con_movimiento.id_entidad
    //                                       ,cuentas_con_movimiento.nombre 
    //                                       ,case 
    //                                             when cuentas_con_movimiento.saldo_cuenta  is null then 0.00
    //                                             else cuentas_con_movimiento.saldo_cuenta 
    //                                       end  AS saldo_cuenta   
    //                                 FROM  contabilidad.plancuentas pc
    //                            LEFT JOIN (
    //                                           SELECT 
    //                                                   pc.id,
    //                                                   pc.codigo,
    //                                                   pc.descripcion,
    //                                                   pc.nivel,
    //                                                   pc.padre,
    //                                                   pc.ruta,
    //                                                   e.nombre,
    //                                                   CASE 
    //                                                       WHEN SUM(CASE WHEN dc.tipo_movimiento = 'DB' THEN dc.importe_moneda_nacional ELSE 0 END) > 
    //                                                           SUM(CASE WHEN dc.tipo_movimiento = 'HB' THEN dc.importe_moneda_nacional ELSE 0 END)
    //                                                       THEN 
    //                                                           SUM(CASE WHEN dc.tipo_movimiento = 'DB' THEN dc.importe_moneda_nacional ELSE 0 END) - 
    //                                                           SUM(CASE WHEN dc.tipo_movimiento = 'HB' THEN dc.importe_moneda_nacional ELSE 0 END)
    //                                                       WHEN SUM(CASE WHEN dc.tipo_movimiento = 'HB' THEN dc.importe_moneda_nacional ELSE 0 END) > 
    //                                                           SUM(CASE WHEN dc.tipo_movimiento = 'DB' THEN dc.importe_moneda_nacional ELSE 0 END)
    //                                                       THEN 
    //                                                           (SUM(CASE WHEN dc.tipo_movimiento = 'HB' THEN dc.importe_moneda_nacional ELSE 0 END) - 
    //                                                             SUM(CASE WHEN dc.tipo_movimiento = 'DB' THEN dc.importe_moneda_nacional ELSE 0 END)) * -1
    //                                                       ELSE 0 
    //                                                   END AS saldo_cuenta,
    //                                                   e.id as id_entidad
    //                                             FROM contabilidad.plancuentas pc
    //                                        LEFT JOIN contabilidad.detalle_comprobante dc ON pc.id = dc.id_cuenta
    //                                        LEFT JOIN contabilidad.comprobante c ON dc.id_comprobante = c.id
    //                                        LEFT JOIN administracion.entidad e ON c.id_entidad = e.id 
    //                                            WHERE pc.estado = 'ACT'
    //                                              AND c.estado = 'ACT'
    //                                              AND dc.estado = 'ACT'
    //                                              AND e.id = ".$id_entidad."
    //                                              AND ('".$id_cuenta_mayor."' = ANY (string_to_array(pc.ruta, '-')) or pc.codigo = '".$cuenta_mayor."')
    //                                              AND c.fecha_comprobante BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."'
    //                                         GROUP BY 
    //                                                  pc.id, pc.codigo, pc.descripcion, pc.nivel, pc.padre, pc.ruta, e.nombre, e.id
    //                                         ORDER BY nivel ASC,
    //                                                  codigo ASC
    //                                       ) cuentas_con_movimiento on pc.id =cuentas_con_movimiento.id
    //                                WHERE ('".$id_cuenta_mayor."' = ANY (string_to_array(pc.ruta, '-')) or pc.codigo = '".$cuenta_mayor."')
    //                             ORDER BY nivel ASC,codigo ASC;
	// 	    							                    ");
	// 	    return $query->result();
    // }
    function getGeneralBalanceGeneralPorMayorBoliviano($id_entidad,$fecha_inicio,$fecha_fin,$cuenta_mayor,$id_cuenta_mayor,$whereFecha,$tipo_cuenta,$whereCierre)
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
                                    FROM  contabilidad.plancuentas pc
                               LEFT JOIN (
                                              SELECT 
                                                      pc.id,
                                                      pc.codigo,
                                                      pc.descripcion,
                                                      pc.nivel,
                                                      pc.padre,
                                                      pc.ruta,
                                                      e.nombre,
                                                      CASE 
                                                          WHEN ('".$tipo_cuenta."' = 'deudor')
                                                          THEN 
                                                              SUM(CASE WHEN dc.tipo_movimiento = 'DB' THEN dc.importe_moneda_nacional ELSE 0 END) - 
                                                              SUM(CASE WHEN dc.tipo_movimiento = 'HB' THEN dc.importe_moneda_nacional ELSE 0 END)
                                                          WHEN ('".$tipo_cuenta."' = 'acreedor')
                                                          THEN 
                                                              (SUM(CASE WHEN dc.tipo_movimiento = 'HB' THEN dc.importe_moneda_nacional ELSE 0 END) - 
                                                                SUM(CASE WHEN dc.tipo_movimiento = 'DB' THEN dc.importe_moneda_nacional ELSE 0 END)) 
                                                          ELSE 0 
                                                      END AS saldo_cuenta,
                                                      e.id as id_entidad
                                                FROM contabilidad.plancuentas pc
                                           LEFT JOIN contabilidad.detalle_comprobante dc ON pc.id = dc.id_cuenta
                                           LEFT JOIN contabilidad.comprobante c ON dc.id_comprobante = c.id
                                           LEFT JOIN administracion.entidad e ON c.id_entidad = e.id 
                                               WHERE pc.estado = 'ACT'
                                                 AND c.estado in ('ACT','HI')
                                                 AND dc.estado in ('ACT','HI')
                                                 AND e.id = ".$id_entidad."
                                                 AND ('".$id_cuenta_mayor."' = ANY (string_to_array(pc.ruta, '-')) or pc.codigo = '".$cuenta_mayor."')
                                                 ".$whereFecha."
                                                 ".$whereCierre."
                                            GROUP BY 
                                                     pc.id, pc.codigo, pc.descripcion, pc.nivel, pc.padre, pc.ruta, e.nombre, e.id
                                            ORDER BY nivel ASC,
                                                     codigo ASC
                                          ) cuentas_con_movimiento on pc.id =cuentas_con_movimiento.id
                                   WHERE ('".$id_cuenta_mayor."' = ANY (string_to_array(pc.ruta, '-')) or pc.codigo = '".$cuenta_mayor."')
<<<<<<< HEAD
                                     AND pc.id_entidad = ".$id_entidad."
=======
>>>>>>> 86e86a998fd13133562672039854ed148e2211bf
                                ORDER BY nivel ASC,codigo ASC;
		    							                    ");
		    return $query->result();
    }
    function getGeneralBalanceGeneralPorMayorUSD($id_entidad,$fecha_inicio,$fecha_fin,$cuenta_mayor,$id_cuenta_mayor,$whereFecha,$tipo_cuenta,$whereCierre)
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
                                    FROM  contabilidad.plancuentas pc
                               LEFT JOIN (
                                              SELECT 
                                                      pc.id,
                                                      pc.codigo,
                                                      pc.descripcion,
                                                      pc.nivel,
                                                      pc.padre,
                                                      pc.ruta,
                                                      e.nombre,
                                                      CASE 
                                                          WHEN ('".$tipo_cuenta."' = 'deudor')
                                                          THEN 
                                                              SUM(CASE WHEN dc.tipo_movimiento = 'DB' THEN dc.importe_moneda_extranjera ELSE 0 END) - 
                                                              SUM(CASE WHEN dc.tipo_movimiento = 'HB' THEN dc.importe_moneda_extranjera ELSE 0 END)
                                                           WHEN ('".$tipo_cuenta."' = 'acreedor')
                                                          THEN 
                                                              (SUM(CASE WHEN dc.tipo_movimiento = 'HB' THEN dc.importe_moneda_extranjera ELSE 0 END) - 
                                                                SUM(CASE WHEN dc.tipo_movimiento = 'DB' THEN dc.importe_moneda_extranjera ELSE 0 END)) 
                                                          ELSE 0 
                                                      END AS saldo_cuenta,
                                                      e.id as id_entidad
                                                FROM contabilidad.plancuentas pc
                                           LEFT JOIN contabilidad.detalle_comprobante dc ON pc.id = dc.id_cuenta
                                           LEFT JOIN contabilidad.comprobante c ON dc.id_comprobante = c.id
                                           LEFT JOIN administracion.entidad e ON c.id_entidad = e.id 
                                               WHERE pc.estado = 'ACT'
                                                 AND c.estado in ('ACT','HI')
                                                 AND dc.estado in ('ACT','HI')
                                                 AND e.id = ".$id_entidad."
                                                 AND ('".$id_cuenta_mayor."' = ANY (string_to_array(pc.ruta, '-')) or pc.codigo = '".$cuenta_mayor."')
                                                 ".$whereFecha."
                                                 ".$whereCierre."
                                            GROUP BY 
                                                     pc.id, pc.codigo, pc.descripcion, pc.nivel, pc.padre, pc.ruta, e.nombre, e.id
                                            ORDER BY nivel ASC,
                                                     codigo ASC
                                          ) cuentas_con_movimiento on pc.id =cuentas_con_movimiento.id
                                   WHERE ('".$id_cuenta_mayor."' = ANY (string_to_array(pc.ruta, '-')) or pc.codigo = '".$cuenta_mayor."')
<<<<<<< HEAD
                                     AND pc.id_entidad = ".$id_entidad."
=======
>>>>>>> 86e86a998fd13133562672039854ed148e2211bf
                                ORDER BY nivel ASC,codigo ASC;
		    							                    ");
		    return $query->result();
    }
    /*FUNCIONES PARA EL CIERRE */
    function getBalanceGeneralPorMayor($id_entidad,$fecha_inicio,$fecha_fin,$cuenta_mayor,$id_cuenta_mayor,$whereFecha,$tipo_cuenta)
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
                                          ,case 
                                                when cuentas_con_movimiento.saldo_cuenta_usd  is null then 0.00
                                                else cuentas_con_movimiento.saldo_cuenta_usd 
                                          end  AS saldo_cuenta_usd  
                                    FROM  contabilidad.plancuentas pc
                               LEFT JOIN (
                                              SELECT 
                                                      pc.id,
                                                      pc.codigo,
                                                      pc.descripcion,
                                                      pc.nivel,
                                                      pc.padre,
                                                      pc.ruta,
                                                      e.nombre,
                                                      CASE 
                                                          WHEN ('".$tipo_cuenta."' = 'deudor')
                                                          THEN 
                                                              SUM(CASE WHEN dc.tipo_movimiento = 'DB' THEN dc.importe_moneda_nacional ELSE 0 END) - 
                                                              SUM(CASE WHEN dc.tipo_movimiento = 'HB' THEN dc.importe_moneda_nacional ELSE 0 END)
                                                          WHEN ('".$tipo_cuenta."' = 'acreedor')
                                                          THEN 
                                                              (SUM(CASE WHEN dc.tipo_movimiento = 'HB' THEN dc.importe_moneda_nacional ELSE 0 END) - 
                                                                SUM(CASE WHEN dc.tipo_movimiento = 'DB' THEN dc.importe_moneda_nacional ELSE 0 END)) 
                                                          ELSE 0 
                                                      END AS saldo_cuenta,
                                                      CASE 
                                                          WHEN ('".$tipo_cuenta."' = 'deudor')
                                                          THEN 
                                                              SUM(CASE WHEN dc.tipo_movimiento = 'DB' THEN dc.importe_moneda_extranjera ELSE 0 END) - 
                                                              SUM(CASE WHEN dc.tipo_movimiento = 'HB' THEN dc.importe_moneda_extranjera ELSE 0 END)
                                                          WHEN ('".$tipo_cuenta."' = 'acreedor')
                                                          THEN 
                                                              (SUM(CASE WHEN dc.tipo_movimiento = 'HB' THEN dc.importe_moneda_extranjera ELSE 0 END) - 
                                                                SUM(CASE WHEN dc.tipo_movimiento = 'DB' THEN dc.importe_moneda_extranjera ELSE 0 END)) 
                                                          ELSE 0 
                                                      END AS saldo_cuenta_usd,
                                                      e.id as id_entidad
                                                FROM contabilidad.plancuentas pc
                                           LEFT JOIN contabilidad.detalle_comprobante dc ON pc.id = dc.id_cuenta
                                           LEFT JOIN contabilidad.comprobante c ON dc.id_comprobante = c.id
                                           LEFT JOIN administracion.entidad e ON c.id_entidad = e.id 
                                               WHERE pc.estado = 'ACT'
                                                 AND c.estado in ('ACT','HI')
                                                 AND dc.estado in ('ACT','HI')
                                                 AND e.id = ".$id_entidad."
                                                 AND ('".$id_cuenta_mayor."' = ANY (string_to_array(pc.ruta, '-')) or pc.codigo = '".$cuenta_mayor."')
                                                 ".$whereFecha."
                                            GROUP BY 
                                                     pc.id, pc.codigo, pc.descripcion, pc.nivel, pc.padre, pc.ruta, e.nombre, e.id
                                            ORDER BY nivel ASC,
                                                     codigo ASC
                                          ) cuentas_con_movimiento on pc.id =cuentas_con_movimiento.id
                                   WHERE ('".$id_cuenta_mayor."' = ANY (string_to_array(pc.ruta, '-')) or pc.codigo = '".$cuenta_mayor."')
<<<<<<< HEAD
                                     AND pc.id_entidad = ".$id_entidad."
=======
>>>>>>> 86e86a998fd13133562672039854ed148e2211bf
                                ORDER BY nivel ASC,codigo ASC;
		    							                    ");
		    return $query->result();
    }

}