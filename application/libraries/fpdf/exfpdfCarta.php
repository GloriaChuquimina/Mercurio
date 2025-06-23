<?php
 /*********************************************************************
 * exFPDF  extend FPDF v1.81                                          *
 *                                                                    *
 * Version: 2.2                                                       *
 * Date:    12-10-2017                                                *
 * Author:  Dan Machado                                               *
 * Require  FPDF v1.81, formatedstring v1.0                           *
 **********************************************************************/
include 'fpdfde.php';
include_once  "easyTable.php";

include 'formatedstring.php';
class exFPDFCarta extends FPDFDE{

   public $tituloCabecera;
   public $subtituloCabecera=null;
   public $segundoSubtituloCabecera=null;
   public $opcion_pie='PAGINADOR';
   public $fechahora_impresion='NO';
   public $fecha_impresion='NO';
   public $orientacion_pagina="LARGO";
   public $tipoReporte;
   public $rubro;
   public $condicionMaquinariaEquipos;
   public $tenencia;
   public $nombreTenencia;
   public $gestion;
   public $totalBienes;
   public $seccionReporteDejurbe;
   public $pieFirmas;
   public $estadoDeclaracion;
   public $marcaDeAguaDeclaracion;

    /*
    public $rubro;
    public function __construct() {
        parent::__construct();
        $this->mes = array('', 'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre');
    }*/

    /*function __construct()
    {
       parent::__construct();
    }*/

   public function PageBreak(){
       return $this->PageBreakTrigger;
   }

   public function current_font($c){
      if($c=='family'){
         return $this->FontFamily;
      }
      elseif($c=='style'){
         return $this->FontStyle;
      }
      elseif($c=='size'){
         return $this->FontSizePt;
      }
   }

   public function get_color($c){
      if($c=='fill'){
         return $this->FillColor;
      }
      elseif($c=='text'){
         return $this->TextColor;
      }
   }

   public function get_page_width(){
      return $this->w;
   }

   public function get_margin($c){
      if($c=='l'){
         return $this->lMargin;
      }
      elseif($c=='r'){
         return $this->rMargin;
      }
      elseif($c=='t'){
         return $this->tMargin;
      }
   }

   public function get_linewidth(){
      return $this->LineWidth;
   }

   public function get_orientation(){
      return $this->CurOrientation;
   }

   public function get_page_size()
   {
    	return $this->CurPageSize;
   }

   public function get_rotation()
   {
      return $this->CurRotation;
   }

   public function get_scale_factor()
   {
      return $this->k;
   }

   static private $hex=array('0'=>0,'1'=>1,'2'=>2,'3'=>3,'4'=>4,'5'=>5,'6'=>6,'7'=>7,'8'=>8,'9'=>9,
   'A'=>10,'B'=>11,'C'=>12,'D'=>13,'E'=>14,'F'=>15);

   public function is_rgb($str){
      $a=true;
      $tmp=explode(',', trim($str, ','));
      foreach($tmp as $color){
         if(!is_numeric($color) || $color<0 || $color>255){
            $a=false;
            break;
         }
      }
      return $a;
   }

   public function is_hex($str){
      $a=true;
      $str=strtoupper($str);
      $n=strlen($str);
      if(($n==7 || $n==4) && $str[0]=='#'){
         for($i=1; $i<$n; $i++){
            if(!isset(self::$hex[$str[$i]])){
               $a=false;
               break;
            }
         }
      }
      else{
         $a=false;
      }
      return $a;
   }

   public function hextodec($str){
      $result=array();
      $str=strtoupper(substr($str,1));
      $n=strlen($str);
      for($i=0; $i<3; $i++){
         if($n==6){
            $result[$i]=self::$hex[$str[2*$i]]*16+self::$hex[$str[2*$i+1]];
         }
         else{
            $result[$i]=self::$hex[$str[$i]]*16+self::$hex[$str[$i]];
         }
      }
      return $result;
   }
   static private $options=array('F'=>'', 'T'=>'', 'D'=>'');

