<?php
ini_set("allow_url_fopen", 1);

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


require_once APPPATH . "/libraries/fpdf/fpdf.php";

class Pdf3 extends FPDF {

    public $xheader;
    public $yheader;
    public $anchoheader = 205; 
    public $cabecera;
    public $tituloCabecera;
    public $gestion;
    public $opcion_cabecera;
    public $rubro;
    public $opcion_pie;

    public $subTitulo;
    public $subEncabezado;
    public $subEncabezado2;
    public $tipoReporte;
    public $fecha;
    public $fechaini;
    public $fechafin; 

    public $fechas; 
    public $sucursal; 
    public $usuarios; 
    public $puntoventa;
    public $logo; 

    public $cliente;
    public $numeroDocumento;
    public $numeroRecibo;
    public $nombre_completo_user;
    public $direccion;
    public $celular;

    
    private $encabezado;
    private $wi;
    private $cds220;
    

    public function __construct() {
        parent::__construct();
        $this->mes = array('', 'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre');
    }
    function setEncabezadoG($e){
        $this->encabezado = $e;
    }
    function setWidthsG($w){
        $this->wi = $w;
        $this->widths = $w;
    }

    public function Header() {
        $this->SetFont('Arial', 'B', 8);

        //CABECERA PARA LA LISTA DE ENTIDADES
        if($this->opcion_cabecera==1)
        {
            $this->Image('resources/images/logos/logo_senape_reporte.png', 15, 6, 75);
            $this->Image('resources/images/logos/chakana.png', 165, 5, 38);
            $this->Ln();
            $this->SetTextColor(0);
            $this->SetFont('Times','B',14);
            $this->SetY(23);
            $this->Cell(0,0,utf8_decode($this->tituloCabecera),0,1,'C',0);
            $this->Ln(6);

            //Cabecera
            $this->SetFillColor(156,156,156);
            $this->SetTextColor(0);
            $this->SetFont('Arial','B',8);
            $this->Cell(21,6,utf8_decode('NRO PARTIDA'),1,0,'C',1);
            $this->Cell(65,6,utf8_decode('DESCRIPCION'),1,0,'C',1);
            $this->Cell(25,6,utf8_decode('UNIDAD'),1,0,'C',1);
            $this->Cell(25,6,utf8_decode('CANT. ENTRADA'),1,0,'C',1);
            $this->Cell(25,6,utf8_decode('CANT. SALIDA'),1,0,'C',1);
            $this->Cell(25,6,utf8_decode('SALDO'),1,0,'C',1);
            $this->Ln();
            $this->SetWidths($this->wi);
        }
        //CABECERA PARA LA LISTA DE  SOLICITUDES CONFIRMADAS
        if($this->opcion_cabecera==2)
        {
            $this->Image('resources/images/logos/logo_senape_reporte.png', 15, 6, 75);
            $this->Image('resources/images/logos/chakana.png', 165, 5, 38);
            $this->Ln();
            $this->SetTextColor(0);
            $this->SetFont('Times','B',14);
            $this->SetY(23);
            $this->Cell(0,0,utf8_decode($this->tituloCabecera),0,1,'C',0);
            $this->SetFont('Times','B',10);
            $this->Ln(6);
            $this->Cell(0,0,utf8_decode($this->subtituloCabecera1),0,1,'C',0);
            $this->Ln(6);
            $this->Cell(0,0,utf8_decode($this->subtituloCabecera2),0,1,'C',0);
            $this->Ln(6);

            //Cabecera
            $this->SetFillColor(156,156,156);
            $this->SetTextColor(0);
            $this->SetFont('Arial','B',8);
            $this->Cell(10,6,utf8_decode('NRO'),1,0,'C',1);
            $this->Cell(30,6,utf8_decode('SOLICITANTE'),1,0,'C',1);
            $this->Cell(20,6,utf8_decode('CODIGO'),1,0,'C',1);
            $this->Cell(40,6,utf8_decode('DESCRIPCION'),1,0,'C',1);
            $this->Cell(25,6,utf8_decode('UNIDAD'),1,0,'C',1);
            $this->Cell(25,6,utf8_decode('PARTIDA'),1,0,'C',1);
            $this->Cell(25,6,utf8_decode('CANT. SOLIC'),1,0,'C',1);
            //$this->Cell(25,6,utf8_decode('SALDO'),1,0,'C',1);
            $this->Ln();
            $this->SetWidths($this->wi);
        }

        if($this->opcion_cabecera==3)
        {
            $this->Image('resources/images/logos/logo_senape_reporte.png', 15, 6, 75);
            $this->Image('resources/images/logos/chakana.png', 165, 5, 38);
            $this->Ln();
            $this->SetTextColor(0);
            $this->SetFont('Times','B',12);
            $this->SetY(23);
            $this->Cell(0,0,utf8_decode($this->tituloCabecera),0,1,'C',0);
            $this->SetFont('Times','B',9);
            $this->Ln(5);
            $this->Cell(0,0,utf8_decode($this->subtituloCabecera1),0,1,'C',0);
            $this->Ln(5);
            $this->Cell(0,0,utf8_decode($this->subtituloCabecera2),0,1,'C',0);
            $this->Ln(5);
            $this->Cell(0,0,utf8_decode($this->subtituloCabecera3),0,1,'C',0);
            $this->Ln(6);

            //Cabecera
            $this->SetFillColor(205,205,205);
            $this->SetTextColor(0);
            $this->SetFont('Arial','B',7);
            $this->Cell(10,6,utf8_decode('NRO'),1,0,'C',1);
            $this->Cell(35,6,utf8_decode('SOLICITANTE'),1,0,'C',1);
            $this->Cell(20,6,utf8_decode('CODIGO'),1,0,'C',1);
            $this->Cell(60,6,utf8_decode('DESCRIPCION'),1,0,'C',1);
            $this->Cell(20,6,utf8_decode('UNIDAD'),1,0,'C',1);
            $this->Cell(18,6,utf8_decode('PARTIDA'),1,0,'C',1);
            $this->Cell(17,6,utf8_decode('CANTIDAD'),1,0,'C',1);
            $this->Ln();
            $this->SetWidths($this->wi);
        }
        //  Reporte Ingresos
        if($this->opcion_cabecera==4)
        {
             $logo = $this->logo;
            $this->Image('upload/imagenes_empresa/'.$logo, 165, 5, 40,20);
            //$this->Image('resources/images/logos/chakana.png', 165, 5, 38);
            $this->Ln();
            $this->SetTextColor(6, 11, 105);
            $this->SetFont('Arial','B',14);
            $this->SetY(10);
            $this->Cell(0,0,utf8_decode($this->tituloCabecera),0,1,'C',0);
            $this->SetFont('Arial','B',18);
            $this->Ln(10);
            $this->Cell(0,0,utf8_decode($this->subtituloCabecera1),0,1,'C',0);
            $this->Ln(10);
            $fechas      = $this->fechas; 
            $numeroRecibo    = $this->numeroRecibo;
            $cliente    = $this->cliente; 
            $numeroDocumento  = $this->numeroDocumento;
            $this->Ln();
            $this->SetFont('Arial', 'B', 9);
            $this->SetTextColor(0);
            $this->Cell(30, 5, utf8_decode('Fecha:'), 0, 0, 'L');   
            $this->SetFont('Arial', '', 9);         
            $this->Cell(100, 5, $fechas, 0, 0, 'L');
            
            $this->SetFont('Arial', 'B', 9);
            $this->Cell(25, 5, utf8_decode('Número:'), 0, 0, 'L');            
            $this->Cell(55, 5, $numeroRecibo, 0, 1, 'L');

            $this->SetFont('Arial', 'B', 9);
            $this->Cell(30, 5, utf8_decode('Nombre Cliente:'), 0, 0, 'L');
            $this->SetFont('Arial', '', 9);
            $this->Cell(100, 5, utf8_decode($cliente), 0, 0, 'L');            
            $this->SetFont('Arial', 'B', 9);
            $this->Cell(25, 5, utf8_decode('Nro Documento:'), 0, 0, 'L');
            $this->SetFont('Arial', '', 9);
            $this->Cell(65, 5, $numeroDocumento, 0, 1, 'L');
            $this->Ln(2); 


            $this->SetFont('Arial', 'B', 18);
            $this->SetTextColor(6, 11, 105);
            $this->Cell(200, 5, utf8_decode('DETALLE PEDIDO:'), 0, 0, 'C');
           
            $this->Ln(7); 

            //Cabecera
            $this->SetFillColor(205,205,205);
            $this->SetTextColor(0);
            $this->SetFont('Arial','B',6);
            $y=$this->GetY();

            $this->SetXY(10,$y);
            $this->MultiCell(8,12,utf8_decode('NRO.'),1,'C',1);          
            $this->SetXY(18,$y);
            $this->MultiCell(20,12,utf8_decode('CODIGO'),1,'C',1); 
            $this->SetXY(38,$y);
            $this->MultiCell(18,12,utf8_decode('UM'),1,'C',1);            
            $this->SetXY(56,$y);
            $this->MultiCell(100,12,utf8_decode('DESCRIPCIÓN PRODUCTO'),1,'C',1);
            $this->SetXY(156,$y);
            $this->MultiCell(15,12,utf8_decode('CANT'),1,'C',1);
            $this->SetXY(171,$y);
            $this->MultiCell(15,12,utf8_decode('MEDIDA ML'),1,'C',1);
            $this->SetXY(186,$y);
            $this->MultiCell(15,6,utf8_decode('SUB TOTAL ML'),1,'C',1);
            
            $this->SetXY(0,35);
         
            $this->Ln(26);
            $this->SetWidths($this->wi);
        }
        if($this->opcion_cabecera==5)
        {
            $logo = $this->logo;
            $this->Image('upload/imagenes_empresa/'.$logo, 165, 5, 40,20);
            //$this->Image('resources/images/logos/chakana.png', 165, 5, 38);
            $this->Ln();
            $this->SetTextColor(6, 11, 105);
            $this->SetFont('Arial','B',14);
            $this->SetY(10);
            $this->Cell(0,0,utf8_decode($this->tituloCabecera),0,1,'C',0);
            $this->SetFont('Arial','B',18);
            $this->Ln(10);
            $this->Cell(0,0,utf8_decode($this->subtituloCabecera1),0,1,'C',0);
            $this->Ln(10);
            $fechas      = $this->fechas; 
            $numeroRecibo    = $this->numeroRecibo;
            $cliente    = $this->cliente; 
            $numeroDocumento = $this->numeroDocumento;
            $direccion      = $this->direccion;
            $celular        = $this->celular;


            $this->Ln();
            $this->SetFont('Arial', 'B', 9);
            $this->SetTextColor(0);
            $this->Cell(30, 5, utf8_decode('Fecha Cotización:'), 0, 0, 'L');   
            $this->SetFont('Arial', '', 9);         
            $this->Cell(100, 5, $fechas, 0, 0, 'L');
            
            $this->SetFont('Arial', 'B', 9);
            $this->Cell(25, 5, utf8_decode('Número:'), 0, 0, 'L');            
            $this->Cell(55, 5, $numeroRecibo, 0, 1, 'L');

            $this->SetFont('Arial', 'B', 9);
            $this->Cell(30, 5, utf8_decode('Nombre Cliente:'), 0, 0, 'L');
            $this->SetFont('Arial', '', 9);
            $this->Cell(100, 5, utf8_decode($cliente), 0, 0, 'L');            
            $this->SetFont('Arial', 'B', 9);
            $this->Cell(25, 5, utf8_decode('Nro Documento:'), 0, 0, 'L');
            $this->SetFont('Arial', '', 9);
            $this->Cell(65, 5, $numeroDocumento, 0, 1, 'L');
            //

            $this->SetFont('Arial', 'B', 9);
            $this->Cell(30, 5, utf8_decode('Dirección:'), 0, 0, 'L');
            $this->SetFont('Arial', '', 9);
            $this->Cell(190, 5, utf8_decode($direccion), 0, 1, 'L');            
            
            $this->SetFont('Arial', 'B', 9);
            $this->Cell(30, 5, utf8_decode('Teléfono/Celular:'), 0, 0, 'L');
            $this->SetFont('Arial', '', 9);
            $this->Cell(100, 5, utf8_decode($celular), 0, 0, 'L');            
            $this->SetFont('Arial', 'B', 9);
            $this->Cell(30, 5, utf8_decode('Términos de Pago:'), 0, 0, 'L');
            $this->SetFont('Arial', '', 9);
            $this->Cell(55, 5, "EFECTIVO", 0, 1, 'L');

            $this->Ln(2); 


            $this->SetFont('Arial', 'B', 18);
            $this->SetTextColor(6, 11, 105);
            $this->Cell(200, 5, utf8_decode('PROFORMA'), 0, 0, 'C');
           
            $this->Ln(7); 

            //Cabecera
            $this->SetFillColor(205,205,205);
            $this->SetTextColor(0);
            $this->SetFont('Arial','B',6);
            $y=$this->GetY();

            $this->SetXY(10,$y);
            $this->MultiCell(8,12,utf8_decode('NRO.'),1,'C',1);          
            $this->SetXY(18,$y);
            $this->MultiCell(13,12,utf8_decode('UM'),1,'C',1);            
            $this->SetXY(31,$y);
            $this->MultiCell(85,12,utf8_decode('DESCRIPCIÓN PRODUCTO'),1,'C',1);
            $this->SetXY(116,$y);
            $this->MultiCell(15,12,utf8_decode('CANT'),1,'C',1);
            $this->SetXY(131,$y);
            $this->MultiCell(15,12,utf8_decode('MEDIDA ML'),1,'C',1);
            $this->SetXY(146,$y);
            $this->MultiCell(15,6,utf8_decode('SUB TOTAL ML'),1,'C',1);
            $this->SetXY(161,$y);
            $this->MultiCell(15,4,utf8_decode('PRECIO UNITARIO BS/ML'),1,'C',1);            
            $this->SetXY(176,$y);
            $this->MultiCell(25,12,utf8_decode('SUB TOTAL'),1,'C',1);
            $this->SetXY(0,35);
         
            $this->Ln(36);
            $this->SetWidths($this->wi);
        }
        if($this->opcion_cabecera==6)
        {
            $logo = $this->logo;
            $this->Image('upload/imagenes_empresa/'.$logo, 165, 5, 40,20);
            //$this->Image('resources/images/logos/chakana.png', 165, 5, 38);
            $this->Ln();
            $this->SetTextColor(6, 11, 105);
            $this->SetFont('Arial','B',14);
            $this->SetY(10);
            $this->Cell(0,0,utf8_decode($this->tituloCabecera),0,1,'C',0);
            $this->SetFont('Arial','B',18);
            $this->Ln(10);
            $this->Cell(0,0,utf8_decode($this->subtituloCabecera1),0,1,'C',0);
            $this->Ln(10);
            $fechas      = $this->fechas; 
            $numeroRecibo    = $this->numeroRecibo;
            $cliente    = $this->cliente; 
            $numeroDocumento  = $this->numeroDocumento;
            $this->Ln();
            $this->SetFont('Arial', 'B', 9);
            $this->SetTextColor(0);
            $this->Cell(30, 5, utf8_decode('Fecha:'), 0, 0, 'L');   
            $this->SetFont('Arial', '', 9);         
            $this->Cell(100, 5, $fechas, 0, 0, 'L');
            
            $this->SetFont('Arial', 'B', 9);
            $this->Cell(25, 5, utf8_decode('Número:'), 0, 0, 'L');            
            $this->Cell(55, 5, $numeroRecibo, 0, 1, 'L');

            $this->SetFont('Arial', 'B', 9);
            $this->Cell(30, 5, utf8_decode('Nombre Cliente:'), 0, 0, 'L');
            $this->SetFont('Arial', '', 9);
            $this->Cell(100, 5, utf8_decode($cliente), 0, 0, 'L');            
            $this->SetFont('Arial', 'B', 9);
            $this->Cell(25, 5, utf8_decode('Nro Documento:'), 0, 0, 'L');
            $this->SetFont('Arial', '', 9);
            $this->Cell(65, 5, $numeroDocumento, 0, 1, 'L');
            $this->Ln(2); 


            $this->SetFont('Arial', 'B', 18);
            $this->SetTextColor(6, 11, 105);
            $this->Cell(200, 5, utf8_decode('DETALLE PEDIDO:'), 0, 0, 'C');
           
            $this->Ln(7); 

            //Cabecera
            $this->SetFillColor(205,205,205);
            $this->SetTextColor(0);
            $this->SetFont('Arial','B',6);
            $y=$this->GetY();

            $this->SetXY(10,$y);
            $this->MultiCell(8,12,utf8_decode('NRO.'),1,'C',1);          
            $this->SetXY(18,$y);
            $this->MultiCell(13,12,utf8_decode('UM'),1,'C',1);            
            $this->SetXY(31,$y);
            $this->MultiCell(85,12,utf8_decode('DESCRIPCIÓN PRODUCTO'),1,'C',1);
            $this->SetXY(116,$y);
            $this->MultiCell(15,12,utf8_decode('CANT'),1,'C',1);
            $this->SetXY(131,$y);
            $this->MultiCell(15,12,utf8_decode('MEDIDA ML'),1,'C',1);
            $this->SetXY(146,$y);
            $this->MultiCell(15,6,utf8_decode('SUB TOTAL ML'),1,'C',1);
            $this->SetXY(161,$y);
            $this->MultiCell(15,4,utf8_decode('PRECIO UNITARIO BS/ML'),1,'C',1);            
            $this->SetXY(176,$y);
            $this->MultiCell(25,12,utf8_decode('SUB TOTAL'),1,'C',1);
            $this->SetXY(0,35);
         
            $this->Ln(26);
            $this->SetWidths($this->wi);
        }
        if($this->opcion_cabecera==7)
        {
            $logo = $this->logo;
            $this->Image('upload/imagenes_empresa/'.$logo, 10, 5, 40,20);
            //$this->Image('resources/images/logos/chakana.png', 165, 5, 38);
            $this->Ln();
            $this->SetTextColor(0);
            $this->SetFont('Times','B',12);
            $this->SetY(23);
            $this->Cell(0,0,utf8_decode($this->tituloCabecera),0,1,'C',0);
            $this->SetFont('Times','B',11);                         
            $this->Ln(5);
            $this->Cell(0,0,utf8_decode($this->subtituloCabecera1),0,1,'C',0);
            $this->Ln(2);
            $fechas      = $this->fechas; 
            $sucursal    = $this->sucursal;
            $usuarios    = $this->usuarios; 
            $puntoventa  = $this->puntoventa;
            $this->Ln();
            $this->SetFont('Arial', 'B', 9);
            $this->Cell(40, 5, utf8_decode('Rango de Fechas:'), 0, 0, 'L');
            $this->SetFont('Arial', '', 9);
            $this->Cell(50, 5, $fechas, 0, 0, 'L');
            $this->Cell(30, 5, '', 0, 0, 'C');
            $this->SetFont('Arial', 'B', 9);
            $this->Cell(30, 5, utf8_decode('Sucursal:'), 0, 0, 'L');
            $this->SetFont('Arial', '', 9);
            $this->Cell(55, 5, $sucursal, 0, 1, 'L');

            $this->SetFont('Arial', 'B', 9);
            $this->Cell(40, 5, utf8_decode('Usuario(s):'), 0, 0, 'L');
            $this->SetFont('Arial', '', 9);
            $this->Cell(50, 5, utf8_decode($usuarios), 0, 0, 'L');
            $this->Cell(30, 5, '', 0, 0, 'C');
            $this->SetFont('Arial', 'B', 9);
            $this->Cell(30, 5, utf8_decode('Punto de Venta:'), 0, 0, 'L');
            $this->SetFont('Arial', '', 9);
            $this->Cell(55, 5, $puntoventa, 0, 1, 'L');
            $this->Ln(3);            
            //Cabecera
            $this->SetFillColor(205,205,205);
            $this->SetTextColor(0);
            $this->SetFont('Arial','B',6);
            $y=$this->GetY();
            $this->SetXY(10,$y);
            $this->MultiCell(8,8,utf8_decode('NRO.'),1,'C',1);
            $this->SetXY(18,$y);
            $this->MultiCell(5,8,utf8_decode('S'),1,'C',1);
            $this->SetXY(23,$y);
            $this->MultiCell(5,8,utf8_decode('PV'),1,'C',1);
            $this->SetXY(28,$y);
            $this->MultiCell(13,8,utf8_decode('FECHA'),1,'C',1);
            $this->SetXY(41,$y);
            $this->MultiCell(11,8,utf8_decode('HORA'),1,'C',1);
            $this->SetXY(52,$y);
            $this->MultiCell(13,4,utf8_decode('NRO. FACTURA'),1,'C',1);
            $this->SetXY(65,$y);
            $this->MultiCell(16,8,utf8_decode('NIT'),1,'C',1);
            $this->SetXY(81,$y);
            $this->MultiCell(30,8,utf8_decode('RAZÓN SOCIAL'),1,'C',1);            
            $this->SetXY(111,$y);
            $this->MultiCell(16,8,utf8_decode('SUB TOTAL'),1,'C',1);
            $this->SetXY(127,$y);
            $this->MultiCell(16,8,utf8_decode('DESCUENTO'),1,'C',1);
            $this->SetXY(143,$y);
            $this->MultiCell(16,4,utf8_decode('TOTAL SUJETO IVA'),1,'C',1);
            $this->SetXY(159,$y);
            $this->MultiCell(10,4,utf8_decode('EMI SIÓN'),1,'C',1);
            $this->SetXY(169,$y);
            $this->MultiCell(20,8,utf8_decode('USUARIO'),1,'C',1);
            $this->SetXY(189,$y);
            $this->MultiCell(15,8,utf8_decode('ESTADO'),1,'C',1);            
            $this->SetXY(0,35);
            $this->Ln();
            $this->SetWidths($this->wi);
            $this->Ln();
            $this->SetWidths($this->wi);
        }

        if($this->opcion_cabecera==8)
        {
            $logo = $this->logo;
            $this->Image('upload/imagenes_empresa/'.$logo, 165, 5, 40,20);
            //$this->Image('resources/images/logos/chakana.png', 165, 5, 38);
            $this->Ln();
            $this->SetTextColor(6, 11, 105);
            $this->SetFont('Arial','B',14);
            $this->SetY(10);
            $this->Cell(0,0,utf8_decode($this->tituloCabecera),0,1,'C',0);
            $this->SetFont('Arial','B',18);
            $this->Ln(10);
            $this->Cell(0,0,utf8_decode($this->subtituloCabecera1),0,1,'C',0);
            $this->Ln(10);
            $fechas      = $this->fechas; 
            $sucursal    = $this->sucursal;
            $usuarios    = $this->usuarios; 
            $puntoventa  = $this->puntoventa;
            $this->Ln();
            $this->SetFont('Arial', 'B', 9);
            $this->Cell(40, 5, utf8_decode('Rango de Fechas:'), 0, 0, 'L');
            $this->SetFont('Arial', '', 9);
            $this->Cell(50, 5, $fechas, 0, 0, 'L');
            $this->Cell(30, 5, '', 0, 0, 'C');
            $this->SetFont('Arial', 'B', 9);
            $this->Cell(30, 5, utf8_decode('Sucursal:'), 0, 0, 'L');
            $this->SetFont('Arial', '', 9);
            $this->Cell(55, 5, $sucursal, 0, 1, 'L');

            $this->SetFont('Arial', 'B', 9);
            $this->Cell(40, 5, utf8_decode('Usuario(s):'), 0, 0, 'L');
            $this->SetFont('Arial', '', 9);
            $this->Cell(50, 5, utf8_decode($usuarios), 0, 0, 'L');
            $this->Cell(30, 5, '', 0, 0, 'C');
            $this->SetFont('Arial', 'B', 9);
            $this->Cell(30, 5, utf8_decode('Punto de Venta:'), 0, 0, 'L');
            $this->SetFont('Arial', '', 9);
            $this->Cell(55, 5, $puntoventa, 0, 1, 'L');
            $this->Ln(3);            
            //Cabecera
            $this->SetFillColor(205,205,205);
            $this->SetTextColor(0);
            $this->SetFont('Arial','B',6);
            $y=$this->GetY();
            $this->SetXY(10,$y);
            $this->MultiCell(8,8,utf8_decode('NRO.'),1,'C',1);
            $this->SetXY(18,$y);
            $this->MultiCell(5,8,utf8_decode('S'),1,'C',1);
            $this->SetXY(23,$y);
            $this->MultiCell(5,8,utf8_decode('PV'),1,'C',1);
            $this->SetXY(28,$y);
            $this->MultiCell(18,4,utf8_decode('FORMA DE PAGO'),1,'C',1);
            $this->SetXY(46,$y);
            $this->MultiCell(14,8,utf8_decode('FECHA'),1,'C',1);
            $this->SetXY(60,$y);
            $this->MultiCell(13,4,utf8_decode('# CORRE LATIVO'),1,'C',1);
            $this->SetXY(73,$y);
            $this->MultiCell(16,8,utf8_decode('CELULAR'),1,'C',1);
            $this->SetXY(89,$y);
            $this->MultiCell(33,8,utf8_decode('CLIENTE'),1,'C',1);            
            $this->SetXY(122,$y);
            $this->MultiCell(16,8,utf8_decode('SUB TOTAL'),1,'C',1);
            $this->SetXY(138,$y);
            $this->MultiCell(16,8,utf8_decode('DESCUENTO'),1,'C',1);
            $this->SetXY(154,$y);
            $this->MultiCell(16,4,utf8_decode('TOTAL VENTA'),1,'C',1);
            
            $this->SetXY(170,$y);
            $this->MultiCell(20,8,utf8_decode('USUARIO'),1,'C',1);
            $this->SetXY(190,$y);
            $this->MultiCell(16,8,utf8_decode('ESTADO'),1,'C',1);            
            $this->SetXY(0,35);
            $this->Ln();
            $this->SetWidths($this->wi);
            $this->Ln();
            $this->SetWidths($this->wi);
        }


        

        if($this->opcion_cabecera==9)
        {
            $logo = $this->logo;
            $this->Image('upload/imagenes_empresa/'.$logo, 230, 5, 40,20);
            //$this->Image('resources/images/logos/chakana.png', 165, 5, 38);
             $this->Ln();
            $this->SetTextColor(0);
            $this->SetFont('Times','B',14);
            $this->SetY(15);
            $this->Cell(0,0,utf8_decode($this->nombre_empresa),0,1,'C',0);
            $this->SetY(23);
            $this->SetFont('Times','B',12);
            $this->Cell(0,0,utf8_decode($this->tituloCabecera),0,1,'C',0);
             $this->SetFont('Times','B',9);
            $this->Ln(5);
            $this->Cell(0,0,utf8_decode($this->subtituloCabecera1),0,1,'C',0);
            $this->Ln(5);
            
            //Cabecera
            $this->SetFillColor(205,205,205);
            $this->SetTextColor(0);
            $this->SetFont('Arial','B',7);
            $this->Cell(7,10,utf8_decode('NRO'),1,0,'C',1);
            $this->Cell(20,10,utf8_decode('CÓDIGO'),1,0,'C',1);
            $this->Cell(12,10,utf8_decode('PARTIDA'),1,0,'C',1);
            $this->Cell(15,10,utf8_decode('UNIDAD'),1,0,'C',1);
            $this->Cell(76,10,utf8_decode('DESCRIPCIÓN'),1,0,'C',1);    
            $this->SetFont('Arial','B',6);
            $y=$this->GetY();
            $this->SetXY(140,$y);
            $this->MultiCell(17,5,utf8_decode('INICIAL FÍSICO'),1,'C',1);
            $this->SetXY(157,$y);
            $this->MultiCell(15,5,utf8_decode('INGRESO FÍSICO'),1,'C',1);
            $this->SetXY(172,$y);
            $this->MultiCell(15,5,utf8_decode('SALIDA FÍSICO'),1,'C',1);
            $this->SetXY(187,$y);
            $this->MultiCell(17,10,utf8_decode('FINAL FÍSICO'),1,'C',1);            
            $this->SetXY(204,$y);
            $this->MultiCell(17,5,utf8_decode('INICIAL VALORADO'),1,'C',1);
            $this->SetXY(221,$y);
            $this->MultiCell(17,5,utf8_decode('INGRESO VALORADO'),1,'C',1);
            $this->SetXY(236,$y);
            $this->MultiCell(17,5,utf8_decode('SALIDA VALORADO'),1,'C',1);
            $this->SetXY(251,$y);            
            $this->MultiCell(17,5,utf8_decode('FINAL VALORADO'),1,'C',1);
            $this->SetXY(188,38);

            $this->Ln();
            $this->SetWidths($this->wi);
        }

        if($this->opcion_cabecera==10)
        {
             $logo = $this->logo;
            $this->Image('upload/imagenes_empresa/'.$logo, 230, 5, 40,20);
            //$this->Image('resources/images/logos/chakana.png', 165, 5, 38);
             $this->Ln();
            $this->SetTextColor(0);
            $this->SetFont('Times','B',14);
            $this->SetY(15);
            $this->Cell(0,0,utf8_decode($this->nombre_empresa),0,1,'C',0);
            $this->SetY(23);
            $this->SetFont('Times','B',12);
            $this->Cell(0,0,utf8_decode($this->tituloCabecera),0,1,'C',0);
             $this->SetFont('Times','B',9);
            $this->Ln(5);
            $this->Cell(0,0,utf8_decode($this->subtituloCabecera1),0,1,'C',0);
            $this->Ln(5);
            
            //Cabecera
            $this->SetFillColor(205,205,205);
            $this->SetTextColor(0);
            $this->SetFont('Arial','B',7);
            $this->Cell(7,10,utf8_decode('NRO'),1,0,'C',1);            
            $this->Cell(12,10,utf8_decode('PARTIDA'),1,0,'C',1);            
            $this->Cell(101,10,utf8_decode('DESCRIPCIÓN'),1,0,'C',1);            
            $this->SetFont('Arial','B',6);
            $y=$this->GetY();
            $this->SetXY(130,$y);
            $this->MultiCell(17,5,utf8_decode('INICIAL FÍSICO'),1,'C',1);
            $this->SetXY(147,$y);
            $this->MultiCell(15,5,utf8_decode('INGRESO FÍSICO'),1,'C',1);
            $this->SetXY(162,$y);
            $this->MultiCell(15,5,utf8_decode('SALIDA FÍSICO'),1,'C',1);
            $this->SetXY(177,$y);
            $this->MultiCell(17,10,utf8_decode('FINAL FÍSICO'),1,'C',1);            
            $this->SetXY(194,$y);
            $this->MultiCell(17,5,utf8_decode('INICIAL VALORADO'),1,'C',1);
            $this->SetXY(211,$y);
            $this->MultiCell(15,5,utf8_decode('INGRESO VALORADO'),1,'C',1);
            $this->SetXY(226,$y);
            $this->MultiCell(15,5,utf8_decode('SALIDA VALORADO'),1,'C',1);
            $this->SetXY(241,$y);            
            $this->MultiCell(17,5,utf8_decode('FINAL VALORADO'),1,'C',1);
            $this->SetXY(188,38);
            $this->Ln();
            $this->SetWidths($this->wi);
        }
        

        //CABECERA PARA EL DETALLE DE INFORMACIÓN DE LAS ENTIDADES
        //***** Cabecera Vacía *****//
        if($this->opcion_cabecera==11)
        {
            /*$this->Ln();
            $this->SetTextColor(0);
            $this->SetFont('Times','B',12);
            $this->SetY(23);
            $this->Cell(0,0,utf8_decode($this->tituloCabecera),0,1,'C',0);
             $this->SetFont('Times','B',9);
            $this->Ln(5);
            $this->Cell(0,0,utf8_decode($this->subtituloCabecera1),0,1,'C',0);*/
            $this->Ln(0);
            
            //Cabecera
           /* $this->SetFillColor(205,205,205);
            $this->SetTextColor(0);
            $this->SetFont('Arial','B',7);
            //$this->Cell(20,10,utf8_decode('CÓDIGO PRODUCTO / SERVICIO'),1,0,'C',1);
            $this->MultiCell(20,5,utf8_decode('CÓDIGO PRODUCTO / SERVICIO'),1,'C',1);
            $this->Cell(20,10,utf8_decode('CANTIDAD'),1,0,'C',1);
            $this->Cell(20,10,utf8_decode('UNIDAD DE MEDIDA'),1,0,'C',1);            
            $this->Cell(70,10,utf8_decode('DESCRIPCIÓN'),1,0,'C',1);
            $this->Cell(20,10,utf8_decode('PRECIO UNITARIO'),1,0,'C',1);
            $this->Cell(20,10,utf8_decode('DESCUENTO'),1,0,'C',1);
            $this->Cell(20,10,utf8_decode('SUBTOTAL'),1,0,'C',1);*/
            $this->SetFillColor(224,224,224);
            $this->SetTextColor(0);
            
            $this->SetFont('Arial','B',6);
            $y=$this->GetY();
            $this->SetXY(20,$y);
            $this->MultiCell(20,4,utf8_decode('CÓDIGO PRODUCTO / SERVICIO'),1,'C',1);
            $this->SetXY(40,$y);
            $this->MultiCell(20,12,utf8_decode('CANTIDAD'),1,'C',1);
            $this->SetXY(60,$y);
            $this->MultiCell(20,6,utf8_decode('UNIDAD DE MEDIDA'),1,'C',1);
            $this->SetXY(80,$y);
            $this->MultiCell(70,12,utf8_decode('DESCRIPCIÓN'),1,'C',1);            
            $this->SetXY(150,$y);
            $this->MultiCell(20,6,utf8_decode('PRECIO UNITARIO'),1,'C',1);
            $this->SetXY(170,$y);
            $this->MultiCell(20,12,utf8_decode('DESCUENTO'),1,'C',1);
            $this->SetXY(190,$y);
            $this->MultiCell(20,12,utf8_decode('SUBTOTAL'),1,'C',1);
            
            $this->SetXY(188,74);
            $this->Ln();
            $this->SetWidths($this->wi);
        }
        if($this->opcion_cabecera==12)
        {
            $logo = $this->logo;
            $this->Image('upload/imagenes_empresa/'.$logo, 165, 5, 40,20);
            //$this->Image('resources/images/logos/chakana.png', 165, 5, 38);
            $this->Ln();
            $this->SetTextColor(6, 11, 105);
            $this->SetFont('Arial','B',14);
            $this->SetY(10);
            $this->Cell(0,0,utf8_decode($this->tituloCabecera),0,1,'C',0);
            $this->SetFont('Arial','B',18);
            $this->Ln(10);
            $this->Cell(0,0,utf8_decode($this->subtituloCabecera1),0,1,'C',0);
            $this->Ln(10);
            $fechas      = $this->fechas; 
            $sucursal    = $this->sucursal;
            $usuarios    = $this->usuarios; 
            $puntoventa  = $this->puntoventa;
            $this->Ln();
            $this->SetFont('Arial', 'B', 9);
            $this->Cell(40, 5, utf8_decode('Fecha Ingreso:'), 0, 0, 'L');
            $this->SetFont('Arial', '', 9);
            $this->Cell(50, 5, $fechas, 0, 0, 'L');
            $this->Cell(30, 5, '', 0, 0, 'C');
            $this->SetFont('Arial', 'B', 9);
            $this->Cell(30, 5, utf8_decode('Sucursal:'), 0, 0, 'L');
            $this->SetFont('Arial', '', 9);
            $this->Cell(55, 5, $sucursal, 0, 1, 'L');

            $this->SetFont('Arial', 'B', 9);
            $this->Cell(40, 5, utf8_decode('Usuario:'), 0, 0, 'L');
            $this->SetFont('Arial', '', 9);
            $this->Cell(50, 5, utf8_decode($usuarios), 0, 0, 'L');
            $this->Cell(30, 5, '', 0, 0, 'C');
            $this->SetFont('Arial', 'B', 9);
            $this->Cell(30, 5, utf8_decode('Número de ingreso:'), 0, 0, 'L');
            $this->SetFont('Arial', '', 9);
            $this->Cell(55, 5, $puntoventa, 0, 1, 'L');
            $this->Ln(3);            
            //Cabecera
            $this->SetFillColor(205,205,205);
            $this->SetTextColor(0);
            $this->SetFont('Arial','B',6);
            $y=$this->GetY();
            $this->SetXY(10,$y);
            $this->MultiCell(8,8,utf8_decode('NRO.'),1,'C',1);            
            $this->SetXY(18,$y);
            $this->MultiCell(12,4,utf8_decode('COD. CAT.'),1,'C',1);
            $this->SetXY(30,$y);
            $this->MultiCell(18,8,utf8_decode('CATEGORÍA'),1,'C',1);
            $this->SetXY(48,$y);
            $this->MultiCell(12,4,utf8_decode('COD. PRO.'),1,'C',1);            
            $this->SetXY(60,$y);
            $this->MultiCell(70,8,utf8_decode('DESCRIPCIÓN PRODUCTO'),1,'C',1);            
            $this->SetXY(130,$y);
            $this->MultiCell(16,8,utf8_decode('UNI. MED'),1,'C',1);
            $this->SetXY(146,$y);
            $this->MultiCell(15,8,utf8_decode('CANT. ING.'),1,'C',1);
            $this->SetXY(161,$y);
            $this->MultiCell(15,4,utf8_decode('PREC. UNIT. ING.'),1,'C',1);
            
            $this->SetXY(176,$y);
            $this->MultiCell(15,4,utf8_decode('PREC. TOTAL.'),1,'C',1);
            $this->SetXY(191,$y);
            $this->MultiCell(15,4,utf8_decode('PREC. UNIT. VENTA'),1,'C',1);            
            $this->SetXY(0,43);
            $this->Ln();
            $this->SetWidths($this->wi);
            $this->Ln();
            $this->SetWidths($this->wi);
        }
    }

