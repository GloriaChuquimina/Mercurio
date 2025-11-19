<?php
/*
*/

class PlanDeCuentas_model extends CI_Model
{
	
	function __construct()
	{
		parent::__construct();	
		$this->db_mercurio = $this->load->database('db_mercurio', TRUE);		
	}

	function getPlanDeCuentas()
	{
		$query = $this->db_mercurio->query("select *
											 from contabilidad.plancuentas
										  --where estado='ACT'
										 order by nivel ASC,
  												  codigo ASC;
											" 
										  );
		return $query->result();
	}
	function getPlanDeCuentasBusqueda()
	{
		$query = $this->db_mercurio->query("select *
											 from contabilidad.plancuentas
										    where estado='ACT'
										 order by nivel ASC,
  												  codigo ASC;
											" 
										  );
		return $query->result();
	}
	function getPlanDeCuentasById($id)
    {
        $query = $this->db_mercurio->query("select *
                                              from contabilidad.plancuentas
                                             where id= ".$id."
                                               and estado='ACT'" 
                                         );
        return $query->result();
    }
	function getPlanDeCuentasAuxiliarById($id)
    {
        $query = $this->db_mercurio->query("select *
                                              from contabilidad.plancuentas_auxiliares
                                             where id= ".$id."
                                               and estado='ACT'" 
                                         );
        return $query->result();
    }
	function getPlanDeCuentasByNivel($nivel)
    {
        $query = $this->db_mercurio->query("select *
                                              from contabilidad.plancuentas
                                             where nivel= ".$nivel."
                                               and estado='ACT'" 
                                         );
        return $query->result();
    }
	function getPlanDeCuentasByCodigo($codigo)
    {
        $query = $this->db_mercurio->query("select *
                                              from contabilidad.plancuentas
                                             where codigo= '".$codigo."'
                                               and estado='ACT'" 
                                         );
        return $query->result();
    }
	function getPlanDeCuentasByNivelMaximo()
    {
        $query = $this->db_mercurio->query("select max(nivel) as nivel
                                              from contabilidad.plancuentas
                                             where estado='ACT'" 
                                         );
        return $query->result();
    }
	function getPlanDeCuentasByPadre($padre)
    {
        $query = $this->db_mercurio->query("select *
                                              from contabilidad.plancuentas
                                             where padre= ".$padre."
                                               and estado='ACT'" 
                                         );
        return $query->result();
    }
	
	function guardarPlanDeCuentas($data)
    {

        $this->db_mercurio->insert('contabilidad.plancuentas',$data);
        return $this->db_mercurio->insert_id();
    }

	function updatePlanDeCuentas($id_cuenta,$data)
	{
		$this->db_mercurio->where('id',$id_cuenta);
		return $this->db_mercurio->update('contabilidad.plancuentas',$data);
	}
	function guardarPlanDeCuentasDependencia($data)
    {
        $this->db_mercurio->insert('contabilidad.plancuenta_dependencia',$data);
        return $this->db_mercurio->insert_id();
    }	

	function getPlanDeCuentasBusquedaIds($ids)
	{
		$query = $this->db_mercurio->query("select *
											 from contabilidad.plancuentas
										    where estado='ACT'
											  and id in(".$ids.")
										 order by nivel ASC,
  												  codigo ASC;
											" 
										  );
		return $query->result();
	}

	function getAuxiliaresPlanDeCuentasById($id_cuenta)
	{
		$query = $this->db_mercurio->query("select *
											 from contabilidad.plancuentas_auxiliares
										    where estado='ACT'
											  and id_plancuenta=".$id_cuenta."
										 order by codigo ASC;
											" 
										  );
		return $query->result();
	}
	function guardarAuxiliaresPlanDeCuentas($data)
    {

        $this->db_mercurio->insert('contabilidad.plancuentas_auxiliares',$data);
        return $this->db_mercurio->insert_id();
    }
	function updateAuxiliarCuenta($id_auxCuenta,$data)
	{
		$this->db_mercurio->where('id',$id_auxCuenta);
		return $this->db_mercurio->update('contabilidad.plancuentas_auxiliares',$data);
	}
	
	function getNivelesPlanDeCuentas()
	{
		$query = $this->db_mercurio->query("select distinct p.nivel, ('Nivel '|| p.nivel) as name_nivel
		                                      from contabilidad.plancuentas p
											 where p.estado='ACT'
											 order by p.nivel ASC;
											");
		return $query->result();
	}
	function getMayoresPlanDeCuentas()
	{
		$query = $this->db_mercurio->query("select *
		                                      from contabilidad.plancuentas p
											 where p.estado='ACT'
											   and p.nivel = 1
											 order by p.nivel ASC;
											");
		return $query->result();
	}
	function getSubCuentasPlanDeCuentas($id_cuenta)
	{
		$query = $this->db_mercurio->query("select *
		                                      from contabilidad.plancuentas p
											 where p.estado='ACT'
											   and p.nivel > 1
											   and p.padre = ".$id_cuenta."
											 order by p.nivel ASC;
											");
		return $query->result();
	}
	function getOtrasSubCuentasPlanDeCuentas($id_mayor,$id_cuenta)
	{
		$query = $this->db_mercurio->query("select *
		                                      from contabilidad.plancuentas p
											 where p.estado='ACT'
											   and p.ruta like( '0-".$id_mayor."-".$id_cuenta."%')
											 order by p.nivel ASC;
											");
		return $query->result();
	}
	function buscarPlanDeCuentas($busqueda)
	{
		$query = $this->db_mercurio->query("select *
											 from contabilidad.plancuentas
										    where estado='ACT'
											  and 1=1
											  ".$busqueda."
										 order by nivel ASC,
  												  codigo ASC;
											" 
										  );
		return $query->result();
	}
}
?>