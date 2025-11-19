<?php
function obtenerCorrelativoComprobanteGestionEntidad($tipoCorrelativo, $id_entidad,$id_dependencia, $gestion)
{
    $fila =& get_instance();
    $fila->load->model('Correlativos_model');
    $datosDocumentoCorrelativo= $fila->Correlativos_model->getTipoCorrelativo($tipoCorrelativo);
    $idCorrelativo = $datosDocumentoCorrelativo[0]->id;
	// echo("corre==>".$idCorrelativo);

    // $datosCorrelativo = $fila->Correlativos_model->getCorrelativoEntidadGestion($idCorrelativo, $id_entidad, $gestion);
    // $correlativo = $datosCorrelativoSolicitud[0]->correlativo+1;
    $datosCorrelativo = $fila->Correlativos_model->getCorrelativoEntidadGestion($idCorrelativo, $id_entidad,$id_dependencia, $gestion);

	// echo json_encode($datosCorrelativo);
	// die();
	if($datosCorrelativo)
	{
		$id_correlativoentidadgestion = $datosCorrelativo[0]->id;
    	$correlativo                  = $datosCorrelativo[0]->correlativo+1;
	}
	else
	{
		$id_correlativoentidadgestion = 0;
    	$correlativo                  = 1;
	}
    // $id_correlativoentidadgestion = $datosCorrelativo[0]->id;
    // $correlativo                  = $datosCorrelativo[0]->correlativo+1;

    $resul = 1;
    $mensaje = "OK";
    $resultado ='[{   
          "idcorrelativoentidadgestion":"'.$id_correlativoentidadgestion.'",
          "correlativo":"'.$correlativo.'",         
          "resultado":"'.$resul.'",
          "mensaje":"'.$mensaje.'"
          }]';

    return $resultado;

}

function correlativoCiteGestion($gestion)
{
  $fila =& get_instance();
  $fila->load->model('Cites_model');
  $datosCorrelativoSolicitud = $fila->cites_model->getCorrelativoGestionSolicitud($gestion);
  $correlativo = $datosCorrelativoSolicitud[0]->correlativo+1;
  if($correlativo < 100)
  {
    if($correlativo < 10)
    {
      $correlativo = "00".$correlativo;
    }
    else
    {
      $correlativo = "0".$correlativo;
    }
  } 
  return $correlativo;
}

function correlativoCiteIndividual($id_funcionario)
{
  $fila =& get_instance();
    $fila->load->model('Cites_model');
  $datosCorrelativoSolicitud = $fila->cites_model->getCorrelativoIndividualSolicitud($id_funcionario);
  $correlativo = $datosCorrelativoSolicitud[0]->correlativo+1;
  if($correlativo < 100)
  {
    if($correlativo < 10)
    {
      $correlativo = "00".$correlativo;
    }
    else
    {
      $correlativo = "0".$correlativo;
    }
  } 
  return $correlativo;
}


function obtenerCorrelativoCiteGestion($gestion,$dependencia,$subdependencia, $tipoCorrelativo)
{
  	$fila =& get_instance();
  	$fila->load->model('Comunes_model');
  	$datosCite = $fila->Comunes_model->getDatoCorrespondencia($tipoCorrelativo);
  	$composicion = $datosCite[0]->composicion;
  	$inicio = $datosCite[0]->inicio;
  	$idprincipal = $datosCite[0]->principal;

  	$sigla_dependencia   = sigla_dependencia($dependencia);
  	$sigla_subdependencia = sub_sigla_dependencia($subdependencia);


  	$correlativo_cite     = valorCite($gestion,$dependencia,$tipoCorrelativo);
  	$correlativo_cite_dge = valorCiteDge($gestion,$idprincipal,$tipoCorrelativo);
  	$idCorrCite           = idvalorCite($gestion,$dependencia,$tipoCorrelativo);
  	$idCorrCiteDge        = idvalorCite($gestion,$idprincipal,$tipoCorrelativo);

    if($dependencia == 1 && $tipoCorrelativo == 3)
    {
      $composicion = 4;
    }

  	$cite = $inicio;
  	switch($composicion){
  		case 1:
  			$cite = $cite.$correlativo_cite_dge."/".$gestion;
  			$correlativo_cite = 0;
  		break;
  		case 2:
  			$cite = $cite.$sigla_dependencia.$correlativo_cite.$sigla_subdependencia."/".$gestion;
  			$correlativo_cite_dge = 0;
  		break;
  		case 3:
  			$cite = $cite.$correlativo_cite_dge."/".$sigla_dependencia.$correlativo_cite.$sigla_subdependencia."/".$gestion;
  		break;
      case 4:
        $cite = $cite.$correlativo_cite_dge."/".$gestion;
      break;

  	}


  	//return $correlativo_cite." - ".$correlativo_cite_dge." - ".$cite." - ".$composicion;
  	$resul = 1;
	  $mensaje = "OK";
  	$resultado ='[{		
					"idCorrCite":"'.$idCorrCite.'",
					"idCorrCiteDge":"'.$idCorrCiteDge.'",
					"correlativo_cite":"'.$correlativo_cite.'",
					"correlativo_cite_dge":"'.$correlativo_cite_dge.'",
					"cite":"'.$cite.'",
					"composicion":"'.$composicion.'",
					"resultado":"'.$resul.'",
					"mensaje":"'.$mensaje.'"
					}]';

	return $resultado;
}
function valorCite($gestion,$dependencia,$correlativo)
{
	  $fila =& get_instance();
  	$fila->load->model('Cites_model');
  	//$datos = $fila->Cites_model->getCorrelativoCite($gestion,$dependencia,$correlativo);
  	$datos = $fila->Cites_model->getCorrelativoCiteTabla($gestion,$dependencia,$correlativo);

  	$correlativo = "";
  	if($datos)
  	{
  		$correlativo = $datos[0]->correlativo + 1;
	  	if($correlativo < 100)
	  	{
	  		if($correlativo < 10)
	  		{
	  			$correlativo = "00".$correlativo;
	  		}
	  		else
	  		{
	  			$correlativo = "0".$correlativo;
	  		}
	  	}	
  	}
  	return $correlativo;
}

function obtenerCiteGestion($id_funcionario,$gestion,$tipoCorrelativo)
{
  	$fila =& get_instance();
  	$fila->load->model('Cites_model');
  	$corre = $fila->Cites_model->getCorrelativoAbreviatura($tipoCorrelativo);
  	$id_correlativo = $corre[0]->id;

  	//$correindi = $fila->Cites_model->getcorrelativociteindividualVacaciones($id_funcionario,$tipoCorrelativo2);

  	$corregeneral = $fila->Cites_model->getcorrelativocite($gestion,$id_correlativo);  	
	$correGestion = $corregeneral[0]->correlativo + 1;
	$cites        = "SNPE/ALM/".$tipoCorrelativo."-".$correGestion."/".$gestion;
  	$resul = 1;
	$mensaje = "OK";
  	$resultado ='[{					
					"correGestion":"'.$correGestion.'",
					"cites":"'.$cites.'",
					"resultado":"'.$resul.'",
					"mensaje":"'.$mensaje.'"
					}]';
	return $resultado;
}







?>