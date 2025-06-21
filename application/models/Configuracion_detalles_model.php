<?php
/*
*/

class Configuracion_detalles_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->db_rrhh = $this->load->database('db_recursos_humanos', TRUE);
		$this->db_mercurio = $this->load->database('db_mercurio', TRUE);
	}

	function getGestionVigente()
	{
		$query = $this->db_rrhh->query("select *
			                              from configuraciones.gestion
			                             where estado = 'AC'");
        return $query->result();
	}
	function getValoresDominios($concepto)
	{
		$query = $this->db_rrhh->query("select *
			                              from administracion.dominios
			                             where concepto ='".$concepto."'
			                               and estado = 'AC'
			                             order by orden asc");
        return $query->result();
	}
	function getValoresDominiosValor($concepto,$valor)
	{
		$query = $this->db_rrhh->query("select *
			                              from administracion.dominios
			                             where concepto ='".$concepto."'
			                               and valor1 ='".$valor."'
			                               and estado = 'ACT'
			                             order by orden asc");
        return $query->result();
	}
	function getValoresDominiosDireccion($direccion)
	{
		$query = $this->db_rrhh->query("select *
			                              from administracion.dominios
			                             where descripcion ='".$direccion."'
			                               and estado = 'ACT'
			                             order by orden asc");
        return $query->result();
	}
	function getValoresPermisosDominiosConcepto($valor)
	{
		$query = $this->db_rrhh->query("select *
			                              from boletas.permisos_dominio
			                             where id ='".$valor."'
			                               and estado = 'ACT'");
        return $query->result();
	}
	function getValoresDominiosCombos($concepto)
	{
		$query = $this->db_rrhh->query("select valor2, valor1
			                              from administracion.dominios
			                             where concepto ='".$concepto."'
			                               and estado = 'ACT'
										   order by valor2");
        return $query->result();
	}
	function getGestiones()
	{
		$query = $this->db_rrhh->query("select *
			                              from configuraciones.gestion
										  order by estado asc ");
        return $query->result();
	}
	function getValoresDominiosCombosExcluyendoValores($concepto,$excluir)
	{
		$query = $this->db_rrhh->query("select valor2, valor1
			                              from administracion.dominios
			                             where concepto ='".$concepto."'
			                               and estado = 'ACT' and valor1 not in('".$excluir."')
										   order by valor2");
        return $query->result();
	}
	// function getValoresDominiosConcepto($concepto,$valor) 
	// {
	// 	$query = $this->db_rrhh->query("select *
	// 		                              from administracion.dominios
	// 		                             where concepto ='".$concepto."'
	// 		                               and valor1 ='".$valor."'
	// 		                               and estado = 'ACT'
	// 		                             order by orden asc");
    //     return $query->result();
	// }

	function getValoresDominiosDescripcion($descripcion,$valor)
	{
		$query = $this->db_rrhh->query("select *
			                              from administracion.dominios
			                             where descripcion ='".$descripcion."'
			                               and valor1 ='".$valor."'
			                               and estado = 'ACT'
			                             order by orden asc");
        return $query->result();
	}
	function getValorDominiosConceptoOtro()
	{
		$query = $this->db_rrhh->query("select nombre_institucion
										  from ficha_personal.normativa_funcionario
										where institucion ='OT'");
        return $query->result();
	}
	function getConfiguracionDetalles($concepto)
	{
		$query = $this->db_rrhh->query("select c.*,
		                                       d.valor2 as estadoregistro
			                              from configuraciones.configuracion_detalles c,
			                                   administracion.dominios d
			                             where c.concepto ='".$concepto."'
			                               and d.valor1 = c.estado
			                               and d.concepto = 'ESTADO REGISTRO'
			                             order by id asc");
        return $query->result();
	}
	function getConfiguracionDetalles_id($concepto)
	{
		$query = $this->db_rrhh->query("select *
			                              from configuraciones.configuracion_detalles c
			                             where c.concepto ='".$concepto."'
			                               and c.estado IN ('AC','HI')
			                             order by id DESC");
        return $query->result();
	}

	function getConfiguracionDetallesGestion($gestion,$concepto)
	{
		$query = $this->db_rrhh->query("select *
			                              from configuraciones.configuracion_detalles c
			                             where c.gestion = ".$gestion."
			                               and c.concepto ='".$concepto."'
			                               and c.estado IN ('AC','HI')
			                             order by id DESC");
        return $query->result();
	}
	function getConfiguracionDetallesLiquidacion($concepto)
	{
		$query = $this->db_rrhh->query("select *
			                              from configuraciones.configuracion_detalles c
			                             where c.concepto ='".$concepto."'			                               
			                               and c.estado IN ('AC')
			                             order by id asc");
        return $query->result();
	}


	function getConfiguracionDetallesLiquidacionVacacion($concepto,$fecha)
	{
		$query = $this->db_rrhh->query("select *
			                              from configuraciones.configuracion_detalles c
			                             where c.concepto ='".$concepto."'
			                               and '".$fecha."' between valor1 and valor2
			                               and c.estado IN ('AC')
			                             order by id asc");
        return $query->result();
	}

	function guardarConfiguracion($data)
    {
        $dataH = array(
        	'estado' => 'HI'
        );
        $update = $this->db_rrhh->update('configuraciones.configuracion_detalles',$dataH);

        $this->db_rrhh->insert('configuraciones.configuracion_detalles',$data);
        return $this->db_rrhh->insert_id();
    }

    function getConfiguracionDetallesId($id)
	{
		$query = $this->db_rrhh->query("select *
			                              from configuraciones.configuracion_detalles c
			                             where id = ".$id);
        return $query->result();
	}

	function editarConfiguracion($id_registro,$data)
    {
        $this->db_rrhh->where('id',$id_registro);
        return $this->db_rrhh->update('configuraciones.configuracion_detalles',$data);
    }
	function getHijosFuncionarios($id_funcionario)
	{
		$query = $this->db_rrhh->query("select count(*) as hijos
										  from ficha_personal.hijos
										where id_funcionario =".$id_funcionario);
        return $query->result();
	}
	/*MERCURIO */
	function getValoresDominiosConcepto($concepto,$valor) 
	{
		$query = $this->db_mercurio->query("select *
			                              from administracion.dominios
			                             where concepto ='".$concepto."'
			                               and valor1 ='".$valor."'
			                               and estado = 'ACT'
			                          order by orden asc");
        return $query->result();
	}
}
?>