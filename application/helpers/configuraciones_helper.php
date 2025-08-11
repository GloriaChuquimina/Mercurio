<?php
function gestion_vigente()
{
  $fila =& get_instance();
  $fila->load->model('Configuracion_detalles_model');
  $gestion = $fila->Configuracion_detalles_model->getGestionVigente();
  $valorgestion = $gestion[0]->gestion;
  return $valorgestion;
}
function getGestiones()
{
  $fila_m =& get_instance();
  $fila_m->load->model('Configuracion_detalles_model');

  $fila = $fila_m->Configuracion_detalles_model->getGestiones();
  $options = "";
  foreach ($fila as $filas)
    {
      $option.="<option value = '".$filas->gestion."'>".$filas->gestion."</option>";
    }
  return $option;
}

function datos_persona($id)
{
  $fila =& get_instance();
  $fila->load->model('Funcionarios_model');
  $datos = $fila->Funcionarios_model->datosPersonas($id);
  if($datos)
  {
    return $datos;
  }
  else
  {
    return "";
  }
}

 function nombre_estructura($iddireccion)
  {
    $fila = &get_instance();
    $fila->load->model('funcionarios_model');
    $persona = $fila->funcionarios_model->estructura_id($iddireccion);
    $nombre = $persona[0]->nombre_dependencia;
    return $nombre;
  }

function datos_persona_nombre($id)
{
  $fila =& get_instance();
  $fila->load->model('Funcionarios_model');
  if($id > 0)
  {
    $datos = $fila->Funcionarios_model->datosPersonas($id);
    if($datos)
    {
      return $datos[0]->nombres." ".$datos[0]->primer_apellido." ".$datos[0]->segundo_apellido ;
    }
    else
    {
      if($id == 100000)
      {
        return "PROCESO MASIVO";
      }
      else
      {
        return "";  
      }
      
    }
  }
  else
  {
    return "";
  }  
}



function datos_dominio($concepto)
{
  $fila =& get_instance();
  $fila->load->model('Configuracion_detalles_model');
  $datos = $fila->Configuracion_detalles_model->getValoresDominios($concepto);
  return $datos;
}

function datos_dominio_valor($concepto,$valor)
{
  $fila =& get_instance();
  $fila->load->model('Configuracion_detalles_model');
  $datos = $fila->Configuracion_detalles_model->getValoresDominiosValor($concepto,$valor);
  return $datos;
}

function getValor2Configuraciones($concepto, $valor)
{
    $fila_m =& get_instance();
    $fila_m->load->model('Configuracion_detalles_model');

    $fila = $fila_m->Configuracion_detalles_model->getValoresDominiosConcepto($concepto,$valor);
    $respuesta = "";
    if($fila)
    {
       $respuesta = $fila[0]->valor2;
    } 
    return $respuesta;
}

function datos_dominio_descripcion($descripcion)
{
  $fila =& get_instance();
  $fila->load->model('Configuracion_detalles_model');
  $datos = $fila->Configuracion_detalles_model->getValoresDominiosDireccion($descripcion);
  return $datos;
}
function sedeTrabajoFuncionario($id)
{
  $fila =& get_instance();
  $fila->load->model('Puestos_model');
  $datos = $fila->Puestos_model->listaPuestosTodos($id);
  if($datos)
  {
    return $datos[0]->sede_trabajo;
  }
  else
  {
    return "";
  }
}
function descripcion_estados($valor)
{
  $fila =& get_instance();
  $fila->load->model('Configuracion_detalles_model');
  $concepto = 'ESTADO REGISTRO';
  $datos = $fila->Configuracion_detalles_model->getValoresDominiosConcepto($concepto,$valor);
  if($datos)
  {
    return $datos[0]->valor2;
  }
  else
  {
    return "";
  }
}

function descripcionTodosEstados($concepto,$valor)
{
  $fila =& get_instance();
  $fila->load->model('Configuracion_detalles_model');  
  $datos = $fila->Configuracion_detalles_model->getValoresDominiosConcepto($concepto,$valor);
  if($datos)
  {
    return $datos[0]->valor2;
  }
  else
  {
    return "";
  }
}

function generoFuncionario($id)
{
    $fila =& get_instance();
    $fila->load->model('Funcionarios_model');
    $datos = $fila->Funcionarios_model->datosPersonas($id);
    $genero= $datos[0]->sexo;
    return $genero;
}