   public function resetColor($str, $p='F'){
      if(isset(self::$options[$p]) && self::$options[$p]!=$str){
         self::$options[$p]=$str;
         $array=array();
         if($this->is_hex($str)){
            $array=$this->hextodec($str);
         }
         elseif($this->is_rgb($str)){
            $array=explode(',', trim($str, ','));
            for($i=0; $i<3; $i++){
               if(!isset($array[$i])){
                  $array[$i]=0;
               }
            }
         }
         else{
            $array=array(null, null, null);
            $i=0;
            $tmp=explode(' ', $str);
            foreach($tmp as $c){
               if(is_numeric($c)){
                  $array[$i]=$c*256;
                  $i++;
               }
            }
         }
         if($p=='T'){
            $this->SetTextColor($array[0],$array[1],$array[2]);
         }
         elseif($p=='D'){
            $this->SetDrawColor($array[0],$array[1], $array[2]);
         }
         elseif($p=='F'){
            $this->SetFillColor($array[0],$array[1],$array[2]);
         }
      }
   }
   static private $font_def='';

   public function resetFont($font_family, $font_style, $font_size){
      if(self::$font_def!=$font_family .'-' . $font_style . '-' .$font_size){
         self::$font_def=$font_family .'-' . $font_style . '-' .$font_size;
         $this->SetFont($font_family, $font_style, $font_size);
      }
   }

   public function resetStaticData(){
      self::$font_def='';
      self::$options=array('F'=>'', 'T'=>'', 'D'=>'');
   }

   /***********************************************************************
   *
   * Based on FPDF method SetFont
   *
   ************************************************************************/

   private function &FontData($family, $style, $size){
      if($family=='')
      $family = $this->FontFamily;
      else
      $family = strtolower($family);
      $style = strtoupper($style);
      if(strpos($style,'U')!==false){
         $this->underline = true;
         $style = str_replace('U','',$style);
      }
      if($style=='IB')
      $style = 'BI';
      $fontkey = $family.$style;
      if(!isset($this->fonts[$fontkey])){
         if($family=='arial')
         $family = 'helvetica';
         if(in_array($family,$this->CoreFonts)){
            if($family=='symbol' || $family=='zapfdingbats')
            $style = '';
            $fontkey = $family.$style;
            if(!isset($this->fonts[$fontkey]))
            $this->AddFont($family,$style);
         }
         else
         $this->Error('Undefined font: '.$family.' '.$style);
      }
      $result['FontSize'] = $size/$this->k;
      $result['CurrentFont']=&$this->fonts[$fontkey];
      return $result;
   }
    

   private function setLines(&$fstring, $p, $q){
      $parced_str=& $fstring->parced_str;
      $lines=& $fstring->lines;
      $linesmap=& $fstring->linesmap;
      $cfty=$fstring->get_current_style($p);
      $ffs=$cfty['font-family'] . $cfty['style'];
      if(!isset($fstring->used_fonts[$ffs])){
         $fstring->used_fonts[$ffs]=& $this->FontData($cfty['font-family'], $cfty['style'], $cfty['font-size']);
      }
      $cw=& $fstring->used_fonts[$ffs]['CurrentFont']['cw'];
      $wmax = $fstring->width*1000*$this->k;
      $j=count($lines)-1;
      $k=strlen($lines[$j]);
         if(!isset($linesmap[$j][0])) {
         $linesmap[$j]=array($p,$p, 0);
      }
      $sl=$cw[' ']*$cfty['font-size'];
      $x=$a=$linesmap[$j][2];
      if($k>0){
         $x+=$sl;
         $lines[$j].=' ';
         $linesmap[$j][2]+=$sl;
      }
      $u=$p;
      $t='';
      $l=$p+$q;
      $ftmp='';
      for($i=$p; $i<$l; $i++){
            if($ftmp!=$ffs){
            $cfty=$fstring->get_current_style($i);
            $ffs=$cfty['font-family'] . $cfty['style'];
            if(!isset($fstring->used_fonts[$ffs])){
               $fstring->used_fonts[$ffs]=& $this->FontData($cfty['font-family'], $cfty['style'], $cfty['font-size']);
            }
            $cw=& $fstring->used_fonts[$ffs]['CurrentFont']['cw'];
            $ftmp=$ffs;
         }
         $x+=$cw[$parced_str[$i]]*$cfty['font-size'];
         if($x>$wmax){
            if($a>0){
               $t=substr($parced_str,$p, $i-$p);
               $lines[$j]=substr($lines[$j],0,$k);
               $linesmap[$j][1]=$p-1;
               $linesmap[$j][2]=$a;
               $x-=($a+$sl);
               $a=0;
               $u=$p;
            }
            else{
               $x=$cw[$parced_str[$i]]*$cfty['font-size'];
               $t='';
               $u=$i;
            }
            $j++;
            $lines[$j]=$t;
            $linesmap[$j]=array();
            $linesmap[$j][0]=$u;
            $linesmap[$j][2]=0;
         }
         $lines[$j].=$parced_str[$i];
         $linesmap[$j][1]=$i;
         $linesmap[$j][2]=$x;
      }
      return;
   }

