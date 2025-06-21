<?php
ini_set("allow_url_fopen", 1);

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once APPPATH . "/libraries/fpdf_rrhh/fpdf/fpdf.php";
// include_once  "fpdf/easyTable.php";

class Pdf2 extends FPDF {

    public $xheader;
    public $yheader;
    public $anchoheader = 205; 
    public $cabecera;
    public $tituloCabecera;
    public $gestion;
    public $opcion_cabecera;
    public $opcion_pie;
    public $nombreServidor;

    public $subTitulo;
    public $otroSubTitulo;
    public $fecha;
    public $fechaini;
    public $fechafin; 

    private $encabezado;
    private $wi;
    private $cds220;
    private $rubro;


    public $marcaDeAguaDeclaracion;

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

        //OPCIÓN PARA LA PROGRAMACIÓN DE VACACIONES
        if($this->opcion_cabecera==1)
        {
            //$this->Image('resources/images/logos/200BicentenarioBolivia_Vectores-9.png', 10, 6, 50);
            $this->Image('resources/images/logos/200BicentenarioBoliviavertical.png', 18, 6, 20);
            $this->Image('resources/images/logos/logo_senape_reporte.png', 100, 6, 73);
            $this->Image('resources/images/logos/chakana.png', 225, 4, 42);
            $this->SetTextColor(0);
            $this->SetFont('Times','B',14);
            $this->SetY(23);
            $this->Cell(0,0,utf8_decode($this->tituloCabecera),0,1,'C',0);
            $this->Ln(6);

            $this->SetFont('Times','B',12);
            //encabezado grilla
            $this->Cell(0,0,utf8_decode($this->subTitulo),0,1,'C',0);
            $this->Ln();

            $this->SetFont('Arial','B',10);
            $this->SetXY(94,34);
            $this->SetFillColor(200,200,200);
            $this->SetTextColor(0);
            $this->Cell(168,7,utf8_decode('GESTIÓN: '.$this->gestion),1,0,'C',1);
            $this->Ln(8);

            //Cabecera
            $this->SetFillColor(31,73,125);
            $this->SetTextColor(255);
            $this->SetFont('Arial','B',7);

            $this->MultiCell(33,10,utf8_decode('Nombres y Apellidos'),1,'C',1);
            $this->SetXY(38, 42);
            $this->MultiCell(14,5,utf8_decode('Fecha Ingreso'),1,'C',1);
            $this->SetFont('Arial','B',6);
            $this->SetXY(52,42);
            $this->MultiCell(14,5,utf8_decode('Saldo al 31/12/'.($this->gestion-1)),1,'C',1);
            $this->SetXY(66,42);
            $this->MultiCell(14,5,utf8_decode('Días a ganar '.$this->gestion),1,'C',1);
            $this->SetXY(80,42);
            $this->MultiCell(14,5,utf8_decode('Total Días a Programar'),1,'C',1);
            $this->SetXY(94,42);
            $this->Cell(14,5,utf8_decode('Enero'),1,0,'C',1);
            $this->Cell(14,5,utf8_decode('Febrero'),1,0,'C',1);
            $this->Cell(14,5,utf8_decode('Marzo'),1,0,'C',1);
            $this->Cell(14,5,utf8_decode('Abril'),1,0,'C',1);
            $this->Cell(14,5,utf8_decode('Mayo'),1,0,'C',1);
            $this->Cell(14,5,utf8_decode('Junio'),1,0,'C',1);
            $this->Cell(14,5,utf8_decode('Julio'),1,0,'C',1);
            $this->Cell(14,5,utf8_decode('Agosto'),1,0,'C',1);
            $this->Cell(14,5,utf8_decode('Septiembre'),1,0,'C',1);
            $this->Cell(14,5,utf8_decode('Octubre'),1,0,'C',1);
            $this->Cell(14,5,utf8_decode('Noviembre'),1,0,'C',1);
            $this->Cell(14,5,utf8_decode('Diciembre'),1,0,'C',1);
            $this->SetFont('Arial','B',7);
            $this->Cell(10,10,utf8_decode('TOTAL'),1,1,'C',1);
            $this->SetXY(94,47);
            for($i=1;$i<=12;$i++){
                $this->Cell(7,5,utf8_decode('DE'),1,0,'C',1);
                $this->Cell(7,5,utf8_decode('A'),1,0,'C',1);
            }
            $this->Ln();
            $this->SetWidths($this->wi);
        }

        //***** Cabecera de Ficha Personal *****//
        if($this->opcion_cabecera==2)
        {
            $this->Image('resources/images/logos/200BicentenarioBoliviavertical.png', 10, 6, 19);
            $this->Image('resources/images/logos/logo_senape_reporte.png', 70, 6, 70);
            $this->Image('resources/images/logos/chakana.png', 170, 4, 40);
            $this->Ln();
            $this->SetTextColor(0);
            $this->SetFont('Times','B',14);
            $this->SetY(23);
            $this->Cell(0,0,utf8_decode($this->tituloCabecera),0,1,'C',0);
            $this->Ln(6);
            $this->SetFont('Times','B',12);
            $this->Cell(0,0,utf8_decode($this->subTitulo),0,1,'C',0);
            $this->Ln(5);
        }

        //***** Cabecera de Ficha Personal *****//
        if($this->opcion_cabecera=='AcuerdoConfidencialidad')
        {
            $this->SetTextColor(0);
            $this->SetFont('Times','B',14);
            $this->SetY(23);
            $this->Cell(180,0,utf8_decode($this->tituloCabecera),0,1,'C',0);
            $this->Ln(5);
        }

