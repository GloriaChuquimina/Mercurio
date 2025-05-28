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

function datos_dominio_descripcion($descripcion)
{
  $fila =& get_instance();
  $fila->load->model('Configuracion_detalles_model');
  $datos = $fila->Configuracion_detalles_model->getValoresDominiosDireccion($descripcion);
  return $datos;
}



function verificar_feriados($fecha,$gestion,$sede)
{   
  $fila =& get_instance();
  $fila->load->model('dias_no_habiles_model');
  $datos = $fila->dias_no_habiles_model->getfechaverificar($fecha,$gestion);
  if($datos)
  {
    if($datos[0]->alcance == 'NACIONAL')
    {
      return true;
    }
    elseif($datos[0]->departamento == $sede) 
    {
      return true;
    } 
    else
    {
      return false;
    }
  }
  else
  {
    return false;
  }
}

function descripcionFeriados($fecha,$gestion,$sede)
{   
  $fila =& get_instance();
  $fila->load->model('dias_no_habiles_model');
  $datos = $fila->dias_no_habiles_model->getfechaverificar($fecha,$gestion);
  if($datos)
  {
    if($datos[0]->alcance == 'NACIONAL')
    {
      return $datos[0]->descripcion;
    }
    elseif($datos[0]->departamento == $sede)
    {
      return $datos[0]->descripcion;
    } 
    else
    {
      return "---";
    }
  }
  else
  {
    return "---";
  }
}