    public function Footer() {
        switch ($this->opcion_pie) 
        {
            case 'FOOTER_VACIO':

                break;
            case 'SOLO_NUMERACION':
                    $this->SetY(-15);
                    $this->SetFont('Arial', 'I', 8);
                    $this->SetTextColor(0);
                    $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
                break;

            case 'PIE_COTIZACION':

                  $this->SetY(-50);
                    $this->SetFont('Arial','B',9);
                    $this->Cell(100,4,utf8_decode('Términos & Condiciones:'),0,0,'L',1); 
                    $this->Cell(25,4,utf8_decode('Elaborado por: '),0,0,'L',1);        
                    $this->SetFont('Arial','',9);
                    $this->Cell(75,4,utf8_decode($this->nombre_completo_user),0,1,'L',1);
                    $this->SetFont('Arial','',9);
                    $this->Cell(50,4,utf8_decode('-Esta cotización tiene una validez de 2 días'),0,1,'L',1); 
                    $this->Cell(50,4,utf8_decode('-Forma de pago: Efectivo - Transferencia - Cheque'),0,1,'L',1); 
                    $this->Cell(50,4,utf8_decode('-Tiempo de entrega: 24 Hrs'),0,1,'L',1); 
                    $this->Cell(50,4,utf8_decode('-Lugar de entrega: Almacenes'),0,1,'L',1); 
                    $this->Cell(50,4,utf8_decode('-Este documento no es válido para efectos fiscales'),0,1,'L',1); 
                    $this->Cell(50,4,utf8_decode('-Por favor lea atentamente su orden'),0,1,'L',1); 
                    $this->SetY(-15);
                    $this->SetFont('Arial', 'I', 8);
                    $this->SetTextColor(0);
                    $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
                break;

            case 'PIE_OPERACIONES':

                  $this->SetY(-85);
                    
                     $this->SetFont('Arial','B',9);
        
                     $this->Cell(35,4,utf8_decode('Elaborado por: '),0,0,'L',1);        
                     $this->SetFont('Arial','',9);
                     $this->Cell(75,4,utf8_decode($this->nombre_completo_user),0,1,'L',1);
                     $this->Ln(3);

                    $this->SetFont('Arial','B',9);
                    $this->SetTextColor(6, 11, 105);
                    $this->Cell(35,4,utf8_decode('Dirección de Envío:'),0,0,'L',1);    
                    $this->SetFillColor(241,224,147);   
                    $this->SetTextColor(55); 
                    $this->Cell(160,4,$this->direccion,0,1,'L',1); 
                    $this->SetFillColor(255,255,255); 
                    $this->SetTextColor(6, 11, 105);        
                    $this->Ln(2);


                    $this->SetTextColor(6, 11, 105);
                    $this->Cell(35,4,utf8_decode('Persona de contacto:'),0,0,'L',1);    
                    $this->SetFillColor(241,224,147);   
                    $this->SetTextColor(55); 
                    $this->Cell(80,4,$this->cliente,0,0,'L',1); 

                    $this->SetTextColor(6, 11, 105);
                    $this->SetFillColor(255,255,255); 
                    $this->Cell(15,4,utf8_decode('Celular:'),0,0,'L',1);    
                    $this->SetFillColor(241,224,147);   
                    $this->SetTextColor(55); 
                    $this->Cell(65,4,$this->celular,0,1,'L',1); 
                    $this->SetFillColor(255,255,255); 
                    $this->SetTextColor(6, 11, 105);        
                    $this->Ln(2);
                    $this->Cell(100,4,utf8_decode('Recibi Conforme (Cliente o responsable de recepción):'),0,1,'L',1);                
                    $this->Ln(3);
                    $this->SetTextColor(55); 
                    $this->Cell(130,4,utf8_decode('Señor(a):...........................................................................................................................'),0,0,'L',1);  

                    $this->Cell(30,4,utf8_decode('Nro. de CI: ...........................................'),0,1,'L',1);  

                    $this->Ln(3);
                    $this->Cell(130,4,utf8_decode('Lugar y Fecha:  ...............................................................................................................'),0,1,'L',1); 
                    $this->Ln(1);
                    $this->SetFont('Arial','B',7);
                    $y = $this->GetY();
                    $this->SetXY(10,$y);
                    $this->MultiCell(140,3,utf8_decode('Nota: La persona responsable de la recepción da fe y conformidad del material y/o bienes entregados, no habiendo observaciones posterior a la firma, y mucho menos cambios o devoluciones de material.'),0,0,'J',1); 
                    $this->SetFont('Arial','B',9);
                    $this->SetXY(160,$y);
                    $this->MultiCell(40,12,utf8_decode('Firma (Recepción)'),0,0,'C',1); 

                    $this->Ln(-1);
                    $this->Cell(100,4,utf8_decode('Entregue Conforme (Responsable de Entrega:'),0,1,'L',1);                
                    $this->Ln(3);
                    $this->SetTextColor(55); 
                    $this->Cell(130,4,utf8_decode('Nombre Completo: .........................................................................................................'),0,0,'L',1);  

                    $this->Cell(30,4,utf8_decode('Nro. de CI: ...........................................'),0,1,'L',1);  

                    $this->Ln(5);
                    $this->Cell(130,4,utf8_decode('Cargo: .............................................................................................................................'),0,0,'L',1);  

                    $this->Cell(30,4,utf8_decode('Firma (Entrega): .................................'),0,1,'L',1);    


                    $this->SetY(-15);
                    $this->SetFont('Arial', 'I', 8);
                    $this->SetTextColor(0);
                    $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
                break;


            default:
                    $this->SetY(-22);
                    $fecha_hoy = $this->fechacompleta2();
                    $this->SetFont('Arial', 'B', 8);
                    $this->SetTextColor(0);
                    $this->Cell(0, 10, $fecha_hoy, 0, 0, 'L');
                    $this->SetY(-15);
                    $this->SetFont('Arial', 'I', 8);
                    $this->SetTextColor(0);
                    $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
                break;
        }

    }