        //***** Cabecera de Datos Por Género *****//
        if($this->opcion_cabecera=='ReporteGenero')
        {
            
            $this->Image('resources/images/logos/200BicentenarioBoliviavertical.png', 10, 6, 19);
            $this->Image('resources/images/logos/logo_senape_reporte.png', 70, 6, 70);
            $this->Image('resources/images/logos/chakana.png', 170, 4, 40);
            $this->Ln();
            $this->SetTextColor(0);
            $this->SetFont('Times','B',14);
            $this->SetY(26);
            $this->Cell(0,0,utf8_decode($this->tituloCabecera),0,1,'C',0);
            $this->Ln(5);

            //Cabecera
            $this->SetFillColor(222,222,222);
            $this->SetTextColor(0);
            $this->SetFont('Arial','B',8);
            $this->Cell(10,10,utf8_decode('Nro'),1,0,'C',1);
            $this->Cell(35,10,utf8_decode('PRIMER APELLIDO'),1,0,'C',1);
            $this->Cell(35,10,utf8_decode('SEGUNDO APELLIDO'),1,0,'C',1);
            $this->Cell(35,10,utf8_decode('NOMBRES'),1,0,'C',1);
            $y=$this->GetY();
            $this->MultiCell(30,5,utf8_decode('NRO DE CÉDULA DE IDENTIDAD'),1,'C',1);
            $this->SetXY(155,$y);
            $this->Cell(10,10,utf8_decode('EXT.'),1,0,'C',1);
            $this->Cell(20,10,utf8_decode('GÉNERO'),1,0,'C',1);
            $this->Cell(15,10,utf8_decode('EDAD'),1,0,'C',1);
            $this->Ln();
            $this->SetWidths($this->wi);
        }

        //***** Cabecera de Datos Por Lugar de Nacimiento *****//
        if($this->opcion_cabecera=='ReporteLugarNacimiento')
        {
            $this->Image('resources/images/logos/200BicentenarioBoliviavertical.png', 18, 6, 20);
            $this->Image('resources/images/logos/logo_senape_reporte.png', 100, 6, 73);
            $this->Image('resources/images/logos/chakana.png', 225, 4, 42);
            $this->Ln();
            $this->SetTextColor(0);
            $this->SetFont('Times','B',14);
            $this->SetY(26);
            $this->Cell(0,0,utf8_decode($this->tituloCabecera),0,1,'C',0);
            $this->Ln(5);

            //Cabecera
            $this->SetFillColor(222,222,222);
            $this->SetTextColor(0);
            $this->SetFont('Arial','B',8);
            $this->Cell(8,10,utf8_decode('Nro'),1,0,'C',1);
            $this->Cell(30,10,utf8_decode('PRIMER APELLIDO'),1,0,'C',1);
            $this->Cell(30,10,utf8_decode('SEGUNDO APELLIDO'),1,0,'C',1);
            $this->Cell(30,10,utf8_decode('NOMBRES'),1,0,'C',1);
            $y=$this->GetY();
            $this->MultiCell(20,5,utf8_decode('Nro Cédula de Identidad'),1,'C',1);
            $this->SetXY(128,$y);
            $this->Cell(10,10,utf8_decode('EXT.'),1,0,'C',1);
            $this->Cell(17,10,utf8_decode('GÉNERO'),1,0,'C',1);
            $this->Cell(15,10,utf8_decode('EDAD'),1,0,'C',1);
            $this->MultiCell(25,5,utf8_decode('FECHA DE NACIMIENTO'),1,'C',1);
            $this->SetXY(195,$y);
            $this->Cell(75,5,utf8_decode('LUGAR DE NACIMIENTO'),1,0,'C',1);
            $this->SetXY(195,$y+5);
            $this->Cell(25,5,utf8_decode('Departamento'),1,0,'C',1);
            $this->Cell(25,5,utf8_decode('Provincia'),1,0,'C',1);
            $this->Cell(25,5,utf8_decode('Municipio'),1,0,'C',1);
            $this->Ln();
            $this->SetWidths($this->wi);
        }

        //***** Cabecera de Datos Por formación Académica *****//
        if($this->opcion_cabecera=='ReporteFormacionAcademica')
        {
            $this->Image('resources/images/logos/200BicentenarioBoliviavertical.png', 18, 6, 20);
            $this->Image('resources/images/logos/logo_senape_reporte.png', 100, 6, 73);
            $this->Image('resources/images/logos/chakana.png', 225, 4, 42);
            $this->Ln();
            $this->SetTextColor(0);
            $this->SetFont('Times','B',14);
            $this->SetY(26);
            $this->Cell(0,0,utf8_decode($this->tituloCabecera),0,1,'C',0);
            $this->Ln(5);

            //Cabecera
            $this->SetFillColor(222,222,222);
            $this->SetTextColor(0);
            $this->SetFont('Arial','B',7);
            $this->Cell(7,8,utf8_decode('Nro'),1,0,'C',1);
            $y=$this->GetY();
            $this->MultiCell(20,4,utf8_decode('PRIMER APELLIDO'),1,'C',1);
            $this->SetXY(37,$y);
            $this->MultiCell(20,4,utf8_decode('SEGUNDO APELLIDO'),1,'C',1);
            $this->SetXY(57,$y);
            $this->Cell(20,8,utf8_decode('NOMBRES'),1,0,'C',1);
            $this->Cell(20,8,utf8_decode('CARGO'),1,0,'C',1);
            $this->Cell(30,8,utf8_decode('PUESTO'),1,0,'C',1);
            $this->Cell(40,8,utf8_decode('UNIDAD / DIRECCIÓN'),1,0,'C',1);
            $this->MultiCell(18,4,utf8_decode('Nro Cédula de Identidad'),1,'C',1);
            $this->SetXY(185,$y);
            $this->Cell(7,8,utf8_decode('EXT.'),1,0,'C',1);
            $this->Cell(15,8,utf8_decode('GÉNERO'),1,0,'C',1);
            $this->Cell(15,8,utf8_decode('GRADO'),1,0,'C',1);
            $this->Cell(50,8,utf8_decode('FORMACIÓN ACADÉMICA'),1,0,'C',1);
            $this->Ln();
            $this->SetWidths($this->wi);
        }

