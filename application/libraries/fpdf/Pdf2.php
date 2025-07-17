<?php
ini_set("allow_url_fopen", 1);

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once APPPATH . "/libraries/fpdf/fpdf.php";
// include_once  "fpdf/easyTable.php";

class Pdf2 extends FPDF {

    public $xheader;
    public $yheader;
    public $anchoheader = 205; 
    public $cabecera;
    public $tituloCabecera;
    public $subtituloCabecera1;
    public $subtituloCabecera2;
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

        //***** Cabecera Vacía *****//
        if($this->opcion_cabecera==1)
        {
            //Sin cabecera
        }
        //***** Cabecera con solo Logos *****//
        if($this->opcion_cabecera==2)
        {
            $this->Image('resources/images/logos/200BicentenarioBoliviavertical.png', 10, 6, 19);
            $this->Image('resources/images/logos/logo_senape_reporte.png', 70, 6, 70);
            $this->Image('resources/images/logos/chakana.png', 170, 4, 40);
            $this->SetY(20);
            //$this->Ln();
        }
        if($this->opcion_cabecera==3)
        {
            // $this->Image('resources/images/logos/bicentenario.jpg', 17, 10,23);
            // $this->Image('resources/images/logos/logo_senape_reporte.png', 70, 10, 74.5);
            // $this->Image('resources/images/logos/chakana.png', 160, 8, 43);

            $this->Ln();
            $this->SetTextColor(0);
            $this->SetFont('Times','B',6);
            $y = $this->GetY();
            $this->SetX(10);
            $this->MultiCell(30,3,utf8_decode($this->entidad),0,'C',0);
            $this->SetX(10);
            $this->MultiCell(30,3,utf8_decode($this->sigla),0,'C',0);
            $this->SetX(10);
            $this->MultiCell(30,3,utf8_decode('SENAPE'),0,'C',0);
            $this->Ln(3);

            $this->SetXY(200, $y); 
            $this->Cell(10, 5, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'R');
            $this->SetXY(190,$y+3);            
            $fecha_hoy = $this->fechaformato();
            $this->Cell(10, 5,utf8_decode('Fecha:').$fecha_hoy, 0, 0, 'L');
            $this->Ln(3);

           
            $this->SetXY(0,30);
            $this->SetFont('Arial', 'BU', 12);
            $this->Cell(0,0,utf8_decode($this->tituloCabecera),0,1,'C',0);
            $this->Ln(4);
            $this->SetFont('Times','B',7);
            $this->SetX(0);
            $this->Cell(0,0,utf8_decode($this->subtituloCabecera1),0,1,'C',0);
            $this->Ln(3);
            $this->SetX(0);
            $this->Cell(0,0,utf8_decode($this->subtituloCabecera2),0,1,'C',0);
            $this->Ln(3);
            
            //Cabecera
            // Cabecera superior agrupada
            $this->SetXY(5, 40); // Coordenada superior izquierda
            $this->SetFillColor(230, 230, 225);
            $this->SetTextColor(0);
            $this->SetFont('Arial','B',7);
            $this->SetX(5);
            $this->Cell(20,13,utf8_decode('Fecha'), 1, 0, 'C', 1);
            $this->Cell(15,13,utf8_decode('Tipo'), 1, 0, 'C', 1);
            $this->Cell(20,13,utf8_decode('Número'), 1, 0, 'C', 1);
            $this->Cell(90,13,utf8_decode('Descripción(Glosa)'), 1, 0, 'C', 1);
            $this->SetXY(150,40);
            $this->Cell(30,5,utf8_decode('Movimientos'),1,0,'C',1);
            $this->SetXY(150,45);
            $this->Cell(15,8,utf8_decode('Debe'),1, 0, 'C', 1);
            $this->SetXY(165,45);
            $this->Cell(15,8,utf8_decode('Haber'),1, 0, 'C', 1);
            $this->SetXY(180,40);
            $this->Cell(30,5,utf8_decode('Saldos'),1,1,'C',1);
            $this->SetXY(180,45);
            $this->Cell(15,8,utf8_decode('Deudor'),1, 0, 'C', 1);
            $this->SetXY(195,45);
            $this->Cell(15,8,utf8_decode('Acreedor'),1, 1, 'C', 1);

        }
        if($this->opcion_cabecera==4)
        {
            // $this->Image('resources/images/logos/bicentenario.jpg', 17, 10,23);
            // $this->Image('resources/images/logos/logo_senape_reporte.png', 70, 10, 74.5);
            // $this->Image('resources/images/logos/chakana.png', 160, 8, 43);

            $this->Ln();
            $this->SetTextColor(0);
            $this->SetFont('Times','B',6);
            $y = $this->GetY();
            $this->SetX(10);
            $this->MultiCell(30,3,utf8_decode($this->entidad),0,'C',0);
            $this->SetX(10);
            $this->MultiCell(30,3,utf8_decode($this->sigla),0,'C',0);
            $this->SetX(10);
            $this->MultiCell(30,3,utf8_decode('SENAPE'),0,'C',0);
            $this->Ln(3);

            $this->SetXY(200, $y); 
            $this->Cell(10, 5, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'R');
            $this->SetXY(190,$y+3);            
            $fecha_hoy = $this->fechaformato();
            $this->Cell(10, 5,utf8_decode('Fecha:').$fecha_hoy, 0, 0, 'L');
            $this->Ln(3);

           
            $this->SetXY(0,30);
            $this->SetFont('Arial', 'BU', 12);
            $this->Cell(0,0,utf8_decode($this->tituloCabecera),0,1,'C',0);
            $this->Ln(4);
            $this->SetFont('Times','B',7);
            $this->SetX(0);
            $this->Cell(0,0,utf8_decode($this->subtituloCabecera1),0,1,'C',0);
            $this->Ln(3);
            $this->SetX(0);
            $this->Cell(0,0,utf8_decode($this->subtituloCabecera2),0,1,'C',0);
            $this->Ln(3);
            
            //Cabecera
            // Cabecera superior agrupada
            $this->SetXY(5, 40); // Coordenada superior izquierda
            $this->SetFillColor(230, 230, 225);
            $this->SetTextColor(0);
            $this->SetFont('Arial','B',7);
            $this->SetX(5);
            $this->Cell(25,13,utf8_decode('CÓDIGO'), 1, 0, 'C', 1);
            $this->Cell(120,13,utf8_decode('DESCRIPCIÓN'), 1, 0, 'C', 1);
            $this->SetXY(150,40);
            $this->Cell(30,5,utf8_decode('SUMAS'),1,0,'C',1);
            $this->SetXY(150,45);
            $this->Cell(15,8,utf8_decode('DEBE'),1, 0, 'C', 1);
            $this->SetXY(165,45);
            $this->Cell(15,8,utf8_decode('HABER'),1, 0, 'C', 1);
            $this->SetXY(180,40);
            $this->Cell(30,5,utf8_decode('SALDOS'),1,1,'C',1);
            $this->SetXY(180,45);
            $this->Cell(15,8,utf8_decode('DEUDOR'),1, 0, 'C', 1);
            $this->SetXY(195,45);
            $this->Cell(15,8,utf8_decode('ACREEDOR'),1, 1, 'C', 1);

        }
        if($this->opcion_cabecera==5)
        {
            // $this->Image('resources/images/logos/bicentenario.jpg', 17, 10,23);
            // $this->Image('resources/images/logos/logo_senape_reporte.png', 70, 10, 74.5);
            // $this->Image('resources/images/logos/chakana.png', 160, 8, 43);

            $this->Ln();
            $this->SetTextColor(0);
            $this->SetFont('Times','B',6);
            $y = $this->GetY();
            $this->SetX(10);
            $this->MultiCell(30,3,utf8_decode($this->entidad),0,'C',0);
            $this->SetX(10);
            $this->MultiCell(30,3,utf8_decode($this->sigla),0,'C',0);
            $this->SetX(10);
            $this->MultiCell(30,3,utf8_decode('SENAPE'),0,'C',0);
            $this->Ln(3);

            $this->SetXY(200, $y); 
            $this->Cell(10, 5, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'R');
            $this->SetXY(190,$y+3);            
            $fecha_hoy = $this->fechaformato();
            $this->Cell(10, 5,utf8_decode('Fecha:').$fecha_hoy, 0, 0, 'L');
            $this->Ln(3);

           
            $this->SetXY(0,30);
            $this->SetFont('Arial', 'BU', 12);
            $this->Cell(0,0,utf8_decode($this->tituloCabecera),0,1,'C',0);
            $this->Ln(4);
            $this->SetFont('Times','B',7);
            $this->SetX(0);
            $this->Cell(0,0,utf8_decode($this->subtituloCabecera1),0,1,'C',0);
            $this->Ln(3);
            $this->SetX(0);
            $this->Cell(0,0,utf8_decode($this->subtituloCabecera2),0,1,'C',0);
            $this->Ln(3);
            
            //Cabecera
            // Cabecera superior agrupada
            // $this->SetXY(5, 40); // Coordenada superior izquierda
            // $this->SetFillColor(230, 230, 225);
            // $this->SetTextColor(0);
            // $this->SetFont('Arial','B',7);
            // $this->SetX(5);
            // $this->Cell(25,13,utf8_decode('CÓDIGO'), 1, 0, 'C', 1);
            // $this->Cell(120,13,utf8_decode('DESCRIPCIÓN'), 1, 0, 'C', 1);
            // $this->SetXY(150,40);
            // $this->Cell(30,5,utf8_decode('SUMAS'),1,0,'C',1);
            // $this->SetXY(150,45);
            // $this->Cell(15,8,utf8_decode('DEBE'),1, 0, 'C', 1);
            // $this->SetXY(165,45);
            // $this->Cell(15,8,utf8_decode('HABER'),1, 0, 'C', 1);
            // $this->SetXY(180,40);
            // $this->Cell(30,5,utf8_decode('SALDOS'),1,1,'C',1);
            // $this->SetXY(180,45);
            // $this->Cell(15,8,utf8_decode('DEUDOR'),1, 0, 'C', 1);
            // $this->SetXY(195,45);
            // $this->Cell(15,8,utf8_decode('ACREEDOR'),1, 1, 'C', 1);

        }
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
        $this->CheckPageBreak_LM($h);
        // Forzar margen izquierdo deseado tras salto
        // $this->SetX(5);
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
    function Row_Reportes_LM($data,$code=false,$fills='',$fh='')
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
        $this->CheckPageBreak_LM($h);
        // Forzar margen izquierdo deseado tras salto
        $this->SetX(5);
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
            // $this->Rect($x,$y,$w,$h,$fills);
            //Print the text
            $this->MultiCell($w,4,$data[$i],0,$a);
            //Put the position to the right of the cell
            $this->SetXY($x+$w,$y);
        }
        //Go to the next line
        $this->Ln($h);
    }
    // function Row_Reportes_BG($data,$code=false,$fills='',$fh='',$indentacion_invertida2=0,$nivel)
    // {
    //     //Aplicar sangría al último campo (por ejemplo, nombre cuenta)
    //     // if (!empty($data)) {
    //     //     $espacios = str_repeat('   ', $indentacion_invertida2);  // 3 espacios por nivel
    //     //     $ultimoIndice = count($data) - 1;
    //     //     $data[$ultimoIndice] = $espacios . $data[$ultimoIndice];
    //     // }
    //     // echo($data[$ultimoIndice]);
    //     //Calculate the height of the row
    //     $nb=0;
    //     for($i=0;$i<count($data);$i++)
    //         $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
    //     if ($fh==""){
    //         $h=4*$nb;
    //     }else{
    //         $h=4*$nb;
    //         if ($h < $fh)
    //             $h = $fh;
    //     }
         
    //     //Issue a page break first if needed
    //     $this->CheckPageBreak_LM($h);
    //     // Forzar margen izquierdo deseado tras salto
    //     $this->SetX(12);
    //     //Draw the cells of the row
    //     for($i=0;$i<count($data);$i++)
    //     {
    //         $w=$this->widths[$i];
    //         $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
    //         //Save the current position
    //         $x=$this->GetX();
    //         $y=$this->GetY();
    //         //Draw the border
    //         $ax=$x; $ay=$y; $aw=$w; $ah=$h;
    //         // $this->Rect($x,$y,$w,$h,$fills);
    //         //Print the text
    //         // Si es la última columna y hay indentación invertida
    //         if ($i == count($data) - 1 ) {
    //             $extraX = $indentacion_invertida2 * 5;  // o el ancho que uses por nivel
    //             $this->SetX($x + $extraX);
    //             // $this->MultiCell($w,4,'xxx',0,$a);
    //         }
    //         if($nivel==1)
    //         {
    //             $this->SetFillColor(230, 230, 225);
    //             if ($i == count($data) - 1 ) {
    //                 $this->Rect($x, $y, 25, 4, 'F');
    //             }
    //             $this->SetTextColor(0);
    //             $this->SetFont('Arial','BU',7);
    //             $this->MultiCell($w,4,$data[$i],0,$a,true);
    //         }
    //         else{
    //             $this->SetFillColor(230, 230, 255);
    //             $this->SetTextColor(0);
    //             $this->SetFont('Arial','',7);
    //             $this->MultiCell($w,4,$data[$i],0,$a,false);
    //         }

    //         //Put the position to the right of the cell
    //         $this->SetXY($x+$w,$y);
    //     }
    //     //Go to the next line
    //     $this->Ln($h);
    // }
    function Row_Reportes_BG($data,$code=false,$fills='',$fh='',$indentacion_invertida2=0,$nivel)
    {
        //Aplicar sangría al último campo (por ejemplo, nombre cuenta)
        // if (!empty($data)) {
        //     $espacios = str_repeat('   ', $indentacion_invertida2);  // 3 espacios por nivel
        //     $ultimoIndice = count($data) - 1;
        //     $data[$ultimoIndice] = $espacios . $data[$ultimoIndice];
        // }
        // echo($data[$ultimoIndice]);
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
        $this->CheckPageBreak_LM($h);
        // Forzar margen izquierdo deseado tras salto
        $this->SetX(12);
        //Draw the cells of the row
        for($i=0;$i<count($data);$i++)
        {
           $w=$this->widths[$i];

           $realWidth = $w; // lo que realmente se usará

           
           if ($i == 0) {
               $realWidth += 40; // Solo se suma a la primera columna
            }
            
            // echo "Ancho real: $realWidth, Ancho original: $w\n"; // Debugging

            $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
            //Save the current position
            $x=$this->GetX();
            $y=$this->GetY();
            //Draw the border
            $ax=$x; $ay=$y; $aw=$w; $ah=$h;
            // $this->Rect($x,$y,$w,$h,$fills);
            //Print the text
            // Si es la última columna
            if ($i == count($data) - 1 ) {
                $extraX = $indentacion_invertida2 * 5;  // o el ancho que uses por nivel
                $this->SetX($x + $extraX);
                // $this->MultiCell($w,4,'xxx',0,$a);
            }
            if($nivel==1)
            {
                $this->SetFillColor(230, 230, 225);
                $this->SetTextColor(0);
                $this->SetFont('Arial','BU',7);
                if ($i == count($data) - 1 ) {
                    $this->Rect($x, $y, 25, 4, 'F');
                }
                
                $this->MultiCell($realWidth,4,$data[$i],0,$a,true);
                
            }
            else{
                $this->SetFillColor(230, 230, 255);
                $this->SetTextColor(0);
                $this->SetFont('Arial','',7);
                // $this->MultiCell($w,4,$data[$i],0,$a,false);
                $this->MultiCell($realWidth,4,$data[$i],0,$a,false);
            }

            //Put the position to the right of the cell
            $this->SetXY($x+$realWidth,$y);
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
    function CheckPageBreak_LM($h)
    {
        if ($this->GetY() + $h > $this->PageBreakTrigger) {
            // Forzar orientación y tamaño igual al primero
            $this->AddPage($this->CurOrientation, 'Letter');
            $this->SetX(5);  // si usas margen personalizado
        }
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
    function fechaformato(){
       $fecha = date('j/m/Y');
        return  $fecha;
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