function verificar_feriados_asistencia($fecha,$gestion,$sede)
{   
  $fila =& get_instance();
  $fila->load->model('dias_no_habiles_model');
  $datos = $fila->dias_no_habiles_model->getfechaverificar($fecha,$gestion);

  if($datos)
  {
    if($datos[0]->alcance == 'NACIONAL')
    {
      return $datos[0]->descripcion;
    }
    elseif($datos[0]->departamento == $sede)
    {
      return $datos[0]->descripcion;
    } 
    else
    {
      return "---";
    }
  }
  else
  {
    return "---";
  }
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

function descripcionVacacion($valor)
{
  $fila =& get_instance();
  $fila->load->model('Configuracion_detalles_model');
  $concepto = 'TIPO DIA VACACION';
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
function descripcionTipoPermiso($valor)
{
  $fila =& get_instance();
  $fila->load->model('Configuracion_detalles_model');
  $concepto = 'TIPO PERMISO';
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

function valorTipoPermiso($valor)
{
  $fila =& get_instance();
  $fila->load->model('Configuracion_detalles_model');
  $concepto = 'VALOR DE PERMISO';
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
function descripcionEstadoPermiso($valor)
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
function descripcionTurnoPermiso($valor)
{
  $fila =& get_instance();
  $fila->load->model('Configuracion_detalles_model');
  $concepto = 'TURNO PERMISO';
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
function descripciondiaVacacion($valor)
{
  $fila =& get_instance();
  $fila->load->model('Configuracion_detalles_model');
  $concepto = 'TURNO DIA VACACION';
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
function descripcionTurnoVacacion($valor)
{
  $fila =& get_instance();
  $fila->load->model('Configuracion_detalles_model');
  $concepto = 'TURNO VACACION';
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
function descripcionTurnoBoleta($valor)
{
  $fila =& get_instance();
  $fila->load->model('Configuracion_detalles_model');
  $concepto = 'TURNO BOLETAS';
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
function descripcionGradoFormacion($valor)
{
  $fila =& get_instance();
  $fila->load->model('Configuracion_detalles_model');
  $concepto = 'GRADO FORMACION';
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
function descripcionOmisionMarcado($valor)
{
  $fila =& get_instance();
  $fila->load->model('Configuracion_detalles_model');
  $concepto = 'OMISIONES MARCADOS';
  $datos = $fila->Configuracion_detalles_model->getValoresDominiosConcepto($concepto,$valor);
  if($datos)
  {
    return $datos[0]->valor2."<br>";
  }
  else
  {
    return "";
  }
}

function descripcionOmisionMarcadoTablas($valor)
{
  $fila =& get_instance();
  $fila->load->model('Configuracion_detalles_model');
  $concepto = 'OMISIONES MARCADOS';
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

function sigla_estrucctura($iddireccion)
  {
    $fila = &get_instance();
    $fila->load->model('funcionarios_model');
    $persona = $fila->funcionarios_model->estructura_id($iddireccion);
    $nombre = $persona[0]->sigla_dependencia;
    return $nombre;
  }

function descripcionMarcadosHorario($valor)
{
  $fila =& get_instance();
  $fila->load->model('Configuracion_detalles_model');
  $concepto = 'TIPO MARCADOS';
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
function descripcionDatosLaboralesTipo($valor)
{
  $fila =& get_instance();
  $fila->load->model('Configuracion_detalles_model');
  $concepto = 'TIPO TRABAJO';
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
function descripcionMotivoDesvinculacion($valor)
{
  $fila =& get_instance();
  $fila->load->model('Configuracion_detalles_model');
  $concepto = 'MOTIVO DESVINCULACION';
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
function descripcionTipoCurso($valor)
{
  $fila =& get_instance();
  $fila->load->model('Configuracion_detalles_model');
  $concepto = 'CURSO';
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


function validarInstrucciones($id_funcionario)
{
  $resultado = "SI";
  $fila =& get_instance();
  $fila->load->model('configuracion_detalles_model');

  $fila2 =& get_instance();
  $fila2->load->model('funcionarios_model');
  $concepto = 'INFORME DE ACTIVIDADES GESTION';
  $concepto2 = 'EVALUACION DE DESEMPENIO GESTION';
  $concepto3 = 'REGISTRO DE POAI GESTION';
  $fechaActual = getFechaActual();
  $gestion = gestion_vigente();

  $datosEvaluacion = $fila2->funcionarios_model->getFuncionarioEvaluacion($id_funcionario,$gestion);
  if($datosEvaluacion)
  {
    $estadoRegistro = $datosEvaluacion[0]->estado;
    $datos = $fila->configuracion_detalles_model->getConfiguracionDetallesGestion($gestion,$concepto);
    if($datos)
    {
      $fechaLimite = $datos[0]->valor2;
      if($fechaActual > $fechaLimite)
      {                
        if($estadoRegistro == "PEN")
        {
          $resultado = "INFORME DE ACTIVIDADES PENDIENTE REGISTRO EN EL SISTEMA, CONSULTE CON RRHH.";  
        }
        if($estadoRegistro == "RC")
        {
          $resultado = "OBSERVACIONES EN SU INFORME DE ACTIVIDADES O DE EVALUCIÓN DE DESEMPEÑO RECHAZADO, ACTUALICE SU INFORMACIÓN, CONSULTE CON RRHH.";  
        }        
      }
    }  
    if ($resultado == "SI")
    {      
      $datos2 = $fila->configuracion_detalles_model->getConfiguracionDetallesGestion($gestion,$concepto2);
      if($datos2)
      {
        $fechaLimite = $datos2[0]->valor2;
        if($fechaActual > $fechaLimite)
        {                
          if($estadoRegistro == "INC")
          {
            $resultado = "LA EVALUACIÓN DE DESEMPEÑO SE ENCUENTRA PENDIENTE REGISTRO EN EL SISTEMA, CONSULTE CON RRHH.";  
          }                  
        }
      } 
    }
  }

  if ($resultado == "SI")
  {      
   
    $datosPoai = $fila2->funcionarios_model->getFuncionarioPoais($id_funcionario,$gestion);
    if($datosPoai)
    {
      $estadoRegistro2 = $datosPoai[0]->estado;    
      $datos2 = $fila->configuracion_detalles_model->getConfiguracionDetallesGestion($gestion,$concepto3);
      if($datos2)
      {
        $fechaLimite = $datos2[0]->valor2;
        if($fechaActual > $fechaLimite)
        {                
          if($estadoRegistro2 == "PEN")
          {
            $resultado = "EL REGISTRO DEL POAI SE ENCUENTRA PENDIENTE DE REGISTRO EN EL SISTEMA, CONSULTE CON RRHH.";
          }                  
        }
      }  
    }     
  }
  
  return $resultado;
}


function diferencia_dias($fechaUno, $fechaDos)
{
  $fecha1= new DateTime($fechaUno);
  $fecha2= new DateTime($fechaDos);
  $diff = $fecha1->diff($fecha2);
  // El resultados sera en dias
  return $diff->days;
}

function verificar_dia_progamado($fecha,$id_funcionario,$turno)
{
  $fila =& get_instance();
  $fila->load->model('vacaciones_model');
  $datos = $fila->vacaciones_model->ckeck_fechavacacion($fecha,$id_funcionario,$turno);
  if($datos)
  {
    return true;
  }
  else
  {
    return false;
  }
}

function verificar_dia_progamado_completo($fecha, $id_funcionario)
{
  $fila =& get_instance();
  $fila->load->model('vacaciones_model');
  $datos = $fila->vacaciones_model->ckeck_fechavacacion_completo($fecha,$id_funcionario);
  if($datos)
  {
    return true;
  }
  else
  {
    return false;
  }
}
function verificar_dia_progamado_Rango($fecha,$id_funcionario,$id_rango)
{
  $fila =& get_instance();
  $fila->load->model('vacaciones_model');
  $datos = $fila->vacaciones_model->ckeck_fechavacacionRango($fecha,$id_funcionario,$id_rango);
  if($datos)
  {
    return true;
  }
  else
  {
    return false;
  }
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
/*FECHA ULTIMA ACTUALIZACION DE FICHA PERSONAL */
function getFechaUltimaActualizacionFicha($id_funcionario)
{
  $fila =& get_instance();
  $fila->load->model('Ficha_model');
  $datos = $fila->Ficha_model->getFechaUltimaActualizacionFicha($id_funcionario);
  if($datos)
  {
    return $datos[0]->fecha_maxima;
  }
  else
  {
    return "";
  }
}
function getEstadoActualizacionFicha($id_funcionario,$gestion)
{
  $fila =& get_instance();
  $fila->load->model('Ficha_model');
  $datos = $fila->Ficha_model->getDatosEstadoActualizacionFichaPersonal($id_funcionario,$gestion);
  if($datos)
  {
    return $datos[0]->estado;
  }
  else
  {
    return "";
  }
}
/*FECHA ULTIMA ACTUALIZACION DE FICHA PERSONAL */

function cargarPerfilSiscor($id_funcionario,$id_puesto,$id_puesto_funcionario,$fechaAlta,$fechaBaja)
{
  $fila =& get_instance();
  $fila->load->model('puestos_model');
  $fila2 =& get_instance();
  $fila2->load->model('perfiles_model');
  $fila3 =& get_instance();
  $fila3->load->model('funcionarios_model');

  $registro_puesto = $fila->puestos_model->getPuesto($id_puesto);
  if($fechaBaja==''){
    $fechaBaja=date('Y').'-12-31';
  }
  if(is_null($registro_puesto[0]->id_subdependencia)) {
    $subDependencia=0;
  } else {
    $subDependencia=$registro_puesto[0]->id_subdependencia;
  }

  if($id_puesto_funcionario==0){
    $puesto_funcionario = $fila->puestos_model->getDatosPuestoFuncionarioUltimoAsignado($id_funcionario,$id_puesto);
    $insert_puesto_funcionario = $puesto_funcionario[0]->id;
  } else {
    $insert_puesto_funcionario = $id_puesto_funcionario;
  }

  $datos_persona = $fila3->funcionarios_model->datosPersonas($id_funcionario);
  
  //Crear registro de Perfil de usuario en SISOR
  $perfil_siscor = array (
    'id_puesto_funcionario'     => $insert_puesto_funcionario,
    'id_funcionario'            => $id_funcionario,
    'id_dependencia'            => $registro_puesto[0]->id_dependencia,
    'id_subdependencia'         => $subDependencia,
    'id_cargo'                  => $registro_puesto[0]->id_cargo,
    'id_puesto'                 => $id_puesto,
    'descripcion_cargo_sigap'   => $registro_puesto[0]->nombre_puesto,
    'fecha_inicio'              => $fechaAlta,
    'fecha_fin'                 => $fechaBaja,
    'id_funcionario_sicenad'    => 0,
    'numero_documento'          => $datos_persona[0]->numero_documento,
    // 'orden_defecto'             => 1,
    'descripcion_nombre_perfil' => $datos_persona[0]->nombres." ".$datos_persona[0]->primer_apellido." ".$datos_persona[0]->segundo_apellido,
    'descripcion_cargo_perfil'  => $registro_puesto[0]->nombre_puesto,
    'estado'                    => 'ACT'
  );
  //echo '<pre>'; var_dump($perfil_siscor); echo '</pre>'; die();
  
  $insert_perfil_usuario = $fila2->perfiles_model->guardarPerfilesUsuarios($perfil_siscor);
  if($insert_perfil_usuario){
    $correlativo_perfil = array (
      'id_perfil'   => $insert_perfil_usuario,
      'id_correlativo'=> 2,
      'estado'    => 'ACT'
    );
    $insert_correlativo_perfil = $fila2->perfiles_model->guardarCorrelativoPerfil($correlativo_perfil);

    $correlativo_perfil = array (
      'id_perfil'   => $insert_perfil_usuario,
      'id_correlativo'=> 4,
      'estado'    => 'ACT'
    );
    $insert_correlativo_perfil = $fila2->perfiles_model->guardarCorrelativoPerfil($correlativo_perfil);
    return 1;
  } else {
    return 0;
  }
}

function cargarActivosInformacionInicial($id_funcionario,$id_usuario_administrador)
{
  $fila =& get_instance();
  $fila->load->model('activoInformacion_model');

  $activosInicial = $fila->activoInformacion_model->getAutorizacionNivelAcceso('BAS');
  //echo '<pre>'; var_dump($activosInicial); echo '</pre>'; exit();
  
  foreach($activosInicial as $filas){
    $activos_recursos = array (
      'id_funcionario'           => (int)$id_funcionario,
      'id_autorizacion_dominio' => (int)$filas->id,
      'estado_habilitacion'      => $filas->estado_defecto,
      'fecha_alta' => getFechaHoraActual(),
      'estado'        => 'AC',
      'id_usuario_habilitacion' => $id_usuario_administrador
    );
    //echo '<pre>'; var_dump($activos_recursos); echo '</pre>';
    $insert_activos_inicial = $fila->activoInformacion_model->guardarAsignacionActivoInformacion($activos_recursos);
  }
  
  $activos_funcionario = array (
      'id_funcionario'  => $id_funcionario,
      'estado_acceso'   => 'ALT',
      'fecha_asignacion'=> getFechaHoraActual(),
      'estado'          => 'AC',
      'id_usuario_habilitacion' => $id_usuario_administrador
  );
  //echo '<pre>'; var_dump($activos_funcionario); echo '</pre>';
  $insert_activos_funcionario = $fila->activoInformacion_model->guardarAsignacionActivoFuncionario($activos_funcionario);
  
  if($insert_activos_funcionario){
    return 1;
  } else {
    return 0;
  }

}

function formaterarValidacion($mensajeValidacion)
{    
    $nuevoMensaje      = str_replace("{", "", $mensajeValidacion);
    $nuevoMensaje      = str_replace("}", "", $nuevoMensaje);
    $nuevoMensaje      = str_replace('"', "", $nuevoMensaje);
    return $nuevoMensaje;
}

function bajaPerfilSiscor($id_puesto_funcionario,$fechaBaja)
{
  $fila2 =& get_instance();
  $fila2->load->model('perfiles_model');
  //Actualizar registro de Perfil de usuario en SISCOR para baja
  $data_siscor = array (
    'fecha_fin'         => $fechaBaja,
    'fecha_modificacion'=> getFechaHoraActual(),
    'estado'            => 'HI'
  );
  
  $update_perfil_usuario = $fila2->perfiles_model->actualizarPerfilesUsuarioPorPuestoFuncionario($id_puesto_funcionario,$data_siscor);
  if($update_perfil_usuario){
    return 1;
  } else {
    return 0;
  }
}

function cambiarPerfilSiscor($id_funcionario,$id_puesto,$id_puesto_funcionario,$fechaAlta,$fechaBaja,$id_puesto_funcionario_anterior)
{
  $fila =& get_instance();
  $fila->load->model('puestos_model');
  $fila2 =& get_instance();
  $fila2->load->model('perfiles_model');
  $fila3 =& get_instance();
  $fila3->load->model('funcionarios_model');

  //******* BAJA DEL PERFIL SISCOR del antiguo puesto
  $baja_siscor = array (
    'fecha_fin'         => $fechaBaja,
    'fecha_modificacion'=> getFechaHoraActual(),
    'estado'            => 'HI'
  );
  $update_perfil_usuario = $fila2->perfiles_model->actualizarPerfilesUsuarioPorPuestoFuncionario($id_puesto_funcionario_anterior,$baja_siscor);

  //******* REGISTRAR NUEVO SISCOR
  $registro_puesto = $fila->puestos_model->getPuesto($id_puesto);

  $fechaBajaGestion=date('Y').'-12-31';
  
  if(is_null($registro_puesto[0]->id_subdependencia)) {
    $subDependencia=0;
  } else {
    $subDependencia=$registro_puesto[0]->id_subdependencia;
  }

  if($id_puesto_funcionario==0){
    $puesto_funcionario = $fila->puestos_model->getDatosPuestoFuncionarioUltimoAsignado($id_funcionario,$id_puesto);
    $insert_puesto_funcionario = $puesto_funcionario[0]->id;
  } else {
    $insert_puesto_funcionario = $id_puesto_funcionario;
  }

  $datos_persona = $fila3->funcionarios_model->datosPersonas($id_funcionario);
  
  //Crear registro de Perfil de usuario en SISOR
  $perfil_siscor = array (
    'id_puesto_funcionario' => $insert_puesto_funcionario,
    'id_funcionario'    => $id_funcionario,
    'id_dependencia'    => $registro_puesto[0]->id_dependencia,
    'id_subdependencia'   => $subDependencia,
    'id_cargo'        => $registro_puesto[0]->id_cargo,
    'id_puesto'       => $id_puesto,
    'descripcion_cargo_sigap' => $registro_puesto[0]->nombre_puesto,
    'fecha_inicio'      => $fechaAlta,
    'fecha_fin'       => $fechaBajaGestion,
    'id_funcionario_sicenad'=> 0,
    'numero_documento'    => $datos_persona[0]->numero_documento,
    'descripcion_nombre_perfil' => $datos_persona[0]->nombres." ".$datos_persona[0]->primer_apellido." ".$datos_persona[0]->segundo_apellido,
    'descripcion_cargo_perfil' => $registro_puesto[0]->nombre_puesto,
    'estado'        => 'ACT'
  );
  //echo '<pre>'; var_dump($perfil_siscor); echo '</pre>'; die();
  
  $insert_perfil_usuario = $fila2->perfiles_model->guardarPerfilesUsuarios($perfil_siscor);
  if($insert_perfil_usuario){
    $correlativo_perfil = array (
      'id_perfil'   => $insert_perfil_usuario,
      'id_correlativo'=> 2,
      'estado'    => 'ACT'
    );
    $insert_correlativo_perfil = $fila2->perfiles_model->guardarCorrelativoPerfil($correlativo_perfil);

    $correlativo_perfil = array (
      'id_perfil'   => $insert_perfil_usuario,
      'id_correlativo'=> 4,
      'estado'    => 'ACT'
    );
    $insert_correlativo_perfil = $fila2->perfiles_model->guardarCorrelativoPerfil($correlativo_perfil);
    return 1;
  } else {
    return 0;
  }
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
/*VALORES COMBO ESTADO REGISTRO ACTUALIZACION - STP*/
function getValoresDominiosCombos1($concepto)
{
  $fila_m =& get_instance();
  $fila_m->load->model('Configuracion_detalles_model');
  $opcion='';
  $fila = $fila_m->Configuracion_detalles_model->getValoresDominiosCombos($concepto);
  // $options = "";
  $i=1;
  $option= "<option VALUE='0'>TODOS</OPTION>";
  foreach ($fila as $filas)
  {
    $option.="<option value = '".$filas->valor1."'>".$filas->valor2."</option>";
    $i++;
  }
  return $option;
}
/*VALORES COMBO ESTADO REGISTRO POAI- STP*/
function getValoresDominiosComboPoai($concepto,$tipo)
{
  $fila_m =& get_instance();
  $fila_m->load->model('Configuracion_detalles_model');
  $opcion='';
  $fila = $fila_m->Configuracion_detalles_model->getValoresDominiosCombos($concepto);
  // $options = "";
  $i=1;
  $option= "<option VALUE='0'>TODOS</OPTION>";
  if($tipo == 'POAI')
  {
    foreach ($fila as $filas)
    {
      if($filas->valor1 !== 'FIN' && $filas->valor1 !== 'INC'&& $filas->valor1 !== 'EDC')
      {
        $option.="<option value = '".$filas->valor1."'>".$filas->valor2."</option>";
        $i++;
      }
    }
  }
  else
  {
    foreach ($fila as $filas)
    {
      if($filas->valor1 !== 'FIN')
      {
        $option.="<option value = '".$filas->valor1."'>".$filas->valor2."</option>";
        $i++;
      }
    }

  }
 
  return $option;
}


?>