        //***** Cabecera de Datos Por Madre / Padre *****//
        if($this->opcion_cabecera=='ReporteMadrePadre')
        {
            $this->Image('resources/images/logos/200BicentenarioBoliviavertical.png', 10, 6, 19);
            $this->Image('resources/images/logos/logo_senape_reporte.png', 61, 6, 70);
            $this->Image('resources/images/logos/chakana.png', 158, 4, 40);
            $this->Ln();
            $this->SetTextColor(0);
            $this->SetFont('Times','B',14);
            $this->SetY(26);
            $this->Cell(0,0,utf8_decode($this->tituloCabecera),0,1,'C',0);
            $this->Ln(5);

            //Cabecera
            $this->SetFillColor(222,222,222);
            $this->SetTextColor(0);
            $this->SetFont('Arial','B',8);
            $this->Cell(10,10,utf8_decode('Nro'),1,0,'C',1);
            $this->Cell(40,10,utf8_decode('PRIMER APELLIDO'),1,0,'C',1);
            $this->Cell(40,10,utf8_decode('SEGUNDO APELLIDO'),1,0,'C',1);
            $this->Cell(40,10,utf8_decode('NOMBRES'),1,0,'C',1);
            $this->Cell(25,10,utf8_decode('EDAD'),1,0,'C',1);
            $this->Cell(30,10,utf8_decode('PADRE/MADRE'),1,0,'C',1);
            $this->Ln();
            $this->SetWidths($this->wi);
        }

        //***** Cabecera de Datos Por AFP *****//
        if($this->opcion_cabecera=='ReporteAfp')
        {
            $this->Image('resources/images/logos/200BicentenarioBoliviavertical.png', 10, 6, 19);
            $this->Image('resources/images/logos/logo_senape_reporte.png', 61, 6, 70);
            $this->Image('resources/images/logos/chakana.png', 158, 4, 40);
            $this->Ln();
            $this->SetTextColor(0);
            $this->SetFont('Times','B',14);
            $this->SetY(26);
            $this->Cell(0,0,utf8_decode($this->tituloCabecera),0,1,'C',0);
            $this->Ln(5);

            //Cabecera
            $this->SetFillColor(222,222,222);
            $this->SetTextColor(0);
            $this->SetFont('Arial','B',8);
            $this->Cell(8,10,utf8_decode('Nro'),1,0,'C',1);
            $this->Cell(30,10,utf8_decode('PRIMER APELLIDO'),1,0,'C',1);
            $this->Cell(30,10,utf8_decode('SEGUNDO APELLIDO'),1,0,'C',1);
            $this->Cell(30,10,utf8_decode('NOMBRES'),1,0,'C',1);
            $y=$this->GetY();
            $this->MultiCell(25,5,utf8_decode('NRO. CÉDULA DE IDENTIDAD'),1,'C',1);
            $this->SetXY(133,$y);
            $this->Cell(10,10,utf8_decode('EXT.'),1,0,'C',1);
            $this->SetXY(143,$y);
            $this->Cell(50,5,utf8_decode('AFP (CUA/NUA)'),1,0,'C',1);
            $this->SetXY(143,$y+5);
            $this->Cell(25,5,utf8_decode('PREVISIÓN'),1,0,'C',1);
            $this->Cell(25,5,utf8_decode('FUTURO'),1,0,'C',1);
            $this->Ln();
            $this->SetWidths($this->wi);
        }

        //***** Cabecera de Datos C.A.S. *****//
        if($this->opcion_cabecera=='ReporteCas')
        {
            $this->Image('resources/images/logos/200BicentenarioBoliviavertical.png', 10, 6, 19);
            $this->Image('resources/images/logos/logo_senape_reporte.png', 70, 6, 70);
            $this->Image('resources/images/logos/chakana.png', 170, 4, 40);
            $this->Ln();
            $this->SetTextColor(0);
            $this->SetFont('Times','B',14);
            $this->SetY(26);
            $this->Cell(0,0,utf8_decode($this->tituloCabecera),0,1,'C',0);
            $this->Ln(5);

            //Cabecera
            $this->SetFillColor(222,222,222);
            $this->SetTextColor(0);
            $this->SetFont('Arial','B',8);
            $this->Cell(10,10,utf8_decode('Nro'),1,0,'C',1);
            $this->Cell(30,10,utf8_decode('PRIMER APELLIDO'),1,0,'C',1);
            $this->Cell(30,10,utf8_decode('SEGUNDO APELLIDO'),1,0,'C',1);
            $this->Cell(30,10,utf8_decode('NOMBRES'),1,0,'C',1);
            $y=$this->GetY();
            $this->MultiCell(95,5,utf8_decode('C.A.S.'),1,'C',1);
            $this->SetXY(110,$y+5);
            $this->Cell(15,5,utf8_decode('AÑOS'),1,0,'C',1);
            $this->Cell(15,5,utf8_decode('MESES'),1,0,'C',1);
            $this->Cell(15,5,utf8_decode('DÍAS'),1,0,'C',1);
            $this->Cell(25,5,utf8_decode('FECHA DE CAS'),1,0,'C',1);
            $this->Cell(25,5,utf8_decode('ESTADO CAS'),1,0,'C',1);
            $this->Ln();
            $this->SetWidths($this->wi);
        }

