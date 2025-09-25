<?php

function verificarPermisosUsuario($idUsuario,$idOpcion)
{
  $fila_m =& get_instance();
  $fila_m->load->model('Roles_model');
    if($idUsuario>0)
    {
        $datos = $fila_m->roles_model->verificarRolUsuario($idUsuario,$idOpcion);
        return count($datos);      
    }
    else
    {
        return 0;
    }
  
}

function verificarUsuarioPermiso($idOpcion,$idUsuario)
{
  $fila_m =& get_instance();
  $fila_m->load->model('Roles_model');

  $datos = $fila_m->Roles_model->check_opciones($idOpcion,$idUsuario);
  if($datos)
  {
    return "checked";
  }
  else
  {
    return "";
  }  
}

?>