function listadoTipoPuesto()
{
  $fila =& get_instance();
  $fila->load->model('Configuracion_detalles_model');
  $concepto = 'TIPO PUESTO';
  $datos = $fila->Configuracion_detalles_model->getValoresDominios($concepto);
  
   return $datos;
}

function listadoJerarquia()
{
  $fila =& get_instance();
  $fila->load->model('Configuracion_detalles_model');
  $concepto = 'NIVEL JERARQUICO';
  $datos = $fila->Configuracion_detalles_model->getValoresDominios($concepto);
  
   return $datos;
}

function nivelGerarquico()
{
  $fila =& get_instance();
  $fila->load->model('Configuracion_detalles_model');
  $concepto = 'TIPO PUESTO';
  $datos = $fila->Configuracion_detalles_model->getValoresDominios($concepto);
  
   return $datos;

}
function mdate($datestr = '', $time = '')
{
  if ($datestr === '')
  {
    return '';
  }
  elseif (empty($time))
  {
    $time = now();
  }

  $datestr = str_replace(
    '%\\',
    '',
    preg_replace('/([a-z]+?){1}/i', '\\\\\\1', $datestr)
  );

  return date($datestr, $time);
}
function descripcionInstitucion($valor)
{
  $fila =& get_instance();
  $fila->load->model('Configuracion_detalles_model');
  $concepto = 'INSTITUCION';
  $datos = $fila->Configuracion_detalles_model->getValoresDominiosConcepto($concepto,$valor);
  if($datos)
  {
    return $datos[0]->valor2;
  }
  else
  {
    return "";
  }
}
function diferencia_dias($fechaUno, $fechaDos)
{
  $fecha1= new DateTime($fechaUno);
  $fecha2= new DateTime($fechaDos);
  $diff = $fecha1->diff($fecha2);
  // El resultados sera en dias
  return $diff->days;
}



function descripcionMes($valor)
{
  $fila =& get_instance();
  $fila->load->model('Configuracion_detalles_model');
  $concepto = 'MESES';
  $datos = $fila->Configuracion_detalles_model->getValoresDominiosConcepto($concepto,$valor);
  if($datos)
  {
    return $datos[0]->valor2;
  }
  else
  {
    return $valor;
  }
}

function getFechaHoraActual()
{
  $hoy = date("Y-m-d H:i:s"); //fecha de hoy
  return $hoy;
}

function getFechaActual()
{
  $hoy = date("Y-m-d"); //fecha de hoy
  return $hoy;
}

function getHoraActual()
{
  $hoy = date("H:i"); //fecha de hoy
  return $hoy;
}

function formato_fecha($fecha)
{
    if($fecha)
    {
      $timestamp = strtotime($fecha);
      return date('d-m-Y', $timestamp);
    }
    else
    {
      return "";
    }
}
function formato_fecha_slash($fecha)
{
    if($fecha)
    {
      $timestamp = strtotime($fecha);
      return date('d/m/Y', $timestamp);
    }
    else
    {
      return "";
    }
}

function formato_fecha_slash_invertido($fecha)
{
    if($fecha)
    {
      $timestamp = strtotime($fecha);
      return date('Y/m/d', $timestamp);
    }
    else
    {
      return "";
    }
}
function formato_fecha_slash_invertido2($fecha)
{
    if($fecha)
    {
      $timestamp = strtotime($fecha);
      return date('Y-m-d', $timestamp);
    }
    else
    {
      return "";
    }
}
function formato_fecha_hora($fecha)
{
    if($fecha)
    {
      $timestamp = strtotime($fecha);
      return date('d-m-Y H:i:s', $timestamp);
    }
    else
    {
      return "";
    }
}