        //***** Cabecera de Datos Inamovilidad Laboral (Discapacidad) *****//
        if($this->opcion_cabecera=='ReporteDiscapacidad')
        {
            $this->Image('resources/images/logos/200BicentenarioBoliviavertical.png', 10, 6, 19);
            $this->Image('resources/images/logos/logo_senape_reporte.png', 70, 6, 70);
            $this->Image('resources/images/logos/chakana.png', 170, 4, 40);
            $this->Ln();
            $this->SetTextColor(0);
            $this->SetFont('Times','B',14);
            $this->SetY(26);
            $this->Cell(0,0,utf8_decode($this->tituloCabecera),0,1,'C',0);
            $this->Ln(5);

            //Cabecera
            $this->SetFillColor(222,222,222);
            $this->SetTextColor(0);
            $this->SetFont('Arial','B',8);
            $this->Cell(10,12,utf8_decode('Nro'),1,0,'C',1);
            $this->Cell(30,12,utf8_decode('PRIMER APELLIDO'),1,0,'C',1);
            $this->Cell(30,12,utf8_decode('SEGUNDO APELLIDO'),1,0,'C',1);
            $this->Cell(30,12,utf8_decode('NOMBRES'),1,0,'C',1);
            $y=$this->GetY();
            $this->MultiCell(50,6,utf8_decode('PERSONA CON DISCAPACIDAD/ PADRE-MADRE/TUTOR'),1,'C',1);
            $this->SetXY(160,$y);
            $this->MultiCell(25,6,utf8_decode('TIPO DE DISCAPACIDAD'),1,'C',1);
            $this->SetXY(185,$y);
            $this->MultiCell(25,4,utf8_decode('VIGENCIA DE CARNET DE DISCAPACIDAD'),1,'C',1);
            $this->SetWidths($this->wi);
        }

        //***** Cabecera de Datos Reporte Cursos normativa *****//
        if($this->opcion_cabecera=='ReporteCursosNormativa')
        {
            $this->Image('resources/images/logos/200BicentenarioBoliviavertical.png', 18, 6, 20);
            $this->Image('resources/images/logos/logo_senape_reporte.png', 100, 6, 73);
            $this->Image('resources/images/logos/chakana.png', 225, 4, 42);
            $this->Ln();
            $this->SetTextColor(0);
            $this->SetFont('Times','B',14);
            $this->SetY(26);
            $this->Cell(0,0,utf8_decode($this->tituloCabecera),0,1,'C',0);
            $this->Ln(5);

            //Cabecera
            $this->SetFillColor(222,222,222);
            $this->SetTextColor(0);
            $this->SetFont('Arial','B',8);
            $this->Cell(8,12,utf8_decode('Nro'),1,0,'C',1);
            $this->Cell(30,12,utf8_decode('PRIMER APELLIDO'),1,0,'C',1);
            $this->Cell(30,12,utf8_decode('SEGUNDO APELLIDO'),1,0,'C',1);
            $this->Cell(30,12,utf8_decode('NOMBRES'),1,0,'C',1);
            $this->SetFont('Arial','B',7);
            $this->Cell(40,6,utf8_decode('IDIOMA'),1,0,'C',1);
            $this->Cell(30,6,utf8_decode('LEY 1178'),1,0,'C',1);
            $this->Cell(30,6,utf8_decode('POLÍTICAS PÚBLICAS'),1,0,'C',1);
            $y=$this->GetY();
            $this->MultiCell(35,3,utf8_decode('RESPONSABILIDAD POR LA FUNCIÓN PÚBLICA'),1,'C',1);
            $this->SetXY(243,$y);
            $this->MultiCell(30,3,utf8_decode('PREVENCIÓN DE LA VIOLENCIA'),1,'C',1);
            $this->SetFont('Arial','B',6);
            $this->SetXY(108,$y+6);
            $this->MultiCell(12,3,utf8_decode('CARGA HORARIA'),1,'C',1);
            $this->SetXY(120,$y+6);
            $this->Cell(28,6,utf8_decode('INSTITUCIÓN'),1,0,'C',1);
            $this->MultiCell(12,3,utf8_decode('CARGA HORARIA'),1,'C',1);
            $this->SetXY(160,$y+6);
            $this->Cell(18,6,utf8_decode('INSTITUCIÓN'),1,0,'C',1);
            $this->MultiCell(12,3,utf8_decode('CARGA HORARIA'),1,'C',1);
            $this->SetXY(190,$y+6);
            $this->Cell(18,6,utf8_decode('INSTITUCIÓN'),1,0,'C',1);
            $this->MultiCell(12,3,utf8_decode('CARGA HORARIA'),1,'C',1);
            $this->SetXY(220,$y+6);
            $this->Cell(23,6,utf8_decode('INSTITUCIÓN'),1,0,'C',1);
            $this->MultiCell(12,3,utf8_decode('CARGA HORARIA'),1,'C',1);
            $this->SetXY(255,$y+6);
            $this->Cell(18,6,utf8_decode('INSTITUCIÓN'),1,0,'C',1);
            $this->Ln();
            $this->SetWidths($this->wi);
        }

