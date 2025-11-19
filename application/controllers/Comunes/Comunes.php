<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Comunes extends CI_Controller 
{
	function __construct(){
		parent::__construct();
		$this->_is_logued_in();
		$this->load->model('Comunes_model');
		$this->load->model('Entidades_model');
		$this->load->model('PlanDeCuentas_model');
		$this->load->model('TipoCambio_model');
		$this->load->helper('configuraciones_helper');

	}
    function _is_logued_in()
	{
		//
		$is_logued_in = $this->session->userdata('is_logued_in');
		$id_apliacion = $this->session->userdata('id_apliacion');
		$aplicacion =   $this->config->item('IDAPLICACION');		
		if($is_logued_in != TRUE || $id_apliacion != $aplicacion)		
		{
			$this->session->sess_destroy();
			redirect('Login');
		}
	}
	function nivel()
	{	
	    $option = "<option VALUE='-1'>Seleccione nivel</OPTION>";
		$niveles = array(0,1,2,3,4,5);
		$sw=0;        
        foreach ($niveles as $nivel) {
            $option.="<option value = '".$nivel."'>".$nivel."</option>";
        }
        echo $option;
	}
	function orden()
	{
		$id_aplicacion = $this->input->post('id_aplicacion');
		$id_modulo     = $this->input->post('id_modulo');
		$id_nivel      = $this->input->post('nivel');
		$id_funcionario = $this->session->userdata('id_funcionario');		
	    $option = "<option VALUE='-1'>Seleccione orden</OPTION>";
		$nivelorden = array(0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20);
		$sw=0;
		if($id_aplicacion == -1){
			$option = "<option VALUE='-1'>Seleccione orden</OPTION>";
		}
		else{

			foreach ($nivelorden as $orden) {
				$ordenBuscado = $orden;

				// echo ("<pre>");
				// print_r($sw);
				// echo ("</pre>");
				// die();
				
				$sw=0;
				$filas = $this->Opciones_model->getOpcionesAplicacionModuloNivel($id_aplicacion,$id_modulo,$id_nivel );
				$columna =array_column ($filas,'orden');
				$orden_unico = array_unique ($columna);
				// echo ("<pre>");
				// print_r($niveles_unicos);
				// echo ("</pre>");
				// die();
				foreach ($orden_unico as $fila)
				{
					if($ordenBuscado == $fila)
					{
						$option.="<option value = '".$ordenBuscado."'>".$ordenBuscado."- Registrado</option>";
						$sw=1;
						break;
					}
				}
				if($sw==0)
				{
					// print_r($nivelBuscado);
					$option.="<option value = '".$ordenBuscado."'>".$ordenBuscado."</option>";
				}
			}
		}
	    echo $option;
	}
	/*MERCURIO*/
	function cargarEntidad()
	{
	    $filas = $this->Entidades_model->getEntidades();
	    $option = "<option VALUE='-1'>Seleccione opción</OPTION>";
	    foreach ($filas as $fila)
	    {
	        $option.="<option value = '".$fila->id."'>".$fila->nombre."</option>";
	    }
   		 echo $option;
	}
	function cargarTipoMovimiento()
	{
	    $concepto = "TIPO MOVIMIENTO";
		$estado = "ACT";
	    $filas = $this->Comunes_model->getCatalogoDominio($concepto,$estado);
	    $option = "<option VALUE='-1'>Seleccione opción</OPTION>";
	    foreach ($filas as $fila)
	    {
	        $option.="<option value = '".$fila->valor1."'>".$fila->valor2."</option>";
	    }
   		 echo $option;
	}
	function cargarTipoComprobante()
	{
	    $concepto = "TIPO COMPROBANTES CONTABLE";
		$estado = "ACT";
	    $filas = $this->Comunes_model->getCatalogoDominio($concepto,$estado);
	    $option = "<option VALUE='-1'>Seleccione opción</OPTION>";
	    foreach ($filas as $fila)
	    {
	        $option.="<option value = '".$fila->valor1."'>".$fila->valor2."</option>";
	    }
   		 echo $option;
	}
	function cargarTipoComprobanteBusqueda()
	{
	    $concepto = "TIPO COMPROBANTES CONTABLE";
		$estado = "ACT";
	    $filas = $this->Comunes_model->getCatalogoDominio($concepto,$estado);
	    $option = "<option VALUE='-1'>TODOS</OPTION>";
	    foreach ($filas as $fila)
	    {
	        $option.="<option value = '".$fila->valor1."'>".$fila->valor2."</option>";
	    }
   		 echo $option;
	}
	function cargarCuentaContableEntidad()
	{
	    $filas = $this->PlanDeCuentas_model->getPlanDeCuentas();
	    $option = "<option VALUE='-1'>Seleccione opción</OPTION>";
	    foreach ($filas as $fila)
	    {
	        $option.="<option value = '".$fila->id."'>".$fila->codigo."-".$fila->descripcion."</option>";
	    }
   		 echo $option;
	}
	function cargarTipoMoneda()
	{
	    $concepto = "MONEDA";
		$estado = "ACT";
	    $filas = $this->Comunes_model->getCatalogoDominio($concepto,$estado);
	    $option = "<option VALUE='-1'>Seleccione opción</OPTION>";
	    foreach ($filas as $fila)
	    {
	        $option.="<option value = '".$fila->valor1."'>".$fila->valor2."</option>";
	    }
   		 echo $option;
	}
	function cargarMeses()
	{
	    $concepto = "MESES";
		$estado = "ACT";
	    $filas = $this->Comunes_model->getCatalogoDominio($concepto,$estado);
	    $option = "<option VALUE='-1'>Seleccione opción</OPTION>";
	    foreach ($filas as $fila)
	    {
	        $option.="<option value = '".$fila->valor1."'>".$fila->valor2."</option>";
	    }
   		 echo $option;
	}
	function cargarGestionTipoCambio()
	{

	    $filas = $this->TipoCambio_model->getGestionTipoCambio();
	    $option = "<option VALUE='-1'>Seleccione opción</OPTION>";
	    foreach ($filas as $fila)
	    {
	        $option.="<option value = '".$fila->gestion."'>".$fila->gestion."</option>";
	    }
   		 echo $option;
	}
	function cargarGestion()
	{
	    $filas = $this->Comunes_model->getGestion();
	    $option = "<option VALUE='-1'>Seleccione opción</OPTION>";
	    foreach ($filas as $fila)
	    {
	        $option.="<option value = '".$fila->gestion."'>".$fila->gestion."</option>";
	    }
   		 echo $option;
	}
	function getFechaCierreGestion($gestion,$tipo_cierre)
	{

	    $filas = $this->Comunes_model->getFechaCierreGestion($gestion,$tipo_cierre);

		// echo json_encode($filas);
		// die();
	    $fecha_cierre = $filas[0]->fecha_cierre;
   		echo $fecha_cierre;
	}
	/*FILTROS DE BUSQUEDA DE PLAN DE CUENTAS */
	function cargarNivelesPlanDeCuentas()
	{
	    $filas = $this->PlanDeCuentas_model->getNivelesPlanDeCuentas();
		// echo("<pre>");
		// print_r($filas);
		// echo("</pre>");
		// die();
	    $option = "<option VALUE='-1'>Seleccione opción</OPTION>";
	    foreach ($filas as $fila)
	    {
	        $option.="<option value = '".$fila->nivel."'>".$fila->name_nivel."</option>";
	    }
	    echo $option;
	}
	function cargarMayoresPlanDeCuentas()
	{
	    $filas = $this->PlanDeCuentas_model->getMayoresPlanDeCuentas();
		// echo("<pre>");
		// print_r($filas);
		// echo("</pre>");
		// die();
	    $option = "<option VALUE='-1'>Seleccione opción</OPTION>";
	    foreach ($filas as $fila)
	    {
	        $option.="<option value = '".$fila->id."'>".$fila->codigo." - ".$fila->descripcion."</option>";
	    }
	    echo $option;
	}
	function cargarSubCuentasPlanDeCuentas()
	{
		$id_cuenta = $this->input->post('id_mayor');
	    $filas = $this->PlanDeCuentas_model->getSubCuentasPlanDeCuentas($id_cuenta);
	    $option = "<option VALUE='-1'>Seleccione primero una cuenta mayor</OPTION>";
	    foreach ($filas as $fila)
	    {
	        $option.="<option value = '".$fila->id."'>".$fila->codigo." - ".$fila->descripcion."</option>";
	    }
	    echo $option;
	}
	
	function cargarOtrasSubCuentasPlanDeCuentas()
	{
		$id_mayor = $this->input->post('id_mayor');
		$id_cuenta = $this->input->post('id_cuenta');
	    $filas = $this->PlanDeCuentas_model->getOtrasSubCuentasPlanDeCuentas($id_mayor,$id_cuenta);
	    $option = "<option VALUE='-1'>Seleccione primero una cuenta mayor y su subcuenta</OPTION>";
	    foreach ($filas as $fila)
	    {
	        $option.="<option value = '".$fila->id."'>".$fila->codigo." - ".$fila->descripcion."</option>";
	    }
	    echo $option;
	}
	
	
}

