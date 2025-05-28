<?php
ini_set("allow_url_fopen", 1);

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once APPPATH . "/libraries/fpdf/fpdf.php";

class Pdf2 extends FPDF {

    public $xheader;
    public $yheader;
    public $anchoheader = 205; 
    public $cabecera;
    public $validador;
    public $titulo;
    public $subTitulo;
    public $subTituloBotoom;
    public $entidad;
    public $fecha;
    public $fechaini;
    public $fechafin;
    public $piepagina;
    public $cabeceraValidacionVerificacion;
    private $encabezado;
    private $wi;
    private $cds220;
    private $rubro;

    public function __construct() {
        parent::__construct();
        $this->mes = ['', 'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
    }
    function setEncabezadoG($e){
        $this->encabezado = $e;
    }
    function setWidthsG($w){
        $this->wi = $w;
    }
    public function Header() {
      
        $this->ln(50);
    }

    public function Header2()
    {
         //encabezado grilla
                $this->SetFillColor(31,73,125);
                $this->SetTextColor(255);
                //$pdf->SetDrawColor(128,0,0);
                $this->SetLineWidth(.2);
                $this->SetFont('Arial','B',8);
                //Cabecera
                $this->SetWidths($this->wi);
                $this->RowHeader($this->encabezado,false,'FD',8);
    }

    public function HeaderTitle($title) {
        $this->SetFont('Arial', 'B', 8);
        $this->Image('assets/img/logo_senape.png', 15, 6, 75);
        $this->Image('assets/img/chakana.png', 220, 5, 38);
        /*
        $this->Cell(90);
        $this->Cell(0, -5, 'SERVICIO NACIONAL DE PATRIMONIO DEL ESTADO', 0, 0, 'R');
        $this->ln(0);
        $this->Cell(25);
        $this->Cell(0, 10, '', 'T');
        $this->ln(0);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(90);
        $this->Cell(0, 5, utf8_decode('Ministerio de Economía y Finanzas Públicas'), 0, 0, 'R');
        $this->ln(3);
        $this->Cell(90);
        $this->Cell(0, 5, utf8_decode('Viceministerio de Tesoro y Crédito Público'), 0, 0, 'R');
        $this->SetFont('Arial', 'B', 13);
        $this->ln(5);
        $this->Cell(0, 10, $title, 0, 0, 'C');
        $this->Ln(5);
        */
        $this->SetFont('Times','B',13);
        if($this->cabecera == 0_1)
            {
                $this->SetY(28);//-16
                //$this->Cell(10);
                $titulo1=  utf8_decode("REPORTE  GENERAL DEL ESTADO LEGAL DE LOS BIENES");
                $titulo2=  utf8_decode("Entidad: ".$this->entidad);
                $titulo3=  utf8_decode($this->titulo);
                $this->Cell(0,0,$titulo1,0,0,'C');
                $this->Ln(5);
                $this->SetFont('Times','B',12);
                $this->Cell(0,0,$titulo2,0,0,'C');
                $this->Ln(5);
                $this->SetFont('Times','B',12);
                $this->Cell(0,0,$titulo3,0,0,'C');
                $this->Ln(5);
                
                //encabezado grilla
                /*$this->SetFillColor(31,73,125);
                $this->SetTextColor(255);
                
                $this->SetLineWidth(.2);
                $this->SetFont('Arial','B',6);
                //Cabecera
                $this->SetWidths($this->wi);
                $this->RowHeader($this->encabezado,false,'DF',8);
                */
            }
        $this->SetFont('Times','B',13);
        if($this->cabecera == 1)
            {
                $this->SetY(20);//-16
                //$this->Cell(10);
                $titulo1=  utf8_decode("Validador: ".$this->validador);
                $titulo2=  utf8_decode("Entidad: ".$this->entidad);
                $titulo3=  utf8_decode($this->titulo);
                $this->Cell(0,0,$titulo3,0,0,'C');
                $this->Ln(5);
                $this->SetFont('Times','B',12);
                $this->Cell(0,0,$titulo1,0,0,'C');
                $this->Ln(5);
                $this->SetFont('Times','B',12);
                $this->Cell(0,0,$titulo2,0,0,'C');
                $this->Ln(5);
                $this->SetFont('Times','B',12);
                $this->Cell(0,0,$this->fecha,0,0,'C');
                $this->Ln(3);
                //encabezado grilla
                $this->SetFillColor(31,73,125);
                $this->SetTextColor(255);
                //$pdf->SetDrawColor(128,0,0);
                $this->SetLineWidth(.2);
                $this->SetFont('Arial','B',6);
                //Cabecera
                $this->SetWidths($this->wi);
                $this->RowHeader($this->encabezado,false,'DF',8);
            }
        if($this->cabecera == 2)
            {
                $this->SetY(20);//-16
                //$this->Cell(10);
                $titulo1=  utf8_decode("Validador: ".$this->validador);
                $titulo3=  utf8_decode($this->titulo);

                $this->Cell(0,0,$titulo3,0,0,'C');
                $this->Ln(5);
                
                $this->SetFont('Times','B',12);
                $this->Cell(0,0,$titulo1,0,0,'C');
                $this->Ln(5);
                $this->SetFont('Times','B',12);
                $this->Cell(0,0,$this->fecha,0,0,'C');
                $this->Ln(5);
                //$this->Cell(0,0,$this->fechaini." al ".$this->fechafin,0,0,'C');
                if(is_null($this->subTitulo))
                {
                    $this->Ln(3);
                }
                else{
                    $this->Ln(5);
                    $this->SetFont('Times','B',12);
                    $this->Cell(0,0,$this->subTitulo,0,0,'C');
                    $this->Ln(3);
                }

                //encabezado grilla
                $this->SetFillColor(31,73,125);
                $this->SetTextColor(255);
                //$pdf->SetDrawColor(128,0,0);
                $this->SetLineWidth(.2);
                $this->SetFont('Arial','B',8);
                //Cabecera
                $this->SetWidths($this->wi);
                $this->RowHeader($this->encabezado,false,'FD',8);
            }
        if($this->cabecera == 3)
        {
            $this->SetFont('Times','B',12);
            $this->SetY(15);//-16
            //$this->Cell(10);
            $titulo=  utf8_decode($this->titulo);
            $this->Ln(7);//2019
            $this->Cell(0,0,utf8_decode($titulo),0,0,'C');//2019
            // $this->Cell(0,0,$titulo,0,0,'C');
            $this->SetFont('Times','B',13);
            if(!is_null($this->subTitulo)){
                $this->Ln(5);
                $subtitulo=  utf8_decode($this->subTitulo);
                $this->Cell(0,0,$subtitulo,0,0,'C');
            }
            $this->SetFont('Times','B',12);
            if (!is_null($this->validador))
            {
                $this->Ln(5);
                $titulo1=  utf8_decode("Validador: ".$this->validador);
                $this->Cell(0,0,$titulo1,0,0,'C');
            }
            if(!is_null($this->fecha)){
                $this->SetFont('Times','B',12);
                $this->Ln(5);
                $this->Cell(0,0,$this->fecha,0,0,'C');
            }
            if (!is_null($this->fechaini))
            {
               // $this->Ln(5);
               // $this->Cell(0,0,$this->fechaini." al ".$this->fechafin,0,0,'C');
            }
            if(is_null($this->subTituloBotoom))
            {
                $this->Ln(3);
            }
            else{
                $this->Ln(5);
                $this->SetFont('Times','B',12);
                $this->Cell(0,0,$this->subTituloBotoom,0,0,'C');
                $this->Ln(3);
            }

            //encabezado grilla
            $this->SetFillColor(213,227,244);
            $this->SetTextColor(0);
            //$pdf->SetDrawColor(128,0,0);
            $this->SetLineWidth(.2);
            $this->SetFont('Arial','B',8);
            //Cabecera
            $this->SetWidths($this->wi);
            $this->SetX(30);
            $this->RowHeader($this->encabezado,false,'FD',9);
        }
        if($this->cabecera == 3_1)
        {
            $this->SetFont('Times','B',12);
            $this->SetY(15);//-16
            //$this->Cell(10);
            $titulo=  utf8_decode($this->titulo);
            $this->Ln(7);//2019
            $this->Cell(0,0,utf8_decode($titulo),0,0,'C');//2019
            // $this->Cell(0,0,$titulo,0,0,'C');
            $this->SetFont('Times','B',13);
            if(!is_null($this->subTitulo)){
                $this->Ln(5);
                $subtitulo=  utf8_decode($this->subTitulo);
                $this->Cell(0,0,$subtitulo,0,0,'C');
            }
            $this->SetFont('Times','B',12);
            if (!is_null($this->validador))
            {
                $this->Ln(5);
                $titulo1=  utf8_decode("Validador: ".$this->validador);
                $this->Cell(0,0,$titulo1,0,0,'C');
            }
            if(!is_null($this->fecha)){
                $this->SetFont('Times','B',12);
                $this->Ln(5);
                $this->Cell(0,0,$this->fecha,0,0,'C');
            }
            if (!is_null($this->fechaini))
            {
               // $this->Ln(5);
               // $this->Cell(0,0,$this->fechaini." al ".$this->fechafin,0,0,'C');
            }
            if(is_null($this->subTituloBotoom))
            {
                $this->Ln(3);
            }
            else{
                $this->Ln(5);
                $this->SetFont('Times','B',12);
                $this->Cell(0,0,$this->subTituloBotoom,0,0,'C');
                $this->Ln(3);
            }

            //encabezado grilla
            $this->SetFillColor(213,227,244);
            $this->SetTextColor(0);
            //$pdf->SetDrawColor(128,0,0);
            $this->SetLineWidth(.2);
            $this->SetFont('Arial','B',8);
            //Cabecera
            $this->SetWidths($this->wi);
            
            $this->RowHeader($this->encabezado,false,'FD',9);
        }
        if($this->cabecera == 4)
        {
            $this->SetFont('Times','B',12);
            $this->SetY(15);//-16

            //$this->Cell(10);
            $titulo=  utf8_decode($this->titulo);
            $this->Ln(7);//2019
            $this->Cell(0,0,utf8_decode($titulo),0,0,'C');//2019
            // $this->Cell(0,0,$titulo,0,0,'C');
            $this->SetFont('Times','B',13);
            if(!is_null($this->subTitulo)){
                $this->Ln(5);
                $subtitulo=  utf8_decode($this->subTitulo);
                $this->Cell(0,0,$subtitulo,0,0,'C');
            }
            $this->SetFont('Times','B',12);
            if (!is_null($this->validador))
            {
                $this->Ln(5);
                $titulo1=  utf8_decode("Validador: ".$this->validador);
                $this->Cell(0,0,$titulo1,0,0,'C');
            }
            if(!is_null($this->fecha)){
                $this->SetFont('Times','B',12);
                $this->Ln(5);
                $this->Cell(0,0,$this->fecha,0,0,'C');
            }
            if (!is_null($this->fechaini))
            {
               // $this->Ln(5);
               // $this->Cell(0,0,$this->fechaini." al ".$this->fechafin,0,0,'C');
            }
            if(is_null($this->subTituloBotoom))
            {
                $this->Ln(3);
            }
            else{
                $this->Ln(5);
                $this->SetFont('Times','B',12);
                $this->Cell(0,0,$this->subTituloBotoom,0,0,'C');
                $this->Ln(3);
            }

            //encabezado grilla
            $this->SetFillColor(213,227,244);
            $this->SetTextColor(0);
            //$pdf->SetDrawColor(128,0,0);
            $this->SetLineWidth(.2);
            $this->SetFont('Arial','B',8);
            //Cabecera
            $this->SetWidths($this->wi);
            $this->SetX(30);
            $this->RowHeader($this->encabezado,false,'FD',9);
        }
        /** cabecera 2021*/
        if($this->cabecera == 5)
        {
            $this->SetFont('Times','B',11);
            $this->SetY(15);//-16
            $titulo=utf8_decode($this->titulo);
            $this->Ln(5);
            $this->Cell(0,0,$this->cabeceraValidacionVerificacion,0,0,'C');

            $this->SetFont('Times','',12);
            if(!is_null($this->subTitulo)){
                $this->Ln(5);
                $subtitulo=  utf8_decode($this->subTitulo);
                $this->Cell(0,0,$subtitulo,0,0,'C');
            }
            if (!is_null($this->validador))
            {
                $this->Ln(5);
                $titulo1=utf8_decode("Validador: ".$this->validador);
                $this->Cell(0,0,$titulo1,0,0,'C');
            }
            if(!is_null($this->fecha)){
                $this->SetFont('Times','',12);
                $this->Ln(5);
                $this->Cell(0,0,$this->fecha,0,0,'C');
            }
            if (!is_null($this->fechaini))
            {
               // $this->Ln(5);
               // $this->Cell(0,0,$this->fechaini." al ".$this->fechafin,0,0,'C');
            }
            if(is_null($this->subTituloBotoom))
            {
                $this->Ln(3);
            }
            else{
                $this->Ln(5);
                $this->SetFont('Times','B',12);
                $this->Cell(0,0,$this->subTituloBotoom,0,0,'C');
                $this->Ln(3);
            }

            //encabezado grilla
            //$this->SetFont('Arial','B',10);
          
            //$this->Cell(259,5,'OBSERVACIONES A LOS DATOS DECLARADOS',1,0,'C',0);
            //$this->Ln();
            $this->SetFillColor(213,227,244);
            $this->SetTextColor(0);
            $this->SetFont('Arial','B',8);
            //Cabecera
            $this->Cell(7,21,'Nro',1,0,'C',1);
            $this->Cell(14,21,'Id Bien',1,0,'C',1);
            $this->Cell(42,21,utf8_decode('Tipo de Documento'),1,0,'C',1);
            $this->Cell(7,21,'',1,0,'C',1);
            $this->Cell(7,21,'',1,0,'C',1);
            $this->Cell(7,21,'',1,0,'C',1);
            //$this->Cell(50,21,'Observaciones en cuanto a los datos técnicos',1,0,'C',1);
            $this->MultiCell(50,7,utf8_decode("Observaciones\nen cuanto a los\ndatos técnicos"),1,'C',1);
            $this->SetXY(144, 38);
            $this->Cell(95,21,'Observaciones Adicionales',1,0,'C',1);
            $this->MultiCell(30,7,'Documentos enviados y no declarados (*)',1,'C',1);
            $this->TextWithDirection(78,54,'Adjunta','U');
            $this->TextWithDirection(85,57,'Corresponde','U');
            $this->TextWithDirection(92,54,'Legible','U');
            $this->SetWidths($this->wi);
            /*
            $this->RowHeader($this->encabezado,false,'FD',8);
            */
        }

         /** cabecera 2024*/
        if($this->cabecera == 6)
        {
            $this->SetFont('Times','B',11);
            $this->SetY(15);//-16
            $titulo=utf8_decode($this->titulo);
            $this->Ln(5);
            $this->Cell(0,0,$this->cabeceraValidacionVerificacion,0,0,'C');

            $this->SetFont('Times','',12);
            if(!is_null($this->subTitulo)){
                $this->Ln(5);
                $subtitulo=  utf8_decode($this->subTitulo);
                $this->Cell(0,0,$subtitulo,0,0,'C');
            }
            if (!is_null($this->validador))
            {
                $this->Ln(5);
                $titulo1=utf8_decode("Validador: ".$this->validador);
                $this->Cell(0,0,$titulo1,0,0,'C');
            }
            if(!is_null($this->fecha)){
                $this->SetFont('Times','',12);
                $this->Ln(5);
                $this->Cell(0,0,$this->fecha,0,0,'C');
            }
            if (!is_null($this->fechaini))
            {
               // $this->Ln(5);
               // $this->Cell(0,0,$this->fechaini." al ".$this->fechafin,0,0,'C');
            }
            if(is_null($this->subTituloBotoom))
            {
                $this->Ln(3);
            }
            else{
                $this->Ln(5);
                $this->SetFont('Times','B',12);
                $this->Cell(0,0,$this->subTituloBotoom,0,0,'C');
                $this->Ln(3);
            }

            //encabezado grilla
            //$this->SetFont('Arial','B',10);
          
            //$this->Cell(259,5,'OBSERVACIONES A LOS DATOS DECLARADOS',1,0,'C',0);
            //$this->Ln();
            $this->SetFillColor(213,227,244);
            $this->SetTextColor(0);
            $this->SetFont('Arial','B',8);
            //Cabecera
            $this->Cell(7,21,'Nro',1,0,'C',1);
            $this->Cell(13,21,'Id Bien',1,0,'C',1);
            $this->Cell(34,21,utf8_decode('Tipo de Documento'),1,0,'C',1);
            $this->Cell(7,21,'',1,0,'C',1);
            $this->Cell(7,21,'',1,0,'C',1);
            $this->Cell(7,21,'',1,0,'C',1);
            //$this->Cell(50,21,'Observaciones en cuanto a los datos técnicos',1,0,'C',1);
            $this->MultiCell(45,7,utf8_decode("Observaciones\nen cuanto a los\ndatos técnicos"),1,'C',1);
            $this->SetXY(130, 38);
            $this->Cell(85,21,'Observaciones Adicionales',1,0,'C',1);
            $this->SetFont('Arial','B',7);
            $this->MultiCell(20,7,'Documentos enviados y no declarados (*)',1,'C',1);
            $this->SetFont('Arial','B',8);
            $this->SetXY(235, 38);
            $this->MultiCell(38,21,'Usuario Validador',1,'C',1);
            $this->TextWithDirection(68,54,'Adjunta','U');
            $this->TextWithDirection(76,57,'Corresponde','U');
            $this->TextWithDirection(82,54,'Legible','U');
            $this->SetWidths($this->wi);
            /*
            $this->RowHeader($this->encabezado,false,'FD',8);
            */
        }
    }

    public function Footer() {
        if($this->piepagina > 1)
        {
            $titulo1=  utf8_decode($this->validador);
            $this->SetXY(40, 189);
            $this->Cell(60, 3, '.........................................................................' , 0,0, 'C');
            $this->SetXY(40, 192);
            $this->Cell(60, 3, 'VALIDADOR' ,0,0, 'C');
            $this->SetXY(40, 195);
            $this->Cell(60, 3, ($titulo1),0,0, 'C');

            $this->SetXY(180, 189);
            $this->Cell(60, 3, '.........................................................................' , 0,0,'C');
            $this->SetXY(180, 192);
            $this->Cell(60, 3, 'SELLO Y FIRMA SUPERVISOR' , 0,0, 'C');
           
            $this->SetY(-15);
            $this->SetFont('Arial', 'I', 8);
            $this->Cell(0, 10, 'Pagina ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
        }
        elseif($this->piepagina == -1)
        {
                $this->SetY(-15);
                $this->SetFont('Arial', 'I', 8);
                $this->Cell(0, 10, 'Pagina ' . $this->PageNo() , 0, 0, 'C');
        }
         else 
        {
                $this->SetY(-15);
                $this->SetFont('Arial', 'I', 8);
                $this->Cell(0, 10, 'Pagina ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
        }
        
        //Mensaje Temporal para el REPORTE
        $this->SetFont('Arial','',65);
        $this->SetTextColor(200);
        //$this->TextWithRotation(35,190,utf8_decode('REPORTE PRELIMINAR'),30,0);
        
        if($this->cabecera==5  || $this->cabecera==6)
        {
            $this->SetY(-17);
            $this->SetTextColor(0);
            $this->SetFont('Arial', '', 6);
            /*$this->MultiCell(0, 2,utf8_decode("(*) Nota: Se aclara que la casilla 'Documentos Agregados', tiene inserta la información de documentos que se encontraron en los adjuntos a la Declaración Jurada de Bienes del Estado - DEJURBE, que corresponden a documentos definitivos o documentos intermedios de mayor jerarquía a los declarados en la DEJURBE, sin que esta inserción de información represente una modificación o alteración de la declaración realizada por la entidad, teniendo únicamente carácter informativo.\n(**) Documentos validados en la DEJURBE de la gestión que fueron verificados"),0,'J');
            */
            $this->MultiCell(0, 2,utf8_decode("(*) Nota: Se aclara que la casilla 'Documentos Agregados', tiene inserta la información de documentos que se encontraron en los adjuntos a la Declaración Jurada de Bienes del Estado - DEJURBE, que corresponden a documentos definitivos o documentos intermedios de mayor jerarquía a los declarados en la DEJURBE, sin que esta inserción de información represente una modificación o alteración de la declaración realizada por la entidad, teniendo únicamente carácter informativo."),0,'J');
        }

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
            $h=3.5*$nb;
        }else{
            $h=3.5*$nb;
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
            $this->MultiCell($w,3.5,$data[$i],0,$a);
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
        $mes = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
        return;
    }
    function fechacompleta(){
        $GetD = getdate();
        $verd = array(
        1=>"Lunes",2=>"Martes",3=>"Mi&eacute;rcoles",4=>"Jueves",5=>"Viernes",6=>"Sábado",7=>"Domingo"
        );
        $verm = array(
        1=>"Enero",2=>"Febrero",3=>"Marzo",4=>"Abril",5=>"Mayo",6=>"Junio",7=>"Julio",
            8=>"Agosto",9=>"Septiembre",10=>"Octubre",11=>"Noviembre",12=>"Diciembre"
        );
        //return $verd[$GetD['wday']].", ".$GetD['mday']." de ".$verm[$GetD['mon']]." del ".$GetD['year'];
        return " ".$GetD['mday']." de ".$verm[$GetD['mon']]." de ".$GetD['year']."  Hora:  ".$GetD['hours'].":".$GetD['minutes'].":".$GetD['seconds'];
    }
    function fechacompleta2(){
        $GetD = getdate();
        $verd = array(
        1=>"Lunes",2=>"Martes",3=>"Mi&eacute;rcoles",4=>"Jueves",5=>"Viernes",6=>"Sábado",7=>"Domingo"
        );
        $verm = array(
        1=>"Enero",2=>"Febrero",3=>"Marzo",4=>"Abril",5=>"Mayo",6=>"Junio",7=>"Julio",
            8=>"Agosto",9=>"Septiembre",10=>"Octubre",11=>"Noviembre",12=>"Diciembre"
        );
        //return $verd[$GetD['wday']].", ".$GetD['mday']." de ".$verm[$GetD['mon']]." del ".$GetD['year'];
        return " ".$GetD['mday']." de ".$verm[$GetD['mon']]." de ".$GetD['year'];
    }
    function RowTitle($data) {
        //Calculate the height of the row
        $nb = 0;
        for ($i = 0; $i < count($data); $i++)
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        $h = 2.5 * $nb;
        //Issue a page break first if needed
        $this->CheckPageBreak($h);
        $this->SetFillColor(31,73,125);
        $this->SetTextColor(255,255,255);
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
    function Row2($data) {
        //Calculate the height of the row
        $nb = 0;
        for ($i = 0; $i < count($data); $i++)
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        $h = 2.5 * $nb;
        //Issue a page break first if needed
        $this->CheckPageBreak($h);
        $this->SetFillColor(255,255,255);
        $this->SetTextColor(0);
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

    /*** FUNCIONES PARA GRÁFICOS CHARTS ***/

    function Sector($xc, $yc, $r, $a, $b, $style='FD', $cw=true, $o=90)
    {
        $d0 = $a - $b;
        if($cw){
            $d = $b;
            $b = $o - $a;
            $a = $o - $d;
        }else{
            $b += $o;
            $a += $o;
        }
        while($a<0)
            $a += 360;
        while($a>360)
            $a -= 360;
        while($b<0)
            $b += 360;
        while($b>360)
            $b -= 360;
        if ($a > $b)
            $b += 360;
        $b = $b/360*2*M_PI;
        $a = $a/360*2*M_PI;
        $d = $b - $a;
        if ($d == 0 && $d0 != 0)
            $d = 2*M_PI;
        $k = $this->k;
        $hp = $this->h;
        if (sin($d/2))
            $MyArc = 4/3*(1-cos($d/2))/sin($d/2)*$r;
        else
            $MyArc = 0;
        //first put the center
        $this->_out(sprintf('%.2F %.2F m',($xc)*$k,($hp-$yc)*$k));
        //put the first point
        $this->_out(sprintf('%.2F %.2F l',($xc+$r*cos($a))*$k,(($hp-($yc-$r*sin($a)))*$k)));
        //draw the arc
        if ($d < M_PI/2){
            $this->_Arc($xc+$r*cos($a)+$MyArc*cos(M_PI/2+$a),
                        $yc-$r*sin($a)-$MyArc*sin(M_PI/2+$a),
                        $xc+$r*cos($b)+$MyArc*cos($b-M_PI/2),
                        $yc-$r*sin($b)-$MyArc*sin($b-M_PI/2),
                        $xc+$r*cos($b),
                        $yc-$r*sin($b)
                        );
        }else{
            $b = $a + $d/4;
            $MyArc = 4/3*(1-cos($d/8))/sin($d/8)*$r;
            $this->_Arc($xc+$r*cos($a)+$MyArc*cos(M_PI/2+$a),
                        $yc-$r*sin($a)-$MyArc*sin(M_PI/2+$a),
                        $xc+$r*cos($b)+$MyArc*cos($b-M_PI/2),
                        $yc-$r*sin($b)-$MyArc*sin($b-M_PI/2),
                        $xc+$r*cos($b),
                        $yc-$r*sin($b)
                        );
            $a = $b;
            $b = $a + $d/4;
            $this->_Arc($xc+$r*cos($a)+$MyArc*cos(M_PI/2+$a),
                        $yc-$r*sin($a)-$MyArc*sin(M_PI/2+$a),
                        $xc+$r*cos($b)+$MyArc*cos($b-M_PI/2),
                        $yc-$r*sin($b)-$MyArc*sin($b-M_PI/2),
                        $xc+$r*cos($b),
                        $yc-$r*sin($b)
                        );
            $a = $b;
            $b = $a + $d/4;
            $this->_Arc($xc+$r*cos($a)+$MyArc*cos(M_PI/2+$a),
                        $yc-$r*sin($a)-$MyArc*sin(M_PI/2+$a),
                        $xc+$r*cos($b)+$MyArc*cos($b-M_PI/2),
                        $yc-$r*sin($b)-$MyArc*sin($b-M_PI/2),
                        $xc+$r*cos($b),
                        $yc-$r*sin($b)
                        );
            $a = $b;
            $b = $a + $d/4;
            $this->_Arc($xc+$r*cos($a)+$MyArc*cos(M_PI/2+$a),
                        $yc-$r*sin($a)-$MyArc*sin(M_PI/2+$a),
                        $xc+$r*cos($b)+$MyArc*cos($b-M_PI/2),
                        $yc-$r*sin($b)-$MyArc*sin($b-M_PI/2),
                        $xc+$r*cos($b),
                        $yc-$r*sin($b)
                        );
        }
        //terminate drawing
        if($style=='F')
            $op='f';
        elseif($style=='FD' || $style=='DF')
            $op='b';
        else
            $op='s';
        $this->_out($op);
    }

    var $legends;
    var $wLegend;
    var $sum;
    var $NbVal;

    function PieChart($w, $h, $data, $format, $colors=null)
    {
        $this->SetFont('Arial', '', 9);
        $this->SetLegends($data,$format);

        $XPage = $this->GetX();
        $YPage = $this->GetY();
        $margin = 2;
        $hLegend = 5;
        $radius = min($w - $margin * 4 - $hLegend - $this->wLegend, $h - $margin * 2);
        $radius = floor($radius / 2);
        $XDiag = $XPage + $margin + $radius;
        $YDiag = $YPage + $margin + $radius;
        if($colors == null) {
            for($i = 0; $i < $this->NbVal; $i++) {
                $gray = $i * intval(255 / $this->NbVal);
                $colors[$i] = array($gray,$gray,$gray);
            }
        }

        //Sectors
        $this->SetLineWidth(0.2);
        $angleStart = 0;
        $angleEnd = 0;
        $i = 0;
        foreach($data as $val) {
            $angle = ($val * 360) / doubleval($this->sum);
            if ($angle != 0) {
                $angleEnd = $angleStart + $angle;
                $this->SetFillColor($colors[$i][0],$colors[$i][1],$colors[$i][2]);
                $this->Sector($XDiag, $YDiag, $radius, $angleStart, $angleEnd);
                $angleStart += $angle;
            }
            $i++;
        }

        //Legends
        $this->SetFont('Arial', '', 9);
        $x1 = $XPage + 2 * $radius + 4 * $margin;
        $x2 = $x1 + $hLegend + $margin;
        $y1 = $YDiag - $radius + (2 * $radius - $this->NbVal*($hLegend + $margin)) / 2;
        for($i=0; $i<$this->NbVal; $i++) {
            $this->SetFillColor($colors[$i][0],$colors[$i][1],$colors[$i][2]);
            $this->Rect($x1, $y1, $hLegend, $hLegend, 'DF');
            $this->SetXY($x2,$y1);
            $this->Cell(0,$hLegend,$this->legends[$i]);
            $y1+=$hLegend + $margin;
        }
    }

    function BarDiagram($w, $h, $data, $format, $color=null, $maxVal=0, $nbDiv=4)
    {
        $this->SetFont('Courier', '', 10);
        $this->SetLegends($data,$format);

        $XPage = $this->GetX();
        $YPage = $this->GetY();
        $margin = 2;
        $YDiag = $YPage + $margin;
        $hDiag = floor($h - $margin * 2);
        $XDiag = $XPage + $margin * 2 + $this->wLegend;
        $lDiag = floor($w - $margin * 3 - $this->wLegend);
        if($color == null)
            $color=array(155,155,155);
        if ($maxVal == 0) {
            $maxVal = max($data);
        }
        $valIndRepere = ceil($maxVal / $nbDiv);
        $maxVal = $valIndRepere * $nbDiv;
        $lRepere = floor($lDiag / $nbDiv);
        $lDiag = $lRepere * $nbDiv;
        $unit = $lDiag / $maxVal;
        $hBar = floor($hDiag / ($this->NbVal + 1));
        $hDiag = $hBar * ($this->NbVal + 1);
        $eBaton = floor($hBar * 80 / 100);

        $this->SetLineWidth(0.2);
        $this->Rect($XDiag, $YDiag, $lDiag, $hDiag);

        $this->SetFont('Courier', '', 10);
        $this->SetFillColor($color[0],$color[1],$color[2]);
        $i=0;
        foreach($data as $val) {
            //Bar
            $xval = $XDiag;
            $lval = (int)($val * $unit);
            $yval = $YDiag + ($i + 1) * $hBar - $eBaton / 2;
            $hval = $eBaton;
            $this->Rect($xval, $yval, $lval, $hval, 'DF');
            //Legend
            $this->SetXY(0, $yval);
            $this->Cell($xval - $margin, $hval, $this->legends[$i],0,0,'R');
            $i++;
        }

        //Scales
        for ($i = 0; $i <= $nbDiv; $i++) {
            $xpos = $XDiag + $lRepere * $i;
            $this->Line($xpos, $YDiag, $xpos, $YDiag + $hDiag);
            $val = $i * $valIndRepere;
            $xpos = $XDiag + $lRepere * $i - $this->GetStringWidth($val) / 2;
            $ypos = $YDiag + $hDiag - $margin;
            $this->Text($xpos, $ypos, $val);
        }
    }

    function SetLegends($data, $format)
    {
        $this->legends=array();
        $this->wLegend=0;
        $this->sum=array_sum($data);
        $this->NbVal=count($data);
        foreach($data as $l=>$val)
        {
            $p=sprintf('%.2f',$val/$this->sum*100).'%';
            $legend=str_replace(array('%l','%v','%p'),array($l,$val,$p),$format);
            $this->legends[]=$legend;
            $this->wLegend=max($this->GetStringWidth($legend),$this->wLegend);
        }
    }

    var $legendsb;
    var $wLegendb;
    var $sumb;
    var $NbValb;
    var $tituloXY ;
    var $totalBarra;

    function SetLegendsB($data, $format)
    {
        $this->legendsb=array();
        //$this->tituloXY=array();
        $this->wLegendb=0;
        $this->NbValb=count($data);
    }

    function ColumnChart( $w, $h, $data,$format, $color=null, $maxVal=0, $nbDiv=4)
    {
        // RGB for color 0
        $colors[3][0] = 155;
        $colors[3][1] = 75;
        $colors[3][2] = 155;

        // RGB for color 1
        $colors[2][0] = 75;
        $colors[2][1] = 155;
        $colors[2][2] = 255;

        // RGB for color 2
        $colors[1][0] = 37;
        $colors[1][1] = 211;
        $colors[1][2] = 102;
        

        // RGB for color 3
        $colors[0][0] = 75;
        $colors[0][1] = 100;
        $colors[0][2] = 155;

        $this->SetFont('Arial', '', 9);
        $this->SetLegendsB($data,$format);
        $XspacioTituloVertical = 8;
        // Starting corner (current page position where the chart has been inserted)
        $XPage = $this->GetX();
        $YPage = $this->GetY();
        $margin = 2; 

        // Y position of the chart
        $YDiag = $YPage + $margin;

        // chart HEIGHT
        $hDiag = floor($h - $margin * 2);

        // X position of the chart
        $XDiag = $XPage + $margin +  $XspacioTituloVertical;

        // chart LENGHT
        $lDiag = floor($w - $margin * 3 - $this->wLegend);

        if($color == null)
            $color=array(155,155,155);
        if ($maxVal == 0) 
        {
            foreach($data as $val)
            {
                if(max($val) > $maxVal)
                {
                    $maxVal = max($val);
                }
            }
        }

        // define the distance between the visual reference lines (the lines which cross the chart's internal area and serve as visual reference for the column's heights)
        $valIndRepere = ceil($maxVal / $nbDiv);

        // adjust the maximum value to be plotted (recalculate through the newly calculated distance between the visual reference lines)
        $maxVal = $valIndRepere * $nbDiv;

        // define the distance between the visual reference lines (in milimeters)
        $hRepere = floor($hDiag / $nbDiv);

        // adjust the chart HEIGHT
        $hDiag = $hRepere * $nbDiv;

        // determine the height unit (milimiters/data unit)
        if( $maxVal >0)
                $unit = $hDiag / $maxVal;
        else    $unit = 1;

        // determine the bar's thickness
        $lBar = floor($lDiag / ($this->NbVal + 1));
        $lDiag = $lBar * ($this->NbVal + 1);
        $eColumn = floor($lBar * 80 / 100);

        // draw the chart border
        $this->SetLineWidth(0.2);
        //$this->Rect($XDiag, $YDiag, $lDiag, $hDiag);


        $this->SetFont('Arial', '', 8);
        $this->SetFillColor($color[0],$color[1],$color[2]);
        $i=0;
        foreach($data as $barraGrupoTexto =>$val) 
        {
            //Column
            $yval = $YDiag + $hDiag;
            $xval = $XDiag + ($i + 1) * $lBar - $eColumn/2;
            $lval = floor($eColumn/(count($val)));
            $j=0;
            $posFinalX=$XDiag;
            foreach($val as $barraTexto => $v)
            {
                $hval = (int)($v * $unit);
                $this->SetFont('Arial', '', 7);
               $posFinalX = $xval+($lval*$j);
                $this->SetFillColor($colors[$j][0], $colors[$j][1], $colors[$j][2]);
                $this->Rect($xval+($lval*$j), $yval, $lval, -$hval, 'DF');
                $this->RotarTexto($xval+($lval*$j)+3, ($yval -$hval-1), $v, 0);
                $j++;
            }

            //$this->SetFont('Arial', 'b', 7);
            //Legend
            $this->SetXY($xval, $yval + $margin);
            $this->Cell($lval+10, 3, $this->legends[$i],0 ,0,'C');
            $f =1;
            /////////////////////////////////////////////////////////////////}
            foreach($val as $barraTexto => $v)
            {            
                    $this->RotarTexto($xval+$lval, ($this->GetY()+2+(5.8*$f)), $v, 0);
                    $f++;
            }
            /////////////////////////////////////////////////////////////////
            $i++;
        }
        ////////////////////////////////////////////////////////////////////////////////////////////
        ///////////////////////////   TOTAL  POR BARRA /////////////////////////////////////////////
        $this->Cell($lval+10, 3, $this->legends[$i],0 ,0,'C');
        $f =1;
        $this->SetFont('Arial', 'B', 8);
        foreach($this->totalBarra as  $v)
        {            
                $this->RotarTexto($xval+$lval+20, ($this->GetY()+2+(5.8*$f )), $v, 0);
                $f++;
        }
        $this->SetFont('Arial', '', 8);
        ////////////////////////////////////////////////////////////////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////////
        $this->Line($XDiag+2, $YDiag, $XDiag+2 , $yval );
        $this->Line($XDiag, $yval , $posFinalX+26, $yval );

        //Scales Y - VERTICAL
        for ($i = 0; $i <= $nbDiv; $i++) 
        {
            $ypos = $YDiag + $hRepere * $i;
            //$this->Line($XDiag, $ypos, $posFinalX+15, $ypos);
            $this->Line($XDiag-1, $ypos, $XDiag + 2, $ypos);
            $val = ($nbDiv - $i) * $valIndRepere;
            $ypos = $YDiag + $hRepere * $i;
            $xpos = $XDiag - $margin - $this->GetStringWidth($val);
            $this->Text($xpos, $ypos, $val);
        }
        //////////////////    TABLA   ////////////////////////////////7
            
        foreach($data as $barraGrupoTexto =>$val) 
        {               
            $tbarra=array();
            $f=0;    
            foreach($val as $barraTexto => $v)
            {
                if(!array_key_exists($barraTexto, $tbarra))
                {
                        array_push($tbarra, $barraTexto );
                }
            }
            $this->SetFont('Arial', '',7 );
            foreach($tbarra as $v => $titBarra)
            {
                $posFinalY =$yval +(6* ($f+1)) ;
                $this->SetDrawColor(193, 188, 183);
                $this->SetFillColor($colors[$f][0], $colors[$f][1], $colors[$f][2]);
                $this->RotarTexto( ($XDiag-2), ($yval +10 +(6.1*$f)), $titBarra, 0);
                $this->Rect($XDiag-8, $yval+ 7+(6.1*$f), 4, 4, 'DF');
                $this->Line($XDiag-8, $yval+(6* ($f+1) ), $posFinalX+26, $posFinalY );
                $f++;
            }
          
            $this->Line($XDiag-8, $yval+(6* ($f+1) ), $posFinalX+26, ($yval +(6* ($f+1) )) );  
            $this->SetDrawColor(0, 0, 0);              
         }  

         ///////// LEYENDA PARA LOS TITULOS DEL GRAFICO  X , Y     ////////////////////
        $this->SetFont('Arial', 'B', 9);
        $XFinPage = $posFinalX/2 ;
        $YFinPage =  $this->GetY() - (($this->GetY() - $YPage)/3 );
        $this->RotarTexto($XPage,$YFinPage,$this->tituloXY[0], 90);
        $this->RotarTexto($XFinPage,$posFinalY +12,$this->tituloXY[1], 0);
        ///////////////////////////////////////////////////////////////////////////// 
        $this->SetXY($XPage, $this->GetY()+30 );
        $this->Rect($XPage-8, $YPage-10, $posFinalX+10 , $h+40, '');
    }


    var $angle=0;
    function Rotate($angle,$x=-1,$y=-1)
    {
        if($x==-1)
            $x=$this->x;
        if($y==-1)
            $y=$this->y;
        if($this->angle!=0)
            $this->_out('Q');
        $this->angle=$angle;
        if($angle!=0)
        {
            $angle*=M_PI/180;
            $c=cos($angle);
            $s=sin($angle);
            $cx=$x*$this->k;
            $cy=($this->h-$y)*$this->k;
            $this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm',$c,$s,-$s,$c,$cx,$cy,-$cx,-$cy));
        }
    }
    function RotarTexto($x,$y,$txt,$angulo)
    {
        //Text rotated around its origin
        $this->Rotate($angulo,$x,$y);
        $this->Text($x,$y,$txt);
        $this->Rotate(0);
    }

    


}

?>