        //***** Cabecera de Datos Asuetos *****//
        if($this->opcion_cabecera=='ReporteAsuetos')
        {
            $this->Image('resources/images/logos/200BicentenarioBoliviavertical.png', 10, 6, 19);
            $this->Image('resources/images/logos/logo_senape_reporte.png', 70, 6, 70);
            $this->Image('resources/images/logos/chakana.png', 170, 4, 40);
            $this->Ln();
            $this->SetTextColor(0);
            $this->SetFont('Times','B',14);
            $this->SetY(26);
            //$this->MultiCell(0,5,utf8_decode($this->tituloCabecera),0,'C',0);
            $this->Cell(0,0,utf8_decode("REPORTE DE ".$this->subTitulo." QUE TOMARON EL ASUETO ".$this->otroSubTitulo),0,1,'C',0);
            $this->Ln(6);
            $this->Cell(0,0,utf8_decode($this->tituloCabecera),0,1,'C',0);
            $this->Ln(5);

            //Cabecera
            $this->SetFillColor(222,222,222);
            $this->SetTextColor(0);
            $this->SetFont('Arial','B',8);
            $this->Cell(8,10,utf8_decode('Nro'),1,0,'C',1);
            $this->Cell(30,10,utf8_decode('PRIMER APELLIDO'),1,0,'C',1);
            $this->Cell(30,10,utf8_decode('SEGUNDO APELLIDO'),1,0,'C',1);
            $this->Cell(30,10,utf8_decode('NOMBRES'),1,0,'C',1);
            $this->Cell(60,10,utf8_decode('UNIDAD ORGANIZACIONAL'),1,0,'C',1);
            $y=$this->GetY();
            $this->MultiCell(40,5,utf8_decode('FECHA'),1,'C',1);
            $this->SetXY(168,$y+5);
            $this->Cell(20,5,utf8_decode('DE'),1,0,'C',1);
            $this->Cell(20,5,utf8_decode('A'),1,0,'C',1);
            $this->Ln();
            $this->SetWidths($this->wi);
        }

        /***** Cabecera de Datos Horarios Especiales ****
        if($this->opcion_cabecera=='ReporteHorariosEspeciales')
        {
            $this->Image('resources/images/logos/logo_senape_reporte.png', 10, 6, 75);
            $this->Image('resources/images/logos/chakana.png', 170, 5, 38);
            $this->Ln();
            $this->SetTextColor(0);
            $this->SetFont('Times','B',14);
            $this->SetY(26);
            //$this->MultiCell(0,5,utf8_decode($this->tituloCabecera),0,'C',0);
            $this->Cell(0,0,utf8_decode("REPORTE DE ".$this->subTitulo." QUE TOMARON EL ASUETO DEL "),0,1,'C',0);
            $this->Ln(5);
            $this->Cell(0,0,utf8_decode($this->tituloCabecera),0,1,'C',0);
            $this->Ln(5);

            //Cabecera
            $this->SetFillColor(222,222,222);
            $this->SetTextColor(0);
            $this->SetFont('Arial','B',8);
            $this->Cell(8,10,utf8_decode('Nro'),1,0,'C',1);
            $this->Cell(30,10,utf8_decode('PRIMER APELLIDO'),1,0,'C',1);
            $this->Cell(30,10,utf8_decode('SEGUNDO APELLIDO'),1,0,'C',1);
            $this->Cell(30,10,utf8_decode('NOMBRES'),1,0,'C',1);
            $this->Cell(60,10,utf8_decode('UNIDAD ORGANIZACIONAL'),1,0,'C',1);
            $y=$this->GetY();
            $this->MultiCell(40,5,utf8_decode('FECHA'),1,'C',1);
            $this->SetXY(168,$y+5);
            $this->Cell(20,5,utf8_decode('DE'),1,0,'C',1);
            $this->Cell(20,5,utf8_decode('A'),1,0,'C',1);
            $this->Ln();
            $this->SetWidths($this->wi);
        }
        */

        //***** Cabecera de Datos Cronograma de Vacaciones Mensuales *****//
        if($this->opcion_cabecera=='ReporteCronogramaVacacionesMensual')
        {
            $this->Image('resources/images/logos/200BicentenarioBoliviavertical.png', 18, 6, 20);
            $this->Image('resources/images/logos/logo_senape_reporte.png', 100, 6, 73);
            $this->Image('resources/images/logos/chakana.png', 225, 4, 42);
            $this->Ln();
            $this->SetTextColor(0);
            $this->SetFont('Times','B',14);
            $this->SetY(26);
            $this->Cell(0,0,utf8_decode($this->tituloCabecera),0,1,'C',0);
            $this->Ln(5);

            //Cabecera
            $this->SetFillColor(222,222,222);
            $this->SetTextColor(0);
            $this->SetFont('Arial','B',8);
            $this->Cell(8,10,utf8_decode('Nro'),1,0,'C',1);
            $this->Cell(30,10,utf8_decode('PRIMER APELLIDO'),1,0,'C',1);
            $this->Cell(30,10,utf8_decode('SEGUNDO APELLIDO'),1,0,'C',1);
            $this->Cell(30,10,utf8_decode('NOMBRES'),1,0,'C',1);
            $this->Cell(55,10,utf8_decode('UNIDAD ORGANIZACIONAL'),1,0,'C',1);
            $y=$this->GetY();
            $this->MultiCell(34,5,utf8_decode('FECHA'),1,'C',1);
            $this->SetXY(197,$y);
            $this->MultiCell(28,3.33,utf8_decode('  PROGRAMADO  / A CUENTA / REPROGRAMADO'),1,'C',1);
            $this->SetXY(225,$y);
            $this->MultiCell(25,5,utf8_decode('DÍA COMPLETO/ MEDIODÍA'),1,'C',1);
            $this->SetXY(250,$y);
            $this->Cell(20,10,utf8_decode('TURNO'),1,0,'C',1);
            $this->SetXY(163,$y+5);
            $this->Cell(17,5,utf8_decode('DE'),1,0,'C',1);
            $this->Cell(17,5,utf8_decode('A'),1,0,'C',1);

            $this->Ln();
            $this->SetWidths($this->wi);
        }