function recuperarArchivo($id_registro)
{
  $fila =& get_instance();
  $fila->load->model('censo_model');
  $archivo = " SIN ARCHIVO";
  $datosCites = $fila->censo_model->getCensoId($id_registro);
  $remplace = array("uploads/cites/");
  $newTexto = array("/");

  if($datosCites)
  {
    
    $documento = $datosCites[0]->nombre_archivo;
    

    if(strlen($datosCites[0]->nombre_archivo) > 0)
    {
      $archivo = base_url()."upload/archivoscenso/".$documento;

      if($datosCites[0]->extension == 'pdf')
      {
        $archivo = "<a href=".$archivo." target='_blank'>
                <img src='" . base_url() . "resources/images/pdf-icon.jpg' width='32px' /></a>";  
      }
      if($datosCites[0]->extension == 'docx' || $datosCites[0]->extension == 'doc')
      {
        $archivo = "<a href=".$archivo." target='_blank'>
                <img src='" . base_url() . "resources/images/word.png' width='32px' /></a>";  
      }
      if($datosCites[0]->extension == 'xls')
      {
        $archivo = "<a href=".$archivo." target='_blank'>
                <img src='" . base_url() . "resources/images/excel.png' width='32px' /></a>";  
      }

    }
    
  }

  return $archivo;
}


function formato_fecha_dia($fecha)
{
    if($fecha)
    {
      $timestamp = strtotime($fecha);
      //$GetD = getdate();
      $verd = array(
                1=>"Lunes",2=>"Martes",3=>"Miércoles",4=>"Jueves",5=>"Viernes",6=>"Sábado",0=>"Domingo"
      );
      $verm = array(1=>"Enero",2=>"Febrero",3=>"Marzo",4=>"Abril",5=>"Mayo",6=>"Junio",7=>"Julio",
          8=>"Agosto",9=>"Septiembre",10=>"Octubre",11=>"Noviembre",12=>"Diciembre"
      );

      //return $verd[$GetD['wday']].", ".$GetD['mday']." de ".$verm[$GetD['mon']]." del ".$GetD['year'];
      return " ".$verd[date('w', $timestamp)]." ".date('d', $timestamp)." de ".$verm[(int)date('m', $timestamp)]." de ".date('Y', $timestamp);
    }
    else
    {
      return "";
    }
}
function formato_fecha_dia_2($fecha)
{
    if($fecha)
    {
      $timestamp = strtotime($fecha);
      //$GetD = getdate();
      $verd = array(
                1=>"Lunes",2=>"Martes",3=>"Miércoles",4=>"Jueves",5=>"Viernes",6=>"Sábado",0=>"Domingo"
      );
      $verm = array(1=>"Enero",2=>"Febrero",3=>"Marzo",4=>"Abril",5=>"Mayo",6=>"Junio",7=>"Julio",
          8=>"Agosto",9=>"Septiembre",10=>"Octubre",11=>"Noviembre",12=>"Diciembre"
      );

      //return $verd[$GetD['wday']].", ".$GetD['mday']." de ".$verm[$GetD['mon']]." del ".$GetD['year'];
      return date('d', $timestamp)." de ".$verm[(int)date('m', $timestamp)]." de ".date('Y', $timestamp);
    }
    else
    {
      return "";
    }
}

function formato_fecha_dia_hora($fecha)
{
    if($fecha)
    {
      $timestamp = strtotime($fecha);
      $verd = array(
                1=>"Lunes",2=>"Martes",3=>"Miércoles",4=>"Jueves",5=>"Viernes",6=>"Sábado",0=>"Domingo"
      );
      $verm = array(1=>"Enero",2=>"Febrero",3=>"Marzo",4=>"Abril",5=>"Mayo",6=>"Junio",7=>"Julio",
          8=>"Agosto",9=>"Septiembre",10=>"Octubre",11=>"Noviembre",12=>"Diciembre"
      );
      return " ".$verd[date('w', $timestamp)]." ".date('d', $timestamp)." de ".$verm[date('m', $timestamp)]." de ".date('Y', $timestamp)."  Hora:  ".date('H:i:s', $timestamp);
    }
    else
    {
      return "";
    }
}
function formato_fecha_dia_hora_meses_con_cero($fecha)
{
    if($fecha)
    {
      $timestamp = strtotime($fecha);
      $verd = array(
                1=>"Lunes",2=>"Martes",3=>"Miércoles",4=>"Jueves",5=>"Viernes",6=>"Sábado",0=>"Domingo"
      );
      $verm = array("01"=>"Enero","02"=>"Febrero","03"=>"Marzo","04"=>"Abril","05"=>"Mayo","06"=>"Junio","07"=>"Julio",
          "08"=>"Agosto","09"=>"Septiembre","10"=>"Octubre","11"=>"Noviembre","12"=>"Diciembre"
      );
      return $verd[date('w', $timestamp)]." ".date('d', $timestamp)." de ".$verm[date('m', $timestamp)]." de ".date('Y', $timestamp).", Hora: ".date('H:i:s', $timestamp);
    }
    else
    {
      return "";
    }
}
function calculo_dias_cas($dia,$mes,$anhio)
{
  if($anhio > 5)
  {
    if($anhio >= 10)
    {
      if($dia>=1)
      {
        return 30;
      }
      else
      {
        if($mes>=1)
        {
          return 30;
        }
        else
        {
          return 20;
        }
      }
    }
    else
    {
      return 20;
    }
  }
  else
  {
    if( $anhio == 5 )
    {
      if($dia >=1)
      {
        return 20;
      }
      else
      {
        if($mes>=1)
        {
          return 20;
        }
        else
        {
          return 15;
        }
      }
    }
    else
    {
      return 15;
    }
  }
}
function calculo_dias_caskdfs($id_funcionario)
{
    $fila =& get_instance();
    $fila->load->model('Registro_cas_model');
    $datos = $fila->Registro_cas_model->getCasFuncionario($id_funcionario);
    if($datos)
    {
        return $datos[0]->dias_vacacion;
    }
    else
    {
        return 15;
    }
}

