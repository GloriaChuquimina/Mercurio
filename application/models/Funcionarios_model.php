<?php
/*
*/

class Funcionarios_model extends CI_Model
{
	
	function __construct()
	{
		parent::__construct();			
		$this->db_rrhh = $this->load->database('db_recursos_humanos', TRUE);
	}
	function getfuncionarios()
	{
		$query = $this->db_rrhh->query("select *
									      from personal.funcionario 
									     where estado = 'AC'									       
									     order by id asc");
        return $query->result();   
	}
	function getfuncionariosLimit($inicio,$fin)
	{
		$query = $this->db_rrhh->query("select *
									      from personal.funcionario 
									     where 1 = 1									       
									       and id between ".$inicio." and ".$fin."
									     order by id asc");
        return $query->result();   
	}
	function datosPersonas($id_persona)
	{
		$query = $this->db_rrhh->query("select *
									      from personal.funcionario 
									     where id= ".$id_persona);
        return $query->result();   
	}
	function datosPersonalesCompleto($id_persona)
	{
		$query = $this->db_rrhh->query("select dpc.*, fb.codigo_biometrico
									      from personal.datos_personales_completo dpc
									      left join marcacion.funcionario_biometrico fb on(dpc.id=fb.id_funcionario)
									     where fb.estado='AC' AND dpc.id= ".$id_persona);
        return $query->result();   
	}

	function estructura_id($iddireccion)
    {
        $query = $this->db_rrhh->query("select * from personal.vista_puesto_funcionarios_activos d where d.id_dependencia =" . $iddireccion);
        return $query->result();
    }

	function getListarFuncionariosPlantaPorEstado($estado)
	{
    	$where='';
    	if($estado<>0){
    		$where.= " AND f.estado ='".$estado."'";
    	}
		$query = $this->db_rrhh->query("
					select ip.id_funcionario, primer_apellido,segundo_apellido,nombres
					from personal.funcionario f JOIN personal.incorporacion_planta ip ON (f.id=ip.id_funcionario)
					where 1=1".$where."
					order by primer_apellido,segundo_apellido");
        return $query->result();   
	}

	function getDatosGeneroFuncionario($genero)
    {
    	$where='';
    	if($genero!='0'){
    		$where.= " AND sexo='".$genero."'";
    	}
    	$query = $this->db_rrhh->query("select primer_apellido,segundo_apellido,nombres,numero_documento,extension,genero,edad,tipo_puesto
        			from personal.vista_informacion_total_personal
        			where estado_funcionario='AC' AND estado_puesto_funcionario='AC' and estado_incorporacion_planta='AC' AND tipo_puesto<>'APO'".$where."
        			order by primer_apellido,segundo_apellido");
        return $query->result();
    }

	function getDatosLugarNacimiento($lugarNacimiento)
    {
    	$where='';
    	if($lugarNacimiento!='0'){
    		$where.= " AND lugar_nacimiento='".$lugarNacimiento."'";
    	}
    	$query = $this->db_rrhh->query("select primer_apellido,segundo_apellido,nombres,numero_documento,extension,genero,edad,fecha_nacimiento,departamento_nacimiento,provincia_nacimiento,municipio_nacimiento
        			from personal.vista_informacion_total_personal
        			where estado_funcionario='AC' AND estado_puesto_funcionario='AC' and estado_incorporacion_planta='AC' AND tipo_puesto<>'APO'".$where."
        			order by primer_apellido,segundo_apellido");
        return $query->result();
    }

	function getDatosFormacionAcademica($formacionAcademica)
    {
    	$where='';
    	if($formacionAcademica<>'0'){
    		$where.= " AND fa.grado_formacion='".$formacionAcademica."'";
    	}
    	$query = $this->db_rrhh->query("select p.id_funcionario,primer_apellido,segundo_apellido,nombres,nombre_subdependencia,nombre_dependencia,nombre_cargo,nombre_puesto,
						CASE
							WHEN nombre_subdependencia<>'' THEN nombre_subdependencia||' / '||nombre_dependencia
						ELSE nombre_dependencia
						END AS unidad_direccion,
						p.numero_documento,p.extension,genero,fa.grado_formacion,d.valor2 as formacion_academica, fa.titulo_obtenido
        			from personal.vista_informacion_total_personal p join ficha_personal.formacion_academica fa ON (p.id_funcionario=fa.id_funcionario)
							JOIN administracion.dominios d ON (fa.grado_formacion=d.valor1 AND d.concepto='GRADO FORMACION' AND d.estado='AC')
        			where estado_funcionario='AC' AND estado_puesto_funcionario='AC' and estado_incorporacion_planta='AC' AND tipo_puesto<>'APO' AND fa.estado='AC'".$where."
					order by primer_apellido,segundo_apellido,nombres,d.orden desc");
        return $query->result();
    }

	function getDatosMadrePadre($opcion)
    {
    	$where='';
    	if($opcion<>'0'){
    		if($opcion=='PA'){
    			$where.= " AND es_padre_madre='PADRE'";
    		}
    		if($opcion=='MA'){
    			$where.= " AND es_padre_madre='MADRE'";
    		}
    		if($opcion=='SH'){
    			$where.= " AND (tiene_hijos='f' or tiene_hijos is null)";
    		}
    	}
    	$query = $this->db_rrhh->query("select primer_apellido,segundo_apellido,nombres,genero,edad,es_padre_madre
        			from personal.vista_informacion_total_personal
        			where estado_funcionario='AC' AND estado_puesto_funcionario='AC' and estado_incorporacion_planta='AC' AND tipo_puesto<>'APO'".$where."
        			order by primer_apellido,segundo_apellido");
        return $query->result();
    }

	function getDatosAFP($afp)
    {
    	$where='';
    	if($afp<>'0'){
    		$where.= " AND afp='".$afp."'";
    	}
    	$query = $this->db_rrhh->query("select primer_apellido,segundo_apellido,nombres,numero_documento,extension,afp,nua_cua
        			from personal.vista_informacion_total_personal
        			where estado_funcionario='AC' AND estado_puesto_funcionario='AC' and estado_incorporacion_planta='AC' AND tipo_puesto<>'APO'".$where."
        			order by primer_apellido,segundo_apellido");
        return $query->result();
    }

	function getDatosCAS($cas)
    {
    	$where='';
    	if($cas<>'0'){
    		$where.= " AND cas.estado='".$cas."'";
    	}
    	$query = $this->db_rrhh->query("select primer_apellido,segundo_apellido,nombres,anhios,meses,dias,fecha_cas,cas.estado
        			from personal.vista_informacion_total_personal p JOIN personal.cas_funcionario cas ON (p.id_funcionario=cas.id_funcionario)
        			where estado_funcionario='AC' AND estado_puesto_funcionario='AC' and estado_incorporacion_planta='AC' AND tipo_puesto<>'APO'".$where."
        			order by primer_apellido,segundo_apellido,fecha_cas desc");
        return $query->result();
    }

	function getDatosDiscapacidad()
    {
    	$query = $this->db_rrhh->query("select primer_apellido,segundo_apellido,nombres,tiene_discapacidad,tutor_persona_discapacidad, '' as tipo_discapacidad, '' as vigencia_carnet_discapacidad
        			from personal.vista_informacion_total_personal p JOIN ficha_personal.datos_complementarios dc ON (p.id_funcionario=dc.id_funcionario)
        			where estado_funcionario='AC' AND estado_puesto_funcionario='AC' and estado_incorporacion_planta='AC' AND tipo_puesto<>'APO' AND (dc.tiene_discapacidad=true or dc.tutor_persona_discapacidad=true)
        			order by primer_apellido,segundo_apellido desc");
        return $query->result();
    }

	function getDatosCursosNormativaPorFuncionario($id_funcionario)
	{
		$query = $this->db_rrhh->query("select nf.id_funcionario, nf.id_cursos_normativa,cn.nombre_curso,cantidad_horas,institucion, d.valor2 as nombre_institucion,nombre_institucion as otra_institucion, nf.estado
					from ficha_personal.normativa_funcionario nf JOIN ficha_personal.cursos_normativa cn ON (nf.id_cursos_normativa=cn.id)
						JOIN administracion.dominios d ON (nf.institucion=d.valor1 AND d.concepto='INSTITUCION')
					where nf.estado='AC' AND nf.id_funcionario=".$id_funcionario."
					order by cn.orden");
		return $query->result();
	}

	function getDatosAsuetos($asueto)
	{
		$query = $this->db_rrhh->query(
				"select * from(
					select DISTINCT ON (p.id_funcionario) p.id_funcionario,primer_apellido,segundo_apellido,nombres,nombre_subdependencia,sigla_subdependencia,nombre_dependencia,
						CASE
							WHEN nombre_subdependencia<>'' THEN nombre_subdependencia||' / '||sigla_dependencia
						ELSE sigla_dependencia
						END AS unidad_direccion,
							dia_inicio,dia_fin
        			from personal.vista_informacion_total_personal p join boletas.salida_dias sd ON (p.id_funcionario=sd.id_funcionario)
        			where tipo_puesto<>'APO' AND sd.estado='AC' AND sd.id_permiso_dominio=".$asueto." AND estado_salida NOT IN ('ANU')
					order by p.id_funcionario,primer_apellido, segundo_apellido	) as tabla
				order by primer_apellido,segundo_apellido");
		return $query->result();
	}
	/*
	function getHorarioEspecialPorId($horarioEspecial)
	{
		$query = $this->db_rrhh->query(
				"select * from(
					select DISTINCT ON (p.id_funcionario) P.id_funcionario,primer_apellido,segundo_apellido,nombres,nombre_subdependencia,sigla_subdependencia,nombre_dependencia,
						CASE
							WHEN nombre_subdependencia<>'' THEN nombre_subdependencia||' / '||sigla_dependencia
						ELSE sigla_dependencia
						END AS unidad_direccion,
							dia_inicio,dia_fin
        			from personal.vista_informacion_total_personal p join boletas.salida_dias sd ON (p.id_funcionario=sd.id_funcionario)
        			where tipo_puesto<>'APO' AND sd.estado='AC' AND sd.id_permiso_dominio=".$asueto."
					order by p.id_funcionario,primer_apellido, segundo_apellido	) as tabla
				order by primer_apellido,segundo_apellido");
		return $query->result();
	}
	*/
	function getListarSalidaDiasPorMesyTipo($gestion,$mes,$permiso_dominio)
	{
		$query = $this->db_rrhh->query(
				"select * from(
					select DISTINCT ON (sd.id) sd.id, p.id_funcionario,primer_apellido,segundo_apellido,nombres,nombre_subdependencia,sigla_subdependencia,nombre_dependencia,
						CASE
							WHEN nombre_subdependencia<>'' THEN nombre_subdependencia||' / '||sigla_dependencia
						ELSE sigla_dependencia
						END AS unidad_direccion,
							EXTRACT(MONTH FROM dia_inicio) as mes,gestion,dia_inicio,dia_fin,motivo,cite_boleta,tiene_horas
        			from personal.vista_informacion_total_personal p join boletas.salida_dias sd ON (p.id_funcionario=sd.id_funcionario)
        			where tipo_puesto<>'APO' AND sd.estado='AC' AND sd.id_permiso_dominio=".$permiso_dominio." AND estado_salida NOT IN ('ANU') AND gestion=". $gestion." AND EXTRACT(MONTH FROM dia_inicio)=". $mes."
					order by sd.id) as tabla
				order by dia_inicio,primer_apellido,segundo_apellido");
		return $query->result();
	}

	function getListarComisionTrabajoPorMes($gestion,$mes)
	{
		$query = $this->db_rrhh->query(
				"select * from(
					select DISTINCT ON (sd.id) sd.id, p.id_funcionario,primer_apellido,segundo_apellido,nombres,nombre_subdependencia,sigla_subdependencia,nombre_dependencia,
						CASE
							WHEN nombre_subdependencia<>'' THEN nombre_subdependencia||' / '||sigla_dependencia
						ELSE sigla_dependencia
						END AS unidad_direccion,
							EXTRACT(MONTH FROM dia_inicio) as mes,gestion,dia_inicio,dia_fin,motivo,cite_boleta,tiene_horas,hora_salida,retorno,hora_retorno,estado_salida
        			from personal.vista_informacion_total_personal p join boletas.salida_dias sd ON (p.id_funcionario=sd.id_funcionario)
							left join boletas.salida_horas sh ON (sd.id=sh.id_salida_dias)
        			where tipo_puesto<>'APO' AND sd.estado='AC' AND estado_salida NOT IN ('ANU') 
        					AND sd.id_permiso_dominio=3 AND gestion=". $gestion." AND EXTRACT(MONTH FROM dia_inicio)=". $mes."
					order by sd.id) as tabla
				order by dia_inicio,primer_apellido,segundo_apellido");
		return $query->result();
	}

	function getListarPermisosParticularesPorMes($gestion,$mes)
	{
		$query = $this->db_rrhh->query(
				"select * from(
					select DISTINCT ON (per.id) per.id, p.id_funcionario,primer_apellido,segundo_apellido,nombres,nombre_subdependencia,sigla_subdependencia,nombre_dependencia,
						CASE
							WHEN nombre_subdependencia<>'' THEN nombre_subdependencia||' / '||sigla_dependencia
						ELSE sigla_dependencia
						END AS unidad_direccion,
							EXTRACT(MONTH FROM fecha_permiso) as mes,gestion,fecha_permiso,fecha_permiso_hasta,motivo,cite_permiso,tipo_permiso,d.valor2 as tipopermiso,turno, tur.valor2 as turno_permiso
        			from personal.vista_informacion_total_personal p join boletas.permiso per ON (p.id_funcionario=per.id_funcionario)
        				JOIN administracion.dominios d ON (per.tipo_permiso=d.valor1 AND d.concepto='TIPO PERMISO' AND d.estado='AC')
						JOIN administracion.dominios tur ON (per.turno=tur.valor1 AND tur.concepto='TURNO PERMISO' AND tur.estado='AC')
        			where tipo_puesto<>'APO' AND per.estado NOT IN ('ANU') AND gestion=". $gestion." AND EXTRACT(MONTH FROM fecha_permiso)=". $mes."
					order by per.id) as tabla
				order by fecha_permiso,primer_apellido,segundo_apellido");
		return $query->result();
	}

	function listarFuncionariosPorTipoPuestoYEstado($tipoPuesto,$estado,$orden)
	{
		$query = $this->db_rrhh->query("
					select *
					from personal.vista_puesto_funcionarios_activos
					where tipo_puesto = '".$tipoPuesto."' AND estado IN (".$estado.")".
					$orden);
        return $query->result();   
	}

	function getDatosPersonasPorCi($ci)
	{
		$query = $this->db_rrhh->query("select p.*, fb.codigo_biometrico, fb.estado as estado_codigo_biometrico
									      from personal.funcionario p left JOIN marcacion.funcionario_biometrico fb ON (p.id=fb.id_funcionario)
									     where fb.estado IN ('AC') AND p.numero_documento=".$ci."
									     order by fb.fecha_registro desc");
        return $query->result();   
	}

	function guardarPersona($data)
	{
		$this->db_rrhh->insert('personal.funcionario',$data);
		return $this->db_rrhh->insert_id();
	}

	function guardarFuncionarioBiometrico($data)
	{
		$this->db_rrhh->insert('marcacion.funcionario_biometrico',$data);
		return $this->db_rrhh->insert_id();
	}

	function getlistarPuestoYCargoPorFuncionario($id_funcionario,$estado,$tipo_puesto)
	{
		$query = $this->db_rrhh->query("select *
										from personal.vista_puesto_funcionarios_activos
										where id=".$id_funcionario." AND estado IN (".$estado.") AND tipo_puesto IN (".$tipo_puesto.")");
		return $query->result();   
	}

	function datosPersonalesPuestoOficial($id_funcionario)
	{
		$query = $this->db_rrhh->query("select dpc.*, fb.codigo_biometrico, vpfa.*, d.valor2 as tipo_puesto_persona
										from personal.datos_personales_completo dpc
											JOIN personal.vista_puesto_funcionarios_activos vpfa ON (dpc.id=vpfa.id)
											JOIN administracion.dominios d ON (vpfa.tipo_puesto=d.valor1 AND d.concepto='TIPO PUESTO' AND d.estado='AC')
											LEFT JOIN marcacion.funcionario_biometrico fb on(dpc.id=fb.id_funcionario)
									     where fb.estado='AC' AND vpfa.tipo_puesto IN('PLA','CON') AND dpc.id=".$id_funcionario.
									     "order by vpfa.fecha_alta desc");
        return $query->result();   
	}

    function editarPersona($id_registro,$data)
    {
        $this->db_rrhh->where('id',$id_registro);
        return $this->db_rrhh->update('personal.funcionario',$data);
    }
	/*RESUMEN DE DATOS FUNCIONARIO PARA REPORTES DE DUODECIMAS */
	function datosPersonalesPuestoGeneral($id_funcionario)
	{
		$query = $this->db_rrhh->query("
										select  f.id as id_funcionario 
											   ,f.nombres 
											   ,f.primer_apellido 
											   ,f.segundo_apellido 
											   ,f.numero_documento 
											   ,f.complemento 
											   ,p.id_dependencia
											   ,case 
													WHEN p.tipo_puesto='PLA' and p.nivel_dependencia is null and dj.tipo_puesto is null then p.id_subdependencia
													WHEN p.tipo_puesto='PLA' and dj.nivel_dependencia is not null and dj.tipo_puesto='PRI' then p.id_subdependencia
													WHEN p.tipo_puesto='PLA' and dj.nivel_dependencia is not null and dj.tipo_puesto='SEC' then dj.id_subdependencia
													WHEN p.tipo_puesto='APO' and p.nivel_dependencia is null and dj.tipo_puesto is null then p.id_subdependencia
													WHEN p.tipo_puesto='APO' and dj.nivel_dependencia is not null and dj.tipo_puesto='PRI' then p.id_subdependencia
													WHEN p.tipo_puesto='APO' and dj.nivel_dependencia is not null and dj.tipo_puesto='SEC' then dj.id_subdependencia
											    end as id_subdependencia
											   ,case 
													WHEN p.tipo_puesto='PLA' and p.nivel_dependencia is null and dj.tipo_puesto is null then 'PRI'
													WHEN p.tipo_puesto='PLA' and dj.nivel_dependencia is not null and dj.tipo_puesto='PRI' then 'PRI'
													WHEN p.tipo_puesto='PLA' and dj.nivel_dependencia is not null and dj.tipo_puesto='SEC' then 'SEC'
													WHEN p.tipo_puesto='APO' and p.nivel_dependencia is null and dj.tipo_puesto is null then 'SEC'
													WHEN p.tipo_puesto='APO' and dj.nivel_dependencia is not null and dj.tipo_puesto='PRI' then 'PRI'
													WHEN p.tipo_puesto='APO' and dj.nivel_dependencia is not null and dj.tipo_puesto='SEC' then 'SEC'
											    end as nivel_puesto
											   ,dj.nivel_dependencia
											   ,p.tipo_puesto
											   ,dj.estado as depjerarquica_estado
										  from personal.funcionario f 
										  join personal.puesto_funcionario pf on f.id =pf.id_funcionario 
										  join entidad.puesto p on (pf.id_puesto=p.id)
										  join entidad.dependencia dep on (p.id_dependencia=dep.id) 
										  join administracion.dominios dom on (dom.valor1=dep.sede_trabajo)
									 left join entidad.dependencia_jerarquica dj on (pf.id_puesto=dj.id_puesto)
										 where dom.concepto ='SEDE TRABAJO' 
										   and pf.id_funcionario=".$id_funcionario
									  );
        return $query->result();   
	}

	function getAsignacionAccesoPorFuncionario($id_funcionario)
	{
		$query = $this->db_rrhh->query("select *
										from activo_informacion.autorizacion_funcionario
										where id_funcionario=".$id_funcionario." AND estado IN ('AC')
										order by fecha_asignacion DESC");
		return $query->result();   
	}

	function getRecursosPorFuncionarioGrupo($id_funcionario,$grupo_acceso)
	{
		$query = $this->db_rrhh->query("select *
										from activo_informacion.vista_autorizaciones_funcionarios
										where id_funcionario=".$id_funcionario." AND grupo_acceso IN ('".$grupo_acceso."')
										order by orden");
		return $query->result();   
	}

	function cargarFuncionariosPlantaPorDireccion($id_direccion)
	{
		$query = $this->db_rrhh->query("select * from (select DISTINCT ON (id) id, *
										  from personal.vista_puesto_funcionarios_activos 
										where estado='AC' and id_dependencia=".$id_direccion." and tipo_puesto='PLA') as tabla
										order by nombres,primer_apellido");
        return $query->result();
	}
	/*LISTA FUNCIONARIOS FICHAS PERSONAL */
	function getListaFuncionarioEstado($estado_actualizacion,$gestion)
    {
    	$query = $this->db_rrhh->query("select  f.*
											   ,af.id as id_actualizacion
											   ,af.id_funcionario
											   ,af.gestion
											   ,af.fecha_registro
											   ,af.fecha_finalizacion
											   ,af.fecha_rechazo
											   ,af.id_usuario
											   ,af.fecha_modificacion
											   ,af.estado as estado_actualizacion
									      from personal.funcionario as f
							   left outer join ficha_personal.actualizacion_ficha as af on f.id = af.id_funcionario
									     where f.estado = 'AC'  
										   and af.gestion=".$gestion."
										   and af.estado='".$estado_actualizacion."'									  
									     order by f.id asc");
        return $query->result();
    }
	function registroActualizacionFicha($data)
    {
        $this->db_rrhh->insert('ficha_personal.actualizacion_ficha',$data);
        return $this->db_rrhh->insert_id();
    }
	/*REGISTRO DE POAI POR FUNCIONARIO */
	function getFuncionarioPoaiGestion($tipo_puesto,$id_funcionario,$gestion)
	{
		$query = $this->db_rrhh->query("
										  select fa.*
												,ap.id as id_poai
												,ap.gestion
												,ap.nombre_archivo
												,ap.extension
												,ap.fecha_registro 
												,ap.fecha_cargado 
												,ap.justificativo 
												,ap.fecha_rechazo 
												,ap.estado as estado_poai
										   from personal.vista_puesto_funcionarios_activos fa
								left outer join ficha_personal.actualizacion_poai ap  on fa.id=ap.id_funcionario 
										  where fa.tipo_puesto = '".$tipo_puesto."' AND fa.estado IN ('AC')
											and fa.id = ".$id_funcionario."
											and ap.gestion=".$gestion."
									   order by numero_item
		
									  ");
        return $query->result();
	}
	function updateActualizacionPoai($id_registro,$data)
    {
        $this->db_rrhh->where('id',$id_registro);
        return $this->db_rrhh->update('ficha_personal.actualizacion_poai',$data);
    }
	function getFuncionariosPoaisGestion($tipo_puesto,$gestion,$estado_poai)
	{
		$query = $this->db_rrhh->query("
										  select  fa.*
												 ,ap.id as id_poai
												 ,ap.gestion
												 ,ap.nombre_archivo
												 ,ap.extension
												 ,ap.fecha_registro 
												 ,ap.fecha_cargado 
												 ,ap.justificativo 
												 ,ap.fecha_rechazo 
												 ,ap.estado as estado_poai
											from personal.vista_puesto_funcionarios_activos fa
									   left join ficha_personal.actualizacion_poai ap  on fa.id=ap.id_funcionario 
										   where fa.tipo_puesto = '".$tipo_puesto."' AND fa.estado IN ('AC')
											 and ap.gestion=".$gestion."
											 and ap.estado in (".$estado_poai.")
										order by numero_item
		
									  ");
        return $query->result();
	}
	/*REGISTRO DE EVALUACION DE DESEMPEÑO FUNCIONARIO */
	function getFuncionarioEvaluacionGestion($tipo_puesto,$id_funcionario,$gestion)
	{
		$query = $this->db_rrhh->query("
										  select fa.*
												,ed.id as id_evaluacion
												,ed.gestion
												,ed.nombre_archivo
												,ed.extension
												,ed.fecha_registro 
												,ed.fecha_cargado 
												,ed.justificativo 
												,ed.fecha_rechazo 
												,ed.estado as estado_evaluacion
												,ed.informe_archivo
												,ed.informe_extension
										  from personal.vista_puesto_funcionarios_activos fa
							   left outer join ficha_personal.evaluacion_desempenio ed  on fa.id=ed.id_funcionario and ed.estado not in('AN')
								 		 where fa.tipo_puesto = '".$tipo_puesto."' AND fa.estado IN ('AC')
										   and fa.id = ".$id_funcionario."
										   and ed.gestion=".$gestion."
									  order by numero_item
									  ");
        return $query->result();
	}
	function updateActualizacionEvaluacion($id_registro,$data)
    {
        $this->db_rrhh->where('id',$id_registro);
        return $this->db_rrhh->update('ficha_personal.evaluacion_desempenio',$data);
    }
	function getFuncionariosEvaluacionGestion($tipo_puesto,$gestion,$estado_evaluacion)
	{
		$query = $this->db_rrhh->query("
										  select  fa.*
												 ,ed.id as id_evaluacion
												 ,ed.gestion
												 ,ed.nombre_archivo
												 ,ed.extension
												 ,ed.fecha_registro 
												 ,ed.fecha_cargado 
												 ,ed.justificativo 
												 ,ed.fecha_rechazo 
												 ,ed.estado as estado_evaluacion
												 ,ed.informe_archivo
												 ,ed.informe_extension
											from personal.vista_puesto_funcionarios_activos fa
								 left outer join ficha_personal.evaluacion_desempenio ed  on fa.id=ed.id_funcionario 
										   where fa.tipo_puesto = '".$tipo_puesto."' AND fa.estado IN ('AC')
											 and ed.gestion=".$gestion."
											 and ed.estado in (".$estado_evaluacion.")
										order by numero_item
		
									  ");
        return $query->result();
	}
	function getEvaluacionDesempenioId($id_evaluacion)
	{
		$query = $this->db_rrhh->query("select * 
		  								  from ficha_personal.evaluacion_desempenio ed
										 where ed.id=".$id_evaluacion
									  );
        return $query->result();
	}

	function getfuncionariosHorarios($valores)
	{
		$query = $this->db_rrhh->query("select *
									      from personal.funcionario 
									     where estado = 'AC'
									      and id not in (".$valores.")									       
									     order by id asc");
        return $query->result();   
	}
	function getFuncionarioEvaluacion($id_funcionario,$gestion)
	{
		$query = $this->db_rrhh->query("select *
									      from ficha_personal.evaluacion_desempenio 
									     where id_funcionario = ".$id_funcionario."
									       and gestion = ".$gestion."									      
									       and estado != 'ANU'
									     order by id asc");
        return $query->result(); 
	}


	function getFuncionarioPoais($id_funcionario,$gestion)
	{
		$query = $this->db_rrhh->query("select *
									      from ficha_personal.actualizacion_poai 
									     where id_funcionario = ".$id_funcionario."
									       and gestion = ".$gestion."									      
									       and estado != 'ANU'
									     order by id asc");
        return $query->result(); 
	}
}

?>