    public function Footer_vacio() {

    }
     function TextWithRotation($x, $y, $txt, $txt_angle, $font_angle=0)
    {
        $font_angle+=90+$txt_angle;
        $txt_angle*=M_PI/180;
        $font_angle*=M_PI/180;

        $txt_dx=cos($txt_angle);
        $txt_dy=sin($txt_angle);
        $font_dx=cos($font_angle);
        $font_dy=sin($font_angle);

        $s=sprintf('BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET',$txt_dx,$txt_dy,$font_dx,$font_dy,$x*$this->k,($this->h-$y)*$this->k,$this->_escape($txt));
        if ($this->ColorFlag)
            $s='q '.$this->TextColor.' '.$s.' Q';
        $this->_out($s);
    }
    function AjustaCelda($ancho, $h = 0, $txt = '', $border = 0, $ln = 0, $align = '', $fill = false, $link = '', $scale = false, $force = true) {
        $TamanoInicial = $this->FontSizePt;
        $TamanoLetra = $this->FontSizePt;
        $Decremento = 0.5;
        while ($this->GetStringWidth($txt) > $ancho)
            $this->SetFontSize($TamanoLetra -= $Decremento);
        $this->Cell($ancho, $h, $txt, $border, $ln, $align, $fill, $link, $scale, $force);
        $this->SetFontSize($TamanoInicial);
    }