function puesto_funcionario($id_puesto)
{
    $fila =& get_instance();
    $fila->load->model('Puestos_model');
    $datos = $fila->Puestos_model->getPuestoFuncionarioPlanta($id_puesto);
    return $datos;
}

//Cálculo de Expiración de contraseñas definido inicialmente en 3 meses
function calcularFechaExpiracionClave($fecha)
{
    if($fecha)
    {
        return date("Y-m-d",strtotime($fecha."+ 6 months"));
    }
    else
    {
      return "";
    }
}

function descripcionEstadoSalida($valor)
{
    $fila =& get_instance();
    $fila->load->model('Configuracion_detalles_model');
    $descripcion = 'ESTADO DEL PERMISO';
    $datos = $fila->Configuracion_detalles_model->getValoresDominiosDescripcion($descripcion,$valor);
    if($datos)
    {
        return $datos[0]->valor2;
    }
    else
    {
        return "";
    }
}
function descripcionTipoSalida($valor)
{
    $fila =& get_instance();
    $fila->load->model('Configuracion_detalles_model');
    $concepto = 'TIPO SALIDA';
    $datos = $fila->Configuracion_detalles_model->getValoresDominiosConcepto($concepto,$valor);
    if($datos)
    {
        return $datos[0]->valor2;
    }
    else
    {
        return "";
    }
}
function descripcionPermisoDominio($valor)
{
  $fila =& get_instance();
  $fila->load->model('Configuracion_detalles_model');
  $datos = $fila->Configuracion_detalles_model->getValoresPermisosDominiosConcepto($valor);
  if($datos)
  {
    return $datos[0]->descripcion;
  }
  else
  {
    return "";
  }
}
function descripcionAbreviaturaDominio($valor)
{
  $fila =& get_instance();
  $fila->load->model('Configuracion_detalles_model');
  $datos = $fila->Configuracion_detalles_model->getValoresPermisosDominiosConcepto($valor);
  if($datos)
  {
    return $datos[0]->abreviatura;
  }
  else
  {
    return "";
  }
}
function cantidadHijos($id_funcionario)
{
  $fila =& get_instance();
  $fila->load->model('Configuracion_detalles_model');
  $datos = $fila->Configuracion_detalles_model->getHijosFuncionarios($id_funcionario);

    return $datos;
}

function formato_fecha_con_dia($fecha)
{
    if($fecha)
    {
      $timestamp = strtotime($fecha);
      //$GetD = getdate();
      $verd = array(
                1=>"Lunes",2=>"Martes",3=>"Miércoles",4=>"Jueves",5=>"Viernes",6=>"Sábado",0=>"Domingo"
      );
      //return $verd[$GetD['wday']].", ".$GetD['mday']." de ".$verm[$GetD['mon']]." del ".$GetD['year'];
      return " ".$verd[date('w', $timestamp)]."<br>".date('d/m/Y', $timestamp);
    }
    else
    {
      return "";
    }
}