        //***** Cabecera de Datos de Bajas Médicas *****//
        if($this->opcion_cabecera=='ReporteBajaMedica')
        {
            $this->Image('resources/images/logos/200BicentenarioBoliviavertical.png', 10, 6, 19);
            $this->Image('resources/images/logos/logo_senape_reporte.png', 70, 6, 70);
            $this->Image('resources/images/logos/chakana.png', 170, 4, 40);
            $this->Ln();
            $this->SetTextColor(0);
            $this->SetFont('Times','B',14);
            $this->SetY(26);
            $this->Cell(0,0,utf8_decode("REPORTE DE SERVIDORES PÚBLICOS CON ".$this->otroSubTitulo),0,1,'C',0);
            $this->Ln(6);
            $this->Cell(0,0,utf8_decode($this->tituloCabecera),0,1,'C',0);
            $this->Ln(5);

            //Cabecera
            $this->SetFillColor(222,222,222);
            $this->SetTextColor(0);
            $this->SetFont('Arial','B',8);
            $this->Cell(8,10,utf8_decode('Nro'),1,0,'C',1);
            $y=$this->GetY();
            $this->MultiCell(22,5,utf8_decode('PRIMER APELLIDO'),1,'C',1);
            $this->SetXY(40,$y);
            $this->MultiCell(22,5,utf8_decode('SEGUNDO APELLIDO'),1,'C',1);
            $this->SetXY(62,$y);
            $this->Cell(30,10,utf8_decode('NOMBRES'),1,0,'C',1);
            $this->Cell(50,10,utf8_decode('UNIDAD ORGANIZACIONAL'),1,0,'C',1);
            $this->Cell(26,10,utf8_decode('TIPO BAJA'),1,0,'C',1);
            $y=$this->GetY();
            $this->MultiCell(40,5,utf8_decode('FECHA'),1,'C',1);
            $this->SetXY(168,$y+5);
            $this->Cell(20,5,utf8_decode('DE'),1,0,'C',1);
            $this->Cell(20,5,utf8_decode('A'),1,0,'C',1);
            $this->Ln();
            $this->SetWidths($this->wi);
        }

        //***** Cabecera de Datos de Comisiones de Viaje *****//
        if($this->opcion_cabecera=='ReporteComisionViaje')
        {
            $this->Image('resources/images/logos/200BicentenarioBoliviavertical.png', 10, 6, 19);
            $this->Image('resources/images/logos/logo_senape_reporte.png', 70, 6, 70);
            $this->Image('resources/images/logos/chakana.png', 170, 4, 40);
            $this->Ln();
            $this->SetTextColor(0);
            $this->SetFont('Times','B',14);
            $this->SetY(26);
            $this->Cell(0,0,utf8_decode("REPORTE DE SERVIDORES PÚBLICOS CON ".$this->otroSubTitulo),0,1,'C',0);
            $this->Ln(6);
            $this->Cell(0,0,utf8_decode($this->tituloCabecera),0,1,'C',0);
            $this->Ln(5);

            //Cabecera
            $this->SetFillColor(222,222,222);
            $this->SetTextColor(0);
            $this->SetFont('Arial','B',8);
            $this->Cell(8,10,utf8_decode('Nro'),1,0,'C',1);
            $this->Cell(30,10,utf8_decode('PRIMER APELLIDO'),1,0,'C',1);
            $this->Cell(30,10,utf8_decode('SEGUNDO APELLIDO'),1,0,'C',1);
            $this->Cell(30,10,utf8_decode('NOMBRES'),1,0,'C',1);
            $this->Cell(60,10,utf8_decode('UNIDAD ORGANIZACIONAL'),1,0,'C',1);
            $y=$this->GetY();
            $this->MultiCell(40,5,utf8_decode('FECHA'),1,'C',1);
            $this->SetXY(168,$y+5);
            $this->Cell(20,5,utf8_decode('DE'),1,0,'C',1);
            $this->Cell(20,5,utf8_decode('A'),1,0,'C',1);
            $this->Ln();
            $this->SetWidths($this->wi);
        }

        //***** Cabecera de Datos de Comisiones de Trabajo *****//
        if($this->opcion_cabecera=='ReporteComisionTrabajo')
        {
            $this->Image('resources/images/logos/200BicentenarioBoliviavertical.png', 18, 6, 20);
            $this->Image('resources/images/logos/logo_senape_reporte.png', 100, 6, 73);
            $this->Image('resources/images/logos/chakana.png', 225, 4, 42);
            $this->Ln();
            $this->SetTextColor(0);
            $this->SetFont('Times','B',14);
            $this->SetY(26);
            $this->Cell(0,0,utf8_decode("REPORTE DE SERVIDORES PÚBLICOS CON ".$this->otroSubTitulo),0,1,'C',0);
            $this->Ln(6);
            $this->Cell(0,0,utf8_decode($this->tituloCabecera),0,1,'C',0);
            $this->Ln(5);

            //Cabecera
            $this->SetFillColor(222,222,222);
            $this->SetTextColor(0);
            $this->SetFont('Arial','B',8);
            $this->Cell(8,10,utf8_decode('Nro'),1,0,'C',1);
            $this->Cell(35,10,utf8_decode('PRIMER APELLIDO'),1,0,'C',1);
            $this->Cell(35,10,utf8_decode('SEGUNDO APELLIDO'),1,0,'C',1);
            $this->Cell(35,10,utf8_decode('NOMBRES'),1,0,'C',1);
            $this->Cell(65,10,utf8_decode('UNIDAD ORGANIZACIONAL'),1,0,'C',1);
            $y=$this->GetY();
            $this->MultiCell(40,5,utf8_decode('FECHA'),1,'C',1);
            $this->SetXY(228,$y);
            $this->Cell(40,10,utf8_decode('HORARIO'),1,0,'C',1);
            $this->SetXY(188,$y+5);
            $this->Cell(20,5,utf8_decode('DE'),1,0,'C',1);
            $this->Cell(20,5,utf8_decode('A'),1,0,'C',1);
            $this->Ln();
            $this->SetWidths($this->wi);
        }