    function SetFontSize($size) {
        if ($this->FontSizePt == $size)
            return;
        $this->FontSizePt = $size;
        $this->FontSize = $size / $this->k;
        if ($this->page > 0)
            $this->_out(sprintf('BT /F%d %.2F Tf ET', $this->CurrentFont['i'], $this->FontSizePt));
    }

/** @var fransc  * */
var $widths;
var $aligns;
var $ah;
var $aw;
var $ax;
var $ay;
function SetWidths($w)
{
    //Set the array of column widths
    $this->widths=$w;
}
function SetAligns($a)
{
    //Set the array of column alignments
    $this->aligns=$a;
}
function SetFills($f)
{
    //Set the array of column alignments
    $this->fills=$f;
}
function SetBorders($b)
{
    //Set the array of column alignments
    $this->borders=$b;
}

function Get_x(){
    return $this->ax;
}
function Get_y(){
    return $this->ay;
}
function Get_w(){
    return $this->aw;
}
function Get_h(){
    return $this->ah;
}
function WriteTable($tcolums)
    {
        // go through all colums
        for ($i = 0; $i < sizeof($tcolums); $i++)
        {
            //para centra un poco la tabla
            $this->Cell(2);//10
            $current_col = $tcolums[$i];
            $height = 0;

            // get max height of current col
            $nb=0;
            for($b = 0; $b < sizeof($current_col); $b++)
            {
                // set style
                $this->SetFont($current_col[$b]['font_name'], $current_col[$b]['font_style'], $current_col[$b]['font_size']);
                $color = explode(",", $current_col[$b]['fillcolor']);
                $this->SetFillColor($color[0], $color[1], $color[2]);
                $color = explode(",", $current_col[$b]['textcolor']);
                $this->SetTextColor($color[0], $color[1], $color[2]);
                $color = explode(",", $current_col[$b]['drawcolor']);
                $this->SetDrawColor($color[0], $color[1], $color[2]);
                $this->SetLineWidth($current_col[$b]['linewidth']);

                $nb = max($nb, $this->NbLines($current_col[$b]['width'], $current_col[$b]['text']));
                $height = $current_col[$b]['height'];
            }
            $h=$height*$nb;


            // Issue a page break first if needed
            $this->CheckPageBreak($h);

            // Draw the cells of the row
            for($b = 0; $b < sizeof($current_col); $b++)
            {
                $w = $current_col[$b]['width'];
                $a = $current_col[$b]['align'];

                // Save the current position
                $x=$this->GetX();
                $y=$this->GetY();

                // set style
                $this->SetFont($current_col[$b]['font_name'], $current_col[$b]['font_style'], $current_col[$b]['font_size']);
                $color = explode(",", $current_col[$b]['fillcolor']);
                $this->SetFillColor($color[0], $color[1], $color[2]);
                $color = explode(",", $current_col[$b]['textcolor']);
                $this->SetTextColor($color[0], $color[1], $color[2]);
                $color = explode(",", $current_col[$b]['drawcolor']);
                $this->SetDrawColor($color[0], $color[1], $color[2]);
                $this->SetLineWidth($current_col[$b]['linewidth']);

                $color = explode(",", $current_col[$b]['fillcolor']);
                $this->SetDrawColor($color[0], $color[1], $color[2]);


                // Draw Cell Background
                $this->Rect($x, $y, $w, $h, 'FD');

                $color = explode(",", $current_col[$b]['drawcolor']);
                $this->SetDrawColor($color[0], $color[1], $color[2]);

                // Draw Cell Border
                if (substr_count($current_col[$b]['linearea'], "T") > 0)
                {
                    $this->Line($x, $y, $x+$w, $y);
                }

                if (substr_count($current_col[$b]['linearea'], "B") > 0)
                {
                    $this->Line($x, $y+$h, $x+$w, $y+$h);
                }

                if (substr_count($current_col[$b]['linearea'], "L") > 0)
                {
                    $this->Line($x, $y, $x, $y+$h);
                }

                if (substr_count($current_col[$b]['linearea'], "R") > 0)
                {
                    $this->Line($x+$w, $y, $x+$w, $y+$h);
                }


                // Print the text
                $this->MultiCell($w, $current_col[$b]['height'], $current_col[$b]['text'], 0, $a, 0);

                // Put the position to the right of the cell
                $this->SetXY($x+$w, $y);
            }

            // Go to the next line
            $this->Ln($h);
        }
    }
function Row($data,$code=false,$fills='',$fh='')
{
    //Calculate the height of the row
    $nb=0;
    for($i=0;$i<count($data);$i++)
        $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
    if ($fh==""){
        $h=3*$nb;
    }else{
        $h=3*$nb;
        if ($h < $fh)
            $h = $fh;
    }
    //Issue a page break first if needed
    $this->CheckPageBreak($h);
    //Draw the cells of the row
    for($i=0;$i<count($data);$i++)
    {
        $w=$this->widths[$i];
        $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
        //Save the current position
        $x=$this->GetX();
        $y=$this->GetY();
        //Draw the border
        $ax=$x; $ay=$y; $aw=$w; $ah=$h;
        $this->Rect($x,$y,$w,$h,$fills);
        //Print the text
        $this->MultiCell($w,3,$data[$i],0,$a);
        //Put the position to the right of the cell
        $this->SetXY($x+$w,$y);
    }
    //Go to the next line
    $this->Ln($h);
}

function Row_General($data,$code=false,$fills='',$fh='') //fh: Alto de fila
{
    //Calculate the height of the row
    $nb=0;
    for($i=0;$i<count($data);$i++)
        $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
    if ($fh=="")
        $fh=4;
    
    $h=$fh*$nb;
    if ($h < $fh)
        $h = $fh;
    
    //Issue a page break first if needed
    $this->CheckPageBreak($h);
    //Draw the cells of the row
    for($i=0;$i<count($data);$i++)
    {
        $w=$this->widths[$i];
        $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
        $f=$this->fills[$i];
        $b=isset($this->borders[$i]) ? $this->borders[$i] : '1';
        //Save the current position
        $x=$this->GetX();
        $y=$this->GetY();
        //Draw the border
        $ax=$x; $ay=$y; $aw=$w; $ah=$h;
        $this->Rect($x,$y,$w,$h,$fills);
        //Print the text
        $this->MultiCell($w,$fh,$data[$i],$b,$a,$f);
        //Put the position to the right of the cell
        $this->SetXY($x+$w,$y);
    }
    //Go to the next line
    $this->Ln($h);
}

function Row_Entidad($data,$code=false,$fills='',$fh='')
{
    //Calculate the height of the row
    $nb=0;
    for($i=0;$i<count($data);$i++)
        $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
    if ($fh==""){
        $h=4*$nb;
    }else{
        $h=4*$nb;
        if ($h < $fh)
            $h = $fh;
    }
    //Issue a page break first if needed
    $this->CheckPageBreak($h);
    //Draw the cells of the row
    for($i=0;$i<count($data);$i++)
    {
        $w=$this->widths[$i];
        $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
        //Save the current position
        $x=$this->GetX();
        $y=$this->GetY();
        //Draw the border
        $ax=$x; $ay=$y; $aw=$w; $ah=$h;
        $this->Rect($x,$y,$w,$h,$fills);
        //Print the text
        $this->MultiCell($w,4,$data[$i],0,$a);
        //Put the position to the right of the cell
        $this->SetXY($x+$w,$y);
    }
    //Go to the next line
    $this->Ln($h);
}

function Row_Reportes($data,$code=false,$fills='',$fh='')
{
    //Calculate the height of the row
    $nb=0;
    for($i=0;$i<count($data);$i++)
        $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
    if ($fh==""){
        $h=4*$nb;
    }else{
        $h=4*$nb;
        if ($h < $fh)
            $h = $fh;
    }
    //Issue a page break first if needed
    $this->CheckPageBreak($h);
    //Draw the cells of the row
    for($i=0;$i<count($data);$i++)
    {
        $w=$this->widths[$i];
        $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
        //Save the current position
        $x=$this->GetX();
        $y=$this->GetY();
        //Draw the border
        $ax=$x; $ay=$y; $aw=$w; $ah=$h;
        $this->Rect($x,$y,$w,$h,$fills);
        //Print the text
        $this->MultiCell($w,4,$data[$i],0,$a);
        //Put the position to the right of the cell
        $this->SetXY($x+$w,$y);
    }
    //Go to the next line
    $this->Ln($h);
}

function RowHeader($data,$code=false,$fills='',$fh='')
{
    //Calculate the height of the row
    $nb=0;
    for($i=0;$i<count($data);$i++)
        $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
    if ($fh==""){
        $h=3*$nb;
    }else{
        $h=3*$nb;
        if ($h < $fh)
            $h = $fh;
    }
    //Issue a page break first if needed
    $this->CheckPageBreak($h);
    //Draw the cells of the row
    for($i=0;$i<count($data);$i++)
    {
        $w=$this->widths[$i];
        $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';
        //Save the current position
        $x=$this->GetX();
        $y=$this->GetY();
        //Draw the border
        $ax=$x; $ay=$y; $aw=$w; $ah=$h;
        $this->Rect($x,$y,$w,$h,$fills);
        //Print the text
        $this->MultiCell($w,3,$data[$i],0,$a);
        //Put the position to the right of the cell
        $this->SetXY($x+$w,$y);
    }
    //Go to the next line
    $this->Ln($h);
}

function CheckPageBreak($h)
{
    //If the height h would cause an overflow, add a new page immediately
    if($this->GetY()+$h>$this->PageBreakTrigger)
        $this->AddPage($this->CurOrientation);
}

function NbLines($w,$txt)
{
    //Computes the number of lines a MultiCell of width w will take
    $cw=&$this->CurrentFont['cw'];
    if($w==0)
        $w=$this->w-$this->rMargin-$this->x;
    $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
    $s=str_replace("\r",'',$txt);
    $nb=strlen($s);
    if($nb>0 and $s[$nb-1]=="\n")
        $nb--;
    $sep=-1;
    $i=0;
    $j=0;
    $l=0;
    $nl=1;
    while($i<$nb)
    {
        $c=$s[$i];
        if($c=="\n")
        {
            $i++;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
            continue;
        }
        if($c==' ')
            $sep=$i;
        $l+=$cw[$c];
        if($l>$wmax)
        {
            if($sep==-1)
            {
                if($i==$j)
                    $i++;
            }
            else
                $i=$sep+1;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
        }
        else
            $i++;
    }
    return $nl;
}


function RoundedRect($x, $y, $w, $h, $r, $style = '')
    {
        $k = $this->k;
        $hp = $this->h;
        if($style=='F')
            $op='f';
        elseif($style=='FD' || $style=='DF')
            $op='B';
        else
            $op='S';
        $MyArc = 4/3 * (sqrt(2) - 1);
        $this->_out(sprintf('%.2F %.2F m',($x+$r)*$k,($hp-$y)*$k ));
        $xc = $x+$w-$r ;
        $yc = $y+$r;
        $this->_out(sprintf('%.2F %.2F l', $xc*$k,($hp-$y)*$k ));

        $this->_Arc($xc + $r*$MyArc, $yc - $r, $xc + $r, $yc - $r*$MyArc, $xc + $r, $yc);
        $xc = $x+$w-$r ;
        $yc = $y+$h-$r;
        $this->_out(sprintf('%.2F %.2F l',($x+$w)*$k,($hp-$yc)*$k));
        $this->_Arc($xc + $r, $yc + $r*$MyArc, $xc + $r*$MyArc, $yc + $r, $xc, $yc + $r);
        $xc = $x+$r ;
        $yc = $y+$h-$r;
        $this->_out(sprintf('%.2F %.2F l',$xc*$k,($hp-($y+$h))*$k));
        $this->_Arc($xc - $r*$MyArc, $yc + $r, $xc - $r, $yc + $r*$MyArc, $xc - $r, $yc);
        $xc = $x+$r ;
        $yc = $y+$r;
        $this->_out(sprintf('%.2F %.2F l',($x)*$k,($hp-$yc)*$k ));
        $this->_Arc($xc - $r, $yc - $r*$MyArc, $xc - $r*$MyArc, $yc - $r, $xc, $yc - $r);
        $this->_out($op);
    }

