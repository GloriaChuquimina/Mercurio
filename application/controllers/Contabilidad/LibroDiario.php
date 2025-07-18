<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LibroDiario extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->_is_logued_in();
        $this->load->model('PlanDeCuentas_model');
        $this->load->model('LibroDiario_model');
        $this->load->model('Comprobantes_model');
		$this->load->helper('configuraciones_helper');
		$this->load->helper('funcionarios_helper');
		$this->load->helper('correlativos_helper');
	}
    function _is_logued_in()
	{
		$is_logued_in = $this->session->userdata('is_logued_in');
		$id_apliacion = $this->session->userdata('id_apliacion');
		$aplicacion =   $this->config->item('IDAPLICACION');
		if($is_logued_in != TRUE || $id_apliacion != $aplicacion)
		{
			redirect('Login');
		}
	}
	public function index()
	{

		$dato['nombre_usuario']  = $this->session->userdata('nombre_usuario');		
		$dato['nombre_sistema']  = "SISTEMA CONTABLE<BR>MERCURIO";
		
		
		$id_usuario = $this->session->userdata('id_usuario');
		$dato['rolescero'] = $this->session->userdata('rolescero');
		$dato['roles']  = $this->session->userdata('roles');
		$dato['nombre_usuario']  = $this->session->userdata('nombre_completo');

		$titulo = "Libro Diario";		
		$dato['titulo'] = $titulo;

		$this->load->view('inicio/cabecera',$dato);
		$this->load->view('inicio/menu',$dato);
		$this->load->view('contabilidad/librodiario',$dato);
		$this->load->view('inicio/pie');
	}
	public function cargarDatosLibroDiario()
	{
		$id_usuario  = $this->session->userdata('id_usuario');
			
		$draw    = intval($this->input->get("draw"));
		$start   = intval($this->input->get("start"));
		$length  = intval($this->input->get("length"));	
		$data    = array();
		$num     = 1;

		$id_entidad            = $this->input->post('id_entidad');
		$cuentasSeleccionadas  = $this->input->post('cuentasSeleccionadas');
		$fecha_desde  		   = $this->input->post('fecha_desde');
		$fecha_hasta           = $this->input->post('fecha_hasta');

		$cadena = str_replace('-', ',', $cuentasSeleccionadas);
		$cadena = rtrim($cadena, ',');

		$libroDiarioComprobante = $this->LibroDiario_model->getLibroDiarioComprobantesPorRango($id_entidad,$fecha_desde,$fecha_hasta);
		$totalDebe =0;
		$totalHaber =0;
		$importeDebe=0;
		$importeHaber=0;
		$importe_moneda_nacional=0;
		$importe_moneda_extranjera=0;
		//  $tr .= "<tbody>";
		 $tr = "";

		foreach ($libroDiarioComprobante as $comprobante)
		{   

			$id_comprobante 	 = $comprobante->id_comprobante;
			$fecha_comprobante   = $comprobante->fecha_comprobante;
			$tipo_comprobante    = $comprobante->tipo_comprobante;
			$glosa_comprobante   = $comprobante->glosa_comprobante;

			$tr.="<tr>
					<td>
						".$fecha_comprobante."
					</td>
					<td>
						".$tipo_comprobante."
					</td>
					<td>
						---
					</td>
					<td>
						---
					</td>
				  </tr>";
			$datosComprobante    = $this->Comprobantes_model->getDetalleComprobanteByIdComprobante($id_comprobante);
			if($datosComprobante)	{
					foreach ($datosComprobante as $detalle_comprobante) {			
							$id_entidad      		   = $detalle_comprobante->id_entidad;
							$id_comprobante 		   = $detalle_comprobante->id_comprobante;
							$id_cuenta     		       = $detalle_comprobante->id_cuenta;
							$tipo_movimiento	       = $detalle_comprobante->tipo_movimiento;
							$tipo_cambio  		       = $detalle_comprobante->tipo_cambio ;
							$importe_moneda_nacional   = $detalle_comprobante->importe_moneda_nacional;
							$importe_moneda_extranjera = $detalle_comprobante->importe_moneda_extranjera;
							$glosa_cuenta     	       = $detalle_comprobante->glosa_cuenta;
							$estado     		       = $detalle_comprobante->estado;
							$codigo_cuenta			   = getCodigoCuenta($id_cuenta);
							$descripcion_cuenta		   = getCuenta($id_cuenta);
							$codigo_descripcion		   = $codigo_cuenta."-".$descripcion_cuenta;
							$resul 				       = 1;
							$mensaje				   = "OK";	
							if($tipo_movimiento == "DB"){
								$importeDebe=$importe_moneda_nacional;
							}
							elseif ($tipo_movimiento == "HB") {
								$importeHaber=$importe_moneda_nacional;
							}
							$tr.="<tr>
									<td>
									".$codigo_cuenta."
									</td>
									<td>
									".$descripcion_cuenta."
									</td>
									<td style='text-align: right'>
									".$importeDebe."
									</td>
									<td style='text-align: right'>
									".$importeHaber."
									</td>							
								</tr>";				
							// $data[] = array(
							// 	$codigo,
							// 	$descripcion,
							// 	number_format($debe,2,'.',','),
							// 	number_format($haber,2,'.',',')
							// 	);
							$totalDebe += $importeDebe;
							$totalHaber += $importeHaber;
					}
				$tr.="<tr style='background-color:rgb(248, 232, 228); font-weight: bold;'>
						<td style='text-align: right'>
							---
						</td>
						<td style='text-align: center'>
						".$glosa_comprobante."
						</td>
						<td style='text-align: right'>
						".$totalDebe."
						</td>
						<td style='text-align: right'>
						".$totalHaber."
						</td>
					 </tr>";
			}			

		}

		// $tr .= "</tbody>";
		
		$output =( array(
			            "resultado" 	 => 1, 
		              "nro_comprobantes" => count($libroDiarioComprobante) , 
						    "tabla" 	 => $tr ) );

		echo json_encode($output);
		exit();

	}
	private function ordenarJerarquicamente($cuentas, $padreId = 0, $indentacion = 0)
	{
		$ordenadas = [];

		foreach ($cuentas as $cuenta) {
			if ($cuenta['padre'] == $padreId) {
				// Buscar si esta cuenta tiene hijos
				$tieneHijos = false;
				foreach ($cuentas as $posibleHijo) {
					if ($posibleHijo['padre'] == $cuenta['id']) {
						$tieneHijos = true;
						break;
					}
					elseif($posibleHijo['padre'] == $cuenta['ruta'])/*ojo*/
					{
						$tieneHijos = true;
						break;
					}
				}
				// Añadir campo extra
				$cuenta['indentacion'] = $indentacion;
				$cuenta['es_padre'] = $tieneHijos;

				// Agregar la cuenta ordenada
				$ordenadas[] = $cuenta;

				// Agregar recursivamente los hijos
				$ordenadas = array_merge($ordenadas, $this->ordenarJerarquicamente($cuentas, $cuenta['id'], $indentacion + 1));
			}
		}

		return $ordenadas;
	}
	public function listarPlanDeCuentasBusqueda()
    {

		$marcarRegistro       = $this->input->post('marcareg');
		$cuentasSeleccionadas = $this->input->post('cuentasSeleccionadas');
		$cuentas   = $this->PlanDeCuentas_model->getPlanDeCuentasBusqueda();
		$totalCuentas = count($cuentas);
		$cuentas = json_decode(json_encode($cuentas), true);
		$ordenadas = $this->ordenarJerarquicamente($cuentas);
		
		$draw    = intval($this->input->get("draw"));
		$start   = intval($this->input->get("start"));
		$length  = intval($this->input->get("length"));	
		$data    = array();
		$num     = 1;
		
		foreach ($ordenadas as $fila)
		{   
			$cuenta= $fila['codigo']."-". $fila['descripcion'];
			$boton   = "
                        <span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Seleccionar'>
                            <button type='button' class='btn btn-success btn-sm' onclick=\"busquedaIDCuenta(".$fila['id'].",'".$cuenta."')\"><i>✓</i></button>     
                        </span>				
                        ";		

			$indentacion = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $fila['indentacion']);
			$descripcion = $fila['descripcion'];
			$codigo      = $fila['codigo'];
			$nivel       = $fila['nivel'];

			if (($fila['es_padre']) && ($fila['indentacion']== 0)) {
				$descripcion = "<strong><u>{$descripcion}</u></strong>";
				$codigo =  "<strong><u>{$codigo}</u></strong>";
			}
			$valor=$fila['id'];
			$nameId1 = "chkCuenta_".$valor;

			$cuentasBuscadas = explode("-", $cuentasSeleccionadas);
			// echo("<pre>");
			// echo(count($cuentasSeleccionadas));
			// echo("<br>");
			// echo($totalCuentas);
			// echo("</pre>");
			// die();
			if($marcarRegistro == 1)
			{
				$checkedCom = "checked";	
			}
			else
			{
				
				if($marcarRegistro == 0 && (count($cuentasBuscadas)-1) == $totalCuentas)
				{
					$checkedCom = "";	
				}
				else
				{
					if(count($cuentasBuscadas)>0)
					{
						foreach($cuentasBuscadas as $cuenta)
						{
							if($cuenta == $fila['id'])
							{
								$checkedCom = "checked";
								break;
							}
							else
							{
								$checkedCom = "";
							}
						}
					}
					else
					{
						$checkedCom = "";
					}
				}
				
			}
			
			$seleccion ="<input type='checkbox' value='".$valor."' name = '".$nameId1."' id='".$nameId1."' ".$checkedCom." >";
			$data[] = array(
				$seleccion,
				"<div style='text-align: center;'>$boton</div>",
				"<span class='badge badge-secondary'>".$codigo."</span>",
				$descripcion,
				$nivel
			);
		}

		// die();
		$output = array(
			"draw" => $draw,
			"recordsTotal" => count($ordenadas),
			"recordsFiltered" => count($ordenadas),
			"data" => $data
		);
		echo json_encode($output);
		exit();
    }
}