        //***** Cabecera de Datos de Permisos Particulares *****//
        if($this->opcion_cabecera=='ReportePermisosParticulares')
        {
            $this->Image('resources/images/logos/200BicentenarioBoliviavertical.png', 18, 6, 20);
            $this->Image('resources/images/logos/logo_senape_reporte.png', 100, 6, 73);
            $this->Image('resources/images/logos/chakana.png', 225, 4, 42);
            $this->Ln();
            $this->SetTextColor(0);
            $this->SetFont('Times','B',14);
            $this->SetY(26);
            $this->Cell(0,0,utf8_decode("REPORTE DE SERVIDORES PÚBLICOS CON ".$this->otroSubTitulo),0,1,'C',0);
            $this->Ln(6);
            $this->Cell(0,0,utf8_decode($this->tituloCabecera),0,1,'C',0);
            $this->Ln(5);

            //Cabecera
            $this->SetFillColor(222,222,222);
            $this->SetTextColor(0);
            $this->SetFont('Arial','B',8);
            $this->Cell(8,10,utf8_decode('Nro'),1,0,'C',1);
            $this->Cell(30,10,utf8_decode('PRIMER APELLIDO'),1,0,'C',1);
            $this->Cell(30,10,utf8_decode('SEGUNDO APELLIDO'),1,0,'C',1);
            $this->Cell(30,10,utf8_decode('NOMBRES'),1,0,'C',1);
            $this->Cell(60,10,utf8_decode('UNIDAD ORGANIZACIONAL'),1,0,'C',1);
            $y=$this->GetY();
            $this->MultiCell(40,5,utf8_decode('FECHA'),1,'C',1);
            $this->SetXY(208,$y);
            $this->Cell(25,10,utf8_decode('TIPO PERMISO'),1,0,'C',1);
            $this->Cell(35,10,utf8_decode('TURNO'),1,0,'C',1);
            $this->SetXY(168,$y+5);
            $this->Cell(20,5,utf8_decode('DE'),1,0,'C',1);
            $this->Cell(20,5,utf8_decode('A'),1,0,'C',1);
            $this->Ln();
            $this->SetWidths($this->wi);
        }


        //***** Cabecera de Datos Resumen Asistencia *****//
        if($this->opcion_cabecera=='ReporteResumenAsistencia')
        {
            $this->Image('resources/images/logos/200BicentenarioBoliviavertical.png', 10, 6, 19);
            $this->Image('resources/images/logos/logo_senape_reporte.png', 70, 6, 70);
            $this->Image('resources/images/logos/chakana.png', 170, 4, 40);
            $this->Ln();
            $this->SetTextColor(0);
            $this->SetFont('Times','B',14);
            $this->SetY(26);
            $this->Cell(0,0,utf8_decode($this->tituloCabecera),0,1,'C',0);
            $this->Ln(5);

            //Cabecera
            $this->SetFillColor(222,222,222);
            $this->SetTextColor(0);
            $this->SetFont('Arial','B',6);
            $this->Cell(7,10,utf8_decode('Nro'),1,0,'C',1);
            $this->Cell(8,10,utf8_decode('ITEM'),1,0,'C',1);
            $this->Cell(22,10,utf8_decode('PRIMER APELLIDO'),1,0,'C',1);
            $this->Cell(24,10,utf8_decode('SEGUNDO APELLIDO'),1,0,'C',1);
            $this->Cell(25,10,utf8_decode('NOMBRES'),1,0,'C',1);
            $this->Cell(14,10,utf8_decode('DIRECCIÓN'),1,0,'C',1);
            $this->SetFont('Arial','B',4.5);
            $y=$this->GetY();
            $this->MultiCell(10,3.33,utf8_decode('Días Hábiles del Mes'),1,'C',1);
            $this->SetXY(120,$y);
            $this->MultiCell(11,3.33,utf8_decode('Medios Días Trabajados'),1,'C',1);
            $this->SetXY(131,$y);
            $this->MultiCell(11,3.33,utf8_decode('Días con Permisos/ Vacaciones'),1,'C',1);
            $this->SetXY(142,$y);
            $this->MultiCell(11,3.33,utf8_decode('Días No Trabajados en el Mes'),1,'C',1);
            $this->SetXY(153,$y);
            $this->MultiCell(10,3.33,utf8_decode('Días en Comisión de Viaje'),1,'C',1);
            $this->SetXY(163,$y);
            $this->MultiCell(11,3.33,utf8_decode('Días Completos Trabajados'),1,'C',1);
            $this->SetXY(174,$y);
            $this->SetFont('Arial','B',5);
            $this->MultiCell(14,5,utf8_decode('MONTO (Bs.) REFRIGERIO'),1,'C',1);
            $this->SetXY(188,$y);
            $this->Cell(18,10,utf8_decode('ESTADO'),1,0,'C',1);
            $this->Ln();
            $this->SetWidths($this->wi);
        }

        //***** Cabecera Vacía *****//
        if($this->opcion_cabecera==3)
        {
            //Sin cabecera
        }