   public function &extMultiCell($font_family, $font_style, $font_size, $font_color, $w, $txt){
      $result=array();
      if($w==0){
         return $result;
      }
      $this->current_font=array('font-family'=>$font_family, 'style'=>$font_style, 'font-size'=>$font_size, 'font-color'=>$font_color);
      $fstring=new formatedString($txt, $w, $this->current_font);
      $word='';
      $p=0;
      $i=0;
      $n=strlen($fstring->parced_str);
      while($i<$n){
         $word.=$fstring->parced_str[$i];
         if($fstring->parced_str[$i]=="\n" || $fstring->parced_str[$i]==' ' || $i==$n-1){
            $word=trim($word);
            $this->setLines($fstring, $p, strlen($word));
            $p=$i+1;
            $word='';
            if($fstring->parced_str[$i]=="\n" && $i<$n-1){
               $z=0;
               $j=count($fstring->lines);
               $fstring->lines[$j]='';
               $fstring->linesmap[$j]=array();
            }
         }
         $i++;
      }
      if($n==0){
         return $result;
      }
      $n=count($fstring->lines);
         for($i=0; $i<$n; $i++){
         $result[$i]=$fstring->break_by_style($i);
      }
      return $result;
   }

   private function GetMixStringWidth($line){
      $w = 0;
      foreach($line['chunks'] as $i=>$chunk){
         $t=0;
         $cf=& $this->FontData($line['style'][$i]['font-family'], $line['style'][$i]['style'], $line['style'][$i]['font-size']);
         $cw=& $cf['CurrentFont']['cw'];
         $s=implode('', explode(' ',$chunk));
         $l = strlen($s);
         for($j=0;$j<$l;$j++){
            $t+=$cw[$s[$j]];
         }
         $w+=$t*$line['style'][$i]['font-size'];
      }
      return $w;
   }