function sumar_dias_calendario($fecha_inicio,$dias)
{
    $fecha = $fecha_inicio;
    $lista_dias=$fecha_inicio.'|';
    for ($i=0; $i<($dias-1); $i++)
    {
        $fecha = strtotime ('+1 day',strtotime($fecha)) ;
        $fecha = date('Y-m-d',$fecha);
        $lista_dias.=$fecha.'|';
    }
    $lista_dias = substr($lista_dias,0,-1);
    return array($fecha,$lista_dias);
}

function sumar_dias_habiles($fecha_inicio,$MaxDias,$gestion,$sede)
{
    $fecha = $fecha_inicio;
    $lista_dias=$fecha.'|';
    $i=1;
    while($i<=($MaxDias-1))
    {
        $fecha = strtotime ('+1 day',strtotime($fecha));
        $fecha_formato = $fecha;
        $fecha = date('Y-m-d',$fecha);
        $dia = date('D',$fecha_formato);

        if(($dia=='Sat')||($dia=='Sun')||(verificar_feriados($fecha,$gestion,$sede))) {
            //No hace nada
        } else {
            $FechaFinal = $fecha;
            $i++;
            $lista_dias.=$fecha.'|';
        }
    }
    $lista_dias = substr($lista_dias,0,-1);
    return array($FechaFinal,$lista_dias);
}

function devolver_dias_habiles($dia_inicio,$dia_fin,$gestion,$sede)
{
    $fecha = $dia_inicio;

    $lista_dias=$fecha.'|';
    $i=1;
    while($fecha!=$dia_fin)
    {
        $fecha = strtotime ('+1 day',strtotime($fecha));
        $fecha_formato = $fecha;
        $fecha = date('Y-m-d',$fecha);
        $dia = date('D',$fecha_formato);

        if(($dia=='Sat')||($dia=='Sun')||(verificar_feriados($fecha,$gestion,$sede))) {
            //No hace nada
        } else {
            $FechaFinal = $fecha;
            $i++;
            $lista_dias.=$fecha.'|';
        }
    }
    $lista_dias = substr($lista_dias,0,-1);
    return array($lista_dias,$i);
}

function getDireccionFuncionario($id_funcionario){
    $fila =& get_instance();
    $fila->load->model('Dependencia_model');
    $dependencia = $fila->Dependencia_model->getDependenciaFuncionario($id_funcionario);
    $id_dependencia = $dependencia[0]->id_dependencia;
    return $id_dependencia;
}

function getUnidadFuncionario($id_funcionario){
    $fila =& get_instance();
    $fila->load->model('SubDependencia_model');
    $subdependencia = $fila->SubDependencia_model->getSubDependenciaFuncionario($id_funcionario);
    if($subdependencia){
        $id_subdependencia=$subdependencia[0]->id_subdependencia;
    } else {
        $id_subdependencia='';
    }
    
    return $id_subdependencia;
}

function listadoExpedicionCI()
{
  $fila =& get_instance();
  $fila->load->model('Configuracion_detalles_model');
  $concepto = 'EXPEDICION CI';
  $datos = $fila->Configuracion_detalles_model->getValoresDominios($concepto);
  
   return $datos;
}

function extraerPrimeraPalabra($cadena)
{
  $cadena = trim($cadena);
  $lista = explode(" ",$cadena);
  if(strlen($lista[0])>2){
    $resultado = $lista[0];
  } else if(strlen($lista[1])>2){
    $resultado = $lista[1];
  } else if(strlen($lista[2])>2){
    $resultado = $lista[2];
  }
  $resultado = eliminar_acentos($resultado);
  return strtolower($resultado);
}