    function _Arc($x1, $y1, $x2, $y2, $x3, $y3)
    {
        $h = $this->h;
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c ', $x1*$this->k, ($h-$y1)*$this->k,
            $x2*$this->k, ($h-$y2)*$this->k, $x3*$this->k, ($h-$y3)*$this->k));
    }

    function fecha() {
        $mes =  array('enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre');
        return;
    }
    function fechacompleta(){
       $fecha = date('Y-m-j');
      //  $nuevafecha = strtotime ( '-4 hour' , strtotime ( $fecha ) ) ;
        //$fecha = date ( 'Y-m-j H:i:s' , $nuevafecha );
        return  $fecha;
      

      /*  $GetD = getdate();
        $verd = array(
        1=>"Lunes",2=>"Martes",3=>"Mi&eacute;rcoles",4=>"Jueves",5=>"Viernes",6=>"Sábado",7=>"Domingo"
        );
        $verm = array(
        1=>"Enero",2=>"Febrero",3=>"Marzo",4=>"Abril",5=>"Mayo",6=>"Junio",7=>"Julio",
            8=>"Agosto",9=>"Septiembre",10=>"Octubre",11=>"Noviembre",12=>"Diciembre"
        );
        //return $verd[$GetD['wday']].", ".$GetD['mday']." de ".$verm[$GetD['mon']]." del ".$GetD['year'];
        return " ".$GetD['mday']." de ".$verm[$GetD['mon']]." de ".$GetD['year']."  Hora:  ".$GetD['hours'].":".$GetD['minutes'].":".$GetD['seconds'];*/
    }

    function fechacompleta2(){
        
        date_default_timezone_set("America/La_Paz");
        $GetD = getdate();
        $mifecha = new DateTime(); 
        //$mifecha->modify('-55 minute');
        $hora = $mifecha->format('H:i:s');

       // $hora = date("H:i:s");
        $verd = array(
            1=>"Lunes",2=>"Martes",3=>"Mi&eacute;rcoles",4=>"Jueves",5=>"Viernes",6=>"Sábado",7=>"Domingo"
        );
        $verm = array(
            1=>"Enero",2=>"Febrero",3=>"Marzo",4=>"Abril",5=>"Mayo",6=>"Junio",7=>"Julio",
            8=>"Agosto",9=>"Septiembre",10=>"Octubre",11=>"Noviembre",12=>"Diciembre"
        );
        $titulo = utf8_decode("Fecha Impresión: ");
        return $titulo.$GetD['mday']." de ".$verm[$GetD['mon']]." de ".$GetD['year'].",  Hora: ".$hora."       Sistema de ventas SOFTALDI contactos al whatsapp 75540732";;
    }

    function fechayhoracompleta(){
        $GetD = getdate();

        $mifecha = new DateTime(); 
        //$mifecha->modify('-55 minute');
        $fechayhora = $mifecha->format('d-m-Y H:i:s');

        return "Fecha: ".$fechayhora;
    }


    function RowTitle($data) {
        //Calculate the height of the row
        $nb = 0;
        for ($i = 0; $i < count($data); $i++)
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        $h = 2.5 * $nb;
        //Issue a page break first if needed
        $this->CheckPageBreak($h);
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 8);
        //Draw the cells of the row
        for ($i = 0; $i < count($data); $i++) {
            $w = $this->widths[$i];
            $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
            //Save the current position
            $x = $this->GetX();
            $y = $this->GetY();
            //Draw the border
            $this->Rect($x, $y, $w, $h, 'FD');
            //Print the text
            $this->MultiCell($w, 5, $data[$i], 0, 'C');
            //Put the position to the right of the cell
            $this->SetXY($x + $w, $y);
        }
        //Go to the next line
        $this->Ln($h - 5);
    }
    function RowWell($data) {
        //Calculate the height of the row
        $nb = 0;
        for ($i = 0; $i < count($data); $i++)
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        $h = 2.5 * $nb;
        //Issue a page break first if needed
        $this->CheckPageBreak($h);
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 7);
        //Draw the cells of the row
        for ($i = 0; $i < count($data); $i++) {
            $w = $this->widths[$i];
            $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
            //Save the current position
            $x = $this->GetX();
            $y = $this->GetY();
            //Draw the border
            $this->Rect($x, $y, $w, $h, 'FD');
            //Print the text
            $this->MultiCell($w, 2.5, $data[$i], 0, 'C');
            //Put the position to the right of the cell
            $this->SetXY($x + $w, $y);
        }
        //Go to the next line
        $this->Ln($h+1);
    }

    function SetDash($black=false, $white=false)
    {
        if($black and $white)
            $s=sprintf('[%.3f %.3f] 0 d', $black*$this->k, $white*$this->k);
        else
            $s='[] 0 d';
        $this->_out($s);
    }



}

?>