   public function CellBlock($w, $lh, &$lines, $align='J'){
      if($w==0){
         return;
      }
      $ctmp='';
      $ftmp='';
      foreach($lines as $i=>$line){
         $k = $this->k;
         if($this->y+$lh*$line['height']>$this->PageBreakTrigger){
            break;
         }
         $dx=0;
         $dw=0;
         if($line['width']!=0){
            if($align=='R'){
               $dx = $w-$line['width']/($this->k*1000);
            }
            elseif($align=='C'){
               $dx = ($w-$line['width']/($this->k*1000))/2;
            }
            if($align=='J'){
               $tmp=explode(' ', implode('',$line['chunks']));
               $ns=count($tmp);
               if($ns>1){
                  $sx=implode('',$tmp);
                  $delta=$this->GetMixStringWidth($line)/($this->k*1000);
                  $dw=($w-$delta)*(1/($ns-1));
               }
            }
         }
         $xx=$this->x+$dx;
         foreach($line['chunks'] as $tj=>$txt){
            $this->resetFont($line['style'][$tj]['font-family'], $line['style'][$tj]['style'], $line['style'][$tj]['font-size']);
            $this->resetColor($line['style'][$tj]['font-color'], 'T');
            $y=$this->y+0.5*$lh*$line['height'] +0.3*$line['height']/$this->k;
            if($dw){
               $tmp=explode(' ', $txt);
               foreach($tmp as $e=>$tt){
                  if($e>0){
                     $xx+=$dw;
                     if($tt==''){
                        continue;
                     }
                  }
                  $this->Text($xx, $y, $tt);
                     if($line['style'][$tj]['href']){
                     $yr=$this->y+0.5*($lh*$line['height']-$line['height']/$this->k);
                     $this->Link($xx, $yr, $this->GetStringWidth($txt),$line['height']/$this->k, $line['style'][$tj]['href']);
                  }
                  $xx+=$this->GetStringWidth($tt);
               }
            }
            else{
               $this->Text($xx, $y, $txt);
                  if($line['style'][$tj]['href']){
                  $yr=$this->y+0.5*($lh*$line['height']-$line['height']/$this->k);
                  $this->Link($xx, $yr, $this->GetStringWidth($txt),$line['height']/$this->k, $line['style'][$tj]['href']);
               }
               $xx+=$this->GetStringWidth($txt);
            }
         }
         unset($lines[$i]);
         $this->y += $lh*$line['height'];
      }
   }


   public function Header() {
      if($this->orientacion_pagina=="ANCHO")
            $this->logoHeaderL();
      else  $this->logoHeaderP();
   }
   
   public function Footer() {
        switch ($this->opcion_pie) {
            case 'FOOTER_RUMBO_VICENTENARIO':
              //$this->Image('resources/images/logos/pie_rumbo_bicentenario.jpg', 22, 194, 187, 80,'','', '', false, 100, '', false, false, 0);
              $this->Image('resources/images/logos/pie_rumbo_bicentenario.jpg',22,194,187,80);
             break;
      }          
      switch($this->pieFirmas){
         case 'ANCHO':
               $this->SetFont('Arial', 'B', 8);
               $this->SetY(-18);
               $this->Cell(15,3,'');
               $this->Cell(80,3,utf8_decode('Encargado de Activos Fijos'),0,0,'C');
               $this->Cell(80,3,utf8_decode('Principal Autoridad Administrativa'),0,0,'C');
               $this->Cell(80,3,utf8_decode('Máxima Autoridad Ejecutiva'),0,1,'C');
               $this->Cell(15,3,'');
               $this->Cell(80,3,utf8_decode('Firma y Sello'),0,0,'C');
               $this->Cell(80,3,utf8_decode('Firma y Sello'),0,0,'C');
               $this->Cell(80,3,utf8_decode('Firma y Sello'),0,0,'C');         
            break;
         case 'LARGO':
               $this->SetFont('Arial', 'B', 8);
               $this->SetY(-18);
               $this->Cell(10,3,'');
               $this->Cell(60,3,utf8_decode('Encargado de Activos Fijos'),0,0,'C');
               $this->Cell(60,3,utf8_decode('Principal Autoridad Administrativa'),0,0,'C');
               $this->Cell(60,3,utf8_decode('Máxima Autoridad Ejecutiva'),0,1,'C');
               $this->Cell(10,3,'');
               $this->Cell(60,3,utf8_decode('Firma y Sello'),0,0,'C');
               $this->Cell(60,3,utf8_decode('Firma y Sello'),0,0,'C');
               $this->Cell(60,3,utf8_decode('Firma y Sello'),0,0,'C');  
            break;
         default:
            break;
      }

   
      $this->SetFont('Arial', 'I', 7);
      $this->SetTextColor(0);
      
      if($this->orientacion_pagina=="ANCHO"){
         $this->SetY(-15);
         $this->Cell(20, 3, utf8_decode('Página ' . $this->PageNo() . '/{nb}'), 0, 0, 'C');
      }
      else {
            $this->SetY(-15);
            //$this->Cell(20,3, utf8_decode('Página ' . $this->PageNo() . '/{nb}'), 0, 0, 'C');
         
      }
   }
   