function eliminar_acentos($cadena)
{
    
    //Reemplazamos la A y a
    $cadena = str_replace(
    array('Á', 'À', 'Â', 'Ä', 'á', 'à', 'ä', 'â', 'ª'),
    array('A', 'A', 'A', 'A', 'a', 'a', 'a', 'a', 'a'),
    $cadena
    );

    //Reemplazamos la E y e
    $cadena = str_replace(
    array('É', 'È', 'Ê', 'Ë', 'é', 'è', 'ë', 'ê'),
    array('E', 'E', 'E', 'E', 'e', 'e', 'e', 'e'),
    $cadena );

    //Reemplazamos la I y i
    $cadena = str_replace(
    array('Í', 'Ì', 'Ï', 'Î', 'í', 'ì', 'ï', 'î'),
    array('I', 'I', 'I', 'I', 'i', 'i', 'i', 'i'),
    $cadena );

    //Reemplazamos la O y o
    $cadena = str_replace(
    array('Ó', 'Ò', 'Ö', 'Ô', 'ó', 'ò', 'ö', 'ô'),
    array('O', 'O', 'O', 'O', 'o', 'o', 'o', 'o'),
    $cadena );

    //Reemplazamos la U y u
    $cadena = str_replace(
    array('Ú', 'Ù', 'Û', 'Ü', 'ú', 'ù', 'ü', 'û'),
    array('U', 'U', 'U', 'U', 'u', 'u', 'u', 'u'),
    $cadena );

    //Reemplazamos la N, n, C y c
    $cadena = str_replace(
    array('Ñ', 'ñ', 'Ç', 'ç'),
    array('N', 'n', 'C', 'c'),
    $cadena
    );
    
    return $cadena;
}

function cargarMenu($id_usuario,$id_puesto,$tipo_puesto,$nivel_dependencia,$id_aplicacion)
{
  //var_dump($id_usuario." - ".$id_puesto." - ".$tipo_puesto." - ".$nivel_dependencia." - ".$id_aplicacion); die();
  $fila =& get_instance();
  $fila->load->model('roles_model');

  if($tipo_puesto=='CON') $descripcion='CONSULTOR';
  else $descripcion='OPERADOR';
  
  if($tipo_puesto=='CON'){
    $rol = $fila->roles_model->obtenerRolporTipoPuesto($descripcion,$id_aplicacion);
    $id_rol = $rol[0]->id;
  } else {
    $datos = $fila->roles_model->obtenerRolporPuesto($id_puesto,$id_aplicacion);
    //echo '<pre>'; var_dump($datos); echo '</pre>';
    
    if($datos){
      $id_rol = $datos[0]->id;
    } else {
      if($nivel_dependencia!=''){
        $datos = $fila->roles_model->obtenerRolporNivelDependencia($nivel_dependencia,$id_aplicacion);
        $id_rol = $datos[0]->id;
      } else {
        $datos = $fila->roles_model->obtenerRolOperador($id_aplicacion);
        $id_rol = $datos[0]->id;
      }
    }
  }
  
  $opciones = $fila->roles_model->obtenerOpcionesPorRol($id_rol);
  //echo '<pre>'; var_dump($opciones); echo '</pre>';

  foreach ($opciones as $opcion)
  {
    if(!$fila->roles_model->check_opciones($opcion->id_opcion,$id_usuario))
    {
      $data = array(
        'id_opcion' => $opcion->id_opcion,
        'id_usuario' => $id_usuario,
        'id_aplicacion' => $id_aplicacion
      );
      //echo '<pre>'; var_dump($data); echo '</pre>';
      $insertar = $fila->roles_model->guardarOpcionesRol($data);
    }
  }
  return 1;
}

/*VALORES DOMINIO FICHAS PERSONAL*/
function descripcionLugarNacimiento($valor)
{
  $fila =& get_instance();
  $fila->load->model('Configuracion_detalles_model');
  $concepto='LUGAR NACIMIENTO';
  $datos = $fila->Configuracion_detalles_model->getValoresDominiosConcepto($concepto,$valor);
  if($datos)
  {
    return $datos[0]->valor2;
  }
  else
  {
    return "";
  }
}
function descripcionEstadoCivil($valor)
{
  $fila =& get_instance();
  $fila->load->model('Configuracion_detalles_model');
  $concepto='ESTADO CIVIL';
  $datos = $fila->Configuracion_detalles_model->getValoresDominiosConcepto($concepto,$valor);
  if($datos)
  {
    return $datos[0]->valor2;
  }
  else
  {
    return "";
  }
}
function descripcionSexo($valor)
{
  $fila =& get_instance();
  $fila->load->model('Configuracion_detalles_model');
  $concepto='SEXO';
  $datos = $fila->Configuracion_detalles_model->getValoresDominiosConcepto($concepto,$valor);
  if($datos)
  {
    return $datos[0]->valor2;
  }
  else
  {
    return "";
  }
}
function descripcionFondoPensiones($valor)
{
  $fila =& get_instance();
  $fila->load->model('Configuracion_detalles_model');
  $concepto='TIPO AFFP';
  $datos = $fila->Configuracion_detalles_model->getValoresDominiosConcepto($concepto,$valor);
  if($datos)
  {
    return $datos[0]->valor2;
  }
  else
  {
    return "";
  }
}
function descripcionTipoBanco($valor)
{
  $fila =& get_instance();
  $fila->load->model('Configuracion_detalles_model');
  $concepto='TIPO BANCO';
  $datos = $fila->Configuracion_detalles_model->getValoresDominiosConcepto($concepto,$valor);
  if($datos)
  {
    return $datos[0]->valor2;
  }
  else
  {
    return "";
  }
}

