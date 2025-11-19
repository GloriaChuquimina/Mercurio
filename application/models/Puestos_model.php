<?php

class Puestos_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();			
		$this->db_rrhh = $this->load->database('db_recursos_humanos', TRUE);
	}

	function listaPuestos($id_persona)
	{
		$query = $this->db_rrhh->query("select pf.id_funcionario,pf.id_puesto,p.nombre_puesto,p.numero_item,dom.valor1 as sede_trabajo, p.id_dependencia,
											case 
												WHEN p.tipo_puesto='PLA' and p.nivel_dependencia is null and dj.tipo_puesto is null then p.id_subdependencia
												WHEN p.tipo_puesto='PLA' and dj.nivel_dependencia is not null and dj.tipo_puesto='PRI' then p.id_subdependencia
												WHEN p.tipo_puesto='PLA' and dj.nivel_dependencia is not null and dj.tipo_puesto='SEC' then dj.id_subdependencia
												WHEN p.tipo_puesto='APO' and p.nivel_dependencia is null and dj.tipo_puesto is null then p.id_subdependencia
												WHEN p.tipo_puesto='APO' and dj.nivel_dependencia is not null and dj.tipo_puesto='PRI' then p.id_subdependencia
												WHEN p.tipo_puesto='APO' and dj.nivel_dependencia is not null and dj.tipo_puesto='SEC' then dj.id_subdependencia
											end as id_subdependencia,
											case 
												WHEN p.tipo_puesto='PLA' and p.nivel_dependencia is null and dj.tipo_puesto is null then 'PRI'
												WHEN p.tipo_puesto='PLA' and dj.nivel_dependencia is not null and dj.tipo_puesto='PRI' then 'PRI'
												WHEN p.tipo_puesto='PLA' and dj.nivel_dependencia is not null and dj.tipo_puesto='SEC' then 'SEC'
												WHEN p.tipo_puesto='APO' and p.nivel_dependencia is null and dj.tipo_puesto is null then 'SEC'
												WHEN p.tipo_puesto='APO' and dj.nivel_dependencia is not null and dj.tipo_puesto='PRI' then 'PRI'
												WHEN p.tipo_puesto='APO' and dj.nivel_dependencia is not null and dj.tipo_puesto='SEC' then 'SEC'
											end as nivel_puesto,
											dj.nivel_dependencia,p.tipo_puesto, dj.estado as depjerarquica_estado
										from personal.puesto_funcionario pf join entidad.puesto p on (pf.id_puesto=p.id)
												join entidad.dependencia dep on (p.id_dependencia=dep.id) 
												join administracion.dominios dom on (dom.valor1=dep.sede_trabajo)
												left join entidad.dependencia_jerarquica dj on (pf.id_puesto=dj.id_puesto)
										where pf.estado='AC' and dom.concepto ='SEDE TRABAJO' 
												and pf.id_funcionario=".$id_persona."
										order by nivel_puesto"
										);
        return $query->result();   
	}

	function listaPuestosTodos($id_persona)
	{
		$query = $this->db_rrhh->query("select pf.id_funcionario,pf.id_puesto,p.nombre_puesto,p.numero_item,dom.valor1 as sede_trabajo, p.id_dependencia,
											case 
												WHEN p.tipo_puesto='PLA' and p.nivel_dependencia is null and dj.tipo_puesto is null then p.id_subdependencia
												WHEN p.tipo_puesto='PLA' and dj.nivel_dependencia is not null and dj.tipo_puesto='PRI' then p.id_subdependencia
												WHEN p.tipo_puesto='PLA' and dj.nivel_dependencia is not null and dj.tipo_puesto='SEC' then dj.id_subdependencia
												WHEN p.tipo_puesto='APO' and p.nivel_dependencia is null and dj.tipo_puesto is null then p.id_subdependencia
												WHEN p.tipo_puesto='APO' and dj.nivel_dependencia is not null and dj.tipo_puesto='PRI' then p.id_subdependencia
												WHEN p.tipo_puesto='APO' and dj.nivel_dependencia is not null and dj.tipo_puesto='SEC' then dj.id_subdependencia
											end as id_subdependencia,
											case 
												WHEN p.tipo_puesto='PLA' and p.nivel_dependencia is null and dj.tipo_puesto is null then 'PRI'
												WHEN p.tipo_puesto='PLA' and dj.nivel_dependencia is not null and dj.tipo_puesto='PRI' then 'PRI'
												WHEN p.tipo_puesto='PLA' and dj.nivel_dependencia is not null and dj.tipo_puesto='SEC' then 'SEC'
												WHEN p.tipo_puesto='APO' and p.nivel_dependencia is null and dj.tipo_puesto is null then 'SEC'
												WHEN p.tipo_puesto='APO' and dj.nivel_dependencia is not null and dj.tipo_puesto='PRI' then 'PRI'
												WHEN p.tipo_puesto='APO' and dj.nivel_dependencia is not null and dj.tipo_puesto='SEC' then 'SEC'
											end as nivel_puesto,
											dj.nivel_dependencia,p.tipo_puesto, dj.estado as depjerarquica_estado
										from personal.puesto_funcionario pf join entidad.puesto p on (pf.id_puesto=p.id)
												join entidad.dependencia dep on (p.id_dependencia=dep.id) 
												join administracion.dominios dom on (dom.valor1=dep.sede_trabajo)
												left join entidad.dependencia_jerarquica dj on (pf.id_puesto=dj.id_puesto)
										where  dom.concepto ='SEDE TRABAJO' 
												and pf.id_funcionario=".$id_persona."
										order by pf.estado asc"
										);
        return $query->result();   
	}

	function getListarTipoPuestosEspecificos($puestos)
	{
		$query = $this->db_rrhh->query("select *
										FROM administracion.dominios
										WHERE concepto = 'TIPO PUESTO' AND estado = 'AC' AND valor1 IN ('".$puestos."')");
        return $query->result();
	}

	function getListarEstadosEspecificos($estados)
	{
		$query = $this->db_rrhh->query("select *
										FROM administracion.dominios
										WHERE concepto = 'ESTADO REGISTRO' AND estado = 'AC' AND valor1 IN ('".$estados."')");
        return $query->result();
	}

	function getPuestosPorDireccionYTipoPuesto($id_depedendencia,$tipoPuesto,$estado)
	{
		$query = $this->db_rrhh->query("select p.id,p.numero_item, c.cargo,d.id as id_dependencia,d.nombre_dependencia,sd.nombre_subdependencia,p.nombre_puesto,p.tipo_puesto,p.estado,a.valor2 as estado_puesto
                                        from entidad.puesto p
	                                        left join entidad.cargo c on p.id_cargo=c.id
	                                        left join  entidad.dependencia d on p.id_dependencia=d.id
	                                        left join  entidad.subdependencia sd on p.id_subdependencia=sd.id
	                                        left join  administracion.dominios a  on p.estado=a.valor1 and a.concepto='ESTADO PUESTO'
										where p.tipo_puesto='".$tipoPuesto."' AND d.id=".$id_depedendencia." AND p.estado IN ('".$estado."')
										order by tipo_puesto desc, p.numero_item asc, c.id asc, nombre_puesto asc
									");
        return $query->result();
	}

	function guardarPuestoFuncionario($data)
	{
		$this->db_rrhh->insert('personal.puesto_funcionario',$data);
		return $this->db_rrhh->insert_id();
	}
	function actualizarPuestoFuncionario($id_puesto_funcionario,$data)
	{
		$this->db_rrhh->where('id',$id_puesto_funcionario);
		return  $this->db_rrhh->update('personal.puesto_funcionario',$data);
	}
	function getDatosPuestoFuncionario($id_puesto_funcionario)
	{
		$query = $this->db_rrhh->query("select *
										FROM personal.puesto_funcionario
										WHERE id=".$id_puesto_funcionario);
		return $query->result();
	}

	function getDatosPuestoFuncionarioUltimoAsignado($id_funcionario,$id_puesto)
	{
		$query = $this->db_rrhh->query("select *
										FROM personal.puesto_funcionario
										WHERE id_funcionario=".$id_funcionario." AND id_puesto=".$id_puesto."
											AND estado in ('AC')
										ORDER by id DESC");
		return $query->result();
	}
	function guardarIncorporacionPlanta($data)
	{
		$this->db_rrhh->insert('personal.incorporacion_planta',$data);
		return $this->db_rrhh->insert_id();
	}
	function actualizarIncorporacionPlanta($id_incorporacion_planta,$data)
	{
		$this->db_rrhh->where('id',$id_incorporacion_planta);
		return  $this->db_rrhh->update('personal.incorporacion_planta',$data);
	}
	function actualizarIncorporacionPlantaPorFuncionario($id_funcionario,$data)
	{
		$this->db_rrhh->where('id_funcionario',$id_funcionario);
		$this->db_rrhh->where('estado','AC');
		return  $this->db_rrhh->update('personal.incorporacion_planta',$data);
	}

	function actualizarPuesto($id_puesto,$data)
	{
		$this->db_rrhh->where('id',$id_puesto);
		return  $this->db_rrhh->update('entidad.puesto',$data);
	}

	function getListarPuestoFuncionarioPorFuncionario($id_funcionario)
	{
		$query = $this->db_rrhh->query("select *
										FROM personal.puesto_funcionario
										WHERE id_funcionario=".$id_funcionario." AND estado = 'AC'");
		return $query->result();
	}
	function getPuesto($id_puesto)
	{
		$query = $this->db_rrhh->query("select *
									      from entidad.puesto p 
									     where id = ".$id_puesto);
		return $query->result(); 
	}
	function getPuestoFuncionario($id_puesto_funcionario)
	{
		$query = $this->db_rrhh->query("select count(*)
									      from personal.puesto_funcionario
									     where id = ".$id_puesto);
		return $query->result(); 
	}




	function verificarPuestosDuplicados($id_puesto_funcionario)
	{
		$query = $this->db_rrhh->query("select count(*)
									      from personal.puesto_funcionario
									     where id = ".$id_puesto);
		return $query->result(); 
	}
	function getUltimoPuestoDelFuncionario($id_funcionario)
	{
		$query = $this->db_rrhh->query("select *
									      from personal.puesto_funcionario
									     where id_funcionario = ".$id_funcionario."
									     order by fecha_baja desc Limit 1");
		return $query->result(); 
	}

	function eliminarFuncionarioyPuesto($idPuestoFuncionario,$idFuncionario,$idPuesto,$datos_puesto_funcionario,$datos_incorporacion_planta,$datos_puesto,$datos_funcionario){
		//Iniciamos la transacción.
		$this->db->trans_begin();
		//Recuperamos el id de la nueva insersión
		//$cliente_id = $this->db->insert_id();
		//Actualizamos el puesto_funcionario.
		$this->db_rrhh->where('id',$idPuestoFuncionario);
		$this->db_rrhh->update('personal.puesto_funcionario',$datos_puesto_funcionario);

		//Actualizamos el puesto con acefalía
		$this->db_rrhh->where('id',$idPuesto);
		$this->db_rrhh->update('entidad.puesto',$datos_puesto);

		if(!empty($datos_incorporacion_planta)){
			//Actualizamos Incorporacion_planta
			$this->db_rrhh->where('id_funcionario',$idFuncionario);
			$this->db_rrhh->update('personal.incorporacion_planta',$datos_incorporacion_planta);	
		}

		if(!empty($datos_funcionario)){
			//Actualizamos el estado del funcionario
			$this->db_rrhh->where('id',$idFuncionario);
			$this->db_rrhh->update('personal.funcionario',$datos_funcionario);			
		}

		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return 0;
		} else {
			$this->db->trans_commit();
			return 1;
		}
	}

	function transactRotacionPersonal($idPuestoFuncionario,$puestoActual,$puestoNuevo,$datos_actualPuestoFuncionario,$datos_nuevoPuestoFuncionario,$datos_puestoActual,$datos_puestoNuevo){
		//Iniciamos la transacción.
		$this->db_rrhh->trans_begin();

		//Actualizamos el puesto_funcionario.
		$this->db_rrhh->where('id',$idPuestoFuncionario);
		$this->db_rrhh->update('personal.puesto_funcionario',$datos_actualPuestoFuncionario);

		$this->db_rrhh->insert('personal.puesto_funcionario',$datos_nuevoPuestoFuncionario);
		//Recuperamos el id de la nueva insersión
		$nuevoPuestoFuncionario = $this->db_rrhh->insert_id();

		//Actualizamos el puesto actual
		$this->db_rrhh->where('id',$puestoActual);
		$this->db_rrhh->update('entidad.puesto',$datos_puestoActual);

		//Actualizamos el nuevo puesto
		$this->db_rrhh->where('id',$puestoNuevo);
		$this->db_rrhh->update('entidad.puesto',$datos_puestoNuevo);

		if ($this->db_rrhh->trans_status() === FALSE){
			$this->db_rrhh->trans_rollback();
			return 0;
		} else {
			$this->db_rrhh->trans_commit();
			return $nuevoPuestoFuncionario;
		}
	}



	function listaPuestosDuodecimas($id_persona)
	{
		$query = $this->db_rrhh->query("select pf.id_funcionario,pf.id_puesto,p.nombre_puesto,p.numero_item,dom.valor1 as sede_trabajo, p.id_dependencia,
											case 
												WHEN p.tipo_puesto='PLA' and p.nivel_dependencia is null and dj.tipo_puesto is null then p.id_subdependencia
												WHEN p.tipo_puesto='PLA' and dj.nivel_dependencia is not null and dj.tipo_puesto='PRI' then p.id_subdependencia
												WHEN p.tipo_puesto='PLA' and dj.nivel_dependencia is not null and dj.tipo_puesto='SEC' then dj.id_subdependencia
												WHEN p.tipo_puesto='APO' and p.nivel_dependencia is null and dj.tipo_puesto is null then p.id_subdependencia
												WHEN p.tipo_puesto='APO' and dj.nivel_dependencia is not null and dj.tipo_puesto='PRI' then p.id_subdependencia
												WHEN p.tipo_puesto='APO' and dj.nivel_dependencia is not null and dj.tipo_puesto='SEC' then dj.id_subdependencia
											end as id_subdependencia,
											case 
												WHEN p.tipo_puesto='PLA' and p.nivel_dependencia is null and dj.tipo_puesto is null then 'PRI'
												WHEN p.tipo_puesto='PLA' and dj.nivel_dependencia is not null and dj.tipo_puesto='PRI' then 'PRI'
												WHEN p.tipo_puesto='PLA' and dj.nivel_dependencia is not null and dj.tipo_puesto='SEC' then 'SEC'
												WHEN p.tipo_puesto='APO' and p.nivel_dependencia is null and dj.tipo_puesto is null then 'SEC'
												WHEN p.tipo_puesto='APO' and dj.nivel_dependencia is not null and dj.tipo_puesto='PRI' then 'PRI'
												WHEN p.tipo_puesto='APO' and dj.nivel_dependencia is not null and dj.tipo_puesto='SEC' then 'SEC'
											end as nivel_puesto,
											dj.nivel_dependencia,p.tipo_puesto, dj.estado as depjerarquica_estado
										from personal.puesto_funcionario pf join entidad.puesto p on (pf.id_puesto=p.id)
												join entidad.dependencia dep on (p.id_dependencia=dep.id) 
												join administracion.dominios dom on (dom.valor1=dep.sede_trabajo)
												left join entidad.dependencia_jerarquica dj on (pf.id_puesto=dj.id_puesto)
										where dom.concepto ='SEDE TRABAJO' 
												and pf.id_funcionario=".$id_persona."
										order by nivel_puesto"
										);
        return $query->result();   
	}
	/*MEMORAMDUM*/
	function guardarMemorandumDesignacion($data)
	{
		$this->db_rrhh->insert('personal.memorandum_designacion_funcionario',$data);
		return $this->db_rrhh->insert_id();
	}
	function getDatosPuestoFuncionarioMemoramdum($id_puesto_funcionario)
	{
		
		$query = $this->db_rrhh->query('Select  pf.*,
												mf.id as id_memorandum,
												mf.id_puesto_funcionario,
												mf.id_cite_memorandum,
												mf.cite_memorandum,
												mf.id_usuario,
												mf.fecha_registro,
												mf.estado,
												mf.pie_firma,
												p.nombre_puesto,
												d.id as id_dependencia
										   from personal.puesto_funcionario pf
								left outer join personal.memorandum_designacion_funcionario mf on pf.id=mf.id_puesto_funcionario
								left outer join entidad.puesto p on pf.id_puesto=p.id
								left outer join entidad.dependencia d on p.id_dependencia = d.id
										  where 1=1
											and pf.id='.$id_puesto_funcionario);
		return $query->result();

	}
	/*LISTAR PUESTO PRINCIPAL DE FUNCIONARIO*/
	function getListarPuestoFuncionarioPrincipal($id_funcionario)
	{
		$query = $this->db_rrhh->query("select pf.*	,
											   p.tipo_puesto	
										  from personal.puesto_funcionario pf 
							   left outer join entidad.puesto p on pf.id_puesto = p.id 
										 where pf.id_funcionario = ".$id_funcionario."
										   and p.tipo_puesto in ('PLA','CON')
										   and pf.estado='AC'");
		return $query->result();
	}
	/*LISTAR PUESTOS ,FUNCIONARIOS ASIGNADOS */
	function getListaPuestoFuncionarioId($id_funcionario)
	{
		$query = $this->db_rrhh->query("select *
										FROM personal.puesto_funcionario
										WHERE id_funcionario=".$id_funcionario." AND estado = 'AC'");
		return $query->result();
	}

}

?>