   function logoHeaderP()
   {
      if($this->tipoReporte!='reporteDEJURBE') {
         $this->SetMargins(-10,15,-10);
      }
      $this->Image('resources/images/logos/bicentenario.jpg', 26, 6, 26,14);
      $this->Image('resources/images/logos/logo_senape_reporte.png', 68, 6, 74.5);
      $this->Image('resources/images/logos/chakana.png', 158, 3, 44);
    
      $this->Ln(1); 
    
      $this->SetFont('Arial','B',12);
      $this->SetY(23);
      $this->Cell(0,0,utf8_decode($this->tituloCabecera),0,1,'C',0);
      $this->Ln(2);
      if($this->subtituloCabecera != null)
      {
         $this->SetFont('Arial','B',10);
         $this->Cell(0,0,$this->subtituloCabecera,0,1,'C',0);
         $this->Ln(2);
      }
   
      
   }

   function logoHeaderL()
   {
      $this->Image('resources/images/logos/bicentenario.jpg', 15, 6, 30,14);
      $this->Image('resources/images/logos/logo_senape_reporte.png', 100, 6, 74.5);
      $this->Image('resources/images/logos/chakana.png', 230, 5, 38);
      $this->Ln(8);
      $this->SetTextColor(0);

      if($this->fecha_impresion != 'NO')
      {
         $this->SetY(17);
         $fecha_hoy = $this->fechacompleta();
         $this->SetFont('Arial', 'I', 6);
         $this->Cell(260, 4, utf8_decode("Fecha Impresión:").$fecha_hoy, 0, 0, 'R');
         $this->Ln(5);  
      }
      if($this->fechahora_impresion != 'NO')
      {
         $this->SetY(19);
         $fecha_hoy = $this->fechacompleta();
         $this->SetFont('Arial', 'I', 6);
         $this->Cell(260, 4, utf8_decode("Fecha Impresión:").$fecha_hoy, 0, 0, 'R');
         $this->Ln(2);  
         $mifecha = new DateTime(); 
         $hora = $mifecha->format('H:i:s');
         $this->Cell(260, 4,  utf8_decode("Hora Impresión: ").$hora, 0, 0, 'R');
         $this->Ln(5);
      }
      $this->SetFont('Times','B',12);
      $this->SetY(23);

      $this->Cell(0,0,utf8_decode($this->tituloCabecera),0,1,'C',0);
      $this->Ln(4);
      if($this->subtituloCabecera != null)
      {
         $this->SetFont('Times','B',12);
         $this->Cell(0,0,$this->subtituloCabecera,0,1,'C',0);
         $this->Ln(4);
      }
      $this->Cell(0,0,utf8_decode("GESTIÓN: ".$this->gestion),0,1,'C',0);
      $this->Ln(4);
      if($this->segundoSubtituloCabecera != null)
      {
         $this->SetFont('Times','B',12);
         $this->Cell(0,0,utf8_decode($this->segundoSubtituloCabecera),0,1,'C',0);
         $this->Ln(4);
      }
      
     
      
   }

