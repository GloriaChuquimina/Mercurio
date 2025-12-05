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

<<<<<<< HEAD
	function getPlanDeCuentas($id_entidad)
	{
		$query = $this->db_mercurio->query("select *
											  from contabilidad.plancuentas
										     where estado='ACT'
											   and id_entidad = ".$id_entidad."
										  order by nivel ASC,
  												   codigo ASC;
=======
	function getPlanDeCuentas()
	{
		$query = $this->db_mercurio->query("select *
											 from contabilidad.plancuentas
										  --where estado='ACT'
										 order by nivel ASC,
  												  codigo ASC;
>>>>>>> 86e86a998fd13133562672039854ed148e2211bf
											" 
										  );
		return $query->result();
	}
<<<<<<< HEAD
	function getPlanDeCuentasBusqueda($id_entidad)
=======
	function getPlanDeCuentasBusqueda()
>>>>>>> 86e86a998fd13133562672039854ed148e2211bf
	{
		$query = $this->db_mercurio->query("select *
											 from contabilidad.plancuentas
										    where estado='ACT'
<<<<<<< HEAD
											  and id_entidad = ".$id_entidad."
=======
>>>>>>> 86e86a998fd13133562672039854ed148e2211bf
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
<<<<<<< HEAD
	function getPlanDeCuentasByCodigoEntidad($codigo,$id_entidad)
    {
        $query = $this->db_mercurio->query("select *
                                              from contabilidad.plancuentas
                                             where codigo= '".$codigo."'
                                               and id_entidad = ".$id_entidad."
                                               and estado='ACT'" 
                                         );
        return $query->result();
    }
=======
>>>>>>> 86e86a998fd13133562672039854ed148e2211bf
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
	
<<<<<<< HEAD
	function getNivelesPlanDeCuentas($id_entidad)
=======
	function getNivelesPlanDeCuentas()
>>>>>>> 86e86a998fd13133562672039854ed148e2211bf
	{
		$query = $this->db_mercurio->query("select distinct p.nivel, ('Nivel '|| p.nivel) as name_nivel
		                                      from contabilidad.plancuentas p
											 where p.estado='ACT'
<<<<<<< HEAD
                                               and p.id_entidad = ".$id_entidad."
										  order by p.nivel ASC;
											");
		return $query->result();
	}
	function getMayoresPlanDeCuentas($id_entidad)
=======
											 order by p.nivel ASC;
											");
		return $query->result();
	}
	function getMayoresPlanDeCuentas()
>>>>>>> 86e86a998fd13133562672039854ed148e2211bf
	{
		$query = $this->db_mercurio->query("select *
		                                      from contabilidad.plancuentas p
											 where p.estado='ACT'
											   and p.nivel = 1
<<<<<<< HEAD
                                               and p.id_entidad = ".$id_entidad."
=======
>>>>>>> 86e86a998fd13133562672039854ed148e2211bf
											 order by p.nivel ASC;
											");
		return $query->result();
	}
<<<<<<< HEAD
	function getSubCuentasPlanDeCuentas($id_cuenta,$id_entidad)
=======
	function getSubCuentasPlanDeCuentas($id_cuenta)
>>>>>>> 86e86a998fd13133562672039854ed148e2211bf
	{
		$query = $this->db_mercurio->query("select *
		                                      from contabilidad.plancuentas p
											 where p.estado='ACT'
											   and p.nivel > 1
											   and p.padre = ".$id_cuenta."
<<<<<<< HEAD
                                               and p.id_entidad = ".$id_entidad."
=======
>>>>>>> 86e86a998fd13133562672039854ed148e2211bf
											 order by p.nivel ASC;
											");
		return $query->result();
	}
<<<<<<< HEAD
	function getOtrasSubCuentasPlanDeCuentas($id_mayor,$id_cuenta,$id_entidad)
=======
	function getOtrasSubCuentasPlanDeCuentas($id_mayor,$id_cuenta)
>>>>>>> 86e86a998fd13133562672039854ed148e2211bf
	{
		$query = $this->db_mercurio->query("select *
		                                      from contabilidad.plancuentas p
											 where p.estado='ACT'
											   and p.ruta like( '0-".$id_mayor."-".$id_cuenta."%')
<<<<<<< HEAD
											   and p.id_entidad = ".$id_entidad."
=======
>>>>>>> 86e86a998fd13133562672039854ed148e2211bf
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
<<<<<<< HEAD
										 order by id,codigo,nivel asc;

=======
										 order by nivel ASC,
  												  codigo ASC;
>>>>>>> 86e86a998fd13133562672039854ed148e2211bf
											" 
										  );
		return $query->result();
	}
}
?>