        //***** Cabecera con solo Logos *****//
        if($this->opcion_cabecera==4)
        {
            $this->Image('resources/images/logos/200BicentenarioBoliviavertical.png', 10, 6, 19);
            $this->Image('resources/images/logos/logo_senape_reporte.png', 70, 6, 70);
            $this->Image('resources/images/logos/chakana.png', 170, 4, 40);
            $this->SetY(20);
            //$this->Ln();
        }
        //$this->SetWidths($this->wi);
    }

    public function Footer() {
        switch ($this->opcion_pie) {
            case 'FOOTER_VACIO':

                break;
            case 'ASISTENCIA_NO_CONSOLIDADA':
                    $this->SetY(-22);
                    $fecha_hoy = $this->fechacompleta2();
                    $this->SetFont('Arial', 'B', 8);
                    $this->SetTextColor(0);
                    $this->Cell(0, 10, $fecha_hoy, 0, 0, 'L');
                    $this->SetY(-15);
                    $this->SetFont('Arial', 'I', 8);
                    $this->SetTextColor(0);
                    $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
                    $this->SetFont('Arial','',55);
                    $this->SetTextColor(200);
                    $this->TextWithRotation(20,260,utf8_decode('ASISTENCIA NO CONSOLIDADA'),50,0);
                break;
            case 'ACUERDO_CONFIDENCIALIDAD':
                    $this->SetY(-25);
                    $this->SetTextColor(0);
                    $this->SetFont('Arial','B',9);
                    $this->Cell(90,4);
                    $this->Cell(88, 4, utf8_decode($this->nombreServidor),'T', 1, 'C');
                    $this->Cell(90,4);
                    $this->SetFont('Arial','B',10);
                    $this->Cell(88, 4, utf8_decode('Firma del Servidor Público'),0, 0, 'C');
                    
                    $this->SetY(-18);
                    $this->SetFont('Arial', 'I', 8);
                    $this->SetTextColor(0);
                    $fecha_hoy = $this->fechacompleta2();
                    $this->Cell(80, 10, utf8_decode("Fecha de Impresión: ").$fecha_hoy, 0, 0, 'L');
                    $this->Cell(102, 10, utf8_decode('Página ') . $this->PageNo() . ' de {nb}', 0, 0, 'R');

                break;
            case 'FICHA_PERSONAL':
                    $this->SetY(-22);
                    $fecha_hoy = $this->fechacompleta2();
                    $this->SetFont('Arial', 'B', 8);
                    $this->SetTextColor(0);
                    //$this->Cell(0, 10, $fecha_hoy, 0, 0, 'L');
                    $this->SetY(-15);
                    $this->SetFont('Arial', 'I', 8);
                    $this->SetTextColor(0);
                    $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');

                    if($this->estadoActualizacionFicha!='FIN' && $this->estadoActualizacionFicha!='REC'){
                        $this->SetFont('Arial','B',40);
                        $this->SetTextColor(170,170,170);
                        switch($this->marcaDeAguaDeclaracion){
                           case 'SI':
                                 $this->TextWithRotation(20,190,utf8_decode('ACTUALIZACIÓN NO FINALIZADA'),35);
                                //  $this->TextWithRotation(75, 170, utf8_decode('NO FINALIZADA'), 35);
                              break;
                           default:
                              break;
                        }
                    }
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

    function Row_Ficha($data,$code=false,$fills='',$fh='')
    {
        //Calculate the height of the row
        $nb=0;
        for($i=0;$i<count($data);$i++)
            $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
        if ($fh==""){
            $h=5*$nb;
        }else{
            $h=5*$nb;
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
            $this->MultiCell($w,5,$data[$i],0,$a);
            //Put the position to the right of the cell
            $this->SetXY($x+$w,$y);
        }
        //Go to the next line
        $this->Ln($h);
    }

    function Row_Asistencia($data,$code=false,$fills='',$fh='',$pintado='')
    {
        //Calculate the height of the row
        $nb=0;
        for($i=0;$i<count($data);$i++)
            $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
        if ($fh==""){
            $h=5*$nb;
        }else{
            $h=5*$nb;
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
            $this->MultiCell($w,5,$data[$i],0,$a,$pintado);
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

    function Row_SinLinea($data,$code=false,$fills='',$fh='')
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
            //$ax=$x; $ay=$y; $aw=$w; $ah=$h;
            //$this->Rect($x,$y,$w,$h,$fills);
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
        return " ".$GetD['mday']." de ".$verm[$GetD['mon']]." de ".$GetD['year'].",  Hora: ".$hora;
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

    function TextWithDirection($x, $y, $txt, $direction='R')
    {
        if ($direction=='R')
            $s=sprintf('BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET',1,0,0,1,$x*$this->k,($this->h-$y)*$this->k,$this->_escape($txt));
        elseif ($direction=='L')
            $s=sprintf('BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET',-1,0,0,-1,$x*$this->k,($this->h-$y)*$this->k,$this->_escape($txt));
        elseif ($direction=='U')
            $s=sprintf('BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET',0,1,-1,0,$x*$this->k,($this->h-$y)*$this->k,$this->_escape($txt));
        elseif ($direction=='D')
            $s=sprintf('BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET',0,-1,1,0,$x*$this->k,($this->h-$y)*$this->k,$this->_escape($txt));
        else
            $s=sprintf('BT %.2F %.2F Td (%s) Tj ET',$x*$this->k,($this->h-$y)*$this->k,$this->_escape($txt));
        if ($this->ColorFlag)
            $s='q '.$this->TextColor.' '.$s.' Q';
        $this->_out($s);
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

    function subWrite($h, $txt, $link='', $subFontSize=12, $subOffset=0)
    {
        // resize font
        $subFontSizeold = $this->FontSizePt;
        $this->SetFontSize($subFontSize);
        
        // reposition y
        $subOffset = ((($subFontSize - $subFontSizeold) / $this->k) * 0.3) + ($subOffset / $this->k);
        $subX        = $this->x;
        $subY        = $this->y;
        $this->SetXY($subX, $subY - $subOffset);

        //Output text
        $this->Write($h, $txt, $link);

        // restore y position
        $subX        = $this->x;
        $subY        = $this->y;
        $this->SetXY($subX,  $subY + $subOffset);

        // restore font size
        $this->SetFontSize($subFontSizeold);
    }
    
}

?>