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
	function getPlanDeCuentasByNivel($nivel)
    {
        $query = $this->db_mercurio->query("select *
                                              from contabilidad.plancuentas
                                             where nivel= ".$nivel."
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


}
?>