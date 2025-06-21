<?php
function perfil_principal_funcionario($id_funcionario)
{
  $fila = &get_instance();
  $fila->load->model('Comunes_model');
  $funcionario = $fila->comunes_model->getDatosPerfiles($id_funcionario);
  $id_perfil='';
	if( $funcionario)
	{
		$nombre = $funcionario[0]->id;
	}

  return $nombre;
}
function tipopuesto_principal_funcionario($id_funcionario)
{
  $fila = &get_instance();
  $fila->load->model('puestos_model');
  $funcionario = $fila->puestos_model->getListarPuestoFuncionarioPrincipal($id_funcionario);
  $tipo_puesto='';
	if( $funcionario)
	{
		$tipo_puesto = $funcionario[0]->tipo_puesto;
	}

  return $tipo_puesto;
}
function datos_persona_nombre2($id)
{
  $fila =& get_instance();
  $fila->load->model('Funcionarios_model');
  $datos = $fila->Funcionarios_model->datosPersonas($id);
  if($datos)
  {
    return $datos[0]->nombres." ".$datos[0]->primer_apellido." ".$datos[0]->segundo_apellido ;
  }
  else
  {
    return "";
  }
}

?>