   function fechacompleta(){
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
        return " ".$GetD['mday']." de ".$verm[$GetD['mon']].", ".$GetD['year'].""; //,  Hora: ".$hora;
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

/*******************************************
 * 
 * FUNCIONES PARA CREAR GRÁFICOS EN FPDF
 * 
 * ****************************************/
    var $legends;
    var $wLegend;
    var $sum;
    var $NbVal;

    function PieChart($w, $h, $data, $format, $colors=null)
    {
        $this->SetFont('Courier', '', 10);
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
        $this->SetFont('Courier', '', 10);
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


    // alpha: real value from 0 (transparent) to 1 (opaque)
    // bm:    blend mode, one of the following:
    //          Normal, Multiply, Screen, Overlay, Darken, Lighten, ColorDodge, ColorBurn,
    //          HardLight, SoftLight, Difference, Exclusion, Hue, Saturation, Color, Luminosity

    protected $extgstates = array();

    function SetAlpha($alpha, $bm='Normal')
    {
        // set alpha for stroking (CA) and non-stroking (ca) operations
        $gs = $this->AddExtGState(array('ca'=>$alpha, 'CA'=>$alpha, 'BM'=>'/'.$bm));
        $this->SetExtGState($gs);
    }

    function AddExtGState($parms)
    {
        $n = count($this->extgstates)+1;
        $this->extgstates[$n]['parms'] = $parms;
        return $n;
    }

    function SetExtGState($gs)
    {
        $this->_out(sprintf('/GS%d gs', $gs));
    }

    function _enddoc()
    {
        if(!empty($this->extgstates) && $this->PDFVersion<'1.4')
            $this->PDFVersion='1.4';
        parent::_enddoc();
    }

    function _putextgstates()
    {
        for ($i = 1; $i <= count($this->extgstates); $i++)
        {
            $this->_newobj();
            $this->extgstates[$i]['n'] = $this->n;
            $this->_put('<</Type /ExtGState');
            $parms = $this->extgstates[$i]['parms'];
            $this->_put(sprintf('/ca %.3F', $parms['ca']));
            $this->_put(sprintf('/CA %.3F', $parms['CA']));
            $this->_put('/BM '.$parms['BM']);
            $this->_put('>>');
            $this->_put('endobj');
        }
    }

    function _putresourcedict()
    {
        parent::_putresourcedict();
        $this->_put('/ExtGState <<');
        foreach($this->extgstates as $k=>$extgstate)
            $this->_put('/GS'.$k.' '.$extgstate['n'].' 0 R');
        $this->_put('>>');
    }

    function _putresources()
    {
        $this->_putextgstates();
        parent::_putresources();
    }

   ////////////////////////////////////////////////////////////////////////////////////////////////
   /////////////////////////////////////////////////////////////////////////////////////////////////

     var $legendsb;
    var $wLegendb;
    var $sumb;
    var $NbValb;
    var $tituloXY ;
    var $totalBarra;
    var $titulo_pie_grafica;
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

            
         /// BARRAS y
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
            //Scales Y - VERTICAL
           for ($c = 0; $c <= $nbDiv; $c++) 
           {
               $ypos = $YDiag + $hRepere * $c;
               $this->SetDrawColor(193, 188, 183);
               //$this->Line($XDiag, $ypos, $posFinalX+15, $ypos);
               $this->Line($XDiag-1, $ypos, $XDiag + 2, $ypos);
               $valor = ($nbDiv - $c) * $valIndRepere;
               $ypos = $YDiag + $hRepere * $c;
               $xpos = $XDiag - $margin - $this->GetStringWidth($valor);
               $this->Text($xpos, $ypos, $valor);
           }

            $this->SetFont('Arial', '', 5);
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
        $this->SetFont('Arial', 'B', 7);
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
        $this->SetFont('Arial', 'B', 7);
        $XFinPage = $posFinalX/2 ;
        $YFinPage =  $this->GetY() - (($this->GetY() - $YPage)/3 );
        $this->RotarTexto($XPage,$YFinPage,$this->tituloXY[0], 90);
        $this->RotarTexto($XFinPage+10,$posFinalY +12,$this->tituloXY[1], 0);
        ///////////////////////////////////////////////////////////////////////////// 
        $this->SetDrawColor(193, 188, 183);
        $this->SetXY($XPage, $this->GetY()+30 );
        $this->Rect($XPage-4, $YPage-10, $posFinalX-10 , $h+33, '');
        /////////////////////////////////////////////////////////////////////////////////////////
        
        $this->SetFont('Arial', 'I', 7);
         $this->RotarTexto( ($XDiag+20), ($yval +7 +(6*$f*2)), $this->titulo_pie_grafica, 0);

    }
    //////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////
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


    /////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////
}
?>