function formaterarValidacion($mensajeValidacion)
{    
    $nuevoMensaje      = str_replace("{", "", $mensajeValidacion);
    $nuevoMensaje      = str_replace("}", "", $nuevoMensaje);
    $nuevoMensaje      = str_replace('"', "", $nuevoMensaje);
    return $nuevoMensaje;
}




function formato_fecha_dia_minuscula_sin_dia($fecha)
{
    if($fecha)
    {
      $timestamp = strtotime($fecha);
      //$GetD = getdate();
      $verd = array(
                1=>"lunes",2=>"martes",3=>"miércoles",4=>"jueves",5=>"viernes",6=>"sábado",0=>"domingo"
      );
      $verm = array(1=>"enero",2=>"febrero",3=>"marzo",4=>"abril",5=>"mayo",6=>"junio",7=>"julio",
          8=>"agosto",9=>"septiembre",10=>"octubre",11=>"noviembre",12=>"diciembre"
      );

      //return $verd[$GetD['wday']].", ".$GetD['mday']." de ".$verm[$GetD['mon']]." del ".$GetD['year'];
      return " ".date('d', $timestamp)." de ".$verm[(int)date('m', $timestamp)]." de ".date('Y', $timestamp);
    }
    else
    {
      return "";
    }
}
/*SISTEMAS MERCURIO*/
function getCuenta($id_cuenta)
{
    $fila_m =& get_instance();
    $fila_m->load->model('PlanDeCuentas_model');

    $fila = $fila_m->PlanDeCuentas_model->getPlanDeCuentasById($id_cuenta);
    $respuesta = "";
    if($fila)
    {
       $respuesta = $fila[0]->descripcion;
    } 
    return $respuesta;
}
function getCodigoCuenta($id_cuenta)
{
    $fila_m =& get_instance();
    $fila_m->load->model('PlanDeCuentas_model');

    $fila = $fila_m->PlanDeCuentas_model->getPlanDeCuentasById($id_cuenta);
    $respuesta = "";
    if($fila)
    {
       $respuesta = $fila[0]->codigo;
    } 
    return $respuesta;
}
function descripcion_nombre_entidad($id_entidad)
{
  $fila =& get_instance();
  $fila->load->model('Entidades_model');
  $datos = $fila->Entidades_model->getEntidadesById($id_entidad);
  if($datos)
  {
    return $datos[0]->nombre;
  }
  else
  {
    return '';
  }
}
function sigla_entidad($id_entidad)
{
  $fila =& get_instance();
  $fila->load->model('Entidades_model');
  $datos = $fila->Entidades_model->getEntidadesById($id_entidad);
  if($datos)
  {
    return $datos[0]->sigla;
  }
  else
  {
    return '';
  }
}


function getTipoCambio($fecha)
{
  $fila =& get_instance();
  $fila->load->model('TipoCambio_model');
  $datos = $fila->TipoCambio_model->getTipoCambioFecha($fecha);
  $valor=0.00;
  if($datos)
  {
    return $datos[0]->valor;
  }
  else
  {
    return $valor;
  }
}
function getNivelMaximo()
{
  $fila =& get_instance();
  $fila->load->model('PlanDeCuentas_model');
  $datos = $fila->PlanDeCuentas_model->getPlanDeCuentasByNivelMaximo();
  $valor=0.00;
  if($datos)
  {
    return $datos[0]->nivel;
  }
  else
  {
    return $valor;
  }
}


?>